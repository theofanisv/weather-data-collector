<?php

namespace App\WeatherCollectors;

use App\Models\Location;
use App\Models\WeatherForecaster;
use App\Models\WeatherRecord;
use Illuminate\Support\Collection;

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

    protected WeatherForecaster $weather_forecaster;

    public function forForecaster(WeatherForecaster $weather_forecaster): static
    {
        $this->weather_forecaster = $weather_forecaster;

        return $this;
    }

    protected function getSetting(string $key): mixed
    {
        return data_get($this->weather_forecaster->settings, $key);
    }


    /**
     * @return Collection<WeatherRecord>
     */
    abstract public function retrieveForecast(Location $location): Collection; // EloquentCollection here causes bugs later
}
