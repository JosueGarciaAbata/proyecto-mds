<?php require_once('header_menu_sectionLogo.php'); ?> 
        <?php require_once('header_menu.phpV1.php'); ?>

  <div
          class="page-wrapper d-flex justify-content-center align-items-center"
        >
       <div class="container container-tight py-4">
       
        <form class="card card-md" action="./" method="get" autocomplete="off" novalidate>
          <div class="card-body">
            <h2 class="card-title text-center mb-4">Forgot password</h2>
            <p class="text-muted mb-4">Enter your email address and your password will be reset and emailed to you.</p>
            <div class="mb-3">
              <label class="form-label">Email address</label>
              <input type="email" class="form-control" placeholder="Enter email">
            </div>
            <div class="form-footer">
              
              <a href="#" class="btn btn-primary w-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z" /><path d="M3 7l9 6l9 -6" /></svg>
                Send me new password
              </a>
            </div>
          </div>
        </form>
        <div class="text-center text-muted mt-3">
          Forget it, <a href="./login.php">send me back</a> to the sign in screen.
        </div>
      </div>
        </div>
        <?php require_once('footerV1.php'); ?> 