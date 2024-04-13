<?php
session_start();

// Destruir todas las variables de sesión
session_destroy();

// Redirigir al usuario a la página de registro
header("Location: register.php");
exit();
?>