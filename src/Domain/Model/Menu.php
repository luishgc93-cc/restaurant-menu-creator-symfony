<?php

namespace App\Domain\Model;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection; 

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
     * @ORM\OneToMany(targetEntity="App\Domain\Model\MenuPhoto", mappedBy="menu", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $photos;

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
     * @ORM\ManyToMany(targetEntity="Producto", cascade={"persist", "remove"})
     * @ORM\JoinTable(name="menu_producto",
     *      joinColumns={@ORM\JoinColumn(name="menu_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="producto_id", referencedColumnName="id")}
     * )
     */
    private $productos;

    /**
     * @ORM\Column(type="integer")
     */
     private $userId;

    public function __construct()
    {
        $this->photos = new ArrayCollection();
        $this->productos = new ArrayCollection();
    }
    
    public function getPhotos()
    {
        return $this->photos;
    }

    public function addPhoto(MenuPhoto $photo)
    {
        $this->photos->add($photo);
        $photo->setMenu($this);
    }

    public function removePhoto(MenuPhoto $photo)
    {
        $this->photos->removeElement($photo);
        $photo->setMenu(null);
    }

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

    public function agregarProducto(Producto $producto)
    {
        $this->productos[] = $producto;
    }
    public function removeProducto(Producto $producto)
    {
        $this->productos->removeElement($producto);
        $producto->setMenus(null);
    }

    public function getEstilo()
    {
        return $this->estilo;
    }

    public function setEstilo($estilo)
    {
        $this->estilo = $estilo;
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
