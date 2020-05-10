<?php


namespace App\Service;


use App\Entity\User;
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

    public function __construct(UserPasswordEncoderInterface $encoder, EntityManagerInterface $em)
    {
        $this->encoder = $encoder;
        $this->em = $em;
    }

    public function manager(User $user, array $datas)
    {
        $password = $this->encoder->encodePassword($user, $user->getPassword());
        $user->setPassword($password);
        if (isset($datas['roles'])){
            $user->setRoles([$datas['roles']]);
        }
        return $user;
    }

    public function save(User$user)
    {
        $this->em->persist($user);
        $this->em->flush();
    }

}