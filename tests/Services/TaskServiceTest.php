<?php


namespace Tests\Services;

use App\Entity\Task;
use App\Entity\User;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use App\Service\TaskService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskServiceTest extends KernelTestCase
{
    private $em;

    private $tasks;

    private $taskRepository;

    private $userRepository;

    public function setUp(): void
    {
        $this->userAnonynous = $this->createMock(User::class);
        $this->userAnonynous->method('getId')->willReturn('3');
        $this->userAnonynous->method('getUsername')->willReturn(UserRepository::ANONYMOUS);
        $this->userAnonynous->method('getRoles')->willReturn(["ROLE_ANONYMOUS"]);

        $this->em = $this->createMock(EntityManagerInterface::class);

        $this->tasks = [
            "task1" => new Task(),
            "task2" => new Task()
        ];

        $this->taskRepository = $this->createMock(TaskRepository::class);
        $this->taskRepository->method('findAllByUser')->willReturn($this->tasks);

        $this->userRepository = $this->createMock(UserRepository::class);
    }


    public function testShowRoleAdminOk()
    {
        $service = new TaskService($this->em, $this->taskRepository, $this->userRepository);
        $this->userRepository->method('getAnonymous')->willReturn($this->userAnonynous);
        $user = new User();
        $user->setRoles(['ROLE_ADMIN']);
        $test = $service->show($user);

        $this->assertIsArray($test);
        $this->assertEquals(2, count($test));
    }

    public function testShowRoleUserOk()
    {
        $service = new TaskService($this->em, $this->taskRepository, $this->userRepository);
        $user = new User();
        $users = $service->show($user);

        $this->assertIsArray($users);
        $this->assertEquals(2, count($users));
    }

    public function testShowRoleUserNok()
    {
        $service = new TaskService($this->em, $this->taskRepository, $this->userRepository);
        $this->expectException(\Exception::class);
        $user = new User;
        $user->setRoles(['ROLE_FALSE']);
        $service->show($user);
    }
}
