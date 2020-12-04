<?php

declare(strict_types=1);

namespace App\Domain\Common\Service;

class FrontendUrlsGenerator
{
    /**
     * @var array|string[]
     */
    private array $frontendUrls;

    public function __construct(array $frontendUrls)
    {
        $this->frontendUrls = $frontendUrls;
    }

    public function generateUserVerificationUrl(string $token): string
    {
        return strtr($this->frontendUrls['user_verification'], ['{token}' => $token]);
    }

    public function generateNewUserEmailVerificationUrl(string $token): string
    {
        return strtr($this->frontendUrls['new_user_email_verification'], ['{token}' => $token]);
    }
}
