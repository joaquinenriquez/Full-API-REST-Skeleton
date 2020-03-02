<?php

class Mesa implements jsonSerializable
{
/* #region  Atributos */
    private $idMesa;
    private $codigoAmigable;
    private $estado;
/* #endregion */

/* #region  Propiedades */
    /**
     * Get the value of idMesa
     */
    public function getIdMesa()
    {
        return $this->idMesa;
    }

    /**
     * Set the value of idMesa
     *
     * @return  self
     */
    public function setIdMesa($idMesa)
    {
        $this->idMesa = $idMesa;

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

    public function jsonSerialize()
    {
        return
            [
            'id_mesa' => $this->getIdMesa(),
            'codigo_amigable' => $this->getCodigoAmigable(),
            'estado' => $this->getEstado(),
        ];
    }

}
