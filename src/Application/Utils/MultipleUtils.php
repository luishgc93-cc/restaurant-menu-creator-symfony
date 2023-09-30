<?php

/**
 * Este archivo pertenece a la aplicación XYZ.
 * Todos los derechos reservados.
 *
 * @category Utils
 * @package  App\Application\Utils\MultipleUtils
 * @license  Todos los derechos reservados
 */

declare(strict_types=1);

namespace App\Application\Utils;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Infrastructure\Persistence\Doctrine\Repository\LocalRepository;

final class MultipleUtils extends AbstractController
{
    private LocalRepository $localRepository;

    public function __construct(
    LocalRepository $localRepository, 
    )
    
    {
    $this->localRepository = $localRepository;
    }

    public function getUrlOfLocalForMenuNavigation(string $localId) :string
    {
        $localRepository = $this->localRepository->findOneBy(array('id' => $localId));

        if($localRepository){
            return $localRepository->getUrl() ?? '';
        }

        return '';
    }
    
}
