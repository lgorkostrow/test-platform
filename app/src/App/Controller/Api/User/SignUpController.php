<?php

declare(strict_types=1);

namespace App\App\Controller\Api\User;

use App\App\Converter\DtoConverter;
use App\App\Factory\Command\SignUpCommandFactory;
use App\App\Http\Request\User\SignUpRequest;
use App\App\Manager\JwtTokenManager;
use App\App\Service\ValidationService;
use App\Domain\User\Entity\User;
use App\Domain\User\UseCase\VerifyUserEmailCommand;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @Rest\Route(path="/sign-up")
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
