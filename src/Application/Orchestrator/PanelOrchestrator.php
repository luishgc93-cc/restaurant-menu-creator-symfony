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

use App\Domain\Model\InformacionPhoto;
use App\Infrastructure\Persistence\Doctrine\Repository\LocalRepository;
use App\Infrastructure\Persistence\Doctrine\Repository\InformationRepository;
use App\Domain\Model\Local;
use App\Domain\Model\Informacion;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Request;
use App\Application\Utils\UploadPhoto;

final class PanelOrchestrator extends AbstractController
{
    private LocalRepository $localRepository;
    private InformationRepository $informationRepository;
    private EntityManagerInterface $entityManager;
    private UploadPhoto $uploadPhoto;

    public function __construct(
        LocalRepository        $localRepository,
        InformationRepository  $informationRepository,

        EntityManagerInterface $entityManager,
        UploadPhoto $uploadPhoto
    )

    {
        $this->localRepository = $localRepository;
        $this->informationRepository = $informationRepository;
        $this->entityManager = $entityManager;
        $this->uploadPhoto = $uploadPhoto;
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

    public function editConfigLocal(Request $request)
    {
        $userId = $this->getUser()->getId();
        $idLocal = intval($request->attributes->get('id'));

        $local = $this->localRepository->findOneBy(array('id' => $idLocal));

        $userOwnerOfLocal = $local->getUsuario()->getId();

        if ($userId !== $userOwnerOfLocal) {
            throw new HttpException(404, 'Página no encontrada');
        }

        if ($request->isMethod('POST') && $this->getUser()) {
            $datosForm = $request->request->all();

            $local->setNombreLocal($datosForm['nombreLocal'] ?? '');
            $local->setDescripcionLocal($datosForm['descripcion'] ?? '');
            $local->setUrl($datosForm['url'] ?? '');

            $this->localRepository->save($local, true);

            $this->addFlash(
                'sucess',
                '¡Tus cambios se han guardado! Ahora puedes crear los menos de tu local.'
            );

        }
        return $local;
    }

    public function showLocal(): array
    {
        $userId = $this->getUser()->getId();

        return $this->localRepository->findBy(array('usuario' => $userId));

    }

    public function editInformationLocal(Request $request): ?Informacion
    {
        $userId = $this->getUser()?->getId();
        $idLocal = intval($request->attributes->get('id'));

        $informacion = $this->informationRepository->findOneBy(array('local' => $idLocal));
        $local = $this->entityManager->getRepository(Local::class)->find($idLocal);

        $userOwnerOfLocal = $local->getUsuario()->getId();

        if ($userId !== $userOwnerOfLocal) {
            throw new HttpException(404, 'Página no encontrada');
        }

        if ($request->isMethod('POST') && $this->getUser()) {
            $datosForm = $request->request->all();
            $photoRequests = $request->files->get('file-upload');
            if($photoRequests){
                foreach ($photoRequests as $photoRequest) {
                    $photo = $this->uploadPhoto->upload($photoRequest);
                    $menuPhoto = new InformacionPhoto();
                    $menuPhoto->setPhotoPath($photo);
                    $informacion->addPhoto($menuPhoto);
                }
            }

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

            $this->informationRepository->save($informacion, true);

            $this->addFlash(
                'sucess',
                '¡Tus cambios se han guardado! Ahora puedes crear los menos de tu local.'
            );

        }

        return $informacion;
    }
    public function selectThemeOfLocal(Request $request)
    {
        $idLocal = (int)$request->attributes->get('id');
        $local = $this->localRepository->findOneBy(array('id' => $idLocal));
        $localThemeSaveOnDb = $local->getEstilo();

        $themeQuerySelected = $request->query->get('theme');

        if ($themeQuerySelected && $this->getUser()) {

            $local->setEstilo($themeQuerySelected ?? 1);
            $this->localRepository->save($local, true);
            $localThemeSaveOnDb = $themeQuerySelected ?? 1;

            $this->addFlash(
                'sucess',
                '¡Tus cambios se han guardado! Puedes ir de nuevo a la web de tu Local y ver el nuevo diseño escogido.'
            );

        }

        return $localThemeSaveOnDb;
    }
    public function panelChangePhotoThemeFromLocal(Request $request) : int
    {

        $photoRequests = $request->files->get('file-upload');
        if($photoRequests){
        $local = $this->localRepository->findOneBy(array('url' => $request->attributes->get('local')));
        $orden = $request->attributes->get('orden');
        $informacion = $this->informationRepository->findOneBy(array('local' => $local->getId()));
        $informacionPhoto = $this->entityManager->getRepository(InformacionPhoto::class)->findOneBy(array('informacion' => $informacion->getId(), 'orden'=> $orden));

        $photo = $this->uploadPhoto->upload($photoRequests);
            if($photo && $orden){
                if(!$informacionPhoto){
                    $informacionPhoto = new InformacionPhoto();
                }
                $informacionPhoto->setPhotoPath($photo);
                $informacionPhoto->setOrden($orden);
                $informacion->addPhoto($informacionPhoto);
                $this->informationRepository->save($informacion, true);
                return 1;
            }
        }
        return 0;
    }
    
}
