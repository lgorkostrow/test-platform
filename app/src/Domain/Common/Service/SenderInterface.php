<?php

namespace App\Domain\Common\Service;

interface SenderInterface
{
    public function generateBody(string $template, array $data): string;

    public function send(string $to, string $subject, string $body): void;
}
