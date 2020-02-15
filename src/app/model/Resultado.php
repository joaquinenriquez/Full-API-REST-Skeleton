<?php

class Resultado implements jsonSerializable 
{
    private $isError;
    private $mensaje;
    private $status;

    /**
     * Get the value of isError
     */ 

    public function __construct($isError, $mensaje, $status) 
    {
        $this->isError = $isError;
        $this->mensaje = $mensaje;
        $this->status = $status;
    }

    public function getIsError()
    {
        return $this->isError;
    }

    /**
     * Set the value of isError
     *
     * @return  self
     */ 
    public function setIsError($isError)
    {
        $this->isError = $isError;

        return $this;
    }

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
     * Get the value of status
     */ 
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set the value of status
     *
     * @return  self
     */ 
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    public function toJSON() {

    }

    public function jsonSerialize() 
    {
        return [
            'status' => $this->getStatus(),
            'mensaje' => $this->getMensaje()
        ];
    }

}


?>