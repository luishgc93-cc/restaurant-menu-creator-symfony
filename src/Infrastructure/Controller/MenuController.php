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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Application\Orchestrator\MenuOrchestrator;
use App\Application\Orchestrator\ProductOrchestrator;

final class MenuController extends AbstractController
{
    private MenuOrchestrator $menuOrchestrator;
    private ProductOrchestrator $productOrchestrator;

    public function __construct(MenuOrchestrator $menuOrchestrator, ProductOrchestrator $productOrchestrator)
    {
        $this->menuOrchestrator = $menuOrchestrator;
        $this->productOrchestrator = $productOrchestrator;
    }

    public function newMenuOfLocalAction(Request $request): Response
    {

        $datos = $this->menuOrchestrator->newMenu($request);
        $menus = $this->menuOrchestrator->showMenusCreated($request);

        $title = 'Crea un Menú o Carta para tu Local';

        return $this->render(
            '/Panel/Sections/newMenu.html.twig',
            ['local' => $request->attributes->get('local'), 
            'datos'=> $datos, 
            'title'=>$title,
            'menus'=>$menus,
            ]
        );
    }

    public function showMenuOfLocalAction(Request $request): Response
    {

        $menus = $this->menuOrchestrator->showMenusCreated($request);
        $title = 'Edita un Menú o los Productos asociados';

        return $this->render(
            '/Panel/Sections/editMenu.html.twig',
            ['local' => $request->attributes->get('local'), 
            'title'=>$title,
            'menus'=>$menus,
            ]
        );
    }

    public function editMenuOfLocalAction(Request $request): Response
    {

        $menu = $this->menuOrchestrator->editMenu($request);
        $products = $this->productOrchestrator->showProductsCreated($request);

        $title = 'Edita el Menú seleccionado o el Producto asociado';

        return $this->render(
            '/Panel/Sections/editMenu.html.twig',
            ['local' => $request->attributes->get('local'), 
            'menu'=> $menu, 
            'title'=>$title,
            'productos'=>$products,
            ]
        );
    }

    public function deleteMenuOfLocalAction(Request $request): Response
    {

        $menu = $this->menuOrchestrator->deleteMenu($request);

        return $this->render(
            '/Panel/Sections/editMenu.html.twig',
            ['id' => $request->attributes->get('id'), 
            'menu'=> $menu, 
            ]
        );
    }
}
