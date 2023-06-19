<?php

/**
 * Este archivo pertenece a la aplicación XYZ.
 * Todos los derechos reservados.
 *
 * @category Controlador
 * @package  App\Application\Orchestrator\LocalOrchestrator
 * @license  Todos los derechos reservados
 */

declare(strict_types=1);

namespace App\Application\Orchestrator;

use App\Infrastructure\Persistence\Doctrine\Repository\LocalRepository;
use App\Infrastructure\Persistence\Doctrine\Repository\InformationRepository;
use App\Infrastructure\Persistence\Doctrine\Repository\MenuRepository;
use App\Infrastructure\Persistence\Doctrine\Repository\ProductRepository;
use App\Domain\Model\Local;
use App\Domain\Model\Informacion;
use App\Domain\Model\Menu;
use App\Domain\Model\Producto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Request;

final class LocalOrchestrator extends AbstractController
{
    private $localRepository;
    private $informationRepository;
    private $menuRepository;
    private $productRepository;

    private $entityManager;

    public function __construct(
    LocalRepository $localRepository, 
    InformationRepository $informationRepository,
    MenuRepository $menuRepository, 
    ProductRepository $productRepository, 
    EntityManagerInterface $entityManager,
    )

    {
        $this->localRepository = $localRepository;
        $this->informationRepository = $informationRepository;
        $this->menuRepository = $menuRepository;
        $this->productRepository = $productRepository;
        $this->entityManager = $entityManager;
    }


    public function showLocal($request)
    {
        $userId = $this->getUser()->getId();

        $locals = $this->localRepository->findBy(array('usuario' => $userId ));

        foreach($locals as $local){
            $url = $local->getUrl();
            $uri = \str_replace('/', '', $request->getPathInfo() );
            if($url === $uri){
                $title = 'Ver Locales Creados';
                $estile = $local->getEstilo() ?? 1;
                $menus = $this->getMenusForLocal($local->getId());
                $content = [
                'estile' => $estile,
                'local' => $local, 
                'menus' => $menus,
                ];

                return $content;
            }
        }

        throw new HttpException(Response::HTTP_BAD_REQUEST, 'Error en url');

    }

    public function getMenusForLocal(int $idLocal)
    {

        $informacionData = $this->informationRepository->findOneBy(array('local' => $idLocal));
        return $menuData = $this->menuRepository->findBy(array('informacion' => $informacionData->getId()));
        
    }

}
