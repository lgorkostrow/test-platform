<?php

namespace App\Application\Exception;

interface AppInformativeExceptionInterface
{
    public function getInformation(): array;
}
