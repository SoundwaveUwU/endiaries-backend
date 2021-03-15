<?php

namespace App\Models;

use App\Models\Traits\HasUuidKey;
use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Follow
 *
 * @property int $from_blog_id
 * @property int $to_blog_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Follow newModelQuery()
 * @method static Builder|Follow newQuery()
 * @method static Builder|Follow query()
 * @method static Builder|Follow whereCreatedAt($value)
 * @method static Builder|Follow whereFromBlogId($value)
 * @method static Builder|Follow whereToBlogId($value)
 * @method static Builder|Follow whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Follow extends Model
{
    use HasUuidKey;

    public $timestamps = false;

    public function fromBlog(): BelongsTo
    {
        return $this->belongsTo(Blog::class);
    }

    public function toBlog(): BelongsTo
    {
        return $this->belongsTo(Blog::class);
    }
}
