<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrainerAvailabilityRatesFeedback extends Migration
{
    public function up()
    {
        Schema::create('trainer_availability', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('trainer_id');
            $table->unsignedTinyInteger('weekday')->nullable();
            $table->date('date_override')->nullable();
            $table->time('start_time');
            $table->time('end_time');
            $table->enum('status', ['available', 'unavailable'])->default('available');
            $table->timestamps();

            $table->foreign('trainer_id')->references('id')->on('trainers')->onDelete('cascade');
            $table->index(['trainer_id', 'weekday', 'date_override']);
        });

        Schema::create('trainer_rates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('trainer_id');
            $table->string('rate_type')->default('default');
            $table->decimal('hourly_rate', 10, 2);
            $table->date('valid_from')->nullable();
            $table->date('valid_to')->nullable();
            $table->timestamps();

            $table->foreign('trainer_id')->references('id')->on('trainers')->onDelete('cascade');
            $table->unique(['trainer_id', 'rate_type', 'valid_from']);
        });

        Schema::create('trainer_feedback', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('trainer_id');
            $table->unsignedBigInteger('member_id');
            $table->unsignedBigInteger('pt_session_id')->nullable();
            $table->unsignedTinyInteger('rating');
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->foreign('trainer_id')->references('id')->on('trainers')->onDelete('cascade');
            $table->foreign('member_id')->references('id')->on('member_gym')->onDelete('cascade');
            $table->foreign('pt_session_id')->references('id')->on('pt_sessions')->onDelete('set null');
            $table->unique(['trainer_id', 'member_id', 'pt_session_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('trainer_feedback');
        Schema::dropIfExists('trainer_rates');
        Schema::dropIfExists('trainer_availability');
    }
}
