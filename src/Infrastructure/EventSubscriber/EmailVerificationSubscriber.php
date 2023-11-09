<?php

declare(strict_types=1);

namespace App\Infrastructure\EventSubscriber;

use App\Infrastructure\Controller\UserController;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

final class EmailVerificationSubscriber implements EventSubscriberInterface
{
	private $security;
	private $twig;
	private $UserController;

	public function __construct(Security $security, \Twig\Environment $twig, UserController $UserController)
	{
		$this->security = $security;
		$this->twig = $twig;
		$this->UserController = $UserController;
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
		$request = $event->getRequest();
		$datosForm = $request->request->all();

		if ($datosForm['username'] ?? null) {
			$response = $this->UserController->recoveryAccountUser($request, 'Revise su Email para verificar la Cuenta.');
			$event->setResponse($response);
		} elseif ($request->getPathInfo() === '/verify/email') {
			$response = $this->UserController->verifyUserEmailAction($request);
			$event->setResponse($response);
		} elseif ($user && !$user->isVerified() && $request->getPathInfo() !== '/') {
			$content = $this->twig->render(
				'/Panel/Sections/resendEmailVerification.html.twig',
				[
					'title' => 'Verificar Email',
					'userEmail' => $user->getEmail(),
				]
			);

			$response = new Response($content);
			$event->setResponse($response);
		}
	}
}
