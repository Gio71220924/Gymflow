<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberMembershipsTable extends Migration
{
    public function up()
    {
        Schema::create('member_memberships', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('member_id');
            $table->unsignedBigInteger('plan_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['aktif', 'selesai', 'dibatalkan'])->default('aktif');
            $table->enum('pembayaran_status', ['menunggu', 'lunas', 'gagal'])->default('menunggu');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('member_id')->references('id')->on('member_gym')->onDelete('cascade');
            $table->foreign('plan_id')->references('id')->on('membership_plans')->onDelete('restrict');
            $table->index(['member_id', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('member_memberships');
    }
}
