<?php

namespace PickingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * trv_picking
 *
 * @ORM\Table(name="trv_picking")
 * @ORM\Entity(repositoryClass="PickingBundle\Repository\trv_pickingRepository")
 */
class trv_picking
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
     * @var string
     *
     * @ORM\Column(name="ordenVenta", type="string", length=10, unique=true)
     */
    private $ordenVenta;

    /**
     * @var string
     *
     * @ORM\Column(name="picking", type="string", length=20, unique=true)
     */
    private $picking;

    /**
     * @var string
     *
     * @ORM\Column(name="factura", type="string", length=30, nullable=true)
     */
    private $factura;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */
    private $fecha;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="hora", type="time", nullable=true)
     */
    private $hora;

    /**
     * @var string
     *
     * @ORM\Column(name="estatus", type="string", length=100)
     */
    private $estatus;

    /**
     * @var \PickingBundle\Entity\trv_emp_alm
     * @ManyToOne(targetEntity="PickingBundle\Entity\trv_emp_alm", fetch="EAGER")
     * @ORM\JoinColumn(name="idEmpleado", referencedColumnName="id")
     */
    private $idEmpleado;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="horaFin", type="time", nullable=true)
     */
    private $horaFin;


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
     * Set ordenVenta
     *
     * @param string $ordenVenta
     *
     * @return trv_picking
     */
    public function setOrdenventa($ordenVenta)
    {
        $this->ordenVenta = $ordenVenta;

        return $this;
    }

    /**
     * Get ordenVenta
     *
     * @return string
     */
    public function getOrdenventa()
    {
        return $this->ordenVenta;
    }


    /**
     * Set picking
     *
     * @param string $picking
     *
     * @return trv_picking
     */
    public function setPicking($picking)
    {
        $this->picking = $picking;

        return $this;
    }

    /**
     * Get picking
     *
     * @return string
     */
    public function getPicking()
    {
        return $this->picking;
    }

    /**
     * Set factura
     *
     * @param string $factura
     *
     * @return trv_picking
     */
    public function setFactura($factura)
    {
        $this->factura = $factura;

        return $this;
    }

    /**
     * Get factura
     *
     * @return string
     */
    public function getFactura()
    {
        return $this->factura;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return trv_picking
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set hora
     *
     * @param \DateTime $hora
     *
     * @return trv_picking
     */
    public function setHora($hora)
    {
        $this->hora = $hora;

        return $this;
    }

    /**
     * Get hora
     *
     * @return \DateTime
     */
    public function getHora()
    {
        return $this->hora;
    }

    /**
     * Set estatus
     *
     * @param string $estatus
     *
     * @return trv_picking
     */
    public function setEstatus($estatus)
    {
        $this->estatus = $estatus;

        return $this;
    }

    /**
     * Get estatus
     *
     * @return string
     */
    public function getEstatus()
    {
        return $this->estatus;
    }

    /**
     * @return trv_emp_alm
     */
    public function getIdEmpleado(): trv_emp_alm
    {
        return $this->idEmpleado;
    }

    /**
     * @param trv_emp_alm $idEmpleado
     */
    public function setIdEmpleado(trv_emp_alm $idEmpleado)
    {
        $this->idEmpleado = $idEmpleado;
    }

    /**
     * @return \DateTime
     */
    public function getHoraFin()
    {
        return $this->horaFin;
    }

    /**
     * @param \DateTime $horaFin
     */
    public function setHoraFin(\DateTime $horaFin)
    {
        $this->horaFin = $horaFin;
    }
}

