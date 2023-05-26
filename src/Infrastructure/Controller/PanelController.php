<?php

/**
 * Este archivo pertenece a la aplicación XYZ.
 * Todos los derechos reservados.
 *
 * @category Controlador
 * @package  App\Infrastructure\Controller
 * @license  Todos los derechos reservados
 */

declare(strict_types=1 );

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

    public function index()
    {
        $users = $this->panelOrchestrator->getAllUsers();

        return $this->render('base.html.twig', [
            'users' => $users,
        ]);

    }

}