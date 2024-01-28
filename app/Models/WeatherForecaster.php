<?php

namespace App\Models;

use App\WeatherCollectors\WeatherCollector;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WeatherForecaster extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'web_url', 'collector_class_key'];

    public function setCollectorClassKeyAttribute(string $value): void
    {
        // Validate key to prevent storage of invalid collector in db.
        throw_unless(in_array($value, array_keys(WeatherCollector::COLLECTORS)),
            \Exception::class,
            "Weather collector class key '$value' is invalid."
        );
        $this->attributes['collector_class_key'] = $value;
    }

    public function getWeatherCollectorClassAttribute(): string
    {
        return WeatherCollector::COLLECTORS[$this->collector_class_key];
    }

    public function getWeatherCollectorAttribute(): WeatherCollector
    {
        $class = $this->weather_collector_class;
        return new $class;
    }

}
