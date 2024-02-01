<section class="background-theme" id="auth">
  <div class="container-fluid py-5 h-100">
    <?php if (isset($validation)): ?>
      <div class="error-msg auth-alert">
        <div class="alert alert-danger" role="alert">
          <span class="close-btn error-btn">&times;</span>
          <div class="row pt-3">
            <?= $validation->listErrors() ?>
          </div>
        </div>
      </div>
    <?php endif; ?>
    <div class="d-flex flex-column justify-content-center align-items-center h-100">
      <div class="card" style="border-radius: 1rem" id="register-card">
        <div class="m-4 m-md-5 pb-1">
          <h4>Sign Up</h4>
          <hr>
          <form action="<?= base_url('/register') ?>" method="post" class="form">
            <div class="form-group">

              <input type="text" class="form-control mb-2" name="first_name" placeholder="First Name"
                value="<?= set_value('first_name') ?>" required>

              <input type="text" class="form-control mb-2" name="last_name" placeholder="Last Name"
                value="<?= set_value('last_name') ?>" required>

              <input type="text" class="form-control mb-2" name="email" placeholder="Email Address"
                value="<?= set_value('email') ?>" required>

              <input type="password" class="form-control mb-2" name="password" placeholder="Password" value="" required>

              <input type="password" class="form-control mb-2" name="confirmPassword" placeholder="Confrim Password"
                required>

              <div class="d-flex flex-column">
                <input type="submit" class="btn btn-info btn-theme mt-4 mx-auto w-100" value="Sign Up">
                <span class="mt-3">
                  Already have an account? <a class="link" href="<?= base_url() ?>">Login</a>
                </span>
              </div>
            </div>
          </form>
        </div>
      </div>
    
    </div>
  </div>
</section>