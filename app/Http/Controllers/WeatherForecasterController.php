<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWeatherForecasterRequest;
use App\Http\Requests\UpdateWeatherForecasterRequest;
use App\Http\Resources\WeatherForecasterResource;
use App\Models\WeatherForecaster;

class WeatherForecasterController extends Controller
{
    public function index()
    {
        return WeatherForecasterResource::collection(WeatherForecaster::all()); // needs caching
    }

    public function store(StoreWeatherForecasterRequest $request)
    {
        return WeatherForecasterResource::make($request->store());
    }

    public function show(WeatherForecaster $weatherForecaster)
    {
        return WeatherForecasterResource::make($weatherForecaster);
    }

    public function update(UpdateWeatherForecasterRequest $request, WeatherForecaster $weatherForecaster)
    {
        return WeatherForecasterResource::make($request->update($weatherForecaster));
    }

    public function destroy(WeatherForecaster $weatherForecaster)
    {
        $weatherForecaster->delete();
        return WeatherForecasterResource::make($weatherForecaster);
    }
}
