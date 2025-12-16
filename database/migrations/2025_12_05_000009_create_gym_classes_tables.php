<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGymClassesTables extends Migration
{
    public function up()
    {
        Schema::create('gym_classes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('level')->nullable();
            $table->unsignedSmallInteger('capacity')->default(0);
            $table->string('location')->nullable();
            $table->dateTime('start_at');
            $table->dateTime('end_at');
            $table->string('type')->nullable();
            $table->enum('status', ['Scheduled', 'Cancelled', 'Done'])->default('Scheduled');
            $table->timestamps();

            $table->index(['start_at', 'status']);
        });

        Schema::create('class_trainers', function (Blueprint $table) {
            $table->unsignedBigInteger('class_id');
            $table->unsignedBigInteger('trainer_id');
            $table->enum('role', ['lead', 'assistant'])->default('lead');

            $table->primary(['class_id', 'trainer_id']);
            $table->foreign('class_id')->references('id')->on('gym_classes')->onDelete('cascade');
            $table->foreign('trainer_id')->references('id')->on('trainers')->onDelete('cascade');
        });

        Schema::create('class_bookings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('class_id');
            $table->unsignedBigInteger('member_id');
            $table->enum('status', ['booked', 'cancelled', 'attended', 'no_show'])->default('booked');
            $table->dateTime('checked_in_at')->nullable();
            $table->timestamps();

            $table->unique(['class_id', 'member_id']);

            $table->foreign('class_id')->references('id')->on('gym_classes')->onDelete('cascade');
            $table->foreign('member_id')->references('id')->on('member_gym')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('class_bookings');
        Schema::dropIfExists('class_trainers');
        Schema::dropIfExists('gym_classes');
    }
}
