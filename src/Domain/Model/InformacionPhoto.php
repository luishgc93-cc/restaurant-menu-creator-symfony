<?php

namespace App\Domain\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class InformacionPhoto
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=1000)
     */
    private $photoPath;

    /**
     * @ORM\ManyToOne(targetEntity="App\Domain\Model\Informacion", inversedBy="photos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $informacion;

    public function getId()
    {
        return $this->id;
    }

    public function getPhotoPath()
    {
        return $this->photoPath;
    }

    public function setPhotoPath($photoPath)
    {
        $this->photoPath = $photoPath;
    }

    public function getInformacion()
    {
        return $this->informacion;
    }

    public function setInformacion($informacion)
    {
        $this->informacion = $informacion;
    }
}
