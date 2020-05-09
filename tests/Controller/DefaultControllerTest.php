<?php

namespace Tests\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tests\Config\Config;
use Tests\Repository\UserRepositoryTest;
use Tests\Security\Connexion;

class DefaultControllerTest extends WebTestCase
{
    use Connexion;
    use UserRepositoryTest;
    /**
     *@var  KernelBrowser
     */
    protected $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testHomepage()
    {
        $user = $this->findLastUser($this->client);
        $this->setAuthorization($this->client, $user);
        $this->client->request('GET', '/');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testHomepageNokWithoutAuthorization()
    {
        $this->client->request('GET', '/');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isRedirect('/login'));
    }

    public function testWrongUrl()
    {
        $this->client->request('GET', '/wrongUrl');
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
    }
}
