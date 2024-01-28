<?php

namespace App\WeatherCollectors;

use App\Models\WeatherRecord;

class OpenMeteoCollector extends WeatherCollector
{

    public function collectForecast(): WeatherRecord
    {
        throw new \Exception("Not implemented yet."); // TODO: Implement collectForecast() method.
    }
}
