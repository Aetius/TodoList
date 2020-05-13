<?php


namespace App\Factory;

use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;

class UserFactory
{

    /**
     * @param UserInterface|null $user
     * @return User
     */
    public function create(UserInterface $user = null)
    {
        $newUser = new User();
        if ($user !== null && in_array('ROLE_ADMIN', $user->getRoles())) {
            $newUser->setRoles(['ROLE_ADMIN']);
        }
        return $newUser;
    }
}
