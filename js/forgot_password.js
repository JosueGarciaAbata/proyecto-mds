/*
 *
 *Validar  informacion ingresada
 *
 */

$(document).ready(function () {
  $("#forgot_password_form").validate({
    rules: {
      forgot_password_email: {
        required: true,
        email: true,
      },
    },
    messages: {
      forgot_password_email: {
        required: "Please enter your email address",
        email: "Enter a valid email address",
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

  /*
   *
   *Procesar informacion ingresadas
   *
   */

  document
    .getElementById("forgot_password_button")
    .addEventListener("click", function (event) {
      event.preventDefault();

      if ($("#forgot_password_form").valid()) {
        var forgotPasswordButton = $(this);
        var originalText = forgotPasswordButton.text(); // Guardar el texto original del enlace

        // Cambiar el texto del enlace a "Sending..."
        forgotPasswordButton.html(
          '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-spinner animate-spin" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /></svg> Sending...'
        );

        var user_email = $("#forgot_password_email").val();

        $.ajax({
          url: "../procesarInformacion/validateResetPassword.php",
          data: {
            user_email: user_email,
            action: "validatEmail",
          },
          type: "POST",
          success: function (resp) {
            console.log(resp);
            if (resp == "true") {
              $.ajax({
                url: "../procesarInformacion/sendEmailResetPassword.php",
                data: {
                  user_email: user_email,
                },
                type: "POST",
                success: function (resp) {
                  console.log(resp);
                  if (resp == "true") {
                    $("#modalCode").modal("show");
                  } else {
                    mostrarModalDeAdvertencia(
                      "The code could not be sent to the email provided!"
                    );
                  }
                },
                complete: function () {
                  // Restaurar el texto original del enlace
                  forgotPasswordButton.html(originalText);
                },
              });
            } else {
              mostrarModalDeAdvertencia("The email entered is not registered");
              // Restaurar el texto original del enlace
              forgotPasswordButton.html(originalText);
            }
          },
          error: function () {
            mostrarModalDeAdvertencia("An error has just occurred");
            // Restaurar el texto original del enlace
            forgotPasswordButton.html(originalText);
          },
        });
      }
    });

  /*
   *
   *Validar  codigo de verificacion ingresado
   *
   */

  $("#verifyCodeBtn").click(function () {
    var code = $("#codeInput").val();
    $.ajax({
      url: "../procesarInformacion/validateResetPassword.php",
      data: {
        user_code: code,
        action: "validateCode",
      },
      type: "POST",
      dataType: "json",
      success: function (resp) {
        if (resp.success) {
          window.location.href = resp.redirectUrl;
        } else {
          mostrarModalDeAdvertencia("The code entered is not correct");
        }
      },
      error: function (xhr, status, error) {
        console.error(xhr.responseText);
        mostrarModalDeAdvertencia("An error has just occurred");
      },
    });
    $("#codigoModal").modal("hide");
  });
});
