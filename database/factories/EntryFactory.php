<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class EntryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'entry_key' => $this->faker->regexify('[A-Za-z0-9]{1,255}'),
            'value' => $this->faker->asciify(str_repeat('*', rand(1, 2000))),
        ];
    }
}
