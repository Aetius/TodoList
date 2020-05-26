<?php

namespace Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskTest extends KernelTestCase
{
    public function testEntityOk()
    {
        $task = $this->getEntity();
        $this->assertHasErrors($task, 0);
        $this->assertTrue($task->getCreatedAt() instanceof \DateTime);
    }

    public function testEntityNokEntriesBlank()
    {
        $task = $this->getEntity();
        $task->setTitle('')
            ->setContent('');
        $this->assertHasErrors($task, 2);
    }

    public function testEntityNokWithoutUser()
    {
        $task = (new Task())
            ->setContent('Some content')
            ->setTitle('Some title');
        $this->assertHasErrors($task, 1);
    }

    /**** Methods for the validation *****/
    public function getEntity()
    {
        self::bootKernel();

        $user = (new User())
            ->setEmail('test@test.fr')
            ->setUsername('test');
        $user->setPassword(self::$container->get('Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface')->encodePassword($user, 'demo'));

        return (new Task())
            ->setContent('Some content')
            ->setTitle('Some title')
            ->setUser($user);
    }

    public function assertHasErrors(Task $task, int $numberErrorsExpecting)
    {
        self::bootKernel();
        $error = self::$container->get('validator')->validate($task);
        $this->assertCount($numberErrorsExpecting, $error);
    }

    public function testUpdateSetDate()
    {
        $task = new Task();
        $task->setCreatedAt(new \DateTime());
        $this->assertTrue($task->getCreatedAt() instanceof \DateTime);
    }

    public function testUpdateSetDateNok()
    {
        $task = new Task();
        $task->setCreatedAt('01/02/2020');
        $this->assertFalse($task->getCreatedAt() instanceof \DateTime);
    }
}
