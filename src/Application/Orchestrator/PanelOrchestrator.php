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
use Symfony\Component\HttpKernel\Exception\HttpException;

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
        $idLocal = intval($request->attributes->get('id'));

        $informacion = $this->informationRepository->findOneBy(array('local' => $idLocal));
        $local = $this->entityManager->getRepository(Local::class)->find($idLocal);
        
        $userOwnerOfLocal = $local->getUsuario()->getId();

        if($userId !== $userOwnerOfLocal){
            throw new HttpException(404, 'Página no encontrada');
        }

        if ($request->isMethod('POST') && $this->getUser()) {
            $datosForm = $request->request->all();
            
            if(!$informacion){
                $informacion = new Informacion();
                $informacion->setLocal($local);
            }

            $informacion->setTelefono($datosForm['telefono'] ?? '');
            $informacion->setDescripcion($datosForm['descripcion'] ?? '');
            $informacion->setCalle($datosForm['calle'] ?? '');
            $informacion->setLocalidad($datosForm['localidad'] ?? '');
            $informacion->setCiudad($datosForm['ciudad'] ?? '');
            $informacion->setEmail($datosForm['email'] ?? '');
    
            $this->informationRepository->save($informacion, true);
    
        }
    
        return $informacion;
    }

    public function newMenu(Request $request){

    }

}
