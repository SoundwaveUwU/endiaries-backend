<?php

namespace App\Http\Resources;

use App\Models\Blog;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read int id
 * @property array blocks
 * @property Blog|null blog
 * @property Carbon created_at
 * @property string created_at_human_readable
 */
class PostResource extends JsonResource
{
    public static $wrap = 'post';

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     * @throws Exception
     */
    public function toArray($request): array
    {
        //return cache()->rememberForever("post.{$this->id}", function () {
            return [
                'id' => $this->id,
                'blocks' => $this->blocks,
                'blog' => BlogResource::make($this->blog),
                'parent_chain' => PostResource::collection($this->parentChain()),
                'created_at' => (string)$this->created_at,
                'created_at_human_readable' => $this->created_at_human_readable,
                'media' => MediaResource::collection($this->media),
                'shares' => $this->resource->repostsCount(),
                'likes' => 0,
            ];
        //});
    }
}
