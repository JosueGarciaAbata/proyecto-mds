<?php
require_once ("../conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $conexion = ConexionBD::obtenerInstancia()->obtenerConexion();

    if (
        isset($_POST['action']) && $_POST['action'] == "get_data_projects" &&
        isset($_POST['proyectoId'])
    ) {
        echo json_encode(getProjectsData($conexion, $_POST['proyectoId']));
    }
}
function getProjectsData($conexion, $id_projects)
{
    $sql = "SELECT proyectos.*, usuarios.*, comentarios.*
        FROM proyectos
        LEFT JOIN usuarios ON proyectos.id_usuario_proyecto = usuarios.id_usuario
        LEFT JOIN (SELECT * FROM comentarios_proyectos 
        WHERE id_estado_comentario = 1) AS comentarios ON proyectos.id_proyecto = comentarios.id_proyecto_comentario
        WHERE proyectos.id_estado_proyecto = 1 
        AND proyectos.id_proyecto = ?
        ORDER BY proyectos.id_proyecto DESC";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_projects);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    return $data;
}