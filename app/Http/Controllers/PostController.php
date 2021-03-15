<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Blog;
use App\Models\Post;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PostController extends Controller
{
    /**
     * @param Request $request
     * @param Blog $blog
     * @return AnonymousResourceCollection
     */
    public function index(Request $request, Blog $blog)
    {
        $posts = $blog->posts()
            ->latest('id')
            ->skip($request->input('offset', 0))
            ->take(10)
            ->with('blog', 'media')
            ->get();

        return PostResource::collection($posts);
    }

    /**
     * @param Post $post
     * @return PostResource
     * @throws Exception
     */
    public function show(Post $post): PostResource
    {
        $post->load('blog');

        return PostResource::make($post);
    }
}
