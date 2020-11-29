<?php

declare(strict_types=1);

namespace App\Domain\Advertisement\Mapper;

use App\Domain\Advertisement\State\Advertisement\ArchivedState;
use App\Domain\Advertisement\State\Advertisement\DraftState;
use App\Domain\Advertisement\State\Advertisement\OnReviewState;
use App\Domain\Advertisement\State\Advertisement\PublishedState;
use App\Domain\Common\State\AbstractState;
use InvalidArgumentException;

class StateMapper
{
    public static function mapStateNameToObject(string $stateName): AbstractState
    {
        switch ($stateName) {
            case DraftState::NAME:
                return new DraftState();
            case OnReviewState::NAME:
                return new OnReviewState();
            case PublishedState::NAME:
                return new PublishedState();
            case ArchivedState::NAME:
                return new ArchivedState();
        }

        throw new InvalidArgumentException("UNDEFINED_STATE");
    }
}
