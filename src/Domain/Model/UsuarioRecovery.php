<?php

namespace App\Domain\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="usuario_recovery")
 */
class UsuarioRecovery
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\Column(type="datetime")
     * 
     */
    private $fechaExpiracion;

    /**
     * @ORM\ManyToOne(targetEntity="App\Domain\Model\Usuario", inversedBy="recoveryTokens")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $usuario;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pin;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFechaExpiracion(): ?\DateTimeInterface
    {
        return $this->fechaExpiracion;
    }

    public function setFechaExpiracionPin(\DateTimeInterface $fechaExpiracion): self
    {
        $this->fechaExpiracion = $fechaExpiracion;
        return $this;

    }

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(?Usuario $usuario): self
    {
        $this->usuario = $usuario;
        return $this;
    }

    public function getPin(): ?string
    {
        return $this->pin;
    }

    public function setPin(string $pin): self
    {
        $this->pin = $pin;

        return $this;
    }
}
