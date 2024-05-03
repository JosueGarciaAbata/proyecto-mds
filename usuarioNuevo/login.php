<?php require_once ('header_menu_sectionLogo.php'); ?>
<?php require_once ('header_menu.php'); ?>

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
              <span class="form-check-label">Show password</span>
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
<?php require_once ('footer.php'); ?>


<script src="../js/login.js"></script>