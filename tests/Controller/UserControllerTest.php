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
        $user = $this->findOneByName($this->client, Config::ROLE_ADMIN);
        $this->setAuthorization($this->client, $user);
        $this->client->request('GET', '/users');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testListActionWithoutAuthorization()
    {
        $this->client->request('GET', '/users');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isRedirect(Config::BASE_URI.'/login'));
    }

    public function testListActionNokWithRoleUser()
    {
        $user = $this->findOneByName($this->client, Config::ROLE_USER);
        $this->setAuthorization($this->client, $user);
        $this->client->request('GET', '/users');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isRedirect(Config::BASE_URI.'/login'));
    }

/**** create Actions   *****/
    public function testCreateActionOkWithoutRole()
    {
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

    public function testCreateActionOkWithRoleAdmin()
    {
        $user = $this->findOneByName($this->client, Config::ROLE_ADMIN);
        $this->setAuthorization($this->client, $user);
        $crawler = $this->client->request('GET', '/users/create');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $button = $crawler->selectButton('Ajouter');
        $form = $button->form();
        $form["user[username]"] = "test";
        $form["user[password][first]"] = "test";
        $form["user[password][second]"] = "test";
        $form["user[email]"] = "test@test.fr";
        $form["user[roles]"] = "ROLE_ADMIN";

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect('/users'));
    }

    public function testCreateActionNokUserAlreadyUsed()
    {
        $user = $this->findLastUser($this->client);
        $this->setAuthorization($this->client, $user);
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
        $user = $this->findLastUser($this->client);
        $this->setAuthorization($this->client, $user);
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
        $user = $this->findLastUser($this->client);
        $this->setAuthorization($this->client, $user);
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

/*    public function testCreateActionGetPageWithoutAuthorization()
    {
        $this->client->request('GET', '/users/create');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isRedirect(Config::BASE_URI.'/login'));
    }*/



/**** Edit Actions   *****/
    public function testEditActionOkRoleAdmin()
    {
        $user = $this->findOneByName($this->client, Config::ROLE_ADMIN );
        $this->setAuthorization($this->client, $user);
        $crawler = $this->client->request('GET', '/users/2/edit');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $button = $crawler->selectButton('Modifier');
        $form = $button->form();
        $form["user[username]"] = "demo2";
        $form["user[password][first]"] = "demo";
        $form["user[password][second]"] = "demo";
        $form["user[email]"] = "demo2@demo.fr";
        $form["user[roles]"] = "ROLE_ADMIN";
        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect('/users'));
    }

    public function testEditActionOkRoleUser()
    {
        $user = $this->findOneByName($this->client, Config::ROLE_USER );
        $this->setAuthorization($this->client, $user);
        $crawler = $this->client->request('GET', '/users/2/edit');
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

    public function testEditActionNokRoleUserTryChangeRole()
    {
        $user = $this->findOneByName($this->client, Config::ROLE_USER );
        $this->setAuthorization($this->client, $user);

        $crawler = $this->client->request('GET', '/users/2/edit');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());


        $token = ($this->client->getContainer()->get('security.csrf.token_manager')->getToken('user')->getValue());
        $this->client->request(
            'POST',
            '/users/2/edit',
            [
                'user'=>[
                    'username'=>'demo2',
                    "password"=>[
                        "first" =>'demo',
                        "second"=>'demo'
                    ],
                    'email'=> 'demo2@demo.fr',
                    'roles'=> 'ROLE_ADMIN',
                    '_token'=>$token
                ]
            ],
        );

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
//This form should not contain extra fields.


    public function testEditActionNokUserAlreadyUsed()
    {
        $user = $this->findLastUser($this->client);
        $this->setAuthorization($this->client, $user);
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
        $user = $this->findLastUser($this->client);
        $this->setAuthorization($this->client, $user);
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
        $user = $this->findLastUser($this->client);
        $this->setAuthorization($this->client, $user);
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