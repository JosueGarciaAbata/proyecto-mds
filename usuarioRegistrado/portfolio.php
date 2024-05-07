<?php
require_once ('header.php');
require_once ('navbar.php');

$stmt_state = $conexion->prepare("SELECT * FROM estado");
$stmt_state->execute();
$result_state = $stmt_state->get_result();
$states = $result_state->fetch_all(MYSQLI_ASSOC);
$stmt_state->close();

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
                                data-bs-target="#imageModal">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M12 5l0 14"></path>
                                    <path d="M5 12l14 0"></path>
                                </svg> New portfolio
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Page body -->
        <div class="page-body">
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
            <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content"
                        style="position: absolute; top: 50px; left: 0; right: 0; bottom: 0; overflow: auto;">
                        <div class="modal-header">
                            <h5 class="modal-title" id="superiorTitle">Create New Portfolio</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center">
                            <!-- Sección para el título y contenido -->
                            <form id="portfolioForm">

                                <div class="mb-3">
                                    <label for="titulo-portafolio" class="form-label">Portfolio title</label>
                                    <input type="text" name="titulo-portafolio" id="titulo-portafolio"
                                        class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label for="foto-perfil" class="form-label">Profile picture:</label>
                                    <input type="file" name="foto-perfil" id="foto-perfil" class="form-control"
                                        accept=".jpg, .jpeg, .png" required>
                                </div>

                                <div class="mb-3">
                                    <label for="foto-fondo" class="form-label">Background photo:</label>
                                    <input type="file" name="foto-fondo" id="foto-fondo" class="form-control"
                                        accept=".jpg, .jpeg, .png" required>
                                </div>

                                <div class="mb-3">
                                    <label for="sobre-mi" class="form-label">About me</label>
                                    <input type="text" name="sobre-mi" id="sobre-mi" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label for="cv" class="form-label">Curriculum:</label>
                                    <input type="file" name="cv" id="cv" class="form-control" accept=".pdf" required>
                                </div>

                                <div class="mb-3" id="tecnicalSkill">
                                    <label for="tecnicalSkill" class="form-label">Tecnical skills</label>
                                    <div id="technicalSkillsContainer">
                                    </div>
                                    <button type="button" class="btn btn-outline-primary mt-2"
                                        id="addTechnicalSkill">Add tecnical Skill</button>
                                </div>

                                <div class="mb-3" id="socialSkill">
                                    <label for="socialSkill" class="form-label">Social skills</label>
                                    <div id="socialSkillsContainer">
                                    </div>
                                    <button type="button" class="btn btn-outline-primary mt-2" id="addSocialSkill">Add
                                        social
                                        Skill</button>
                                </div>

                                <div class="mb-3">
                                    <label for="inputTitle" class="form-label">Projects</label>
                                    <div class="form-selectgroup projects">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="state" class="form-label">Select Visibility</label>
                                    <select class="form-select" id="state" name="state">
                                        <option value="" selected disabled>Select status</option>
                                        <?php foreach ($states as $state) { ?>
                                            <option value="<?php echo $state['id_estado']; ?>">
                                                <?php echo $state['nombre_estado']; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <!-- Botones para guardar y cerrar el modal -->
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button type="button" class="btn btn-secondary me-md-2"
                                        data-bs-dismiss="modal">Close</button>
                                    <button id="btn_portfolio" type="button" class="btn btn-primary">Save</button>
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
<script src="js/portfolio.js"></script>