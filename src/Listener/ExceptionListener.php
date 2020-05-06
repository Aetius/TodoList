<?php


namespace App\Listener;


use Symfony\Bundle\FrameworkBundle\Controller\RedirectController;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\HttpKernel;
use Twig\Environment;

class ExceptionListener
{

    /**
     * ExceptionListener constructor.
     * @var Environment $router
     */
    public function __construct($router)
    {
        $this->router = $router;
    }

    public function OnKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        $statusCode = ($exception instanceof HttpExceptionInterface) ?
            $exception->getStatusCode() :
            Response::HTTP_INTERNAL_SERVER_ERROR;

        if ($statusCode === 403) {
            return $event->setResponse(new Response($this->router->render('errors/error_403.html.twig')));
        }
        if ($statusCode === 404) {
            return $event->setResponse(new Response($this->router->render('errors/error_404.html.twig')));
        }
        if ($statusCode === 500) {
            return $event->setResponse(new Response($this->router->render('errors/error_500.html.twig')));
        }
        return $event->setResponse(new Response($this->router->render('errors/error.html.twig')));

    }



}