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
use Cloudinary\Api\ApiResponse;
use Cloudinary\Cloudinary;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Request;
use Cloudinary\Api\Upload\UploadApi;
use Cloudinary\Configuration\Configuration;

final class PanelOrchestrator extends AbstractController
{
    private LocalRepository $localRepository;
    private InformationRepository $informationRepository;
    private ProductRepository $productRepository;
    private $menuRepository;

    private EntityManagerInterface $entityManager;

    public function __construct(
        LocalRepository        $localRepository,
        InformationRepository  $informationRepository,
        MenuRepository         $menuRepository,
        ProductRepository      $productRepository,
        EntityManagerInterface $entityManager,
    )

    {
        $this->localRepository = $localRepository;
        $this->informationRepository = $informationRepository;
        $this->menuRepository = $menuRepository;
        $this->productRepository = $productRepository;

        $this->entityManager = $entityManager;
    }

    public function createLocal(Request $request): bool
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

    public function showLocal(): array
    {
        $userId = $this->getUser()->getId();

        return $this->localRepository->findBy(array('usuario' => $userId));

    }

    public function editInformationLocal(Request $request): ?Informacion
    {
        $userId = $this->getUser()->getId();
        $idLocal = intval($request->attributes->get('id'));

        $informacion = $this->informationRepository->findOneBy(array('local' => $idLocal));
        $local = $this->entityManager->getRepository(Local::class)->find($idLocal);

        $userOwnerOfLocal = $local->getUsuario()->getId();

        if ($userId !== $userOwnerOfLocal) {
            throw new HttpException(404, 'Página no encontrada');
        }

        if ($request->isMethod('POST') && $this->getUser()) {
            $datosForm = $request->request->all();
            $files = $request->files->get('file-upload');
            $foto = $this->uploadPhotos($files);
            if (!$informacion) {
                $informacion = new Informacion();
                $informacion->setLocal($local);
            }

            $informacion->setTelefono($datosForm['telefono'] ?? '');
            $informacion->setDescripcion($datosForm['descripcion'] ?? '');
            $informacion->setCalle($datosForm['calle'] ?? '');
            $informacion->setLocalidad($datosForm['localidad'] ?? '');
            $informacion->setCiudad($datosForm['ciudad'] ?? '');
            $informacion->setEmail($datosForm['email'] ?? '');
            $informacion->setFotosInformativas($foto);


            $this->informationRepository->save($informacion, true);

            $this->addFlash(
                'sucess',
                '¡Tus cambios se han guardado! Ahora puedes crear los menos de tu local.'
            );

        }

        return $informacion;
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

            if (!$menu) {
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

            $this->productRepository->save($producto, true);

            return $producto;

        }
        return false;
    }

    public function uploadPhotos($files)
    {
        $destination = $this->getParameter('kernel.project_dir') . '/public/uploads/';
        $fileName = uniqid().'.'.$files->guessExtension();
        $files->move($destination, $fileName);
        $filePath = $destination.$fileName;

        $config = Configuration::instance();
        $config->cloud->cloudName = 'dmo3iliks';
        $config->cloud->apiKey = '982421683171437';
        $config->cloud->apiSecret = 'iqY0a7gPn-3ozoBlf6MsmwMI4yo';
        $config->url->secure = true;

        $uploadApi = new UploadApi();
        /** @var ApiResponse $response */
        $response = $uploadApi->upload($filePath);
        return $response['url'];

    }

    public function selectThemeOfLocal(Request $request)
    {
        $idLocal = (int)$request->attributes->get('id');
        $local = $this->localRepository->findOneBy(array('id' => $idLocal));
        $localThemeSaveOnDb = $local->getEstilo();

        $themeQuerySelected = $request->query->get('theme');

        if ($themeQuerySelected && $this->getUser()) {
            $datosForm = $request->request->all();

            $local->setEstilo($themeQuerySelected ?? 1);

            $this->localRepository->save($local, true);

            $this->addFlash(
                'sucess',
                '¡Tus cambios se han guardado! Puedes ir de nuevo a la web de tu Local y ver el nuevo diseño escogido.'
            );

        }

        return $localThemeSaveOnDb;
    }
}
