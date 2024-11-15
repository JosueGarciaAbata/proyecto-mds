<?php
require_once ("../conexion.php");


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $conexion = ConexionBD::obtenerInstancia()->obtenerConexion();

    if (isset($_POST['action']) && $_POST['action'] == "getPostsVisitor") {
        echo json_encode(getPosts($conexion));
    }
}
function getPosts($conexion)
{
    $sql = "SELECT posts.*, usuarios.nombre_usuario, usuarios.ubicacion_foto_perfil_usuario
        FROM posts 
        INNER JOIN usuarios ON posts.id_usuario_post = usuarios.id_usuario 
        WHERE posts.id_estado_post = 1
        ORDER BY posts.id_post DESC";
    $stmt = $conexion->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    return $data;
}