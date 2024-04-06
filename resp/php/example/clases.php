<?php

class Semestre
{
    protected $nivel;
    protected $carrera;
    protected $semestre_id;
    protected $materias = [];
    protected $carpetaSemestre;

    public function __construct($nivel, $carrera, $semestre_id, $materias = null)
    {
        $this->nivel = $nivel;
        $this->carrera = $carrera;
        $this->semestre_id = $semestre_id;
        $this->carpetaSemestre = "archivos/" . $semestre_id;
    }

    public function añadirMateria(Materia $materia)
    {
        if (count($this->materias) < 6) {
            $this->materias[] = $materia;
            return true;
        } else {
            return false; // No se pueden añadir más de 6 materias al semestre
        }
    }

    public function getNivel()
    {
        return $this->nivel;
    }

    public function getCarrera()
    {
        return $this->carrera;
    }

    public function getID()
    {
        return $this->semestre_id;
    }

    public function getCarpeta()
    {
        return $this->carpetaSemestre;
    }

    public function getMaterias()
    {
        return $this->materias;
    }
}

class Materia
{
    protected $nombre;
    protected $materiaId;
    protected $docente;
    protected $semestre;
    protected $paralelo;
    protected $descripcion;
    protected $estudiantes = [];
    protected $tareas = [];
    protected $carpetaMateria;

    public function __construct($nombre, Semestre $semestre, $paralelo, $descripcion, $materiaId, Docente $docente = null)
    {
        $this->nombre = $nombre;
        $this->docente = $docente;
        $this->semestre = $semestre;
        $this->paralelo = $paralelo;
        $this->descripcion = $descripcion;
        $this->materiaId = $materiaId;
        $this->carpetaMateria = "archivos/" . $semestre->getID() . $materiaId;
    }

    public function getNombre()
    {
        return $this->nombre;
    }
    public function getDocente()
    {
        return $this->docente;
    }
    public function getSemestre()
    {
        return $this->semestre;
    }
    public function getParalelo()
    {
        return $this->paralelo;
    }
    public function getMateriaId()
    {
        return $this->materiaId;
    }
    public function getDescripcion()
    {
        return ($this->descripcion != null) ? $this->descripcion : "";
    }


    public function getTareas()
    {
        return $this->tareas;
    }
    public function añadirEstudiante(Usuario $estudiante)
    {
        $this->estudiantes[] = $estudiante;
    }

    public function setEstudiantes($estudiantes)
    {
        $this->estudiantes = $estudiantes;
    }
    public function setDocente(Docente $docente)
    {
        // Esto agrega la tarea al final del array de tareas
        $this->docente[] = $docente;
    }

    public function añadirTarea(Tarea $tarea)
    {
        // Esto agrega la tarea al final del array de tareas
        $this->tareas[] = $tarea;
    }
    public function getCarpetaMateria()
    {
        return $this->carpetaMateria;
    }

    public function getEstudiantes()
    {
        return $this->estudiantes;
    }

}

class Tarea
{
    private $descripcion;
    private $fechaInicio;
    private $fechaLimite;
    private $fechaEntrega;
    private $idTarea;
    private $carpetaTarea;
    private $nombre;

    public function __construct($nombre, $descripcion, Datetime $fechaInicio, Datetime $fechaLimite, $idTarea, $carpetaMateria)
    {
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->fechaInicio = $fechaInicio;
        $this->fechaLimite = $fechaLimite;
        $this->idTarea = $idTarea;
        $this->carpetaTarea = $carpetaMateria . $idTarea;
    }
    //llenarlo luego
    public function entregarTarea($tarea, $fechaEntrega)
    {
        // esto seria mas en la base d datos
        //si la fecha es menor
        //no se como acerle esta part
        //$deber=new Tarea($tarea, $fechaEntrega);
    }

    public function getNombre()
    {
        return $this->nombre;
    }
    public function getDescripcion()
    {
        return $this->descripcion;
    }
    public function getFechaInicio()
    {
        return $this->fechaInicio;
    }
    public function getFechaFin()
    {
        return $this->fechaLimite;
    }
    public function getCarpetaTarea()
    {
        return $this->carpetaTarea;
    }

    public function getIdTarea()
    {
        return $this->idTarea;
    }
}
// Clase adicional para la relación entre estudiante y curso, no se bien como hacerle esto
class Matricula
{
    protected $matricula_id; //creo q talvez lo elimino luego
    protected $estudiante_id;
    protected $curso_id;

    public function insertarMatricula($estudiante_id, $curso_id)
    {
        // ...
    }

    public function actualizarMatricula($matricula_id, Estudiante $estudiante, $curso_id)
    {
        //matricula seria para curso o materia?
    }

    public function eliminarMatricula(Estudiante $estudiante)
    {
        // ...
    }
}

class Usuario
{
    protected $nombre;
    protected $apellido;
    protected $usuario_id;
    protected $correo;
    protected $tipo; //docente-estudiante
    protected $carrera; //por defecto Software
    public function __construct($nombre, $apellido, $usuario_id, $correo = null, $tipo = null)
    {
        // falta correo y tipo
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->usuario_id = $usuario_id;
        $this->correo = $correo;
        $this->tipo = $tipo;
        $this->carrera = "software";
    }


    public function getUsuarioId()
    {
        return $this->usuario_id;
    }

    public function getCarrera()
    {
        return $this->carrera;
    }
    public function getNombre()
    {
        return $this->nombre;
    }
    public function getApellido()
    {
        return $this->apellido;
    }
    public function getNombreCompleto()
    {
        return $this->getNombre() . " " . $this->getApellido();
    }
    public function getTipo()
    {
        return 0;
    }

