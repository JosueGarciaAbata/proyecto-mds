<?php
require_once('header.php'); 
require_once('navbar.php'); 
require_once('cargarComentarios.php');

require_once("../procesarInformacion/conexion.php");
$conexion = ConexionBD::obtenerInstancia()->obtenerConexion();
    $id_usuario = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
// Verificar si se ha enviado el formulario para eliminar o publicar un comentario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['eliminar_comentario'])) {
        $idComentario = $_POST['comentario_id'];
        if (eliminarComentario($conexion, $idComentario)) {
            echo "El comentario se eliminó correctamente.";
            echo "<script>hideComment($idComentario);</script>";
        } else {
            echo "Error al eliminar el comentario.";
        }
    } elseif (isset($_POST['publicar_comentario'])) {
        // Aquí puedes agregar la lógica para publicar el comentario
        // Por ejemplo, insertar el comentario en la base de datos
        $idComentario = $_POST['comentario_id'];
        // Aquí deberías realizar las acciones correspondientes para publicar el comentario
        echo "Comentario publicado correctamente.";
        echo "<script>hideComment($idComentario);</script>";
    }
}

function obtenerPostsPorUsuario($conexion, $idUsuario) {
    $stmt_posts = $conexion->prepare("SELECT id_post FROM posts WHERE id_usuario_post = ?");
    $stmt_posts->bind_param("i", $idUsuario);
    $stmt_posts->execute();
    $result_posts = $stmt_posts->get_result();
    $posts = $result_posts->fetch_all(MYSQLI_ASSOC);
    $stmt_posts->close();
    return $posts;
}

// Obtener posts del mismo usuario
$posts = obtenerPostsPorUsuario($conexion, $id_usuario);

?>

<?php foreach ($posts as $post): ?>
    <div class="comment" id="comment-<?php echo $post['id_post']; ?>">
        <div class="comment-content">
            <p><strong>ID del Post: </strong><?php echo $post['id_post']; ?></p>
            <!-- Aquí puedes mostrar otros campos del post si los necesitas -->
        </div>
        <!-- Aquí mostramos los comentarios -->
        <?php $comentarios = obtenerComentariosPorPost($conexion, $post['id_post']); ?>
        <?php foreach ($comentarios as $comentario): ?>
            <div class="comment">
                <p><strong>Comentario: </strong><?php echo $comentario['contenido_comentario']; ?></p>
                <!-- Botones para eliminar y publicar comentarios -->
                <div class="comment-actions">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" style="display: inline;">
                        <input type="hidden" name="comentario_id" value="<?php echo $comentario['id_comentario']; ?>">
                        <button type="submit" name="eliminar_comentario" class="btn btn-danger">Eliminar</button>
                    </form>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" style="display: inline;">
                        <input type="hidden" name="comentario_id" value="<?php echo $comentario['id_comentario']; ?>">
                        <button type="submit" name="publicar_comentario" class="btn btn-success">Publicar</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endforeach; ?>

<?php require_once('footer.php'); ?>
