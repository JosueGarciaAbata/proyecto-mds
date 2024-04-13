<?php
require_once "clases.php";
require_once "db.php";
require_once "conexion.php";

error_reporting(E_ALL & ~E_NOTICE);
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function generarSelectSemestres($arraySemestres)
{
    // Agrupar semestres por carrera
    $grupos = [];
    foreach ($arraySemestres as $semestre) {
        $grupos[$semestre->getCarrera()][] = $semestre;
    }

    // Ordenar semestres por niveles dentro de cada grupo
    foreach ($grupos as &$grupo) {
        usort($grupo, function ($a, $b) {
            return $a->getNivel() - $b->getNivel();
        });
    }

    // Generar el código HTML
    $html = '<select name="curso" id="curso">' . PHP_EOL;

    foreach ($grupos as $carrera => $semestres) {
        $html .= '<optgroup label="' . $carrera . '">' . PHP_EOL;

        foreach ($semestres as $semestre) {
            $html .= '<option value="' . $semestre->getID() . '">' . $semestre->getNivel() . '</option>' . PHP_EOL;
        }

        $html .= '</optgroup>' . PHP_EOL;
    }

    $html .= '</select>' . PHP_EOL;

    return $html;
}
function nombre($number)
{
    $semestre = "";
    switch ($number) {
        case 1:
            $semestre = "Primero";
            break;
        case 2:
            $semestre = "Segundo";
            break;
        case 3:
            $semestre = "Tercero";
            break;
        case 4:
            $semestre = "Cuarto";
            break;
        case 5:
            $semestre = "Quinto";
            break;
        case 6:
            $semestre = "Sexto";
            break;
        case 7:
            $semestre = "Septimo";
            break;
        case 8:
            $semestre = "Octavo";
            break;
        case 9:
            $semestre = "Noveno";
            break;
        case 10:
            $semestre = "Decimo";
            break;
        default:
            $semestre = "Excepcion. Numero fuera de rango";
    }
    return $semestre;
}
function generarListaCursos($arraySemestres)
{
    // Agrupar semestres por carrera

    $grupos = [];
    foreach ($arraySemestres as $semestre) {
        $grupos[$semestre->getCarrera()][] = $semestre;
    }

    // Ordenar semestres por niveles dentro de cada grupo
    foreach ($grupos as &$grupo) {
        usort($grupo, function ($a, $b) {
            return $a->getNivel() - $b->getNivel();
        });
    }

    // Generar el código HTML
    $html = "";
    foreach ($grupos as $carrera => $semestres) {
        $html .= '<div class="label">' . $carrera . '</div><div class="content">';
        foreach ($semestres as $semestre) {
            $materias = $semestre->getMaterias();
            if (!empty($materias)) {
                foreach ($materias as $materia) {
                    $html .= '<p>' . $semestre->getNivel() . " - " . $materia->getNombre() . '</p>';
                }
            }
        }
        $html .= '</div></div>';
    }
    return $html;
}

function generarListaTareas($tareas)
{
    //primero llenarlo con tareas antes de entregarlo
    // Generar el código HTML
    $html = "";
    foreach ($tareas as $tarea) {
        $html .= '<article class="row"><img class="imgArchivo" src="assets/img/tarea.svg" alt="tarea"><p>' . $tarea->getNombre() . '</p>
                <img class="imgArchivo tarea" src="assets/img/editar.svg" alt="editar tarea" data-id="' . $tarea->getIdTarea() . '">
                <img class="imgArchivo usserE" src="assets/img/delete.svg" alt="eliminar alumno" data-id="' . $tarea->getIdTarea() . '">
                </article><br>';
    }
    echo $html;
}

// Método para obtener los docentes con su respectivo ID de usuario y docente
function obtenerDocentes()
{
    $conexion = obtenerConexion();

    $query = "SELECT id_usuario, nombres FROM usuario WHERE tipo_usuario = 1";
    $result = $conexion->query($query);

    $usuarios_docentes = [];

    if ($result->rowCount() > 0) {
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            // Obtener el ID del docente asociado al ID del usuario
            $idUsuario = $row['id_usuario'];
            $idDocente = obtenerIdDocente($conexion, $idUsuario);

            $usuarios_docentes[] = [
                'id_usuario' => $idUsuario,
                'id_docente' => $idDocente,
                'nombre' => $row['nombres']
            ];
        }
    }

    return $usuarios_docentes;
}

