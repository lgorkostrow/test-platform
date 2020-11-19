<?php

declare(strict_types=1);

namespace App\App\Factory\Command;

use App\App\Http\Request\Advertisement\CreateAdvertisementRequest;
use App\Domain\Advertisement\Dto\AdvertisementDto;
use App\Domain\Advertisement\UseCase\CreateAdvertisementCommand;
use App\Domain\User\Entity\User;
use Ramsey\Uuid\Uuid;

class CreateAdvertisementCommandFactory
{
    public static function createFromRequest(CreateAdvertisementRequest $request, User $user): CreateAdvertisementCommand
    {
        return new CreateAdvertisementCommand(
            Uuid::uuid4()->toString(),
            new AdvertisementDto(
                $request->title,
                $request->description,
                $request->price,
                $request->currency,
                $request->category,
                $user->getId(),
            ),
        );
    }
}
