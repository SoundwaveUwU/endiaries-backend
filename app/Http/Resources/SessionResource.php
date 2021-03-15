<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class SessionResource
 * @package App\Http\Resources
 *
 * @property-read Carbon created_at
 */
class SessionResource extends JsonResource
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
            'created_at' => (string) $this->created_at,
            'created_at_human_readable' => $this->created_at->diffForHumans(),
        ] + parent::toArray($request);
    }
}
