<?php


namespace Tests\Services;


use App\Entity\Task;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\TaskService;
use PHPUnit\Util\Exception;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskServiceTest extends KernelTestCase
{
    private $userInterfaceAdmin;
    private $userAnonymous;
    private $em;
    private $tasks;
    private $taskRepository;
    private $userRepository;
    private $userInterfaceUser;
    private $userRoleUser;
    private $wrongUserInterface;


    public function setUp() : void
    {
        $this->userInterfaceAdmin = $this->getMockBuilder('Symfony\Component\Security\Core\User\UserInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $this->userInterfaceAdmin->method('getRoles')->willReturn(["ROLE_ADMIN"]);

        $this->userInterfaceUser = $this->getMockBuilder('Symfony\Component\Security\Core\User\UserInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $this->userInterfaceUser->method('getRoles')->willReturn(["ROLE_USER"]);

        $this->wrongUserInterface = $this->getMockBuilder('Symfony\Component\Security\Core\User\UserInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $this->wrongUserInterface->method('getRoles')->willReturn(["ROLE_FALSE"]);


        $this->userAnonynous = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->userAnonynous->method('getId')->willReturn('3');
        $this->userAnonynous->method('getUsername')->willReturn(UserRepository::ANONYMOUS);
        $this->userAnonynous->method('getRoles')->willReturn(["ROLE_ANONYMOUS"]);

        $this->userRoleUser = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->userRoleUser->method('getId')->willReturn('2');
        $this->userRoleUser->method('getUsername')->willReturn("demo");
        $this->userRoleUser->method('getRoles')->willReturn(["ROLE_USER"]);

        $this->em = $this->getMockBuilder('Doctrine\ORM\EntityManagerInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->tasks = [
            "task1" => new Task(),
            "task2" => new Task()
        ];

        $this->taskRepository = $this->getMockBuilder('App\Repository\TaskRepository')
            ->disableOriginalConstructor()
            ->getMock();
        $this->taskRepository->method('findAllByUser')->willReturn($this->tasks);


        $this->userRepository = $this->getMockBuilder('App\Repository\UserRepository')
            ->disableOriginalConstructor()
            ->getMock();

    }


    public function testShowRoleAdminOk()
    {
        $service = new TaskService($this->em, $this->taskRepository, $this->userRepository);

        $this->userRepository->method('findOneByName')->willReturn($this->userAnonynous);

        $test = $service->show($this->userInterfaceAdmin);

        $this->assertIsArray($test);
        $this->assertEquals(2, count($test));
    }

    public function testShowRoleUserOk()
    {
        $service = new TaskService($this->em, $this->taskRepository, $this->userRepository);

        $test = $service->show($this->userRoleUser);

        $this->assertIsArray($test);
        $this->assertEquals(2, count($test));
    }

    public function testShowRoleUserNok()
    {
        $service = new TaskService($this->em, $this->taskRepository, $this->userRepository);
        $this->expectException(Exception::class);
        $service->show($this->wrongUserInterface);

    }

}