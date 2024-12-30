<?php

namespace Database\Factories;

use App\Models\TravelOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

class TravelOrderFactory extends Factory
{
    protected $model = TravelOrder::class;

    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'requester_name' => $this->faker->name,
            'destination' => $this->faker->city,
            'start_date' => $this->faker->dateTimeBetween('now', '+1 month'),
            'end_date' => $this->faker->dateTimeBetween('+1 month', '+2 months'),
            'status' => 'solicitado',
        ];
    }
}
