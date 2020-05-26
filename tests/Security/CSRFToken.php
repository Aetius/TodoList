<?php

namespace Tests\Security;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\Security\Csrf\CsrfTokenManager;

trait CSRFToken
{
    public function getCsrfToken(KernelBrowser $client, int $id, string $action)
    {
        $token = $client->getContainer()->get('security.csrf.token_manager');
        /* @var CsrfTokenManager $token */
        return $token->getToken($action.'-'.$id);
    }
}
