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
            'state' => ['pending', 'shipped'][rand(0, 1)],
            'total' => 120000,
            'sub_total' => 100000,
            'shippment_fee' => 20000
        ];
    }
}