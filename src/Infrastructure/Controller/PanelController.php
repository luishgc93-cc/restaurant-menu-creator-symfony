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

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Application\Orchestrator\PanelOrchestrator;

final class PanelController extends AbstractController
{
    private $panelOrchestrator;

    public function __construct(panelOrchestrator $panelOrchestrator)
    {
        $this->panelOrchestrator = $panelOrchestrator;
    }

    public function panelControllerAction()
    {

        return $this->render('/Panel/panel.html.twig');

    }
    public function createLocalAction(Request $request)
    {

        $requestUserSendForm = $this->panelOrchestrator->createLocal($request);

        if ($requestUserSendForm) {
            return $this->redirect($this->generateUrl('panel-show-local'));
        }

        return $this->render('/Panel/Sections/newLocal.html.twig');

    }

    public function showLocalAction()
    {
        $local = $this->panelOrchestrator->showLocal();

        return $this->render('/Panel/Sections/showLocal.html.twig',
        ['local' => $local]);

    }

    public function editLocalAction()
    {

    }

    public function deleteLocalAction()
    {
    }

    public function editInformationLocalAction(Request $request)
    {

        $datos = $this->panelOrchestrator->editInformationLocal($request);

        if ($datos) {
            return $this->redirect($this->generateUrl('panel-show-local'));
        }

        return $this->render('/Panel/Sections/newInformation.html.twig',
        ['id' => $request->attributes->get('id'), 'datos'=> $datos]);
    }

    public function showInformationLocalAction(Request $request)
    {

        $datos = $this->panelOrchestrator->showInformationLocal($request);

        return $this->render('/Panel/Sections/showInformation.html.twig',
        ['id' => $request->attributes->get('id'), 'datos'=> $datos]);
    }

}
