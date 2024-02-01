<section class="background-theme" id="auth">
  <div class="container-fluid h-100">
    <?php if (session()->get('success')): ?>
      <div class="success-msg auth-alert">
        <div class="alert alert-success" role="alert">
          <span class="close-btn success-btn">&times;</span>
          <div class="row pt-2 pb-2">
            <?= session()->get('success') ?>
          </div>
        </div>
      </div>
    <?php endif; ?>

      <?php if (isset($validation)): ?>
        <div class="error-msg auth-alert" >
          <div class="alert alert-danger" role="alert">
            <span class="close-btn error-btn">&times;</span>
            <div class="row pt-3">
              <?= $validation->listErrors() ?>
            </div>
          </div>
        </div>
     <?php endif; ?>
    <div class="d-flex flex-column justify-content-center align-items-center h-100">
      <div class="card p-2" style="border-radius: 1rem" id="login-card">
        <div class="m-3 m-md-4">
          <h4>Sign In</h4>
          <hr>
          <form action="<?= base_url('/login') ?>" method="post" class="form">
            <?= csrf_field() ?>
            <div class="form-group">
              <input type="text" class="form-control mb-3" name="email" placeholder="Email Address"
                value="<?= set_value('email') ?>" required>

              <input type="password" class="form-control mb-3" name="password" placeholder="Password" value="" required>

              <div class="d-flex flex-column">
                <input type="submit" class="btn btn-info btn-theme mt-2 mx-auto w-100" value="Sign in">
                <span class="mt-3">Don't have an account? <a class="link" href="<?= base_url('/register') ?>">
                    Register
                  </a></span>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>