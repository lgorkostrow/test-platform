<?php

declare(strict_types=1);

namespace App\Infrastructure\Common\Service;

use App\Domain\Common\Service\TokenGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface as BaseTokenGeneratorInterface;

class TokenGenerator implements TokenGeneratorInterface
{
    /**
     * @var BaseTokenGeneratorInterface
     */
    private BaseTokenGeneratorInterface $generator;

    public function __construct(BaseTokenGeneratorInterface $generator)
    {
        $this->generator = $generator;
    }

    public function generateToken(): string
    {
        return $this->generator->generateToken();
    }
}
