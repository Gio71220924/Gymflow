<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('member_gym', function (Blueprint $table) {
            // letakkan setelah notes biar rapi, bebas geser sesuai selera
            $table->varchar('foto_profil', 200)->nullable()->after('notes');
        });
    }

    public function down(): void
    {
        Schema::table('member_gym', function (Blueprint $table) {
            $table->dropColumn('foto_profil');
        });
    }
};
