<?php

namespace App\Console\Commands;

use App\Models\Location;
use App\Models\WeatherForecaster;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class CollectWeatherForecast extends Command
{
    protected $signature = 'weather:collect-forecasts {--locations=* : Limit to these location ids} {--forecasters=* : Limit to these forecaster ids}';

    protected $description = 'Collect weather data for the specified or all locations by the specified or all forecasters.';

    public function handle()
    {
        $forecasters = $this->option('forecasters');
        if (count($forecasters) == 1) {// Allow comma-separated values when only one provided
            $forecasters = explode(',', head($forecasters));
        }
        $forecasters = WeatherForecaster::query()
            ->when($forecasters, fn(Builder $q) => $q->whereKey($forecasters))
            ->get();

        $locations = $this->option('locations');
        if (count($locations) == 1) { // Allow comma-separated values when only one provided
            $locations = explode(',', head($locations));
        }
        $locations_query = Location::query()
            ->when($locations, fn(Builder $q) => $q->whereIn('id', $locations));
        $locations_count = $locations_query->count();

        $this->line("Collecting weather data for $locations_count locations ✕ by {$forecasters->count()} providers ✕ 24+1 = " . ($locations_count * $forecasters->count() * (24 + 1)) . " records");
        // Chunk locations because large dataset could exist.
        $locations_query->chunk(2000, function (EloquentCollection $locations) use ($forecasters) {
            $records = collect();

            foreach ($locations as $location) {
                foreach ($forecasters as $forecaster) {
                    $records = $records->merge(rescue(
                        fn() => $forecaster->weather_collector->retrieveForecast($location)
                    ));
                }
            }

            // This should be refactored to one insert with multiple records instead of many single-record inserts.
            // So that instead of each record performing a separate insert and locking that table thousands of times it would
            // require the table lock only for one time.
            $records->each->save();
        });
    }
}
