<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * \App\Models\Location
 *
 * @property int $id
 * @property string $name
 * @property string $timezone
 * @property float|null $lat
 * @property float|null $lng
 * @property string|null $street
 * @property string|null $municipality
 * @property string|null $city
 * @property string|null $region
 * @property string|null $country
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read string|null $coordinates_as_string
 * @property-read string|null $full_address
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\WeatherRecord> $weather_records
 * @property-read int|null $weather_records_count
 * @method static \Database\Factories\LocationFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Location newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Location newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Location onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Location query()
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereLng($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereMunicipality($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereRegion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereStreet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereTimezone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Location withoutTrashed()
 * @mixin \Eloquent
 */
class Location extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name', 'street', 'municipality', 'city', 'region', 'country', 'lat', 'lng'];

    public function getFullAddressAttribute(): ?string
    {
        $parts = array_filter($this->only([
            'country',
            'region',
            'city',
            'municipality',
            'street',
        ]));

        return empty($parts)
            ? null
            : implode(', ', $parts);
    }

    public function getCoordinatesAsStringAttribute(): ?string
    {
        return (filled($this->lat) && filled($this->lng))
            ? "{$this->lat},{$this->lng}"
            : null;
    }

    public function weather_records(): HasMany
    {
        return $this->hasMany(WeatherRecord::class);
    }
}
