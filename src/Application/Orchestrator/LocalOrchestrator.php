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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class LocalOrchestrator extends AbstractController
{
    private LocalRepository $localRepository;
    private InformationRepository $informationRepository;
    private MenuRepository $menuRepository;
    private ProductRepository $productRepository;

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


    public function showLocal($request): array
    {
        $NameLocalFromRequest = \str_replace('/', '', $request->getPathInfo());
        $localRepository = $this->localRepository->findOneBy(array('url' => $NameLocalFromRequest));
        $userId =  $this->getUser()?->getId() ?? '';

        if (!$localRepository){
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Error en url, no encontrado en la base de datos');
        }

        if(1 === $localRepository->getBloquearWeb() && $userId !== $localRepository->getUsuario()->getId() ){
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Web deshabilitada desde administración');
        }

        $userOwnerOfLocal = $localRepository->getUsuario()->getId();

        if ($userId === $userOwnerOfLocal && 'true' === $request->query->get('edit-photos')) {
            $userIsAdmin = true;
        }

        $estile = $localRepository->getEstilo() ?? 1;
        $menus = $this->getMenusForLocal($localRepository->getId());
        $productsAlone = $this->getProductsAloneForLocal($localRepository->getId());
        $informationForLocal = $this->getInformationForLocal($localRepository->getId());

        return [
        'estile' => $estile,
        'local' => $localRepository,
        'menus' => $menus,
        'productsAlone' => $productsAlone,
        'informationForLocal' => $informationForLocal,
        'userIsAdmin' => $userIsAdmin??= false,
        ];
    }

    public function getMenusForLocal(int $idLocal) : array
    {
        $informationData = $this->informationRepository->findOneBy(array('local' => $idLocal));
       return $this->menuRepository->findBy(array('informacion' => $informationData->getId()));
    }

    public function getProductsAloneForLocal(int $idLocal) : array
    {
        $informationData = $this->informationRepository->findOneBy(array('local' => $idLocal));
        return $this->productRepository->findBy(array('informacion' => $informationData->getId()));
    }

    public function getInformationForLocal(int $idLocal): ?Informacion
    {
        $informationData = $this->informationRepository->findOneBy(array('local' => $idLocal));
        return $this->informationRepository->find($informationData->getId());
    }
}
