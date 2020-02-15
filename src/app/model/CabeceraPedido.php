<?php

require_once '../src/app/modelDAO/UsuarioDAO.php';

class CabeceraPedido
{
/* #region  Atributos */

    private $idPedido;
    private $idUsuario;
    private $nombreCliente;
    private $estado;
    private $codigoAmigable;
    private $idMesa;
    private $foto;
    private $fechaInicio;
    private $fechaFin;

    
/* #endregion */

public function __construct()
{
    date_default_timezone_set('America/Argentina/Buenos_Aires');

    $this->setCodigoAmigable(CabeceraPedido::GenerarCodigo());
    $this->fechaInicio = date('d/m/y H:i');
    $this->estado = 1;
    $this->setIdUsuario(UsuarioDAO::TraerUsuarioActual());
}


/* #region  Propiedades */

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

    


/* #endregion */

public static function GenerarCodigo() {
    
    $caracteres = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $cantidadCaracteres = strlen($caracteres) - 1; 

    // Buscamos un numero aleatorio de entre 0 y la cantidad de caracteres
    // Ese numero lo utilizamos como comienzo del substring de largo 1
    $caracter1 = substr($caracteres, rand(0, $cantidadCaracteres), 1);
    $caracter2 = substr($caracteres, rand(0, $cantidadCaracteres), 1);
    $caracter3 = substr($caracteres, rand(0, $cantidadCaracteres), 1);
    $caracter4 = substr($caracteres, rand(0, $cantidadCaracteres), 1);
    $caracter5 = substr($caracteres, rand(0, $cantidadCaracteres), 1);

    $codigo = $caracter1 . $caracter2 . $caracter3 . $caracter4 . $caracter5;

    return $codigo;

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


}
