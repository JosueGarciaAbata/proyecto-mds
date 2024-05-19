<?php 
require_once('funcionesComentarios.php');
require_once("../procesarInformacion/conexion.php");
$conexion = ConexionBD::obtenerInstancia()->obtenerConexion();

if (isset($_GET['id_post'])) {
    $id_post = intval($_GET['id_post']);
    $comentarios = obtenerComentariosPorPost($conexion, $id_post);
    if ($comentarios !== false) {
        echo json_encode($comentarios);
    } else {
        echo json_encode(["error" => "No se pudieron obtener los comentarios."]);
    }
} else {
    echo json_encode(["error" => "ID del post no recibido."]);
}
?>

