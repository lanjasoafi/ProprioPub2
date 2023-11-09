<?php 

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccountExpiredException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof User) {
            return;
        }

       
        
    }

    public function checkPostAuth(UserInterface $user): void
    {
        if (!$user instanceof User) {
            return;
        }
        if ($user->getIsVerified() !== true) {
            // L'utilisateur n'est pas vérifié
            throw new CustomUserMessageAccountStatusException('Votre compte n\'est pas vérifié. Veuillez le confirmer en cliquant sur le lien dans votre boîte mail.');
        }
    }
}