// Función para obtener el ID del docente asociado al ID del usuario
function obtenerIdDocente($conexion, $idUsuario)
{
    $query = "SELECT id_docente FROM docente WHERE id_usuario_docente = :id_usuario_docente";
    $stmt = $conexion->prepare($query);
    $stmt->bindParam(':id_usuario_docente', $idUsuario, PDO::PARAM_INT);
    $stmt->execute();

    $idDocente = null;

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $idDocente = $row['id_docente'];
    }

    return $idDocente;
}

function obtenerEstudiantes()
{
    $conexion = obtenerConexion();

    $query = "SELECT id_usuario, nombres FROM usuario WHERE tipo_usuario = 0";
    $result = $conexion->query($query);

    $usuarios_docentes = [];

    if ($result->rowCount() > 0) {
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            // Obtener el ID del docente asociado al ID del usuario
            $idUsuario = $row['id_usuario'];
            $idEstudiante = obtenerIdEstudiante($conexion, $idUsuario);

            $usuarios_docentes[] = [
                'id_usuario' => $idUsuario,
                'id_estudiante' => $idEstudiante,
                'nombre' => $row['nombres']
            ];
        }
    }

    return $usuarios_docentes;
}

// Función para obtener el ID del docente asociado al ID del usuario
function obtenerIdEstudiante($conexion, $idUsuario)
{
    $query = "SELECT id_estudiante FROM estudiante WHERE id_usuario_estudiante = :id_usuario_estudiante";
    $stmt = $conexion->prepare($query);
    $stmt->bindParam(':id_usuario_estudiante', $idUsuario, PDO::PARAM_INT);
    $stmt->execute();

    $idEstudiante = null;

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $idEstudiante = $row['id_estudiante'];
    }

    return $idEstudiante;
}

function genFormMaterias($arraySemestres, $arrayDocentes)
{
    $html = '
    <form action="#" method="POST">
    <button class="btnMateria" id="addMateria">Añadir Materia</button>
        <fieldset>
            <legend>Nueva Materia:</legend>
            <br>
            <label>Nombre:
                <input type="text" id="nombre" name="nombreMateria" required>
            </label>
            <br>

            <label>Docente:
                <select id="docente" name="docente" required>
                    <option value="" disabled selected>Seleccione un docente</option>';
    foreach ($arrayDocentes as $docente) {
        $html .= '<option value="' . $docente['id_docente'] . '">' . $docente['nombre'] . '</option>';
    }
    $html .= '</select>
            </label>
            <br>

            <label>Paralelo:
                <input type="text" maxlength="1" id="paralelo" name="paralelo" required>
            </label>
            <br>

            <br>
            <textarea class="textarea" id="textArea" placeholder="Ingresa una descripcion..." name="descripcion" cols="60" rows="10"></textarea>
            <br>

            <br>
            <label>Semestres:' . generarSelectSemestres($arraySemestres) . '</label>     
        </fieldset>
    </form>';
    echo $html;
}

function obtenerDocentesConTareas()
{

    $conexion = obtenerConexion();

    $query = "SELECT DISTINCT d.id_docente, u.nombres, u.apellidos
              FROM docente d
              INNER JOIN tarea t ON d.id_docente = t.tarea_id_docente
              INNER JOIN usuario u ON d.id_usuario_docente = u.id_usuario";

    // Ejecutar la consulta y obtener los resultados
    $stmt = $conexion->prepare($query); // Ajusta esto según tu lógica de ejecución de consultas
    $stmt->execute();

    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $resultados;
}

