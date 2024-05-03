<?php require_once ('header.php'); ?>
<?php require_once ('navbar.php'); ?>
<?php
// Obtener información del usuario
$stmt_info_usuario = "SELECT nombre_usuario, correo_electronico_usuario FROM usuarios WHERE id_usuario = ?";
$stmt_info_usuario = $conexion->prepare($stmt_info_usuario);
$stmt_info_usuario->bind_param("s", $user_id);
$stmt_info_usuario->execute();
$stmt_info_usuario->bind_result($name_user, $email_user);
$stmt_info_usuario->fetch();
$stmt_info_usuario->close();
?>

<div class="page-wrapper">

  <div class="page-header d-print-none">
    <div class="container-xl">
      <div class="row g-2 align-items-center">
        <div class="col">

        </div>
      </div>
    </div>
  </div>

  <div class="page-body">
    <div class="container-xl">
      <div class="card">
        <div class="row g-0">
          <div class="col d-flex flex-column">
            <div class="card-body">
              <h2 class="mb-4">My account</h2>
              <h3 class="card-title">Profile details</h3>
              <div class="row align-items-center">
                <div class="col-auto">
                  <span class="avatar avatar-xl" style="background-image: url('<?php echo $fotoPerfil; ?>')"></span>
                </div>
                <div class="col-auto">
                  <form id="uploadForm" enctype="multipart/form-data">
                    <input type="file" id="uploadInput" name="profileImage" accept="image/*" style="display: none;">
                    <label for="uploadInput" class="btn">Select image</label>
                  </form>
                </div>
              </div>

              <!-- Formulario para actualizar información -->
              <form id="updateFormInfo" method="post" action="procesar_actualizacion.php">
                <h3 class="card-title mt-4">Name</h3>
                <div class="row g-2">
                  <div class="col-auto">
                    <input id="update_name" type="text" class="form-control" name="update_name"
                      placeholder="Ingresa tu nombre" value="<?php echo $name_user; ?>" size="30">
                  </div>
                </div>
                <h3 class="card-title mt-4">Email</h3>
                <div class="row g-2">
                  <div class="col-auto">
                    <input id="update_email" type="email" class="form-control" name="update_email"
                      placeholder="Ingresa tu correo" value="<?php echo $email_user; ?>" size="30">
                  </div>
                </div>
                <h3 class="card-title mt-4">Password</h3>
                <div class="row g-2">
                  <div class="col-auto">
                    <input id="update_password" type="password" class="form-control mb-2" name="update_password"
                      placeholder="Ingresa tu contraseña" value="<?php echo $_SESSION["password_user"]; ?>" size="30">
                  </div>
                </div>
                <div class="mb-3">
                  <label class="form-check">
                    <input id="show_password" type="checkbox" class="form-check-input" />
                    <span class="form-check-label">Show password</span>
                  </label>
                </div>
                <div class="card-footer bg-transparent mt-auto">
                  <div class="btn-list justify-content-end">
                    <a href="index.php" class="btn">Cancel</a>
                    <button type="button" id="btnUpdate" class="btn btn-primary">Update</button>
                  </div>
                </div>
              </form>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<?php require_once ('footer.php'); ?>

<script src="js/settings.js"></script>