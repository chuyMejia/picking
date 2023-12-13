<?php

namespace PickingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * trv_manifiestos
 *
 * @ORM\Table(name="trv_manifiestos")
 * @ORM\Entity(repositoryClass="PickingBundle\Repository\trv_manifiestosRepository")
 */
class trv_manifiestos
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
     * @ORM\Column(name="guia", type="string", length=150)
     */
    private $guia;

    /**
     * @var string
     *
     * @ORM\Column(name="ordenVenta", type="string", length=50)
     */
    private $ordenVenta;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="date")
     */
    private $fecha;

    /**
     * @var string
     *
     * @ORM\Column(name="paqueteria", type="string", length=150, nullable=true)
     */
    private $paqueteria;

    /**
     * @var string
     *
     * @ORM\Column(name="cliente", type="string", length=255, nullable=true)
     */
    private $cliente;


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
     * Set guia
     *
     * @param string $guia
     *
     * @return trv_manifiestos
     */
    public function setGuia($guia)
    {
        $this->guia = $guia;

        return $this;
    }

    /**
     * Get guia
     *
     * @return string
     */
    public function getGuia()
    {
        return $this->guia;
    }

    /**
     * Set ordenVenta
     *
     * @param string $ordenVenta
     *
     * @return trv_manifiestos
     */
    public function setOrdenVenta($ordenVenta)
    {
        $this->ordenVenta = $ordenVenta;

        return $this;
    }

    /**
     * Get ordenVenta
     *
     * @return string
     */
    public function getOrdenVenta()
    {
        return $this->ordenVenta;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return trv_manifiestos
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
     * Set paqueteria
     *
     * @param string $paqueteria
     *
     * @return trv_manifiestos
     */
    public function setPaqueteria($paqueteria)
    {
        $this->paqueteria = $paqueteria;

        return $this;
    }

    /**
     * Get paqueteria
     *
     * @return string
     */
    public function getPaqueteria()
    {
        return $this->paqueteria;
    }

    /**
     * Set cliente
     *
     * @param string $cliente
     *
     * @return trv_manifiestos
     */
    public function setCliente($cliente)
    {
        $this->cliente = $cliente;

        return $this;
    }

    /**
     * Get cliente
     *
     * @return string
     */
    public function getCliente()
    {
        return $this->cliente;
    }
}

