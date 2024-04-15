<section>
    <div class="page-wrapper d-flex justify-content center  align-items-center">
        <div d-md-none="d-sm-none" id="submenu-portafolio" style="display: none;">
            <div class="row justify-content-center">
                <div class="col-auto">
                    <a href="#" class="btn btn-primary crear-portafolio" data-bs-toggle="modal"
                        data-bs-target="#nuevoModal" style="margin-top: 20px;"><i class="fa-solid fa-circle-plus"></i>
                        Nuevo portafolio</a>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="portafolios-section" class="portafolios-section" style="display: none; ">
    <!-- Esto se genera dinamicamente  -->
    <div class="contenido-seccion">
        <div class="galeria">
            <!-- Aqui se va a generar dinamicamente los proyectos  -->
        </div>
</section>

<?php
$sqlProyecto = "SELECT id_proyecto, titulo_proyecto FROM proyectos";
$proyectos = $conexion->query($sqlProyecto);
?>

<?php include ("crud_portafolio/modalCrearPortafolio.php"); ?>