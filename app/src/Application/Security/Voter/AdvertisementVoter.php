<?php

declare(strict_types=1);

namespace App\Application\Security\Voter;

use App\Application\Enum\PermissionEnum;
use App\Domain\Advertisement\Entity\Advertisement;
use App\Domain\User\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class AdvertisementVoter extends Voter
{
    protected function supports(string $attribute, $subject): bool
    {
        return $subject instanceof Advertisement
            && in_array($attribute, [
                PermissionEnum::ADVERTISEMENT_EDIT,
                PermissionEnum::ADVERTISEMENT_SEND_TO_REVIEW,
                PermissionEnum::ADVERTISEMENT_PUBLISH,
                PermissionEnum::ADVERTISEMENT_ARCHIVE,
            ])
        ;
    }

    /**
     * @param string $attribute
     * @param Advertisement $subject
     * @param TokenInterface $token
     * @return bool
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        /** @var User $user */
        $user = $token->getUser();
        if (!$user) {
            return false;
        }

        if (in_array($attribute, [PermissionEnum::ADVERTISEMENT_EDIT, PermissionEnum::ADVERTISEMENT_SEND_TO_REVIEW])) {
            return $subject->isAuthor($user);
        }

        if (PermissionEnum::ADVERTISEMENT_PUBLISH === $attribute) {
            return $user->isManagerOrAdmin();
        }

        if (PermissionEnum::ADVERTISEMENT_ARCHIVE === $attribute) {
            return $user->isManagerOrAdmin() || $subject->isAuthor($user);
        }

        return false;
    }
}
