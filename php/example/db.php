<?php

class DB
{
    private static $conexion;
    public function __construct($servername, $username, $password, $database, $port)
    {
        if (self::$conexion === null) {
            try {
                self::$conexion = new PDO("mysql:host=$servername;port=$port;dbname=$database", $username, $password);
                self::$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo "Conexión fallida: " . $e->getMessage();
            }
        }
    }

    public function getPDO()
    {
        // Asegúrate de que la conexión esté establecida
        if (!isset(self::$conexion)) {
            self::getConection();
        }

        // Devuelve la instancia de PDO
        return self::$conexion;
    }


    public static function getConection()
    {
        // Lógica para establecer la conexión a la base de datos
        self::$conexion = new PDO("mysql:host=localhost;port=3307;dbname=proyecto_php", "root", "root");
    }

    //funciona
    public static function quoteValor($valor)
    {
        // Asegúrate de que la conexión esté establecida
        if (!isset(self::$conexion)) {
            self::getConection();
        }

        // Escapa y entrecomilla el valor de forma segura
        return self::$conexion->quote($valor);
    }

    //funciona
    public static function insertarRegistro($tabla, $columnas, $valores)
    {
        // Verificar que haya una conexión antes de intentar la inserción
        if (self::$conexion == null) {
            echo "Error: No hay una conexión establecida.";
            return;
        }

        // Verificar que el número de columnas coincida con el número de valores
        if (count($columnas) != count($valores)) {
            echo "Error: El número de columnas no coincide con el número de valores.";
            return;
        }

        // Construir la consulta SQL
        $columnasStr = implode(", ", $columnas);
        //Esta línea utiliza la función implode para convertir el array de nombres de columnas ($columnas) en una cadena de texto separada por comas. Por ejemplo, si $columnas es ['columna1', 'columna2'], entonces $columnasStr será la cadena 'columna1, columna2'. Esto es necesario para construir la parte de la consulta SQL que especifica las columnas en las que se insertarán los valores.

        $valoresStr = implode(", ", array_map(function ($valor) {
            return self::quoteValor($valor);
        }, $valores));
        $consulta = "INSERT INTO $tabla ($columnasStr) VALUES ($valoresStr)";

        try {
            // Ejecutar la consulta
            self::$conexion->exec($consulta);
        } catch (PDOException $e) {
            echo "Error al insertar el registro: " . $e->getMessage();
        }
    }

    //funciona
    public static function consultarRegistros($tabla, $columnas = [], $condicionesColumna = [], $valoresColumna = [])
    {
        // Verificar que haya una conexión antes de intentar la consulta
        if (self::$conexion == null) {
            echo "Error: No hay una conexión establecida.";
            return [];
        }

        $columnasStr = empty($columnas) ? '*' : implode(", ", $columnas);
        $condicionesStr = '';

        // Construir la parte de condiciones si se proporcionan condicionesColumna y valoresColumna
        if (!empty($condicionesColumna) && !empty($valoresColumna)) {
            $condiciones = array_map(function ($columna, $valor) {
                return "$columna = " . self::quoteValor($valor);
            }, $condicionesColumna, $valoresColumna);

            $condicionesStr = ' WHERE ' . implode(' AND ', $condiciones);
        }

        // Construir la consulta SQL
        $consulta = "SELECT $columnasStr FROM $tabla$condicionesStr";

        try {
            // Ejecutar la consulta
            $resultado = self::$conexion->query($consulta);
            return $resultado->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error al leer registros: " . $e->getMessage();
            return []; // Puedes devolver un array vacío o lanzar nuevamente la excepción
        }
    }

    public function añadirSemestre(Semestre $semestre)
    {
        //ya esta enm sesion $_SESSION["idDocente"]
        //necesitamos para esto tabla, columnas y valores
        $columnas = ["nivel", "carrera", "carpetaSemestre"];
        $valores = [$semestre->getNivel(), $semestre->getCarrera(), $semestre->getCarpeta()];
        $this->insertarRegistro("semestre", $columnas, $valores);
    }

