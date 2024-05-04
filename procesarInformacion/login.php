<?php

require_once ("conexion.php");

$conexion = ConexionBD::obtenerInstancia()->obtenerConexion();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  //Enviar resultaldo del proceso
  echo json_encode(validateAccount($conexion, $_POST['login_email'], $_POST['login_password']));



}


function validateAccount($conexion, $email, $password)
{
  $passwordText = $password;

  //Encriptar contraseña
  $password = hash("sha256", $password);


  //Buscar  existencia de la cuenta
  $sql = "SELECT * FROM usuarios WHERE correo_electronico_usuario = ? AND contrasenia_usuario=? ";
  $stmt = $conexion->prepare($sql);
  $stmt->bind_param("ss", $email, $password);
  $stmt->execute();
  $result = $stmt->get_result();


  //Verficar existencia de la cuenta
  if ($result->num_rows > 0) {

    $row = $result->fetch_assoc();
    $user_id = $row['id_usuario'];

    //Generar una sesion para el usuario
    session_start();
    $_SESSION['password_user'] = $passwordText;
    $_SESSION['user_id'] = $user_id;
    $response = true;

  } else {

    $response = false;

  }

  return $response;



}






