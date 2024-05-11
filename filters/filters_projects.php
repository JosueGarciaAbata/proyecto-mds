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
        <div class="modal fade" id="filtroModalProjects" tabindex="-1" aria-labelledby="filtroModalProjects"
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
                                <select name="tipo-filtro" id="tipo-filtro-project" class="form-select" required>
                                    <option value="" disabled selected>Select...</option>
                                    <option value="tipo-categoria">Category</option>
                                    <option value="tipo-etiqueta">Tags</option>
                                </select>
                            </div>

                            <!-- Seccion solamente para categorias -->
                            <div class="mb-3" style="display: none;" id="categorias-div">
                                <label for="inputTitle" class="form-label">Categories</label>
                                <div class="form-selectgroup" id="categories">
                                </div>
                            </div>

                            <!-- Seccion para las categorias de las etiquetas -->
                            <div class="mb-3" style="display: none;" id="categorias-tags-div">
                                <label for="inputTitle" class="form-label">Categories</label>
                                <div class="form-selectgroup" id="categories-tags">
                                </div>
                            </div>

                            <!-- Seccion para mostrar las etiquetas de la categoria-->
                            <div class="mb-3" style="display: none;" id="etiquetas-div">
                                <label for="inputTitle" class="form-label">Tags</label>
                                <div class="form-selectgroup" id="tags">
                                </div>
                            </div>

                            <!-- Close and save button -->
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