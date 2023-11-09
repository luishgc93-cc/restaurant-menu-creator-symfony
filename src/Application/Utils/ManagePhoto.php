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
use Symfony\Component\HttpKernel\Exception\HttpException;

final class ManagePhoto extends AbstractController
{
	public function upload($files): string
	{
		$fileType = $files->getClientMimeType();
		$allowedFiles = ['image/jpeg', 'image/gif', 'image/png'];
		if (!in_array($fileType, $allowedFiles)) {
			throw new HttpException(404, 'Error, fichero no autorizado');
		}

		$destination = $this->getParameter('uploads_photos_directory');
		$fileName = uniqid() . '.' . $files->guessExtension();
		$files->move(
			$this->getParameter('uploads_photos_directory'),
			$fileName
		);
		$filePath = $destination . $fileName;
		
		if ($files->getClientMimeType() !== 'image/png') {
			$this->compressImage($filePath, $filePath, 50);
		}

		if (filesize($filePath) > 1500000) {
			unlink($filePath);
			return '';
		}

		return $fileName;
	}
	public function deletePhoto(string $file): bool
	{
		$hrefFile = $this->getParameter('uploads_photos_directory') . $file;
		try {
			unlink($hrefFile);
			return true;
		} catch (Exception $e) {
			return false;
		}
	}
	public function compressImage($source, $destination, $quality)
	{
		$imgInfo = getimagesize($source);
		$mime = $imgInfo['mime'];
		 
		switch ($mime) {
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
