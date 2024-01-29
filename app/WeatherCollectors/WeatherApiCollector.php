<?php

namespace App\WeatherCollectors;

use App\Models\Location;
use App\Models\WeatherRecord;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class WeatherApiCollector extends WeatherCollector
{

    protected function http(): PendingRequest
    {
        return Http::baseUrl('https://api.weatherapi.com/v1')
            ->withQueryParameters(['key' => $this->getSetting('api_key')]);
    }

    public function retrieveForecast(Location $location, array $options = []): Collection
    {
        $response = $this->http()->get('/forecast.json', [
            'q'    => $location->coordinates_as_string ?? $location->full_address,
            'days' => $options['days'] ?? 1,
        ])->throw()
            ->json();

        $records = collect();

        for ($day = 0; $day < count($response['forecast']['forecastday']); $day++) {
            $records = $records->add(
                $this->parseDailyForecast($location, $response, $day)
            )->merge(
                $this->parseHourlyForecasts($location, $response, $day)
            );
        }

        return $records;
    }

    private function parseDailyForecast(Location $location, array $response, int $day_index): WeatherRecord
    {
        $day_record = data_get($response, "forecast.forecastday.$day_index");
        $day_record = new WeatherRecord([
            'temperature'        => data_get($day_record, 'day.avgtemp_c'),
            'precipitation'      => data_get($day_record, 'day.totalprecip_mm'),
            'period_type'        => 'day',
            'reference_datetime' => $day_record['date'],
        ]);
        $day_record->location()->associate($location);
        $day_record->weather_forecaster()->associate($this->weather_forecaster);

        return $day_record;
    }

    /**
     * @return Collection<WeatherRecord>
     */
    private function parseHourlyForecasts(Location $location, array $response, int $day_index): Collection
    {
        return collect(data_get($response, "forecast.forecastday.$day_index.hour"))
            ->map(function (array $hour_data) use ($location) {

                $record = new WeatherRecord([
                    'temperature'        => $hour_data['temp_c'],
                    'precipitation'      => $hour_data['precip_mm'],
                    'period_type'        => 'hour',
                    'reference_datetime' => $hour_data['time'],
                ]);

                $record->location()->associate($location);
                $record->weather_forecaster()->associate($this->weather_forecaster);

                return $record;
            });
    }
}