function obtenerMateriasDocenteActual($matricula_id_usuario)
{
    try {
        $conexion = obtenerConexion();

        $sentencia = "SELECT DISTINCT m.id_materia, m.nombre_materia, m.materia_id_docente, m.paralelo
                  FROM matricula AS ma
                  JOIN materia AS m ON ma.matricula_id_materia = m.id_materia
                  WHERE ma.matricula_id_usuario = :matricula_id_usuario";

        // Preparar la declaración
        $consulta = $conexion->prepare($sentencia);

        // Asignar valores a los parámetros
        $consulta->bindParam(':matricula_id_usuario', $matricula_id_usuario, PDO::PARAM_INT);

        // Ejecutar la consulta
        $consulta->execute();

        // Obtener y devolver los resultados
        $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);

        return $resultados;
    } catch (PDOException $e) {
        echo "Error al ejecutar la consulta: " . $e->getMessage();
        return false;
    }
}

function genFormTareas()
{
    $idUsuario = $_SESSION['idUsuario'];
    $materias = obtenerMateriasDocenteActual($idUsuario);

    $html = '
      <h2>Crear tarea</h2>
        <section class="añadirTarea" id="añadirTarea">
            <form action="#" method="POST" enctype="multipart/form-data" class="formCrearTarea">
                <fieldset>
                    <legend>Nueva Tarea:</legend>
                    <br>
                    <label>Nombre:
                        <input type="text" minlength="5" maxlength="50" id="nombre" name="nombreTarea" required>
                    </label>
                    <br>
                    <label>Fecha inicio:
                        <input type="date" name="fechaDiaInicio" id="fechaDiaInicio" class="fecha dia-inicio">
                        <input type="time" name="fechaHoraInicio" id="fechaHoraInicio" class="fecha hora-inicio">
                    </label>
                    <br>
                    <label>Fecha fin:
                        <input type="date" name="fechaDiaFin" id="fechaDiaFin" class="fecha dia-fin" required>
                        <input type="time" name="fechaHoraFin" id="fechaHoraFin" class="fecha hora-fin" required>
                    </label>
                    <br>
                    <label>Materia:
                        <select name="materia" id="materiaSelect" class="materiaSelect" onchange="actualizarDocente()" required>
                            <option value="" disabled selected>Seleccione una materia</option>';
    foreach ($materias as $materia) {
        $html .= '<option value="' . $materia['id_materia'] . '">' . $materia['nombre_materia'] . '</option>';
    }
    $html .= '</select>
                    </label>
                    <br>
                    <label>Docente:
                        <select name="docente" id="docenteSelect" class="docenteSelect"required>
                            <option value="" disabled selected>Seleccione un docente</option>
                        </select>
                    </label>
                    <br>
                    <label>
                        <input type="checkbox" name="agregarATodos" value="1"> Tarea para todos los estudiantes
                    </label>
                    <br>
                    <label>Guía docente:
                        <br><input type="file" name="archivoSubido" id="archivoSubido">
                    </label>
                    <br>
                    <br>
                    <textarea class="textarea" id="textArea" placeholder="Ingresa una descripción..." name="descripcionTarea" cols="60" rows="10" required></textarea>
                    <br>
                    <input type="submit" name="tareaEstudiantes" value="Enviar tarea">
                </fieldset>
            </form>
        </section>';

    $html .= '<h2>Actualizar tarea</h2>

    <label>Materias:
    <select name="materia" id="materiaSelectTarea" class="materiaSelectTarea" onchange="obtenerTareas()" required>
        <option value="" disabled selected>Seleccione una materia</option>';

    foreach ($materias as $materia) {
        $html .= '<option value="' . $materia['id_materia'] . '">' . $materia['nombre_materia'] . '</option>';
    }

    $html .= '</select>
</label>

<form id="formModificarTarea" action="actualizar_tarea.php" method="POST" class="forEnviarTarea">
    <label>Tarea:
        <select name="tarea" id="tareaSelect" class="tareaSelect" required>
            <option value="" disabled selected>Seleccione una tarea</option>
            <!-- Las opciones de tarea se cargarán dinámicamente después de seleccionar una materia -->
        </select>
    </label>
    <br>
    <label>Nuevo nombre de la tarea:
        <input type="text" name="nombreTarea" id="nombreTarea" class="nombreTarea" placeholder="Ingrese el nuevo nombre de la tarea" required>
    </label>
    
    <button type="submit">Actualizar</button>
</form>
<br>';

    echo $html;
}

