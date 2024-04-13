<?php
session_start();

// Verifica si existe la sesión y si la variable 'code' está definida
if (isset($_SESSION['code'])) {
    // Recupera el código almacenado en la sesión
    $codeSesion = $_SESSION['code'];

    // Verifica si se recibió el código en la URL
    if (isset($_GET['code'])) {
        // Recupera el código de la URL
        $codeURL = $_GET['code'];

        if ($codeSesion === $codeURL) {
            echo $_SESSION["userEmailPassword"];
            unset($_SESSION["code"]);
        } else {
            // Los códigos no coinciden, redirige a index.php
            header("Location: index.php");
            exit; // Importante para evitar que el script siga ejecutándose después de la redirección
        }
    } else {
        // No se recibió el código en la URL, redirige a index.php
        header("Location: index.php");
        exit; // Importante para evitar que el script siga ejecutándose después de la redirección
    }
} else {
    header("Location: index.php");
    exit; // Importante para evitar que el script siga ejecutándose después de la redirección
}
?>
<?php require_once ('header_menu_sectionLogo.php'); ?>
<?php require_once ('header_menu.phpV1.php'); ?>
<div class="page-wrapper d-flex justify-content-center align-items-center">
    <div class="container container-tight py-4">

        <form id="reset_password_form" class="card card-md" action="./" method="get" autocomplete="off" novalidate>
            <div class="card-body">

                <p class="text-muted mb-4">Para finalizar por favor ingrese su nueva contraseña</p>
                <div class="mb-3">
                    <label class="form-label">Nueva contraseña</label>
                    <input id="reset_password" name="reset_password" type="password" class="form-control">
                </div>
                <div class="form-footer">

                    <div class="form-footer">
                        <button type="button" class="btn btn-primary w-100" id="reset_password_button">Cambiar
                            contraseña</button>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>
<?php require_once ('footerV1.php'); ?>
<script>
    $(document).ready(function () {
        $.validator.addMethod("strongPassword", function (value, element) {
            return this.optional(element) || /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@*().-_])[a-zA-Z\d!@*().-_]{8,}$/.test(value);
        }, "La contraseña debe tener al menos 8 caracteres y contener al menos una letra mayúscula, una letra minúscula, un número y un carácter especial (opcional).");

        $("#reset_password_form").validate({
            rules: {
                reset_password: {
                    required: true,
                    strongPassword: true,
                },

            },
            messages: {
                reset_password: {
                    required: "Por favor ingrese una contraseña",
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
                url: "procesarInformacion/validateResetPassword.php",
                data: {
                    new_password: $("#reset_password").val(),
                    action: "changePassword",
                },
                type: "POST",
                success: function (resp) {
                    console.log(resp);
                    if (resp == "true") {
                        mostrarModalExito("Contraseña cambiada con éxito");
                        setTimeout(function () {
                            window.location.href = "login.php"
                        }, 2500);
                    } else {
                        mostrarModalDeAdvertencia("Error al cambiar la contraseña");
                    }
                },
                error: function () {
                    mostrarModalDeAdvertencia("Ha sucedido un error");
                }

            });

        }
    });

</script>