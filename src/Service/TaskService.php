<?php


namespace App\Service;

use App\Entity\Task;
use App\Entity\User;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Security\Core\User\UserInterface;

class TaskService
{

    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var TaskRepository
     */
    private $repository;
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(EntityManagerInterface $em, TaskRepository $repository, UserRepository $userRepository)
    {
        $this->em = $em;
        $this->repository = $repository;
        $this->userRepository = $userRepository;
    }

    /**
     * @param Task $task
     */
    public function save(Task $task)
    {
        $this->em->persist($task);
        $this->em->flush();
    }

    /**
     * @param Task $task
     * @return Task
     */
    public function updateToggle(Task $task)
    {
        $task->toggle(!$task->isDone());
        return $task;
    }

    /**
     * @param UserInterface $user
     * @return Task[]
     */
    public function show(UserInterface $user)
    {
        /**@var User $user */
        if (in_array('ROLE_USER', $user->getRoles())) {
            return $this->repository->findAllByUser($user);
        }
        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            $anonymous = $this->userRepository->getAnonymous();
            $tasksAnonymous = $this->repository->findAllByUser($anonymous);
            $tasksUser = $this->repository->findAllByUser($user);
            $tasks = array_merge($tasksAnonymous, $tasksUser);
            return $tasks;
        }
        throw new Exception('You must be logged to access this.');
    }

    /**
     * @param Task $task
     */
    public function delete(Task $task)
    {
        $this->em->remove($task);
        $this->em->flush();
    }
}
