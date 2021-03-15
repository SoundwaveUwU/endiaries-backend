<?php

namespace App\Models;

use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * App\Models\Blog
 *
 * @property int $id
 * @property string $slug
 * @property string $title
 * @property string|null $description
 * @property int $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read mixed $avatar
 * @property-read Media[] $media
 * @property-read int|null $media_count
 * @property-read Collection|Post[] $posts
 * @property-read int|null $posts_count
 * @property-read User $user
 * @method static Builder|Blog newModelQuery()
 * @method static Builder|Blog newQuery()
 * @method static Builder|Blog query()
 * @method static Builder|Blog whereCreatedAt($value)
 * @method static Builder|Blog whereDeletedAt($value)
 * @method static Builder|Blog whereDescription($value)
 * @method static Builder|Blog whereId($value)
 * @method static Builder|Blog whereSlug($value)
 * @method static Builder|Blog whereTitle($value)
 * @method static Builder|Blog whereUpdatedAt($value)
 * @method static Builder|Blog whereUserId($value)
 * @mixin Eloquent
 */
class Blog extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'title',
        'slug',
        'description',
    ];

    protected $appends = [
        'avatar',
    ];

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(
            Blog::class,
            'follows',
            'to_blog_id',
            'from_blog_id',
        );
    }

    public function following(): BelongsToMany
    {
        return $this->belongsToMany(
            Blog::class,
            'follows',
            'from_blog_id',
            'to_blog_id',
        );
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getAvatarAttribute()
    {
        $mediaItem = $this->getFirstMedia('avatar');

        return [
            'src' => $mediaItem->getFullUrl('optimized'),
            'srcset' => $mediaItem->getSrcset('optimized'),
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
            ->storeConversionsOnDisk(config('media-library.conversion_disk_name'))
            ->singleFile();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('optimized')
            ->fit(Manipulations::FIT_CONTAIN, 320, 320)
            ->optimize()
            ->withResponsiveImages();
    }
}
