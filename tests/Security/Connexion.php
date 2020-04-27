<?php


namespace Tests\Security;


use Symfony\Bundle\FrameworkBundle\KernelBrowser;

trait Connexion
{
    public function setAuthorization(KernelBrowser $client, $user = null)
    {
        $this->user = ($user !== null) ? $user : $this->findLastUser($client);

        dd($this->user);
    }
}