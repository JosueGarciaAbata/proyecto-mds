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

 function eliminarComentario($conexion, $idComentario){
    $stmt_eliminar_comentario = $conexion->prepare("UPDATE comentarios SET id_estado_comentario = 2 WHERE id_comentario = ?");
    $stmt_eliminar_comentario->bind_param("i", $idComentario);
    if ($stmt_eliminar_comentario->execute()) {
        return true;
    } else {
        return false;
    }

    $stmt_eliminar_comentario->close();

 }
 function publicarComentario($conexion, $idComentario){
    $stmt_publicar_comentario = $conexion->prepare("UPDATE comentarios SET id_estado_comentario = 1 WHERE id_comentario = ?");
    $stmt_publicar_comentario->bind_param("i", $idComentario);
    if ($stmt_publicar_comentario->execute()) {
        return true;
    } else {
        return false;
    }

    $stmt_publicar_comentario->close();
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


function encontrarUsuarioComentario($conexion, $idUsuario) {
    // Verificar si el idUsuario es 0 y devolver "Anonimo"
    if ($idUsuario == 0) {
        return "Anonimo";
    }
    
    // Preparar la consulta SQL
    $stmt_encontrar_usuario = $conexion->prepare("SELECT nombre_usuario FROM usuarios WHERE id_usuario = ?");
    
    // Vincular el parámetro a la consulta
    $stmt_encontrar_usuario->bind_param("i", $idUsuario);
    
    // Ejecutar la consulta
    if ($stmt_encontrar_usuario->execute()) {
        // Obtener el resultado de la consulta
        $resultado = $stmt_encontrar_usuario->get_result();
        // Verificar si se encontró un usuario
        if ($resultado->num_rows > 0) {
            $usuario = $resultado->fetch_assoc();
            $stmt_encontrar_usuario->close();
            return $usuario['nombre_usuario'];
        } else {
            // Cerrar la declaración y devolver false si no se encontró ningún usuario
            $stmt_encontrar_usuario->close();
            return false;
        }
    } else {
        // Devolver false en caso de falla en la ejecución de la consulta
        return false;
    }
}

?>
