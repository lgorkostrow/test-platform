<?php

declare(strict_types=1);

namespace App\App\Factory\Command;

use App\App\Http\Request\User\SignUpRequest;
use App\Domain\User\Dto\UserDto;
use App\Domain\User\UseCase\SignUpCommand;
use Ramsey\Uuid\Uuid;

class SignUpCommandFactory
{
    public static function createFromSignUpRequest(SignUpRequest $request)
    {
        return new SignUpCommand(
            Uuid::uuid4()->toString(),
            new UserDto(
                $request->getEmail(),
                $request->getPassword(),
                $request->getFirstName(),
                $request->getLastName(),
                $request->getBiography(),
            ),
        );
    }
}
