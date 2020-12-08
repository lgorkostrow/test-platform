<?php

declare(strict_types=1);

namespace App\Domain\File\Utils;

class FileUtils
{
    public static function generateFileName(string $originalName, string $extension): string
    {
        return sprintf(
            '%s-%s.%s',
            str_replace(".$extension", '', $originalName),
            time(),
            $extension,
        );
    }

    public static function getRelativePath(string $publicDir, string $fullPath): string
    {
        $publicDir = rtrim($publicDir, '/');

        return substr(
            $fullPath,
            strpos($fullPath, $publicDir . '/') + strlen($publicDir . '/')
        );
    }
}
