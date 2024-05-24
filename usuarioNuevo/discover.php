<?php 
require_once ('header_menu_sectionLogo.php'); 
require_once ('header_menu.php'); 
require_once ('funcionesComentarios.php');
require_once("../procesarInformacion/conexion.php");
// Obtener la conexión
$conexion = ConexionBD::obtenerInstancia()->obtenerConexion();
// Asegúrate de que la sesión esté iniciada
$id_usuario = 0;
$stmt_posts = $conexion->prepare("SELECT * FROM posts WHERE id_estado_post = 1");
$stmt_posts->execute();
$posts = $stmt_posts->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt_posts->close();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['contenido_comentario'], $_POST['id_post_comentario'])) {
        $id_usuario= 0;
        $comentario = $_POST['contenido_comentario'];
        $id_post_comentario = $_POST['id_post_comentario'];

        if (insertarComentario($conexion, $id_post_comentario, $id_usuario, $comentario)) {
            echo '<script>alert("Comentario insertado correctamente.");</script>';
            echo '<script>window.location.href = "discover.php";</script>';
            exit;
        } else {
            echo '<script>alert("Error al insertar el comentario.");</script>';
        }
    } 

function obtenerNombreCategoria($conexion, $idCategoriaPost) {
    $stmt_categoria = $conexion->prepare("SELECT nombre_categoria FROM categorias WHERE id_categoria = ?");
    $stmt_categoria->bind_param("i", $idCategoriaPost);
    $stmt_categoria->execute();
    $categoria = $stmt_categoria->get_result()->fetch_assoc();
    $stmt_categoria->close();
    return $categoria['nombre_categoria'];
}

function obtenerNombresEtiquetas($conexion, $idCategoria) {
    $stmt_etiquetas = $conexion->prepare("SELECT nombre_etiqueta FROM etiquetas WHERE id_categoria_etiqueta = ?");
    $stmt_etiquetas->bind_param("i", $idCategoria);
    $stmt_etiquetas->execute();
    $result_etiquetas = $stmt_etiquetas->get_result();
    $etiquetas = [];
    while ($etiqueta = $result_etiquetas->fetch_assoc()) {
        $etiquetas[] = $etiqueta['nombre_etiqueta'];
    }
    $stmt_etiquetas->close();
    return implode(", ", $etiquetas);
}

function obtenerUsuarioPost($conexion, $idUsuarioPost) {
    $stmt_usuario = $conexion->prepare("SELECT nombre_usuario FROM usuarios WHERE id_usuario = ?");
    $stmt_usuario->bind_param("i", $idUsuarioPost);
    $stmt_usuario->execute();
    $usuario = $stmt_usuario->get_result()->fetch_assoc();
    $stmt_usuario->close();
    return $usuario['nombre_usuario'];
}

?>

<div class="page-wrapper">
    <div class="page-body">
        <div class="container-xl">
            <div class="row justify-content-center">
                <?php foreach ($posts as $post) { ?>
                    <div class="col-sm-8 mb-4">
                        <div class="card mb-3">
                            <div class="card-body">
                                <p class="card-text"><?php echo obtenerUsuarioPost($conexion, $post['id_usuario_post']); ?></p>
                                <h5 class="card-title"><?php echo htmlspecialchars($post['titulo_post']); ?></h5>
                                <p class="card-text"><?php echo nl2br(htmlspecialchars($post['contenido_textual_post'])); ?></p>
                                <p class="card-text">Categoría: <?php echo obtenerNombreCategoria($conexion, $post['id_categoria_post']); ?></p>
                                <p class="card-text">Etiquetas: <?php echo obtenerNombresEtiquetas($conexion, $post['id_categoria_post']); ?></p>
                                <?php if (!empty($post['ubicacion_imagen_post'])) { ?>
                                    <img src="<?php echo htmlspecialchars($post['ubicacion_imagen_post']); ?>" class="img-fluid" alt="Imagen del post">
                                <?php } ?>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Comentarios</h5>
                                <div id="comentarios_<?php echo $post['id_post']; ?>">
                                    <?php 
                                    $comentarios = publicarComentariosVisibles($conexion, 1);
                                    if ($comentarios !== false && !empty($comentarios)) {
                                        foreach ($comentarios as $comentario) {
                                            if ($comentario['id_post_comentario'] == $post['id_post']) {
                                                echo htmlspecialchars($comentario['contenido_comentario']) . '<br>';
                                            }
                                        }
                                    } else {
                                        echo "No hay comentarios.";
                                    }
                                    ?>
                                </div>
                                <form class="mt-3 comentario_form" id="comentario_form_<?php echo $post['id_post']; ?>" method="post">
                                    <div class="mb-3">
                                        <label for="comentario_<?php echo $post['id_post']; ?>" class="form-label">Escribe un comentario</label>
                                        <textarea class="form-control" id="comentario_<?php echo $post['id_post']; ?>" name="contenido_comentario" rows="1"></textarea>
                                    </div>
                                    <input type="hidden" name="id_post_comentario" value="<?php echo $post['id_post']; ?>">
                                    <input type="hidden" name="estado_comentario" value="2"> <!-- Estado oculto -->
                                    <button type="submit" class="btn btn-primary">Enviar comentario</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<?php require_once('footer.php'); ?>