<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // intermediary test users
        Role::create(['name' => 'Intermediary']);
        $intermediaries = User::factory()
            ->count(3)
            ->sequence(fn($sequence) => [
                'name' => 'Intermediary User ' . ($sequence->index + 1),
                'email' => 'intermediary' . ($sequence->index + 1) . '@example.test',
            ])
            ->create();
        foreach ($intermediaries as $intermediary)
            $intermediary->assignRole('Intermediary');

        // delivery test users
        Role::create(['name' => 'Delivery']);
        $deliveries = User::factory()
            ->count(3)
            ->sequence(fn($sequence) => [
                'name' => 'Delivery User ' . ($sequence->index + 1),
                'email' => 'delivery' . ($sequence->index + 1) . '@example.test',
            ])
            ->create();
        foreach ($deliveries as $delivery)
            $delivery->assignRole('Delivery');
    }
}
