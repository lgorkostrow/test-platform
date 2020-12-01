<?php

declare(strict_types=1);

namespace App\Domain\File\Utils;

class FileUtils
{
    /**
     * @param string $originalName
     * @param string $extension
     * @return string
     */
    public static function generateFileName(string $originalName, string $extension): string
    {
        return sprintf(
            '%s-%s.%s',
            str_replace(".$extension", '', $originalName),
            time(),
            $extension,
        );
    }

    /**
     * @param string $publicDir
     * @param string $fullPath
     * @return string
     */
    public static function getRelativePath(string $publicDir, string $fullPath): string
    {
        $publicDir = rtrim($publicDir, '/');

        return substr(
            $fullPath,
            strpos($fullPath, $publicDir . '/') + strlen($publicDir . '/')
        );
    }
}
