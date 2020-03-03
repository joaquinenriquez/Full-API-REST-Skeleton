<?php

require_once '../src/app/model/Log.php';

class LogDAO 
{


    public static function GuardarRegistro($idUsuario, $idSector, $accion) 
    {
        $auxReturn = new Resultado(false, null, EstadosError::OK);
        $ubicacionParaMensaje = "LogDAO->GuardarRegistro";

        try
        {
            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
            $auxQuerySQL = QuerysSQL_Logs::CargarUna;
            $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);

            date_default_timezone_set('America/Argentina/Buenos_Aires');
                        
            $querySQL->bindValue(":id_usuario", $idUsuario);
            $querySQL->bindValue(":id_sector", $idSector);
            $querySQL->bindValue(":id_accion", $accion[0]);
            $querySQL->bindValue(":descripcion_accion", $accion[1]);
            $querySQL->bindValue(":fecha_hora", date('Y-m-d H:i:s'));

            $estadoQuery = $querySQL->execute();

            if ($estadoQuery == false) {
                $auxReturn = new Resultado(true, "Ocurrio un error al intentar ejecutar la consulta ($ubicacionParaMensaje)", EstadosError::ERROR_DB);
            } else if ($querySQL->rowCount() <= 0) {
                $auxReturn = new Resultado(true, "Ocurrio un error al intentar ejecutar la consulta ($ubicacionParaMensaje)", EstadosError::ERROR_DB);
            } else {
                $mensaje = sprintf("Se guardo correctamente el registro (ID: %s)", $objetoAccesoDatos->RetornarUltimoIdInsertado());
                $auxReturn = new Resultado(false, $mensaje, EstadosError::RECURSO_CREADO);
            }

        } catch (PDOException $unErrorDB) {
            $mensaje = "Ocurrio un error con la conexion con la base de datos ($ubicacionParaMensaje)." . $unErrorDB->getMessage();
            $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_DB);
        } catch (Exception $unError) {
            $mensaje = "Ocurrio un error al intentar guardar ($ubicacionParaMensaje)." . $unError->getMessage();
            $auxReturn = new Resultado(true, $mensaje, EstadosError::ERROR_DB);
        }

        return $auxReturn;
    }

    public static function TraerIniciosDeSesion($fechaHoraDesde, $fechaHoraHasta)
    {
        $auxReturn = new Resultado(false, null, EstadosError::OK);
        $ubicacionParaMensaje = "LogDAO->TraerIniciosDeSesion";
        $listadoDeRegistrosLog = [];

        try
        {
            $objetoAccesoDatos = AccesoDatos::dameUnObjetoAcceso();
            $auxQuerySQL = QuerysSQL_Logs::TraerIniciosDeSesion;
            $querySQL = $objetoAccesoDatos->RetornarConsulta($auxQuerySQL);
            
            $querySQL->bindValue(":fecha_hora_desde", $fechaHoraDesde);
            $querySQL->bindValue(":fecha_hora_hasta", $fechaHoraHasta);

            $estadoQuery = $querySQL->execute();

            if ($estadoQuery == false) {
                $auxReturn = new Resultado(true, "Ocurrio un error al intentar traer los datos ($ubicacionParaMensaje)", EstadosError::OK);
            } else {
                if ($querySQL->rowCount() <= 0) {
                    $mensaje = "No se encontro ningun registro.";
                    $auxReturn = new Resultado(false, $mensaje, EstadosError::SIN_RESULTADOS);
                } else {
                    $rows = $querySQL->fetchAll();
                    foreach ($rows as $unaRow) {

                        $unRegistroLog = new Log();

                        $unRegistroLog->setIdLog($unaRow["id_registro"]);
                        $unRegistroLog->setFecha($unaRow["fecha"]);
                        $unRegistroLog->setHora($unaRow["hora"]);
                        $unRegistroLog->setIdUsuario($unaRow["id_usuario"]);
                        $unRegistroLog->setNombreUsuario($unaRow["nombre_usuario"]);
                        $unRegistroLog->setIdRol($unaRow["id_rol"]);
                        $unRegistroLog->setDescripcionRol(Roles::TraerRolPorId($unRegistroLog->getIdRol()));
                        $unRegistroLog->setIdAccion($unaRow["id_accion"]);
                        $unRegistroLog->setDescripcionAccion($unaRow["descripcion_accion"]);

                        array_push($listadoDeRegistrosLog, $unRegistroLog);
                    }

                    $auxReturn = new Resultado(false, $listadoDeRegistrosLog, EstadosError::OK);
                }
            }

        } catch (PDOException $unErrorDB) {
            $auxReturn = new Resultado(true, "Ocurrio un error con la conexion con la base de datos ($ubicacionParaMensaje)." . $unErrorDB->getMessage(), EstadosError::ERROR_DB);
        } catch (Exception $unError) {
            $auxReturn = new Resultado(true, "Ocurrio un error al intentar traer los datos ($ubicacionParaMensaje)." . $unError->getMessage(), EstadosError::ERROR_DB);
        }

        return $auxReturn;
    }

}



?>