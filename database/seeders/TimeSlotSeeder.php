<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TimeSlot;

class TimeSlotSeeder extends Seeder
{
    public function run()
    {
        $timeSlots = [];
        
        // Horarios universitarios típicos
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        $morningSlots = [
            ['08:00', '10:00'],
            ['10:00', '12:00'],
            ['14:00', '16:00'], // Tarde
        ];
        $afternoonSlots = [
            ['16:00', '18:00'],
        ];
        $nightSlots = [
            ['18:00', '20:00'],
            ['20:00', '22:00'],
        ];

        foreach ($days as $day) {
            // Jornada matutina
            foreach ($morningSlots as $index => $slot) {
                $shift = $slot[0] >= '14:00' ? 'afternoon' : 'morning';
                $timeSlots[] = [
                    'name' => ucfirst($day) . ' ' . $slot[0] . '-' . $slot[1],
                    'day' => $day,
                    'start_time' => $slot[0],
                    'end_time' => $slot[1],
                    'shift' => $shift,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Jornada nocturna
            foreach ($nightSlots as $slot) {
                $timeSlots[] = [
                    'name' => ucfirst($day) . ' ' . $slot[0] . '-' . $slot[1],
                    'day' => $day,
                    'start_time' => $slot[0],
                    'end_time' => $slot[1],
                    'shift' => 'night',
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        TimeSlot::insert($timeSlots);
    }
}