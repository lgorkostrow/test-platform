<?php

declare(strict_types=1);

namespace App\Domain\User\EventListener;

use App\Domain\Common\Event\DomainEventHandlerInterface;
use App\Domain\Common\Service\FrontendUrlsGenerator;
use App\Domain\Common\Service\SenderInterface;
use App\Domain\User\Event\UserCreatedEvent;
use App\Domain\User\Repository\UserRepositoryInterface;

class VerifyUserEmail implements DomainEventHandlerInterface
{
    private UserRepositoryInterface $repository;

    private SenderInterface $emailSender;

    private FrontendUrlsGenerator $urlsGenerator;

    public function __construct(UserRepositoryInterface $repository, SenderInterface $emailSender, FrontendUrlsGenerator $urlsGenerator)
    {
        $this->repository = $repository;
        $this->emailSender = $emailSender;
        $this->urlsGenerator = $urlsGenerator;
    }

    public function __invoke(UserCreatedEvent $event): void
    {
        $user = $this->repository->findOrFail($event->getUserId());
        if ($user->isEmailConfirmed()) {
            return;
        }

        $body = $this->emailSender->generateBody(
            'mails/user/user_verification.html.twig',
            ['url' => $this->urlsGenerator->generateUserVerificationUrl($user->getConfirmationToken())],
        );

        $this->emailSender->send($user->getEmail(), 'Email verification', $body);
    }
}
