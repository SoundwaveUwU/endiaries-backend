<?php

namespace Database\Factories;

use App\Models\Blog;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BlogFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Blog::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title = $this->faker->words(2, true);
        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'description' => $this->faker->numberBetween(0, 100) ? $this->faker->paragraphs(3, true) : null,
        ];
    }
}
