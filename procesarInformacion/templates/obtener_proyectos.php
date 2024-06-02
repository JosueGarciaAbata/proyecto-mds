<?php

header("Content-Type: application/json");
require_once ('../procesarInformacion/conexion.php');
$conexion = ConexionBD::obtenerInstancia()->obtenerConexion();


if (!empty($_GET['id-portfolio'])) {
    getProjectData($conexion, $_GET['id-portfolio']);
}else{
    http_response_code(400);
    echo json_encode(["status" => "BAD_REQUEST", "statusText" => "Portfolio no exist"]);
}

function getProjectData($conexion, $id) {
    
    $sql = 'SELECT 
                p.titulo_proyecto AS title, 
                p.descripcion_proyecto AS description, 
                p.id_categoria_proyecto AS category, 
                p.ubicacion_imagen_proyecto AS ubic_img, 
                p.fecha_inicio_proyecto AS date_init, 
                p.fecha_finalizacion_proyecto AS date_end
            FROM 
                proyectos_agrupados_portafolio pa
            JOIN
                proyectos p ON pa.id_proyecto_proyectos_agrupados_portafolio = p.id_proyecto
            WHERE 
                pa.id_portafolio_proyectos_agrupados_portafolio = ?';

    $stmt_projectData = $conexion->prepare($sql);
    $stmt_projectData->bind_param("s", $id);
    $stmt_projectData->execute();
    $result_projectData = $stmt_projectData->get_result()->fetch_all(MYSQLI_ASSOC);

    if (empty($result_projectData)) {
        http_response_code(404);
        echo json_encode(["status" => "NOT_FOUND", "statusText" => "No projects found for this portfolio"]);
        exit();
    }
    foreach ($result_projectData as &$project) {
        $project['ubic_img'] = "../" . $project['ubic_img'];
    }
    http_response_code(200);
    echo json_encode($result_projectData);
    $stmt_projectData->close();
}
