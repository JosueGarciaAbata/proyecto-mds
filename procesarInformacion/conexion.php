<?php
class ConexionBD {
    // Variable estática para almacenar la única instancia de la clase
    private static $instancia;
    
    // Datos de conexión a la base de datos
    private $servername = "localhost"; // Nombre del servidor de la base de datos
    private $username = "modelamientoProyecto";      // Nombre de usuario de la base de datos
    private $password = "EKQuY92.ovMe7xin";   // Contraseña de la base de datos
    private $database = "my_creative_portfolio";    // Nombre de la base de datos
    
    // Variable para almacenar la conexión
    private $conexion;
    
    // Constructor privado para evitar que se pueda instanciar la clase desde fuera
    private function __construct() {
        // Crear conexión
        $this->conexion = new mysqli($this->servername, $this->username, $this->password, $this->database);

        if ($this->conexion->connect_error) {
            die("Conexión fallida: " . $this->conexion->connect_error);
        }
    }

    // Método estático para obtener la instancia única de la clase
    public static function obtenerInstancia() {
        // Si la instancia aún no ha sido creada, se crea una nueva
        if (!self::$instancia) {
            self::$instancia = new self();
        }
        // Se retorna la instancia existente
        return self::$instancia;
    }

    // Método para obtener la conexión a la base de datos
    public function obtenerConexion() {
        return $this->conexion;
    }


}