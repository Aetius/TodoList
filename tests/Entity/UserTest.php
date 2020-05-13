<?php


namespace Tests\Entity;


use App\Entity\Task;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{

    public function testSetTasks()
    {

        $task = new Task();
        $user = new User();
        $user->addTask($task);
        $this->assertTrue(($user->getTasks()) instanceof ArrayCollection);
        $this->assertTrue($user->getTasks()->get("0") instanceof Task);

        $user->removeTask($task);
        $this->assertTrue($user->getTasks()->get("0") === null);
    }


}