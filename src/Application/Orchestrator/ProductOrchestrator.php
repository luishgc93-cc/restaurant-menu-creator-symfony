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
use App\Application\Utils\MultipleUtils;
use App\Domain\Model\Producto;
use App\Domain\Model\ProductoPhoto;
use App\Infrastructure\Persistence\Doctrine\Repository\InformationRepository;
use App\Infrastructure\Persistence\Doctrine\Repository\LocalRepository;
use App\Infrastructure\Persistence\Doctrine\Repository\MenuRepository;
use App\Infrastructure\Persistence\Doctrine\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class ProductOrchestrator extends AbstractController
{
	private LocalRepository $localRepository;
	private InformationRepository $informationRepository;
	private ProductRepository $productRepository;
	private MenuRepository $menuRepository;
	private EntityManagerInterface $entityManager;
	private ManagePhoto $managePhoto;
	private MultipleUtils $multipleUtils;

	public function __construct(
		LocalRepository $localRepository,
		InformationRepository $informationRepository,
		MenuRepository $menuRepository,
		ProductRepository $productRepository,
		EntityManagerInterface $entityManager,
		ManagePhoto $managePhoto,
		MultipleUtils $multipleUtils
	) {
		$this->localRepository = $localRepository;
		$this->informationRepository = $informationRepository;
		$this->menuRepository = $menuRepository;
		$this->productRepository = $productRepository;
		$this->entityManager = $entityManager;
		$this->managePhoto = $managePhoto;
		$this->multipleUtils = $multipleUtils;
	}

	public function showLocal(): array
	{
		$userId = $this->getUser()->getId();
		return $this->localRepository->findBy(['usuario' => $userId]);
	}

	public function newProduct(Request $request, bool $saveProductWithIdInformation = false): array|bool|Producto
	{
		$userId = $this->getUser()->getId();
		$idMenu = intval($request->attributes->get('menuId'));
		$local = intval($request->attributes->get('local'));
		$menu = $this->menuRepository->findOneBy(['id' => $idMenu]);
		$informacion = $this->informationRepository->findOneBy(['local' => $local]);
		$submittedToken = $request->request->get('token');
		
		$local = $this->localRepository->findOneBy(['id' => $local]);

		if ($local->getUsuario()->getId() !== $this->getUser()->getId()) {
			throw new HttpException(404, 'Usuario no Autorizado');
		}

		if ($request->isMethod('POST') && $this->getUser() && $this->isCsrfTokenValid(
			'validateTokenSym',
			$submittedToken
		)) {
			$datosForm = $request->request->all();
			
			if ($datosForm['producto'] === '') {
				$this->addFlash(
					'error',
					'El nombre del Producto esta vacío.'
				);
				return false;
			}

			$producto = new Producto();
			$producto->setMenus($menu);
			if ($saveProductWithIdInformation) {
				$producto->setInformacion($informacion);
			}
			$producto->setUserId($this->getUser()->getId());
			$producto->setNombreProducto($datosForm['producto'] ?? '');
			$producto->setInformacionProducto($datosForm['informacion'] ?? '');
			$producto->setPrecioProducto($datosForm['precioProducto'] ?? '');

			if ($menu) {
				$menu->agregarProducto($producto);
			}

			$photoRequest = $request->files->get('file-upload');
			if ($photoRequest) {
				$productoPhoto = new ProductoPhoto();
				$photo = $this->managePhoto->upload($photoRequest);
				
				if ($photo !== '') {
					$productoPhoto->setPhotoPath($photo);
					$producto->addPhoto($productoPhoto);
				}
				if ($photo === '') {
					$this->addFlash(
						'error',
						'La foto pesa más de mega y medio, bajala de peso.'
					);
				}
			}
			$this->addFlash(
				'sucess',
				'Producto añadido correctamente.'
			);
			$this->productRepository->save($producto, true);

			return $producto;
		}
		return false;
	}
	public function editProduct(Request $request, bool $saveProductWithIdInformation = false): array|bool|Producto
	{
		$idProducto = intval($request->attributes->get('productoId'));
		$producto = $this->productRepository->findOneBy(['id' => $idProducto]);

		$userGrantedForEdit = $producto->getUserId() === $this->getUser()->getId();
		
		if (!$userGrantedForEdit) {
			throw new HttpException(404, 'Usuario no Autorizado');
		}

		$informacion = $this->informationRepository->findOneBy(['local' => $idProducto]);

		if ($saveProductWithIdInformation && $request->isMethod('GET')) {
			return $this->productRepository->findBy(['informacion' => $informacion->getId()]);
		}

		$submittedToken = $request->request->get('token');

		if ($request->isMethod('POST') && $this->getUser() && $this->isCsrfTokenValid(
			'validateTokenSym',
			$submittedToken
		)) {
			$datosForm = $request->request->all();
			
			if ($datosForm['producto'] === '') {
				$this->addFlash(
					'error',
					'El nombre del Producto esta vacío.'
				);
				return $producto;
			}

			if (!$producto) {
				$producto = new Producto();
			}
			if ($saveProductWithIdInformation) {
				$producto->setInformacion($informacion);
			}
			$producto->setNombreProducto($datosForm['producto'] ?? '');
			$producto->setInformacionProducto($datosForm['informacion'] ?? '');
			$producto->setPrecioProducto($datosForm['precioProducto'] ?? '');

			$photoRequest = $request->files->get('file-upload');
			if ($photoRequest) {
				$photo = $this->managePhoto->upload($photoRequest);
				$productoPhoto = $producto->getPhotos()->first();
				
				if (!$productoPhoto) {
					$productoPhoto = new ProductoPhoto();
				}
				
				if ($photo !== '') {
					$productoPhoto->setPhotoPath($photo);
					$producto->addPhoto($productoPhoto);
				}

				if ($photo === '') {
					$this->addFlash(
						'error',
						'La foto pesa más de mega y medio, bajala de peso.'
					);
				}
			}
			$this->addFlash(
				'sucess',
				'Producto Editado correctamente.'
			);
			$this->productRepository->save($producto, true);

			return $producto;
		}
		return $producto;
	}
	public function editProductForDeletePhoto(
		Request $request,
		bool $saveProductWithIdInformation = false
	): array|bool|Producto {
		$idProducto = intval($request->attributes->get('productoId'));
		$producto = $this->productRepository->findOneBy(['id' => $idProducto]);

		$userGrantedForEdit = $producto->getUserId() === $this->getUser()->getId();
		
		if (!$userGrantedForEdit) {
			throw new HttpException(404, 'Usuario no Autorizado');
		}

		$informacion = $this->informationRepository->findOneBy(['local' => $idProducto]);

		if ($saveProductWithIdInformation && $request->isMethod('GET')) {
			return $this->productRepository->findBy(['informacion' => $informacion->getId()]);
		}
		
		$productoPhoto = $producto->getPhotos()->first()->getPhotoPath() ?? '';
		$deletePhoto = $this->managePhoto->deletePhoto($productoPhoto);
		if ($deletePhoto) {
			$this->addFlash(
				'success',
				'Foto borrada correctamente del Producto'
			);
			$producto->removePhoto($producto->getPhotos()->first());
			$this->productRepository->save($producto, true);
		}
		if (!$deletePhoto) {
			$this->addFlash(
				'error',
				'Error borrando la foto del Producto'
			);
		}

		return $producto;
	}
	
	public function deleteProduct(Request $request)
	{
		$userId = $this->getUser()->getId();
		$productId = intval($request->attributes->get('productoId'));

		$product = $this->productRepository->findOneBy(['id' => $productId]);
		$userGrantedForEdit = $product->getUserId() === $this->getUser()->getId();
		
		if (!$userGrantedForEdit) {
			throw new HttpException(404, 'Usuario no Autorizado');
		}

		if (!$product) {
			$this->addFlash(
				'error',
				'El Producto no ha podido ser borrado, no se ha encontrado...'
			);
			return;
		}
		if ($product->getPhotos()->first()) {
			$this->managePhoto->deletePhoto($product->getPhotos()->first()->getPhotoPath());
		}
		$this->productRepository->remove($product, true);
		
		$this->addFlash(
			'sucess',
			'El Producto ha sido borrado correctamente.'
		);

		return true;
	}
	public function showProductsCreated(Request $request, bool $getProductsFromMenu = false): array
	{
		$idMenu = (int) $request->attributes->get('menuId');
		$productos = $this->productRepository->findBy(['menus' => $idMenu]);
		if (!$productos && !$getProductsFromMenu) {
			$idLocal = (int) $request->attributes->get('local');
			$idInformacion = $this->informationRepository->findOneBy(['local' => $idLocal]);
			$productos = $this->productRepository->findBy(['informacion' => $idInformacion->getId()]);
		}
		return $productos;
	}
}
