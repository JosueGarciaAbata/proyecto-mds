<?php
require_once ("../conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $conexion = ConexionBD::obtenerInstancia()->obtenerConexion();

    if (
        isset($_POST['action']) && $_POST['action'] == "get_data_post" &&
        isset($_POST['postId'])
    ) {
        echo json_encode(getPostsData($conexion, $_POST['postId']));
    }
}
function getPostsData($conexion, $id_post)
{
    $sql = "SELECT posts.*, usuarios.*, comentarios.*
        FROM posts 
        LEFT JOIN usuarios ON posts.id_usuario_post = usuarios.id_usuario
        LEFT JOIN (SELECT * FROM comentarios WHERE id_estado_comentario = 1) AS comentarios ON posts.id_post = comentarios.id_post_comentario
        WHERE posts.id_estado_post = 1 
        AND posts.id_post = ?
        ORDER BY posts.id_post DESC";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_post);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    return $data;
}