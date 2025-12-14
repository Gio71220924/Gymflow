<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPhotoToGymClassesTable extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('gym_classes', 'photo')) {
            Schema::table('gym_classes', function (Blueprint $table) {
                $table->string('photo')->nullable()->after('type');
            });
        }
    }

    public function down()
    {
        Schema::table('gym_classes', function (Blueprint $table) {
            if (Schema::hasColumn('gym_classes', 'photo')) {
                $table->dropColumn('photo');
            }
        });
    }
}
