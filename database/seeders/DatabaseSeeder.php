<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            RoleSeeder::class,
            AdminUserSeeder::class,
            TimeSlotSeeder::class,
            TeacherSeeder::class,
            StudentGroupSeeder::class,
            BuildingSeeder::class,
            ClassroomSeeder::class,
            AvailabilitySeeder::class,
            StudentsSeeder::class,
            ActivitiesSeeder::class,
            MaintenanceSeeder::class,
        ]);
    }
}