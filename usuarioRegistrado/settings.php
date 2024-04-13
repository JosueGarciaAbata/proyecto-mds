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
  <!-- Page header -->
  <div class="page-header d-print-none">
    <div class="container-xl">
      <div class="row g-2 align-items-center">
        <div class="col">

        </div>
      </div>
    </div>
  </div>
  <!-- Page body -->
  <div class="page-body">
    <div class="container-xl">
      <div class="card">
        <div class="row g-0">
          <div class="col d-flex flex-column">
            <div class="card-body">
              <h2 class="mb-4">Mi cuenta</h2>
              <h3 class="card-title">Detalles del perfil</h3>
              <div class="row align-items-center">
                <div class="col-auto">
                  <span class="avatar avatar-xl" style="background-image: url('<?php echo $fotoPerfil; ?>')"></span>
                </div>
                <div class="col-auto">
                  <form id="uploadForm" enctype="multipart/form-data">
                    <input type="file" id="uploadInput" name="profileImage" accept="image/*" style="display: none;">
                    <label for="uploadInput" class="btn">Seleccionar imagen</label>
                  </form>
                </div>
              </div>

              <!-- Formulario para actualizar información -->
              <form id="updateFormInfo" method="post" action="procesar_actualizacion.php">
                <h3 class="card-title mt-4">Nombre</h3>
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
                <h3 class="card-title mt-4">Contraseña</h3>
                <div class="row g-2">
                  <div class="col-auto">
                    <input id="update_password" type="password" class="form-control mb-2" name="update_password"
                      placeholder="Ingresa tu contraseña" value="<?php echo $_SESSION["password_user"]; ?>" size="30">
                  </div>
                </div>
                <div class="mb-3">
                  <label class="form-check">
                    <input id="show_password" type="checkbox" class="form-check-input" />
                    <span class="form-check-label">Mostrar contraseña</span>
                  </label>
                </div>
                <div class="card-footer bg-transparent mt-auto">
                  <div class="btn-list justify-content-end">
                    <a href="index.php" class="btn">Cancelar</a>
                    <button type="button" id="btnUpdate" class="btn btn-primary">Actualizar</button>
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

<script>

  //validarInformacion


  $(document).ready(function () {
    $.validator.addMethod("strongPassword", function (value, element) {
      return this.optional(element) || /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@*().-_])[a-zA-Z\d!@*().-_]{8,}$/.test(value);
    }, "La contraseña debe tener al menos 8 caracteres y contener al menos una letra mayúscula, una letra minúscula, un número y un carácter especial (opcional).");
    $.validator.addMethod("controllerName", function (value, element) {
      return this.optional(element) || /^[A-Za-z0-9_]+$/.test(value);
    }, "El nombre no puede contener espacios en blanco ni caracteres especiales, solo letras, números o guiones bajos (_).");

    $("#updateFormInfo").validate({
      rules: {
        update_name: {
          required: true,
          minlength: 5,
          maxlength: 10,
          controllerName: true,
        },
        update_email: {
          required: true,
          email: true,
        },
        update_password: {
          required: true,
          strongPassword: true,
          minlength: 8,
        },
      },
      messages: {
        update_name: {
          required: "Por favor ingrese un nombre",
          minlength: "Por favor ingrese al menos 5 caracteres",
          maxlength: "El número máximo de carácteres es 10"
        },
        update_email: {
          required: "Por favor ingrese un email",
          email: "Por favor ingrese un email válido",
        },
        update_password: {
          required: "Por favor ingrese una contraseña para esta cuenta",
          minlength: "Por favor ingrese al menos 8 caracteres para la contraseña"
        },
      },
      errorElement: "div",
      errorPlacement: function (error, element) {
        error.addClass("error");
        var container = $("<div>").addClass("error-container");
        container.insertAfter(element);
        error.appendTo(container);
      },
      highlight: function (element) {
        $(element).addClass("error");
      },
      unhighlight: function (element) {
        $(element).removeClass("error");
      },
    });
  });


  //Mostrar contraseña
  $('#show_password').change(function () {
    var passwordField = $('#update_password');
    if ($(this).is(':checked')) {
      passwordField.attr('type', 'text');
    } else {
      passwordField.attr('type', 'password');
    }
  });



  $('#uploadInput').change(function () {
    $('#uploadForm').submit();
  });

  //Cargar Imagen Temporal
  $('#uploadForm').submit(function (event) {
    event.preventDefault();
    var formData = new FormData(this);
    formData.append('action', 'imgPerfilTemporal');

    $.ajax({
      url: 'procesarInformacion/updateUser.php',
      type: 'POST',
      data: formData,
      contentType: false,
      processData: false,
      success: function (response) {

        if (response == "false") {
          mostrarModalDeAdvertencia("Error al cargar la iamgen");

        }
        var imageUrl = response; // Suponiendo que el servidor devuelve la URL de la nueva imagen
        $('.avatar').css('background-image', 'url(' + imageUrl + ')');
      },
      error: function (xhr, status, error) {
        console.error(error);

      }
    });
  });





  //ActualizarInformacion




  bntUpdate = document.getElementById("btnUpdate");

  btnUpdate.addEventListener("click", function () {
    if (
      $("#updateFormInfo").valid()

    ) {


      if ($("#updateFormInfo").valid()) {

        // Obtener el archivo de imagen seleccionado
        var imageFile = $('#uploadInput')[0].files[0];

        // Verificar si se seleccionó una imagen
        if (imageFile) {

          // Crear un objeto FormData para enviar la imagen al servidor
          var formData = new FormData();
          formData.append('profileImage', imageFile);
          formData.append('action', 'imgPerfil'); // Especificar la acción como 'imgPerfil'
          formData.append('action', 'imgPerfil'); // Especificar la acción como 'imgPerfil'
          formData.append('update_name', $('#update_name').val()); // Agregar el valor de update_name
          formData.append('update_email', $('#update_email').val()); // Agregar el valor de update_email
          formData.append('update_password', $('#update_password').val());

          // Realizar una solicitud AJAX para subir la imagen al servidor
          $.ajax({
            url: 'procesarInformacion/updateUser.php', // URL del script de procesamiento
            type: 'POST', // Método HTTP utilizado para la solicitud
            data: formData, // Datos a enviar (FormData con la imagen)
            contentType: false, // No establecer el tipo de contenido
            processData: false, // No procesar datos
            success: function (response) {

              if (response !== 'false') {
                mostrarModalExito("MostrarModalExito");
                setTimeout(function () {
                  window.location.href = "index.php"

                }, 2500)
              } else {
                mostrarModalDeAdvertencia("Error al actualizar la imagen de perfil");
              }
            },
            error: function (xhr, status, error) {
              // Manejar errores de la solicitud AJAX
              console.error(error);
              mostrarModalDeAdvertencia("Error de conexión");
            }
          });

        } else {
          // Si no se seleccionó ninguna imagen, continuar con la actualización sin la imagen
          console.log("No se seleccionó ninguna imagen para actualizar");
          // Aquí puedes agregar más lógica si es necesario
        }

      } else {
        // Si el formulario no es válido, no realizar la acción de actualización
        console.log("El formulario no es válido");
        // Aquí puedes agregar más lógica si es necesario
      }



    }



    else {

    }


  });



</script>