<?php

require_once '../src/app/enum/responseJSONEstados.php';

class ResponseJSON implements JsonSerializable
{

/* #region  Constructores */
public function __construct($status, $mensaje) 
{
    $this->setStatus($status);
    $this->setMensaje($mensaje);
}
/* #endregion */

/* #region  Atributos */

    private $status;
    private $mensaje;

/* #endregion */

/* #region  Getters y Setters */
    /**
     * Get the value of mensaje
     */ 
    public function getMensaje()
    {
        return $this->mensaje;
    }

    /**
     * Set the value of mensaje
     *
     * @return  self
     */ 
    public function setMensaje($mensaje)
    {
        $this->mensaje = $mensaje;

        return $this;
    }

    /**
     * Get the value of estado
     */ 
    public function getStatus()
    {
        return $this->estado;
    }

    /**
     * Set the value of estado
     *
     * @return  self
     */ 
    public function setStatus($status)
    {
        $this->estado = $status;

        return $this;
    }
/* #endregion */

public function jsonSerialize() 
{   
    return 
    [
        'estado' => $this->getStatus(),
        'mensaje' => $this->getMensaje()
    ];

}


}





?>