<?php

namespace Tests\Factory;

use App\Entity\User;
use App\Factory\UserFactory;
use PHPUnit\Framework\TestCase;

class UserFactoryTest extends TestCase
{
    protected $user;

    protected function setUp(): void
    {
        $this->user = $this->getMockBuilder("App\Entity\User")
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testNewUserByRoleUser()
    {
        $this->user->method('getRoles')->willReturn(['ROLE_USER']);

        $factory = new UserFactory();
        $newUser = $factory->create($this->user);

        $this->assertInstanceOf(User::class, $newUser);
        $this->assertEquals(['ROLE_USER'], $newUser->getRoles());
    }

    public function testNewUserByRoleAdmin()
    {
        $this->user->method('getRoles')->willReturn(['ROLE_ADMIN']);

        $factory = new UserFactory();
        $newUser = $factory->create($this->user);

        $this->assertInstanceOf(User::class, $newUser);
        $this->assertEquals(['ROLE_ADMIN'], $newUser->getRoles());
    }
}
