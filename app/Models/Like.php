<?php

namespace App\Models;

use App\Models\Traits\HasUuidKey;
use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\Like
 *
 * @property int $blog_id
 * @property int $post_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Like newModelQuery()
 * @method static Builder|Like newQuery()
 * @method static Builder|Like query()
 * @method static Builder|Like whereBlogId($value)
 * @method static Builder|Like whereCreatedAt($value)
 * @method static Builder|Like wherePostId($value)
 * @method static Builder|Like whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Like extends Model
{
    use HasUuidKey;
}
