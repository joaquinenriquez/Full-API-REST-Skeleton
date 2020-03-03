<?php

class CabeceraPedido
{

#region  Atributos

    private $idPedido;
    private $idUsuario;
    private $nombreCliente;
    private $estado;
    private $codigoAmigable;
    private $idMesa;
    private $foto;
    private $fechaInicio;
    private $fechaFin;
    private $importe;

    private $contesto;
    private $calificacion_mesa;
    private $calificacion_mozo;
    private $calificacion_cocinero;
    private $calificacion_restaurante;
    private $comentarios;
    
#endregion

#region constructores

public function __construct()
{

}

#endregion 

#region  Propiedades

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
     * Get the value of mesa
     */
    public function getMesa()
    {
        return $this->mesa;
    }

    /**
     * Set the value of mesa
     *
     * @return  self
     */
    public function setMesa($mesa)
    {
        $this->mesa = $mesa;

        return $this;
    }


    /**
     * Get the value of foto
     */ 
    public function getFoto()
    {
        return $this->foto;
    }

    /**
     * Set the value of foto
     *
     * @return  self
     */ 
    public function setFoto($foto)
    {
        $this->foto = $foto;

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
     * Get the value of fechaInicio
     */ 
    public function getFechaInicio()
    {
        return $this->fechaInicio;
    }

    /**
     * Set the value of fechaInicio
     *
     * @return  self
     */ 
    public function setFechaInicio($fechaInicio)
    {
        $this->fechaInicio = $fechaInicio;

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
     * Get the value of fechaFin
     */ 
    public function getFechaFin()
    {
        return $this->fechaFin;
    }

    /**
     * Set the value of fechaFin
     *
     * @return  self
     */ 
    public function setFechaFin($fechaFin)
    {
        $this->fechaFin = $fechaFin;

        return $this;
    }

    
    /**
     * Get the value of contesto
     */ 
    public function getContesto()
    {
        return $this->contesto;
    }

    /**
     * Set the value of contesto
     *
     * @return  self
     */ 
    public function setContesto($contesto)
    {
        $this->contesto = $contesto;

        return $this;
    }

        /**
     * Get the value of calificacion_mesa
     */ 
    public function getCalificacion_mesa()
    {
        return $this->calificacion_mesa;
    }

    /**
     * Set the value of calificacion_mesa
     *
     * @return  self
     */ 
    public function setCalificacion_mesa($calificacion_mesa)
    {
        $this->calificacion_mesa = $calificacion_mesa;

        return $this;
    }

    /**
     * Get the value of calificacion_mozo
     */ 
    public function getCalificacion_mozo()
    {
        return $this->calificacion_mozo;
    }

    /**
     * Set the value of calificacion_mozo
     *
     * @return  self
     */ 
    public function setCalificacion_mozo($calificacion_mozo)
    {
        $this->calificacion_mozo = $calificacion_mozo;

        return $this;
    }

    /**
     * Get the value of calificacion_cocinero
     */ 
    public function getCalificacion_cocinero()
    {
        return $this->calificacion_cocinero;
    }

    /**
     * Set the value of calificacion_cocinero
     *
     * @return  self
     */ 
    public function setCalificacion_cocinero($calificacion_cocinero)
    {
        $this->calificacion_cocinero = $calificacion_cocinero;

        return $this;
    }

    /**
     * Get the value of calificacion_restaurante
     */ 
    public function getCalificacion_restaurante()
    {
        return $this->calificacion_restaurante;
    }

    /**
     * Set the value of calificacion_restaurante
     *
     * @return  self
     */ 
    public function setCalificacion_restaurante($calificacion_restaurante)
    {
        $this->calificacion_restaurante = $calificacion_restaurante;

        return $this;
    }

    /**
     * Get the value of comentarios
     */ 
    public function getComentarios()
    {
        return $this->comentarios;
    }

    /**
     * Set the value of comentarios
     *
     * @return  self
     */ 
    public function setComentarios($comentarios)
    {
        $this->comentarios = $comentarios;

        return $this;
    }


#endregion



    /**
     * Get the value of importe
     */ 
    public function getImporte()
    {
        return $this->importe;
    }

    /**
     * Set the value of importe
     *
     * @return  self
     */ 
    public function setImporte($importe)
    {
        $this->importe = $importe;

        return $this;
    }
}
