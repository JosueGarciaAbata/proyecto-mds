<?php
require_once ("../../../procesarInformacion/conexion.php");
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $conexion = ConexionBD::obtenerInstancia()->obtenerConexion();

    if (
        isset($_POST['action']) && $_POST['action'] == "getPostsRegister" &&
        isset($_SESSION['user_id'])
    ) {
        $id_usuario = intval($_SESSION['user_id']);
        echo json_encode(getPostsRegister($conexion, $id_usuario));
    }
}

function getPostsRegister($conexion, $id_usuario)
{
    $sql = "SELECT posts.*, usuarios.nombre_usuario, usuarios.ubicacion_foto_perfil_usuario
            FROM posts 
            INNER JOIN usuarios ON posts.id_usuario_post = usuarios.id_usuario 
            WHERE (posts.id_estado_post = 1 OR posts.id_usuario_post = ?)
            ORDER BY posts.id_post DESC";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    return $data;
}