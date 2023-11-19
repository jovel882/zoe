<?php

namespace Database\Factories;

use App\Models\Security;
use App\Models\SecurityPrice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SecurityPrice>
 */
class SecurityPriceFactory extends Factory
{
    protected $model = SecurityPrice::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'security_id' => fn() => Security::factory()->create()->id,
            'last_price' => $this->faker->randomFloat(2, 0, 1000),
            'as_of_date' => $this->faker->dateTimeThisYear->format('Y-m-d H:i:s'),
        ];
    }
}
