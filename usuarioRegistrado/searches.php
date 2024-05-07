<?php
require_once ('header.php');
require_once ('navbar.php');

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
        <!-- Page header -->
        <div class="page-header d-print-none">
            <div class="container-xl">
                <div class="row g-2 align-items-center">
                    <!-- Page title actions -->
                    <div class="col-auto ms-auto d-print-none">
                        <div class="d-flex">
                            <button id="btn_create" type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#filtroModal">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M12 5l0 14"></path>
                                    <path d="M5 12l14 0"></path>
                                </svg>Apply filter
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page body -->
        <div class="page-body">
            <!-- In this div you will find the results of the query -->
            <div class="container-xl">
                <div class="row row-cards">
                    <div class="col-sm-6 col-lg-4">
                    </div>
                    <div class="d-flex">
                    </div>
                </div>
            </div>
            <!-- Delete modal -->
            <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete this portfolio?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main modal -->
            <div class="modal fade" id="filtroModal" tabindex="-1" aria-labelledby="filtroModal" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content"
                        style="position: absolute; top: 50px; left: 0; right: 0; bottom: 0; overflow: auto;">
                        <div class="modal-header">
                            <h5 class="modal-title" id="superiorTitle">Filtrar contenido</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center">
                            <!-- Sección para el título y contenido -->
                            <form id="postForm">
                                <div class="mb-3">
                                    <label for="tipo-filtro" class="form-label">Type:</label>
                                    <select name="tipo-filtro" id="tipo-filtro" class="form-select" required>
                                        <option value="" disabled selected>Select...</option>
                                        <option value="post">Post</option>
                                        <option value="proyecto">Project</option>
                                        <option value="portafolio">Portfolio</option>
                                    </select>
                                </div>

                                <div class="mb-3" style="display: none;" id="categorias-div">
                                    <label for="tipo-filtro" class="form-label"">Select Category:</label>
                                    <select name=" tipo-filtro" id="categorias-filtro" class="form-select" required>
                                        <option value="" disabled selected>Select...</option>
                                        <?php foreach ($categorias as $categoria) { ?>
                                            <option value="<?php echo $categoria['id_categoria'] ?>">
                                                <?php echo $categoria['nombre_categoria'] ?>
                                            </option>
                                        <?php } ?>
                                        </select>
                                </div>

                                <div class="mb-3" style="display: none;" id="etiquetas-div">
                                    <label for="inputTitle" class="form-label">Tags</label>
                                    <div class="form-selectgroup">
                                    </div>
                                </div>

                                <div class="mb-3" style="display: none;" id="habilidades-div">
                                    <label for="inputTitle" class="form-label">Skills</label>
                                    <div class="form-selectgroup habilities">
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

<?php require_once ('footer.php'); ?>
<script src="js/searches.js"></script>