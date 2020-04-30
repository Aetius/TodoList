<?php


namespace Tests\Controller;


use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tests\Config\Config;
use Tests\Repository\UserRepositoryTest;
use Tests\Security\Connexion;

class TaskControllerTest extends WebTestCase
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

/****  Task list  ****/
    public function testListActionOk()
    {
        $this->setAuthorization($this->client);
        $this->client->request('GET', '/tasks');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testListActionNok()
    {
        $this->client->request('GET', '/tasks');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isRedirect(Config::BASE_URI.'/login'));
    }

/**** task create  ****/
    public function testCreateActionOk()
    {
        $this->setAuthorization($this->client);
        $crawler = $this->client->request('GET', '/tasks/create');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $button = $crawler->selectButton('Ajouter');
        $form = $button->form();
        $form["task[title]"] = "test";
        $form["task[content]"] = "test";
        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect('/tasks'));
    }

    public function testCreateActionGetNokWithoutAuthorization()
    {
        $this->client->request('GET', '/tasks/create');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isRedirect(Config::BASE_URI.'/login'));
    }

    public function testCreateActionPostNokWithoutAuthorization()
    {
        $token = ($this->client->getContainer()->get('security.csrf.token_manager')->getToken('task')->getValue());
        $this->client->request(
            'POST',
            '/tasks/create',
            [
                'task'=>[
                    'title'=>'test',
                    'content'=>'test',
                   '_token'=>$token
                ]
            ],
        );
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isRedirect(Config::BASE_URI.'/login'));
    }

    public function testCreateActionNokDatasEmpty()
    {
        $this->setAuthorization($this->client);
        $crawler = $this->client->request('GET', '/tasks/create');

        $button = $crawler->selectButton('Ajouter');
        $form = $button->form();
        $form["task[title]"] = "";
        $form["task[content]"] = "";
        $this->client->submit($form);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

/****  task edit  ****/
    public function testEditActionOk()
    {
        $this->setAuthorization($this->client);
        $crawler = $this->client->request('GET', '/tasks/1/edit');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $button = $crawler->selectButton('Modifier');
        $form = $button->form();
        $form["task[title]"] = "test";
        $form["task[content]"] = "test";
        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect('/tasks'));
    }

    public function testEditActionGetNokWithoutAuthorization()
    {
        $this->client->request('GET', '/tasks/1/edit');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isRedirect(Config::BASE_URI.'/login'));
    }

    public function testEditActionNokDatasEmpty()
    {
        $this->setAuthorization($this->client);
        $crawler = $this->client->request('GET', '/tasks/1/edit');

        $button = $crawler->selectButton('Modifier');
        $form = $button->form();
        $form["task[title]"] = "";
        $form["task[content]"] = "";
        $this->client->submit($form);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testEditActionPostNokWithoutAuthorization()
    {
        $token = ($this->client->getContainer()->get('security.csrf.token_manager')->getToken('task')->getValue());
        $this->client->request(
            'POST',
            '/tasks/1/edit',
            [
                'task'=>[
                    'title'=>'test',
                    'content'=>'test',
                    '_token'=>$token
                ]
            ],
        );
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isRedirect(Config::BASE_URI.'/login'));
    }


/**** Delete Task ****/
    public function testDeleteTaskActionOk()
    {
        $this->setAuthorization($this->client);
        $this->client->request(
            'POST',
            '/tasks/1/delete',
            [],
        );
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isRedirect('/tasks'));
    }

    public function testDeleteTaskActionNokWithoutAuthorization()
    {
        $this->client->request(
            'POST',
            '/tasks/1/delete',
            [],
        );
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isRedirect(Config::BASE_URI.'/login'));
    }


/**** Task toggle ****/

    public function testToggleTaskActionOk()
    {
        $this->setAuthorization($this->client);
        $this->client->request(
            'POST',
            '/tasks/1/toggle',
            [],
        );
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isRedirect('/tasks'));
    }

    public function testToggleTaskActionNokWithoutAuthorization()
    {
        $this->client->request(
            'POST',
            '/tasks/1/toggle',
            [],
        );
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isRedirect(Config::BASE_URI.'/login'));
    }

}