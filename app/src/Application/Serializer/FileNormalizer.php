<?php

declare(strict_types=1);

namespace App\Application\Serializer;

use App\Domain\Advertisement\Dto\AdvertisementAttachmentSimpleDto;
use App\Domain\Advertisement\Dto\AdvertisementListItemDto;
use App\Domain\File\Utils\FileUtils;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class FileNormalizer implements ContextAwareNormalizerInterface
{
    private const MAP = [
        AdvertisementListItemDto::class => 'featuredImage',
        AdvertisementAttachmentSimpleDto::class => 'path',
    ];

    private string $publicDir;

    private ObjectNormalizer $objectNormalizer;

    public function __construct(string $publicDir, ObjectNormalizer $objectNormalizer)
    {
        $this->publicDir = $publicDir;
        $this->objectNormalizer = $objectNormalizer;
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return is_object($data) && array_key_exists(get_class($data), self::MAP);
    }

    public function normalize($object, string $format = null, array $context = [])
    {
        $data = $this->objectNormalizer->normalize($object, $format, $context);
        $path = $data[self::MAP[get_class($object)]] ?? null;
        if (!$path || filter_var($path, FILTER_VALIDATE_URL)) {
            return $data;
        }

        $data[self::MAP[get_class($object)]] = FileUtils::getRelativePath($this->publicDir, $path);

        return $data;
    }
}
