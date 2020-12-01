<?php

declare(strict_types=1);

namespace App\Tests\Application\Utils;

use App\Domain\File\Utils\FileUtils;
use App\Tests\AbstractKernelTestCase;

class FileUtilsTest extends AbstractKernelTestCase
{
    /**
     * @dataProvider getRelativePathDataProvider
     *
     * @param string $publicDir
     * @param string $path
     * @param string $result
     */
    public function testGetRelativePath(string $publicDir, string $path, string $result)
    {
        $this->assertEquals($result, FileUtils::getRelativePath($publicDir, $path));
    }

    public function getRelativePathDataProvider()
    {
        return [
            [
                'publicDir' => '/var/www/public',
                'path' => '/var/www/public/test.png',
                'result' => 'test.png',
            ],
            [
                'publicDir' => '/var/www/public/',
                'path' => '/var/www/public/test.png',
                'result' => 'test.png',
            ],
        ];
    }
}
