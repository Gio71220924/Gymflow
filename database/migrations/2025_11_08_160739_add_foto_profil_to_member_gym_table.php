<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFotoProfilToMemberGymTable extends Migration
{
    public function up()
    {
        Schema::table('member_gym', function (Blueprint $table) {
            $table->string('foto_profil', 255)->nullable()->after('notes'); // VARCHAR(255) NULL
        });
    }

    public function down()
    {
        Schema::table('member_gym', function (Blueprint $table) {
            $table->dropColumn('foto_profil');
        });
    }
}
