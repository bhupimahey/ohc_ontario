<?php
$header = array('title' => 'Update Admins');
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
	      <?php echo $validation;?>
	   <div class="card-header border-bottom">
                                <h4 class="card-title"><i data-feather="plus-square"></i> Update Admins Details</h4>
                            </div>
                            <div class="card-body py-2 my-25">
                                <!-- header section -->
                               
                                <form class="mt-2 pt-50" name="doctor-form" id="doctor-form" method="post" acton="<?php echo base_url();?>/users/edit/<?php echo $teacher_id;?>" enctype="multipart/form-data">
                                    <div class="row">		
                                       
                                     <div class="col-12 col-sm-6 mb-1">
                                            <label class="form-label" for="firstName">First Name</label>
                                            <input type="text" class="form-control" id="teacherFname" name="teacherFname" value="<?php echo $teacher_info['first_name'];?>" required>
                                     </div>
                                        
                                     <div class="col-12 col-sm-6 mb-1">
                                            <label class="form-label" for="firstName">Last Name</label>
                                            <input type="text" class="form-control" id="teacherLname" name="teacherLname" value="<?php echo $teacher_info['last_name'];?>">
                                        </div>
                                        
                                        <div class="col-12 col-sm-6 mb-1">
                                            <label class="form-label" for="firstName">Mobile</label>
                                            <input type="text" class="form-control phone-number-mask" id="teacherMobile" name="teacherMobile" value="<?php echo $teacher_info['mobile_no'];?>" required>
                                        </div>			
                                        
                                       <div class="col-12 col-sm-6 mb-1">
                                            <label class="form-label" for="firstName">Password</label>
                                            <input type="password" class="form-control" id="teacherPassword" name="teacherPassword">
                                        </div>
                                        
                                        <div class="col-12 col-sm-6 mb-1">
                                            <label class="form-label" for="firstName">Photo</label>
                                            <input type="file" class="form-control" id="teacherPhoto" name="teacherPhoto">
                                        </div>
                                        	<div class="col-12 col-sm-6 mb-1">
                                            <label class="form-label" for="firstName">User Role</label>
                                             <?php  
											      echo form_dropdown("user_role",$user_roles,$teacher_info['account_type'],'class="form-control select2" id="user_role" required');
												?>
                                        </div>
										
										<div class="col-12 col-sm-6 mb-1">
                                            <label class="form-label" for="firstName">Address</label>
                                            <input type="text" class="form-control" id="teacherAddress" name="teacherAddress" value="<?php echo $teacher_info['address'];?>"  required>
                                        </div>
                                        
                                          <div class="col-12 col-sm-6 mb-1">
                                            <label class="form-label" for="firstName">User Status</label>
                                             <?php  
                                             $user_status = array('1'=>'Active','0'=>'Inactive');
											      echo form_dropdown("user_status",$user_status,$teacher_info['account_status'],'class="form-control select2" id="user_status" required');
												?>
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
<script>
 $(document).ready(function(){
                $("#student_ids").select2({ placeholder: "Choose" });
         $("#user_role").select2({ placeholder: "Choose" });
    }); 
  </script>
<?php echo view('includes/footer'); ?>	