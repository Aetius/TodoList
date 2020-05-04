<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class UserVoter extends Voter
{

    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, ['admin_access', "form_user", "edit_user"])){
            return false;
        }


        if (!is_null($subject) && !$subject instanceof User){
            return false;
        }

        return true;

    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        if (!$this->security->isGranted("ROLE_USER")){
            return false;
        }

        switch ($attribute) {
            case 'admin_access':
                if($this->security->isGranted("ROLE_ADMIN")){
                    return true;
                }
                break;
            case 'form_user':
                if($this->security->isGranted("ROLE_ADMIN")){
                    return true;
                }
                break;
            case 'edit_user':
                if ($subject->getId() === $user->getId()) {
                    return true;
                }
                if ($this->security->isGranted("ROLE_ADMIN")){
                    return true;
                }
                break;
        }

        return false;
    }
}
