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
use App\Infrastructure\Persistence\Doctrine\Repository\InformationRepository;
use App\Domain\Model\Local;
use App\Domain\Model\Informacion;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;

final class PanelOrchestrator extends AbstractController
{
    private $localRepository;
    private $informationRepository;
    private $entityManager;

    public function __construct(LocalRepository $localRepository, InformationRepository $informationRepository, EntityManagerInterface $entityManager)
    {
        $this->localRepository = $localRepository;
        $this->informationRepository = $informationRepository;
        $this->entityManager = $entityManager;

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
    public function showLocal(){
        $userId = $this->getUser()->getId();
        
        return $this->localRepository->findBy(array('usuario' => $userId ));

    }

    public function editInformationLocal(Request $request)
    {
        $userId = $this->getUser()->getId();     
        
        if ($request->isMethod('POST') && $this->getUser() ) {
            $datosForm = $request->request->all();
            $idLocal = intval($request->attributes->get('id')); 
            $local = $this->entityManager->getRepository(Local::class)->find($idLocal);

            $local = new Informacion();
            $local->setLocal($local);
            $local->setTelefono($datosForm['telefono'] ?? '');
            $local->setDescripcion($datosForm['descripcion'] ?? '');
            $local->setCalle($datosForm['calle'] ?? '');
            $local->setLocalidad($datosForm['localidad'] ?? '');
            $local->setCiudad($datosForm['ciudad'] ?? '');
            $local->setEmail($datosForm['email'] ?? '');

            $this->informationRepository->save($local, true);
            
            return true;
        }

        return false;

    }

}
