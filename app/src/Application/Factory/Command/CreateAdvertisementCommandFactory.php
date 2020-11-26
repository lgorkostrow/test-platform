<?php

declare(strict_types=1);

namespace App\Application\Factory\Command;

use App\Application\Factory\Dto\Factory\FileDtoFactory;
use App\Application\Http\Request\Advertisement\CreateAdvertisementRequest;
use App\Domain\Advertisement\Dto\AdvertisementDto;
use App\Domain\Advertisement\UseCase\CreateAdvertisementCommand;
use App\Domain\User\Entity\User;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CreateAdvertisementCommandFactory
{
    /**
     * @var FileDtoFactory
     */
    private FileDtoFactory $fileDtoFactory;

    public function __construct(FileDtoFactory $fileDtoFactory)
    {
        $this->fileDtoFactory = $fileDtoFactory;
    }

    public function createFromRequest(CreateAdvertisementRequest $request, User $user): CreateAdvertisementCommand
    {
        $attachments = array_map(function (UploadedFile $file) {
            return $this->fileDtoFactory->createFromUploadedFile(Uuid::uuid4()->toString(), $file);
        }, $request->attachments);

        return new CreateAdvertisementCommand(
            Uuid::uuid4()->toString(),
            new AdvertisementDto(
                $request->title,
                $request->description,
                $request->price,
                $request->currency,
                $request->category,
                $user->getId(),
                $attachments,
            ),
        );
    }
}
