<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\SoftDeletingScope;

/**
 * \App\Models\WeatherRecord
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon $reference_datetime
 * @property string $period_type
 * @property int $location_id
 * @property int $weather_forecaster_id
 * @property float|null $temperature
 * @property float|null $precipitation
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Location $location
 * @property-read \App\Models\WeatherForecaster $weather_forecaster
 * @method static \Illuminate\Database\Eloquent\Builder|WeatherRecord newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WeatherRecord newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WeatherRecord onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|WeatherRecord query()
 * @method static \Illuminate\Database\Eloquent\Builder|WeatherRecord whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WeatherRecord whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WeatherRecord whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WeatherRecord whereLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WeatherRecord wherePeriodType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WeatherRecord wherePrecipitation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WeatherRecord whereReferenceDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WeatherRecord whereTemperature($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WeatherRecord whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WeatherRecord whereWeatherForecasterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WeatherRecord withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|WeatherRecord withoutTrashed()
 * @mixin \Eloquent
 */
class WeatherRecord extends Model
{
    use SoftDeletes;

    public const PERIOD_TYPES = ['day', 'hour'];

    protected $fillable = ['reference_datetime', 'period_type', 'temperature', 'precipitation'];
    protected $casts = [
        'reference_datetime' => 'datetime',
    ];

    public function setPeriodTypeAttribute(?string $value): void
    {
        // Validate period_type
        throw_unless(in_array($value, static::PERIOD_TYPES), "Invalid period type '$value'");
        $this->attributes['period_type'] = $value;
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class)
            ->withoutGlobalScope(SoftDeletingScope::class); // Include deleted because this record cannot exist without the related one.
    }

    public function weather_forecaster(): BelongsTo
    {
        return $this->belongsTo(WeatherForecaster::class)
            ->withoutGlobalScope(SoftDeletingScope::class); // Include deleted because this record cannot exist without the related one.
    }
}
