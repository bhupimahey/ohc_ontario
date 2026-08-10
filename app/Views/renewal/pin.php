<?php $header = ['title' => 'Renewal Access']; ?>
<?php echo view('includes/header', $header); ?>
<body class="horizontal-layout horizontal-menu blank-page navbar-floating footer-static" data-open="hover" data-menu="horizontal-menu" data-col="blank-page">
<div class="app-content content">
  <div class="content-wrapper">
    <div class="content-body">
      <div class="auth-wrapper auth-basic px-2">
        <div class="auth-inner my-2">
          <div class="card mb-0" style="max-width:420px;margin:40px auto;">
            <div class="card-body">
              <h4 class="card-title mb-1">Software Renewal</h4>
              <p class="card-text mb-2">Enter MPIN to manage AMC &amp; Hosting payments.</p>
              <p class="mb-2"><strong>Next Renewal On:</strong> <?= esc($next_renewal); ?></p>
              <?php echo $message_output->run(); ?>
              <form action="<?= base_url('renewal/unlock'); ?>" method="post" class="mt-2">
                <div class="mb-1">
                  <label class="form-label" for="mpin">MPIN</label>
                  <input class="form-control" type="password" id="mpin" name="mpin" inputmode="numeric" maxlength="8" required autofocus>
                </div>
                <button type="submit" class="btn btn-primary w-100">Continue</button>
              </form>
              <p class="text-center mt-2 mb-0">
                <a href="<?= base_url('login'); ?>">Back to Login</a>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php echo view('includes/footer_scripts'); ?>
<?php echo view('includes/footer'); ?>
