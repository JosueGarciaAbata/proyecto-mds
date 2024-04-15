<?php

require_once ('../../procesarInformacion/conexion.php');
$conexion = ConexionBD::obtenerInstancia()->obtenerConexion();

$titulo_proyecto = $conexion->real_escape_string($_POST['titulo-proyecto']);
$nombres = $conexion->real_escape_string($_POST['nombres']);
$apellidos = $conexion->real_escape_string($_POST['apellidos']);
$estudios = $conexion->real_escape_string($_POST['estudios']);
$sobre_mi = $conexion->real_escape_string($_POST['sobre-mi']);
$proyectos = $conexion->real_escape_string($_POST['proyectos']);