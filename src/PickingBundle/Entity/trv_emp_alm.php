<?php

namespace PickingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * trv_emp_alm
 *
 * @ORM\Table(name="trv_emp_alm")
 * @ORM\Entity(repositoryClass="PickingBundle\Repository\trv_emp_almRepository")
 */
class trv_emp_alm
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="codEmp", type="integer", unique=true)
     */
    private $codEmp;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=100)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="apellido", type="string", length=100)
     */
    private $apellido;

    /**
     * @var string
     *
     * @ORM\Column(name="nombreComp", type="string", length=200)
     */
    private $nombreComp;

    /**
     * @var bool
     *
     * @ORM\Column(name="activo", type="boolean", nullable=true)
     */
    private $activo;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set codEmp
     *
     * @param integer $codEmp
     *
     * @return trv_emp_alm
     */
    public function setCodEmp($codEmp)
    {
        $this->codEmp = $codEmp;

        return $this;
    }

    /**
     * Get codEmp
     *
     * @return int
     */
    public function getCodEmp()
    {
        return $this->codEmp;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return trv_emp_alm
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set apellido
     *
     * @param string $apellido
     *
     * @return trv_emp_alm
     */
    public function setApellido($apellido)
    {
        $this->apellido = $apellido;

        return $this;
    }

    /**
     * Get apellido
     *
     * @return string
     */
    public function getApellido()
    {
        return $this->apellido;
    }

    /**
     * Set nombreComp
     *
     * @param string $nombreComp
     *
     * @return trv_emp_alm
     */
    public function setNombreComp($nombreComp)
    {
        $this->nombreComp = $nombreComp;

        return $this;
    }

    /**
     * Get nombreComp
     *
     * @return string
     */
    public function getNombreComp()
    {
        return $this->nombreComp;
    }

    /**
     * Set activo
     *
     * @param boolean $activo
     *
     * @return trv_emp_alm
     */
    public function setActivo($activo)
    {
        $this->activo = $activo;

        return $this;
    }

    /**
     * Get activo
     *
     * @return bool
     */
    public function getActivo()
    {
        return $this->activo;
    }
}
