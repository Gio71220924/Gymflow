<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Seed a default admin account.
     *
     * @return void
     */
    public function run()
    {
        $user = User::firstOrNew(['email' => 'admin@gmail.com']);

        $user->name   = 'admin';
        $user->role   = User::ROLE_SUPER_ADMIN;
        $user->status = User::STATUS_ACTIVE;
        $user->password = Hash::make('admin123');

        $user->save();
    }
}
