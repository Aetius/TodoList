<?php

namespace Tests\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
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
        $this->setAuthorization($this->client);
        $this->client->request('GET', '/');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testHomepageNok()
    {
        $this->client->request('GET', '/');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->client->getResponse()->isRedirect("/login");
    }
}
