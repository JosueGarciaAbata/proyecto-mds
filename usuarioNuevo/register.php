<?php require_once ('header_menu_sectionLogo.php'); ?>
<?php require_once ('header_menu.php'); ?>
<div class="page-wrapper d-flex justify-content-center align-items-center">
  <div class="container container-tight py-4">

    <form id="register-form" class="card card-md" action="./" method="get" autocomplete="off" novalidate="">
      <div class="card-body">
        <h2 class="card-title text-center mb-4">Create new account</h2>
        <div class="mb-3">
          <label class="form-label">Name</label>
          <input type="text" class="form-control" placeholder="Enter name" name="register_name" id="register_name">
        </div>
        <div class="mb-3">
          <label class="form-label">Email address</label>
          <input type="email" class="form-control" placeholder="Enter email" name="register_mail" id="register_mail">
        </div>
        <div class="mb-3 position-relative">
          <label class="form-label">Password</label>
          <input type="password" class="form-control" placeholder="Password" autocomplete="off" name="register_password"
            id="register_password">

        </div>
        <div class="mb-3">
          <label class="form-check">
            <input id="show_password" type="checkbox" class="form-check-input" />
            <span class="form-check-label">Show password</span>
          </label>
        </div>
        <div class="form-footer">
          <button type="button" class="btn btn-primary w-100" id="register_button">Create new account</button>
        </div>
      </div>
    </form>
    <div class="text-center text-muted mt-3">
      Already have account? <a href="./login.php" tabindex="-1">Sign in</a>
    </div>
  </div>
</div>

<?php require_once ('footer.php'); ?>

<script src="../js/register.js"></script>