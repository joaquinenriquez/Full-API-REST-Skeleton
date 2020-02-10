<?php

class Mesa 
{
/* #region  Atributos */
    private $idMesa;
    private $numeroMesa;
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
     * Get the value of numeroMesa
     */ 
    public function getNumeroMesa()
    {
        return $this->numeroMesa;
    }

    /**
     * Set the value of numeroMesa
     *
     * @return  self
     */ 
    public function setNumeroMesa($numeroMesa)
    {
        $this->numeroMesa = $numeroMesa;

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

}

?>