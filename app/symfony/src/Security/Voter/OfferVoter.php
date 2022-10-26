<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class OfferVoter extends Voter {
    public const EDIT = 'OFFER_EDIT';
    public const VIEW = 'OFFER_VIEW';
    public const DELETE = 'OFFER_VIEW';

    protected function supports(string $attribute, $subject): bool {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::EDIT, self::VIEW, self::DELETE])
            && $subject instanceof \App\Entity\Offer;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        switch ($attribute) {
            case self::EDIT:

                break;
            case self::VIEW:
                // logic to determine if the user can VIEW
                // return true or false
                break;
            case self::DELETE:
                // logic to determine if the user can VIEW
                // return true or false
                break;
        }

        return false;
    }
}
