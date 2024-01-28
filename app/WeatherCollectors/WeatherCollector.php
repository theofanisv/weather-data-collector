<?php

namespace App\WeatherCollectors;

use App\Models\WeatherRecord;

abstract class WeatherCollector
{
    /**
     * Key will be stored to db as reference instead of using the full class name.
     * Because we might need to move/rename the class in future so that we do not have to migrate db data.
     */
    public final const COLLECTORS = [
        'weather-api' => WeatherApiCollector::class,
        'open-meteo'  => OpenMeteoCollector::class,
    ];

    abstract public function collectForecast(): WeatherRecord;
}
