<?php

namespace App\Domain\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="producto")
 */
class Producto
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
    private $nombreProducto;

    /**
     * @ORM\Column(type="text")
     */
    private $informacionProducto;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $precioProducto;

    /**
     * @ORM\Column(type="blob", nullable=true)
     */

    private $fotoProducto;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $estilo;

    /**
     * @ORM\ManyToOne(targetEntity="App\Domain\Model\Menu")
     * @ORM\JoinColumn(nullable=false)
     */
    private $menus;

    // Getters y setters

    public function getId()
    {
        return $this->id;
    }

    public function getNombreProducto()
    {
        return $this->nombreProducto;
    }

    public function setNombreProducto($nombreProducto)
    {
        $this->nombreProducto = $nombreProducto;
    }

    public function getInformacionProducto()
    {
        return $this->informacionProducto;
    }

    public function setInformacionProducto($informacionProducto)
    {
        $this->informacionProducto = $informacionProducto;
    }

    public function getPrecioProducto()
    {
        return $this->precioProducto;
    }

    public function setPrecioProducto($precioProducto)
    {
        $this->precioProducto = $precioProducto;
    }

    public function getFotoProducto()
    {
        return $this->fotoProducto;
    }

    public function setFotoProducto($fotoProducto)
    {
        $this->fotoProducto = $fotoProducto;
    }

    public function getMenus()
    {
        return $this->menus;
    }

    public function setMenus($menus)
    {
        $this->menus = $menus;
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
