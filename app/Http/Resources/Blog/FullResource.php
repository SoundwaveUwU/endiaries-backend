<?php

namespace App\Http\Resources\Blog;

use App\Http\Resources\MediaResource;
use Illuminate\Http\Resources\Json\JsonResource;

class FullResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'description' => $this->description,
            'media' => MediaResource::collection($this->media),
        ];
    }
}
