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
use App\Application\Orchestrator\UserOrchestrator;

final class UserController extends AbstractController
{
    private $userOrchestrator;

    public function __construct(userOrchestrator $userOrchestrator)
    {
        $this->userOrchestrator = $userOrchestrator;
    }

    public function loginAction()
    {

        return $this->render('/User/login.html.twig');


    }

}