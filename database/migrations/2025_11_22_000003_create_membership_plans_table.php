<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembershipPlansTable extends Migration
{
    public function up()
    {
        Schema::create('membership_plans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama', 100)->unique();
            $table->decimal('harga', 12, 2);
            $table->unsignedInteger('durasi_bulan');
            $table->text('benefit')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('membership_plans');
    }
}
