<?php

declare(strict_types=1);

namespace App\Domain\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class HorarioLocal
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
	private $diaSemana;

	/**
	 * @ORM\Column(type="time")
	 */
	private $horaApertura;

	/**
	 * @ORM\Column(type="time")
	 */
	private $horaCierre;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Domain\Model\Informacion", inversedBy="horariosLocal")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $informacion;

	/**
	 * @ORM\Column(type="boolean")
	 */
	private $noMostrarHora;

	public function getId()
	{
		return $this->id;
	}

	public function getDiaSemana()
	{
		return $this->diaSemana;
	}

	public function setDiaSemana($diaSemana)
	{
		$this->diaSemana = $diaSemana;
	}

	public function getHoraApertura()
	{
		return $this->horaApertura;
	}

	public function setHoraApertura($horaApertura)
	{
		$this->horaApertura = $horaApertura;
	}

	public function getHoraCierre()
	{
		return $this->horaCierre;
	}

	public function setHoraCierre($horaCierre)
	{
		$this->horaCierre = $horaCierre;
	}

	public function getInformacion()
	{
		return $this->informacion;
	}

	public function setInformacion($informacion)
	{
		$this->informacion = $informacion;
	}

	public function getNoMostrarHora()
	{
		return $this->noMostrarHora;
	}

	public function setNoMostrarHora($noMostrarHora)
	{
		return $this->noMostrarHora = $noMostrarHora;
	}
}
