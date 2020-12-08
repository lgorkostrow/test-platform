<?php

declare(strict_types=1);

namespace App\Application\Exception;

class FilesNotMappedException extends AbstractAppInformativeException
{
    private array $fileKeys;

    public function __construct(array $fileKeys)
    {
        parent::__construct(400, 'FILES_WAS_NOT_MAPPED');

        $this->fileKeys = $fileKeys;
    }

    public function getInformation(): array
    {
        return [
            'keys' => $this->fileKeys,
        ];
    }
}
