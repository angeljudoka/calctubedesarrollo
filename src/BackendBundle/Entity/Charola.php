<?php

namespace BackendBundle\Entity;

/**
 * Charola
 */
class Charola
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
    private $peralte;

    /**
     * @var \BackendBundle\Entity\CharolaTipo
     */
    private $charolaTipo;


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
     * @return Charola
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
     * Set peralte
     *
     * @param float $peralte
     *
     * @return Charola
     */
    public function setPeralte($peralte)
    {
        $this->peralte = $peralte;

        return $this;
    }

    /**
     * Get peralte
     *
     * @return float
     */
    public function getPeralte()
    {
        return $this->peralte;
    }

    /**
     * Set charolaTipo
     *
     * @param \BackendBundle\Entity\CharolaTipo $charolaTipo
     *
     * @return Charola
     */
    public function setCharolaTipo(\BackendBundle\Entity\CharolaTipo $charolaTipo = null)
    {
        $this->charolaTipo = $charolaTipo;

        return $this;
    }

    /**
     * Get charolaTipo
     *
     * @return \BackendBundle\Entity\CharolaTipo
     */
    public function getCharolaTipo()
    {
        return $this->charolaTipo;
    }
}

