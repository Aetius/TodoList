<?php


namespace Tests\Factory;


use App\Entity\Task;
use App\Entity\User;
use App\Factory\TaskFactory;
use PHPUnit\Framework\TestCase;

class TaskFactoryTest extends TestCase
{

    protected $user;
    protected function setUp() :void
    {
        $this->user = $this->getMockBuilder("App\Entity\User")
            ->disableOriginalConstructor()
            ->getMock();

    }

    public function testNewTask()
    {
        $factory = new TaskFactory();
        $task = $factory->create($this->user);

        $this->assertInstanceOf(Task::class, $task);
        $this->assertInstanceOf(User::class, $task->getUser());
    }
}