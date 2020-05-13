<?php


namespace App\Listener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Twig\Environment;

class ExceptionListener
{
    private $twig;

    /**
     * ExceptionListener constructor.
     *
     * @param Environment $twig
     */
    public function __construct($twig)
    {
        $this->twig = $twig;
    }

    /**
     * @param ExceptionEvent $event
     */
    public function OnKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        $statusCode = ($exception instanceof HttpExceptionInterface) ?
            $exception->getStatusCode() :
            Response::HTTP_INTERNAL_SERVER_ERROR;

        switch ($statusCode) {
            case 404:
                $message = ["message" => "Désolé, cette page n'existe pas."];
                break;
            case 403:
                $message = ["message" => "Désolé, Vous n'êtes pas autorisé à accéder à cette page."];
                break;
            case 500:
                $message = ["message" => "Une erreur serveur s'est produite. Si le problème persiste, merci de contacter l'administrateur du site."];
                break;
            default:
                $message = ["message" => "Une erreur est apparue sur la page. Merci de la recharger."];
        }
        return $event->setResponse(new Response($this->twig->render('errors/error.html.twig', $message)));
    }
}
