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

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Application\Utils\UploadPhoto;

final class StaticOrchestrator extends AbstractController
{

    public function __construct(
        UploadPhoto $uploadPhoto
    )

    {

        $this->uploadPhoto = $uploadPhoto;
    }

}
