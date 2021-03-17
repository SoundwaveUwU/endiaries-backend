<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\Follow;
use App\Models\User;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();

        Blog::factory()
            ->count(10)
            ->for($users->random())
            ->create()
            ->each(function ($blog) {
                // some blogs may lack avatar
                if (rand(1, 100) >= 50)
                    return;

                $width = rand(32, 512);

                $blog->addMediaFromUrl("http://placekitten.com/{$width}/{$width}")
                    ->storingConversionsOnDisk('ugc_thumbnail_public')
                    ->toMediaCollection('avatar');
            });
    }
}
