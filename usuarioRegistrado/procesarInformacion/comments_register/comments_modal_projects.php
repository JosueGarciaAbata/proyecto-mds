<?php
$stmt_categorias = $conexion->prepare("SELECT * FROM categorias");
$stmt_categorias->execute();
$result_categorias = $stmt_categorias->get_result();
$categorias = $result_categorias->fetch_all(MYSQLI_ASSOC);
$stmt_categorias->close();
?>
<style>
    .delete-icon {
        width: 32px;
        height: 32px;
        stroke: red;
        fill: none;
    }

    .modal-dialog {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    .modal {
        background-color: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(8px);
    }
</style>

<!-- Main content -->
<div class="page">
    <div class="page-wrapper">
        <!-- Main modal -->
        <div class="modal fade" id="commentsModalProjects" tabindex="-1" aria-labelledby="commentsModalProjects"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content"
                    style="position: relative; top: 50px; left: 0; right: 0; bottom: 0; overflow: auto;">
                    <div class="modal-header">
                        <h1 class="modal-title text-center h1-title-projects" id="superiorTitle" style="margin: 0;">dd
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">

     <!-- Sección para la fecha de inicio -->
                        <div class="mb-3">
                            <label for="startDate" class="form-label">Fecha de inicio:</label>
                             <input type="date" class="form-control" id="startDate" name="startDate">
                        </div>

                        <!-- Sección para la fecha de fin -->
                        <div class="mb-3">
                             <label for="endDate" class="form-label">Fecha de fin:</label>
                            <input type="date" class="form-control" id="endDate" name="endDate">
                        </div>

                         <div id="projectContentContainer" style="margin-bottom: 20px;">
                         <p id="projectContent" style="text-align: left;"></p>
                    </div>

                          <!-- Sección para la imagen del post -->
                         <div id="projectImageContainer" style="margin-bottom: 20px; text-align: center;">
                          <img id="projectImage" src="" alt="Imagen del Proyecto" style="max-height: 200px; width: 100%; display: inline-block;">
                         </div>

                         <!-- Sección para las etiquetas -->
                        <div id="projectTagsContainer" style="margin-bottom: 20px;">
                         <span id="projectTags"></span>
                         </div>

                        <!-- Sección para el título y contenido -->
                        <form id="postForm">
                            <div id="commentsContainerProjects"></div>

                            <p id="noCommentsMessageProjects" style="margin-bottom: 20px; display: none;">Sé el primer
                                comentario!</p>

                            <!-- Área de texto para el comentario del posts-->
                            <div class="input-group mb-3" style="border-radius: 20px;">
                                <textarea class="form-control" placeholder="Escribe tu comentario..."
                                    aria-label="Escribe tu comentario..." id="commentTextProjects" rows="3"></textarea>
                                <button class="btn btn-outline-secondary" type="button"
                                    id="sendCommentBtnProjects">Enviar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- End modal -->
    </div>
</div>
</div>