<?php


namespace Tests\Entity;


use App\Entity\Task;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

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
        $task->setTitle("")
            ->setContent("");
        $this->assertHasErrors($task, 2);
    }

    /**
     @@expectedException \PDOException()
     */
    /*public function testEntityNokWithoutUser()
    {
        $task = (new Task())
            ->setContent('Some content')
            ->setTitle('Some title');


        $kernel = self::bootKernel();
        $entityManager = $kernel->getContainer()
            ->get('doctrine.orm.default_entity_manager');

        ($entityManager->persist($task));
        ($entityManager->flush());


        $test = $this->getMockBuilder(Doctrine\ORM\EntityManagerInterface::class)->getMock();

        $violation = $this->getMockBuilder(ConstraintViolationBuilderInterface::class)->getMock();
        $violation->expects($this->any())->method('setParameter')->willReturn($violation);
        $violation->expects($this->once())->method('addViolation');


        $test->expects($this->any())
            ->method('persist')
            ->with($task)
            ->willReturn($task);
        $test->expects($this->once())
            ->method('flush')
            ->willReturn($violation);



        $this->assertHasErrors($violation, 1);
    }*/



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
        $task->setCreatedAt("01/02/2020");
        $this->assertFalse($task->getCreatedAt() instanceof \DateTime);
    }

}