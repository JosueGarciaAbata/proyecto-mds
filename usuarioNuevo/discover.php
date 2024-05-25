<?php
require_once ('header_menu_sectionLogo.php');
require_once ('header_menu.php');
require_once ('funcionesComentarios.php');
require_once ("../procesarInformacion/conexion.php");
?>

<div class="page-wrapper">

    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <!-- Page title actions -->
                <div class="col-auto ms-auto d-print-none">
                    <div class="d-flex">
                        <button id="btn_apply_filter" type="button" class="btn btn-primary me-2" data-bs-toggle="modal"
                            data-bs-target="#filtroModalDiscover">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-filter" viewBox="0 0 16 16">
                                <path
                                    d="M6 10.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5m-2-3a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m-2-3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5" />
                            </svg>Apply filter
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
    </div>

    <?php require_once ('../filters/filters_discover.php'); ?>
    <?php require_once ('../procesarInformacion/comments_no_register/comments_modal.php'); ?>
</div>

<?php require_once ('footer.php'); ?>
<script src="../js/discover_no_register.js"></script>
<script src="../js/filters_discover.js"></script>