<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
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
            ->with('blog', 'media')
            ->get();

        return PostResource::collection($posts);
    }
}