function genParticipantRow(Usuario $usser)
{

    return '<article class="row"><img class="imgArchivo" src="assets/img/usser.svg" alt="usuario"><p>' . $usser->getNombreCompleto() . '</p>
        <img class="imgArchivo usserE" src="assets/img/editar.svg" alt="editar usuario" data-id="' . $usser->getUsuarioId() . '">
        <img class="imgArchivo usserE" src="assets/img/delete.svg" alt="eliminar usuario" data-id="' . $usser->getUsuarioId() . '">
        </article><br>';
}

// Función para obtener la lista de usuarios
function obtenerUsuarios()
{
    $conexion = obtenerConexion();
    $query = "SELECT id_usuario, nombres, apellidos, correo, tipo_usuario FROM usuario";
    $result = $conexion->query($query);

    $usuarios = [];

    if ($result->rowCount() > 0) {
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $usuarios[] = $row;
        }
    }

    return $usuarios;
}

function genParticipantes()
{
    $html = '
        <h2>Crear participantes</h2>
    <div class="form_box register">
      <h2>Crear</h2>
      <form action="#" method="POST" class="genParticipantes">
        <div class="input_box">
          <input class="userName" type="text" name="nombres" required />
          <label>Name</label>
        </div>
        <div class="input_box">
          <input class="lastName" type="text" name="apellidos" required />
          <label>LastName</label>
        </div>
        <div class="input_box">
          <input class="email" type="text" name="correo" required />
          <label>Email</label>
        </div>
        <div class="input_box">
          <input class="pass" type="password" name="contrasena" required />
          <label>Password</label>

        </div>
        <div class="labelUsuario">
          <label>Tipo de usuario
        </div>

        <div class="tipoUsuario">
          <label>
            <input class="typeUserDocente" type="radio" name="tipoUsuario" value=1> Docente
          </label>
          <label>
            <input type="radio" class="typeUserEstudiante" name="tipoUsuario" value=0> Estudiante
          </label>
        </div>
        <input id="reg" type="submit" value="Crear" class="btn" name="enviar" />
      </form>
      </div>

    <h2>Actualizar participantes</h2>';

    $usuarios = obtenerUsuarios();
    if (!empty($usuarios)) {
        $html .= '<table border="1" class="tablaGenParticipantes">
                    <tr>
                      <th>ID</th>
                      <th>Nombre</th>
                      <th>Apellido</th>
                      <th>Email</th>
                      <th>Tipo de Usuario</th>
                      <th>Acciones</th>
                    </tr>';

        foreach ($usuarios as $usuario) {
            $html .= '<tr>
                        <td>' . $usuario['id_usuario'] . '</td>
                        <td>' . $usuario['nombres'] . '</td>
                        <td>' . $usuario['apellidos'] . '</td>
                        <td>' . $usuario['correo'] . '</td>
                        <td>' . ($usuario['tipo_usuario'] == 1 ? 'Docente' : 'Estudiante') . '</td>
                        <td><a href="actualizar_usuario.php?id=' . $usuario['id_usuario'] . '">Actualizar</a></td>
                      </tr>';
        }

        $html .= '</table>';
    } else {
        $html .= '<p>No hay usuarios disponibles para actualizar.</p>';
    }

    $html .= '<h2>Eliminar participantes</h2>';
    $usuarios = obtenerUsuarios();
    if (!empty($usuarios)) {
        $html .= '<table border="1" class="tablaEliminarUsuarios">
                    <tr>
                      <th>ID</th>
                      <th>Nombre</th>
                      <th>Apellido</th>
                      <th>Email</th>
                      <th>Tipo de Usuario</th>
                      <th>Acciones</th>
                    </tr>';

        foreach ($usuarios as $usuario) {
            $html .= '<tr class="forMeliminarParticipantes">
                        <td>' . $usuario['id_usuario'] . '</td>
                        <td>' . $usuario['nombres'] . '</td>
                        <td>' . $usuario['apellidos'] . '</td>
                        <td>' . $usuario['correo'] . '</td>
                        <td>' . ($usuario['tipo_usuario'] == 1 ? 'Docente' : 'Estudiante') . '</td>
                        <td><a href="eliminar_usuario.php?id=' . $usuario['id_usuario'] . '">Eliminar</a></td>
                      </tr>';
        }

        $html .= '</table>';
    } else {
        $html .= '<p>No hay usuarios disponibles para actualizar.</p>';
    }

    echo $html;
}

