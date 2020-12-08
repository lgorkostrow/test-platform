<?php

declare(strict_types=1);

namespace App\Domain\Advertisement\Query;

use App\Domain\Common\Message\SyncMessageInterface;

class GetAdvertisementQuery implements SyncMessageInterface
{
    private string $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }
}
