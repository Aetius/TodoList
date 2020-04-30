<?php


namespace Tests\Security;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tests\Config\Config;

class SecurityControllerTest extends WebTestCase
{
    protected $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testLoginSubmitForm()
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'demo',
            '_password' => 'demo'
        ]);
        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect(Config::BASE_URI.'/'));
    }

    public function testLoginSubmitFailedForm()
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'raté',
            '_password' => 'falsePassword'
        ]);
        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect(Config::BASE_URI.'/login'));
    }
}