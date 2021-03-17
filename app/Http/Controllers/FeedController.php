<?php

namespace App\Http\Controllers;

use App\Http\Resources\BlogResource;
use App\Http\Resources\PostResource;
use App\Models\Blog;
use App\Models\Post;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FeedController extends Controller
{
    /**
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function index(Request $request)
    {
        $posts = Post::latest('id')
            ->skip($request->input('offset', 0))
            ->take(10)
            ->get();

        $parentPosts = Post::whereIn('id', $posts->pluck('parentChain')->flatten()->unique())->get();

        $blogs = Blog::whereIn('id', $posts->pluck('blog_id')->merge($parentPosts->pluck('id'))->unique())->get();

        return [
            'posts' => PostResource::collection($posts),
            'parent_posts' => PostResource::collection($parentPosts),
            'blogs' => BlogResource::collection($blogs),
        ];
    }
}
