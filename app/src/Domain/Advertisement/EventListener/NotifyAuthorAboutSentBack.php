<?php

declare(strict_types=1);

namespace App\Domain\Advertisement\EventListener;

use App\Domain\Advertisement\Event\AdvertisementSentBackEvent;
use App\Domain\Advertisement\Repository\AdvertisementRepositoryInterface;
use App\Domain\Common\Event\DomainEventHandlerInterface;
use App\Domain\Common\Service\SenderInterface;
use App\Domain\User\Repository\UserRepositoryInterface;

class NotifyAuthorAboutSentBack implements DomainEventHandlerInterface
{
    private UserRepositoryInterface $userRepository;

    private AdvertisementRepositoryInterface $advertisementRepository;

    private SenderInterface $emailSender;

    public function __construct(
        UserRepositoryInterface $userRepository,
        AdvertisementRepositoryInterface $advertisementRepository,
        SenderInterface $emailSender
    ) {
        $this->userRepository = $userRepository;
        $this->advertisementRepository = $advertisementRepository;
        $this->emailSender = $emailSender;
    }

    public function __invoke(AdvertisementSentBackEvent $event): void
    {
        $author = $this->userRepository->findOrFail($event->getAuthorId());
        $advertisement = $this->advertisementRepository->findOrFail($event->getAdvertisementId());

        $body = $this->emailSender->generateBody(
            'mails/advertisement/sent_back.html.twig',
            [
                'advertisementTitle' => $advertisement->getTitle(),
                'reason' => $event->getReason(),
            ],
        );

        $this->emailSender->send($author->getEmail(), 'An advertisement was sent back', $body);
    }
}
