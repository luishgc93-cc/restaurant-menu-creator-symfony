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
            return $this->redirect($this->generateUrl('panel'));
        }

        return $this->render('/Panel/Sections/newLocal.html.twig');

    }

    public function showLocalAction()
    {

    }

    public function editLocalAction()
    {

    }

    public function deleteLocalAction()
    {
    }

}
