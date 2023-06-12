<section class="gradient-custom" id="auth">
    <div class="container-fluid py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">
            <div class="card rounded-3 border-0" style="background: linear-gradient(to right, #d52dde, #15abbe); margin-bottom: 30px; margin-top: 30px">
                <h4 class="text-center" style="font-size: 40px; color: white;">
                    <img src="public/cat.png" alt="Cat" style="width: 70px; height: auto;">
                    POUNCE
                    <img src="public/cat.png" alt="Cat" style="width: 70px; height: auto;">
                </h4>
            </div>
                <div class="card" style="border-radius: 1rem">
                    <div class="m-3 m-md-4">
                        <h4>Sign In</h4>
                        <hr>
                        <?php if (session()->get('success')) : ?>
                            <div class="col-12 success-msg">
                                <div class="alert alert-success" role="alert">
                                    <span class="close-btn success-btn">&times;</span>
                                    <div class="row pt-2 pb-2">
                                        <?= session()->get('success') ?>
                                    </div>
                                </div>
                            </div>
                            
                        <?php endif; ?>
                        <form action="<?=base_url('/login')?>" method="post" class="form">
                            <?= csrf_field() ?>
                            <div class="form-group">
                                <input type="text" class="form-control mb-2" name="email" placeholder="Email Address" value="<?= set_value('email') ?>" required>

                                <input type="password" class="form-control mb-2" name="password" placeholder="Password" value="" required>

                                <?php if (isset($validation)) : ?>
                                    <div class="col-12 error-msg">
                                        <div class="alert alert-danger" role="alert">
                                            <span class="close-btn error-btn">&times;</span>
                                            <div class="row pt-3">
                                                <?= $validation->listErrors() ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <div class="row d-flex justify-content-center">   
                                   <input type="submit" class="btn btn-info btn-green mt-4 mx-auto" value="Sign in">
                                    
                                    <button type="button" class="btn btn-info btn-green mt-4 mx-auto" onclick="location.href='<?=base_url('/register')?>'">
                                        New User
                                    </button>
                    
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>