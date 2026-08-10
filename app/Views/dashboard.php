<?php $header = array( 	'title' => 'Home' ); ?>
<?php echo view('includes/header',$header); 
$jobsmodel       = new \App\Models\JobsModel;

?>
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
             <section id="dashboard-ecommerce">
                <div class="row match-height">
                       
                        <div class="col-xl-12 col-md-12 col-12">
                            <div class="card card-statistics">
                                <div class="card-header">
                                    <h4 class="card-title">Statistics</h4>
                                   
                                </div>
                                <div class="card-body statistics-body">
                                    <div class="row">
                                        
                                        <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                                            <a href="<?php echo base_url();?>/users" target="_blank">
                                            <div class="d-flex flex-row">
                                                <div class="avatar bg-light-info me-2">
                                                    <div class="avatar-content">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user avatar-icon"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                                    </div>
                                                </div>
                                                <div class="my-auto">
                                                    <h4 class="fw-bolder mb-0"><?php echo $total_staff;?></h4>
                                                    <p class="card-text font-small-3 mb-0">Staff</p>
                                                </div>
                                            </div>
                                            </a>
                                        </div>
                                        
                                       
                                        <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                                            <a href="<?php echo base_url();?>/jobs" target="_blank">
                                            <div class="d-flex flex-row">
                                                <div class="avatar bg-light-primary me-2">
                                                    <div class="avatar-content">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trending-up avatar-icon"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg>
                                                    </div>
                                                </div>
                                                <div class="my-auto">
                                                    <h4 class="fw-bolder mb-0"><?php echo $total_jobs;?></h4>
                                                    <p class="card-text font-small-3 mb-0">Jobs</p>
                                                </div>
                                            </div>
                                            </a>
                                        </div>
                                        <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                                            <a href="<?php echo base_url();?>/customers" target="_blank">
                                            <div class="d-flex flex-row">
                                                <div class="avatar bg-light-info me-2">
                                                    <div class="avatar-content">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user avatar-icon"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                                    </div>
                                                </div>
                                                <div class="my-auto">
                                                    <h4 class="fw-bolder mb-0"><?php echo $total_customers;?></h4>
                                                    <p class="card-text font-small-3 mb-0">Customers</p>
                                                </div>
                                            </div>
                                            </a>
                                        </div>
                                        <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-sm-0">
                                            <a href="<?php echo base_url();?>/quotations" target="_blank">
                                            <div class="d-flex flex-row">
                                                <div class="avatar bg-light-danger me-2">
                                                    <div class="avatar-content">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-box avatar-icon"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                                                    </div>
                                                </div>
                                                <div class="my-auto">
                                                    <h4 class="fw-bolder mb-0"><?php echo $total_quotations;?></h4>
                                                    <p class="card-text font-small-3 mb-0">Quotations</p>
                                                </div>
                                            </div>
                                            </a>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--/ Statistics Card -->
                    </div> 
               <div class="row match-height">
                        <!-- Company Table Card -->
                        <div class="col-lg-12 col-12">
                            
                            <div class="card card-company-table card-header">
                                
                                <div class="card-body p-0">
                                    
                                    <table class="table dataTable" id="jobs_table">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>#JobId</th>
                                            <th>Customer<br> Name</th>
                                            <th>Work<br> Details</th>
                                            <th>Staff<br> Name's</th>
                                            <th>Payment</th>
                                            <th>Job<br> Status</th>
											<th>Remarks</th>
											<th>Entry<br> Date</th>	
											<th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
										
                                    </tbody>
                                </table>
                                </div>
                            </div>
                            
                            
                        </div>
                       
                    </div>  
                 
             </section>

            </div>
        </div>
    </div>
    
    <!-- OVERDUE PAYMENT MODAL -->
<div class="modal fade" id="overdueModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">

      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title">
            ⚠ Payment Overdue Reminder
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Job ID</th>
                    <th>Customer</th>
                    <th>Pending</th>
                    <th>Days Overdue</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="overdueTableBody"></tbody>
        </table>

      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>



<?php echo view('includes/footer_scripts'); ?>
<style>
    #overdueModal { display:none; }
</style>

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
   <script src="<?php echo base_url(); ?>/public/assets/js/jobs_datatable.js"></script>	
    <script src="<?php echo base_url();?>/public/app-assets/vendors/js/forms/cleave/cleave.min.js"></script>
    <script src="<?php echo base_url();?>/public/app-assets/vendors/js/forms/cleave/addons/cleave-phone.us.js"></script>
    <script src="<?php echo base_url();?>/public/app-assets/js/scripts/forms/form-input-mask.js"></script>
    <script src="<?php echo base_url();?>/public/app-assets/vendors/js/pickers/pickadate/picker.js"></script>
    <script src="<?php echo base_url();?>/public/app-assets/vendors/js/pickers/pickadate/picker.date.js"></script>
    <script src="<?php echo base_url();?>/public/app-assets/vendors/js/pickers/pickadate/picker.time.js"></script>
    <script src="<?php echo base_url();?>/public/app-assets/vendors/js/pickers/pickadate/legacy.js"></script>
    <script src="<?php echo base_url();?>/public/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js"></script>
    <script src="<?php echo base_url();?>/public/app-assets/js/scripts/forms/pickers/form-pickers.js"></script>
    <script>
        
        $(document).ready(function () {
    checkOverduePayments();
});

function checkOverduePayments()
{
    $.get(baseurl + "/dashboard/overdue_notifications", function(data){

        // ✅ DO NOT SHOW MODAL IF EMPTY
        if(!data || data.length === 0){
            return;
        }

        let rows = '';

        data.forEach(item => {

            let badge = item.severity === 'critical'
                ? '<span class="badge bg-danger">30+ Days</span>'
                : '<span class="badge bg-warning text-dark">15+ Days</span>';

            rows += `
                <tr>
                    <td><strong><a href="jobs/edit/${item.jobs_id}">#${item.jobs_id}</a></strong></td>
                    <td>${item.customer_name}</td>
                    <td>${item.pending_amount}</td>
                    <td>${item.overdue_days}</td>
                    <td>${badge}</td>
                </tr>`;
        });

        $("#overdueTableBody").html(rows);

        const modal = new bootstrap.Modal(document.getElementById('overdueModal'));
        modal.show();

    });
}
    </script>
    
<?php echo view('includes/footer'); ?>	
