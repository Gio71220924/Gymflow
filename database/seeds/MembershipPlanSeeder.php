<?php

use Illuminate\Database\Seeder;
use App\MembershipPlan;

class MembershipPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $plans = [
            [
                'nama'         => 'basic',
                'harga'        => 150000,
                'durasi_bulan' => 1,
                'benefit'      => 'Akses basic bulanan',
                'status'       => 'aktif',
            ],
            [
                'nama'         => 'premium',
                'harga'        => 300000,
                'durasi_bulan' => 12,
                'benefit'      => 'Akses premium tahunan',
                'status'       => 'aktif',
            ],
        ];

        foreach ($plans as $plan) {
            MembershipPlan::updateOrCreate(
                ['nama' => $plan['nama']],
                $plan
            );
        }
    }
}
