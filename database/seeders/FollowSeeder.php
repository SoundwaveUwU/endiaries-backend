<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\Follow;
use Illuminate\Database\Seeder;

class FollowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $blogs = Blog::all();

        $blogs->each(function (Blog $fromBlog) use ($blogs) {
            foreach ($blogs->random(rand(1, $blogs->count())) as $toBlog) {
                $follow = new Follow;
                $follow->fromBlog()->associate($fromBlog);
                $follow->toBlog()->associate($toBlog);
                $follow->save();
            }

        });
    }
}
