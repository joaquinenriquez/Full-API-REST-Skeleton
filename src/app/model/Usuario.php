<?php

class Usuario implements jsonSerializable
{
/* #region  Atributos */
    private $idUsuario;
    private $nombreUsuario;
    private $password;
    private $nombre;
    private $apellido;
    private $rol;
    private $estado;
/* #endregion */

/* #region  Propiedades */

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
        $this->nombreUsuario = trim($nombreUsuario);

        return $this;
    }

    /**
     * Get the value of password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @return  self
     */
    public function setPassword($password)
    {
        $this->password = trim($password);

        return $this;
    }

    /**
     * Get the value of nombre
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set the value of nombre
     *
     * @return  self
     */
    public function setNombre($nombre)
    {
        $this->nombre = trim($nombre);

        return $this;
    }

    /**
     * Get the value of apellido
     */
    public function getApellido()
    {
        return $this->apellido;
    }

    /**
     * Set the value of apellido
     *
     * @return  self
     */
    public function setApellido($apellido)
    {
        $this->apellido = trim($apellido);

        return $this;
    }

    /**
     * Get the value of rol
     */
    public function getRol()
    {
        return $this->rol;
    }

    /**
     * Set the value of rol
     *
     * @return  self
     */
    public function setRol($rol)
    {
        $this->rol = trim($rol);

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

    public function HashPassword()
    {
        $passwordHasheada = password_hash($this->getPassword(), PASSWORD_BCRYPT);
        $this->setPassword($passwordHasheada);
    }

    /**
     * Get the value of id_usuario
     */
    public function getIdUsuario()
    {
        return $this->idUsuario;
    }

    /**
     * Set the value of id_usuario
     *
     * @return  self
     */
    public function setIdUsuario($idUsuario)
    {
        $this->idUsuario = $idUsuario;

        return $this;
    }

/* #endregion */

    public function jsonSerialize()
    {
        return
            [
            'id_usuario' => $this->getIdUsuario(),
            'nombre_usuario' => $this->getNombreUsuario(),
            'nombre' => $this->getNombre(),
            'apellido' => $this->getApellido(),
            'rol' => $this->getRol(),
            'estado' => $this->getEstado(),

        ];
    }

}
