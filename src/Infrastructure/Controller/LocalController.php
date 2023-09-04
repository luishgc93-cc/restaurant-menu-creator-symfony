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
use App\Application\Orchestrator\LocalOrchestrator;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Contracts\Cache\ItemInterface;
use Psr\Cache\CacheItemPoolInterface; // Importa la interfaz PSR

final class LocalController extends AbstractController
{
    private LocalOrchestrator $localOrchestrator;

    public function __construct(localOrchestrator $localOrchestrator)
    {
        $this->localOrchestrator = $localOrchestrator;
    }

    public function localControllerAction(): Response
    {

        return $this->render('/Panel/panel.html.twig');

    }

    public function showLocalAction(Request $request, CacheItemPoolInterface $cache): Response
    {
        $cacheKey = 'show_local_' . md5($request->getPathInfo());

        $response = $cache->get($cacheKey, function (ItemInterface $item) use ($request) {
            $content = $this->localOrchestrator->showLocal($request);
            $estile = $content['estile'] ?? 1;

            return $this->render(
                '/Local/Themes/' . $estile . '/index.html.twig',
                ['content' => $content]
            );
        });

        if ($response->getStatusCode() === Response::HTTP_BAD_REQUEST) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Error en url');
        }

        return $response;
    }

}
