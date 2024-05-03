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

  $("#reset_password_form").validate({
    rules: {
      reset_password: {
        required: true,
        strongPassword: true,
      },
    },
    messages: {
      reset_password: {
        required: "Please enter a password",
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

$("#reset_password_button").click(function () {
  if ($("#reset_password_form").valid()) {
    $.ajax({
      url: "../procesarInformacion/validateResetPassword.php",
      data: {
        new_password: $("#reset_password").val(),
        action: "changePassword",
      },
      type: "POST",
      success: function (resp) {
        console.log(resp);
        if (resp == "true") {
          mostrarModalExito("Password successfully changed");
          setTimeout(function () {
            window.location.href = "login.php";
          }, 2500);
        } else {
          mostrarModalDeAdvertencia("Error changing password");
        }
      },
      error: function () {
        mostrarModalDeAdvertencia("An error has occurred");
      },
    });
  }
});
