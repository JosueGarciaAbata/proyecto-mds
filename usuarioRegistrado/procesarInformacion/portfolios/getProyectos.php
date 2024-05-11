<?php
function getProjectsById($id = 0)
{
    $conexion = ConexionBD::obtenerInstancia()->obtenerConexion();

    $sql =
        "SELECT 
        p.id_proyecto, p.id_categoria_proyecto, p.id_estado_proyecto, p.titulo_proyecto, 
        p.descripcion_proyecto, p.fecha_inicio_proyecto, p.fecha_finalizacion_proyecto 
        FROM proyectos p WHERE id_usuario_proyecto = ?";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    // Obtener los datos de las habilidades
    $proyectos = [];

    while ($row = $result->fetch_assoc()) {
        $proyectos[] = [
            "id" => $row["id_proyecto"],
            "idCategoria" => $row["id_categoria_proyecto"],
            "estado" => $row["id_estado_proyecto"],
            "titulo" => $row["titulo_proyecto"],
            "descripcion" => $row["descripcion_proyecto"],
            "fechaInicio" => $row["fecha_inicio_proyecto"],
            "fechaFin" => $row["fecha_finalizacion_proyecto"],
        ];

    }
    $stmt->close();
    return $proyectos;
}

