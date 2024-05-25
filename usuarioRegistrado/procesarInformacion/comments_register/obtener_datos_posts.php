<?php
require_once ("../../../procesarInformacion/conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $conexion = ConexionBD::obtenerInstancia()->obtenerConexion();

    if (
        isset($_POST['action']) && $_POST['action'] == "get_data_post_register" &&
        isset($_POST['postId'])
    ) {
        echo json_encode(getPostsRegisterComment($conexion, $_POST['postId']));
    }
}

function getPostsRegisterComment($conexion, $id_post)
{
    // Consulta para obtener los datos del post y del usuario creador del post
    $sql = "SELECT posts.*, usuarios.*
            FROM posts 
            LEFT JOIN usuarios ON posts.id_usuario_post = usuarios.id_usuario
            WHERE posts.id_estado_post = 1 
            AND posts.id_post = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_post);
    $stmt->execute();
    $result = $stmt->get_result();

    // Obtener los datos del post y del usuario creador del post
    $postData = $result->fetch_assoc();

    // Consulta para obtener los comentarios relacionados con el post
    $sql = "SELECT comentarios.*, usuarios.* 
            FROM comentarios 
            LEFT JOIN usuarios ON comentarios.id_usuario_comentario = usuarios.id_usuario
            WHERE comentarios.id_estado_comentario = 1 
            AND comentarios.id_post_comentario = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_post);
    $stmt->execute();
    $result = $stmt->get_result();

    // Array para almacenar los comentarios
    $commentsData = [];
    while ($row = $result->fetch_assoc()) {
        $commentsData[] = $row;
    }

    // Combinar los datos del post y del usuario con los datos de los comentarios
    $postData['comentarios'] = $commentsData;

    // Devolver los datos combinados del post y los comentarios
    return $postData;
}
