<?php

require_once "../src/app/model/Usuario.php";

class UsuarioDAO
{
    public static function TraerUsuarioActual()
    {
        return "asd";
    }

    public static function Login()
    {

    }

    public static function CargarUno(Usuario $nuevo)
    {

        $ubicacionParaMensaje = "UsuarioDAO->CargarUno";
        $auxReturn = false;

        try
        {
            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();

            $auxQuerySQL =
                "INSERT INTO usuarios SET
                nombre_usuario = :nombre_usuario,
                password = :password,
                nombre = :nombre,
                apellido = :apellido,
                id_sector = :id_sector,
                estado = 1";

            $querySQLPreparada = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);
            $querySQLPreparada->bindValue(":nombre_usuario", $nuevo->getNombreUsuario());
            $querySQLPreparada->bindValue(":password", $nuevo->getPassword());
            $querySQLPreparada->bindValue(":nombre", $nuevo->getNombre());
            $querySQLPreparada->bindValue(":apellido", $nuevo->getApellido());
            $querySQLPreparada->bindValue(":id_sector", $nuevo->getSector());

            $auxReturn = $querySQLPreparada->execute();

            if ($auxReturn == true) {
                $auxReturn = new Resultado(false, "El usuario se creo correctamente", EstadosError::OK);
            } else {
                $auxReturn = new Resultado(true, "Ocurrio un error al intentar de guardar los datos.($ubicacionParaMensaje)", EstadosError::ERROR_GUARDAR);
            }

        } catch (PDOException $unErrorDB) {
            $auxReturn = new Resultado(true, "Ocurrio un error con la conexion con la base de datos ($ubicacionParaMensaje)." . $unErrorDB->getMessage(), EstadosError::ERROR_DB);
        } catch (Exception $unError) {
            $auxReturn = new Resultado(true, "Ocurrio un error al intentar guardar ($ubicacionParaMensaje)." . $unError->getMessage(), EstadosError::ERROR_GENERAL);
        }

        return $auxReturn;

    }

    public static function VerificarSiExisteUsuario(string $nombreDeUsuario)
    {
        $auxReturn = false;
        $ubicacionParaMensaje = "UsuarioDAO->VerificarSiExiste";
        $rows = [];

        try
        {
            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
            $auxQuerySQL = "SELECT id_usuario FROM usuarios WHERE estado != 0 AND nombre_usuario = :nombre_usuario";
            
            $querySQLPreparada = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);

            $querySQLPreparada->bindValue(':nombre_usuario', $nombreDeUsuario, PDO::PARAM_STR);

            $estadoQuery = $querySQLPreparada->execute();

            if ($estadoQuery == true) {

                if ($querySQLPreparada->rowCount() > 0) {
                    $rows = $querySQLPreparada->fetch();
                    $auxReturn = new Resultado(false,"Ya existe un usuario con ese nombre " . $rows["id_usuario"], EstadosError::OK);
                } else {
                    $auxReturn = new Resultado(false, "No existe un usuario con ese nombre", EstadosError::SIN_RESULTADOS);
                }
            } else {
                $auxReturn = new Resultado(true, "Ocurrio un error al ejecutar la query. ($ubicacionParaMensaje)", EstadosError::ERROR_GENERAL);
            }

        } catch (PDOException $unErrorDB) {
            $auxReturn = new Resultado(true, "Ocurrio un error con la conexion con la base de datos ($ubicacionParaMensaje)" . $unErrorDB->getMessage(), EstadosError::ERROR_DB);
        } catch (Exception $unError) {
            $auxReturn = new Resultado(true, "Ocurrio un error al intentar traer el dato ($ubicacionParaMensaje)" . $unError->getMessage(), EstadosError::ERROR_GENERAL);
        }

        return $auxReturn;

    }

}
