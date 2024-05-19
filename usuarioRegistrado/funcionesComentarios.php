<?php
require_once("../procesarInformacion/conexion.php");

function obtenerComentariosPorPost($conexion, $idPost) {
    $sql="SELECT * FROM comentarios WHERE id_post_comentario = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $idPost);
    $stmt->execute();
    $result_comentarios = $stmt->get_result();
    // Obtener todos los resultados en un array asociativo
    $comentarios = $result_comentarios->fetch_all(MYSQLI_ASSOC);
    return $comentarios;
}


 function eliminarComentario($conexion, $idComentario){
    $sql="UPDATE comentarios SET id_estado_comentario = 2 WHERE id_comentario = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $idComentario);
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
 }

function actualizarComentario($conexion, $idComentario, $nuevoContenido) {
    $sql="UPDATE comentarios SET contenido_comentario = ? WHERE id_comentario = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("si", $nuevoContenido, $idComentario);
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
    $stmt->close();
}

function getAllVisiblePost($conexion){
    $sql="SELECT * FROM posts WHERE id_estado_post = 1";
    $stmt = $conexion->prepare($sql);
    $stmt->execute();
    //  obtener lo de la consulta
    $result_posts = $stmt->get_result();
    return $result_posts->fetch_all(MYSQLI_ASSOC);
}

function obtenerNombreCategoria($conexion, $idCategoriaPost)
{
    $sql="SELECT nombre_categoria FROM categorias WHERE id_categoria = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $idCategoriaPost);
    $stmt->execute();
    $result_categoria = $stmt->get_result();
    $categoria = $result_categoria->fetch_assoc();
    return $categoria['nombre_categoria'];
}

function obtenerNombresEtiquetas($conexion, $idCategoria)
{
    $sql = "SELECT nombre_etiqueta FROM etiquetas WHERE id_categoria_etiqueta = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $idCategoria);
    $stmt->execute();
    $result_etiquetas = $stmt->get_result();
    // Obtener todas las etiquetas como un array asociativo
    $etiquetas = $result_etiquetas->fetch_all(MYSQLI_ASSOC);
    // Extraer los valores de la columna 'nombre_etiqueta' en un array
    $nombresEtiquetas = array_column($etiquetas, 'nombre_etiqueta');
    // Devolver las etiquetas como una cadena separada por comas
    return implode(", ", $nombresEtiquetas);
}

function obtenerUsuarioPost($conexion, $idUsuarioPost)
{
    $sql="SELECT nombre_usuario FROM usuarios WHERE id_usuario = ?";
    $stmt_usuario = $conexion->prepare($sql);
    $stmt_usuario->bind_param("s", $idUsuarioPost);
    $stmt_usuario->execute();
    $result_usuario = $stmt_usuario->get_result();
    $usuario = $result_usuario->fetch_assoc();
    return $usuario['nombre_usuario'];
}


function renderPosts($conexion) {
    $posts = getAllVisiblePost($conexion);
    foreach ($posts as $post) {
        //  pa cerrar la etiqueta
        ?>
        <div class="col-sm-8 mb-4">
            <div class="card mb-3">
                <div class="row g-0">
                    <div class="col-md-12">
                        <div class="card-body">
                            <p class="card-text">
                                <?php echo obtenerUsuarioPost($conexion, $post['id_usuario_post']); ?></p>
                            <h5 class="card-title"><?php echo $post['titulo_post']; ?></h5>
                            <p class="card-text"><?php echo $post['contenido_textual_post']; ?></p>
                            <p class="card-text">Categoría:
                                <?php echo obtenerNombreCategoria($conexion, $post['id_categoria_post']); ?></p>
                            <p class="card-text">Etiquetas:
                                <?php echo obtenerNombresEtiquetas($conexion, $post['id_categoria_post']); ?>
                            </p>
                            <?php if (!empty($post['ubicacion_imagen_post'])) { ?>
                                <img src="<?php echo $post['ubicacion_imagen_post']; ?>" class="img-fluid" alt="imagen-post">
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Comentarios -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Comentarios</h5>
                    <div id="comentarios_<?php echo $post['id_post']; ?>">
                        <?php
                        // Obtener y mostrar los comentarios del post actual
                        $comentarios = obtenerComentariosPorPost($conexion, $post['id_post']);
                        foreach ($comentarios as $comentario) {
                            echo $comentario['contenido_comentario']; // Modificado aquí
                        }
                        ?>
                    </div>
                    <!-- Formulario de comentario -->
                    <form class="mt-3 comentario_form" data-id="<?php echo $post['id_post']; ?>" data-user="<?php echo $post['id_usuario_post']; ?>">
                        <div class="mb-3">Escribe un comentario
                            <label class="form-label">
                                <textarea class="form-control comentaryArea" name="contenido_comentario" rows="1"></textarea>
                            </label>
                        </div>
                        <input type="submit" value="Enviar comentario">


                        <!-- <button type="button"
                                onclick="insertarComentario(<?php echo $post['id_usuario_post']; ?>, <?php echo $post['id_post']; ?>, document.getElementById('comentario_<?php echo $post['id_post']; ?>').value)">Enviar
                            comentario</button> -->

                    </form>
                    <!-- Botón para cargar comentarios manualmente -->
                    <button class="btn btn-primary mt-3 chargeComentary">Cargar Comentarios</button>
                </div>
                <div class="comentary-box">

                </div>
            </div>
        </div>
        <?php
    }
}

function cargarComentarios($conexion,$postId){
    // Obtener y mostrar los comentarios del post actual
    $comentarios = obtenerComentariosPorPost($conexion, $postId);
    foreach ($comentarios as $comentario) {
        echo $comentario;
    }
}



?>