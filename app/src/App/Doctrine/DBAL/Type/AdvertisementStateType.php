<?php

declare(strict_types=1);

namespace App\App\Doctrine\DBAL\Type;

use App\Domain\Advertisement\State\Advertisement\ArchivedState;
use App\Domain\Advertisement\State\Advertisement\DraftState;
use App\Domain\Advertisement\State\Advertisement\OnReviewState;
use App\Domain\Advertisement\State\Advertisement\PublishedState;
use App\Domain\Common\State\AbstractState;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use InvalidArgumentException;

class AdvertisementStateType extends Type
{
    const NAME = 'advertisement_state';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return sprintf(
            "ENUM('%s', '%s', '%s', '%s') COMMENT '(DC2Type: %s)'",
            DraftState::NAME,
            OnReviewState::NAME,
            PublishedState::NAME,
            ArchivedState::NAME,
            self::NAME,
        );
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        switch ($value) {
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

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (!$value instanceof AbstractState) {
            throw new InvalidArgumentException("UNDEFINED_STATE");
        }

        return $value::NAME;
    }

    public function getName()
    {
        return self::NAME;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }
}
