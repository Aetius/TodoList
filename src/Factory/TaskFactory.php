<?php

namespace App\Factory;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;

class TaskFactory
{
    /**
     * @return Task
     */
    public function create(UserInterface $user)
    {
        $task = new Task();
        /* @var User $user */
        $task->setUser($user);

        return $task;
    }
}
