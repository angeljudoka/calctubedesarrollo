<?php

namespace BackendBundle\Entity;

/**
 * Tubo
 */
class Tubo
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
    private $tamanoComercial;

    /**
     * @var integer
     */
    private $designacionMetrica;

    /**
     * @var float
     */
    private $diametroInterno;

    /**
     * @var \BackendBundle\Entity\TuboTipo
     */
    private $tuboTipo;


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
     * @return Tubo
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
     * Set tamanoComercial
     *
     * @param float $tamanoComercial
     *
     * @return Tubo
     */
    public function setTamanoComercial($tamanoComercial)
    {
        $this->tamanoComercial = $tamanoComercial;

        return $this;
    }

    /**
     * Get tamanoComercial
     *
     * @return float
     */
    public function getTamanoComercial()
    {
        return $this->tamanoComercial;
    }

    /**
     * Set designacionMetrica
     *
     * @param integer $designacionMetrica
     *
     * @return Tubo
     */
    public function setDesignacionMetrica($designacionMetrica)
    {
        $this->designacionMetrica = $designacionMetrica;

        return $this;
    }

    /**
     * Get designacionMetrica
     *
     * @return integer
     */
    public function getDesignacionMetrica()
    {
        return $this->designacionMetrica;
    }

    /**
     * Set diametroInterno
     *
     * @param float $diametroInterno
     *
     * @return Tubo
     */
    public function setDiametroInterno($diametroInterno)
    {
        $this->diametroInterno = $diametroInterno;

        return $this;
    }

    /**
     * Get diametroInterno
     *
     * @return float
     */
    public function getDiametroInterno()
    {
        return $this->diametroInterno;
    }

    /**
     * Set tuboTipo
     *
     * @param \BackendBundle\Entity\TuboTipo $tuboTipo
     *
     * @return Tubo
     */
    public function setTuboTipo(\BackendBundle\Entity\TuboTipo $tuboTipo = null)
    {
        $this->tuboTipo = $tuboTipo;

        return $this;
    }

    /**
     * Get tuboTipo
     *
     * @return \BackendBundle\Entity\TuboTipo
     */
    public function getTuboTipo()
    {
        return $this->tuboTipo;
    }
}

