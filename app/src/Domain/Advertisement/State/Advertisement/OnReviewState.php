<?php

declare(strict_types=1);

namespace App\Domain\Advertisement\State\Advertisement;

use App\Domain\Common\State\AbstractState;

class OnReviewState extends AbstractState
{
    public const NAME = 'on_review';

    /**
     * @var array|string[]
     */
    protected array $next = [
        DraftState::class,
        PublishedState::class,
    ];
}
