<?php

declare(strict_types=1);

namespace App\Application\Factory\Command;

use App\Application\Http\Request\User\UpdatePersonalDataRequest;
use App\Domain\User\UseCase\UpdatePersonalDataCommand;

class UpdatePersonalDataCommandFactory
{
    public static function createFromUpdatePersonalDataRequest(
        string $userId,
        UpdatePersonalDataRequest $request
    ): UpdatePersonalDataCommand {
        return new UpdatePersonalDataCommand(
            $userId,
            $request->firstName,
            $request->lastName,
            $request->email,
            $request->biography,
        );
    }
}
