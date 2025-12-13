<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Trainer;

class TrainerSeeder extends Seeder
{
    public function run()
    {
        $trainers = [
            [
                'name'             => 'Rama Pratama',
                'phone'            => '0812-3456-1001',
                'experience_years' => 5,
                'hourly_rate'      => 150000,
                'status'           => 'active',
                'bio'              => 'Spesialis strength & hypertrophy, fokus pada teknik compound.',
            ],
            [
                'name'             => 'Nadia Salsabila',
                'phone'            => '0813-2211-4488',
                'experience_years' => 4,
                'hourly_rate'      => 175000,
                'status'           => 'active',
                'bio'              => 'Instruktur yoga & mobility, sertifikasi RYT-200.',
            ],
            [
                'name'             => 'Kevin Hartono',
                'phone'            => '0819-7777-2233',
                'experience_years' => 6,
                'hourly_rate'      => 200000,
                'status'           => 'active',
                'bio'              => 'Coach HIIT & conditioning, pernah menang kompetisi cross-training.',
            ],
            [
                'name'             => 'Sheila Anggraini',
                'phone'            => '0821-8899-6655',
                'experience_years' => 3,
                'hourly_rate'      => 160000,
                'status'           => 'active',
                'bio'              => 'Fokus women strength, posture correction, dan prehab ringan.',
            ],
            [
                'name'             => 'Dimas Saputra',
                'phone'            => '0857-1234-9090',
                'experience_years' => 8,
                'hourly_rate'      => 220000,
                'status'           => 'active',
                'bio'              => 'Instruktur cycling & endurance, spesialis program fat loss.',
            ],
            [
                'name'             => 'Laras Widyastuti',
                'phone'            => '0812-9000-4455',
                'experience_years' => 7,
                'hourly_rate'      => 210000,
                'status'           => 'active',
                'bio'              => 'Pakar Pilates & core stability, fokus rehabilitasi ringan.',
            ],
            [
                'name'             => 'Andi Mahendra',
                'phone'            => '0813-7788-9911',
                'experience_years' => 2,
                'hourly_rate'      => 140000,
                'status'           => 'active',
                'bio'              => 'Calisthenics & bodyweight training, progresi pull-up & handstand.',
            ],
            [
                'name'             => 'Citra Melani',
                'phone'            => '0851-2233-7788',
                'experience_years' => 5,
                'hourly_rate'      => 180000,
                'status'           => 'active',
                'bio'              => 'Zumba & dance cardio, spesialis kelas energi tinggi.',
            ],
            [
                'name'             => 'Fajar Nugroho',
                'phone'            => '0822-1100-3344',
                'experience_years' => 4,
                'hourly_rate'      => 170000,
                'status'           => 'active',
                'bio'              => 'Functional training & kettlebell, fokus power dan koordinasi.',
            ],
            [
                'name'             => 'Putri Lestari',
                'phone'            => '0817-5566-8899',
                'experience_years' => 9,
                'hourly_rate'      => 230000,
                'status'           => 'active',
                'bio'              => 'Senior coach endurance & marathon prep, periodisasi lari.',
            ],
        ];

        foreach ($trainers as $data) {
            Trainer::updateOrCreate(
                ['name' => $data['name']],
                $data
            );
        }

        $trainerIds = Trainer::pluck('id')->all();
        $classIds   = DB::table('gym_classes')->pluck('id')->all();

        if (empty($trainerIds) || empty($classIds)) {
            return;
        }

        DB::table('class_trainers')->delete();

        $rows = [];
        foreach ($classIds as $cid) {
            $count = rand(1, min(3, count($trainerIds)));
            $selected = collect($trainerIds)->shuffle()->take($count);
            foreach ($selected as $tid) {
                $rows[] = [
                    'class_id'   => $cid,
                    'trainer_id' => $tid,
                    'role'       => 'lead',
                ];
            }
        }

        DB::table('class_trainers')->insert($rows);
    }
}
