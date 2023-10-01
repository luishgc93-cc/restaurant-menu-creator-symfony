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
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class StaticController extends AbstractController
{
    public function avisoLegalAction(): Response
    {
        $title = 'Aviso Legal';
        return $this->render('/Static/aviso-legal.html.twig',['title'=>$title]);
    }

    public function privacidadAction(): Response
    {
        $title = 'Política de Privacidad';
        return $this->render('/Static/politica-de-privacidad.html.twig',['title'=>$title]);
    }

    public function terminosYCondicionesAction(): Response
    {
        $title = 'Terminos & Condiciones de Uso';
        return $this->render('/Static/terminosYCondicionesDeUso.html.twig',['title'=>$title]);
    }

    public function faqAction(): Response
    {
        $title = 'FAQ';
        return $this->render('/Static/faq.html.twig',['title'=>$title]);
    }

    public function soporteAction(): Response
    {
        $title = 'Soporte al usuario';
        return $this->render('/Static/soporte.html.twig',['title'=>$title]);
    }

    public function contactoAction(): Response
    {
        $title = 'Contacto';
        return $this->render('/Static/contacto.html.twig',['title'=>$title]);
    }
}
