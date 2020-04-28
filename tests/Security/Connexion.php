<?php


namespace Tests\Security;


use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

trait Connexion
{

    /**
     * @var
     */
    private $user;


    public function setAuthorization(KernelBrowser $client, User $user = null)
    {
        $this->user = ($user !== null) ? $user : $this->findLastUser($client);

        $session = $client->getContainer()->get('session');
        $token = new UsernamePasswordToken($this->user, null, 'main', ['ROLE_USER']);
        $session->set('_security_main', serialize($token));
        $session->save();
        $cookie = new Cookie($session->getName(), $session->getId());
        $client->getCookieJar()->set($cookie);
    }
}