function genFormSemestres()
{
    $html = '
        <form action="#" method="POST" class="genFormSemestres">
        <label for="carrera">Carrera:</label>
        <input type="text" id="carrera" name="carrera" placeholder="Software" required>

        <label for="semestre">Semestre:</label>
        <input type="number" id="semestre" name="semestre" min="1" max="9" required>

        <button type="submit" name="submit">Crear Semestre</button>
    </form>';
    echo $html;
}

function obtenerMaterias()
{
    $conexion = obtenerConexion();
    $query = "SELECT DISTINCT matricula_id_materia FROM matricula";
    $result = $conexion->query($query);

    $materias = [];

    if ($result->rowCount() > 0) {
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            // Obtén los detalles de la materia desde la tabla materia
            $materiaDetalles = obtenerDetallesMateria($row['matricula_id_materia']);

            // Agrega los detalles al array de materias
            if ($materiaDetalles) {
                $materias[] = $materiaDetalles;
            }
        }
    }

    return $materias;
}

function obtenerDetallesMateria($idMateria)
{
    $conexion = obtenerConexion();
    $query = "SELECT id_materia, nombre_materia, paralelo, materia_id_docente FROM materia WHERE id_materia = ?";
    $stmt = $conexion->prepare($query);
    $stmt->execute([$idMateria]);

    $detallesMateria = $stmt->fetch(PDO::FETCH_ASSOC);

    return $detallesMateria;
}

function obtenerNombreApellidoDocente($idDocente)
{
    $conexion = obtenerConexion();
    $query = "SELECT u.nombres, u.apellidos
              FROM docente d
              INNER JOIN usuario u ON d.id_usuario_docente = u.id_usuario
              WHERE d.id_docente = ?";
    $stmt = $conexion->prepare($query);
    $stmt->execute([$idDocente]);

    $nombreApellido = $stmt->fetch(PDO::FETCH_ASSOC);

    return $nombreApellido;
}

