<?php
class AccesoDatos
{
    private static $ObjetoAccesoDatos;
    private $objetoPDO;
    
    private function __construct()
    {
        try {
            // Agregamos la opcion PDO::MYSQL_ATTR_FOUND_ROWS => true para que nos avise igualmente cuando se actualiza por un mismo valor
            $this->objetoPDO = new \PDO('mysql:host=localhost;dbname=comanda;charset=utf8', 'root', '', array(\PDO::ATTR_EMULATE_PREPARES => false, \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION, PDO::MYSQL_ATTR_FOUND_ROWS => true));
            $this->objetoPDO->exec("SET CHARACTER SET utf8");
        } catch (PDOException $unErrorDB) {
            $auxReturn = "Ocurrio un error con la conexion con la base de datos (AccesoDatos)" . $unErrorDB->getMessage();
            print $auxReturn;
            die();
        } catch (Exception $unError) {
            $auxReturn = "Ocurrio un con la conexion de la base de datos (AccesoDatos)" . $unError->getMessage();
            print $auxReturn;
        }
    }

    public function RetornarConsulta($sql)
    {
        return $this->objetoPDO->prepare($sql);
    }
    public function RetornarUltimoIdInsertado()
    {
        return $this->objetoPDO->lastInsertId();
    }

    public static function dameUnObjetoAcceso()
    {
        if (!isset(self::$ObjetoAccesoDatos)) {
            self::$ObjetoAccesoDatos = new AccesoDatos();
        }
        return self::$ObjetoAccesoDatos;
    }

    // Evita que el objeto se pueda clonar
    public function __clone()
    {
        trigger_error('La clonación de este objeto no está permitida', E_USER_ERROR);
    }
}
