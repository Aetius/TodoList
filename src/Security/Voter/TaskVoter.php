<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class TaskVoter extends Voter
{
    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @param string $attribute
     * @param mixed  $subject
     *
     * @return bool
     */
    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, ['task_edit', 'task_delete', 'task_create', 'task_show'])) {
            return false;
        }

        return true;
    }

    /**
     * @param string $attribute
     * @param mixed  $subject
     *
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        if (!$this->security->isGranted('ROLE_USER')) {
            return false;
        }

        switch ($attribute) {
            case 'task_edit':
                if ($subject->getUser()->getId() === $user->getId()) {
                    return true;
                }
                break;

                case 'task_create':
                    return true;

            case 'task_delete':
                if ($subject->getUser()->getId() === $user->getId()) {
                    return true;
                }
                if (in_array('ROLE_ANONYMOUS', $subject->getUser()->getRoles()) &&
                    $this->security->isGranted('ROLE_ADMIN')) {
                    return true;
                }
                break;

            case 'task_show':
                    return true;
        }

        return false;
    }
}
