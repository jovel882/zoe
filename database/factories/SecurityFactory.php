<?php

namespace Database\Factories;

use App\Models\Security;
use App\Models\SecurityType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Security>
 */
class SecurityFactory extends Factory
{
    protected $model = Security::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'security_type_id' => fn() => SecurityType::factory()->create()->id,
            'symbol' => $this->faker->unique(true)->word,
        ];
    }
}
