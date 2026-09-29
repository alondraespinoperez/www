<?php

namespace Database\Factories;

use App\Models\SensorReading;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SensorReading>
 */
class SensorReadingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'device_id' => 'esp8266-dht11-01',
            'temperature' => fake()->randomFloat(2, 15, 35),
            'humidity' => fake()->randomFloat(2, 20, 90),
            'measured_at' => fake()->dateTimeBetween('-7 days', 'now'),
        ];
    }
}
