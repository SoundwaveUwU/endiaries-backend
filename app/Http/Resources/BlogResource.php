<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BlogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return cache()->rememberForever("blog.{$this->id}", function () {
            return [
                'id' => $this->id,
                'slug' => $this->slug,
                'media' => MediaResource::collection($this->media),
            ];
        });
    }
}
