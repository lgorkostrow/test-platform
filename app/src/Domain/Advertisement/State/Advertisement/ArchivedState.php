<?php

declare(strict_types=1);

namespace App\Domain\Advertisement\State\Advertisement;

use App\Domain\Common\State\AbstractState;

class ArchivedState extends AbstractState
{
    const NAME = 'archived';
}
