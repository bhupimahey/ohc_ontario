<?php $header = array( 	'title' => 'Home' ); ?>
<?php echo view('includes/header',$header); ?>
<body class="vertical-layout vertical-menu-modern  navbar-floating footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="">

    <!-- BEGIN: Header-->
   <?php echo view('includes/inner_header'); ?>
    

    <!-- BEGIN: Main Menu-->
    <?php echo view('includes/menu'); ?>
    <!-- END: Main Menu-->

    <!-- BEGIN: Content-->
    <div class="app-content content">
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
                        <div class="card">
							 <div class="card-header border-bottom">
                                    <h4 class="card-title"><i data-feather="users"></i> View Customers</h4>
                                    <div style="float:right;"><h4><?php echo date('d M, Y');?></h4></div>
                                </div>
                            <div class="card-body mt-2">
                                    <form class="dt_adv_search">
                                        <div class="row g-1 mb-md-1">
                                             <div class="col-md-3">
                                                <label class="form-label">Name:</label>
                                                <input type="text" name="filter_search_name" id="filter_search_name" class="form-control dt-input dt-full-name" data-column="1" placeholder="Search by name" data-column-index="0">
                                            </div>
											
											<div class="col-md-3">
                                                <label class="form-label">Mobile:</label>
                                                <input type="text" name="filter_search_mobile" id="filter_search_mobile" class="form-control dt-input dt-full-name phone-number-mask" data-column="1" placeholder="Search by mobile" data-column-index="0">
                                            </div>
                                             	<div class="col-md-3">
                                                <label class="form-label">Date Range:</label>
                                                <input type="text" name="filter_daterange" id="filter_daterange" class="form-control flatpickr-range" data-column="1" placeholder="YYYY-MM-DD to YYYY-MM-DD" data-column-index="0">
                                            </div> 
                                            
                                            <div class="col-md-2">
                                                 <label class="form-label" style="margin-top:10px;"></label>
                                                <button id="search_users_btn" type="button" style="margin-top:23px;" class="btn btn-primary me-1 waves-effect waves-float waves-light manage_button">Search</button>
                                            </div>
                                            
                                            
					                     </div>	
											
									
                                    </form>
                                </div>
                                
                            <div class="table-responsive">
                                <table class="table" id="admissions_table1">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Sr.No.</th>
                                            <th>Full Name</th>
                                            <th>Address</th>
                                            <th>Material <br>Cost</th>
                                            <th>Labour <br>Cost</th>
                                            <th>Collection <br>Cost</th>
                                            <th>Pending <br>Payment</th>
                                          	<th>Enry<br> Date</th>	
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
    <script src="<?php echo base_url(); ?>/public/app-assets/vendors/js/tables/datatable/buttons.html5.min.js"></script>
    <script src="<?php echo base_url(); ?>/public/app-assets/vendors/js/tables/datatable/buttons.print.min.js"></script>
    <script src="<?php echo base_url(); ?>/public/app-assets/vendors/js/tables/datatable/dataTables.rowGroup.min.js"></script>
  <!--  <script src="<?php echo base_url(); ?>/public/assets/js/dashboard_admissions_datatable.js"></script>	-->
    <script src="<?php echo base_url();?>/public/app-assets/vendors/js/forms/cleave/cleave.min.js"></script>
    <script src="<?php echo base_url();?>/public/app-assets/vendors/js/forms/cleave/addons/cleave-phone.us.js"></script>
    <script src="<?php echo base_url();?>/public/app-assets/js/scripts/forms/form-input-mask.js"></script>
    <script src="<?php echo base_url();?>/public/app-assets/vendors/js/pickers/pickadate/picker.js"></script>
    <script src="<?php echo base_url();?>/public/app-assets/vendors/js/pickers/pickadate/picker.date.js"></script>
    <script src="<?php echo base_url();?>/public/app-assets/vendors/js/pickers/pickadate/picker.time.js"></script>
    <script src="<?php echo base_url();?>/public/app-assets/vendors/js/pickers/pickadate/legacy.js"></script>
    <script src="<?php echo base_url();?>/public/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js"></script>
    <script src="<?php echo base_url();?>/public/app-assets/js/scripts/forms/pickers/form-pickers.js"></script>
    
<?php echo view('includes/footer'); ?>	