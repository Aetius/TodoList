<?php


namespace Tests\Security;


use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\UriSafeTokenGenerator;

trait CSRFToken
{

    public function getCsrfToken(KernelBrowser $client, int $id, string $action)
    {
        $token = $client->getContainer()->get('security.csrf.token_manager');
        /** @var CsrfTokenManager $token */
        return $token->getToken($action."-".$id);
    }

}