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

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Application\Orchestrator\PanelOrchestrator;
use Symfony\Component\HttpFoundation\RedirectResponse;

final class PanelController extends AbstractController
{
    private PanelOrchestrator $panelOrchestrator;

    public function __construct(panelOrchestrator $panelOrchestrator)
    {
        $this->panelOrchestrator = $panelOrchestrator;
    }

    public function panelControllerAction()
    {
        $title = 'Panel de Control';

        return $this->render('/Panel/panel.html.twig', ['title' => $title]);

    }
    public function createLocalAction(Request $request): Response
    {

        $requestUserSendForm = $this->panelOrchestrator->createLocal($request);

        if ($requestUserSendForm) {
            return $this->redirect($this->generateUrl('panel-show-local'));
        }

        $title = 'Crear un Local';

        return $this->render('/Panel/Sections/newLocal.html.twig', ['title' => $title]);

    }

    public function configLocalAction(Request $request): Response
    {

        $datos = $this->panelOrchestrator->editConfigLocal($request);

        $title = 'Edita la Configuración de tu Local';

        return $this->render(
            '/Panel/Sections/localOptions.html.twig',
            ['id' => $request->attributes->get('id'), 'datos'=> $datos, 'title'=>$title]
        );
    }

    public function showLocalAction(): Response
    {
        $local = $this->panelOrchestrator->showLocal();

        $title = 'Ver Locales Creados';

        return $this->render(
            '/Panel/Sections/showLocal.html.twig',
            ['local' => $local,'title' => $title ]
        );

    }

    public function editInformationLocalAction(Request $request): Response
    {

        $datos = $this->panelOrchestrator->editInformationLocal($request);

        $title = 'Información de tu Local';

        return $this->render(
            '/Panel/Sections/newInformation.html.twig',
            ['id' => $request->attributes->get('id'), 'datos'=> $datos, 'title'=>$title]
        );
    }

    public function selectThemeOfLocalAction(Request $request): Response
    {
        $content = $this->panelOrchestrator->selectThemeOfLocal($request);
        $title = 'Escoge la plantilla web para tu Local';

        return $this->render('/Panel/Sections/selectThemeOfLocal.html.twig',[
            'title'=>$title,
            'content' => $content
        ]);

        throw new HttpException(Response::HTTP_BAD_REQUEST, 'Error en url');

    }
    public function panelChangePhotoThemeFromLocalAction(Request $request): RedirectResponse
    {
        $this->panelOrchestrator->panelChangePhotoThemeFromLocal($request);
    
        return new RedirectResponse(
            $this->generateUrl(
                'panel-show-local-online',
                array(
                'local' => $request->attributes->get('local'),
                'edit-photos' => 'true'
                )
            )
        );
    }  
}
