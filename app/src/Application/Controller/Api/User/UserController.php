<?php

declare(strict_types=1);

namespace App\Application\Controller\Api\User;

use App\Application\Converter\DtoConverter;
use App\Application\Factory\Command\UpdatePersonalDataCommandFactory;
use App\Application\Http\Request\User\UpdatePersonalDataRequest;
use App\Application\Manager\JwtTokenManager;
use App\Application\Service\ValidationService;
use App\Domain\User\UseCase\VerifyNewUserEmailCommand;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;

/**
 * @Rest\Route(path="/user")
 *
 * @OA\Tag(name="User")
 */
class UserController extends AbstractFOSRestController
{
    private MessageBusInterface $commandBus;

    public function __construct(MessageBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * @Rest\Put(path="/personal-data")
     *
     * @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *        type="object",
     *        ref=@Model(type=UpdatePersonalDataRequest::class),
     *     )
     * )
     *
     * @OA\Response(
     *     response=204,
     *     description="",
     * )
     *
     * @Rest\View
     *
     * @param Request $request
     * @param DtoConverter $converter
     * @param ValidationService $validationService
     */
    public function updatePersonalData(Request $request, DtoConverter $converter, ValidationService $validationService): void
    {
        $data = array_merge($request->request->all(), ['id' => $this->getUser()->getId()]);
        $updatePersonalDataRequest = $validationService->validate($converter->convertToDto(
            UpdatePersonalDataRequest::class,
            $data,
        ));

        $this->commandBus->dispatch(UpdatePersonalDataCommandFactory::createFromUpdatePersonalDataRequest(
            $this->getUser()->getId(),
            $updatePersonalDataRequest
        ));
    }

    /**
     * @Rest\Post(path="/email/verify")
     *
     * @Rest\RequestParam(name="token", nullable=false, allowBlank=false, strict=true)
     *
     * @OA\Response(
     *     response=200,
     *     description="",
     *     @OA\JsonContent(
     *        type="object",
     *        @OA\Property(property="token", type="string"),
     *        @OA\Property(property="refresh_token", type="string"),
     *     )
     * )
     *
     * @Rest\View
     *
     * @param ParamFetcherInterface $paramFetcher
     * @param JwtTokenManager $tokenManager
     * @return array
     */
    public function verifyNewUserEmail(ParamFetcherInterface $paramFetcher, JwtTokenManager $tokenManager): array
    {
        $this->commandBus->dispatch(
            new VerifyNewUserEmailCommand($this->getUser()->getId(), $paramFetcher->get('token'))
        );

        return $tokenManager->create($this->getUser());
    }
}
