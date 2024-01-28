<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->isLocal()) {
            $user = \App\Models\User::factory()->create([
                'name'  => 'Test User',
                'email' => 'test@example.com',
            ]);
            $user->tokens()->create([
                'name'      => "local token",
                'token'     => hash('sha256', "my-secure-token"),
                'abilities' => ['*'],
            ]);
        }

        $this->call(WeatherForecasterSeeder::class);
        $this->call(LocationSeeder::class);
    }
}
