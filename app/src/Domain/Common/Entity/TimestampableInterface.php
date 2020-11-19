<?php

namespace App\Domain\Common\Entity;

use DateTime;

interface TimestampableInterface
{
    public function getCreatedAt(): ?DateTime;
    public function setCreatedAt(DateTime $createdAt);
    public function getUpdatedAt(): ?DateTime;
    public function setUpdatedAt(DateTime $updatedAt);
}
