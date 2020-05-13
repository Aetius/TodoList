<?php


namespace Tests\Repository;

use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RepositoryTest extends KernelTestCase
{
    public function testFindAllUsersNull()
    {
        $kernel = self::bootKernel();


        /** @var EntityManagerInterface $entityManager */
        $entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $users = $entityManager
            ->getRepository(Task::class)
            ->findAllUsersNull();

        $this->assertIsArray($users);
        $this->assertEquals(0, count($users));
    }
}
