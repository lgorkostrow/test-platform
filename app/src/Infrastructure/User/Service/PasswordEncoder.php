<?php

declare(strict_types=1);

namespace App\Infrastructure\User\Service;

use App\Domain\User\Service\PasswordEncoderInterface;
use App\Domain\User\UserInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PasswordEncoder implements PasswordEncoderInterface
{
    private UserPasswordEncoderInterface $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function encodePassword(UserInterface $user, string $plainPassword): string
    {
        return $this->encoder->encodePassword($user, $plainPassword);
    }

    public function isPasswordValid(UserInterface $user, string $raw): bool
    {
        return $this->encoder->isPasswordValid($user, $raw);
    }
}
