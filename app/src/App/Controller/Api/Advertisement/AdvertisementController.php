<?php

declare(strict_types=1);

namespace App\App\Controller\Api\Advertisement;

use App\App\Converter\DtoConverter;
use App\App\Factory\Command\CreateAdvertisementCommandFactory;
use App\App\Http\Request\Advertisement\CreateAdvertisementRequest;
use App\App\Service\ValidationService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;

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
}
