<?php


namespace App\Service;


use Symfony\Component\Security\Core\Security;

class FormService
{
    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function defineRole()
    {
        if($this->security->isGranted('form_user')){
            return true;
        }
        return false;
    }
}