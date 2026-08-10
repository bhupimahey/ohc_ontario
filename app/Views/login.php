<?php $header = array('title' => 'Login');  ?>
<?php echo view('includes/header',$header); ?>
  <!-- END: Head-->
  <body class="horizontal-layout horizontal-menu blank-page navbar-floating footer-static" data-open="hover" data-menu="horizontal-menu" data-col="blank-page">
    <!-- BEGIN: Content-->
    <div class="app-content content ">
      <div class="content-overlay"></div>
      <div class="header-navbar-shadow"></div>
      <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body">
          <div class="auth-wrapper auth-cover">
            <div class="auth-inner row m-0">
              <a class="brand-logo" href="<?php echo base_url();?>"><img src="<?php echo base_url();?>/public/app-assets/images/apple-icon-57x57.png" >  </a>
             
              <!-- Left Text-->
              <div class="d-none d-lg-flex col-lg-8 align-items-center p-5">
                <div class="w-100 d-lg-flex align-items-center justify-content-center px-5"><img class="img-fluid" src="<?php echo base_url();?>/public/app-assets/images/pages/login-v2.svg" alt="Login V2"/></div>
              </div>
              <!-- /Left Text-->
              <!-- Login-->
              <div class="d-flex col-lg-4 align-items-center auth-bg px-2 p-lg-5">
                <div class="col-12 col-sm-8 col-md-6 col-lg-12 px-xl-2 mx-auto">
                 
                  <p class="card-text mb-2">Please sign-in to your account</p>
                  <p class="card-text mb-2 text-muted">
                    Next Renewal On :
                    <strong><?= esc($next_renewal ?? '05 May,2026'); ?></strong>
                  </p>
                  <?php echo $message_output->run() ;?>
				  
				  <form class="auth-login-form mt-2" action="<?php echo base_url();?>/login" method="POST">
                    <div class="mb-1">
                      <label class="form-label" for="login-email">Login Id</label>
                      <input class="form-control" id="login-email" type="text" name="login-email" placeholder="Login Id" aria-describedby="login-email" autofocus="" tabindex="1"/>
                    </div>
                    <div class="mb-1">
                      <div class="input-group input-group-merge form-password-toggle">
                        <input class="form-control form-control-merge" id="login-password" type="password" name="login-password" placeholder="············" aria-describedby="login-password" tabindex="2"/><span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                      </div>
                    </div>                   
                    <button class="btn btn-primary w-100" tabindex="4">Sign in</button>
                  </form>               
                    </div>
              </div>
              <!-- /Login-->
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- END: Content-->
    <!-- END: Content-->
<?php echo view('includes/footer_scripts'); ?>
<?php echo view('includes/footer'); ?>