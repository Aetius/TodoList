<?php


namespace Tests\Controller;


use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tests\Config\Config;
use Tests\Repository\TaskRepositoryTest;
use Tests\Repository\UserRepositoryTest;
use Tests\Security\Connexion;

class TaskControllerTest extends WebTestCase
{

    use Connexion;
    use UserRepositoryTest;
    use TaskRepositoryTest;

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
        $user = $this->findLastUser($this->client);
        $this->setAuthorization($this->client, $user);

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
        $user = $this->findLastUser($this->client);
        $this->setAuthorization($this->client, $user);
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
        $user = $this->findLastUser($this->client);
        $this->setAuthorization($this->client, $user);
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
        $user = $this->findLastUser($this->client);
        $this->setAuthorization($this->client, $user);
        $task = $this->findLastTaskByUserId($this->client, $user)->getId();
        $crawler = $this->client->request('GET', "/tasks/$task/edit");
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
        $user = $this->findLastUser($this->client);
        $this->setAuthorization($this->client, $user);
        $task = $this->findLastTaskByUserId($this->client, $user)->getId();
        $crawler = $this->client->request('GET', "/tasks/$task/edit");

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
    public function testDeleteTaskActionByOwnerTaskOk()
    {
        $user = $this->findLastUser($this->client);
        $this->setAuthorization($this->client, $user);
        $task = $this->findLastTaskByUserId($this->client, $user)->getId();

        $this->client->request(
            'GET',
            "/tasks/$task/delete",
            [],
        );
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isRedirect('/tasks'));
    }

    public function testDeleteTaskActionNokWrongUserForTask()
    {
        $user = $this->findLastUser($this->client);
        $this->setAuthorization($this->client, $user);

        $this->client->request(
            'GET',
            "/tasks/1/delete",
            [],
        );
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());

    }

    public function testDeleteTaskActionNokWithoutAuthorization()
    {
        $this->client->request(
            'GET',
            '/tasks/1/delete',
            [],
        );
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isRedirect(Config::BASE_URI.'/login'));
    }

    public function testDeleteTaskActionAnonymousByAdminOk()
    {
        $user = $this->findOneByName($this->client, Config::ROLE_ADMIN);
        $this->setAuthorization($this->client, $user);
        $anonymous = $this->findOneByName($this->client, Config::ROLE_ANONYMOUS);
        $task = $this->findLastTaskByUserId($this->client, $anonymous)->getId();

        $this->client->request(
            'GET',
            "/tasks/$task/delete",
            [],
        );
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isRedirect('/tasks'));
    }


/**** Task toggle ****/

    public function testToggleTaskActionOk()
    {
        $user = $this->findLastUser($this->client);
        $this->setAuthorization($this->client, $user);
        $task = $this->findLastTaskByUserId($this->client, $user)->getId();
        $this->client->request(
            'GET',
            "/tasks/$task/toggle",
            [],
        );
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isRedirect('/tasks'));
    }

    public function testToggleTaskActionNokWrongUser()
    {
        $user = $this->findLastUser($this->client);
        $this->setAuthorization($this->client, $user);
        $this->client->request(
            'GET',
            '/tasks/1/toggle',
            [],
        );
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }

    public function testToggleTaskActionNokWithoutAuthorization()
    {
        $this->client->request(
            'GET',
            '/tasks/1/toggle',
            [],
        );
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isRedirect(Config::BASE_URI.'/login'));
    }

}