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

final class PanelOrchestrator extends AbstractController
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

    public function createLocal(Request $request)
    {

        if ($request->isMethod('POST') && $this->getUser()) {
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
    public function showLocal()
    {
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

        if($userId !== $userOwnerOfLocal) {
            throw new HttpException(404, 'Página no encontrada');
        }

        if ($request->isMethod('POST') && $this->getUser()) {
            $datosForm = $request->request->all();

            if(!$informacion) {
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

            $this->addFlash(
                'sucess',
                '¡Tus cambios se han guardado! Ahora puedes crear los menos de tu local.'
            );

        }

        return $informacion;
    }
    public function showMenusCreated(Request $request){
        $idLocal = intval($request->attributes->get('id'));
        $informationId = $this->informationRepository->findOneBy(array('local' => $idLocal));
        $menuData = $this->menuRepository->findBy(array('informacion' => $informationId->getId()));
        if($menuData){
            return $menuData;   
        }

        return null;
    }

    public function newMenu(Request $request)
    {
        $userId = $this->getUser()->getId();
        $idLocal = intval($request->attributes->get('id'));

        $idLocal = $this->informationRepository->findOneBy(array('local' => $idLocal));
        $menu = $this->entityManager->getRepository(Menu::class)->find($idLocal);

        if ($request->isMethod('POST') && $this->getUser()) {
            $datosForm = $request->request->all();

            if(!$menu) {
                $menu = new Menu();
                $menu->setInformacion($idLocal);
            }

            $menu->setNombreMenu($datosForm['nombre_menu'] ?? '');
            $menu->setInformacionMenu($datosForm['informacion_menu'] ?? '');
            $menu->setPrecioMenu($datosForm['precio_menu'] ?? '');

            $this->menuRepository->save($menu, true);

        }

        return $menu;
    }

    public function newProduct(Request $request, bool $saveProductWithIdInformation = false)
    {
        $userId = $this->getUser()->getId();
        $idMenu = intval($request->attributes->get('id'));
        $menu = $this->menuRepository->findOneBy(array('id' => $idMenu));

        if ($request->isMethod('POST') && $this->getUser()) {
            $datosForm = $request->request->all();
            $producto = new Producto(); 
            $producto->setMenus($menu);
            if($saveProductWithIdInformation){
                $informacion = $this->informationRepository->findOneBy(array('local' => $idMenu));
                $producto->setInformacion($informacion);             
            }
            $producto->setNombreProducto($datosForm['producto'] ?? '');
            $producto->setInformacionProducto($datosForm['informacion'] ?? '');
            $producto->setPrecioProducto($datosForm['precioProducto'] ?? '');

            $this->productRepository->save($producto, true);

            return $producto;

        }
        return false;
    }
}
