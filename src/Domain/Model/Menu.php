<?php

namespace App\Domain\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="menu")
 */
class Menu
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
    private $nombreMenu;

    /**
     * @ORM\Column(type="text")
     */
    private $informacionMenu;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $precioMenu;

    /**
     * @ORM\Column(type="string", length=1000)
     */
    private $fotoMenu;

    /**
     * @ORM\ManyToOne(targetEntity="App\Domain\Model\Informacion")
     * @ORM\JoinColumn(nullable=false)
     */
    private $informacion;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $estilo;

    /**
     * @ORM\ManyToMany(targetEntity="App\Domain\Model\Producto")
     */
    private $productos;

    // Resto de propiedades, getters y setters

    public function getId()
    {
        return $this->id;
    }

    public function getNombreMenu()
    {
        return $this->nombreMenu;
    }

    public function setNombreMenu($nombreMenu)
    {
        $this->nombreMenu = $nombreMenu;
    }

    public function getInformacionMenu()
    {
        return $this->informacionMenu;
    }

    public function setInformacionMenu($informacionMenu)
    {
        $this->informacionMenu = $informacionMenu;
    }

    public function getPrecioMenu()
    {
        return $this->precioMenu;
    }

    public function setPrecioMenu($precioMenu)
    {
        $this->precioMenu = $precioMenu;
    }

    public function getFotoMenu()
    {
        return $this->fotoMenu;
    }

    public function setFotoMenu($fotoMenu)
    {
        $this->fotoMenu = $fotoMenu;
    }

    public function getInformacion()
    {
        return $this->informacion;
    }

    public function setInformacion($informacion)
    {
        $this->informacion = $informacion;
    }

    public function getProductos()
    {
        return $this->productos;
    }

    public function setProductos($productos)
    {
        $this->productos = $productos;
    }

  public function getEstilo()
  {
      return $this->estilo;
  }

    public function setEstilo($estilo)
    {
        $this->estilo = $estilo;
    }
}
