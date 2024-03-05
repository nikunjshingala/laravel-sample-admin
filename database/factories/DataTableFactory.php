<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\DataTable;

class DataTableFactory extends Factory
{
    protected $model = DataTable::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'birthdate' => $this->faker->dateTimeBetween('1990-01-01', '2022-04-18')->format('Y-m-d'),
            'country' => $this->faker->randomElement(['Qatar', 'UK', 'USA', 'China', 'Peru', 'Spain', 'Turkey']),
            'type' => $this->faker->randomElement(['1', '2', '3']),
            'status' => $this->faker->randomElement(['active', 'inactive', 'deleted']),
        ];
    }
}