<?php


namespace Tests\Repository;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

trait TaskRepositoryTest
{

    /**
     * @param KernelBrowser $client
     * @return User|null
     */
    public function findLastTaskByUserId(KernelBrowser $client, User $user)
    {
        $kernel = $client->getKernel();
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $user = $entityManager
            ->getRepository(Task::class)
            ->findOneByUserId($user);
        return $user;
    }
}
