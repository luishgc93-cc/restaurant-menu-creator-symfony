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
     * @ORM\Column(type="blob", nullable=true)
     */
    private $fotoLocal;

    /**
     * @ORM\ManyToOne(targetEntity="App\Domain\Model\Usuario")
     * @ORM\JoinColumn(nullable=false)
     */
    private $usuario;

    // Getters y setters

    // ...

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

	public function getId(){
		return $this->id;
	}

	public function getNombreLocal(){
		return $this->nombreLocal;
	}

	public function setNombreLocal($nombreLocal){
		$this->nombreLocal = $nombreLocal;
	}

	public function getDescripcionLocal(){
		return $this->descripcionLocal;
	}

	public function setDescripcionLocal($descripcionLocal){
		$this->descripcionLocal = $descripcionLocal;
	}

	public function getFotoLocal(){
		return $this->fotoLocal;
	}

	public function setFotoLocal($fotoLocal){
		$this->fotoLocal = $fotoLocal;
	}

	public function getUsuario(){
		return $this->usuario;
	}

	public function setUsuario($usuario){
		$this->usuario = $usuario;
	}

	public function getInformacion(){
		return $this->informacion;
	}

	public function setInformacion($informacion){
		$this->informacion = $informacion;
	}

	public function getUsuarios(){
		return $this->usuarios;
	}

	public function setUsuarios($usuarios){
		$this->usuarios = $usuarios;
	}

}
