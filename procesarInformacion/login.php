<?php

require_once ("conexion.php");

$conexion = ConexionBD::obtenerInstancia()->obtenerConexion();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  echo json_encode(validateAccount($conexion, $_POST['login_email'], $_POST['login_password']));



}


function validateAccount($conexion, $email, $password)
{

  $password = hash("sha256", $password);

  $sql = "SELECT * FROM usuarios WHERE correo_electronico_usuario = ? AND contrasenia_usuario=? ";
  $stmt = $conexion->prepare($sql);
  $stmt->bind_param("ss", $email, $password);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $resultado = false;
    $row = $result->fetch_assoc();
    $user_id = $row['id_usuario'];
    session_start();

    $_SESSION['user_id'] = $user_id;
    $response = true;

  } else {

    $response = false;

  }

  return $response;



}






