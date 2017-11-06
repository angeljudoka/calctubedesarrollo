<?php

namespace BackendBundle\Entity;

/**
 * Cable
 */
class Cable
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $nombre;

    /**
     * @var float
     */
    private $diametroExterior;

    /**
     * @var \BackendBundle\Entity\CableTipo
     */
    private $cableTipo;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Cable
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
     * Set diametroExterior
     *
     * @param float $diametroExterior
     *
     * @return Cable
     */
    public function setDiametroExterior($diametroExterior)
    {
        $this->diametroExterior = $diametroExterior;

        return $this;
    }

    /**
     * Get diametroExterior
     *
     * @return float
     */
    public function getDiametroExterior()
    {
        return $this->diametroExterior;
    }

    /**
     * Set cableTipo
     *
     * @param \BackendBundle\Entity\CableTipo $cableTipo
     *
     * @return Cable
     */
    public function setCableTipo(\BackendBundle\Entity\CableTipo $cableTipo = null)
    {
        $this->cableTipo = $cableTipo;

        return $this;
    }

    /**
     * Get cableTipo
     *
     * @return \BackendBundle\Entity\CableTipo
     */
    public function getCableTipo()
    {
        return $this->cableTipo;
    }
}

