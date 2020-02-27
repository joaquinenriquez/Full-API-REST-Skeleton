<?php

require_once "../src/app/model/Usuario.php";
require_once "../src/app/modelAPI/AutentificadorJWT.php";
require_once "../src/app/modelAPI/TokenSeguridad.php";
require_once "../src/app/enum/EstadosUsuarios.php";
require_once "../src/app/Querys/QuerysSQL_Usuarios.php";


class UsuarioDAO
{

    public static function Login($nombreUsuario, $password)
    {
        $auxReturn = false;
        $ubicacionParaMensaje = "UsuarioDAO->Login";
        $rows = [];
        $unUsuario = new Usuario();

        try
        {
            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
            $auxQuerySQL = "SELECT id_usuario, nombre_usuario, password, nombre, apellido, id_rol, estado FROM usuarios WHERE estado != 0 AND nombre_usuario = :nombre_usuario";

            $querySQLPreparada = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);

            $querySQLPreparada->bindValue(':nombre_usuario', $nombreUsuario, PDO::PARAM_STR);

            $estadoQuery = $querySQLPreparada->execute();

            if ($estadoQuery == true) {

                if ($querySQLPreparada->rowCount() > 0) {
                    $rows = $querySQLPreparada->fetch();

                    if (password_verify($password, $rows["password"])) {
                        $unUsuario->setNombreUsuario($rows["nombre_usuario"]);
                        $unUsuario->setIdUsuario($rows["id_usuario"]);
                        $unUsuario->setNombre($rows["nombre"]);
                        $unUsuario->setApellido($rows["apellido"]);
                        $unUsuario->setRol($rows["id_rol"]);
                        $unUsuario->setEstado($rows["estado"]);

                        $datosToken = array(
                            "id_usuario" => $unUsuario->getIdUsuario(),
                            "id_rol" => $unUsuario->getRol(),
                        );

                        $token = TokenSeguridad::CrearUno($datosToken);

                        $mensaje = "Login correcto (" . strtoupper(Roles::TraerRolPorId($unUsuario->getRol())) . "). TOKEN:" . $token;

                        $auxReturn = new Resultado(false, $mensaje, EstadosError::OK);
                    } else {
                        $auxReturn = new Resultado(false, "Password Incorrecto", EstadosError::ERROR_DE_AUTORIZACION);
                    }

                } else {
                    $auxReturn = new Resultado(false, "No existe un usuario con ese nombre o se encuentra deshabilitado", EstadosError::ERROR_DE_AUTORIZACION);
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
                id_rol = :id_rol,
                estado = 1";

            $nuevo->HashPassword();

            $querySQLPreparada = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);
            $querySQLPreparada->bindValue(":nombre_usuario", $nuevo->getNombreUsuario());
            $querySQLPreparada->bindValue(":password", $nuevo->getPassword());
            $querySQLPreparada->bindValue(":nombre", $nuevo->getNombre());
            $querySQLPreparada->bindValue(":apellido", $nuevo->getApellido());
            $querySQLPreparada->bindValue(":id_rol", $nuevo->getSector());

            $auxReturn = $querySQLPreparada->execute();

            if ($auxReturn == true) {
                $mensaje = "El usuario se creo correctamente: " . sprintf("%s (Rol: %s)", $nuevo->getNombreUsuario(), strtoupper(Roles::TraerRolPorId($nuevo->getRol())));
                $auxReturn = new Resultado(false, $mensaje, EstadosError::RECURSO_CREADO);
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

    public static function VerificarSiExisteUsuario($nombreDeUsuario)
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
                    $auxReturn = new Resultado(false, "Ya existe un usuario con ese nombre " . $rows["id_usuario"], EstadosError::OK);
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

    public static function TraerUno($idUsuario)
    {
        $auxReturn = new Resultado(false, null, EstadosError::OK);
        $ubicacionParaMensaje = "Usuario->TraerUno";

        try
        {
            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
            $auxQuerySQL = "SELECT id_usuario, nombre_usuario, nombre, apellido, id_rol, estado FROM usuarios WHERE estado != 0 AND id_usuario = :id_usuario LIMIT 1";

            $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);

            $querySQL->bindValue(':id_usuario', $idUsuario, PDO::PARAM_INT);

            $estadoQuery = $querySQL->execute();

            if ($estadoQuery == false) {
                $auxReturn = new Resultado(true, "Ocurrio un error al ejecutar la consulta. ($ubicacionParaMensaje)", EstadosError::ERROR_DB);
            } else {
                if ($querySQL->rowCount() > 0) {
                    $row = $querySQL->fetch();
                    $unUsuario = new Usuario();

                    $unUsuario->setIdUsuario($row["id_usuario"]);
                    $unUsuario->setNombreUsuario($row["nombre_usuario"]);
                    $unUsuario->setNombre($row["nombre"]);
                    $unUsuario->setApellido($row["apellido"]);
                    $unUsuario->setRol($row["id_rol"]);
                    $unUsuario->setEstado($row["estado"]);

                    $auxReturn = new Resultado(false, $unUsuario, EstadosError::OK);
                } else {
                    $auxReturn = new Resultado(false, "No existe ningun usuario con el ID: " . $idUsuario, EstadosError::SIN_RESULTADOS);
                }

            }

        } catch (PDOException $unErrorDB) {
            $mensaje = "Ocurrio un error con la conexion con la base de datos ($ubicacionParaMensaje)" . $unErrorDB->getMessage();
            $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_DB);
        } catch (Exception $unError) {
            $mensaje = "Ocurrio un error al intentar traer el dato ($ubicacionParaMensaje)" . $unError->getMessage();
            $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_DB);
        }

        return $auxReturn;
    }

