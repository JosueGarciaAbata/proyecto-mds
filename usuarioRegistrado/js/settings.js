/*
 *
 *Validar informacion ingresada
 *
 */

$(document).ready(function () {
  $.validator.addMethod(
    "strongPassword",
    function (value, element) {
      return (
        this.optional(element) ||
        /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@*().-_])[a-zA-Z\d!@*().-_]{8,}$/.test(
          value
        )
      );
    },
    "The password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one number and one special character (optional)."
  );
  $.validator.addMethod(
    "controllerName",
    function (value, element) {
      return this.optional(element) || /^[A-Za-z0-9_]+$/.test(value);
    },
    "The name cannot contain blank spaces or special characters, only letters, numbers or underscores (_)."
  );

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
        required: "Please enter a name",
        minlength: "Please enter at least 5 characters",
        maxlength: "The maximum number of characters is 10",
      },
      update_email: {
        required: "Please enter an email",
        email: "Please enter a valid email",
      },
      update_password: {
        required: "Please enter a password for this account",
        minlength: "Please enter at least 8 characters for the password",
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

/*
 *
 *Intercalar visibilidad contraseña
 *
 */
$("#show_password").change(function () {
  var passwordField = $("#update_password");
  if ($(this).is(":checked")) {
    passwordField.attr("type", "text");
  } else {
    passwordField.attr("type", "password");
  }
});

/*
 *
 *Guardar imagen subida en el form
 *
 */

$("#uploadInput").change(function () {
  $("#uploadForm").submit();
});

/*
 *
 *Mostrar imagen subida
 *
 */
$("#uploadForm").submit(function (event) {
  event.preventDefault();
  var formData = new FormData(this);
  formData.append("action", "imgPerfilTemporal");

  $.ajax({
    url: "procesarInformacion/user/updateUser.php",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (response) {
      console.log(response);
      if (response == "false") {
        mostrarModalDeAdvertencia("Error loading image");
      } else {
        var imageUrl = response;
        $(".avatar").css("background-image", "url(" + imageUrl + ")");
      }
    },
    error: function (xhr, status, error) {
      mostrarModalDeAdvertencia("An error has ocurred ");
    },
  });
});

/*
 *
 *Actualizar información del usuario
 *
 */

bntUpdate = document.getElementById("btnUpdate");

btnUpdate.addEventListener("click", function () {
  if ($("#updateFormInfo").valid()) {
    if ($("#updateFormInfo").valid()) {
      // Obtener el archivo de imagen seleccionado
      var imageFile = $("#uploadInput")[0].files[0];

      // Verificar si se seleccionó una imagen
      if (imageFile) {
        // Crear un objeto FormData para enviar la imagen al servidor
        var formData = new FormData();
        formData.append("profileImage", imageFile);
        formData.append("action", "imgPerfil");

        formData.append("update_name", $("#update_name").val());
        formData.append("update_email", $("#update_email").val());
        formData.append("update_password", $("#update_password").val());

        $.ajax({
          url: "procesarInformacion/user/updateUser.php",
          type: "POST",
          data: formData,
          contentType: false,
          processData: false,
          success: function (response) {
            if (response !== "false") {
              mostrarModalExito("Profile successfully updated");
              setTimeout(function () {
                window.location.href = "index.php";
              }, 2500);
            } else {
              mostrarModalDeAdvertencia("Error updating profile ");
            }
          },
          error: function (xhr, status, error) {
            mostrarModalDeAdvertencia("Connection error");
          },
        });
      } else {
        mostrarModalDeAdvertencia("There is no loaded image");
      }
    }
  }
});
