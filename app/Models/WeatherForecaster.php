<?php

namespace App\Models;

use App\WeatherCollectors\WeatherCollector;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * \App\Models\WeatherForecaster
 *
 * @property int $id
 * @property string $name
 * @property string|null $web_url
 * @property string $collector_class_key
 * @property array $settings
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read WeatherCollector $weather_collector
 * @property-read string $weather_collector_class
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\WeatherRecord> $weather_records
 * @property-read int|null $weather_records_count
 * @method static \Illuminate\Database\Eloquent\Builder|WeatherForecaster newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WeatherForecaster newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WeatherForecaster onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|WeatherForecaster query()
 * @method static \Illuminate\Database\Eloquent\Builder|WeatherForecaster whereCollectorClassKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WeatherForecaster whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WeatherForecaster whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WeatherForecaster whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WeatherForecaster whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WeatherForecaster whereSettings($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WeatherForecaster whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WeatherForecaster whereWebUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WeatherForecaster withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|WeatherForecaster withoutTrashed()
 * @mixin \Eloquent
 */
class WeatherForecaster extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'web_url', 'collector_class_key', 'settings'];
    protected $attributes = [
        'settings' => '{}',
    ];
    protected $casts = [
        'settings' => 'array',
    ];

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
        /** @var WeatherCollector $class */
        $class = new $this->weather_collector_class;
        $class->forForecaster($this);

        return $class;
    }

    public function weather_records(): HasMany
    {
        return $this->hasMany(WeatherRecord::class);
    }
}
