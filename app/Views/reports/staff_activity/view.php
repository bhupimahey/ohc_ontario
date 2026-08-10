<?php $header = array('title' => 'Staff Activity Report'); ?>
<?php echo view('includes/header',$header); ?>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern  navbar-floating footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="">

    <!-- Admin Header starts -->
	 <?php echo view('includes/inner_header'); ?>
	<!-- Admin Header ends -->



    <!-- BEGIN: Main Menu-->
 <?php echo view('includes/menu'); ?>
    <!-- END: Main Menu-->

    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
			
            </div>
            <div class="content-body">
                <!-- Basic Tables start -->
                <div class="row">
					 <div class="col-12">
					 <?php echo $message_output->run(); ?>
					 </div>
                    <div class="col-12">
                        <div class="card">
							 <div class="card-header border-bottom">
                                    <h4 class="card-title"><i data-feather="users"></i> Staff Activity Report</h4>
                                  
                                </div>
                             
                            <div class="card-body mt-2">
                                
                                    <form class="dt_adv_search">
                                        <div class="row g-1 mb-md-1">
                                            <div class="col-md-4">
                                                <label class="form-label">Staff:</label>
                                               <?php  
                        						echo form_dropdown("filter_staff_id",$StaffDropdown,'','class="form-control select2" id="filter_staff_id" ');
                        						?> 
                                            </div>
                                            
											<div class="col-md-4">
                                                <label class="form-label">Date Range:</label>
                                                <input type="text" name="filter_daterange" id="filter_daterange" class="form-control flatpickr-range" data-column="1" placeholder="YYYY-MM-DD to YYYY-MM-DD" data-column-index="0">
                                            </div>
                                           
                                          <div class="col-md-1">
                                                <button  type="button" id="search_staff_btn" class="btn btn-primary me-1 waves-effect waves-float waves-light manage_button" style="margin-top:23px;">Search</button>
                                            </div>
                                           <div class="col-md-1">
										  <img src="<?php echo base_url();?>/public/img/excel.png" id="export_excel" title="Download Excel" style="cursor:pointer;float:right;margin-top:17px;width:47px;"  title="download excel" id="export_excel" >
										 
                                                  </div>   
										</div>	
                                    </form>
                                </div>
                                
                            <div class="table-responsive">
                               
                                <table class="dt-responsive table dataTable" id="activity_table">
                                    <thead class="table-dark">
                                        <tr>
                                           <th>#JOBID</th>
                                            <th>Staff Name</th>
                                            <th>Job Title</th>
                                            <th>Hours Worked</th>
                                            <th>Customer Location</th>
                                            <th>Visited On</th>
                                        </tr>
                                    </thead>
                                    <tbody>
										
                                    </tbody>
                                </table>
                             
                                
								<br><br>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Basic Tables end -->

            </div>
        </div>
    </div>
    <!-- END: Content-->
<?php echo view('includes/footer_scripts'); ?>

   <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/public/app-assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/public/app-assets/vendors/css/tables/datatable/responsive.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/public/app-assets/vendors/css/tables/datatable/buttons.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/public/app-assets/vendors/css/tables/datatable/rowGroup.bootstrap5.min.css">
	
	
	 <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/public/app-assets/vendors/css/pickers/pickadate/pickadate.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/public/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css">
    
	 <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/public/app-assets/css/plugins/forms/pickers/form-flat-pickr.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/public/app-assets/css/plugins/forms/pickers/form-pickadate.css">
    
    
    <script src="<?php echo base_url(); ?>/public/app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url(); ?>/public/app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js"></script>
    <script src="<?php echo base_url(); ?>/public/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js"></script>
    <script src="<?php echo base_url(); ?>/public/app-assets/vendors/js/tables/datatable/responsive.bootstrap5.min.js"></script>
    <script src="<?php echo base_url(); ?>/public/app-assets/vendors/js/tables/datatable/datatables.checkboxes.min.js"></script>
    <script src="<?php echo base_url(); ?>/public/app-assets/vendors/js/tables/datatable/datatables.buttons.min.js"></script>
    <script src="<?php echo base_url(); ?>/public/app-assets/vendors/js/tables/datatable/jszip.min.js"></script>
    <script src="<?php echo base_url(); ?>/public/app-assets/vendors/js/tables/datatable/pdfmake.min.js"></script>
	 <script src="<?php echo base_url(); ?>/public/app-assets/vendors/js/tables/datatable/vfs_fonts.js"></script>
    <script src="<?php echo base_url(); ?>/public/app-assets/vendors/js/tables/datatable/buttons.html5.min.js"></script>
    <script src="<?php echo base_url(); ?>/public/app-assets/vendors/js/tables/datatable/buttons.print.min.js"></script>
    <script src="<?php echo base_url(); ?>/public/app-assets/vendors/js/tables/datatable/dataTables.rowGroup.min.js"></script>
    <script src="<?php echo base_url(); ?>/public/assets/js/activity_datatable.js"></script>	
    <script src="<?php echo base_url();?>/public/app-assets/vendors/js/forms/cleave/cleave.min.js"></script>
   <script src="<?php echo base_url();?>/public/app-assets/vendors/js/forms/cleave/addons/cleave-phone.us.js"></script>
 <script src="<?php echo base_url();?>/public/app-assets/js/scripts/forms/form-input-mask.js"></script>
 
 <script src="<?php echo base_url();?>/public/app-assets/vendors/js/pickers/pickadate/picker.js"></script>
    <script src="<?php echo base_url();?>/public/app-assets/vendors/js/pickers/pickadate/picker.date.js"></script>
    <script src="<?php echo base_url();?>/public/app-assets/vendors/js/pickers/pickadate/picker.time.js"></script>
    <script src="<?php echo base_url();?>/public/app-assets/vendors/js/pickers/pickadate/legacy.js"></script>
    <script src="<?php echo base_url();?>/public/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js"></script>
    <script src="<?php echo base_url();?>/public/app-assets/js/scripts/forms/pickers/form-pickers.js"></script>

 <style>.dataTables_length label{float: right;margin-right: 21px;}
  .table-responsive{margin-top:-20px!important;}
  .form-select-sm {padding-top: 9px!important;}
 #search_users_btn{margin-top:23px!important;}
 .select2-container--default .select2-selection--single .select2-selection__arrow b{margin-left: -4px;
    margin-top: -2px;position: relative!important;top: 50%;width: 0;left:0!important;height: 0!important;border-width:0px!important;}
     .buttons-excel{display:none;}
 </style>
<script>
  $('#export_excel').on("click",function(){ 
      
     	var filter_staff_id   = $("#filter_staff_id").val();
	var filter_daterange     = $("#filter_daterange").val();
	
window.location.href=	baseurl+"/report/export_staff_activity/?filter_staff_id="+filter_staff_id+"&filter_daterange="+filter_daterange;
	
   });

</script>
<?php echo view('includes/footer'); ?>