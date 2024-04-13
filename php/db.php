<?php

class DB
{

    private static $instancia;
    private $conexion;
    public function __construct($servername, $username, $password, $database, $port)
    {
        if ($this->conexion === null) {
            try {
                $this->conexion = new PDO("mysql:host=$servername;port=$port;dbname=$database", $username, $password);
                $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo "Conexión fallida: " . $e->getMessage();
            }
        }
    }

    public static function obtenerInstancia($servername, $username, $password, $database, $port)
    {
        if (self::$instancia === null) {
            self::$instancia = new self($servername, $username, $password, $database, $port);
        }
        return self::$instancia;
    }

    public function getPDO()
    {
        return $this->conexion;
    }

    public function quoteValor($valor)
    {
        return $this->conexion->quote($valor);
    }


}

?>