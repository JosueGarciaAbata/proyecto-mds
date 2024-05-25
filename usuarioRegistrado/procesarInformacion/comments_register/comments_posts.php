<?php
require_once ("../../../procesarInformacion/conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $conexion = ConexionBD::obtenerInstancia()->obtenerConexion();

    if (isset($_POST['action']) && $_POST['action'] == "getPostsRegister") {
        echo json_encode(getPostsRegister($conexion));
    }
}

function getPostsRegister($conexion)
{
    $sql = "SELECT posts.*, usuarios.nombre_usuario, usuarios.ubicacion_foto_perfil_usuario
        FROM posts 
        INNER JOIN usuarios ON posts.id_usuario_post = usuarios.id_usuario 
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