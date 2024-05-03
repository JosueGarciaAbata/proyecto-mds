<?php
session_start();

if (isset($_SESSION['code'])) {

    $codeSesion = $_SESSION['code'];

    if (isset($_GET['code'])) {
        // Recupera el código de la URL
        $codeURL = $_GET['code'];

        if ($codeSesion === $codeURL) {

            unset($_SESSION["code"]);
        } else {

            header("Location: index.php");
            exit;
        }
    } else {

        header("Location: index.php");
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}
?>
<?php require_once ('header_menu_sectionLogo.php'); ?>
<?php require_once ('header_menu.php'); ?>
<div class="page-wrapper d-flex justify-content-center align-items-center">
    <div class="container container-tight py-4">

        <form id="reset_password_form" class="card card-md" action="./" method="get" autocomplete="off" novalidate>
            <div class="card-body">

                <p class="text-muted mb-4">To finish please enter your new password</p>
                <div class="mb-3">
                    <label class="form-label">New password</label>
                    <input id="reset_password" name="reset_password" type="password" class="form-control">
                </div>
                <div class="form-footer">

                    <div class="form-footer">
                        <button type="button" class="btn btn-primary w-100" id="reset_password_button">Change
                            password</button>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>
<?php require_once ('footer.php'); ?>
<script src="../js/resetPassword.js"></script>