function crearCursos($arraySemestres)
{
    $docentes = obtenerDocentes();
    $estudiantes = obtenerEstudiantes();

    $idUsuario = $_SESSION['idUsuario'];
    $materias = obtenerMateriasDocenteActual($idUsuario);

    $html = '
    <h2>Crear materia</h2>
    <div class="form_box register">
    <form action="#" method="POST">
        <div class="input_box">
            <label for="nombre_curso">Nombre de la materia:</label>
            <input type="text" id="nombre_curso" name="nombre_materia" required />
        </div>

        <div class="input_box">
            <label for="docente">Docente:</label>
            <select id="docente" name="docente" required>';

    foreach ($docentes as $docente) {
        $value = $docente['id_usuario'] . '|' . $docente['id_docente'];
        $html .= '<option value="' . htmlspecialchars($value) . '">' . $docente['nombre'] . '</option>';
    }

    $html .= '</select>
        </div>

        <div class="input_box">
            <label for="paralelo">Paralelo:</label>
            <input type="text" maxlength="1" id="paralelo" name="paralelo" required>
        </div>';

    $html .= '

        <div class="input_box">
            <label for="semestres">Semestres:' . generarSelectSemestres($arraySemestres) . '</label>
        </div>


        <div class="descripcion">
            <br>
            <textarea class="textarea" id="textArea" placeholder="Ingresa una descripcion..." name="descripcion" cols="60" rows="10"></textarea>
            <br>
        </div>

        <input type="submit" value="Crear materia" class="btn" name="enviarCrear" />
    </form>
    </div>';

    $html .= '<h2>Añadir estudiantes a materias</h2>';

    if (!empty($estudiantes)) {
        $html .= '<form action="#" method="POST" class="addEstudiantesMateria">
        <div class="input_box">
            <label for="estudiante">Estudiante:</label>
            <select id="estudiante" name="estudiante" required>';

        foreach ($estudiantes as $estudiante) {
            $html .= '<option value="' . $estudiante['id_usuario'] . '">' . $estudiante['nombre'] . '</option>';
        }

        $html .= '</select>
        </div>';
    } else {
        $html .= '<p>No hay estudiantes disponibles.</p>';
    }

    if (!empty($materias)) {
        $html .= '<div class="input_box">
        <label for="materia">Materia:</label>
        <select id="materia" name="materia" required>';

        foreach ($materias as $materia) {
            $html .= '<option value="' . $materia['id_materia'] . '">' . $materia['nombre_materia'] . '</option>';
        }

        $html .= '</select>
        </div>

        <input type="submit" value="Agregar" class="btn" name="agregarEstudianteMateria" />
    </form>';
    } else {
        $html .= '<p>No hay materias disponibles.</p>';
    }

    $html .= '<h2>Actualizar materia</h2>';

    if (!empty($materias)) {
        $html .= '<table border="1" class="actualizarMateria">
                    <tr>
                      <th>ID</th>
                      <th>Nombre</th>
                      <th>Paralelo</th>
                      <th>Docente</th>
                      <th>Acciones</th>
                    </tr>';

        foreach ($materias as $materia) {
            $docenteInfo = obtenerNombreApellidoDocente($materia['materia_id_docente']);

            $html .= '<tr>
                        <td>' . $materia['id_materia'] . '</td>
                        <td>' . $materia['nombre_materia'] . '</td>
                        <td>' . $materia['paralelo'] . '</td>
                        <td>' . $docenteInfo['nombres'] . ' ' . $docenteInfo['apellidos'] . '</td>
                        <td><a href="actualizar_materia.php?id=' . $materia['id_materia'] . '">Actualizar</a></td>
                      </tr>';
        }

        $html .= '</table>';
    } else {
        $html .= '<p>No hay materias disponibles para actualizar.</p>';
    }

    $html .= '<h2>Eliminar Materia</h2>';

    if (!empty($materias)) {
        $html .= '<table border="1" class="eliminarMaterias">
                    <tr>
                      <th>ID</th>
                      <th>Nombre</th>
                      <th>Paralelo</th>
                      <th>Docente</th>
                      <th>Acciones</th>
                    </tr>';

        foreach ($materias as $materia) {
            $docenteInfo = obtenerNombreApellidoDocente($materia['materia_id_docente']);

            $html .= '<tr>
                        <td>' . $materia['id_materia'] . '</td>
                        <td>' . $materia['nombre_materia'] . '</td>
                        <td>' . $materia['paralelo'] . '</td>
                        <td>' . $docenteInfo['nombres'] . ' ' . $docenteInfo['apellidos'] . '</td>
                        <td><a href="eliminar_materia.php?id=' . $materia['id_materia'] . '">Eliminar</a></td>
                      </tr>';
        }

        $html .= '</table>';
    } else {
        $html .= '<p>No hay materias disponibles para eliminar.</p>';
    }

    echo $html;
}

function genFormCursos()
{

    // Obtener el ID del usuario desde la sesión (ajusta según la estructura de tu sesión)
    $idUsuario = $_SESSION['idUsuario'];

    $html = '<div class="form_box register">
    <h2>Materias Disponibles</h2>';

    $cursos = obtenerCursosMatriculados($idUsuario);

    if (!empty($cursos)) {
        $html .= '<table border="1" class="materiasDisponibles">
                    <tr>
                      <th>ID</th>
                      <th>Nombre</th>
                      <th>Paralelo</th>
                      <th>Acciones</th>
                    </tr>';

        foreach ($cursos as $curso) {
            $html .= '<tr>
                        <td>' . $curso['id_materia'] . '</td>
                        <td>' . $curso['nombre_materia'] . '</td>
                        <td>' . $curso['paralelo'] . '</td>
                        <td><a href="ver_curso.php?id=' . $curso['id_materia'] . '">Ver Detalles</a></td>
                      </tr>';
        }

        $html .= '</table>';
    } else {
        $html .= '<p>No hay cursos (materias) disponibles.</p>';
    }

    $html .= '</div>';
    echo $html;
}

