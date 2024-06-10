<?php
require_once ("../../../procesarInformacion/conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $conexion = ConexionBD::obtenerInstancia()->obtenerConexion();

    if (
        isset($_POST['action']) && $_POST['action'] == "get_data_projects_register" &&
        isset($_POST['proyectoId'])
    ) {
        echo json_encode(getProjectsRegisterComment($conexion, $_POST['proyectoId']));
    }
}

function getProjectsRegisterComment($conexion, $id_project)
{
    // Consulta para obtener los datos del proyecto y del usuario creador del proyecto
    $sql = "SELECT proyectos.*, usuarios.*
            FROM proyectos 
            LEFT JOIN usuarios ON proyectos.id_usuario_proyecto = usuarios.id_usuario
            WHERE proyectos.id_proyecto = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_project);
    $stmt->execute();
    $result = $stmt->get_result();

    // Obtener los datos del proyecto y del usuario creador del proyecto
    $projectData = $result->fetch_assoc();

    // Consulta para obtener los comentarios relacionados con el proyecto
    $sql = "SELECT comentarios_proyectos.*, usuarios.* 
            FROM comentarios_proyectos 
            LEFT JOIN usuarios ON comentarios_proyectos.id_usuario_comentario = usuarios.id_usuario
            WHERE comentarios_proyectos.id_estado_comentario = 1 
            AND comentarios_proyectos.id_proyecto_comentario = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_project);
    $stmt->execute();
    $result = $stmt->get_result();

    // Array para almacenar los comentarios
    $commentsData = [];
    while ($row = $result->fetch_assoc()) {
        $commentsData[] = $row;
    }

        // Consulta para obtener las etiquetas asociadas con el post
    $sql = "SELECT DISTINCT etiquetas_agrupadas_proyectos.*, etiquetas.*
        FROM etiquetas_agrupadas_proyectos
        LEFT JOIN etiquetas ON etiquetas_agrupadas_proyectos.id_etiqueta_etiquetas_agrupadas = etiquetas.id_etiqueta
        WHERE etiquetas_agrupadas_proyectos.id_proyecto_etiquetas_agrupadas = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $id_project);
        $stmt->execute();
        $result = $stmt->get_result();

    // Array para almacenar las etiquetas
    $tagsData = [];
    while ($row = $result->fetch_assoc()) {
        $tagsData[] = $row;
    }

    // Combinar los datos del proyecto y del usuario con los datos de los comentarios
    $projectData['comentarios'] = $commentsData;
    $projectData['etiquetas'] = $tagsData;


    // Devolver los datos combinados del proyecto y los comentarios
    return $projectData;
}
