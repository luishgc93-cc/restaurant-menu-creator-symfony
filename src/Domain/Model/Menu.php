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
     * @ORM\Column(type="blob", nullable=true)
     */
    private $fotoMenu;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Informacion")
     * @ORM\JoinColumn(nullable=false)
     */
    private $informacion;

    // Getters y setters

    // ...

    // Relación con la entidad "producto"
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Producto")
     * @ORM\JoinTable(name="menu_producto",
     *   joinColumns={
     *     @ORM\JoinColumn(name="menu_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="producto_id", referencedColumnName="id")
     *   }
     * )
     */
    private $productos;

    // Resto de propiedades, getters y setters

    // ...
    public function getId(){
		return $this->id;
	}

	public function getNombreMenu(){
		return $this->nombreMenu;
	}

	public function setNombreMenu($nombreMenu){
		$this->nombreMenu = $nombreMenu;
	}

	public function getInformacionMenu(){
		return $this->informacionMenu;
	}

	public function setInformacionMenu($informacionMenu){
		$this->informacionMenu = $informacionMenu;
	}

	public function getPrecioMenu(){
		return $this->precioMenu;
	}

	public function setPrecioMenu($precioMenu){
		$this->precioMenu = $precioMenu;
	}

	public function getFotoMenu(){
		return $this->fotoMenu;
	}

	public function setFotoMenu($fotoMenu){
		$this->fotoMenu = $fotoMenu;
	}

	public function getInformacion(){
		return $this->informacion;
	}

	public function setInformacion($informacion){
		$this->informacion = $informacion;
	}

	public function getProductos(){
		return $this->productos;
	}

	public function setProductos($productos){
		$this->productos = $productos;
	}
}
