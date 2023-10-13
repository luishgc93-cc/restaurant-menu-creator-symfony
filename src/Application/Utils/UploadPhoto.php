<?php

/**
 * Este archivo pertenece a la aplicación XYZ.
 * Todos los derechos reservados.
 *
 * @category Utils
 * @package  App\Application\Utils\UploadPhoto
 * @license  Todos los derechos reservados
 */

declare(strict_types=1);

namespace App\Application\Utils;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class UploadPhoto extends AbstractController
{
    public function upload($files) : String
    {
        $destination = $this->getParameter('kernel.project_dir') . '/public/uploads/';
        $fileName = uniqid().'.'.$files->guessExtension();
        $files->move($destination, $fileName);
        $filePath = $destination.$fileName;
        
        if(filesize($filePath) > 1500000){
            unlink($filePath);
            return '';
        }

        return '/uploads/' . $fileName;
    }
}
