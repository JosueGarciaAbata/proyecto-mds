<?php

function sendHabilidades($conexion)
{
    $sql = "SELECT id_habilidades, nombre_habilidades, tipo_habilidades FROM habilidades";
    $stmt_userData = $conexion->prepare($sql);
    $stmt_userData->execute();

    $result_userData = $stmt_userData->get_result();

    if ($result_userData->num_rows < 1) {
        return [];
    }

    $habilidades = [];
    $habilidadesTecnicas = [];
    $habilidadesSociales = [];
    while ($row = $result_userData->fetch_assoc()) {
        $habilidad = [
            "id" => $row["id_habilidades"],
            "nombre" => $row["nombre_habilidades"],
            "tipo" => $row["tipo_habilidades"]
        ];
        if ($row["tipo_habilidades"] == 1) {
            $habilidadesTecnicas[] = $habilidad;
        } else {
            $habilidadesSociales[] = $habilidad;
        }

    }
    return array("habilidadesTecnicas" => $habilidadesTecnicas, "habilidadesSociales" => $habilidadesSociales);
}