<?php

declare(strict_types=1);

namespace App\Domain\Model;

use Doctrine\Common\Collections\ArrayCollection;
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
	 * @ORM\OneToMany(targetEntity="App\Domain\Model\ProductoPhoto", mappedBy="producto", cascade={"persist", "remove"}, orphanRemoval=true)
	 */
	private $photos;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $estilo;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Domain\Model\Menu")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $menus;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Domain\Model\Informacion")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $informacion;
	
	/**
	 * @ORM\Column(type="integer")
	 */
	private $userId;

	public function __construct()
	{
		$this->photos = new ArrayCollection();
	}
	
	public function getPhotos()
	{
		return $this->photos;
	}

	public function addPhoto(ProductoPhoto $photo)
	{
		$this->photos->add($photo);
		$photo->setProducto($this);
	}

	public function removePhoto(ProductoPhoto $photo)
	{
		$this->photos->removeElement($photo);
		$photo->setProducto(null);
	}

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

	public function getInformacion()
	{
		return $this->informacion;
	}

	public function setInformacion($informacion)
	{
		$this->informacion = $informacion;
	}

	public function getUserId()
	{
		return $this->userId;
	}

	public function setUserId($userId)
	{
		$this->userId = $userId;
	}
}
