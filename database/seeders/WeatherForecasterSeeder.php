<?php

namespace Database\Seeders;

use App\Models\WeatherForecaster;
use Illuminate\Database\Seeder;

class WeatherForecasterSeeder extends Seeder
{

    public function run(): void
    {
        collect([
            ['name' => 'Open Meteo', 'web_url' => 'https://open-meteo.com', 'collector_class_key' => 'open-meteo'],
            ['name' => 'Weather API', 'web_url' => 'https://www.weatherapi.com', 'collector_class_key' => 'weather-api'],
        ])->each(WeatherForecaster::create(...));
    }
}
