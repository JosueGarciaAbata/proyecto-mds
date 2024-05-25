<?php
require_once ("../conexion.php");


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $conexion = ConexionBD::obtenerInstancia()->obtenerConexion();

    if (isset($_POST['action']) && $_POST['action'] == "getProjectsVisitor") {
        echo json_encode(getProjects($conexion));
    }
}
function getProjects($conexion)
{
    $sql = "SELECT proyectos.*, usuarios.nombre_usuario, usuarios.ubicacion_foto_perfil_usuario
        FROM proyectos 
        INNER JOIN usuarios ON proyectos.id_usuario_proyecto = usuarios.id_usuario 
        WHERE proyectos.id_estado_proyecto = 1
        ORDER BY proyectos.id_proyecto DESC";
    $stmt = $conexion->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    return $data;
}