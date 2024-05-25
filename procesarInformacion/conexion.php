<?php
class ConexionBD
{
    // Variable estática para almacenar la única instancia de la clase
    private static $instancia;

    // Datos de conexión a la base de datos
    private $servername = "localhost";
    private $username = "modelamientoProyecto";
    private $password = "EKQuY92.ovMe7xin"; // EKQuY92ovMe7xin
    private $database = "my_creative_portfolio";
    private static $port = 3007;
    // Variable para almacenar la conexión
    private $conexion;


    private function __construct()
    {
        // Crear conexión
        $this->conexion = new mysqli($this->servername, $this->username, $this->password, $this->database, self::$port);

        if ($this->conexion->connect_error) {
            die("Connection failed: " . $this->conexion->connect_error);
        }
    }

    // Método estático para obtener la instancia única de la clase
    public static function obtenerInstancia()
    {
        // Si la instancia aún no ha sido creada, se crea una nueva
        if (!self::$instancia) {
            self::$instancia = new self();
        }
        // Se retorna la instancia existente
        return self::$instancia;
    }

    // Método para obtener la conexión a la base de datos
    public function obtenerConexion()
    {
        return $this->conexion;
    }
}