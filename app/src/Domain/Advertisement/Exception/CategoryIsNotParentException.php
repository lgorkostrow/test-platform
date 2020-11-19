<?php

declare(strict_types=1);

namespace App\Domain\Advertisement\Exception;

use App\Domain\Common\Exception\BusinessException;

class CategoryIsNotParentException extends BusinessException
{
    public function __construct()
    {
        parent::__construct('CATEGORY_IS_NOT_PARENT');
    }
}
