<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ErrorController extends AbstractController
{

    /**
     * @Route("/error/404", name="error_404", methods={"GET"})
     */
    public function error404()
    {
        return $this->render('error_404');

    }

    /**
     * @Route("/403", name="error_403", methods={"GET"})
     */
    public function error403()
    {

    }

    /**
     * @Route("/500", name="error_500", methods={"GET"})
     */
    public function error500()
    {
        return $this->render('error_404');
    }


}