<?php
require_once ('header.php');
require_once ('navbar.php');
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
                                data-bs-target="#imageModal">
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

        <?php require_once ('../filters/filters_portfolios.php'); ?>
        <!-- Fin wrapper -->
    </div>

    <!-- Fin page -->
</div>

<?php require_once ('footer.php'); ?>
<script src="js/filters_portfolios.js"></script>