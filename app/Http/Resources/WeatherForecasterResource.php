<?php

namespace App\Http\Resources;

use App\Models\WeatherForecaster;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property WeatherForecaster $resource
 */
class WeatherForecasterResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return $this->resource->only('name', 'web_url', 'collector_class_key', 'created_at');
    }
}
