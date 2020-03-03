<?php

class Log implements JsonSerializable
{
    #region Atributos

    private $idLog;
    private $idUsuario;
    private $nombreUsuario;
    private $fecha;
    private $hora;
    private $idRol;
    private $descripcionRol;
    private $idAccion;
    private $descripcionAccion;

    #endregion

    #region Propiedades

    /**
     * Get the value of idLog
     */ 
    public function getIdLog()
    {
        return $this->idLog;
    }

    /**
     * Set the value of idLog
     *
     * @return  self
     */ 
    public function setIdLog($idLog)
    {
        $this->idLog = $idLog;

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
     * Get the value of fecha
     */ 
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set the value of fecha
     *
     * @return  self
     */ 
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get the value of hora
     */ 
    public function getHora()
    {
        return $this->hora;
    }

    /**
     * Set the value of hora
     *
     * @return  self
     */ 
    public function setHora($hora)
    {
        $this->hora = $hora;

        return $this;
    }

    /**
     * Get the value of idRol
     */ 
    public function getIdRol()
    {
        return $this->idRol;
    }

    /**
     * Set the value of idRol
     *
     * @return  self
     */ 
    public function setIdRol($idRol)
    {
        $this->idRol = $idRol;

        return $this;
    }

        /**
     * Get the value of nombreUsuario
     */ 
    public function getNombreUsuario()
    {
        return $this->nombreUsuario;
    }

    /**
     * Set the value of nombreUsuario
     *
     * @return  self
     */ 
    public function setNombreUsuario($nombreUsuario)
    {
        $this->nombreUsuario = $nombreUsuario;

        return $this;
    }

    /**
     * Get the value of descripcionRol
     */ 
    public function getDescripcionRol()
    {
        return $this->descripcionRol;
    }

    /**
     * Set the value of descripcionRol
     *
     * @return  self
     */ 
    public function setDescripcionRol($descripcionRol)
    {
        $this->descripcionRol = $descripcionRol;

        return $this;
    }

       /**
     * Get the value of idAccion
     */ 
    public function getIdAccion()
    {
        return $this->idAccion;
    }

    /**
     * Set the value of idAccion
     *
     * @return  self
     */ 
    public function setIdAccion($idAccion)
    {
        $this->idAccion = $idAccion;

        return $this;
    }

    /**
     * Get the value of descripcionAccion
     */ 
    public function getDescripcionAccion()
    {
        return $this->descripcionAccion;
    }

    /**
     * Set the value of descripcionAccion
     *
     * @return  self
     */ 
    public function setDescripcionAccion($descripcionAccion)
    {
        $this->descripcionAccion = $descripcionAccion;

        return $this;
    }

    #endregion 

    #region Metodos

    public function jsonSerialize() 
    {
        return 
        [
            'id_log' => $this->getIdLog(),
            'id_usuario' => $this->getIdUsuario(),
            'nombre_usuario' => $this->getNombreUsuario(),
            'fecha' => $this->getFecha(),
            'hora' => $this->getHora(),
            'id_rol' => $this->getIdRol(),
            'descripcion_rol' => $this->getDescripcionRol(),
            'id_accion' => $this->getIdAccion(),
            'descripcion_accion' => $this->getDescripcionAccion()
        ];
    }

    #endregion

 
}


?>