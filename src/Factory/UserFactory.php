<?php

namespace App\Factory;

use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;

class UserFactory
{
    /**
     * @return User
     */
    public function create(UserInterface $user = null)
    {
        $newUser = new User();
        if (null !== $user && in_array('ROLE_ADMIN', $user->getRoles())) {
            $newUser->setRoles(['ROLE_ADMIN']);
        }

        return $newUser;
    }
}
