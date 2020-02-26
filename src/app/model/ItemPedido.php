<?php

class ItemPedido implements jsonSerializable
{

#region Atributos

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

    private $descripcionArticulo;
    private $usuarioCreador;
    private $usuarioAsignado;
    private $codigoAmigable;
    private $idSector;
    private $descripcionSector;
    private $nombreCliente;

#endregion

#region Constructores

    public function __construct()
    {
        $this->setFechaHoraFin(null);
        $this->setFechaHoraFin(null);
        $this->setIdUsuarioOwner(null);
        $this->setIdUsuarioAsignado(null);
        $this->setTiempoEstimado(null);
        $this->setEstado(1);
    }

#endregion

#region Propiedades
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

        /**
     * Get the value of descripcionArticulo
     */ 
    public function getDescripcionArticulo()
    {
        return $this->descripcionArticulo;
    }

    /**
     * Set the value of descripcionArticulo
     *
     * @return  self
     */ 
    public function setDescripcionArticulo($descripcionArticulo)
    {
        $this->descripcionArticulo = $descripcionArticulo;

        return $this;
    }

    /**
     * Get the value of usuarioCreador
     */ 
    public function getUsuarioCreador()
    {
        return $this->usuarioCreador;
    }

    /**
     * Set the value of usuarioCreador
     *
     * @return  self
     */ 
    public function setUsuarioCreador($usuarioCreador)
    {
        $this->usuarioCreador = $usuarioCreador;

        return $this;
    }

    /**
     * Get the value of usuarioAsignado
     */ 
    public function getUsuarioAsignado()
    {
        return $this->usuarioAsignado;
    }

    /**
     * Set the value of usuarioAsignado
     *
     * @return  self
     */ 
    public function setUsuarioAsignado($usuarioAsignado)
    {
        $this->usuarioAsignado = $usuarioAsignado;

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

#endregion

#region Metodos

public function jsonSerialize()
{
    return
        [
        'id_item_pedido' => $this->getIdItemPedido(),
        'id_pedido' => $this->getIdPedido(),
        'codigo_amigable' => $this->getCodigoAmigable(),
        'nombre_cliente' => $this->getNombreCliente(),
        'fecha_Hora_Inicio' => $this->getFechaHoraInicio(),
        'fecha_Hora_Fin' => $this->getFechaHoraFin(),
        'id_articulo' => $this->getIdArticulo(),
        'descripcion_articulo' => $this->getDescripcionArticulo(),
        'cantidad' => $this->getCantidad(),
        'id_sector' => $this->getIdSector(),
        'descripcion_sector' => $this->getDescripcionSector(),
        'tiempo_estimado' => $this->getTiempoEstimado(),
        'id_usuario_creador' => $this->getIdUsuarioOwner(),
        'nombre_usuario_creador' => $this->getUsuarioCreador(),
        'id_usuario_asignado' => $this->getIdUsuarioAsignado(),
        'nombre_usuario_asignado' => $this->getUsuarioAsignado(),
        'estado' => $this->getEstado(),
    ];
}


#endregion



}

?>