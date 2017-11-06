<?php

namespace BackendBundle\Entity;

/**
 * Canaleta
 */
class Canaleta
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
    private $area;

    /**
     * @var \BackendBundle\Entity\CanaletaTipo
     */
    private $canaletaTipo;


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
     * @return Canaleta
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
     * Set area
     *
     * @param float $area
     *
     * @return Canaleta
     */
    public function setArea($area)
    {
        $this->area = $area;

        return $this;
    }

    /**
     * Get area
     *
     * @return float
     */
    public function getArea()
    {
        return $this->area;
    }

    /**
     * Set canaletaTipo
     *
     * @param \BackendBundle\Entity\CanaletaTipo $canaletaTipo
     *
     * @return Canaleta
     */
    public function setCanaletaTipo(\BackendBundle\Entity\CanaletaTipo $canaletaTipo = null)
    {
        $this->canaletaTipo = $canaletaTipo;

        return $this;
    }

    /**
     * Get canaletaTipo
     *
     * @return \BackendBundle\Entity\CanaletaTipo
     */
    public function getCanaletaTipo()
    {
        return $this->canaletaTipo;
    }
}

