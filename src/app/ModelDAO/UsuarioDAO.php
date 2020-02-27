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
        $auxReturn = new Resultado(false, null, EstadosError::OK);
        $ubicacionParaMensaje = "UsuarioDAO->Login";
        $rows = [];
        $unUsuario = new Usuario();

        try
        {
            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
            $auxQuerySQL = QuerysSQL_Usuarios::Login;
            $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);
            $querySQL->bindValue(':nombre_usuario', $nombreUsuario, PDO::PARAM_STR);
            $estadoQuery = $querySQL->execute();

            // Si ocurrio un error al ejecutar la query
            if ($estadoQuery == false) {
                $auxReturn = new Resultado(true, "Ocurrio un error al ejecutar la query. ($ubicacionParaMensaje)", EstadosError::ERROR_DB);

            } 
            // Si no se encontro un usuario habilitado con ese nombre de usuario
            else if ($querySQL->rowCount() <= 0)
            {
                $auxReturn = new Resultado(false, "No existe un usuario con ese nombre o se encuentra deshabilitado", EstadosError::ERROR_DE_AUTORIZACION);

            } 
            // Si la consulta devolvio algun registro verificamos que el usuario se encuentre ACTIVO
            else 
            {
                $rows = $querySQL->fetch();
                $estadoUsuario = $rows["estado"];

                // Si se encuentra SUSPENDIDO
                if ($estadoUsuario  == EstadosUsuarios::SUSPENDIDO[0])
                {
                    $mensaje = "El usuario " . $rows["nombre_usuario"] . " se encuentra suspendido";
                    $auxReturn = new Resultado(false, $mensaje, EstadosError::ERROR_DE_AUTORIZACION);

                    // Si no se encuentra ACTIVO
                } else if ($estadoUsuario != EstadosUsuarios::ACTIVO[0]) {
                    $auxReturn = new Resultado(false, "El usuario no se encuentra activo", EstadosError::ERROR_DE_AUTORIZACION);

                } else 
                {
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
                }
            }

        } catch (PDOException $unErrorDB) {
            $auxReturn = new Resultado(true, "Ocurrio un error con la conexion con la base de datos ($ubicacionParaMensaje)" . $unErrorDB->getMessage(), EstadosError::ERROR_DB);
        } catch (Exception $unError) {
            $auxReturn = new Resultado(true, "Ocurrio un error al intentar traer el dato ($ubicacionParaMensaje)" . $unError->getMessage(), EstadosError::ERROR_DB);
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
            $querySQLPreparada->bindValue(":id_rol", $nuevo->getRol());

            $auxReturn = $querySQLPreparada->execute();

            if ($auxReturn == true) {
                $mensaje = "El usuario se creo correctamente! " . sprintf("%s (Rol: %s) ID: %s", $nuevo->getNombreUsuario(), strtoupper(Roles::TraerRolPorId($nuevo->getRol())), $objetoAccesoDatos->RetornarUltimoIdInsertado());
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

    public static function TraerUsuarioPorNombreDeUsuario($nombreDeUsuario)
    {
        $auxReturn = new Resultado(false, null, EstadosError::OK);
        $ubicacionParaMensaje = "UsuarioDAO->TraerUsuarioPorNombreDeUsuario";
        $row = [];

        try
        {
            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
            $auxQuerySQL = QuerysSQL_Usuarios::TraerUsuarioPorNombreUsuario;
            $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);
            $querySQL->bindValue(':nombre_usuario', $nombreDeUsuario, PDO::PARAM_STR);
            $estadoQuery = $querySQL->execute();

            if ($estadoQuery == false) 
            {
                $auxReturn = new Resultado(true, "Ocurrio un error al ejecutar la query. ($ubicacionParaMensaje)", EstadosError::ERROR_DB);

            } else if ($querySQL->rowCount() <= 0)
            {
                $auxReturn = new Resultado(false, "No existe un usuario con nombre $nombreDeUsuario", EstadosError::SIN_RESULTADOS);

            } else 
            {
                $row = $querySQL->fetch();
                $unUsuario = new Usuario();

                $unUsuario->setIdUsuario($row["id_usuario"]);
                $unUsuario->setNombreUsuario($row["nombre_usuario"]);
                $unUsuario->setNombre($row["nombre"]);
                $unUsuario->setApellido($row["apellido"]);
                $unUsuario->setRol($row["id_rol"]);
                $unUsuario->setEstado($row["estado"]);

                $auxReturn = new Resultado(false, $unUsuario, EstadosError::OK);
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
            if ($estadoQuery == false) {
                $auxReturn = new Resultado(true, "Ocurrio un error al intentar actualizar el estado. ($ubicacionParaMensaje)", EstadosError::ERROR_DB);

            } else if ($querySQL->rowCount() <= 0) {
                $auxReturn = new Resultado(false, "No existen usuarios con ese ID: ", $idUsuario, EstadosError::SIN_RESULTADOS);
            } else {
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
