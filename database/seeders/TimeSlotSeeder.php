<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TimeSlot;

class TimeSlotSeeder extends Seeder
{
    public function run()
    {
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];

        // BLOQUES DIURNOS (8:00 - 18:00)
        $dayBlocks = [
            ['start' => '08:00:00', 'end' => '10:00:00', 'shift' => 'morning', 'schedule_type' => 'day'],
            ['start' => '10:00:00', 'end' => '12:00:00', 'shift' => 'morning', 'schedule_type' => 'day'],
            ['start' => '14:00:00', 'end' => '16:00:00', 'shift' => 'afternoon', 'schedule_type' => 'day'],
            ['start' => '16:00:00', 'end' => '18:00:00', 'shift' => 'afternoon', 'schedule_type' => 'day'],
        ];

        // BLOQUES NOCTURNOS (18:00 - 22:00)
        $nightBlocks = [
            ['start' => '18:00:00', 'end' => '20:00:00', 'shift' => 'night', 'schedule_type' => 'night'],
            ['start' => '20:00:00', 'end' => '22:00:00', 'shift' => 'night', 'schedule_type' => 'night'],
        ];

        // Crear time slots para cada día
        foreach ($days as $day) {
            // Bloques diurnos
            foreach ($dayBlocks as $i => $block) {
                $blockNum = $i + 1;
                TimeSlot::firstOrCreate(
                    ['day' => $day, 'start_time' => $block['start'], 'shift' => $block['shift']],
                    [
                        'name' => "Bloque {$blockNum}",
                        'end_time' => $block['end'],
                        'shift' => $block['shift'],
                        'schedule_type' => $block['schedule_type'],
                        'duration_minutes' => 120,
                        'is_active' => true,
                    ]
                );
            }

            // Bloques nocturnos
            foreach ($nightBlocks as $i => $block) {
                $blockNum = $i + 5;
                TimeSlot::firstOrCreate(
                    ['day' => $day, 'start_time' => $block['start'], 'shift' => $block['shift']],
                    [
                        'name' => "Bloque {$blockNum}",
                        'end_time' => $block['end'],
                        'shift' => $block['shift'],
                        'schedule_type' => $block['schedule_type'],
                        'duration_minutes' => 120,
                        'is_active' => true,
                    ]
                );
            }
        }

        echo "✓ Bloques horarios actualizados con schedule_type\n";
    }
}

