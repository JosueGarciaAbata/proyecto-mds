<?php
require_once 'Template.php';
use dev\template;

$titulo = $_POST['nombre'] ?? '';
$descripcion = $_POST['descripcion'] ?? '';
$fechaInicio = $_POST['fecha_inicio'] ?? '';
$fechaFin = $_POST['fecha_fin'] ?? '';
$lang = "en";

$datosPlantilla = [
    'titulo' => $titulo,
    'descripcion' => $descripcion,
    'fechaInicio' => $fechaInicio,
    'fechaFin' => $fechaFin,
    'lang' => $lang
];

$template = new Template('generar', $datosPlantilla);

$html = $template->render('plantilla.php', $datosPlantilla);

header('Location: pagina_generada.php?html=' . urlencode($html));
exit();