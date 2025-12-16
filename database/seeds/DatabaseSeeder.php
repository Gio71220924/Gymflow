<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        require_once __DIR__ . '/TrainerSeeder.php';

        $this->call([
            MembershipPlanSeeder::class,
            AdminUserSeeder::class,
            GymClassSeeder::class,
            TrainerSeeder::class,
        ]);
    }
}
