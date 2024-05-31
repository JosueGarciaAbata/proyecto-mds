<?php
require_once 'template.php';

use dev\Template;

// Obtener la conexion, datos de la plantilla, etc.
// $db = DB::obtenerInstancia("localhost", "root", "josueg", "proyecto_modelamiento", "3306");
// $pdo = $db->getPDO();

// $tituloProyecto = $_POST['tituloProyecto'];
// $mensajeBienvenida = $_POST['$mensajeBienvenida'];
// $nombres = $_POST['nombres'];
// $apellidos = $_POST['apellidos'];
// $habilidadesTecnicas = $_POST['habilidadesTecnicas'];
// $habilidadesSociales = $_POST['habilidadesSociales'];
// $lang = "en";

// $datosPlantilla = [
//     'tituloProyecto' => $tituloProyecto,
//     'mensajeBienvenida' => $mensajeBienvenida,
//     'nombres' => $nombres,
//     'apellidos' => $apellidos,
//     'habilidadesTecnicas' => $habilidadesTecnicas,
//     'habilidadesSociales' => $habilidadesSociales
// ];

// // Enviar los datos a la base de datos.
// $statement = $pdo->prepare("INSERT INTO portafolios (nombre_apellido, habilidades_tecnicas, habilidades_sociales,
//                                                      educacion, sobre_mi, mensaje_bienvenida, 
//                                                      ubicacion_foto, ubicacion_cv, fecha_modificacion)

//                              VALUES (:nombre_apellido, :habilidades_tecnicas, :habilidades_sociales, :educacion, :sobre_mi,
//                                      :mensaje_bienvenida, :ubicacion_foto, :ubicacion_cv, :fecha_modificacion)");

$template = new Template('plantilla', $datosPlantilla);

$html = $template->render('plantilla.php', $datosPlantilla);

exit();