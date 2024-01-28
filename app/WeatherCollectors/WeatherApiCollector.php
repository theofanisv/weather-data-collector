<?php

namespace App\WeatherCollectors;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class WeatherApiCollector extends WeatherCollector
{

    public function collectForecast(): EloquentCollection
    {
        throw new \Exception("Not implemented yet."); // TODO: Implement collectForecast() method.
    }
}
