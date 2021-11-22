<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Post;

class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {   $name = $this->faker->realText(rand(10, 20));
        return [
            'user_id' => rand(1, 10),
            'category_id' => rand(1, 12),
            'name' => $name,
            'excerpt' => $this->faker->realText(rand(300, 400)),
            'content' => $this->faker->realText(rand(400, 500)),
            'slug' => Str::slug($name),
            'published_by' => rand(1, 10),
        ];
    }
}
