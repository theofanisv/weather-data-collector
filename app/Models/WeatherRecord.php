<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WeatherRecord extends Model
{
    use SoftDeletes;

    protected $fillable = ['temperature', 'precipitation'];

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
