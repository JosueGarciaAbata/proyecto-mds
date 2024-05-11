/*
 *
 * Validar información  ingresada
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
    "The name may not contain blank spaces or special characters, only letters, numbers or underscores (_)."
  );

  $("#register-form").validate({
    rules: {
      register_name: {
        required: true,
        minlength: 5,
        maxlength: 10,
        controllerName: true,
      },
      register_mail: {
        required: true,
        email: true,
      },
      register_password: {
        required: true,
        strongPassword: true,
        minlength: 8,
      },
    },
    messages: {
      register_name: {
        required: "Please enter a name",
        minlength: "Please enter at least 5 characters",
        maxlength: "The maximum number of characters is 10",
      },
      register_mail: {
        required: "Please enter an email",
        email: "Please enter a valid email",
      },
      register_password: {
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
 *Intercalar  visibilidad contraseña
 *
 *
 */

$("#show_password").change(function () {
  var passwordField = $("#register_password");
  if ($(this).is(":checked")) {
    passwordField.attr("type", "text");
  } else {
    passwordField.attr("type", "password");
  }
});

/*
 *
 *Procesar información
 *
 */

$("#register_button").click(function () {
  if ($("#register-form").valid()) {
    $("#register_button").prop("disabled", true);

    user_name = $("#register_name").val();
    user_email = $("#register_mail").val();
    user_password = $("#register_password").val();

    $.ajax({
      url: "../procesarInformacion/register.php",
      data: {
        register_name: user_name,
        register_mail: user_email,
        register_password: user_password,
      },
      type: "POST",
      success: function (resp) {
        if (resp == "true") {
          mostrarModalExito("User created successfully");
          setTimeout(function () {
            window.location.href = "../usuarioRegistrado/index.php";
          }, 1000);
        } else {
          mostrarModalDeAdvertencia(
            "An account already exists with the information entered"
          );
          $("#register_button").prop("disabled", false);
        }
      },
      error: function (xhr, status, error) {
        mostrarModalAdvertencia("An error occurred while creating the account");
      },
    });
  }
});
