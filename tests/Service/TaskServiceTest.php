<?php


namespace Tests\Service;


use App\Entity\Task;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskServiceTest extends KernelTestCase
{

    public function testSave()
    {
        $task = (new Task())
            ->setContent('Some content')
            ->setTitle('Some title');


        $kernel = self::bootKernel();
        $entityManager = $kernel->getContainer()
            ->get('doctrine.orm.default_entity_manager');

        ($entityManager->persist($task));
        ($entityManager->flush());
        $this->expectException(\Exception::class);
    }
}