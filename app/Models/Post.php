<?php

namespace App\Models;

use Eloquent;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * App\Models\Post
 *
 * @property int $id
 * @property int|null $blog_id Blog on which post was posted
 * @property int|null $post_id Refers re-blogged post
 * @property string|null $title
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|array $blocks
 * @property-read int|null $blocks_count
 * @property-read Blog|null $blog
 * @property-read string $created_at_human_readable
 * @property-read string $updated_at_human_readable
 * @property-read Post|null $parent
 * @method static \Illuminate\Database\Eloquent\Builder|Post newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Post newQuery()
 * @method static Builder|Post onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Post query()
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereBlogId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post wherePostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereUpdatedAt($value)
 * @method static Builder|Post withTrashed()
 * @method static Builder|Post withoutTrashed()
 * @mixin Eloquent
 */
class Post extends Model implements HasMedia
{
    use HasFactory,
        SoftDeletes,
        InteractsWithMedia;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $appends = [
        'created_at_human_readable',
        //'updated_at_human_readable',
    ];

    protected $casts = [
        'blocks' => 'array',
    ];

    public function getCreatedAtHumanReadableAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    public function getUpdatedAtHumanReadableAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * @return BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Post::class, 'post_id');
    }

    public function blog(): BelongsTo
    {
        return $this->belongsTo(Blog::class);
    }

    public function repostsCount()
    {
        $cacheKey = "post.{$this->id}.reposts_count";
        $count = 0;
        if ($count = cache($cacheKey)) {
            return $count;
        }

        $count = $this->children()->get()->reduce(function ($count, $item) {
            return $count + $item->repostsCount();
        }, $this->children()->count());

        cache($cacheKey, $count);

        return $count;
    }

    /**
     * @throws Exception
     */
    public function parentChain()
    {
        $cacheKey = "post.{$this->id}.parent_chain";
        if ($chain = cache($cacheKey, false)) {
            return $chain;
        }

        $posts = collect();
        $parent = $this->parent;
        while (!is_null($parent)) {
            $parent->load(['blog.media', 'parent']);
            $posts->add($parent);

            $cacheKeyParent = "post.{$parent->id}.parent_chain";
            if (cache($cacheKeyParent)) {
                $chain = $posts->merge($parent->parentChain());
                cache()->put($cacheKey, $chain);
                return $chain;
            }

            $parent = $parent->parent;
        }

        $chain = $posts
            ->sortBy('created_at')
            ->sortBy('id');

        cache()->put($cacheKey, $chain);

        return $chain;
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('photos')
            ->storeConversionsOnDisk(config('media-library.conversion_disk_name'));
        $this->addMediaCollection('video')
            ->storeConversionsOnDisk(config('media-library.conversion_disk_name'))
            ->singleFile();
        $this->addMediaCollection('audio')
            ->storeConversionsOnDisk(config('media-library.conversion_disk_name'))
            ->singleFile();
        $this->addMediaCollection('cover')
            ->storeConversionsOnDisk(config('media-library.conversion_disk_name'))
            ->singleFile();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('optimized')
            ->optimize()
            ->withResponsiveImages()
            ->performOnCollections('photos', 'video');

        $this->addMediaConversion('optimized')
            ->width(320)
            ->height(320)
            ->optimize()
            ->withResponsiveImages()
            ->performOnCollections('cover');
    }
}
