<?php

require_once("conexion.php");

$conexion = ConexionBD::obtenerInstancia()->obtenerConexion();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  echo json_encode(crearCuenta($conexion,$_POST['register_name'],$_POST['register_mail'],$_POST['register_password']));



}


function crearCuenta($conexion,$name,$email,$password){

$sql_correo = "SELECT * FROM usuarios WHERE correo_electronico_usuario = ?";
$stmt_correo = $conexion->prepare($sql_correo);
$stmt_correo->bind_param("s", $email);
$stmt_correo->execute();
$resultado_correo = $stmt_correo->get_result();

$sql_nombre = "SELECT * FROM usuarios WHERE nombre_usuario = ?";
$stmt_nombre = $conexion->prepare($sql_nombre);
$stmt_nombre->bind_param("s", $name);
$stmt_nombre->execute();
$resultado_nombre = $stmt_nombre->get_result();
if ($resultado_correo->num_rows > 0) {
  $resultado = false;
} elseif ($resultado_nombre->num_rows > 0) {
  $resultado = false;
} else {
  // No hay usuarios con el mismo correo electrónico ni nombre de usuario
  $sql_insert = "INSERT INTO usuarios (nombre_usuario, correo_electronico_usuario, contrasenia_usuario) VALUES (?, ?, ?)";
  $stmt_insert = $conexion->prepare($sql_insert);
  $stmt_insert->bind_param("sss", $name, $email, $password);
  $stmt_insert->execute();
  $user_id = $stmt_insert->insert_id;

  session_start();

  $_SESSION['user_id'] = $user_id;
  $_SESSION['user_name'] = $name;

  $resultado = true;
}

return $resultado;

}























?>