    public function añadirMateria(Materia $materia)
    {
        $columnas = ["materia_id_docente", "materia_id_semestre", "nombre_materia", "paralelo", "descripcion_materia", "carpeta_materia"];
        $valores = [$materia->getDocente()->getDocenteId(), $materia->getSemestre()->getID(), $materia->getNombre(), $materia->getParalelo(), $materia->getDescripcion(), $materia->getCarpetaMateria()];
        $this->insertarRegistro("materia", $columnas, $valores);
    }
    public function mostrarSemestres()
    {
        $semestres = self::consultarRegistros("semestre");

        // consultarRegistros($tabla, $columnas = [], $condicionesColumna = [], $valoresColumna = [])
        foreach ($semestres as &$semestre) {
            // Obtener instancias de Materia para el semestre actual
            $col = [];
            $condicion = ["materia_id_semestre"];
            $values = [$semestre["id_semestre"]];
            $materias = self::consultarRegistros("materia", $col, $condicion, $values);
            /* 
            Esto es lo que tiene un elemento de $materias

                [id_materia] => 4
                [materia_id_docente] => 13
                [materia_id_semestre] => 2
                [nombre_materia] => InteresantesHechos
                [paralelo] => A
                [descripcion_materia] => 
                [carpeta_materia] => archivos/23
            */
            // Asignar las instancias de Materia al semestre
            //toca crear el semestre, pero que m***** porq viene en formato array asociativo
            //vamos a hacer la burbuja
            $semestreF = new Semestre($semestre["nivel"], $semestre["carrera"], $semestre["id_semestre"]); //$nivel, $carrera,$semestre_id
            $semestre = $semestreF;
            $smestreF = null;
            foreach ($materias as $materia) {
                //toca hacer llenar todos los docentes :CCCCC
                $materiasF = new Materia($materia["nombre_materia"], $semestre, $materia["paralelo"], $materia["descripcion_materia"], $materia["id_materia"]);
                //($nombre, Semestre $semestre, $paralelo, $descripcion, $materiaId, Docente $docente=null)
                $semestre->añadirMateria($materiasF);
            }
            //print_r($materiasF);
        }

        return $semestres;
    }

    public function consultarMayorId($tabla, $columna)
    {
        // Consulta SQL para obtener el mayor valor en la columna específica
        $consulta = "SELECT MAX($columna) as mayor_valor FROM $tabla";
        // Ejecutar la consulta
        $resultado = self::$conexion->query($consulta);

        // Verificar si la consulta fue exitosa
        if ($resultado) {
            // Obtener el resultado como un array asociativo
            $fila = $resultado->fetch(PDO::FETCH_ASSOC);
            // Devolver el valor más grande
            return $fila['mayor_valor'];
        } else {
            return 0;
        }
    }


    public function añadirTarea(Tarea $tarea, Materia $materia)
    {
        //ya esta enm sesion $_SESSION["idDocente"]
        //necesitamos para esto tabla, columnas y valores
        $columnas = ["tarea_id_docente", "nombre_tarea", "descripcion", "fecha_inicio", "fecha_fin", "tarea_id_materia", "carpeta_tarea"];
        $valores = [$_SESSION["idDocente"], $tarea->getNombre(), $tarea->getDescripcion(), $tarea->getFechaInicio()->format('Y-m-d H:i:s'), $tarea->getFechaFin()->format('Y-m-d H:i:s'), $materia->getmateriaId(), $tarea->getCarpetaTarea()];
        $this->insertarRegistro("tarea", $columnas, $valores);
    }

    public function consultarSemestre($id)
    {
        //ya esta enm sesion $_SESSION["idDocente"]
        //necesitamos para esto tabla, columnas y valores
        $columnas = ["id_semestre"];
        $valores = [$id];
        //quiero tambien el id

        return $this->consultarRegistros("semestre", [], $columnas, $valores);
    }
    //luego veo si lo dejo como esta

    public function consultarEstudiantePorId($estudiante_id)
    {
        global $conn;
        try {
            $sql = "SELECT * FROM usuario WHERE id_usuario = :estudiante_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':estudiante_id', $estudiante_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error al consultar estudiante: " . $e->getMessage();
            return false;
        }
    }

