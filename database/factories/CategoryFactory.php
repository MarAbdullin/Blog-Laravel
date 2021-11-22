<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {  $name = $this->faker->word(rand(40, 50));
        return [
            'name' => $name,
            'content' => $this->faker->realText(rand(200, 500)),
            'slug' => Str::slug($name),
         ];
    }
}
