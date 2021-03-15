<?php

namespace App\Media;

use App\Media\Concerns\WidthCalculator;
use Illuminate\Support\Collection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\ImageFactory;

class MediaWidthCalculator implements WidthCalculator
{
    public function calculateWidthsFromMedia(Media $media): Collection
    {
        $image = ImageFactory::load($media->getPath());

        return $this->calculateWidths($media->collection_name, $image->getWidth());
    }

    public function calculateWidths(string $type, int $width): Collection
    {
        $targetWidths = collect();

        $predefinedWidths = [541, 480];

        if ($type == 'avatar') {
            // TODO
            $predefinedWidths = [80, 64];
        }

        if ($type == 'cover') {
            // TODO
            $predefinedWidths = [512];
        }

        $predefinedWidths = collect($predefinedWidths)->sort(SORT_NUMERIC);

        $multiplier = 1;
        $count = 0;

        $targetWidths->push($width);

        while ($count < 10 && $multiplier < 3) {
            foreach ($predefinedWidths as $predefinedWidth) {
                if ($predefinedWidth * $multiplier >= $width) {
                    break 2;
                }

                $targetWidths->push($predefinedWidth * $multiplier);
                $count++;

                if ($count >= 10)
                    break 2;
            }
            $multiplier++;
        }

        return $targetWidths->sortDesc(SORT_NUMERIC);
    }
}
