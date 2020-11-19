<?php

declare(strict_types=1);

namespace App\Domain\Advertisement\State\Advertisement;

use App\Domain\Common\State\AbstractState;

class DraftState extends AbstractState
{
    const NAME = 'draft';

    /**
     * @var array|string[]
     */
    protected array $next = [
        OnReviewState::class,
        ArchivedState::class,
    ];
}
