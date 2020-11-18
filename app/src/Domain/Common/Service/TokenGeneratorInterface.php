<?php

namespace App\Domain\Common\Service;

interface TokenGeneratorInterface
{
    public function generateToken(): string;
}
