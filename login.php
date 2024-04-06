<?php require_once ('header_menu_sectionLogo.php'); ?>
<?php require_once ('header_menu.phpV1.php'); ?>

<div class="page-wrapper d-flex justify-content-center align-items-center">
  <div class="container container-tight py-4">

    <div class="card card-md">
      <div class="card-body">
        <h2 class="h2 text-center mb-4">Login to your account</h2>
        <form id="login-form" action="./" method="get" autocomplete="off" novalidate="">
          <div class="mb-3">
            <label class="form-label">Email address</label>
            <input type="email" class="form-control" placeholder="Your email" autocomplete="off" name="login_email"
              id="login_email" />
          </div>
          <div class="mb-3 ">
            <label class="form-label">Password
              <span class="form-label-description">
                <a href="./forgot_password.php">I forgot password</a>
              </span>
            </label>
            <input type="password" class="form-control" placeholder="Password" autocomplete="off" name="login_password"
              id="login_password">
          </div>
          <div class="mb-3">
            <label class="form-check">
              <input id="show_password" type="checkbox" class="form-check-input" />
              <span class="form-check-label">Mostrar contraseña</span>
            </label>
          </div>
          <div class="form-footer">
            <button id="login_button" type="button" class="btn btn-primary w-100">
              Sign in
            </button>
          </div>
        </form>
      </div>
      <div class="hr-text">or</div>

    </div>
    <div class="text-center text-muted mt-3">
      Don't have account yet?
      <a href="./register.php" tabindex="-1">Sign up</a>
    </div>
  </div>
</div>
<?php require_once ('footerV1.php'); ?>


<script>




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
          required: "Por favor ingrese un email",
          email: "Por favor ingrese un email válido",
        },
        login_password: {
          required: "Por favor ingrese su contraseña",
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
  $("#show_password").change(function () {

    var passwordField = $("#login_password");

    if ($(this).is(":checked")) {

      passwordField.attr("type", "text");
    } else {

      passwordField.attr("type", "password")
    }

  })

  $('#login_button').click(function () {

    if (
      $("#login-form").valid()

    ) {
      user_email = $("#login_email").val();
      user_password = $("#login_password").val();

      $.ajax({
        url: 'procesarInformacion/login.php',
        data: {
          login_email: user_email,
          login_password: user_password,

        },
        type: 'POST',
        success: function (resp) {
          console.log(resp);
          if (resp == "true") {

            mostrarModalExito("Inicio de sesión éxitoso");
            setTimeout(function () {
              window.location.href = "usuarioRegistrado/index.php"

            }, 2500)
          } else {

            mostrarModalDeAdvertencia("No se encontró la cuenta ingresada");

          }
        },
        error: function (xhr, status, error) {
          mostrarModalAdvertencia("Sucedio un error al iniciar sesion");

        }

      });

    }

  });


</script>