<?php
require_once ('../../../procesarInformacion/conexion.php');
use dev\Template;

require_once ('../../../templates/template.php');

$conexion = ConexionBD::obtenerInstancia()->obtenerConexion();

$carpeta_usuario = '../../../usuarioPrueba/';

// Obtener el número de portafolios existentes
$portafolios_existentes = array_filter(glob($carpeta_usuario . 'portafolio*'), 'is_dir');
$num_portafolios = count($portafolios_existentes);

// Crear el nombre único para la nueva carpeta de portafolio
$nueva_carpeta = $carpeta_usuario . 'portafolio' . ($num_portafolios + 1) . '/';

// Crear la carpeta del nuevo portafolio
mkdir($nueva_carpeta);

// Obtener la ruta temporal del archivo subido
$ruta_temporal = $_FILES['foto-perfil']['tmp_name'];
// Obtener el nombre original del archivo
$nuevo_nombre_imagen = "Fotografia.jpg";

// Mover el archivo a la nueva carpeta
$ruta_destino = $nueva_carpeta . $nuevo_nombre_imagen;
move_uploaded_file($ruta_temporal, $ruta_destino);

$ruta_completa_imagen = $ruta_destino;

$titulo_portafolio = $conexion->real_escape_string($_POST['titulo-proyecto']);
$nombres = $conexion->real_escape_string($_POST['nombres']);
$apellidos = $conexion->real_escape_string($_POST['apellidos']);
$estudios = $conexion->real_escape_string($_POST['estudios']);
$sobre_mi = $conexion->real_escape_string($_POST['sobre-mi']);
$proyectos = $_POST['proyectos'];

$nombres_apellidos = $nombres . $apellidos;
$habilidades_sociales = "Datos de prueba...";
$habilidades_tecnicas = "Datos de prueba...";
$mensaje_bienvenida = "Datos de prueba...";
$id_usuario = "55";

$ruta_imagen = "../../usuarioPrueba/portafolio" . ($num_portafolios + 1) . "/" . $nuevo_nombre_imagen;

$datos_plantilla = [
    'titulo_portafolio' => $titulo_portafolio,
    'ruta_imagen' => $ruta_imagen,
    'nombres' => $nombres,
    'apellidos' => $apellidos,
    'estudios' => $estudios,
    'sobre_mi' => $sobre_mi,
    'proyectos' => $proyectos
];

$template = new Template('../../../templates/generar', $datos_plantilla);
$html_generado = $template->render('plantilla.php');

// Crear un nombre único para el archivo HTML
$nombre_archivo_html = 'portafolio.html'; // Puedes usar time() para generar un nombre único

// Guardar el HTML generado en un archivo en la carpeta del usuario
$ruta_archivo_html = $carpeta_usuario . 'portafolio' . ($num_portafolios + 1) . '/' . $nombre_archivo_html;
file_put_contents($ruta_archivo_html, $html_generado);


$sql = "INSERT INTO portafolios (nombre_apellido_portafolio, id_usuario_portafolio, habilidades_portafolio, habilidades_sociales_portafolio, 
                    educacion_portafolio, sobre_mi_portafolio, mensaje_bienvenida_portafolio,
                    ubicacion_foto_portafolio, ubicacion_cv_portafolio, ruta_pagina)
        VALUES ('$nombres_apellidos', '$id_usuario','$habilidades_sociales', '$habilidades_tecnicas',
        '$estudios', '$sobre_mi', '$mensaje_bienvenida', '$ruta_completa_imagen', '$ruta_completa_imagen', '$ruta_archivo_html')";

if ($conexion->query($sql)) {
    $id = $conexion->insert_id;
}

header('Location: ../index.php');
