<!-- Modal -->
<div class="modal fade" id="nuevoModal" tabindex="-1" role="dialog" aria-labelledby="nuevoModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="nuevoModalLabel">Registrar proyecto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="crud_portafolio/guardar_portafolio.php" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="titulo-proyecto" class="form-label">Titulo del proyecto:</label>
                        <input type="text" name="titulo-proyecto" id="titulo-proyecto" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="mensaje-bienvenida" class="form-label">Mensaje bienvenida:</label>
                        <textarea type="text" name="mensaje-bienvenida" id="mensaje-bienvenida" class="form-control"
                            required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="foto-perfil" class="form-label">Foto de perfil:</label>
                        <input type="file" name="foto-perfil" id="foto-perfil" class="form-control"
                            accept="image/jpeg, image/png" required>
                    </div>
                    <div class="mb-3">
                        <label for="nombres" class="form-label">Nombres:</label>
                        <input type="text" name="nombres" id="nombres" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="apellidos" class="form-label">Apellidos:</label>
                        <input type="text" name="apellidos" id="apellidos" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="estudios" class="form-label">Estudios:</label>
                        <input type="text" name="estudios" id="estudios" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="sobre-mi" class="form-label">Sobre mi:</label>
                        <input type="text" name="sobre-mi" id="sobre-mi" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="proyectos" class="form-label">Seleccione los proyectos:</label>
                        <select type="text" name="proyectos[]" id="proyectos" class="form-select" multiple required>
                            <option value="" disabled>Seleccionar...</option>
                            <?php
                            while ($row_proyecto = $proyectos->fetch_assoc()) { ?>
                                <option value="" <?= $row_proyecto['id_proyecto'] ?>> <?= $row_proyecto['titulo_proyecto'] ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-floppy2-fill"></i>Registrar
                            cambios</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>