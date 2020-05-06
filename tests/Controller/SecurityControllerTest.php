<?php


namespace Tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tests\Config\Config;

class SecurityControllerTest extends WebTestCase
{
    protected $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testLoginSubmitFormOk()
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'demo',
            '_password' => 'demo'
        ]);
        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect('/'));
    }

    public function testLoginSubmitFormNokAnonymousProfile()
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'anonymous',
            '_password' => 'anonymous'
        ]);
        $this->client->submit($form);
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());

    }

    public function testLoginSubmitFailedForm()
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'raté',
            '_password' => 'falsePassword'
        ]);
        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect('/login'));
    }
}