<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberGym extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_gym', function (Blueprint $table) {
            //Atribut Tabel
            //Profil Member
            $table->bigIncrements('id');
            $table->string('id_member', 10)->unique();
            $table->string('nama_member', 100);
            $table->string('email_member', 100)->unique();
            $table->string('nomor_telepon_member', 20);
            $table->date('tanggal_lahir');
            $table->enum('gender', ['Laki-laki','Perempuan']);
            //Informasi Keanggotaan
            $table->date('tanggal_join');
            $table->enum('membership_plan', ['basic','premium']);
            $table->unsignedInteger('durasi_plan')->default(1);
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status_membership', ['Aktif', 'Tidak Aktif', 'Suspended']);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('member_gym');
    }
}
