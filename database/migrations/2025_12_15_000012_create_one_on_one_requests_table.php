<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOneOnOneRequestsTable extends Migration
{
    public function up()
    {
        Schema::create('one_on_one_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('member_id');
            $table->unsignedBigInteger('trainer_id');
            $table->date('preferred_date');
            $table->string('preferred_time', 16);
            $table->string('location');
            $table->text('note')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_note')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamps();

            $table->index(['member_id', 'status']);
            $table->index(['trainer_id', 'preferred_date']);
            $table->foreign('member_id')->references('id')->on('member_gym')->onDelete('cascade');
            $table->foreign('trainer_id')->references('id')->on('trainers')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('one_on_one_requests');
    }
}
