<?php
require_once ("../../../procesarInformacion/conexion.php");
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $conexion = ConexionBD::obtenerInstancia()->obtenerConexion();

    if (
        isset($_POST['action']) && $_POST['action'] == "getProjectsRegister" &&
        isset($_SESSION['user_id'])
    ) {
        $id_usuario = intval($_SESSION['user_id']);
        echo json_encode(getProjectsRegister($conexion, $id_usuario));
    }
}

function getProjectsRegister($conexion, $id_usuario)
{
    $sql = "SELECT proyectos.*, usuarios.nombre_usuario, usuarios.ubicacion_foto_perfil_usuario
            FROM proyectos
            INNER JOIN usuarios ON proyectos.id_usuario_proyecto = usuarios.id_usuario 
            WHERE (proyectos.id_estado_proyecto = 1 OR proyectos.id_usuario_proyecto = ?)
            ORDER BY proyectos.id_proyecto DESC";
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