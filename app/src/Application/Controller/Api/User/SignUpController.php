<?php

declare(strict_types=1);

namespace App\Application\Controller\Api\User;

use App\Application\Converter\DtoConverter;
use App\Application\Factory\Command\SignUpCommandFactory;
use App\Application\Http\Request\User\SignUpRequest;
use App\Application\Manager\JwtTokenManager;
use App\Application\Service\ValidationService;
use App\Domain\User\Entity\User;
use App\Domain\User\UseCase\VerifyUserEmailCommand;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;

/**
 * @Rest\Route(path="/sign-up")
 *
 * @OA\Tag(name="Sign-Up")
 */
class SignUpController extends AbstractFOSRestController
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
     * @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *        type="object",
     *        ref=@Model(type=SignUpRequest::class),
     *     )
     * )
     *
     * @OA\Response(
     *     response=200,
     *     description="",
     *     @OA\JsonContent(
     *        type="object",
     *        @OA\Property(property="id", type="string")
     *     )
     * )
     *
     * @Rest\View
     *
     * @param Request $request
     * @param DtoConverter $converter
     * @param ValidationService $validationService
     * @return array
     */
    public function signUp(Request $request, DtoConverter $converter, ValidationService $validationService)
    {
        $signUpRequest = $validationService->validate(
            $converter->convertToDto(SignUpRequest::class, $request->request->all())
        );

        $command = SignUpCommandFactory::createFromSignUpRequest($signUpRequest);

        $this->commandBus->dispatch($command);

        return ['id' => $command->getId()];
    }

    /**
     * @Rest\Post(path="/verify/email/{confirmationToken}")
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
     * @param User $user
     * @param JwtTokenManager $tokenManager
     * @return array
     */
    public function verifyEmailAction(User $user, JwtTokenManager $tokenManager)
    {
        $this->commandBus->dispatch(
            new VerifyUserEmailCommand($user->getId())
        );

        return $tokenManager->create($user);
    }
}
