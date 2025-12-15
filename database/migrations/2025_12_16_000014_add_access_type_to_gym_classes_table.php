<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAccessTypeToGymClassesTable extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('gym_classes', 'access_type')) {
            Schema::table('gym_classes', function (Blueprint $table) {
                $table->enum('access_type', ['all', 'premium_only'])
                    ->default('all')
                    ->after('type');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('gym_classes', 'access_type')) {
            Schema::table('gym_classes', function (Blueprint $table) {
                $table->dropColumn('access_type');
            });
        }
    }
}
