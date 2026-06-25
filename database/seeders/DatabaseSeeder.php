<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Schedule;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            ['name' => 'Test User', 'password' => bcrypt('password')]
        );

        $schedules = [
            [
                'route' => 'Bandung (Leuwi Panjang) - Jakarta (Kampung Rambutan)',
                'departure_time' => '2026-06-05 08:00:00',
                'facilities' => 'AC, WiFi, Colokan Listrik',
                'price' => 120000,
            ],
            [
                'route' => 'Bandung (Cicaheum) - Surabaya (Bungurasih)',
                'departure_time' => '2026-06-05 15:30:00',
                'facilities' => 'AC, TV, Toilet, Makan Malam',
                'price' => 250000,
            ],
            [
                'route' => 'Jakarta (Lebak Bulus) - Yogyakarta (Giwangan)',
                'departure_time' => '2026-06-05 18:00:00',
                'facilities' => 'AC, Reclining Seat, Snack',
                'price' => 180000,
            ],
        ];

        foreach ($schedules as $schedule) {
            Schedule::firstOrCreate(['route' => $schedule['route']], $schedule);
        }
    }
}