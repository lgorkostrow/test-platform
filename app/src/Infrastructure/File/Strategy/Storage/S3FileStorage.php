<?php

declare(strict_types=1);

namespace App\Infrastructure\File\Strategy\Storage;

use App\Domain\File\Enum\StorageTypeEnum;
use App\Domain\File\Storage\FileStorageInterface;
use Aws\S3\S3ClientInterface;
use Ramsey\Uuid\Uuid;

class S3FileStorage implements FileStorageInterface
{
    private string $bucket;

    private S3ClientInterface $s3Client;

    public function __construct(string $bucket, S3ClientInterface $s3Client)
    {
        $this->bucket = $bucket;
        $this->s3Client = $s3Client;
    }

    public function supports(string $storageType): bool
    {
        return StorageTypeEnum::S3 === $storageType;
    }

    public function buildFullPath(string $path): string
    {
        return $path;
    }

    public function upload(string $path, string $fileName): string
    {
        $result = $this->s3Client->upload(
            $this->bucket,
            Uuid::uuid4()->toString(),
            fopen($path, 'rb'),
        );

        return $result->get('ObjectURL');
    }
}
