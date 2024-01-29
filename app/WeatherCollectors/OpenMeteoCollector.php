<?php

namespace App\WeatherCollectors;

use App\Models\Location;
use App\Models\WeatherRecord;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class OpenMeteoCollector extends WeatherCollector
{
    private function http(): PendingRequest
    {
        return Http::baseUrl('https://api.open-meteo.com/v1/')
            ->withQueryParameters(['apikey' => $this->getSetting('api_key')]); // null for demo for OpenMeteo
    }

    public function retrieveForecast(Location $location, array $options = []): Collection
    {
        $response = $this->http()->get('forecast', [
            'latitude'      => $location->lat,
            'longitude'     => $location->lng,
            'hourly'        => 'temperature_2m,precipitation',
            'daily'         => 'temperature_2m_max,precipitation_sum',
            'timezone'      => $location->timezone,
            'forecast_days' => $options['days'] ?? 1,
        ])->throw()
            ->json();

        return $this->parseDailyForecasts($location, $response)
            ->merge($this->parseHourlyForecast($location, $response));
    }

    /**
     * @return Collection<WeatherRecord>
     */
    private function parseDailyForecasts(Location $location, array $response): Collection
    {
        $records = collect();

        for ($i = 0; $i < count($response['daily']['time'] ?? []); $i++) {
            $records->add($record = new WeatherRecord([
                'temperature'        => data_get($response, "daily.temperature_2m_max.$i"),
                'precipitation'      => data_get($response, "daily.precipitation_sum.$i"),
                'period_type'        => 'day',
                'reference_datetime' => data_get($response, "daily.time.$i"),
            ]));
            $record->location()->associate($location);
            $record->weather_forecaster()->associate($this->weather_forecaster);
        }

        return $records;
    }

    /**
     * @return Collection<WeatherRecord>
     */
    private function parseHourlyForecast(Location $location, array $response): Collection
    {
        $records = collect();

        for ($i = 0; $i < count($response['hourly']['time'] ?? []); $i++) {
            $records->add($record = new WeatherRecord([
                'temperature'        => data_get($response, "hourly.temperature_2m.$i"),
                'precipitation'      => data_get($response, "hourly.precipitation.$i"),
                'period_type'        => 'hour',
                'reference_datetime' => data_get($response, "hourly.time.$i"),
            ]));
            $record->location()->associate($location);
            $record->weather_forecaster()->associate($this->weather_forecaster);
        }

        return $records;
    }
}
