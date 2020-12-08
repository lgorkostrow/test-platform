<?php

declare(strict_types=1);

namespace App\Application\Http\Response;

use JsonSerializable;

class AppInformativeExceptionResponse implements JsonSerializable
{
    private string $message;

    private array $information;

    public function __construct(string $message, array $information)
    {
        $this->message = $message;
        $this->information = $information;
    }

    public function jsonSerialize(): array
    {
        return [
            '_app' => $this->message,
            '_information' => $this->information,
        ];
    }
}
