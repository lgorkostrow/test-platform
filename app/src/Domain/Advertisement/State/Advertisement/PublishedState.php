<?php

declare(strict_types=1);

namespace App\Domain\Advertisement\State\Advertisement;

use App\Domain\Common\State\AbstractState;

class PublishedState extends AbstractState
{
    const NAME = 'published';

    /**
     * @var array|string[]
     */
    protected array $next = [
        ArchivedState::class,
    ];
}