    public static function TraerTodos()
    {
        $auxReturn = new Resultado(false, null, EstadosError::OK);
        $ubicacionParaMensaje = "Usuario->TraerTodos";
        $listadoUsuarios = [];

        try
        {
            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
            $auxQuerySQL = "SELECT id_usuario, nombre_usuario, nombre, apellido, id_rol, estado FROM usuarios WHERE estado != 0";
            $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);
            $estadoQuery = $querySQL->execute();

            if ($estadoQuery == false) {

                $auxReturn = new Resultado(true, "Ocurrio un error al ejecutar la consulta. ($ubicacionParaMensaje)", EstadosError::ERROR_DB);

            } else if (($querySQL->rowCount() > 0) == false) {

                $auxReturn = new Resultado(false, "No existen usuarios creados!", EstadosError::SIN_RESULTADOS);

            } else {

                $rows[] = $querySQL->fetchAll();
                foreach ($rows[0] as $unaRow) {
                    $unUsuario = new Usuario();

                    $unUsuario->setIdUsuario($unaRow["id_usuario"]);
                    $unUsuario->setNombreUsuario($unaRow["nombre_usuario"]);
                    $unUsuario->setNombre($unaRow["nombre"]);
                    $unUsuario->setApellido($unaRow["apellido"]);
                    $unUsuario->setRol($unaRow["id_rol"]);
                    $unUsuario->setEstado($unaRow["estado"]);

                    array_push($listadoUsuarios, $unUsuario);
                }

                $auxReturn = new Resultado(false, $listadoUsuarios, EstadosError::OK);
            }

        } catch (PDOException $unErrorDB) {

            $mensaje = "Ocurrio un error con la conexion con la base de datos ($ubicacionParaMensaje)" . $unErrorDB->getMessage();
            $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_DB);

        } catch (Exception $unError) {

            $mensaje = "Ocurrio un error al intentar traer el dato ($ubicacionParaMensaje)" . $unError->getMessage();
            $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_DB);
        }

        return $auxReturn;
    }

    public static function CambiarEstado($idUsuario, $nuevoEstado)
    {
        $ubicacionParaMensaje = "UsuarioDAO->CambiarEstado";
        $auxReturn = new Resultado(false, null, EstadosError::OK);

        try
        {
            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
            $auxQuerySQL = QuerysSQL_Usuarios::CambiarEstado;
            $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);

            $querySQL->bindValue(":id_usuario", $idUsuario);
            $querySQL->bindValue(":estado", $nuevoEstado);

            $estadoQuery = $querySQL->execute();
            if ($estadoQuery == false)
            {
                $auxReturn = new Resultado(true, "Ocurrio un error al intentar actualizar el estado. ($ubicacionParaMensaje)", EstadosError::ERROR_DB);

            } else if ($querySQL->rowCount() <= 0)
            {
                $auxReturn = new Resultado(false, "No existen usuarios con ese ID: " , $idUsuario, EstadosError::SIN_RESULTADOS);
            } else 
            {
                $mensaje = "Se actualizo correctamente el estado del usuario a " . EstadosUsuarios::TraerEstadoPorId($nuevoEstado);
                $auxReturn = new Resultado(false, $mensaje, EstadosError::OK);
            }

        } catch (PDOException $unErrorDB) {

            $mensaje = "Ocurrio un error con la conexion con la base de datos ($ubicacionParaMensaje)" . $unErrorDB->getMessage();
            $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_DB);

        } catch (Exception $unError) {

            $mensaje = "Ocurrio un error al intentar traer el dato ($ubicacionParaMensaje)" . $unError->getMessage();
            $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_DB);

        }

        return $auxReturn;

    }

}
