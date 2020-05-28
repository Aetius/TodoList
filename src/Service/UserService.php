<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserService
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var UserRepository
     */
    private $repository;

    public function __construct(UserPasswordEncoderInterface $encoder, EntityManagerInterface $em, UserRepository $repository)
    {
        $this->encoder = $encoder;
        $this->em = $em;
        $this->repository = $repository;
    }

    public function manager(User $user)
    {
        $password = $this->encoder->encodePassword($user, $user->getPassword());
        $user->setPassword($password);

        return $user;
    }

    public function update(User $user, string $clearPassword = null)
    {
        if ($clearPassword !== null){
            $password = $this->encoder->encodePassword($user, $clearPassword);
            $user->setPassword($password);
        }

        return $user;
    }

    public function save(User $user)
    {
        $this->em->persist($user);
        $this->em->flush();
    }

    public function getUsers()
    {
        $users = $this->repository->findAllExceptAnonymous();

        return $users;
    }
}