    public function modificarEstudiante($estudiante_id, $nuevoNombre, $nuevoApellido)
    {
        global $conn;
        try {
            // Verifica primero si el estudiante existe
            $estudianteExistente = consultarEstudiantePorId($estudiante_id);
            echo "hola modificar E";
            if ($estudianteExistente) {
                // El estudiante existe, procede a la modificación
                $sql = "UPDATE usuario SET nombres = :nombre, apellidos = :apellido WHERE id_usuario = :estudiante_id";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':nombre', $nuevoNombre);
                $stmt->bindParam(':apellido', $nuevoApellido);
                $stmt->bindParam(':estudiante_id', $estudiante_id, PDO::PARAM_INT);
                $stmt->execute();

                return true;
            } else {
                // El estudiante no existe
                echo "El estudiante con ID $estudiante_id no existe.";
                return false;
            }
        } catch (PDOException $e) {
            echo "Error al modificar estudiante: " . $e->getMessage();
            return false;
        }
    }
    public function eliminarEstudiante($estudiante_id)
    {
        global $conn;
        try {
            // Verifica primero si el estudiante existe
            $estudianteExistente = consultarEstudiantePorId($estudiante_id);
            if ($estudianteExistente) {
                // El estudiante existe, procede a la eliminación
                $sql = "DELETE FROM usuario WHERE id_usuario = :estudiante_id";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':estudiante_id', $estudiante_id, PDO::PARAM_INT);
                $stmt->execute();

                return true;
            } else {
                // El estudiante no existe
                echo "El estudiante con ID $estudiante_id no existe.";
                return false;
            }
        } catch (PDOException $e) {
            echo "Error al eliminar estudiante: " . $e->getMessage();
            return false;
        }
    }
    public function queryTareasByDocente($idDocente)
    {
        global $conn;
        try {
            $query = "SELECT t.id_tarea, t.nombre_tarea, t.fecha_inicio, t.fecha_fin
            FROM tarea t
            JOIN materia m ON t.tarea_id_materia = m.id_materia
            WHERE m.materia_id_curso = :idCurso AND m.id_materia = :idMateria;
            ";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':idDocente', $idDocente, PDO::PARAM_INT);
            $stmt->execute();
            echo "valio";
            while (($row = $stmt->fetch(PDO::FETCH_ASSOC))) {
                echo '<article class="row"><p>ID Tarea: ' . $row['id_tarea'] . '</p>';
                echo '<p>Nombre Tarea: ' . $row['nombre_tarea'] . '</p>';
                echo '<p>Fecha Inicio: ' . $row['fecha_inicio'] . '</p>';
                echo '<p>Fecha Fin: ' . $row['fecha_fin'] . '</p>';
                echo '<p>ID Curso: ' . $row['id_curso'] . '</p>';
                echo '<p>Nombre Materia: ' . $row['nombre_materia'] . '</p>';
                echo '<p>Paralelo: ' . $row['paralelo'] . '</p></article><br>';
            }
        } catch (PDOException $e) {
            echo "Error en la consulta: " . $e->getMessage();
        }
    }

    public function crearUsuario(Usuario $datos)
    {
        // Implementar la lógica para insertar un nuevo usuario en la base de datos
    }

    public function consultaParticipantes(Materia $materia)
    {
        //tenemos la materia, ahora queremos los participantes
        //primero arreglar la db
        $columnas = ["id_materia_curso"];
        $valores = [$materia->getMateriaId()];
        $participantes = self::consultarRegistros("curso", [], $columnas, $valores);
        ///tomar su arreglo asociativo y volverlo objeto y retornarlo
        $participantes = ordenarArrayAsociativo($participantes, "");
        //ahora quiero obtener todos los usuarios
        for ($i = 0; $i < sizeof($participantes); $i++) {
            //primero una consulta a sus datos a que tenemos su id y tambien la materia en la que se encuentran matriculados
            $datosUs = self::consultarRegistros("usuarios", [], ["id_usuario"], $participantes[$i]["id_usuario_curso"]);
            if ($datosUs["tipo_usuario"] === "1") { //docente
                $materia->setDocente(new Docente($datosUs["nombres"], $datosUs["apellidos"], $datosUs["id_usuario"], $datosUs["correo"])); //$docente_id=null
                // $nombre, $apellido, $usuario_id, $correo, $docente_id
                continue;
            } //estudiante
            //($nombre, $apellido, $usuario_id, $correo=null, $tipo)
            $materia->añadirEstudiante(new Estudiante($datosUs["nombres"], $datosUs["apellidos"], $datosUs["correo"], $datosUs["id_usuario"])); //$nombre, $apellido, $correo,$usuario_id, $estudiante_id
        }
        return $materia;
    }

    public function consultarTareasMateria(Materia $materia)
    {
        print_r($materia);
        $columnas = ["tarea_id_materia"];
        $valores = [$materia->getMateriaId()];
        $tareas = self::consultarRegistros("tarea", [], $columnas, $valores);
        $tareas = ordenarArrayAsociativo($tareas, "fecha_fin");
        ///tomar su arreglo asociativo y volverlo objeto y retornarlo

        for ($i = 0; $i < sizeof($tareas); $i++) {
            //vamos a crear los objetos en cada posicion y luego lo retornamos
            $tareas[$i] = new Tarea($tareas[$i]["nombre_tarea"], $tareas[$i]["descripcion"], $tareas[$i]["fecha_inicio"], $tareas[$i]["fecha_fin"], $tareas[$i]["id_tarea"], $tareas[$i]["carpeta_tarea"]);
        }
        return $tareas;
    }

}

//  no se si en esta clase sea el mejor lugar para usar este metodo
function buscarSemestrePorID($arraySemestres, $idBuscado)
{
    foreach ($arraySemestres as $semestre) {
        if ($semestre->getID() === $idBuscado) {
            // Se encontró el semestre con el ID buscado
            return $semestre;
        }
    }
    return null;
}

function buscarMateriaPorId($arraySemestres, $MateriaId)
{
    $materiaF = null;
    foreach ($arraySemestres as $semestre) {
        $materias = $semestre->getMaterias();
        foreach ($materias as $materia) {
            if ($materia->getMateriaId() == $MateriaId) {
                return $materia;
            }
        }
    }
    return $materiaF;
}

function ordenarArrayAsociativo($array, $campo)
{
    /* 
    Usort ordena el array
    Para ordenarlo usa una funcion con campos a y b que pertenecen al conjunto y los compara usando un campo y devuelve un resultado que indica si $a es menor, igual o mayor que $b.
    
    La función usort de PHP utiliza el operador <=> (spaceship) para comparar elementos, que es una comparación combinada que tiene en cuenta el tipo y el valor. Por lo tanto, ordenará el array sin importar si los valores son cadenas(claro tomamos su valor numerico) o números.
    */
    usort($array, function ($a, $b) use ($campo) {

        return $a[$campo] <=> $b[$campo];
    });

    return $array;
}



?>