<?php

require_once("conexion.php");

$conexion = ConexionBD::obtenerInstancia()->obtenerConexion();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  echo json_encode(crearCuenta($conexion,$_POST['register_name'],$_POST['register_mail'],$_POST['register_password']));



}


function crearCuenta($conexion,$name,$email,$password){

$sql= "SELECT * FROM usuarios WHERE correo_electronico_usuario = ? AND nombre_usuario=?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ss", $email,$password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
  $resultado = false;
}  else {

  $fotoPerfilDefecto="../img/defaulAvatar.png";
  $randomNumbers = mt_rand(1000, 9999);

  $carpetaUsuario = "../usersContent/$name$randomNumbers";
  if (!mkdir($carpetaUsuario)) {

   return false;
 
  }
  $sql_insert = "INSERT INTO usuarios (nombre_usuario, correo_electronico_usuario, contrasenia_usuario,ubicacion_foto_perfil_usuario,carpeta_usuario) VALUES (?, ?, ?,?,?)";
  $stmt_insert = $conexion->prepare($sql_insert);
  $stmt_insert->bind_param("sssss", $name, $email, $password,$fotoPerfilDefecto,$carpetaUsuario);
  $stmt_insert->execute();
  $user_id = $stmt_insert->insert_id;

  session_start();

  $_SESSION['user_id'] = $user_id;

  $resultado = true;

}

return $resultado;



}























?>