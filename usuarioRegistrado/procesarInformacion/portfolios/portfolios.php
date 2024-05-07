<?php
require_once ("../../../procesarInformacion/conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conexion = ConexionBD::obtenerInstancia()->obtenerConexion();
    session_start();
    $user_id = $_SESSION['user_id'];

    if (isset($_POST['action']) && $_POST['action'] == 'getProjects') {
        echo json_encode(getProjects($conexion, $user_id));
    }
}

function getProjects($conexion, $user_id)
{
    $sql = "SELECT * FROM proyectos WHERE id_usuario_proyecto = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $projectsArray = [];

    while ($row = $result->fetch_assoc()) {
        $projectsArray[] = $row;
    }
    $stmt->close();
    return $projectsArray;
}