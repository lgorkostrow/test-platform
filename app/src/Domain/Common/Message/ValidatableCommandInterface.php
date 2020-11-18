<?php

namespace App\Domain\Common\Message;

interface ValidatableCommandInterface
{
    public function getDataToValidate(): object;
}
