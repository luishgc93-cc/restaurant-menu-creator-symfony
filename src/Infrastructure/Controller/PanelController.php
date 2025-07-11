<?php

/**
 * Este archivo pertenece a la aplicación XYZ.
 * Todos los derechos reservados.
 *
 * @category Controlador
 * @package  App\Infrastructure\Controller
 * @license  Todos los derechos reservados
 */

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Application\Orchestrator\PanelOrchestrator;
use App\Application\Utils\MultipleUtils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class PanelController extends AbstractController
{
	private PanelOrchestrator $panelOrchestrator;
	private MultipleUtils $multipleUtils;

	public function __construct(
		panelOrchestrator $panelOrchestrator,
		MultipleUtils $multipleUtils
	) {
		$this->panelOrchestrator = $panelOrchestrator;
		$this->multipleUtils = $multipleUtils;
	}

	public function panelControllerAction(): Response
	{
		$title = 'Panel de Control';
		
		$local = $this->panelOrchestrator->showLocal();

		if (!$local) {
			return $this->redirect($this->generateUrl('panel-new-local'));
		}

		return $this->render('/Panel/panel.html.twig', ['title' => $title, 'local' => $local]);
	}

	public function createLocalAction(Request $request): Response
	{
		$requestUserSendForm = $this->panelOrchestrator->createLocal($request);

		if ($requestUserSendForm) {
			return $this->redirect($this->generateUrl('panel'));
		}

		$title = 'Crear un Local';

		return $this->render('/Panel/Sections/newLocal.html.twig', ['title' => $title]);
	}

	public function configLocalAction(Request $request): Response
	{
		$datos = $this->panelOrchestrator->editConfigLocal($request);

		$title = 'Edita la Configuración de tu Local';

		$urlLocal = $this->multipleUtils->getUrlOfLocalForMenuNavigation($request->attributes->get('local'));

		return $this->render(
			'/Panel/Sections/localOptions.html.twig',
			['local' => $request->attributes->get('local'),
				'datos' => $datos,
				'title' => $title,
				'urlLocal' => $urlLocal]
		);
	}

	public function configLocalDeleteLogoAction(Request $request): Response
	{
		$this->panelOrchestrator->editConfigLocalForDeleteLogo($request);
		return $this->redirectToRoute('panel-modify-config-local', ['local' => $request->attributes->get('local')]);
	}

	public function editInformationLocalAction(Request $request): Response
	{
		$datos = $this->panelOrchestrator->editInformationLocal($request);

		$title = 'Información de tu Local';

		$urlLocal = $this->multipleUtils->getUrlOfLocalForMenuNavigation($request->attributes->get('local'));

		return $this->render(
			'/Panel/Sections/newInformation.html.twig',
			['local' => $request->attributes->get('local'),
				'datos' => $datos,
				'title' => $title,
				'urlLocal' => $urlLocal]
		);
	}

	public function selectThemeOfLocalAction(Request $request): Response
	{
		$content = $this->panelOrchestrator->selectThemeOfLocal($request);
		$title = 'Escoge la plantilla web para tu Local';
		$urlLocal = $this->multipleUtils->getUrlOfLocalForMenuNavigation($request->attributes->get('local'));

		return $this->render('/Panel/Sections/selectThemeOfLocal.html.twig', [
			'title' => $title,
			'content' => $content,
			'urlLocal' => $urlLocal,
		]);

		throw new HttpException(Response::HTTP_BAD_REQUEST, 'Error en url');
	}
	public function panelChangePhotoThemeFromLocalAction(Request $request): RedirectResponse
	{
		$uploadPhotoOnTheme = $this->panelOrchestrator->panelChangePhotoThemeFromLocal($request);

		if ($uploadPhotoOnTheme === 1) {
			$this->addFlash(
				'sucess',
				'Tu Foto ha sido actualizada correctamente.'
			);
		}

		return new RedirectResponse(
			$this->generateUrl(
				'panel-show-local-online',
				[
					'local' => $request->attributes->get('local'),
					'edit-photos' => 'true',
				]
			)
		);
	}
}
