<?php

namespace App\Models;

use App\Models\Traits\HasUuidKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tag extends Model
{
    use HasFactory, HasUuidKey;

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}
