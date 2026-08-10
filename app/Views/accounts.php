<?php 
$header = array('title' => 'Accounts');
echo view('includes/header',$header);

$gross_collection = $gross_collection ?? 0;
$material_cost    = $material_cost ?? 0;
$labour_cost      = $labour_cost ?? 0;
$total_received   = $total_received ?? 0;

$profit        = $gross_collection - ($material_cost + $labour_cost);
$total_pending = $gross_collection - $total_received;
?>

<?php echo view('includes/header',$header); ?>
<body class="vertical-layout vertical-menu-modern navbar-floating footer-static" data-open="click" data-menu="vertical-menu-modern" data-col="">
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
            <div class="content-header row"></div>
            <div class="content-body">
                <section id="dashboard-ecommerce">
                    <div class="row match-height">
                        <div class="col-xl-12 col-md-12 col-12">
                            <div class="card card-statistics">
                                <div class="card-header">                   <?php					$start_date = $FiscalYearDate['start_date'];
                                $end_date = $FiscalYearDate['end_date']; 				   ?>
                                    <h4 class="card-title">Accounts</h4>
                                </div>
                                   <div class="card-body mt-2">
                                    <form class="dt_adv_search" action="">
                                        <div class="row g-1 mb-md-1">
                                            
                                            	<div class="col-md-4">
                                                <label class="form-label">From Date:</label>
                                                <input type="text" name="filter_fromdate" id="filter_fromdate" class="form-control flatpickr-basic" data-column="1" value="<?php echo date('Y-m-d',strtotime($start_date));?>" placeholder="YYYY-MM-DD" data-column-index="0">
                                            </div>
                                            	<div class="col-md-4">
                                                <label class="form-label">To Date:</label>
                                                <input type="text" name="filter_todate" id="filter_todate" class="form-control flatpickr-basic" data-column="1" value="<?php echo date('Y-m-d',strtotime($end_date));?>" placeholder="YYYY-MM-DD" data-column-index="0">
                                            </div>
                                            <div class="col-md-1">
                                                <button id="search_users_btn" type="submit" style="margin-top:24px;" class="btn btn-primary me-1 waves-effect waves-float waves-light manage_button">Search</button>
												 </div>  <div class="col-md-1">
												 <img src="<?php echo base_url();?>/public/img/excel.png" id="export_excel" title="Download Excel" style="cursor:pointer;float:right;margin-top:17px;width:47px;"  title="download excel" id="export_excel" >
										 
                                            </div>
											
                                            
					                          </div>	
											
										<div class="row g-1 mb-md-1">		
									      
                                        </div>
                                    </form>
                                </div>
                                
                                <div class="card-body statistics-body">
                                    <div class="row border rounded p-3 mt-4">
                                        <div class="col-12 col-sm-4">
                                            <div class="d-flex gap-2 align-items-center">
                                                <div class="badge rounded bg-label-primary p-1"><i class="ti ti-chart-pie-2 ti-sm"></i></div>
                                                <h4 class="mb-0">Gross Collection</h4>
                                            </div>
                                            <h4 class="my-2 pt-1">$<?php echo number_format($gross_collection,2);?></h4>
                                            <div class="progress w-75" style="height: 4px;">
                                                <div class="progress-bar bg-info" role="progressbar" style="width: 50%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>

                                        <div class="col-12 col-sm-4">
                                            <div class="d-flex gap-2 align-items-center">
                                                <div class="badge rounded bg-label-info p-1"><i class="ti ti-chart-pie-2 ti-sm"></i></div>
                                                <h4 class="mb-0">Matrial Cost</h4>
                                            </div>
                                            <h4 class="my-2 pt-1">$<?php echo number_format($material_cost,2);?></h4>
                                            <div class="progress w-75" style="height: 4px;">
                                                <div class="progress-bar bg-info" role="progressbar" style="width: 50%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>

                                        <div class="col-12 col-sm-4">
                                            <div class="d-flex gap-2 align-items-center">
                                                <div class="badge rounded bg-label-danger p-1"><i class="ti ti-chart-pie-2 ti-sm"></i></div>
                                                <h4 class="mb-0">Labour Cost</h4>
                                            </div>
                                            <h4 class="my-2 pt-1">$<?php echo ($labour_cost>0)? number_format($labour_cost,2):0;?></h4>
                                            <div class="progress w-75" style="height: 4px;">
                                                <div class="progress-bar bg-info" role="progressbar" style="width: 50%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <br />
                                        <br />
                                    </div>
                                    <div class="row border rounded p-3 mt-4">
                                        <div class="col-12 col-sm-4">
                                            <div class="d-flex gap-2 align-items-center">
                                                <div class="badge rounded bg-label-info p-1"><i class="ti ti-chart-pie-2 ti-sm"></i></div>
                                                <h4 class="mb-0">Profit</h4>
                                            </div>
                                            <h4 class="my-2 pt-1">$<?php echo number_format($profit,2);?></h4>
                                            <div class="progress w-75" style="height: 4px;">
                                                <div class="progress-bar bg-info" role="progressbar" style="width: 50%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>

                                        <div class="col-12 col-sm-4">
                                            <div class="d-flex gap-2 align-items-center">
                                                <div class="badge rounded bg-label-info p-1"><i class="ti ti-chart-pie-2 ti-sm"></i></div>
                                                <h4 class="mb-0">Total Received</h4>
                                            </div>
                                            <h4 class="my-2 pt-1">$<?php echo number_format($total_received,2);?></h4>
                                            <div class="progress w-75" style="height: 4px;">
                                                <div class="progress-bar bg-info" role="progressbar" style="width: 50%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>

                                        <div class="col-12 col-sm-4">
                                            <div class="d-flex gap-2 align-items-center">
                                                <div class="badge rounded bg-label-info p-1"><i class="ti ti-chart-pie-2 ti-sm"></i></div>
                                                <h4 class="mb-0">Total Pending</h4>
                                            </div>
                                            <h4 class="my-2 pt-1">$<?php echo number_format($total_pending,2);?></h4>
                                            <div class="progress w-75" style="height: 4px;">
                                                <div class="progress-bar bg-info" role="progressbar" style="width: 50%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--/ Statistics Card -->
                    </div>
                </section>
            </div>
        </div>
    </div>
    <?php echo view('includes/footer_scripts'); ?>

  
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/public/app-assets/vendors/css/pickers/pickadate/pickadate.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/public/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/public/app-assets/css/plugins/forms/pickers/form-flat-pickr.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/public/app-assets/css/plugins/forms/pickers/form-pickadate.css" />
    <script src="<?php echo base_url();?>/public/app-assets/vendors/js/forms/cleave/cleave.min.js"></script>
    <script src="<?php echo base_url();?>/public/app-assets/vendors/js/forms/cleave/addons/cleave-phone.us.js"></script>
    <script src="<?php echo base_url();?>/public/app-assets/js/scripts/forms/form-input-mask.js"></script>
    <script src="<?php echo base_url();?>/public/app-assets/vendors/js/pickers/pickadate/picker.js"></script>
    <script src="<?php echo base_url();?>/public/app-assets/vendors/js/pickers/pickadate/picker.date.js"></script>
    <script src="<?php echo base_url();?>/public/app-assets/vendors/js/pickers/pickadate/picker.time.js"></script>
    <script src="<?php echo base_url();?>/public/app-assets/vendors/js/pickers/pickadate/legacy.js"></script>
    <script src="<?php echo base_url();?>/public/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js"></script>
    <script src="<?php echo base_url();?>/public/app-assets/js/scripts/forms/pickers/form-pickers.js"></script>
    <script src="<?php echo base_url();?>/public/app-assets/js/dashboards-analytics.js"></script>
<script>
$("#export_excel").on("click",function(){
	var filter_fromdate   = $("#filter_fromdate").val();
	var filter_todate = $("#filter_todate").val();
	
   window.location.href=	baseurl+"/dashboard/export_accounts/?filter_fromdate="+filter_fromdate+"&filter_todate="+filter_todate;
	
});
</script>
    <?php echo view('includes/footer'); ?>
</body>
