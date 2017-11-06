<?php

namespace BackendBundle\Entity;

/**
 * CharolaTipo
 */
class CharolaTipo
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
     * @var \BackendBundle\Entity\Usuario
     */
    private $usuario;


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
     * @return CharolaTipo
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
     * Set usuario
     *
     * @param \BackendBundle\Entity\Usuario $usuario
     *
     * @return CharolaTipo
     */
    public function setUsuario(\BackendBundle\Entity\Usuario $usuario = null)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return \BackendBundle\Entity\Usuario
     */
    public function getUsuario()
    {
        return $this->usuario;
    }
}

