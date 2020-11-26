<?php

namespace App\Domain\File\Storage;

interface FileStorageInterface
{
    public function supports(string $storageType): bool;

    public function upload(string $path, string $fileName): string;
}
