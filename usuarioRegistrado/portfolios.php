<?php
require_once ('header.php');
require_once ('navbar.php');
require_once ('procesarInformacion/portafolios/getHabilidades.php');
require_once ('procesarInformacion/portafolios/getProyectos.php');

$habilidades = sendHabilidades($conexion);

function generarOpcionesProyectos()
{
    // session_start();
    $proyectos = getProjectsById($_SESSION['user_id']);
    $response = "";
    foreach ($proyectos as $proyecto) {
        $response .= '<option value="' . $proyecto["id"] . '">' . $proyecto["titulo"] . '</option>';
    }
    echo $response;
}


//  HabT: {'HabTech':contentString}
function generarOpcionesHabilidades($habT)
{
    $response = "";
    foreach ($habT as $habilidad) {
        $response .= '<option value="' . $habilidad["id"] . '">' . $habilidad["nombre"] . '</option>';
    }
    echo $response;
}

?>


<div class="page">
    <div class="page-wrapper">
        <!-- Page header -->
        <div class="page-header d-print-none">
            <div class="container-xl">
                <div class="row g-2 align-items-center">

                    <!-- Page title actions -->
                    <div class="col-auto ms-auto d-print-none">
                        <div class="d-flex">
                            <!-- Principio boton filtrado -->
                            <button id="btn_apply_filter" type="button" class="btn btn-primary me-2"
                                data-bs-toggle="modal" data-bs-target="#filtroModalPortfolios">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-filter" viewBox="0 0 16 16">
                                    <path
                                        d="M6 10.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5m-2-3a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m-2-3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5" />
                                </svg>Apply filter
                            </button>
                            <!-- Fin boton filtrado -->
                            <!-- Inicio crear post -->
                            <button id="btn_create" type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#portfoliosModal">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M12 5l0 14"></path>
                                    <path d="M5 12l14 0"></path>
                                </svg>New portfolio
                            </button>
                            <!-- Fin crear post -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Page body -->
        <div class="page-body">
            <div class="container-xl">
                <div class="row row-cards">
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
        <div class="modal fade" id="portfoliosModal" tabindex="-1" aria-labelledby="portfoliosModal" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="superiorTitle">Create New Portfolio</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        
                        <!-- Sección para el título y contenido -->
                        <form enctype="multipart/form-data" id="form-portafolio">
                            <!--  titulo proyecto  -->
                            <div class="mb-3">
                                <label for="titulo-portafolio" class="form-label">Titulo del proyecto:</label>
                                <input type="text" name="titulo-portafolio" id="titulo-portafolio" class="form-control"
                                    required>
                            </div>
                            <!-- mensaje bienvenida -->
                            <div class="mb-3">
                                <label for="mensaje-bienvenida" class="form-label">Mensaje bienvenida:</label>
                                <textarea type="text" name="mensaje-bienvenida" id="mensaje-bienvenida"
                                    class="form-control" required></textarea>
                            </div>
                            <!-- foto perfil -->
                            <div class="mb-3">
                                <label for="foto-perfil" class="form-label">Foto de perfil:</label>
                                <input type="file" name="foto-perfil" id="foto-perfil" class="form-control"
                                    accept=".jpg, .jpeg, .png, .gif" required>
                            </div>
                            <!-- foto fondo -->
                            <div class="mb-3">
                                <label for="foto-fondo" class="form-label">Fondo del portafolio:</label>
                                <input type="file" name="foto-fondo" id="foto-fondo" class="form-control"
                                    accept=".jpg, .jpeg, .png, .gif" required>
                            </div>
                            <!-- cv -->
                            <div class="mb-3">
                                <label for="cv" class="form-label">Curriculum:</label>
                                <input type="file" name="cv" id="cv" class="form-control" accept=".pdf" required>
                            </div>

                            <!-- estudios -->
                            <div class="mb-3">
                                <label for="estudios" class="form-label">Estudios:</label>
                                <input type="text" name="estudios" id="estudios" class="form-control" required>
                            </div>
                            <!-- sobre mi -->
                            <div class="mb-3">
                                <label for="sobre-mi" class="form-label">Sobre mi:</label>
                                <input type="text" name="sobre-mi" id="sobre-mi" class="form-control" required>
                            </div>
                            <!-- habilidades tecnicas -->
                            <div class="mb-3">
                                <label for="habilidades-Tecnicas" class="form-label">Seleccione sus habilidades
                                    tecnicas:</label>
                                <select type="text" name="habilidades-Tecnicas" id="habilidades-Tecnicas"
                                    class="form-select" multiple required>
                                    <option value="" disabled>Seleccionar...</option>
                                    <?php
                                    generarOpcionesHabilidades($habilidades["habilidadesTecnicas"]);
                                    ?>
                                </select>
                                <input type="text" id="technicalSkillInput" class="form-control"
                                    placeholder="Enter technical skill">
                                <button id="addTechnicalSkill" type="button" class="btn btn-primary">Add Technical
                                    Skill</button>
                                <button id="deleteLastTechnicalSkill" type="button" class="btn btn-danger">Delete Last
                                    Skill</button>
                            </div>
                            <!-- habilidades sociales -->
                            <div class="mb-3">
                                <label for="habilidades-Sociales" class="form-label">Seleccione sus habilidades
                                    sociales:</label>
                                <select type="text" name="habilidades-Sociales" id="habilidades-Sociales"
                                    class="form-select" multiple required>
                                    <option value="" disabled>Seleccionar...</option>
                                    <?php
                                    generarOpcionesHabilidades($habilidades["habilidadesSociales"]);
                                    ?>
                                </select>
                                <input type="text" id="socialSkillInput" class="form-control"
                                    placeholder="Enter technical skill">
                                <button id="addSocialSkill" type="button" class="btn btn-primary">Add Socials
                                    Skill</button>
                                <button id="deleteLastSocialSkill" type="button" class="btn btn-danger">Delete Last
                                    Skill</button>

                            </div>
                            <!-- proyectos del usuario -->
                            <div class="mb-3">
                                <label for="proyectos" class="form-label">Seleccione los proyectos:</label>
                                <select type="text" name="proyectos" id="proyectos" class="form-select" multiple>
                                    <option value="" disabled>Seleccionar...</option>
                                    <?php
                                    generarOpcionesProyectos();
                                    ?>
                                </select>
                            </div>

                            <!-- Botones para guardar y cerrar el modal -->
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="button" class="btn btn-secondary me-md-2"
                                    data-bs-dismiss="modal">Close</button>
                                <input id="btn_portfolio" type="submit" class="btn btn-primary" value="Save">
                            </div>
                        </form>
                        <!-- Fin modal -->
                    </div>
                </div>
            </div>
        </div>

        <?php require_once ('../filters/filters_portfolios.php'); ?>
        <!-- Fin wrapper -->
    </div>

    <!-- Fin page -->
</div>

<?php require_once ('footer.php'); ?>
<script src="js/filters_portafolios.js"></script>
<!-- <script src="js/portafolio-handle.js" type="module"></script> -->
<!-- <script src="js/portafolios.js"></script> -->