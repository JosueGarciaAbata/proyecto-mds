<?php
require_once ('../../../procesarInformacion/conexion.php');

$conexion = ConexionBD::obtenerInstancia()->obtenerConexion();

$sql = "SELECT nombre_apellido_portafolio, sobre_mi_portafolio FROM portafolios";
$resultado = $conexion->query($sql);

if ($resultado->num_rows > 0) {
    $portafolios = array();

    while ($fila = $resultado->fetch_assoc()) {
        $portafolio = array(
            "nombre_apellido" => $fila['nombre_apellido_portafolio'],
            "sobre_mi" => $fila["sobre_mi_portafolio"]
        );
        $portafolios[] = $portafolio;
    }

    header('Content-Type: application/json');
    echo json_encode($portafolios);
} else {
    header('Content-Type: application/json');
    echo json_encode(array("mensaje" => "No se han encontrado proyectos."));
}

$conexion->close();