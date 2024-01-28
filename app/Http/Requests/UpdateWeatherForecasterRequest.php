<?php

namespace App\Http\Requests;

use App\Models\WeatherForecaster;
use App\WeatherCollectors\WeatherCollector;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateWeatherForecasterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'                => ['required', 'string'],
            'web_url'             => ['nullable', 'url'],
            'collector_class_key' => ['required', Rule::in(array_keys(WeatherCollector::COLLECTORS))],
        ];
    }

    public function update(WeatherForecaster $weather_forecaster): WeatherForecaster
    {
        $weather_forecaster->update($this->all());
        return $weather_forecaster;
    }
}
