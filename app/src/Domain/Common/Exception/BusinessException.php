<?php

declare(strict_types=1);

namespace App\Domain\Common\Exception;

use RuntimeException;

class BusinessException extends RuntimeException
{
    public function __construct($message = '')
    {
        parent::__construct($message, 400);
    }
}
