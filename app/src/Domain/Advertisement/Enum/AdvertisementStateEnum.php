<?php

declare(strict_types=1);

namespace App\Domain\Advertisement\Enum;

use App\Domain\Advertisement\State\Advertisement\ArchivedState;
use App\Domain\Advertisement\State\Advertisement\DraftState;
use App\Domain\Advertisement\State\Advertisement\OnReviewState;
use App\Domain\Advertisement\State\Advertisement\PublishedState;

class AdvertisementStateEnum
{
    const VALID_CHOICES = [
        DraftState::NAME,
        OnReviewState::NAME,
        PublishedState::NAME,
        ArchivedState::NAME,
    ];
}
