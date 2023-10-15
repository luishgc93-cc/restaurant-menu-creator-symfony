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

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ManagePhoto extends AbstractController
{
    public function upload($files) : String
    {
        $destination = $this->getParameter('uploads_photos_directory');
        $fileName = uniqid().'.'.$files->guessExtension();
        $files->move(
            $this->getParameter('uploads_photos_directory'),
            $fileName
        );
        $filePath = $destination.$fileName;
        
        if('image/png' !== $files->getClientMimeType()){
            $this->compressImage($filePath,$filePath, 50);
        }

        if(filesize($filePath) > 1500000){
            unlink($filePath);
            return '';
        }

        return $fileName;
    }
    public function deletePhoto(string $file) : bool
    {
        $hrefFile = $this->getParameter('uploads_photos_directory') . $file;
        try {
            unlink($hrefFile);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    function compressImage($source, $destination, $quality) { 
        $imgInfo = getimagesize($source); 
        $mime = $imgInfo['mime']; 
         
        switch($mime){ 
            case 'image/jpeg': 
                $image = imagecreatefromjpeg($source); 
                break; 
            case 'image/gif': 
                $image = imagecreatefromgif($source); 
                break; 
            default: 
                $image = imagecreatefromjpeg($source); 
        } 
        imagejpeg($image, $destination, $quality); 
        
        return $destination; 
    }
}
