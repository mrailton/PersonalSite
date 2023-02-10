<?php

declare(strict_types=1);

namespace App\Services\MediaLibrary;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

class CertificatesPathGenerator implements PathGenerator
{
    public function getPath(Media $media): string
    {
        return "certificates/{$media->uuid}/";
    }

    public function getPathForConversions(Media $media): string
    {
        return "certificates/{$media->uuid}/conversions/";
    }

    public function getPathForResponsiveImages(Media $media): string
    {
        return "certificates/{$media->uuid}/responsive/";
    }
}
