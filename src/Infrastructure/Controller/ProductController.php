<?php

/**
 * Este archivo pertenece a la aplicación XYZ.
 * Todos los derechos reservados.
 *
 * @category Controlador
 * @package  App\Infrastructure\Controller
 * @license  Todos los derechos reservados
 */

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Application\Orchestrator\ProductOrchestrator;
use App\Application\Utils\MultipleUtils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class ProductController extends AbstractController
{
	private ProductOrchestrator $productOrchestrator;
	private MultipleUtils $multipleUtils;

	public function __construct(
		ProductOrchestrator $productOrchestrator,
		MultipleUtils $multipleUtils
	) {
		$this->productOrchestrator = $productOrchestrator;
		$this->multipleUtils = $multipleUtils;
	}

	public function newProductAction(Request $request): Response
	{
		$this->productOrchestrator->newProduct($request);
		$productsRelated = $this->productOrchestrator->showProductsCreated($request, true);

		$title = 'Añade un Producto a tu Local';

		$urlLocal = $this->multipleUtils->getUrlOfLocalForMenuNavigation($request->attributes->get('local'));

		return $this->render(
			'/Panel/Sections/newProduct.html.twig',
			['local' => $request->attributes->get('local'),
				'menuId' => $request->attributes->get('menuId'),
				'datos' => $productsRelated,
				'title' => $title,
				'urlLocal' => $urlLocal]
		);
	}

	public function newProductAloneAction(Request $request)
	{
		$this->productOrchestrator->newProduct($request, true);
		$productsRelated = $this->productOrchestrator->showProductsCreated($request);

		$title = 'Añade un Producto a tu Local sin incluirlo en ningún Menú';

		$urlLocal = $this->multipleUtils->getUrlOfLocalForMenuNavigation($request->attributes->get('local'));

		return $this->render(
			'/Panel/Sections/newProductAlone.html.twig',
			[
				'local' => $request->attributes->get('local'),
				'datos' => $productsRelated,
				'title' => $title,
				'urlLocal' => $urlLocal,
			]
		);
	}

	public function editProductAloneAction(Request $request): Response
	{
		$productToEdit = $this->productOrchestrator->editProduct($request);
		$productsRelated = $this->productOrchestrator->showProductsCreated($request);

		$title = 'Edita el Producto seleccionado o escoge otro para editar';
		$urlLocal = $this->multipleUtils->getUrlOfLocalForMenuNavigation($request->attributes->get('local'));

		return $this->render(
			'/Panel/Sections/editProduct.html.twig',
			['local' => $request->attributes->get('local'),
				'title' => $title,
				'productToEdit' => $productToEdit,
				'productsRelated' => $productsRelated,
				'urlLocal' => $urlLocal
			]
		);
	}

	public function editProductOfMenuAction(Request $request): Response
	{
		$productToEdit = $this->productOrchestrator->editProduct($request);
		$productsRelated = $this->productOrchestrator->showProductsCreated($request);

		$title = 'Edita el Producto seleccionado o escoge otro para editar';
		$urlLocal = $this->multipleUtils->getUrlOfLocalForMenuNavigation($request->attributes->get('local'));

		return $this->render(
			'/Panel/Sections/editProduct.html.twig',
			['local' => $request->attributes->get('local'),
				'title' => $title,
				'productToEdit' => $productToEdit,
				'productsRelated' => $productsRelated,
				'urlLocal' => $urlLocal,
			]
		);
	}
	
	public function editProductForDeletePhotoOfLocalAction(Request $request): Response
	{
		$this->productOrchestrator->editProductForDeletePhoto($request);

		if (!$request->query->get('menuId')) {
			return $this->redirectToRoute(
				'panel-edit-product-alone',
				['local' => $request->attributes->get('local'),
					'productoId' => $request->attributes->get('productoId')]
			);
		}
		return $this->redirectToRoute(
			'panel-edit-product',
			['local' => $request->attributes->get('local'),
				'menuId' => $request->attributes->get('menuId'),
				'productoId' => $request->attributes->get('productoId')]
		);
	}

	public function deleteProductOfLocalAction(Request $request): Response
	{
		$this->productOrchestrator->deleteProduct($request);
		return $this->redirectToRoute(
			'panel-edit-menu-and-show-products',
			['local' => $request->attributes->get('local'), 'menuId' => $request->attributes->get('menuId')]
		);
	}
	public function deleteProductAloneOfLocalAction(Request $request): Response
	{
		$this->productOrchestrator->deleteProduct($request);
		return $this->redirectToRoute('panel-new-product-alone', ['local' => $request->attributes->get('local')]);
	}
}
