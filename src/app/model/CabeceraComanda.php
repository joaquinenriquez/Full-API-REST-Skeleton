<?php

class CabeceraComanda
{
/* #region  Atributos */

    private $idComanda;
    private $idUsuario;
    private $nombreCliente;
    private $estado;
    private $codigoAmigable;
    private $mesa;
    private $foto;

/* #endregion */

/* #region  Propiedades */
    /**
     * Get the value of idComanda
     */
    public function getIdComanda()
    {
        return $this->idComanda;
    }

    /**
     * Set the value of idComanda
     *
     * @return  self
     */
    public function setIdComanda($idComanda)
    {
        $this->idComanda = $idComanda;

        return $this;
    }

    /**
     * Get the value of idUsuario
     */
    public function getIdUsuario()
    {
        return $this->idUsuario;
    }

    /**
     * Set the value of idUsuario
     *
     * @return  self
     */
    public function setIdUsuario($idUsuario)
    {
        $this->idUsuario = $idUsuario;

        return $this;
    }

    /**
     * Get the value of nombreCliente
     */
    public function getNombreCliente()
    {
        return $this->nombreCliente;
    }

    /**
     * Set the value of nombreCliente
     *
     * @return  self
     */
    public function setNombreCliente($nombreCliente)
    {
        $this->nombreCliente = $nombreCliente;

        return $this;
    }

    /**
     * Get the value of estado
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set the value of estado
     *
     * @return  self
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get the value of codigoAmigable
     */
    public function getCodigoAmigable()
    {
        return $this->codigoAmigable;
    }

    /**
     * Set the value of codigoAmigable
     *
     * @return  self
     */
    public function setCodigoAmigable($codigoAmigable)
    {
        $this->codigoAmigable = $codigoAmigable;

        return $this;
    }

    /**
     * Get the value of mesa
     */
    public function getMesa()
    {
        return $this->mesa;
    }

    /**
     * Set the value of mesa
     *
     * @return  self
     */
    public function setMesa($mesa)
    {
        $this->mesa = $mesa;

        return $this;
    }


    /**
     * Get the value of foto
     */ 
    public function getFoto()
    {
        return $this->foto;
    }

    /**
     * Set the value of foto
     *
     * @return  self
     */ 
    public function setFoto($foto)
    {
        $this->foto = $foto;

        return $this;
    }



/* #endregion */



}
