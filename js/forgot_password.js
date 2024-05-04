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

  var buttonForgotPassword = document.getElementById("forgot_password_button");

  buttonForgotPassword.addEventListener("click", function (event) {
    event.preventDefault();

    if ($("#forgot_password_form").valid()) {
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
                }
                if (resp == "false") {
                  mostrarModalDeAdvertencia(
                    "The code could not be sent to the email provided!"
                  );
                }
              },
            });
          } else {
            mostrarModalDeAdvertencia("The email entered is not registered");
          }
        },
        error: function () {
          mostrarModalDeAdvertencia("An error has just occurred");
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
          mostrarModalDeAdvertencia(
            "The code could not be sent to the email provided!"
          );
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
