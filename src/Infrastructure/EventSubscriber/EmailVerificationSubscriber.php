<?php

namespace App\Infrastructure\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\DependencyInjection\ContainerInterface;

final class EmailVerificationSubscriber implements EventSubscriberInterface
{
    private $security;
    private $twig;

    public function __construct(Security $security, \Twig\Environment $twig)
    {
        $this->security = $security;
        $this->twig = $twig;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $user = $this->security->getUser();

        if ($user && !$user->isVerified()) {
            // Renderiza la vista Twig
            $content = $this->twig->render('/Panel/Sections/resendEmailVerification.html.twig', 
            [
            'title'=>'Verificar Email',
            'userEmail' => $user->getEmail()
            ]);

            $response = new Response($content);

            $event->setResponse($response);
        }
    }
}
