<?php


namespace App\Command;

use App\Entity\User;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class JoinUserToTaskCommand extends Command
{

    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var TaskRepository
     */
    private $taskRepository;

    public function __construct(
        UserRepository $userRepository,
        TaskRepository $taskRepository,
        EntityManagerInterface $em,
        string $name = null
    )
    {
        $this->userRepository = $userRepository;
        $this->em = $em;
        $this->taskRepository = $taskRepository;
        parent::__construct($name);
    }


    protected static $defaultName = 'app:add-anonymous-user-to-table';

    protected function configure()
    {
        $this->setDescription('Add an anonymous user to the task if user_id is null. Create a new anonymous user or find it in database.');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $user = $this->anonymousUser();
        $tasks = $this->addUserToTask($user);
        $output->writeln(count($tasks)." modification(s) have been done.");
    }

    /**
     * @return User
     */
    private function anonymousUser()
    {
        $user = $this->userRepository->getAnonymous();
        if (($user === null)) {
            $user = (new User())
                ->setUsername(UserRepository::ANONYMOUS)
                ->setEmail("anonymous@anonymous.fr")
                ->setPassword(UserRepository::ANONYMOUS)
                ->setRoles(['ROLE_ANONYMOUS']);
            $this->em->persist($user);
            $this->em->flush();
        }
        return $user;
    }

    /**
     * @param User $user
     * @return \App\Entity\Task[]
     */
    private function addUserToTask(User $user)
    {
        $tasks = $this->taskRepository->findAllUsersNull();
        foreach ($tasks as $task) {
            $task->setUser($user);
        }
        $this->em->flush();
        return $tasks;
    }
}
