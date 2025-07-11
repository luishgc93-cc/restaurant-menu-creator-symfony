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

use App\Application\Utils\ManagePhoto;
use App\Domain\Model\HorarioLocal;
use App\Domain\Model\Informacion;
use App\Domain\Model\InformacionPhoto;
use App\Domain\Model\Local;
use App\Infrastructure\Persistence\Doctrine\Repository\InformationRepository;

use App\Infrastructure\Persistence\Doctrine\Repository\LocalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class PanelOrchestrator extends AbstractController
{
	private LocalRepository $localRepository;
	private InformationRepository $informationRepository;
	private EntityManagerInterface $entityManager;
	private ManagePhoto $managePhoto;

	public function __construct(
		LocalRepository $localRepository,
		InformationRepository $informationRepository,
		EntityManagerInterface $entityManager,
		ManagePhoto $managePhoto
	) {
		$this->localRepository = $localRepository;
		$this->informationRepository = $informationRepository;
		$this->entityManager = $entityManager;
		$this->managePhoto = $managePhoto;
	}

	public function createLocal(Request $request): bool
	{
		$submittedToken = $request->request->get('token');

		if ($request->isMethod('POST') && $this->getUser() && $this->isCsrfTokenValid(
			'validateTokenSym',
			$submittedToken
		)) {
			$datosForm = $request->request->all();
			$getUrlSaved = $this->localRepository->findOneBy(['url' => $datosForm['url']]);

			if ($getUrlSaved) {
				$this->addFlash(
					'error',
					'¡La url del local ya está registrada! Indica otra por favor.'
				);
				return false;
			}

			$local = new Local();
			$local->setUsuario($this->getUser());
			$local->setNombreLocal($datosForm['nombreLocal'] ?? '');
			$local->setDescripcionLocal($datosForm['descripcion'] ?? '');
			$local->setUrl($datosForm['url'] ?? '');
			$this->localRepository->save($local, true);

			return true;
		}
		return false;
	}

	public function editConfigLocal(Request $request): ?Local
	{
		$userId = $this->getUser()->getId();
		$idLocal = intval($request->attributes->get('local'));

		$local = $this->localRepository->findOneBy(['id' => $idLocal]);

		$userOwnerOfLocal = $local->getUsuario()->getId();

		if ($userId !== $userOwnerOfLocal) {
			throw new HttpException(404, 'Página no encontrada');
		}

		$submittedToken = $request->request->get('token');

		if ($request->isMethod('POST') && $this->getUser() && $this->isCsrfTokenValid(
			'validateTokenSym',
			$submittedToken
		)) {
			$datosForm = $request->request->all();
			$bloquearWeb = $datosForm['bloquearWeb'] ?? '';
			$ocultarFormulario = $datosForm['ocultarFormulario'] ?? '';

			$getUrlSaved = $this->localRepository->findOneBy(['url' => $datosForm['url']]);
			if ($getUrlSaved) {
				if ($getUrlSaved->getId() !== $idLocal) {
					$this->addFlash(
						'error',
						'¡La url del local ya está registrada! Indica otra por favor.'
					);
					return $local;
				}
			}

			$local->setNombreLocal($datosForm['nombreLocal'] ?? '');
			$local->setDescripcionLocal($datosForm['descripcion'] ?? '');
			$local->setUrl($datosForm['url'] ?? '');
			$local->setBloquearWeb($bloquearWeb === 'on' ? 1 : 0);
			$local->setOcultarFormularioContacto($ocultarFormulario === 'on' ? 1 : 0);

			$photoRequest = $request->files->get('file-upload');
			if ($photoRequest) {
				$photo = $this->managePhoto->upload($photoRequest);
				
				if ($photo !== '') {
					$local->setLogo($photo);
				}

				if ($photo === '') {
					$this->addFlash(
						'error',
						'La foto pesa más de mega y medio, bajala de peso.'
					);
				}
			}
			$local->setColorWeb($datosForm['colorWeb'] ?? '');

			$this->localRepository->save($local, true);

			$this->addFlash(
				'sucess',
				'¡Tus cambios se han guardado!'
			);
		}
		return $local;
	}
	public function editConfigLocalForDeleteLogo(Request $request): ?Local
	{
		$userId = $this->getUser()->getId();
		$idLocal = intval($request->attributes->get('local'));

		$local = $this->localRepository->findOneBy(['id' => $idLocal]);

		$userOwnerOfLocal = $local->getUsuario()->getId();

		if ($userId !== $userOwnerOfLocal) {
			throw new HttpException(404, 'Página no encontrada');
		}

		$localPhoto = $local->getLogo() ?? '';
		$deletePhoto = $this->managePhoto->deletePhoto($localPhoto);
		
		if ($deletePhoto) {
			$local->setLogo(null);
			$this->localRepository->save($local, true);
			$this->addFlash(
				'success',
				'Foto borrada correctamente'
			);
		}

		if (!$deletePhoto) {
			$this->addFlash(
				'error',
				'Error borrando la foto'
			);
		}

		return $local;
	}


	public function showLocal(): ?Local
	{
		$userId = $this->getUser()->getId();
		return $this->localRepository->findOneBy(['usuario' => $userId]);
	}

	public function editInformationLocal(Request $request): ?Informacion
	{
		$userId = $this->getUser()?->getId();
		$idLocal = intval($request->attributes->get('local'));

		$informacion = $this->informationRepository->findOneBy(['local' => $idLocal]);
		$local = $this->entityManager->getRepository(Local::class)->find($idLocal);

		$userOwnerOfLocal = $local->getUsuario()->getId();

		if ($userId !== $userOwnerOfLocal) {
			throw new HttpException(404, 'Página no encontrada');
		}

		$submittedToken = $request->request->get('token');

		if ($request->isMethod('POST') && $this->getUser() && $this->isCsrfTokenValid(
			'validateTokenSym',
			$submittedToken
		)) {
			$datosForm = $request->request->all();

			if (!$informacion) {
				$informacion = new Informacion();
				$informacion->setLocal($local);
			}

			$informacion->setTelefono($datosForm['telefono'] ?? '');
			$informacion->setDescripcion($datosForm['descripcion'] ?? '');
			$informacion->setDireccionCompleta($datosForm['direccionCompleta'] ?? '');
			$informacion->setLocalidad($datosForm['localidad'] ?? '');
			$informacion->setCiudad($datosForm['ciudad'] ?? '');
			$informacion->setEmail($datosForm['email'] ?? '');
			$informacion->setTwiter($datosForm['twiter'] ?? '');
			$informacion->setFacebook($datosForm['facebook'] ?? '');
			$informacion->setInstagram($datosForm['instagram'] ?? '');
			$informacion->setYoutube($datosForm['youtube'] ?? '');
			
			if ($datosForm['maps']) {
				preg_match('/src="([^"]+)"/', $datosForm['maps'], $match);
				$informacion->setMaps($match[1] ?? '');
			}

			$daysOfWeek = [
				'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo',
			];
			
			foreach ($daysOfWeek as $day) {
				$aperturaKey = 'horario' . $day . 'Apertura';
				$cierreKey = 'horario' . $day . 'Cierre';
				$mostrarHora = $datosForm['no-mostrar-hora-' . $day] ?? false;

				if ($datosForm[$aperturaKey] && $datosForm[$cierreKey]) {
					$horarioExistente = null;
					foreach ($informacion->getHorariosLocal() as $horarioLocal) {
						if ($horarioLocal->getDiaSemana() === $day) {
							$horarioExistente = $horarioLocal;
							break;
						}
					}

					if (!$horarioExistente) {
						$horarioLocal = new HorarioLocal();
					}

					$horarioLocal->setDiaSemana($day);
					$horarioLocal->setHoraApertura(new \DateTimeImmutable('@' . strtotime($datosForm[$aperturaKey])));
					$horarioLocal->setHoraCierre(new \DateTimeImmutable('@' . strtotime($datosForm[$cierreKey])));
					$horarioLocal->setInformacion($informacion);
					$horarioLocal->setNoMostrarHora($mostrarHora ? true : false);
					
					$informacion->addHorarioLocal($horarioLocal);
				}
			}
			
			$this->informationRepository->save($informacion, true);

			$this->addFlash(
				'sucess',
				'¡Tus cambios se han guardado! Ahora puedes crear los Menús de tu Local.'
			);
		}

		return $informacion;
	}
	public function selectThemeOfLocal(Request $request)
	{
		$idLocal = (int) $request->attributes->get('local');
		$local = $this->localRepository->findOneBy(['id' => $idLocal]);
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
	public function panelChangePhotoThemeFromLocal(Request $request): int
	{
		$photoRequests = $request->files->get('file-upload');
		if ($photoRequests) {
			$local = $this->localRepository->findOneBy(['url' => $request->attributes->get('local')]);
			
			$userId = $this->getUser()?->getId();
			$userOwnerOfLocal = $local->getUsuario()->getId();

			if ($userId !== $userOwnerOfLocal) {
				throw new HttpException(404, 'Página no encontrada');
			}

			$orden = $request->attributes->get('orden');
			$informacion = $this->informationRepository->findOneBy(['local' => $local->getId()]);
			$informacionPhoto = $this->entityManager->getRepository(InformacionPhoto::class)->findOneBy(
				['informacion' => $informacion->getId(), 'orden' => $orden]
			);

			$photo = $this->managePhoto->upload($photoRequests);
			if ($photo === '') {
				$this->addFlash(
					'error',
					'La foto pesa más de mega y medio, bajala de peso.'
				);
			}
			if ($photo && $orden && $photo !== '') {
				if (!$informacionPhoto) {
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
