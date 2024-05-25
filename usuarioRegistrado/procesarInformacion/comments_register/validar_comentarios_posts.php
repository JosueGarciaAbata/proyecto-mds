<?php
require_once ("../../../procesarInformacion/conexion.php");
session_start();

// En este punto todo lo de aqui ya se realiza para usuario registrado. Es decir, se podria
// omitir el metodo obtener_id_usuario(), pero lo dejo por si acaso.

if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    $conexion = ConexionBD::obtenerInstancia()->obtenerConexion();
    $id_usuario = obtener_id_usuario();

    if (
        isset($_POST['action']) && $_POST['action'] == "insertComment" &&
        isset($_POST['postId']) && isset($_POST['commentContent'])
    ) {
        $id_post = intval($_POST['postId']);

        // Ver si el id del usuario actual le pertenece el post que esta siendo editado.
        if (usuario_es_propietario_del_post($conexion, $id_post, $id_usuario)) {
            // Si el usuario actual es propietario del post, permitir comentario
            if (insertar_comentario_register_user_first_type($conexion, $id_post, $id_usuario, $_POST['commentContent'])) {
                echo "trueRegister";
            } else {
                echo "false";
            }
        } else {
            if (insertar_comentario_register_user_second_type($conexion, $id_post, $id_usuario, $_POST['commentContent'])) {
                echo "true";
            } else {
                echo "false";
            }
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

function usuario_es_propietario_del_post($conexion, $id_post, $id_usuario_actual)
{
    $id_usuario_propietario = null;

    // Preparar la consulta para obtener el ID del usuario propietario del post
    $stmt = $conexion->prepare("SELECT id_usuario_post FROM posts WHERE id_post = ?");
    $stmt->bind_param("i", $id_post);
    $stmt->execute();
    $stmt->bind_result($id_usuario_propietario);
    $stmt->fetch();
    $stmt->close();

    // Verificar si el ID del usuario actual coincide con el ID del usuario propietario del post
    return $id_usuario_actual === $id_usuario_propietario;
}

function insertar_comentario_register_user_first_type($conexion, $id_post, $id_usuario_comentario, $contenidoComentario)
{
    // Preparar la consulta con el campo id_estado_comentario
    $stmt_insertar_comentario = $conexion->prepare("INSERT INTO comentarios (id_post_comentario, id_usuario_comentario, id_estado_comentario, contenido_comentario) VALUES (?, ?, 1, ?)");

    // Enlazar los parámetros
    $stmt_insertar_comentario->bind_param("iis", $id_post, $id_usuario_comentario, $contenidoComentario);

    // Ejecutar la consulta
    if ($stmt_insertar_comentario->execute()) {
        return true;
    } else {
        // Imprimir el mensaje de error para depuración
        echo "Error al ejecutar la consulta: " . $stmt_insertar_comentario->error;
        return false;
    }

}

function insertar_comentario_register_user_second_type($conexion, $id_post, $id_usuario_Comentario, $contenidoComentario)
{
    // Preparar la consulta con el campo id_estado_comentario
    $stmt_insertar_comentario = $conexion->prepare("INSERT INTO comentarios (id_post_comentario, id_usuario_comentario, id_estado_comentario, contenido_comentario) VALUES (?, ?, 2, ?)");

    // Enlazar los parámetros
    $stmt_insertar_comentario->bind_param("iis", $id_post, $id_usuario_Comentario, $contenidoComentario);

    // Ejecutar la consulta
    if ($stmt_insertar_comentario->execute()) {
        return true;
    } else {
        // Imprimir el mensaje de error para depuración
        echo "Error al ejecutar la consulta: " . $stmt_insertar_comentario->error;
        return false;
    }
}
