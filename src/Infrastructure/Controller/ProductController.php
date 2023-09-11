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

final class ProductController extends AbstractController
{
    private ProductOrchestrator $productOrchestrator;

    public function __construct(ProductOrchestrator $productOrchestrator)
    {
        $this->productOrchestrator = $productOrchestrator;
    }

    public function newProductAction(Request $request): Response
    {

        $datos = $this->productOrchestrator->newProduct($request);

        $title = 'Añade un Producto a tu Local';

        return $this->render(
            '/Panel/Sections/newProduct.html.twig',
            ['local' => $request->attributes->get('local'), 
            'menuId' => $request->attributes->get('menuId'), 
            'datos'=> $datos, 
            'title'=>$title]
        );
    }

    public function newProductAloneAction(Request $request)
    {

        $datos = $this->productOrchestrator->newProduct($request, true);
        $productsRelated = $this->productOrchestrator->showProductsCreated($request);

        $title = 'Añade un Producto a tu Local sin incluirlo en ningún Menú';

        return $this->render(
            '/Panel/Sections/newProductAlone.html.twig',[
            'local' => $request->attributes->get('local'), 
            'datos'=> $datos, 
            'title'=>$title,
            'productsRelated' => $productsRelated
            ]
        );
    }
    public function editProductOfMenuAction(Request $request): Response
    {

        $productToEdit = $this->productOrchestrator->editProduct($request);
        $productsRelated = $this->productOrchestrator->showProductsCreated($request);

        $title = 'Edita el Producto seleccionado o escoge otro para editar';

        return $this->render(
            '/Panel/Sections/editProduct.html.twig',
            ['local' => $request->attributes->get('local'), 
            'title'=>$title,
            'productToEdit'=>$productToEdit,
            'productsRelated' => $productsRelated
            ]
        );
    }
    public function editProductAloneAction(Request $request): Response
    {

        $productToEdit = $this->productOrchestrator->editProduct($request);
        $productsRelated = $this->productOrchestrator->showProductsCreated($request);

        $title = 'Edita el Producto seleccionado o escoge otro para editar';

        return $this->render(
            '/Panel/Sections/editProduct.html.twig',
            ['id' => $request->attributes->get('id'), 
            'title'=>$title,
            'productToEdit'=>$productToEdit,
            'productsRelated' => $productsRelated
            ]
        );
    } 

    public function deleteProductOfLocalAction(Request $request): Response
    {
        $this->productOrchestrator->deleteProduct($request);
        return $this->redirectToRoute('panel-edit-menu-and-show-products', ['id' => $request->attributes->get('id'),'menuId' => $request->attributes->get('menuId')]);
    }
}
