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
use App\Domain\Model\MenuPhoto;
use App\Domain\Model\ProductoPhoto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Request;
use App\Application\Utils\UploadPhoto;

final class ProductOrchestrator extends AbstractController
{
    private LocalRepository $localRepository;
    private InformationRepository $informationRepository;
    private ProductRepository $productRepository;
    private MenuRepository $menuRepository;
    private EntityManagerInterface $entityManager;
    private UploadPhoto $uploadPhoto;

    public function __construct(
        LocalRepository        $localRepository,
        InformationRepository  $informationRepository,
        MenuRepository         $menuRepository,
        ProductRepository      $productRepository,
        EntityManagerInterface $entityManager,
        UploadPhoto $uploadPhoto
    )

    {
        $this->localRepository = $localRepository;
        $this->informationRepository = $informationRepository;
        $this->menuRepository = $menuRepository;
        $this->productRepository = $productRepository;
        $this->entityManager = $entityManager;
        $this->uploadPhoto = $uploadPhoto;
    }

    public function showLocal(): array
    {
        $userId = $this->getUser()->getId();

        return $this->localRepository->findBy(array('usuario' => $userId));

    }

    public function showMenusCreated(Request $request): ?array
    {
        $idLocal = intval($request->attributes->get('id'));
        $informationId = $this->informationRepository->findOneBy(array('local' => $idLocal));
        $menuData = $this->menuRepository->findBy(array('informacion' => $informationId->getId()));

        if ($menuData) {
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

            if( '' === $datosForm['nombre_menu'] ||  '' === $datosForm['informacion_menu'] ||  '' === $datosForm['precio_menu']){
                throw new HttpException(Response::HTTP_BAD_REQUEST, 'No puedes dejar campos vacios');
            }

            if (!$menu) {
                $menu = new Menu();
                $menu->setInformacion($idLocal);
            }

            $photoRequests = $request->files->get('file-upload');
            if($photoRequests){
                foreach ($photoRequests as $photoRequest) {
                    $photo = $this->uploadPhoto->upload($photoRequest);
                    $menuPhoto = new MenuPhoto();
                    $menuPhoto->setPhotoPath($photo);
                    $menu->addPhoto($menuPhoto);
                }
            }
            $menu->setNombreMenu($datosForm['nombre_menu'] ?? '');
            $menu->setInformacionMenu($datosForm['informacion_menu'] ?? '');
            $menu->setPrecioMenu($datosForm['precio_menu'] ?? '');

            $this->menuRepository->save($menu, true);
        }

        return $menu;
    }

    public function newProduct(Request $request, bool $saveProductWithIdInformation = false): bool|array|Producto
    {
        $userId = $this->getUser()->getId();
        $idMenu = intval($request->attributes->get('id'));
        $menu = $this->menuRepository->findOneBy(array('id' => $idMenu));
        $informacion = $this->informationRepository->findOneBy(array('local' => $idMenu));

        if ($saveProductWithIdInformation && $request->isMethod('GET')) {
            return $this->productRepository->findBy(array('informacion' => $informacion->getId()));
        }

        if (!$saveProductWithIdInformation && $request->isMethod('GET')) {
            return $this->productRepository->findBy(array('menus' => $menu->getId()));
        }

        if ($request->isMethod('POST') && $this->getUser()) {
            $datosForm = $request->request->all();
            $producto = new Producto();
            $producto->setMenus($menu);
            if ($saveProductWithIdInformation) {
                $producto->setInformacion($informacion);
            }
            $producto->setNombreProducto($datosForm['producto'] ?? '');
            $producto->setInformacionProducto($datosForm['informacion'] ?? '');
            $producto->setPrecioProducto($datosForm['precioProducto'] ?? '');

            if($menu){
                $menu->agregarProducto($producto);
            }

            $photoRequests = $request->files->get('file-upload');
            if($photoRequests){
                foreach ($photoRequests as $photoRequest) {
                    $photo = $this->uploadPhoto->upload($photoRequest);
                    $productoPhoto = new ProductoPhoto();
                    $productoPhoto->setPhotoPath($photo);
                    $producto->addPhoto($productoPhoto);
                }
            }

            $this->productRepository->save($producto, true);

            return $producto;

        }
        return false;
    }
    public function editProduct(Request $request, bool $saveProductWithIdInformation = false): bool|array|Producto
    {
        $idProducto = intval($request->attributes->get('productoId'));
        $producto = $this->productRepository->findOneBy(array('id' => $idProducto));

        $informacion = $this->informationRepository->findOneBy(array('local' => $idProducto));

        if ($saveProductWithIdInformation && $request->isMethod('GET')) {
            return $this->productRepository->findBy(array('informacion' => $informacion->getId()));
        }

        if ($request->isMethod('POST') && $this->getUser()) {
            $datosForm = $request->request->all();

            if(!$producto){
                $producto = new Producto();
            }
            if ($saveProductWithIdInformation) {
                $producto->setInformacion($informacion);
            }
            $producto->setNombreProducto($datosForm['nombre_producto'] ?? '');
            $producto->setInformacionProducto($datosForm['informacion_producto'] ?? '');
            $producto->setPrecioProducto($datosForm['precio_producto'] ?? '');

            $photoRequests = $request->files->get('file-upload');
            if($photoRequests){
                foreach ($photoRequests as $photoRequest) {
                    $photo = $this->uploadPhoto->upload($photoRequest);
                    $productoPhoto = new ProductoPhoto();
                    $productoPhoto->setPhotoPath($photo);
                    $producto->addPhoto($productoPhoto);
                }
            }

            $this->productRepository->save($producto, true);

            return $producto;

        }
        return $producto;
    }

    public function deleteProduct(Request $request)
    {
        $userId = $this->getUser()->getId();
        $productId = intval($request->attributes->get('productoId'));
        $product = $this->productRepository->findOneBy(array('id' => $productId));

        if(!$product){
            $this->addFlash(
                'error',
                'El Producto no ha podido ser borrado, no se ha encontrado...'
            );
            return;
        }

        $this->productRepository->remove($product, true);
        
        $this->addFlash(
            'sucess',
            'El Producto ha sido borrado correctamente.'
        );

        return true;
    }
    public function showProductsCreated(Request $request)
    {
        $idMenu = (int)$request->attributes->get('menuId');
        return $this->productRepository->findBy(array('menus' => $idMenu));
    }
}
