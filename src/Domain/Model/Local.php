<?php

namespace App\Domain\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="local")
 */
class Local
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombreLocal;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $descripcionLocal;
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Domain\Model\Usuario")
     * @ORM\JoinColumn(nullable=false)
     */
    private $usuario;

    // Relación con la entidad "informacion"
    /**
     * @ORM\OneToOne(targetEntity="App\Domain\Model\Informacion", mappedBy="local", cascade={"persist", "remove"})
     */
    private $informacion;

    // Relación con la entidad "usuario"
    /**
     * @ORM\ManyToMany(targetEntity="App\Domain\Model\Usuario", inversedBy="locales")
     * @ORM\JoinTable(name="usuario_local",
     *   joinColumns={
     *     @ORM\JoinColumn(name="local_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="usuario_id", referencedColumnName="id")
     *   }
     * )
     */
    private $usuarios;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $estilo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @ORM\Column(type="integer")
     */
    private $bloquearWeb;

    /**
     * @ORM\Column(type="integer")
     */
    private $ocultarFormularioContacto;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $logo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $colorWeb;

    public function getId()
    {
        return $this->id;
    }

    public function getNombreLocal()
    {
        return $this->nombreLocal;
    }

    public function setNombreLocal($nombreLocal)
    {
        $this->nombreLocal = $nombreLocal;
    }

    public function getDescripcionLocal()
    {
        return $this->descripcionLocal;
    }

    public function setDescripcionLocal($descripcionLocal)
    {
        $this->descripcionLocal = $descripcionLocal;
    }

    public function getUsuario()
    {
        return $this->usuario;
    }

    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
    }

    public function getInformacion()
    {
        return $this->informacion;
    }

    public function setInformacion($informacion)
    {
        $this->informacion = $informacion;
    }

    public function getUsuarios()
    {
        return $this->usuarios;
    }

    public function setUsuarios($usuarios)
    {
        $this->usuarios = $usuarios;
    }

    public function getEstilo()
    {
        return $this->estilo;
    }

    public function setEstilo($estilo)
    {
        $this->estilo = $estilo;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }
    public function getBloquearWeb()
    {
        return $this->bloquearWeb;
    }

    public function setBloquearWeb($bloquearWeb)
    {
        $this->bloquearWeb = $bloquearWeb;
    }

    public function getOcultarFormularioContacto()
    {
        return $this->ocultarFormularioContacto;
    }

    public function setOcultarFormularioContacto($ocultarFormularioContacto)
    {
        $this->ocultarFormularioContacto = $ocultarFormularioContacto;
    }

    public function getLogo()
    {
        return $this->logo;
    }

    public function setLogo($logo)
    {
        $this->logo = $logo;
    }
    
    public function getColorWeb()
    {
        return $this->colorWeb;
    }

    public function setColorWeb($colorWeb)
    {
        $this->colorWeb = $colorWeb;
    }
}
