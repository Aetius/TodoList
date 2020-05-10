<?php


namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    const ANONYMOUS = "anonymous";


    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @return User|null
     */
    public function findLast()
    {
        return $this->findOneBy([], ['id'=> 'DESC']);
    }

    public function findAllExceptAnonymous()
    {
       return( $this->createQueryBuilder('p')
        ->where("p.roles LIKE'[\"ROLE_USER\"]'")
        ->orWhere("p.roles LIKE'[\"ROLE_ADMIN\"]'")
           ->getQuery()
           ->getResult());

    }
    /**
     * @param string $name
     * @return User|null
     */
    public function findOneByName(string $name)
    {
        return $this->findOneBy(['username'=>$name]);
    }

}