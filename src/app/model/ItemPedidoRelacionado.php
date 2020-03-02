<?php

require_once "../src/app/model/ItemPedido.php";

class ItemPedidoRelacionado extends ItemPedido
{

    #region Atributos

    private $descripcionArticulo;
    private $importeArticulo;
    private $usuarioCreador;
    private $usuarioAsignado;
    private $codigoAmigable;
    private $idSector;
    private $descripcionSector;
    private $nombreCliente;
    private $idMesa;
    private $codigoAmigableMesa;
    private $estadoMesa;


    #endregion

    #region Propiedades

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
     * Get the value of estadoMesa
     */
    public function getEstadoMesa()
    {
        return $this->estadoMesa;
    }

    /**
     * Set the value of estadoMesa
     *
     * @return  self
     */
    public function setEstadoMesa($estadoMesa)
    {
        $this->estadoMesa = $estadoMesa;

        return $this;
    }

        /**
     * Get the value of codigoAmigableMesa
     */ 
    public function getCodigoAmigableMesa()
    {
        return $this->codigoAmigableMesa;
    }

    /**
     * Set the value of codigoAmigableMesa
     *
     * @return  self
     */ 
    public function setCodigoAmigableMesa($codigoAmigableMesa)
    {
        $this->codigoAmigableMesa = $codigoAmigableMesa;

        return $this;
    }

        /**
     * Get the value of importeArticulo
     */ 
    public function getImporteArticulo()
    {
        return $this->importeArticulo;
    }

    /**
     * Set the value of importeArticulo
     *
     * @return  self
     */ 
    public function setImporteArticulo($importeArticulo)
    {
        $this->importeArticulo = $importeArticulo;

        return $this;
    }

    #endregion

    public function jsonSerialize()
    {
        return
            [
            'id_item_pedido' => $this->getIdItemPedido(),
            'fecha_hora_creacion' => $this->getFechaHoraCreacion(),
            'id_pedido' => $this->getIdPedido(),
            'codigo_amigable_pedido' => $this->getCodigoAmigable(),
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
            'estado_item_pedido' => $this->getEstado(),
            'id_mesa' => $this->getIdMesa(),
            'codigo_amigable_mesa' => $this->getCodigoAmigableMesa(),
            'estado_mesa' => $this->getEstadoMesa(),
        ];
    }







}
