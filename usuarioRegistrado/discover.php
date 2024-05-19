<?php
require_once ('header.php');
require_once ('navbar.php');
require_once ('funcionesComentarios.php');
require_once ('cargarComentarios.php');
$conexion = ConexionBD::obtenerInstancia()->obtenerConexion();
// Preparar la consulta SQL para seleccionar todos los posts con estado "visible"


// Verificar si se recibió el formulario de comentario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['contenido_comentario']) && isset($_POST['id_post_comentario'])) {
    // Verificar si el usuario está autenticado y obtener su ID de usuario

    // Obtener el ID de usuario de la sesión
    if (isset($_SESSION['user_id'])) {
        $id_usuario = $_SESSION['user_id'];
    } else {
        // Manejar la situación si el usuario no está autenticado
        echo "Error: Usuario no autenticado.";
        // Terminar el script
    }

    // Obtener los datos del formulario
    $comentario = $_POST['contenido_comentario'];
    $id_post_comentario = $_POST['id_post_comentario'];

    // Insertar el comentario en la base de datos
    $stmt_insertar_comentario = $conexion->prepare("INSERT INTO comentarios (id_post_comentario, id_usuario_comentario, id_estado_comentario, contenido_comentario) VALUES (?, ?, 1, ?)");
    $stmt_insertar_comentario->bind_param("iis", $id_post_comentario, $id_usuario, $comentario);

    $stmt_insertar_comentario->execute();

    if ($stmt_insertar_comentario) {
        echo "Comentario insertado correctamente.";
    } else {
        echo "Error al insertar el comentario: " . $conexion->error;
    }

    // $stmt_insertar_comentario->close();

    // Redirigir de vuelta al post después de insertar el comentario
    header("Location: discover.php");
    exit;
}


?>
<div class="page-wrapper">
    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            <div class="row justify-content-center post-container">
            </div>
        </div>
    </div>
</div>

<?php require_once ('footer.php'); ?>

<script src="js/comentary-handle.js" type="module"></script>