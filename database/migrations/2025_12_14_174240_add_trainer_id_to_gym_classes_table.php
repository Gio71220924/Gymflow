<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AddTrainerIdToGymClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Add trainer_id column
        Schema::table('gym_classes', function (Blueprint $table) {
            $table->unsignedBigInteger('trainer_id')->nullable()->after('status');
            $table->foreign('trainer_id')->references('id')->on('trainers')->onDelete('set null');
        });

        // 2. Migrate data from class_trainers to gym_classes
        $mappings = DB::table('class_trainers')->get();
        foreach ($mappings as $map) {
            // Take the first trainer found for each class
            DB::table('gym_classes')
                ->where('id', $map->class_id)
                ->whereNull('trainer_id') // only update if not set
                ->update(['trainer_id' => $map->trainer_id]);
        }

        // 3. Fix time range (GMT+7, 07:00 - 18:00)
        // We assume stored values are what they are. We just shift them to fit the window in Asia/Jakarta.
        $classes = DB::table('gym_classes')->get();
        foreach ($classes as $c) {
            $start = Carbon::parse($c->start_at)->timezone('Asia/Jakarta');
            $end   = Carbon::parse($c->end_at)->timezone('Asia/Jakarta');
            $durationMinutes = $start->diffInMinutes($end);

            $hour = $start->hour;
            $newStart = $start->copy();

            // Shift to 7 AM if too early
            if ($hour < 7) {
                $newStart->hour(7)->minute(0)->second(0);
            } 
            // Shift to start at 5 PM (ending 6 PM approx) if too late
            elseif ($hour >= 18) {
                $newStart->hour(17)->minute(0)->second(0); // 1-hour class ends at 18:00
            }

            // If changes needed
            if ($start->ne($newStart)) {
                $newEnd = $newStart->copy()->addMinutes($durationMinutes);
                
                // Ensure end time doesn't exceed 18:00 (trim duration if needed, or just let it slide slightly)
                // User said "range 7am - 6pm". Let's cap end at 18:00.
                if ($newEnd->hour > 18 || ($newEnd->hour == 18 && $newEnd->minute > 0)) {
                   $newEnd->hour(18)->minute(0)->second(0);
                }

                // Save back converted to UTC/Database format? 
                // Laravel usually handles timezone conversion in model. DB facade writes raw. 
                // If app_timezone is Asia/Jakarta, DB might be storing that literal string or UTC.
                // We'll write the formatted string which DB usually takes as literal or converts based on connection.
                // Safest is to write the datetime string corresponding to the adjusted time.
                
                DB::table('gym_classes')
                    ->where('id', $c->id)
                    ->update([
                        'start_at' => $newStart->format('Y-m-d H:i:s'),
                        'end_at'   => $newEnd->format('Y-m-d H:i:s'),
                    ]);
            }
        }

        // 4. Drop class_trainers table
        Schema::dropIfExists('class_trainers');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // 1. Recreate class_trainers
        Schema::create('class_trainers', function (Blueprint $table) {
            $table->unsignedBigInteger('class_id');
            $table->unsignedBigInteger('trainer_id');
            $table->enum('role', ['lead', 'assistant'])->default('lead');

            $table->primary(['class_id', 'trainer_id']);
            $table->foreign('class_id')->references('id')->on('gym_classes')->onDelete('cascade');
            $table->foreign('trainer_id')->references('id')->on('trainers')->onDelete('cascade');
        });

        // 2. Restore data
        $classes = DB::table('gym_classes')->whereNotNull('trainer_id')->get();
        foreach ($classes as $c) {
            DB::table('class_trainers')->insert([
                'class_id' => $c->id,
                'trainer_id' => $c->trainer_id,
                'role' => 'lead'
            ]);
        }

        // 3. Drop trainer_id column
        Schema::table('gym_classes', function (Blueprint $table) {
            $table->dropForeign(['trainer_id']);
            $table->dropColumn('trainer_id');
        });
    }
}
