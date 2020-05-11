<?php


namespace App\Repository;


use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    /**
     * @param User $user
     * @return Task|null
     */
    public function findOneByUserId(User $user)
    {
        return $this->findOneBy(['user'=>$user], ['id'=>'DESC']);
    }

    /**
     * @param User $user
     * @return Task[]
     */
    public function findAllByUser(User $user)
    {
        return $this->findBy(['user'=>$user]);
    }

    /**
     * @return Task[]
     */
    public function findAllUsersNull()
    {
        return $this->findBy(['user'=>null]);
    }


}