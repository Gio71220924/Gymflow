<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class IncreaseIdMemberLength extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `member_gym` MODIFY `id_member` VARCHAR(20) NOT NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Perhatikan: rollback akan gagal jika ada data lebih dari 10 karakter.
        DB::statement('ALTER TABLE `member_gym` MODIFY `id_member` VARCHAR(10) NOT NULL');
    }
}
