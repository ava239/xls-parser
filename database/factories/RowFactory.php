<?php

namespace Database\Factories;

use App\Models\Row;
use Illuminate\Database\Eloquent\Factories\Factory;

class RowFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Row::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'import_name' => $this->faker->text(20),
            'import_id' => $this->faker->randomNumber,
            'import_date' => $this->faker->date()
        ];
    }
}
