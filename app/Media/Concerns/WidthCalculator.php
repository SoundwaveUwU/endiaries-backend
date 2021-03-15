<?php


namespace App\Media\Concerns;


use Spatie\MediaLibrary\MediaCollections\Models\Media;

interface WidthCalculator
{
    public function calculateWidthsFromMedia(Media $media);

    public function calculateWidths(string $type, int $width);
}
