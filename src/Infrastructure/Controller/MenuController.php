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
use App\Application\Utils\MultipleUtils;

final class MenuController extends AbstractController
{
    private MenuOrchestrator $menuOrchestrator;
    private ProductOrchestrator $productOrchestrator;
    private MultipleUtils $multipleUtils;

    public function __construct(
        MenuOrchestrator $menuOrchestrator, 
        ProductOrchestrator $productOrchestrator,
        MultipleUtils $multipleUtils
        )
    {
        $this->menuOrchestrator = $menuOrchestrator;
        $this->productOrchestrator = $productOrchestrator;
        $this->multipleUtils = $multipleUtils;
    }

    public function newMenuOfLocalAction(Request $request): Response
    {
        $datos = $this->menuOrchestrator->newMenu($request);
        $menus = $this->menuOrchestrator->showMenusCreated($request);

        $title = 'Crea un Menú o Carta para tu Local';

        $urlLocal = $this->multipleUtils->getUrlOfLocalForMenuNavigation($request->attributes->get('local'));

        return $this->render(
            '/Panel/Sections/newMenu.html.twig',
            ['local' => $request->attributes->get('local'), 
            'datos'=> $datos, 
            'title'=>$title,
            'menus'=>$menus,
            'urlLocal' => $urlLocal
            ]
        );
    }

    public function showMenuOfLocalAction(Request $request): Response
    {
        $menus = $this->menuOrchestrator->showMenusCreated($request);

        $title = 'Edita un Menú o los Productos asociados';

        $urlLocal = $this->multipleUtils->getUrlOfLocalForMenuNavigation($request->attributes->get('local'));

        return $this->render(
            '/Panel/Sections/editMenu.html.twig',
            ['local' => $request->attributes->get('local'), 
            'title'=>$title,
            'menus'=>$menus,
            'urlLocal' => $urlLocal
            ]
        );
    }

    public function editMenuOfLocalAction(Request $request): Response
    {
        $menu = $this->menuOrchestrator->editMenu($request);

        $products = $this->productOrchestrator->showProductsCreated($request, true);

        $title = 'Edita el Menú seleccionado o el Producto asociado';

        $urlLocal = $this->multipleUtils->getUrlOfLocalForMenuNavigation($request->attributes->get('local'));

        return $this->render(
            '/Panel/Sections/editMenu.html.twig',
            ['local' => $request->attributes->get('local'), 
            'menu'=> $menu, 
            'title'=>$title,
            'productos'=>$products,
            'urlLocal' => $urlLocal
            ]
        );
    }
    public function editMenuForDeletePhotoOfLocalAction(Request $request): Response
    {
        $menu = $this->menuOrchestrator->editMenuForDeletePhoto($request);

        $products = $this->productOrchestrator->showProductsCreated($request, true);

        $title = 'Edita el Menú seleccionado o el Producto asociado';

        $urlLocal = $this->multipleUtils->getUrlOfLocalForMenuNavigation($request->attributes->get('local'));

        return $this->render(
            '/Panel/Sections/editMenu.html.twig',
            ['local' => $request->attributes->get('local'), 
            'menu'=> $menu, 
            'title'=>$title,
            'productos'=>$products,
            'urlLocal' => $urlLocal
            ]
        );
    }

    public function deleteMenuOfLocalAction(Request $request): Response
    {
        $menu = $this->menuOrchestrator->deleteMenu($request);
        $urlLocal = $this->multipleUtils->getUrlOfLocalForMenuNavigation($request->attributes->get('local'));

        return $this->render(
            '/Panel/Sections/editMenu.html.twig',
            ['local' => $request->attributes->get('local'), 
            'menu'=> $menu, 
            'urlLocal' => $urlLocal
            ]
        );
    }
}
