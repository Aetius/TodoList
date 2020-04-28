<?php


namespace Tests\Controller;


use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tests\Config\Config;
use Tests\Repository\UserRepositoryTest;
use Tests\Security\Connexion;

class UserControllerTest extends WebTestCase
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

/*****  Tests users action : display the list of all users  *******/
    public function testListAction()
    {
        $this->setAuthorization($this->client);
        $this->client->request('GET', '/users');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testListActionWithoutAuthorization()
    {
        $this->client->request('GET', '/users');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isRedirect(Config::BASE_URI.'/login'));
    }

/**** create Actions   *****/
    public function testCreateActionOk()
    {
        $this->setAuthorization($this->client);
        $crawler = $this->client->request('GET', '/users/create');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $button = $crawler->selectButton('Ajouter');
        $form = $button->form();
        $form["user[username]"] = "test";
        $form["user[password][first]"] = "test";
        $form["user[password][second]"] = "test";
        $form["user[email]"] = "test@test.fr";
        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect('/users'));
    }

    public function testCreateActionNokUserAlreadyUsed()
    {
        $this->setAuthorization($this->client);
        $crawler = $this->client->request('GET', '/users/create');

        $button = $crawler->selectButton('Ajouter');
        $form = $button->form();
        $form["user[username]"] = "demo";
        $form["user[password][first]"] = "test";
        $form["user[password][second]"] = "test";
        $form["user[email]"] = "test@test.fr";
        $this->client->submit($form);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testCreateActionNokEmailAlreadyUsed()
    {
        $this->setAuthorization($this->client);
        $crawler = $this->client->request('GET', '/users/create');

        $button = $crawler->selectButton('Ajouter');
        $form = $button->form();
        $form["user[username]"] = "test";
        $form["user[password][first]"] = "test";
        $form["user[password][second]"] = "test";
        $form["user[email]"] = "demo@demo.fr";
        $this->client->submit($form);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testCreateActionNokPasswordFailed()
    {
        $this->setAuthorization($this->client);
        $crawler = $this->client->request('GET', '/users/create');

        $button = $crawler->selectButton('Ajouter');
        $form = $button->form();
        $form["user[username]"] = "test";
        $form["user[password][first]"] = "test";
        $form["user[password][second]"] = "failed";
        $form["user[email]"] = "test@test.fr";
        $this->client->submit($form);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testCreateActionGetPageWithoutAuthorization()
    {
        $this->client->request('GET', '/users/create');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isRedirect(Config::BASE_URI.'/login'));
    }



/**** Edit Actions   *****/
    public function testEditActionOk()
    {
        $this->setAuthorization($this->client);
        $crawler = $this->client->request('GET', '/users/1/edit');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $button = $crawler->selectButton('Modifier');
        $form = $button->form();
        $form["user[username]"] = "demo";
        $form["user[password][first]"] = "demo";
        $form["user[password][second]"] = "demo";
        $form["user[email]"] = "demo@demo.fr";
        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect('/users'));
    }

    public function testEditActionNokUserAlreadyUsed()
    {
        $this->setAuthorization($this->client);
        $crawler = $this->client->request('GET', '/users/1/edit');

        $button = $crawler->selectButton('Modifier');
        $form = $button->form();
        $form["user[username]"] = "demo2";
        $form["user[password][first]"] = "demo";
        $form["user[password][second]"] = "demo";
        $form["user[email]"] = "demo@demo.fr";
        $this->client->submit($form);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testEditActionNokEmailAlreadyUsed()
    {
        $this->setAuthorization($this->client);
        $crawler = $this->client->request('GET', '/users/1/edit');

        $button = $crawler->selectButton('Modifier');
        $form = $button->form();
        $form["user[username]"] = "demo";
        $form["user[password][first]"] = "demo";
        $form["user[password][second]"] = "demo";
        $form["user[email]"] = "demo2@demo.fr";
        $this->client->submit($form);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testEditActionNokPasswordFalse()
    {
        $this->setAuthorization($this->client);
        $crawler = $this->client->request('GET', '/users/1/edit');

        $button = $crawler->selectButton('Modifier');
        $form = $button->form();
        $form["user[username]"] = "demo";
        $form["user[password][first]"] = "demo";
        $form["user[password][second]"] = "failed";
        $form["user[email]"] = "demo@demo.fr";
        $this->client->submit($form);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}