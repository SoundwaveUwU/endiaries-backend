<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\Media;
use App\Models\Post;
use App\Models\PostBlock;
use Arr;
use Faker\Generator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileCannotBeAdded;

class PostSeeder extends Seeder
{
    private Generator $faker;

    public function __construct(Generator $faker)
    {
        $this->faker = $faker;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     * @throws FileCannotBeAdded
     */
    public function run()
    {
        $blogs = Blog::all();
        $date = now();
        for ($i = 1; $i <= 10; $i++) {
            $posts = Post::factory()
                ->count(5)
                ->create();

            $date = $date->subMinutes(30);

            /** @var Post $firstPost */
            $firstPost = $posts->shift();
            $firstPost->created_at = $date;
            $firstPost->blog()->associate($blogs->random());
            $firstPost->save();

            $firstPost->blocks = $this->generateBlocks($this->faker->numberBetween(1, 3))
                ->map(function (&$block) use ($firstPost) {
                    if ($block['type'] != 'image') {
                        return $block;
                    }

                    $width = rand(100, 3000);
                    $height = rand(500, 800);

                    $media = $firstPost->addMediaFromUrl("http://placekitten.com/{$width}/{$height}")
                        ->storingConversionsOnDisk('ugc_thumbnail_public')
                        ->toMediaCollection('photos');

                    $block['media'] = [$media->id];

                    return $block;
                });

            $firstPost->save();

            $previousId = $firstPost->id;

            $posts->each(function (Post $post) use ($blogs, &$date, &$previousId) {
                $date = $date->addMinute();

                $post->title = null;
                $post->parent()->associate($previousId);
                $post->created_at = $date;
                $post->blog()->associate($blogs->random());
                $post->save();

                $post->blocks = $this->generateBlocks($this->faker->numberBetween(1, 3))
                    ->map(function (&$block) use ($post) {
                        if ($block['type'] != 'image') {
                            return $block;
                        }

                        $width = rand(100, 3000);
                        $height = rand(500, 800);

                        $media = $post->addMediaFromUrl("http://placekitten.com/{$width}/{$height}")
                            ->storingConversionsOnDisk('ugc_thumbnail_public')
                            ->toMediaCollection('photos');

                        $block['media'] = [$media->id];

                        return $block;
                    });

                $post->save();

                $previousId = $post->id;
            });
        }
    }

    private function generateBlocks($count = 3)
    {
        $blocks = collect();
        for ($i = 1; $i <= $count; $i++) {
            $type = Arr::random(['text', 'image']);

            $content = [];
            if ($type == 'text') {
                $content = $this->faker->paragraphs($this->faker->numberBetween(1, 3));
            }

            $block = [
                'type' => $type,
                'content' => $content,
            ];

            $blocks->push($block);
        }

        return $blocks;
    }
}
