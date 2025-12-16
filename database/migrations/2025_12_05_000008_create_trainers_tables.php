<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrainersTables extends Migration
{
    public function up()
    {
        Schema::create('trainers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('photo_url')->nullable();
            $table->unsignedTinyInteger('experience_years')->default(0);
            $table->decimal('rating_avg', 3, 2)->default(0);
            $table->decimal('hourly_rate', 10, 2)->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->text('bio')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });

        Schema::create('trainer_specialties', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('trainer_specialty_map', function (Blueprint $table) {
            $table->unsignedBigInteger('trainer_id');
            $table->unsignedBigInteger('specialty_id');

            $table->primary(['trainer_id', 'specialty_id']);

            $table->foreign('trainer_id')->references('id')->on('trainers')->onDelete('cascade');
            $table->foreign('specialty_id')->references('id')->on('trainer_specialties')->onDelete('cascade');
        });

        Schema::create('trainer_certifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('trainer_id');
            $table->string('name');
            $table->string('issuer')->nullable();
            $table->date('issued_at')->nullable();
            $table->date('expires_at')->nullable();
            $table->timestamps();

            $table->foreign('trainer_id')->references('id')->on('trainers')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('trainer_certifications');
        Schema::dropIfExists('trainer_specialty_map');
        Schema::dropIfExists('trainer_specialties');
        Schema::dropIfExists('trainers');
    }
}
