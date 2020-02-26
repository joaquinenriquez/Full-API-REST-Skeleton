<?php

class Sector implements JsonSerializable
{
    /* #region  Atributos */
    private $idSector;
    private $descripcionSector;
    /* #endregion */

    /* #region  Propiedades */
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
     * Get the value of descripcionSector
     */
    public function getDescripcionSector()
    {
        return $this->descripcionSector;
    }

    /**
     * Set the value of descripcionSector
     *
     * @return  self
     */
    public function setDescripcionSector($descripcionSector)
    {
        $this->descripcionSector = $descripcionSector;

        return $this;
    }

    /* #endregion */

    public function jsonSerialize()
    {
        return
            [
            'id_sector' => $this->getIdSector(),
            'descripcion' => $this->getDescripcionSector(),
        ];
    }
}
