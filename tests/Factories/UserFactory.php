<?php

namespace Javaabu\MenuBuilder\Tests\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Javaabu\MenuBuilder\Tests\Models\User;

/**
 * @extends Factory
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
        ];
    }
}
