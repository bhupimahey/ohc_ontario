<?php
$header = array(
	'title' => 'View Purchase Orders'
);
?>
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
                                    <h4 class="card-title"><i data-feather="shopping-cart"></i> View Purchase Orders</h4>
                                    
                                </div>
                             
                            <div class="card-body mt-2">
                                    <form class="dt_adv_search">
                                        <div class="row g-1 mb-md-1">
                                            
                                              <div class="col-md-3">
                                                <label class="form-label">Choose Job:</label>
                                            <?php  
                        						echo form_dropdown("filter_job_id",$JobsDropdown,'','class="form-control select2" id="filter_job_id" ');
                        						?>   </div>
                        						
                                             <div class="col-md-3">
                                                <label class="form-label">Choose Vendor:</label>
                                            <?php  
                        						echo form_dropdown("filter_vendor_id",$all_vendors,'','class="form-control select2" id="filter_vendor_id" ');
                        						?>   </div>
										
                                            	<div class="col-md-3">
                                                <label class="form-label">Order Date:</label>
                                                <input type="text" name="filter_daterange" id="filter_daterange" class="form-control flatpickr-range" data-column="1" placeholder="YYYY-MM-DD to YYYY-MM-DD" data-column-index="0">
                                            </div>
                                            <div class="col-md-1">
                                                <button id="search_users_btn" type="button" class="btn btn-primary me-1 waves-effect waves-float waves-light manage_button">Search</button>
                                            </div>
                                             <div class="col-md-1">
										    <img src="<?php echo base_url();?>/public/img/excel.png" id="export_excel" title="Download Excel" style="cursor:pointer;float:right;margin-top:17px;width:47px;"  title="download excel" id="export_excel" >
										 
                                                  </div> 
					                          </div>	
											
										<div class="row g-1 mb-md-1">		
									      
                                        </div>
                                    </form>
                                </div>
                                
                           
                                <table class="table dataTable" id="purchaseorder_table">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>#OrderNo</th>
                                            <th>#JobId</th>
                                            <th>Vendor Name</th>                                            
                                            <th>Items List</th>
                                            <th>Total</th>
                                            <th>File</th>
                                            <th>Remarks</th>
											<th>Order Date</th>
											<th>Action</th> 
                                        </tr>
                                    </thead>
                                    <tbody>
										
                                    </tbody>
                                    <tfoot>
            <tr>
                <th colspan="8" style="text-align:right">Total:</th>
                <th></th>
                
            </tr>
        </tfoot>
                                </table>
								<br><br>
                           
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
    <script src="<?php echo base_url(); ?>/public/app-assets/vendors/js/tables/datatable/buttons.html5.min.js"></script>
    <script src="<?php echo base_url(); ?>/public/app-assets/vendors/js/tables/datatable/buttons.print.min.js"></script>
    <script src="<?php echo base_url(); ?>/public/app-assets/vendors/js/tables/datatable/dataTables.rowGroup.min.js"></script>
   <script src="<?php echo base_url(); ?>/public/assets/js/purchaseorder_datatable.js"></script>	
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
 </style>
 
<script>
     $(document).ready(function(){
    $("#filter_vendor_id").select2({
    placeholder: "Choose"
});

 $("#filter_job_id").select2({
    placeholder: "Choose"
});
    
});
  $("#export_excel").on("click",function(){
	var filter_vendor_id     = $("#filter_vendor_id").val();
	var filter_daterange     = $("#filter_daterange").val();

window.location.href=	baseurl+"/purchase_orders/export_purchase_orders/?filter_vendor_id="+filter_vendor_id+"&filter_daterange="+filter_daterange;
	
});
 </script>
<?php echo view('includes/footer'); ?>