function obtenerCursosMatriculados($idUsuario)
{
    $conexion = obtenerConexion();

    // Obtener las ID de las materias en las que el usuario está matriculado
    $queryMatriculas = "SELECT matricula_id_materia FROM matricula WHERE matricula_id_usuario = :id_usuario";
    $stmtMatriculas = $conexion->prepare($queryMatriculas);
    $stmtMatriculas->bindParam(':id_usuario', $idUsuario);
    $stmtMatriculas->execute();

    $idMaterias = $stmtMatriculas->fetchAll(PDO::FETCH_COLUMN);

    // Obtener la información completa de las materias
    $cursos = [];
    foreach ($idMaterias as $idMateria) {
        $queryCurso = "SELECT id_materia, nombre_materia, paralelo FROM materia WHERE id_materia = :id_materia";
        $stmtCurso = $conexion->prepare($queryCurso);
        $stmtCurso->bindParam(':id_materia', $idMateria);
        $stmtCurso->execute();

        $curso = $stmtCurso->fetch(PDO::FETCH_ASSOC);

        if ($curso) {
            $cursos[] = $curso;
        }
    }

    return $cursos;
}

function obtenerNombresEstudiantes($estudiantes)
{
    $nombres = [];
    foreach ($estudiantes as $estudiante) {
        $nombres[] = $estudiante['nombre'];
    }
    return implode(', ', $nombres);
}

function crearInicio()
{

    $conexion = obtenerConexion();

    $id_usuario = $_SESSION['idUsuario'];

    $sql = "SELECT * FROM usuario WHERE id_usuario = :id_usuario";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(":id_usuario", $id_usuario);
    $stmt->execute();

    $fetch = $stmt->fetch(PDO::FETCH_ASSOC);

    $html = "<div class='welcome'>Bienvenido</div>";
    $html .= "<div class='userId'>Id: {$fetch['id_usuario']}</div>";
    $html .= "<div class='userName'>Nombre: {$fetch['nombres']}</div>";
    $html .= "<div class='userLastName'>Apellido: {$fetch['apellidos']}</div>";

    echo $html;
}

function genFormInscribirse()
{
    $materias = obtenerMaterias();

    $html = '';

    if (empty($materias)) {
        $html = 'No hay materias disponibles.';
    } else {
        $html .= '<form action="#" method="POST" class="genFormInscribirse">
    <div class="input_box">
        <label for="materia">Materia:</label>
        <select id="materia" name="materia" required>';

        foreach ($materias as $materia) {
            $html .= '<option value="' . $materia['id_materia'] . '">' . $materia['nombre_materia'] . '</option>';
        }

        $html .= '</select>
    </div>

    <input type="submit" value="Agregar" class="btn" name="addEstudianteMateria" />
    </form>';
    }

    echo $html;
}

$operacion = isset($_GET['operacion']) ? $_GET['operacion'] : null;

if (isset($_GET['operacion'])) {
    $conexion = new DB("localhost", "root", "josueg", "proyecto_php", 3306);
    $arraySemestres = $conexion->mostrarSemestres();
    session_start();
    switch ($operacion) {
        case 'crearInicio':
            return crearInicio();
        case 'crearMaterias':
            return genFormMaterias($arraySemestres, obtenerDocentes());
        case 'crearTareas':
            return genFormTareas();
        case 'mostrarTareas':
            $materia = buscarMateriaPorId($conexion->mostrarSemestres(), $_SESSION["id_materia"]);
            $tareas = $conexion->consultarTareasMateria($materia);
            return generarListaTareas($tareas);
        case 'crearParticipantes':
            return genParticipantes();
        case 'crearSemestres':
            return genFormSemestres();
        case 'crearCursos':
            return crearCursos($arraySemestres);
        case 'verCursos':
            return genFormCursos();
        case 'inscribirCursos':
            return genFormInscribirse();
        default:
            // Operación por defecto o manejo de error
            return '<div>Error: Operación no válida</div>';
    }

}