<?php

declare(strict_types=1);

namespace App\Application\Controller\Api\Advertisement;

use App\Application\Converter\DtoConverter;
use App\Application\Factory\Command\CreateAdvertisementCommandFactory;
use App\Application\Http\Annotation\ChoiceQueryParam;
use App\Application\Http\Request\Advertisement\CreateAdvertisementRequest;
use App\Application\Service\ValidationService;
use App\Application\Utils\MessengerUtils;
use App\Domain\Advertisement\Dto\AdvertisementDetailedViewDto;
use App\Domain\Advertisement\Entity\Advertisement;
use App\Domain\Advertisement\Query\GetAdvertisementQuery;
use App\Domain\Advertisement\Query\GetPublishedAdvertisementsQuery;
use App\Domain\Advertisement\Query\GetReadyForReviewAdvertisementsQuery;
use App\Domain\Advertisement\Query\GetUserAdvertisementsQuery;
use App\Domain\Advertisement\UseCase\ArchiveAdvertisementCommand;
use App\Domain\Advertisement\UseCase\PublishAdvertisementCommand;
use App\Domain\Advertisement\UseCase\SendAdvertisementToReviewCommand;
use App\Domain\Advertisement\UseCase\SendBackAdvertisementCommand;
use App\Domain\Common\Repository\PaginatedQueryResult;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Application\Enum\PermissionEnum;
use Symfony\Component\Validator\Constraints as Assert;
use App\Domain\Advertisement\Enum\AdvertisementStateEnum;
use App\Application\Validator\Constraints\EntityExists;
use App\Domain\Advertisement\Entity\Category;
use App\Domain\User\Enum\RoleEnum;

/**
 * @Rest\Route(path="/advertisement")
 */
class AdvertisementController extends AbstractFOSRestController
{
    /**
     * @var MessageBusInterface
     */
    private MessageBusInterface $commandBus;

    /**
     * @var MessageBusInterface
     */
    private MessageBusInterface $queryBus;

    /**
     * @var DtoConverter
     */
    private DtoConverter $converter;

    /**
     * @var ValidationService
     */
    private ValidationService $validationService;

    public function __construct(
        MessageBusInterface $commandBus,
        MessageBusInterface $queryBus,
        DtoConverter $converter,
        ValidationService $validationService
    ) {
        $this->commandBus = $commandBus;
        $this->queryBus = $queryBus;
        $this->converter = $converter;
        $this->validationService = $validationService;
    }

    /**
     * Find published advertisements
     *
     * @Rest\Get(path="")
     *
     * @Rest\QueryParam(name="limit", requirements="\d+", strict=true, default=15)
     * @Rest\QueryParam(name="offset", requirements="\d+", strict=true, default=0)
     * @Rest\QueryParam(name="category", strict=true, allowBlank=false, nullable=false, requirements=@EntityExists(class=Category::class))
     * @Rest\QueryParam(name="title", strict=true, allowBlank=false, nullable=true)
     *
     * @Rest\View
     *
     * @param ParamFetcherInterface $paramFetcher
     * @return PaginatedQueryResult
     */
    public function getPublished(ParamFetcherInterface $paramFetcher)
    {
        $envelope = $this->queryBus->dispatch(new GetPublishedAdvertisementsQuery(
            (int)$paramFetcher->get('limit'),
            (int)$paramFetcher->get('offset'),
            $paramFetcher->get('category'),
            $paramFetcher->get('title'),
        ));

        return MessengerUtils::getResultFromEnvelope($envelope);
    }

    /**
     * @Rest\Get(path="/my")
     *
     * @Rest\QueryParam(name="limit", requirements="\d+", strict=true, default=15)
     * @Rest\QueryParam(name="offset", requirements="\d+", strict=true, default=0)
     * @Rest\QueryParam(name="category", strict=true, allowBlank=false, nullable=true, requirements=@EntityExists(class=Category::class))
     *
     * @ChoiceQueryParam(
     *     name="state",
     *     nullable=true,
     *     requirements=@Assert\Choice(choices=AdvertisementStateEnum::VALID_CHOICES),
     *     strict=true,
     * )
     *
     * @Rest\View
     *
     * @param ParamFetcherInterface $paramFetcher
     * @return PaginatedQueryResult
     */
    public function getForCurrentUser(ParamFetcherInterface $paramFetcher)
    {
        $envelope = $this->queryBus->dispatch(new GetUserAdvertisementsQuery(
            (int)$paramFetcher->get('limit'),
            (int)$paramFetcher->get('offset'),
            $this->getUser()->getId(),
            $paramFetcher->get('state'),
            $paramFetcher->get('category'),
        ));

        return MessengerUtils::getResultFromEnvelope($envelope);
    }

    /**
     * @Rest\Get(path="/ready-for-review")
     *
     * @IsGranted(RoleEnum::ROLE_MANAGER)
     *
     * @Rest\QueryParam(name="limit", requirements="\d+", strict=true, default=15)
     * @Rest\QueryParam(name="offset", requirements="\d+", strict=true, default=0)
     * @Rest\QueryParam(name="category", strict=true, allowBlank=false, nullable=true, requirements=@EntityExists(class=Category::class))
     *
     * @Rest\View
     *
     * @param ParamFetcherInterface $paramFetcher
     * @return PaginatedQueryResult
     */
    public function getReadyForReview(ParamFetcherInterface $paramFetcher)
    {
        $envelope = $this->queryBus->dispatch(new GetReadyForReviewAdvertisementsQuery(
            (int)$paramFetcher->get('limit'),
            (int)$paramFetcher->get('offset'),
            $paramFetcher->get('category'),
        ));

        return MessengerUtils::getResultFromEnvelope($envelope);
    }

    /**
     * @Rest\Get(path="/{id}")
     *
     * @Rest\View
     *
     * @param string $id
     * @return AdvertisementDetailedViewDto
     */
    public function getDetailedView(string $id)
    {
        // TODO Need to check permission
        $result = MessengerUtils::getResultFromEnvelope(
            $this->queryBus->dispatch(new GetAdvertisementQuery($id))
        );
        if (!$result) {
            throw new NotFoundHttpException();
        }

        return $result;
    }

    /**
     * @Rest\Post(path="")
     *
     * @Rest\View
     *
     * @param Request $request
     * @param CreateAdvertisementCommandFactory $factory
     * @return array
     */
    public function create(Request $request, CreateAdvertisementCommandFactory $factory)
    {
        $createAdvertisementRequest = $this->validationService->validate(
            $this->converter->convertToDto(CreateAdvertisementRequest::class, $request->request->all())
        );

        $command = $factory->createFromRequest($createAdvertisementRequest, $this->getUser());

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
     * @Rest\Post(path="/{id}/send-back")
     *
     * @IsGranted(attributes=PermissionEnum::ADVERTISEMENT_SEND_BACK, subject="advertisement")
     *
     * @Rest\RequestParam(name="reason", requirements=@Assert\Length(min=25), nullable=false, allowBlank=false, strict=true)
     *
     * @Rest\View
     *
     * @param ParamFetcherInterface $paramFetcher
     * @param Advertisement $advertisement
     */
    public function sendBack(ParamFetcherInterface $paramFetcher, Advertisement $advertisement)
    {
        $this->commandBus->dispatch(
            new SendBackAdvertisementCommand($advertisement->getId(), $paramFetcher->get('reason'))
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
