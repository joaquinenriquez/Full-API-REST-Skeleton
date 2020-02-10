<?php

require_once '../src/app/enum/responseJSONEstados.php';

class ResponseJSON implements JsonSerializable
{

/* #region  Constructores */
public function __construct($estado, $mensaje) 
{
    $this->setEstado($estado);
    $this->setMensaje($mensaje);
}
/* #endregion */

/* #region  Atributos */

    private $estado;
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
        'estado' => $this->getEstado(),
        'mensaje' => $this->getMensaje()
    ];

}


}





?>