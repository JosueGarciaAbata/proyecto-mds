<?php
require_once ('header.php');
require_once ('navbar.php');
require_once ("../procesarInformacion/conexion.php");
require_once ('funciones_comentarios.php');

$conexion = ConexionBD::obtenerInstancia()->obtenerConexion();
$id_usuario = $_SESSION['user_id'];
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['eliminar_comentario'])) {
        $idComentario = $_POST['comentario_id'];
        if (eliminarComentario($conexion, $idComentario)) {
            $mensaje = "El comentario se ocultó correctamente.";
        } else {
            $mensaje = "Error al ocultar el comentario.";
        }
    } elseif (isset($_POST['publicar_comentario'])) {
        $idComentario = $_POST['comentario_id'];
        if (publicarComentario($conexion, $idComentario)) {
            $mensaje = "Comentario publicado correctamente.";
        } else {
            $mensaje = "Error al publicar el comentario.";
        }
    }
}

function obtenerPostsPorUsuario($conexion, $idUsuario)
{
    $stmt_posts = $conexion->prepare("SELECT id_post, titulo_post 
                                      FROM posts 
                                      WHERE id_usuario_post = ?");
    $stmt_posts->bind_param("i", $idUsuario);
    $stmt_posts->execute();
    $result_posts = $stmt_posts->get_result();
    $posts = $result_posts->fetch_all(MYSQLI_ASSOC);
    $stmt_posts->close();
    return $posts;
}

$posts = obtenerPostsPorUsuario($conexion, $id_usuario);
?>
<div class="container mt-4">
    <?php foreach ($posts as $post): ?>
        <div class="post mb-4">
            <h3><?php echo htmlspecialchars($post['titulo_post']); ?></h3>
            <?php $comentarios = obtenerComentariosPorPost($conexion, $post['id_post']); ?>
            <?php if ($comentarios): ?>
                <?php foreach ($comentarios as $comentario): ?>
                    <div class="comment border p-3 mb-3" id="comment-<?php echo $comentario['id_comentario']; ?>">
                        <p><?php echo htmlspecialchars($comentario['contenido_comentario']); ?>
                            <span class="estado-comentario">
                                <?php if ($comentario['id_estado_comentario'] == 1): ?>
                                    (Visible)
                                <?php elseif ($comentario['id_estado_comentario'] == 2): ?>
                                    (Oculto)
                                <?php endif; ?>
                            </span>
                        </p>
                        <div class="comment-actions mt-2">
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="d-inline">
                                <input type="hidden" name="comentario_id" value="<?php echo $comentario['id_comentario']; ?>">
                                <button type="submit" name="eliminar_comentario" class="btn btn-danger">Ocultar</button>
                            </form>
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="d-inline">
                                <input type="hidden" name="comentario_id" value="<?php echo $comentario['id_comentario']; ?>">
                                <button type="submit" name="publicar_comentario" class="btn btn-primary">Publicar</button>
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

<?php require_once ('footer.php'); ?>

<script>
    function hideComment(idComentario) {
        document.getElementById('comment-' + idComentario).style.display = 'none';
    }
</script>

<script>
    function mostrarMensaje(mensaje, tipo) {
        if (tipo === 'exito') {
            mostrarModalExito(mensaje);
        } else if (tipo === 'error') {
            mostrarModalDeAdvertencia(mensaje);
        }

        setTimeout(function () {
            window.location.href = window.location.href;
        }, 1000); // Retraso de 1 segundo (1000 milisegundos)
    }

    // Verificar si hay un mensaje para mostrar
    <?php if (!empty($mensaje)): ?>
        // Llamar a la función para mostrar el mensaje
        mostrarMensaje("<?php echo $mensaje; ?>", "<?php echo strpos($mensaje, 'correctamente') !== false ? 'exito' : 'error'; ?>");
    <?php endif; ?>

</script>