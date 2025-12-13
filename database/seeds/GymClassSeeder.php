<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GymClassSeeder extends Seeder
{
    /**
     * Seed 50 gym classes with varied schedule, level, and status.
     */
    public function run()
    {
        $baseDate   = Carbon::now('Asia/Jakarta')->startOfWeek(Carbon::MONDAY)->setTime(6, 0, 0);
        $levels     = ['Beginner', 'Intermediate', 'Advanced'];
        $types      = ['Cardio', 'Strength', 'HIIT', 'Yoga', 'Pilates', 'Mobility', 'Cycling'];
        $locations  = ['Studio A', 'Studio B', 'Studio C', 'Outdoor', 'Pool', 'Functional Area'];
        $timeSlots  = [
            [6, 0],
            [8, 0],
            [10, 0],
            [17, 0],
            [19, 0],
        ];

        $classes = [];
        for ($i = 0; $i < 50; $i++) {
            $dayOffset = $i % 21; // isi 3 pekan agar jadwal tersebar
            $day       = $baseDate->copy()->addDays($dayOffset);

            $slotIdx = $i % count($timeSlots);
            $start   = $day->copy()->setTime($timeSlots[$slotIdx][0], $timeSlots[$slotIdx][1]);
            $end     = $start->copy()->addMinutes(60 + (($i % 3) * 15)); // 60-90 menit

            $status = $start->isFuture()
                ? 'Scheduled'
                : 'Done';

            $classes[] = [
                'title'       => $types[$i % count($types)] . ' Session #' . ($i + 1),
                'description' => 'Sesi fokus pada teknik dasar dan conditioning untuk meningkatkan performa.',
                'level'       => $levels[$i % count($levels)],
                'capacity'    => 12 + ($i % 10) * 2,
                'location'    => $locations[$i % count($locations)],
                'start_at'    => $start,
                'end_at'      => $end,
                'type'        => $types[$i % count($types)],
                'status'      => $status,
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ];
        }

        DB::table('gym_classes')->insert($classes);
    }
}
