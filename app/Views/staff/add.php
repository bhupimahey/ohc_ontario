<?php
$header = array(
	'title' => 'Add Staff'
);
?>
<?php echo view('includes/header',$header); ?>
<body class="vertical-layout vertical-menu-modern  navbar-floating footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="">

    <!-- BEGIN: Header-->
   <?php echo view('includes/inner_header'); ?>
    

    <!-- BEGIN: Main Menu-->
    <?php echo view('includes/menu'); ?>
	<div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
			
            </div>
            <div class="content-body">
	
	<div class="row">
	 <div class="col-12">
	  <div class="card">
	   <div class="card-header border-bottom">
                                <h4 class="card-title"><i data-feather="plus-square"></i> Add Staff Details</h4>
                            </div>
                            <div class="card-body py-2 my-25">
                                <!-- header section -->
                               
                                <form class="mt-2 pt-50" name="doctor-form" id="doctor-form" method="post" acton="<?php echo base_url();?>/staff/add" enctype="multipart/form-data">
                                    <div class="row">		
                                       
                                    
                                     <div class="col-12 col-sm-6 mb-1">
                                            <label class="form-label" for="firstName">First Name</label>
                                            <input type="text" class="form-control" id="staffFname" name="staffFname" required>
                                        </div>
                                        
                                     <div class="col-12 col-sm-6 mb-1">
                                            <label class="form-label" for="firstName">Last Name</label>
                                            <input type="text" class="form-control" id="staffLname" name="staffLname">
                                        </div>
                                        
                                        
                                        <div class="col-12 col-sm-6 mb-1">
                                            <label class="form-label" for="firstName">Mobile</label>
                                            <input type="text" class="form-control phone-number-mask" id="staffMobile" name="staffMobile" required>
                                        </div>										
                                        <div class="col-12 col-sm-6 mb-1">
                                            <label class="form-label" for="firstName">Photo</label>
                                            <input type="file" class="form-control" id="staffPhoto" name="staffPhoto">
                                        </div>
									  <div class="col-12 col-sm-6 mb-1">
                                            <label class="form-label" for="firstName">Hourly Pay($)</label>
                                            <input type="text" class="form-control" id="hourly_rate" name="hourly_rate">
                                        </div>
                                        
                                       	<div class="col-12 col-sm-6 mb-1">
                                            <label class="form-label" for="firstName">Address</label>
                                            
                                            <input type="text" class="form-control" id="staffAddress" name="staffAddress" required>
                                        </div>
                                       
										
										<div class="col-12">
                                            <button type="submit" class="btn btn-primary mt-1 me-1 waves-effect waves-float waves-light">Save changes</button>
                                        </div>
										
                                    </div>
                                </form>
                                <!--/ form -->
                            </div>
                        </div>
	 </div>
	</div>
	
</div></div></div>	
  <?php echo view('includes/footer_scripts'); ?>
  <script src="<?php echo base_url();?>/public/app-assets/vendors/js/forms/cleave/cleave.min.js"></script>
  <script src="<?php echo base_url();?>/public/app-assets/vendors/js/forms/cleave/addons/cleave-phone.us.js"></script>
  <script src="<?php echo base_url();?>/public/app-assets/js/scripts/forms/form-input-mask.js"></script>
  <script src="<?php echo base_url();?>/public/app-assets/js/scripts/forms/form-validation.js"></script>
  <?php echo view('includes/footer'); ?>	