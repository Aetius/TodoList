<?php


namespace App\Fixtures\Processor;

use App\Entity\User;
use Fidry\AliceDataFixtures\ProcessorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserProcessor implements ProcessorInterface
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function preProcess(string $id, $object): void
    {
        if (false === $object instanceof User) {
            return;
        }
        /** @var User $object */
        $object->setPassword($this->encoder->encodePassword($object, $object->getPassword()));
    }

    public function postProcess(string $id, $object): void
    {
        // do nothing
    }
}
