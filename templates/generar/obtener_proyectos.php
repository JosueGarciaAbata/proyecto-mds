<?php
require_once ('../../procesarInformacion/conexion.php');

$conexion = ConexionBD::obtenerInstancia()->obtenerConexion();

$sql = "SELECT titulo_proyecto, descripcion_proyecto FROM proyectos";
$resultado = $conexion->query($sql);

if ($resultado->num_rows > 0) {
    $proyectos = array();

    while ($fila = $resultado->fetch_assoc()) {
        $proyecto = array(
            "titulo" => $fila['titulo_proyecto'],
            "descripcion" => $fila["descripcion_proyecto"]
        );
        $proyectos[] = $proyecto;
    }

    header('Content-Type: application/json');
    echo json_encode($proyectos);
} else {
    echo "No se han encontrado proyectos.";
}

$conexion->close();