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
        <div class="modal fade" id="filtroModalDiscover" tabindex="-1" aria-labelledby="filtroModalDiscover"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content"
                    style="position: relative; top: 50px; left: 0; right: 0; bottom: 0; overflow: auto;">
                    <div class="modal-header">
                        <h5 class="modal-title" id="superiorTitle">Filter content</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <!-- Sección para el título y contenido -->
                        <form id="postForm">
                            <div class="mb-3">
                                <label for="tipo-filtro" class="form-label">Type:</label>
                                <select name="tipo-filtro" id="tipo-filtro" class="form-select" required>
                                    <option value="" disabled selected>Select...</option>
                                    <option value="tipo-posts">Post</option>
                                    <option value="tipo-projects">Projects</option>
                                    <option value="tipo-portfolios">Portfolios</option>
                                </select>
                            </div>

                            <!-- Div para los posts -->
                            <div class="mb-3" style="display:none;" id="posts-div">

                                <div class="mb-3">
                                    <label for="tipo-filtro" class="form-label">Select category type:</label>
                                    <select name="tipo-filtro" id="tipo-filtro-post" class="form-select" required>
                                        <option value="" disabled selected>Select...</option>
                                        <option value="tipo-categoria">Category</option>
                                        <option value="tipo-etiqueta">Tags</option>
                                    </select>
                                </div>


                                <!-- Seccion solamente para categorias -->
                                <div class="mb-3" style="display: none;" id="categorias-posts-div">
                                    <label for="inputTitle" class="form-label">Categories</label>
                                    <div class="form-selectgroup" id="categories">
                                    </div>
                                </div>

                                <!-- Seccion para las categorias de las etiquetas -->
                                <div class="mb-3" style="display: none;" id="categorias-tags-posts-div">
                                    <label for="inputTitle" class="form-label">Categories</label>
                                    <div class="form-selectgroup" id="categories-tags">
                                    </div>
                                </div>

                                <!-- Seccion para mostrar las etiquetas de la categoria-->
                                <div class="mb-3" style="display: none;" id="etiquetas-posts-div">
                                    <label for="inputTitle" class="form-label">Tags</label>
                                    <div class="form-selectgroup" id="tags">
                                    </div>
                                </div>
                            </div>

                            <!-- Div para los proyectos -->
                            <div class="mb-3" style="display:none;" id="projects-div">

                                <div class="mb-3">
                                    <label for="tipo-filtro" class="form-label">Type:</label>
                                    <select name="tipo-filtro" id="tipo-filtro-projects" class="form-select" required>
                                        <option value="" disabled selected>Select...</option>
                                        <option value="tipo-categoria">Category</option>
                                        <option value="tipo-etiqueta">Tags</option>
                                    </select>
                                </div>

                                <!-- Seccion solamente para categorias -->
                                <div class="mb-3" style="display: none;" id="categorias-projects-div">
                                    <label for="inputTitle" class="form-label">Categories</label>
                                    <div class="form-selectgroup" id="categories-projects">
                                    </div>
                                </div>

                                <!-- Seccion para las categorias de las etiquetas -->
                                <div class="mb-3" style="display: none;" id="categorias-tags-projects-div">
                                    <label for="inputTitle" class="form-label">Categories</label>
                                    <div class="form-selectgroup" id="categories-tags-projects">
                                    </div>
                                </div>

                                <!-- Seccion para mostrar las etiquetas de la categoria-->
                                <div class="mb-3" style="display: none;" id="etiquetas-projects-div">
                                    <label for="inputTitle" class="form-label">Tags</label>
                                    <div class="form-selectgroup" id="tags-projects">
                                    </div>
                                </div>
                            </div>

                            <!-- Div para los portafolios -->
                            <div class="mb-3" style="display: none;" id="portfolios-div">
                                <label for="inputTitle" class="form-label">Skills</label>
                                <div class="form-selectgroup" id="skills">
                                </div>
                            </div>

                            <!-- Boton de filtros -->
                            <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                                <button type="button" class="btn btn-secondary me-md-2"
                                    data-bs-dismiss="modal">Close</button>
                                <button id="btn_filter" type="button" class="btn btn-primary">Filter</button>
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