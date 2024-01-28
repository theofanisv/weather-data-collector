<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name', 'street', 'municipality', 'city', 'region', 'country', 'lat', 'lng'];

    public function weather_records(): HasMany
    {
        return $this->hasMany(WeatherRecord::class);
    }
}
