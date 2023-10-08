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


use Cloudinary\Api\ApiResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Cloudinary\Api\Upload\UploadApi;
use Cloudinary\Configuration\Configuration;

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

        $config = Configuration::instance();
        $config->cloud->cloudName = 'dmo3iliks';
        $config->cloud->apiKey = '982421683171437';
        $config->cloud->apiSecret = 'iqY0a7gPn-3ozoBlf6MsmwMI4yo';
        $config->url->secure = true;

        $uploadApi = new UploadApi();
        /** @var ApiResponse $response */
        $response = $uploadApi->upload($filePath);

        if ($response['url'] ?? '') {
            unlink($filePath);
        }
        return $response['url'];
    }
}
