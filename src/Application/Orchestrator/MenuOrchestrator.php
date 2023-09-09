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

use App\Infrastructure\Persistence\Doctrine\Repository\MenuRepository;
use App\Domain\Model\Menu;
use App\Domain\Model\MenuPhoto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Request;
use App\Application\Utils\UploadPhoto;
use App\Infrastructure\Persistence\Doctrine\Repository\InformationRepository;
use Doctrine\ORM\EntityManagerInterface;

final class MenuOrchestrator extends AbstractController
{
    private MenuRepository $menuRepository;
    private UploadPhoto $uploadPhoto;
    private InformationRepository $informationRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(
        MenuRepository $menuRepository,
        UploadPhoto $uploadPhoto,
        InformationRepository  $informationRepository,
        EntityManagerInterface $entityManager,
    )

    {
        $this->menuRepository = $menuRepository;
        $this->uploadPhoto = $uploadPhoto;
        $this->informationRepository = $informationRepository;
        $this->entityManager = $entityManager;
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
    public function editMenu(Request $request)
    {
        $userId = $this->getUser()->getId();
        $menuId = intval($request->attributes->get('menuId'));
        $menu = $this->entityManager->getRepository(Menu::class)->find($menuId);

        if ($request->isMethod('POST') && $this->getUser()) {
            $datosForm = $request->request->all();

            if( '' === $datosForm['nombre_menu'] ||  '' === $datosForm['informacion_menu'] ||  '' === $datosForm['precio_menu']){
                throw new HttpException(Response::HTTP_BAD_REQUEST, 'No puedes dejar campos vacios');
            }

            if (!$menu) {
                $menu = new Menu();
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
}
