<?php

declare(strict_types=1);

namespace App\Application\Factory\Command;

use App\Application\Factory\Dto\Factory\AdvertisementAttachmentDtoFactory;
use App\Application\Http\Request\Advertisement\AttachmentDto;
use App\Application\Http\Request\Advertisement\CreateAdvertisementRequest;
use App\Domain\Advertisement\Dto\AdvertisementDto;
use App\Domain\Advertisement\UseCase\CreateAdvertisementCommand;
use App\Domain\User\Entity\User;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CreateAdvertisementCommandFactory
{
    /**
     * @var AdvertisementAttachmentDtoFactory
     */
    private AdvertisementAttachmentDtoFactory $fileDtoFactory;

    public function __construct(AdvertisementAttachmentDtoFactory $fileDtoFactory)
    {
        $this->fileDtoFactory = $fileDtoFactory;
    }

    public function createFromRequest(CreateAdvertisementRequest $request, User $user): CreateAdvertisementCommand
    {
        $attachments = array_map(function (AttachmentDto $dto) {
            return $this->fileDtoFactory->create(Uuid::uuid4()->toString(), $dto);
        }, $request->attachments ?? []);

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
