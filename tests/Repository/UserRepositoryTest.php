<?php


namespace Tests\Repository;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

trait UserRepositoryTest
{

    /**
     * @param KernelBrowser $client
     * @return User|null
     */
    public function findLastUser(KernelBrowser $client)
    {
        $kernel = $client->getKernel();
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $user = $entityManager
            ->getRepository(User::class)
            ->findLast();
        return $user;
    }


    /**
     * @param KernelBrowser $client
     * @param string $name
     * @return User|null
     */
    public function findOneByName(KernelBrowser $client, string $name)
    {
        $kernel = $client->getKernel();
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $user = $entityManager
            ->getRepository(User::class)
            ->findOneByName($name);
        return $user;
    }


    /**
     * @param KernelBrowser $client
     * @param string $name
     * @return User|null
     */
    public function findByRole(KernelBrowser $client, string $role)
    {
        $kernel = $client->getKernel();
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $user = $entityManager
            ->getRepository(User::class)
            ->findByRole($role);
        return $user;
    }




}