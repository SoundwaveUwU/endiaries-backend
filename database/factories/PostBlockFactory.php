<?php

namespace Database\Factories;

use App\Models\Media;
use App\Models\PostBlock;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Http;

class PostBlockFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PostBlock::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $type = $this->faker->randomElement(['text', 'image']);

        return [
            'type' => $type,
            'content' => $type === 'text' ? [
                [
                    'text' => $this->faker->paragraph,
                ]
            ] : null,
        ];
    }
}
