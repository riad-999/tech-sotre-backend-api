<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'total' => 120000,
            'sub_total' => 100000,
            'shippment_fee' => 20000,
            'exported' => [1, 0, 1, 1, 1][rand(0, 4)]
        ];
    }
}