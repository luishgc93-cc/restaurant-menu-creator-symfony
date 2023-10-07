<?php

namespace App\Domain\Model;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="informacion")
 */
class Informacion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $telefono;

    /**
     * @ORM\Column(type="text")
     */
    private $descripcion;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $calle;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $localidad;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ciudad;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity="App\Domain\Model\InformacionPhoto", mappedBy="informacion", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $photos;

    /**
     * @ORM\OneToOne(targetEntity="App\Domain\Model\Local", inversedBy="informacion", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $local;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $facebook;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $instagram;

        /**
     * @ORM\Column(type="string", length=255)
     */
    private $twiter;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $youtube;
    
    /**
     * @ORM\OneToMany(targetEntity="App\Domain\Model\HorarioLocal", mappedBy="local", cascade={"persist", "remove"})
     */
    private $horariosLocal;

    public function __construct()
    {
        $this->photos = new ArrayCollection();
        $this->horariosLocal = new ArrayCollection();
    }

    public function getPhotos()
    {
        return $this->photos;
    }

    public function addPhoto(InformacionPhoto $photo)
    {
        $this->photos->add($photo);
        $photo->setInformacion($this);
    }

    public function removePhoto(InformacionPhoto $photo)
    {
        $this->photos->removeElement($photo);
        $photo->setInformacion(null);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTelefono()
    {
        return $this->telefono;
    }

    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;
    }

    public function getDescripcion()
    {
        return $this->descripcion;
    }

    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }

    public function getCalle()
    {
        return $this->calle;
    }

    public function setCalle($calle)
    {
        $this->calle = $calle;
    }

    public function getLocalidad()
    {
        return $this->localidad;
    }

    public function setLocalidad($localidad)
    {
        $this->localidad = $localidad;
    }

    public function getCiudad()
    {
        return $this->ciudad;
    }

    public function setCiudad($ciudad)
    {
        $this->ciudad = $ciudad;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getFacebook()
    {
        return $this->facebook;
    }

    public function setFacebook($facebook)
    {
        $this->facebook = $facebook;
    }
    public function getInstagram()
    {
        return $this->instagram;
    }

    public function setInstagram($instagram)
    {
        $this->instagram = $instagram;
    }
    public function getYoutube()
    {
        return $this->youtube;
    }

    public function setYoutube($youtube)
    {
        $this->youtube = $youtube;
    }
    public function getTwiter()
    {
        return $this->twiter;
    }

    public function setTwiter($twiter)
    {
        $this->twiter = $twiter;
    }
    
    public function getFotosInformativas()
    {
        return $this->fotosInformativas;
    }

    public function setFotosInformativas($fotosInformativas)
    {
        $this->fotosInformativas = $fotosInformativas;
    }

    public function getLocal()
    {
        return $this->local;
    }

    public function setLocal($local)
    {
        $this->local = $local;
    }
    public function addHorarioLocal(HorarioLocal $horario)
    {
        $this->horariosLocal[] = $horario;
        $horario->setInformacion($this);
    }

    public function removeHorarioLocal(HorarioLocal $horario)
    {
        $this->horariosLocal->removeElement($horario);
        $horario->setInformacion(null);
    }
    public function getHorariosLocal()
    {
        return $this->horariosLocal;
    }
}
