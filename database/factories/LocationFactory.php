<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Location>
 */
class LocationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'         => $this->faker->unique()->streetName,
            'timezone'     => $this->faker->timezone,
            'street'       => $this->faker->streetName,
            'municipality' => null,
            'city'         => $this->faker->city,
            'region'       => null,
            'country'      => $this->faker->country,
            'lat'          => ($point = $this->faker->localCoordinates)['latitude'],
            'lng'          => $point['longitude'],
        ];
    }
}
