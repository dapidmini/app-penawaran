<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PenawaranFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nama_customer' => $this->faker->name(),
            'user_id' => 1
        ];
    }
}
