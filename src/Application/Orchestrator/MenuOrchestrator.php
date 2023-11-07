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
use App\Application\Utils\ManagePhoto;
use App\Infrastructure\Persistence\Doctrine\Repository\InformationRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Infrastructure\Persistence\Doctrine\Repository\LocalRepository;

final class MenuOrchestrator extends AbstractController
{
    private MenuRepository $menuRepository;
    private ManagePhoto $managePhoto;
    private InformationRepository $informationRepository;
    private EntityManagerInterface $entityManager;
    private LocalRepository $localRepository;

    public function __construct(
        MenuRepository $menuRepository,
        ManagePhoto $managePhoto,
        InformationRepository  $informationRepository,
        EntityManagerInterface $entityManager,
        LocalRepository $localRepository, 
    )

    {
        $this->menuRepository = $menuRepository;
        $this->managePhoto = $managePhoto;
        $this->informationRepository = $informationRepository;
        $this->entityManager = $entityManager;
        $this->localRepository = $localRepository;
    }

    public function showMenusCreated(Request $request): ?array
    {
        $idLocal = intval($request->attributes->get('local'));
        $informationId = $this->informationRepository->findOneBy(array('local' => $idLocal));
        $menuData = $this->menuRepository->findBy(array('informacion' => $informationId->getId()));

        if ($menuData) {
            return $menuData;
        }

        return null;
    }

    public function newMenu(Request $request)
    {
        $idLocal = intval($request->attributes->get('local'));

        $informacion = $this->informationRepository->findOneBy(array('local' => $idLocal));
        $local = $this->localRepository->findOneBy(array('id' => $idLocal));

        if($local->getUsuario()->getId() !== $this->getUser()->getId() ){
            throw new HttpException(404, 'Usuario no Autorizado');
        }
        
        $menu = $this->entityManager->getRepository(Menu::class)->find($informacion);

        $submittedToken = $request->request->get('token');

        if ($request->isMethod('POST') && $this->getUser() && $this->isCsrfTokenValid('validateTokenSym', $submittedToken) ) {
            $datosForm = $request->request->all();
            if( '' === $datosForm['nombre_menu']){
                throw new HttpException(404, 'No puedes dejar el nombre del Menú vacío');
            }

            if (!$menu) {
                $menu = new Menu();
                $menu->setInformacion($informacion);
            }

            $photoRequest = $request->files->get('file-upload');
            if($photoRequest){
                $menuPhoto = new MenuPhoto();
                $photo = $this->managePhoto->upload($photoRequest);
                if('' !== $photo){
                    $menuPhoto->setPhotoPath($photo);
                    $menu->addPhoto($menuPhoto);
                }
                if('' === $photo){
                    $this->addFlash(
                        'error',
                        'La foto pesa más de mega y medio, bajala de peso.'
                    );
                }    
            }
            $menu->setUserId($this->getUser()->getId());
            $menu->setNombreMenu($datosForm['nombre_menu'] ?? '');
            $menu->setInformacionMenu($datosForm['informacion_menu'] ?? '');
            $menu->setPrecioMenu($datosForm['precio_menu'] ?? '');
            
            $this->addFlash(
                'sucess',
                'Menu creado correctamente.'
            );

            $this->menuRepository->save($menu, true);
        }

        return $menu;
    }
    public function editMenu(Request $request): ?Menu
    {
        $menuId = intval($request->attributes->get('menuId'));
        $menu = $this->entityManager->getRepository(Menu::class)->find($menuId);
        $userGrantedForEdit = $menu->getUserId() === $this->getUser()->getId();
        
        if (!$userGrantedForEdit) {
            throw new HttpException(404, 'Usuario no Autorizado');
        }
        $submittedToken = $request->request->get('token');

        if ($request->isMethod('POST') && $userGrantedForEdit && $this->isCsrfTokenValid('validateTokenSym', $submittedToken) ) {
            $datosForm = $request->request->all();

            if( '' === $datosForm['nombre_menu']){
                throw new HttpException(404, 'No puedes dejar el nombre del Menú vacío');
            }

            if (!$menu) {
                $menu = new Menu();
            }

            $photoRequest = $request->files->get('file-upload');
            if($photoRequest){
                $photo = $this->managePhoto->upload($photoRequest);
                
                if('' !== $photo){
                    $menuPhoto = $menu->getPhotos()->first();
                
                    if(!$menuPhoto){
                    $menuPhoto = new MenuPhoto();
                    }
    
                    $menuPhoto->setPhotoPath($photo);
                    $menu->addPhoto($menuPhoto);
                }   

                if('' === $photo){
                    $this->addFlash(
                        'error',
                        'La foto pesa más de mega y medio, bajala de peso.'
                    );
                }               
            }

            $menu->setNombreMenu($datosForm['nombre_menu'] ?? '');
            $menu->setInformacionMenu($datosForm['informacion_menu'] ?? '');
            $menu->setPrecioMenu($datosForm['precio_menu'] ?? '');
            
            $this->addFlash(
                'sucess',
                'Menu editado correctamente.'
            );
            
            $this->menuRepository->save($menu, true);
        }

        return $menu;
    }
    public function editMenuForDeletePhoto(Request $request): ?Menu
    {
        $menuId = intval($request->attributes->get('menuId'));
        $menu = $this->entityManager->getRepository(Menu::class)->find($menuId);
        $userGrantedForEdit = $menu->getUserId() === $this->getUser()->getId();
        
        if (!$userGrantedForEdit) {
            throw new HttpException(404, 'Usuario no Autorizado');
        }

        $menuPhoto = $menu->getPhotos()->first()->getPhotoPath() ?? '';
        $deletePhoto = $this->managePhoto->deletePhoto($menuPhoto);
        if($deletePhoto){
            $this->addFlash(
                'success',
                'Foto borrada correctamente'
            );
            $menu->removePhoto($menu->getPhotos()->first());
            $this->menuRepository->save($menu, true);

        }
        if(!$deletePhoto){
            $this->addFlash(
                'error',
                'Error borrando la foto'
            );
        }
        return $menu;
    }
    public function deleteMenu(Request $request) : bool
    {
        $menuId = intval($request->attributes->get('menuId'));
        $menu = $this->menuRepository->findOneBy(array('id' => $menuId));

        $userGrantedForEdit = $menu->getUserId() === $this->getUser()->getId();
        
        if (!$userGrantedForEdit) {
            throw new HttpException(404, 'Usuario no Autorizado');
        }

        if(!$menu){
            $this->addFlash(
                'error',
                'El menú no ha podido ser borrado, no se ha encontrado...'
            );
            return false;
        }
        if($menu->getPhotos()->first()){
            $this->managePhoto->deletePhoto($menu->getPhotos()->first()->getPhotoPath());
        }
        $this->menuRepository->remove($menu, true);
        
        $this->addFlash(
            'sucess',
            'El menú ha sido borrado correctamente, los productos asociados han sido borrados igualmente.'
        );

        return true;
    }
}
