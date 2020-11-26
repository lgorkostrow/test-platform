<?php

declare(strict_types=1);

namespace App\Application\Http\Response;

class AppInformativeExceptionResponse implements \JsonSerializable
{
    /**
     * @var string
     */
    private string $message;

    /**
     * @var array
     */
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
