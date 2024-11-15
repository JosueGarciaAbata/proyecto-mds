<?php
require_once("../procesarInformacion/conexion.php");
$conexion = ConexionBD::obtenerInstancia()->obtenerConexion();

function insertarComentario($conexion, $idPost, $idUsuarioComentario, $contenidoComentario) {
    // Preparar la consulta con el campo id_estado_comentario
    $stmt_insertar_comentario = $conexion->prepare("INSERT INTO comentarios (id_post_comentario, id_usuario_comentario, id_estado_comentario, contenido_comentario) VALUES (?, ?, 2, ?)");

    // Enlazar los parámetros
    $stmt_insertar_comentario->bind_param("iis", $idPost, $idUsuarioComentario, $contenidoComentario);
    
    // Ejecutar la consulta
    if ($stmt_insertar_comentario->execute()) {
        return true;
    } else {
        // Imprimir el mensaje de error para depuración
        echo "Error al ejecutar la consulta: " . $stmt_insertar_comentario->error;
        return false;
    }

    // Cerrar la declaración
    $stmt_insertar_comentario->close();
}

function obtenerComentariosPorPost($conexion, $idPost) {
    $stmt = $conexion->prepare("SELECT * FROM comentarios WHERE id_post_comentario = ?");
    if ($stmt === false) {
        error_log("Error en la preparación de la consulta: " . $conexion->error);
        return false;
    }
    $stmt->bind_param("i", $idPost);
    if (!$stmt->execute()) {
        error_log("Error en la ejecución de la consulta: " . $stmt->error);
        return false;
    }
    $result = $stmt->get_result();
    if ($result === false) {
        error_log("Error en la obtención de resultados: " . $stmt->error);
        return false;
    }
    $comentarios = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $comentarios;
}

 function publicarComentariosVisibles($conexion, $idEstadoComentario) {
    $stmt_publicar_comentarios = $conexion->prepare("SELECT * FROM comentarios WHERE id_estado_comentario = ?");
    $stmt_publicar_comentarios->bind_param("i", $idEstadoComentario);
    if ($stmt_publicar_comentarios->execute()) {
        $result = $stmt_publicar_comentarios->get_result();
        $comentarios = $result->fetch_all(MYSQLI_ASSOC);
        $stmt_publicar_comentarios->close();
        return $comentarios;
    } else {
        return false;
    }
}

?>