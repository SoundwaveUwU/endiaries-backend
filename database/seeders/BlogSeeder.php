<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\Follow;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileCannotBeAdded;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws FileCannotBeAdded
     */
    public function run()
    {
        $users = User::all();

        Blog::factory()
            ->count(10)
            ->make()
            ->each(function (Blog $blog) use ($users) {
                $blog->user()->associate($users->random());

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
