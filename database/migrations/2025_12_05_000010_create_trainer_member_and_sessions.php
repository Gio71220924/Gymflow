<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrainerMemberAndSessions extends Migration
{
    public function up()
    {
        Schema::create('trainer_member', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('trainer_id');
            $table->unsignedBigInteger('member_id');
            $table->date('start_at')->nullable();
            $table->date('end_at')->nullable();
            $table->enum('status', ['active', 'paused', 'ended'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['trainer_id', 'member_id', 'status']);
            $table->foreign('trainer_id')->references('id')->on('trainers')->onDelete('cascade');
            $table->foreign('member_id')->references('id')->on('member_gym')->onDelete('cascade');
        });

        Schema::create('pt_sessions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('trainer_member_id');
            $table->dateTime('scheduled_start');
            $table->dateTime('scheduled_end');
            $table->string('location')->nullable();
            $table->enum('status', ['scheduled', 'completed', 'cancelled', 'no_show'])->default('scheduled');
            $table->dateTime('completed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['trainer_member_id', 'scheduled_start']);
            $table->foreign('trainer_member_id')->references('id')->on('trainer_member')->onDelete('cascade');
        });

        Schema::create('pt_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('pt_session_id')->nullable();
            $table->unsignedBigInteger('trainer_member_id')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('IDR');
            $table->string('method')->nullable();
            $table->enum('status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->dateTime('paid_at')->nullable();
            $table->timestamps();

            $table->foreign('pt_session_id')->references('id')->on('pt_sessions')->onDelete('set null');
            $table->foreign('trainer_member_id')->references('id')->on('trainer_member')->onDelete('set null');
            $table->index(['trainer_member_id', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('pt_payments');
        Schema::dropIfExists('pt_sessions');
        Schema::dropIfExists('trainer_member');
    }
}
