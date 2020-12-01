<?php

declare(strict_types=1);

namespace App\Application\Serializer;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class UploadedFileDenormalizer extends ObjectNormalizer
{
    public function supportsDenormalization($data, string $type, string $format = null): bool
    {
        return is_object($data) && $data instanceof File;
    }

    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        return $data;
    }
}
