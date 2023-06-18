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
use App\Application\Orchestrator\LocalOrchestrator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class LocalController extends AbstractController
{
    private $localOrchestrator;

    public function __construct(localOrchestrator $localOrchestrator)
    {
        $this->localOrchestrator = $localOrchestrator;
    }

    public function localControllerAction()
    {

        return $this->render('/Panel/panel.html.twig');

    }

    public function showLocalAction(Request $request)
    {
        $locals = $this->localOrchestrator->showLocal();
        foreach($locals as $local){
            $url = $local->getUrl();
            $uri = \str_replace('/', '', $request->getPathInfo() );
            if($url === $uri){
                $title = 'Ver Locales Creados';
                $estile = $local->getEstilo() ?? 1;
                return $this->render(
                    '/Local/Themes/' . $estile . '/index.html.twig',
                    ['local' => $local ]
                );
            }
        }

        throw new HttpException(Response::HTTP_BAD_REQUEST, 'Error en url');

    }

}
