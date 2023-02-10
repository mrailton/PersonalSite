<?php

declare(strict_types=1);

namespace App\Services\MediaLibrary;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

class CertificatesPathGenerator implements PathGenerator
{

    public function getPath(Media $media): string
    {
        return "certificates/{$this->getHash($media)}/";
    }

    public function getPathForConversions(Media $media): string
    {
        return "certificates/{$this->getHash($media)}/conversions/";
    }

    public function getPathForResponsiveImages(Media $media): string
    {
        return "certificates/{$this->getHash($media)}/responsive/";
    }

    private function getHash(Media $media): string
    {
        return md5($media->id . config('app.key'));
    }
}
