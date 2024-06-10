<?php
require_once ('header.php');
require_once ('navbar.php');
require_once ("../procesarInformacion/conexion.php");
require_once ('funciones_comentarios.php');

$conexion = ConexionBD::obtenerInstancia()->obtenerConexion();
$id_usuario = $_SESSION['user_id'];
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Eliminar y publicar para posts
    if (isset($_POST['eliminar_comentario_posts'])) {
        $idComentario = $_POST['comentario_id_posts'];
        if (eliminarComentarioPosts($conexion, $idComentario)) {
            $mensaje = "El comentario se ocultó correctamente.";
        } else {
            $mensaje = "Error al ocultar el comentario.";
        }
    } elseif (isset($_POST['publicar_comentario_posts'])) {
        $idComentario = $_POST['comentario_id_posts'];
        if (publicarComentarioPosts($conexion, $idComentario)) {
            $mensaje = "Comentario publicado correctamente.";
        } else {
            $mensaje = "Error al publicar el comentario.";
        }
    }

    // Eliminar y publicar para proyectos
    if (isset($_POST['eliminar_comentario_projects'])) {
        $idComentario = $_POST['comentario_id_projects'];
        if (eliminarComentarioProjects($conexion, $idComentario)) {
            $mensaje = "El comentario se ocultó correctamente.";
        } else {
            $mensaje = "Error al ocultar el comentario.";
        }
    } elseif (isset($_POST['publicar_comentario_projects'])) {
        $idComentario = $_POST['comentario_id_projects'];
        if (publicarComentarioProjects($conexion, $idComentario)) {
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

function obtenerProyectosPorUsuario($conexion, $idUsuario)
{
    $stmt_proyectos = $conexion->prepare("SELECT id_proyecto, titulo_proyecto 
                                      FROM  proyectos
                                      WHERE id_usuario_proyecto = ?");
    $stmt_proyectos->bind_param("i", $idUsuario);
    $stmt_proyectos->execute();
    $result_posts = $stmt_proyectos->get_result();
    $proyectos = $result_posts->fetch_all(MYSQLI_ASSOC);
    $stmt_proyectos->close();
    return $proyectos;
}

$posts = obtenerPostsPorUsuario($conexion, $id_usuario);
$proyectos = obtenerProyectosPorUsuario($conexion, $id_usuario);
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
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="d-inline comentario-form" data-id="<?php echo $comentario['id_comentario']; ?>">
                                <input type="hidden" name="comentario_id_posts" value="<?php echo $comentario['id_comentario']; ?>">
                                <button type="submit" name="eliminar_comentario_posts" class="btn btn-danger">Ocultar</button>
                            </form>
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="d-inline comentario-form" data-id="<?php echo $comentario['id_comentario']; ?>">
                                <input type="hidden" name="comentario_id_posts" value="<?php echo $comentario['id_comentario']; ?>">
                                <button type="submit" name="publicar_comentario_posts" class="btn btn-primary">Publicar</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No hay comentarios para este post.</p>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>

    <!-- Todo para proyectos -->
    <?php foreach ($proyectos as $proyecto): ?>
        <div class="proyecto mb-4">
            <h3><?php echo htmlspecialchars($proyecto['titulo_proyecto']); ?></h3>
            <?php $comentarios = obtenerComentariosPorProyecto($conexion, $proyecto['id_proyecto']); ?>
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
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="d-inline comentario-form" data-id="<?php echo $comentario['id_comentario']; ?>">
                                <input type="hidden" name="comentario_id_projects" value="<?php echo $comentario['id_comentario']; ?>">
                                <button type="submit" name="eliminar_comentario_projects" class="btn btn-danger">Ocultar</button>
                            </form>
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="d-inline comentario-form" data-id="<?php echo $comentario['id_comentario']; ?>">
                                <input type="hidden" name="comentario_id_projects" value="<?php echo $comentario['id_comentario']; ?>">
                                <button type="submit" name="publicar_comentario_projects" class="btn btn-primary">Publicar</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No hay comentarios para este proyecto.</p>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>

<?php require_once ('footer.php'); ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Capturar todos los formularios con la clase 'comentario-form'
        document.querySelectorAll('.comentario-form').forEach(function(form) {
            form.addEventListener('submit', function(event) {
                event.preventDefault(); // Evitar el envío del formulario

                var formData = new FormData(this);
                var comentarioId = this.getAttribute('data-id');
                var commentElement = document.getElementById('comment-' + comentarioId);

                // Enviar la solicitud de forma asíncrona (AJAX)
                fetch(this.action, {
                    method: 'POST',
                    body: formData
                }).then(response => response.text())
                .then(data => {
                    // Ocultar el comentario de la interfaz
                    if (commentElement) {
                        commentElement.style.display = 'none';
                    }
                }).catch(error => {
                    console.error('Error:', error);
                });
            });
        });
    });
</script>
