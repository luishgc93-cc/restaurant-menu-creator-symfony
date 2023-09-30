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

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Application\Orchestrator\ProductOrchestrator;
use App\Application\Utils\MultipleUtils;

final class ProductController extends AbstractController
{
    private ProductOrchestrator $productOrchestrator;
    private MultipleUtils $multipleUtils;

    public function __construct(
        ProductOrchestrator $productOrchestrator, 
        MultipleUtils $multipleUtils
        )
    {
        $this->productOrchestrator = $productOrchestrator;
        $this->multipleUtils = $multipleUtils;
    }

    public function newProductAction(Request $request): Response
    {
        $datos = $this->productOrchestrator->newProduct($request);

        $title = 'Añade un Producto a tu Local';

        $urlLocal = $this->multipleUtils->getUrlOfLocalForMenuNavigation($request->attributes->get('local'));

        return $this->render(
            '/Panel/Sections/newProduct.html.twig',
            ['local' => $request->attributes->get('local'), 
            'menuId' => $request->attributes->get('menuId'), 
            'datos'=> $datos, 
            'title'=>$title,
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
            '/Panel/Sections/newProductAlone.html.twig',[
            'local' => $request->attributes->get('local'), 
            'datos'=> $productsRelated, 
            'title'=>$title,
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
            'title'=>$title,
            'productToEdit'=>$productToEdit,
            'productsRelated' => $productsRelated,
            'urlLocal' => $urlLocal
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
            'title'=>$title,
            'productToEdit'=>$productToEdit,
            'productsRelated' => $productsRelated,
            'urlLocal' => $urlLocal
            ]
        );
    } 

    public function deleteProductOfLocalAction(Request $request): Response
    {
        $this->productOrchestrator->deleteProduct($request);
        return $this->redirectToRoute('panel-edit-menu-and-show-products', ['local' => $request->attributes->get('local'),'menuId' => $request->attributes->get('menuId')]);
    }
    public function deleteProductAloneOfLocalAction(Request $request): Response
    {
        $this->productOrchestrator->deleteProduct($request);
        return $this->redirectToRoute('panel-new-product-alone', ['local' => $request->attributes->get('local')]);
    }
}
