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
use App\Application\Orchestrator\MenuOrchestrator;

final class MenuController extends AbstractController
{
    private MenuOrchestrator $menuOrchestrator;

    public function __construct(MenuOrchestrator $menuOrchestrator)
    {
        $this->menuOrchestrator = $menuOrchestrator;
    }


    public function newMenuOfLocalAction(Request $request): Response
    {

        $datos = $this->menuOrchestrator->newMenu($request);
        $menus = $this->menuOrchestrator->showMenusCreated($request);

        $title = 'Crea un Menú o Carta para tu Local';

        return $this->render(
            '/Panel/Sections/newMenu.html.twig',
            ['id' => $request->attributes->get('id'), 
            'datos'=> $datos, 
            'title'=>$title,
            'menus'=>$menus,
            ]
        );
    }

}