    public function registrarse()
    {
        //para la matricula, no se si seria mejor en solo la clase estudiante este metodo
    }
    public function login()
    {
        //iniciar _SESSION
    }

    public function logout()
    {
        //destruir sesion

    }
}

class Estudiante extends Usuario
{
    private $estudiante_id;
    private $calificaciones = [];
    public function __construct($nombre, $apellido, $correo, $usuario_id, $estudiante_id = null)
    {
        parent::__construct($nombre, $apellido, $usuario_id, $correo, "0");

        $this->estudiante_id = $estudiante_id;
    }
    public function getTipo()
    {
        return 1;
    }
    public function getEstudianteId()
    {
        return $this->estudiante_id;
    }
    public function enviarTarea(Materia $materiaId, Tarea $tareaId)
    {
        // Lógica para enviar una tarea
        // ...
    }

    public function verCalificaciones(Materia $materia)
    {
        // Lógica para ver calificaciones
        // ...
    }

}

class Docente extends Usuario
{
    protected $materias = [];
    protected $docente_id;
    public function __construct($nombre, $apellido, $usuario_id, $correo, $docente_id = null)
    {
        parent::__construct($nombre, $apellido, $usuario_id, $correo, "1");
        $this->docente_id = $docente_id;
    }
    public function getDocenteId()
    {
        return $this->docente_id;
    }
    public function asignarMateria(Materia $materia)
    {
        if (count($this->materias) < 6) {
            $this->materias[] = $materia;
            return true;
        } else {
            return false; // No se pueden asignar más de 6 materias
        }
    }
    public function getTipo()
    {
        return 2;
    }
    public function añadirEstudianteAMateria(Materia $materia, Estudiante $estudiante)
    {
        // Lógica para añadir un estudiante a la materia
        // ...
    }

    public function añadirTarea(Materia $materia, Tarea $tarea)
    {
        // Lógica para añadir una tarea
        // ...
    }

    public function calificarTarea(Materia $materia, Estudiante $estudiante, Tarea $tarea, $calificacion)
    {
        // Lógica para calificar una tarea
        // ...
    }

    public function verCalificacionesEstudiante(Estudiante $estudiante)
    {
        // Lógica para ver las calificaciones de un estudiante
        // ...
    }
}

class FileManager
{
    private static $location = "archivos";
    public static function getLocation()
    {
        return self::$location;
    }
    public static function crearDirectorio($path)
    {
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }
    }

    public static function createDir()
    {
        // Ver si hay una carpeta para dicha carrera, sino la creamos
        self::crearDirectorio(self::getLocation());
    }

    public static function añadirCarrera(Semestre $semestre)
    {
        // Ver si hay una carpeta para dicha carrera, sino la creamos
        $path = self::$location . "/" . $semestre->getCarrera();
        self::crearDirectorio($path);

        $path .= "/" . (($semestre->getID()) + 1); //es decir se toma cada semestre, ej 2do semestre
        // Creamos por cada nivel una carpeta con su ID
        self::crearDirectorio($path);
    }

    public static function añadirMateria(Materia $materia)
    {
        // Ver si hay una carpeta para dicha materia, sino la creamos
        //  archivos/semestreId/materiaID
        $path = self::$location . "/" . $materia->getSemestre()->getID() . "/" . ($materia->getmateriaId() + 1);
        self::crearDirectorio($path);
    }

    public static function guardarArchivos($path, $name, Usuario $usuario)
    {
        if (isset($_FILES[$name]) && $_FILES[$name]["error"] == UPLOAD_ERR_OK) {
            // Verificar si el archivo no está vacío y tambien si no hay errores al subir X archivo
            if ($_FILES[$name]["size"] > 0) {
                // Directorio donde se guardará el archivo
                $directorioDestino = $path;

                // Nombre del archivo en el servidor (puedes cambiarlo según tus necesidades)

                $extension = pathinfo(basename($_FILES[$name]["name"]), PATHINFO_EXTENSION);
                //quiero dejarlo en el siguiente formato, para ello es necesario tener el usuario y la clase de usuario
                /* 
                    formato[e=estudiante,d=docente]
                        e_id_apellidosUsuario.pdf
                        d_id_...
                */
                //ver que tipo de usuario es
                if (get_class($usuario) === "Docente") {
                    $nombreArchivo = "/d";
                } else {
                    $nombreArchivo = "/e";
                }
                $nombreArchivo .= "_" . $usuario->getUsuarioId() . "_" . $usuario->getApellido() . "." . $extension;
                // Ruta completa del archivo en el servidor
                $rutaArchivo = $directorioDestino . "" . $nombreArchivo;

                // Mover el archivo al directorio de destino
                if (move_uploaded_file($_FILES[$name]["tmp_name"], $rutaArchivo)) {
                    echo "El archivo se cargó correctamente en $rutaArchivo";
                } else {
                    echo "Error al mover el archivo.";
                }
            } else {
                echo "El archivo está vacío.";
            }
        } else {
            echo "No se envió ningún archivo o hubo un error en la carga.";
        }
    }

    public static function añadirTarea(Materia $materia, Tarea $tarea, Usuario $usuario, $archivoSubido)
    {

        //vamos a crear la carpeta con su id,
        //si se añade una tarea eso significa que esta al final

        $path = self::$location . "/" . $materia->getSemestre()->getID() . "/" . $materia->getmateriaId() . "/" . $tarea->getIdTarea();
        //con esto estariamos en un array asociativo con tareas
        self::crearDirectorio($path);
        //veamos que tal con el archivo si fue pasado alguno claro
        self::guardarArchivos($path, $archivoSubido, $usuario);
    }
}
?>