<?php

declare(strict_types=1);

namespace App\Application\Serializer;

use App\Domain\Advertisement\Dto\AdvertisementAttachmentSimpleDto;
use App\Domain\File\Manager\FileManager;
use App\Domain\File\Storage\FileStorageInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class FileNormalizer implements ContextAwareNormalizerInterface
{
    private FileManager $fileManager;

    private ObjectNormalizer $objectNormalizer;

    public function __construct(FileManager $fileManager, ObjectNormalizer $objectNormalizer)
    {
        $this->fileManager = $fileManager;
        $this->objectNormalizer = $objectNormalizer;
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return is_object($data) && $data instanceof AdvertisementAttachmentSimpleDto;
    }

    /**
     * @param AdvertisementAttachmentSimpleDto $object
     * @param string|null $format
     * @param array $context
     * @return array
     * @throws ExceptionInterface
     */
    public function normalize($object, string $format = null, array $context = []): array
    {
        $data = $this->objectNormalizer->normalize($object, $format, $context);
        $data['path'] = $this->fileManager->buildFullPath($object->getStorage(), $data['path']);

        return $data;
    }
}
