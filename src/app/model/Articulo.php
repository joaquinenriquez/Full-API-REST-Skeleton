<?php

class Articulo implements jsonSerializable
{
/* #region  Atributos */

    private $idArticulo;
    private $descripcion;
    private $idSector;
    private $importe;
    private $estado;

/* #endregion */

/* #region  Propiedades */
    /**
     * Get the value of idArticulo
     */
    public function getIdArticulo()
    {
        return $this->idArticulo;
    }

    /**
     * Set the value of idArticulo
     *
     * @return  self
     */
    public function setIdArticulo($idArticulo)
    {
        $this->idArticulo = $idArticulo;

        return $this;
    }

    /**
     * Get the value of descripcion
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set the value of descripcion
     *
     * @return  self
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get the value of idSector
     */
    public function getIdSector()
    {
        return $this->idSector;
    }

    /**
     * Set the value of idSector
     *
     * @return  self
     */
    public function setIdSector($idSector)
    {
        $this->idSector = $idSector;

        return $this;
    }

    /**
     * Get the value of importe
     */
    public function getImporte()
    {
        return $this->importe;
    }

    /**
     * Set the value of importe
     *
     * @return  self
     */
    public function setImporte($importe)
    {
        $this->importe = $importe;

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
/* #endregion */

/* #region  Métodos */
public function jsonSerialize() 
{
    return 
    [
        'idArticulo' => $this->getIdArticulo(),
        'descripcion' => $this->getDescripcion(),
        'idSector' => $this->getIdSector(),
        'importe' => $this->getImporte(),
        'estado' => $this->getEstado()
    ];
}
/* #endregion */
}

?>