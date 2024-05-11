<?php require_once ('header_menu_sectionLogo.php'); ?>
<?php require_once ('header_menu.php'); ?>
<div class="modal fade" id="modalCode" tabindex="-1" aria-labelledby="codigoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalCodeLabel">Enter the code received by e-mail</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label for="code" class="form-label">Code:</label>
          <input type="text" class="form-control" id="codeInput" placeholder="Ingrese el código">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="verifyCodeBtn">Verify Code</button>
      </div>
    </div>
  </div>
</div>
<div class="page-wrapper d-flex justify-content-center align-items-center">
  <div class="container container-tight py-4">

    <form id="forgot_password_form" class="card card-md" action="./" method="get" autocomplete="off" novalidate>
      <div class="card-body">
        <h2 class="card-title text-center mb-4">Forgot password</h2>
        <p class="text-muted mb-4">Enter your email address and your password will be reset and emailed to you.</p>
        <div class="mb-3">
          <label class="form-label">Email address</label>
          <input id="forgot_password_email" name="forgot_password_email" type="email" class="form-control"
            placeholder="Enter email">
        </div>
        <div class="form-footer">

          <a id="forgot_password_button" href="#" class="btn btn-primary w-100">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
              stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
              <path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z" />
              <path d="M3 7l9 6l9 -6" />
            </svg>
            Send
          </a>
        </div>
      </div>
    </form>
    <div class="text-center text-muted mt-3">
      Forget it, <a href="./login.php">send me back</a> to the sign in screen.
    </div>
  </div>
</div>
</div>
<?php require_once ('footer.php'); ?>

<script src="../js/forgot_password.js"></script>