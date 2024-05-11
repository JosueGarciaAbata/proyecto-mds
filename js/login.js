/*
 *Validación de campos
 *
 */

$(document).ready(function () {
  $("#login-form").validate({
    rules: {
      login_email: {
        required: true,
        email: true,
      },
      login_password: {
        required: true,
      },
    },
    messages: {
      login_email: {
        required: "Please enter an email",
        email: "Please enter a valid email address",
      },
      login_password: {
        required: "Please enter your password",
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
 *Intercalacion visible/oculto  campo contraseña
 *
 */

$("#show_password").change(function () {
  var passwordField = $("#login_password");

  if ($(this).is(":checked")) {
    passwordField.attr("type", "text");
  } else {
    passwordField.attr("type", "password");
  }
});

/*
 *Procesar informacion  ingresada
 *
 */

$("#login_button").click(function () {
  if ($("#login-form").valid()) {
    $("#login_button").prop("disabled", true);

    var user_email = $("#login_email").val();
    var user_password = $("#login_password").val();

    $.ajax({
      url: "../procesarInformacion/login.php",
      data: {
        login_email: user_email,
        login_password: user_password,
      },
      type: "POST",
      success: function (resp) {
        if (resp == "true") {
          mostrarModalExito("Successful login");
          setTimeout(function () {
            window.location.href = "../usuarioRegistrado/index.php";
          }, 1000);
        } else {
          mostrarModalDeAdvertencia(
            "The account entered does not exist or the password is incorrect."
          );
          $("#login_button").prop("disabled", false);
        }
      },
      error: function (xhr, status, error) {
        mostrarModalAdvertencia("An error occurred while logging in");
      },
    });
  }
});
