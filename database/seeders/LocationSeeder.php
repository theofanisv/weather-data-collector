<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->isLocal()) {
            Location::factory()->count(3)->create();
        }
    }
}
