<?php


namespace App\Service;


use App\Entity\Task;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class TaskService
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function create(Task $task, UserInterface $user)
    {
        /** @var User $user */
        $task->setUser($user);
        return $task;
    }

    public function save(Task $task)
    {
        $this->em->persist($task);
        $this->em->flush();
    }
}