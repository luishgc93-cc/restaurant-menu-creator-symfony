<?php

namespace App\Domain\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class MenuPhoto
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
    private $photoPath;

    /**
     * @ORM\ManyToOne(targetEntity="App\Domain\Model\Menu", inversedBy="photos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $menu;

    // Add more properties, getters, and setters as needed

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

    public function getMenu()
    {
        return $this->menu;
    }

    public function setMenu($menu)
    {
        $this->menu = $menu;
    }
}
