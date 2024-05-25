<?php
require_once ("../conexion.php");
session_start();

// En este caso al ser un usuario no registrado. El id de usuario siempre sera null. 
// Asi que siempre se ira por el else a insertarComentario_visitor
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $conexion = ConexionBD::obtenerInstancia()->obtenerConexion();
    $id_usuario = obtener_id_usuario();

    if (
        isset($_POST['action']) && $_POST['action'] == "insertComment" &&
        isset($_POST['projectId']) && isset($_POST['commentContent'])
    ) {
        $id_project = intval($_POST['projectId']);

        if (insertar_comentario_visitor($conexion, $id_project, $id_usuario, $_POST['commentContent'])) {
            echo "true";
        } else {
            echo "false";
        }
    }
}

function obtener_id_usuario()
{
    // Verificar si hay una sesión activa de usuario
    if (isset($_SESSION['user_id'])) {
        // Si hay una sesión activa, devolver el ID de usuario
        return $_SESSION['user_id'];
    } else {
        // Si no hay una sesión activa, devolver NULL
        return NULL;
    }
}

function insertar_comentario_visitor($conexion, $id_project, $id_usuario_Comentario, $contenidoComentario)
{
    // Preparar la consulta con el campo id_estado_comentario
    $stmt_insertar_comentario = $conexion->prepare("INSERT INTO comentarios_proyectos (id_proyecto_comentario, id_usuario_comentario, id_estado_comentario, contenido_comentario) VALUES (?, ?, 2, ?)");

    // Enlazar los parámetros
    $stmt_insertar_comentario->bind_param("iis", $id_project, $id_usuario_Comentario, $contenidoComentario);

    // Ejecutar la consulta
    if ($stmt_insertar_comentario->execute()) {
        return true;
    } else {
        // Imprimir el mensaje de error para depuración
        echo "Error al ejecutar la consulta: " . $stmt_insertar_comentario->error;
        return false;
    }
}
