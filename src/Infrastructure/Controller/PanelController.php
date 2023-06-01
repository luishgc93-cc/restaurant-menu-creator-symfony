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
        $title = 'Panel de Control';

        return $this->render('/Panel/panel.html.twig', ['title' => $title]);

    }
    public function createLocalAction(Request $request)
    {

        $requestUserSendForm = $this->panelOrchestrator->createLocal($request);

        if ($requestUserSendForm) {
            return $this->redirect($this->generateUrl('panel-show-local'));
        }

        $title = 'Crear un Local';

        return $this->render('/Panel/Sections/newLocal.html.twig', ['title' => $title]);

    }

    public function showLocalAction()
    {
        $local = $this->panelOrchestrator->showLocal();

        $title = 'Ver Locales Creados';

        return $this->render(
            '/Panel/Sections/showLocal.html.twig',
            ['local' => $local,'title' => $title ]
        );

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

        $title = 'Información de tu Local';

        return $this->render(
            '/Panel/Sections/newInformation.html.twig',
            ['id' => $request->attributes->get('id'), 'datos'=> $datos, 'title'=>$title]
        );
    }

    public function newMenuOfLocalAction(Request $request)
    {

        $datos = $this->panelOrchestrator->newMenu($request);
        $menus = $this->panelOrchestrator->showMenusCreated($request);

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

    public function newProductAction(Request $request)
    {

        $datos = $this->panelOrchestrator->newProduct($request);

        $title = 'Añade un Producto a tu Local';

        return $this->render(
            '/Panel/Sections/newProduct.html.twig',
            ['id' => $request->attributes->get('id'), 
            'datos'=> $datos, 
            'title'=>$title]
        );
    }

    public function newProductAloneAction(Request $request)
    {

        $datos = $this->panelOrchestrator->newProduct($request, true);

        $title = 'Añade un Producto a tu Local sin incluirlo en ningún Menú';

        return $this->render(
            '/Panel/Sections/newProductAlone.html.twig',[
            'id' => $request->attributes->get('id'), 
            'datos'=> $datos, 
            'title'=>$title
            ]
        );
    }
}
