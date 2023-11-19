<?php

namespace Database\Factories;

use App\Models\SecurityType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SecurityTypes>
 */
class SecurityTypeFactory extends Factory
{
    protected $model = SecurityType::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'slug' => $this->faker->unique(true)->slug,
            'name' => $this->faker->word,
        ];
    }
}
