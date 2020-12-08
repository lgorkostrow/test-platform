<?php

declare(strict_types=1);

namespace App\Domain\User\EventListener;

use App\Domain\Common\Event\DomainEventHandlerInterface;
use App\Domain\Common\Service\FrontendUrlsGenerator;
use App\Domain\Common\Service\SenderInterface;
use App\Domain\User\Event\EmailChangedEvent;
use App\Domain\User\Repository\UserRepositoryInterface;

class VerifyNewUserEmail implements DomainEventHandlerInterface
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

    public function __invoke(EmailChangedEvent $event): void
    {
        $user = $this->repository->findOrFail($event->getUserId());

        $body = $this->emailSender->generateBody(
            'mails/user/new_user_email_verification.html.twig',
            ['url' => $this->urlsGenerator->generateNewUserEmailVerificationUrl($user->getConfirmationToken())],
        );

        $this->emailSender->send($event->getNewEmail(), 'New email verification', $body);
    }
}