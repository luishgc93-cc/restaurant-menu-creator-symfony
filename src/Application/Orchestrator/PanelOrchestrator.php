<?php

/**
 * Este archivo pertenece a la aplicación XYZ.
 * Todos los derechos reservados.
 *
 * @category Controlador
 * @package  App\Application\Orchestrator\PanelOrchestrator
 * @license  Todos los derechos reservados
 */

declare(strict_types=1);

namespace App\Application\Orchestrator;
use Symfony\Component\HttpFoundation\Request;
use App\Infrastructure\Persistence\Doctrine\Repository\LocalRepository;
use App\Domain\Model\Local;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class PanelOrchestrator extends AbstractController
{
    private $localRepository;

    public function __construct(LocalRepository $localRepository)
    {
        $this->localRepository = $localRepository;

    }

    public function createLocal(Request $request)
    {

        if ($request->isMethod('POST') && $this->getUser() ) {
            $datosForm = $request->request->all();
            $local = new Local();
            $local->setUsuario($this->getUser());
            $local->setNombreLocal($datosForm['local'] ?? '');
            $local->setDescripcionLocal($datosForm['descripcion'] ?? '');
            $this->localRepository->save($local, true);

            return true;
        }

        return false;

    }


}
