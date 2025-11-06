<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Faker\Factory as Faker;

class MemberGym_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now   = Carbon::now();
        $faker = Faker::create('id_ID');

        //Sesuaikan dengan nama tabel
        // DB::table('member_gym')->insert([
        //     [
        //     'id_member'          => 'PL2500001',
        //     'nama_member'         => 'John Doe',
        //     'email_member'        => 'Johndoe@gmail.com',
        //     'nomor_telepon_member'=> '081234567890',
        //     'tanggal_lahir'       => '1990-01-15',
        //     'gender'              => 'Laki-laki',
        //     'tanggal_join'        => '2025-10-30',
        //     'membership_plan'     => 'premium',
        //     'durasi_plan'         => 12,
        //     'start_date'          => '2025-10-30',
        //     'end_date'            => '2026-10-30',
        //     'status_membership'   => "Aktif",
        //     'notes'               => 'Member since 2025',
        //     'created_at'            => $now,
        //     'updated_at'            => $now,
        //     ]
        // ]);

        // Generate 50 Fake data menggunakan Faker
        $prefix = 'PL' . $now->format('y'); 
        for ($i = 2; $i <= 71; $i++) {
            $kodeUrut = str_pad((string)$i, 5, '0', STR_PAD_LEFT);   
            $idMember = $prefix . $kodeUrut;

            $plan   = $faker->randomElement(['basic','premium']);    
            $durasi = $faker->randomElement([1,3,6,12]);

            // start dalam 10 bulan terakhir
            $start  = Carbon::instance($faker->dateTimeBetween('-10 months', 'now'))->startOfDay();
            $end    = (clone $start)->addMonths($durasi);

            // tanggal lahir 
            $dob    = Carbon::instance($faker->dateTimeBetween('-60 years', '-18 years'))->toDateString();

            // status: 10% suspended; sisanya aktif/tdk aktif berdasar end_date
            if ($faker->boolean(10)) {
                $status = 'Suspended';
            } else {
                $status = $end->isFuture() ? 'Aktif' : 'Tidak Aktif';
            }

            DB::table('member_gym')->insert([
                'id_member'             => $idMember,
                'nama_member'           => $faker->name,
                'email_member'          => $faker->unique()->safeEmail,
                'nomor_telepon_member'  => preg_replace('/\D+/', '', $faker->phoneNumber),
                'tanggal_lahir'         => $dob,
                'gender'                => $faker->randomElement(['Laki-laki','Perempuan']),
                'tanggal_join'          => $start->toDateString(),
                'membership_plan'       => $plan,
                'durasi_plan'           => $durasi,
                'start_date'            => $start->toDateString(),
                'end_date'              => $end->toDateString(),
                'status_membership'     => $status,
                'notes'                 => $faker->optional()->sentence,
                'created_at'            => $now,
                'updated_at'            => $now,
            ]);
        }

    }
}
