<?php 
require_once('header.php'); 
require_once('navbar.php'); 
require_once("../procesarInformacion/conexion.php");
require_once('funcionesComentarios.php');

$conexion = ConexionBD::obtenerInstancia()->obtenerConexion();
$id_usuario = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['eliminar_comentario'])) {
        $idComentario = $_POST['comentario_id'];
        if (eliminarComentario($conexion, $idComentario)) {
            echo "<script>alert('El comentario se eliminó correctamente.');</script>";
            echo "<script>window.location.href = window.location.href;</script>";
        } else {
            echo "<script>alert('Error al eliminar el comentario.');</script>";
        }
    } elseif (isset($_POST['publicar_comentario'])) {
        $idComentario = $_POST['comentario_id'];
        if (publicarComentario($conexion, $idComentario)) {
            echo "<script>alert('Comentario publicado correctamente.');</script>";
            echo "<script>window.location.href = window.location.href;</script>";
        } else {
            echo "<script>alert('Error al publicar el comentario.');</script>";
        }
    }
}

function obtenerPostsPorUsuario($conexion, $idUsuario) {
    $stmt_posts = $conexion->prepare("SELECT id_post, titulo_post FROM posts WHERE id_usuario_post = ?");
    $stmt_posts->bind_param("i", $idUsuario);
    $stmt_posts->execute();
    $result_posts = $stmt_posts->get_result();
    $posts = $result_posts->fetch_all(MYSQLI_ASSOC);
    $stmt_posts->close();
    return $posts;
}

$posts= obtenerPostsPorUsuario($conexion, $id_usuario);
?>

<div class="container">
    <h2>Administrar Comentarios</h2>
    <?php foreach ($posts as $post): ?>
        <div class="post">
            <h3><?php echo htmlspecialchars($post['titulo_post']); ?></h3>
            <?php $comentarios = obtenerComentariosPorPost($conexion, $post['id_post']); ?>
            <?php if ($comentarios): ?>
                <?php foreach ($comentarios as $comentario): ?>
                    <div class="comment" id="comment-<?php echo $comentario['id_comentario']; ?>">
                        <p><strong>ID del Comentario: </strong><?php echo $comentario['id_comentario']; ?></p>
                        <p><?php echo htmlspecialchars($comentario['contenido_comentario']); ?></p>
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
            <?php else: ?>
                <p>No hay comentarios para este post.</p>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>

<?php require_once('footer.php'); ?>

<script>
function hideComment(idComentario) {
    document.getElementById('comment-' + idComentario).style.display = 'none';
}
</script>

