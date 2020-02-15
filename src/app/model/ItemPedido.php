<?php

class ItemPedido implements jsonSerializable
{
    
    private $idItemPedido;
    private $idPedido;
    private $fechaHoraInicio;
    private $fechaHoraFin;
    private $idArticulo;
    private $cantidad;
    private $idUsuarioOwner;
    private $idUsuarioAsignado;
    private $tiempoEstimado;
    private $estado;

    
    public function __construct() {
        $this->setFechaHoraFin(null);
        $this->setFechaHoraFin(null);
        $this->setIdUsuarioOwner(null);
        $this->setIdUsuarioAsignado(null);
        $this->setTiempoEstimado(null);
        $this->setEstado(1);
    }


    /**
     * Get the value of idItemPedido
     */ 
    public function getIdItemPedido()
    {
        return $this->idItemPedido;
    }

    /**
     * Set the value of idItemPedido
     *
     * @return  self
     */ 
    public function setIdItemPedido($idItemPedido)
    {
        $this->idItemPedido = $idItemPedido;

        return $this;
    }

    /**
     * Get the value of idPedido
     */ 
    public function getIdPedido()
    {
        return $this->idPedido;
    }

    /**
     * Set the value of idPedido
     *
     * @return  self
     */ 
    public function setIdPedido($idPedido)
    {
        $this->idPedido = $idPedido;

        return $this;
    }

    /**
     * Get the value of fechaHoraInicio
     */ 
    public function getFechaHoraInicio()
    {
        return $this->fechaHoraInicio;
    }

    /**
     * Set the value of fechaHoraInicio
     *
     * @return  self
     */ 
    public function setFechaHoraInicio($fechaHoraInicio)
    {
        $this->fechaHoraInicio = $fechaHoraInicio;

        return $this;
    }

    /**
     * Get the value of fechaHoraFin
     */ 
    public function getFechaHoraFin()
    {
        return $this->fechaHoraFin;
    }

    /**
     * Set the value of fechaHoraFin
     *
     * @return  self
     */ 
    public function setFechaHoraFin($fechaHoraFin)
    {
        $this->fechaHoraFin = $fechaHoraFin;

        return $this;
    }


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
     * Get the value of idUsuarioOwner
     */ 
    public function getIdUsuarioOwner()
    {
        return $this->idUsuarioOwner;
    }

    /**
     * Set the value of idUsuarioOwner
     *
     * @return  self
     */ 
    public function setIdUsuarioOwner($idUsuarioOwner)
    {
        $this->idUsuarioOwner = $idUsuarioOwner;

        return $this;
    }

    /**
     * Get the value of idUsuarioAsignado
     */ 
    public function getIdUsuarioAsignado()
    {
        return $this->idUsuarioAsignado;
    }

    /**
     * Set the value of idUsuarioAsignado
     *
     * @return  self
     */ 
    public function setIdUsuarioAsignado($idUsuarioAsignado)
    {
        $this->idUsuarioAsignado = $idUsuarioAsignado;

        return $this;
    }

    /**
     * Get the value of tiempoEstimado
     */ 
    public function getTiempoEstimado()
    {
        return $this->tiempoEstimado;
    }

    /**
     * Set the value of tiempoEstimado
     *
     * @return  self
     */ 
    public function setTiempoEstimado($tiempoEstimado)
    {
        $this->tiempoEstimado = $tiempoEstimado;

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


    public function jsonSerialize() {
        
    }


    /**
     * Get the value of cantidad
     */ 
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set the value of cantidad
     *
     * @return  self
     */ 
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }
}

?>