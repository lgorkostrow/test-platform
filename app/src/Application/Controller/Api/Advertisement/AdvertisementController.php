<?php

declare(strict_types=1);

namespace App\Application\Controller\Api\Advertisement;

use App\Application\Converter\DtoConverter;
use App\Application\Factory\Command\CreateAdvertisementCommandFactory;
use App\Application\Http\Request\Advertisement\CreateAdvertisementRequest;
use App\Application\Service\ValidationService;
use App\Domain\Advertisement\Entity\Advertisement;
use App\Domain\Advertisement\UseCase\ArchiveAdvertisementCommand;
use App\Domain\Advertisement\UseCase\PublishAdvertisementCommand;
use App\Domain\Advertisement\UseCase\SendAdvertisementToReviewCommand;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Application\Enum\PermissionEnum;

/**
 * @Rest\Route(path="/advertisement")
 */
class AdvertisementController extends AbstractFOSRestController
{
    /**
     * @var MessageBusInterface
     */
    private MessageBusInterface $commandBus;

    public function __construct(MessageBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * @Rest\Post(path="")
     *
     * @Rest\View
     *
     * @param Request $request
     * @param DtoConverter $converter
     * @param ValidationService $validationService
     * @return array
     */
    public function create(Request $request, DtoConverter $converter, ValidationService $validationService)
    {
        $createAdvertisementRequest = $validationService->validate(
            $converter->convertToDto(CreateAdvertisementRequest::class, $request->request->all())
        );

        $command = CreateAdvertisementCommandFactory::createFromRequest($createAdvertisementRequest, $this->getUser());

        $this->commandBus->dispatch($command);

        return ['id' => $command->getId()];
    }

    /**
     * @Rest\Post(path="/{id}/send-to-review")
     *
     * @IsGranted(attributes=PermissionEnum::ADVERTISEMENT_SEND_TO_REVIEW, subject="advertisement")
     *
     * @Rest\View
     *
     * @param Advertisement $advertisement
     */
    public function sendToReview(Advertisement $advertisement)
    {
        $this->commandBus->dispatch(
            new SendAdvertisementToReviewCommand($advertisement->getId())
        );
    }

    /**
     * @Rest\Post(path="/{id}/publish")
     *
     * @IsGranted(attributes=PermissionEnum::ADVERTISEMENT_PUBLISH, subject="advertisement")
     *
     * @Rest\View
     *
     * @param Advertisement $advertisement
     */
    public function publish(Advertisement $advertisement)
    {
        $this->commandBus->dispatch(
            new PublishAdvertisementCommand($advertisement->getId())
        );
    }

    /**
     * @Rest\Post(path="/{id}/archive")
     *
     * @IsGranted(attributes=PermissionEnum::ADVERTISEMENT_ARCHIVE, subject="advertisement")
     *
     * @Rest\View
     *
     * @param Advertisement $advertisement
     */
    public function archive(Advertisement $advertisement)
    {
        $this->commandBus->dispatch(
            new ArchiveAdvertisementCommand($advertisement->getId())
        );
    }
}
