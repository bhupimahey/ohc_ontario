<?php $header = array('title' => 'Job Profit Summary Report'); ?>
<?php echo view('includes/header',$header); ?>

<body class="vertical-layout vertical-menu-modern  navbar-floating footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="">

<?php echo view('includes/inner_header'); ?>
<?php echo view('includes/menu'); ?>

<div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
			
            </div>

<div class="content-body">

<div class="card">
<div class="card-header border-bottom">
    <h4 class="card-title">
        <i data-feather="bar-chart-2"></i>
        Job Profit Summary Report
    </h4>
</div>

<div class="card-body mt-2">

<form class="dt_adv_search">
<div class="row g-1">

    

    <!-- REPORT TYPE -->
    <div class="col-md-4">
        <label class="form-label">Report Type</label>
        <select id="report_type" class="form-select">
            <option value="weekly">Weekly</option>
            <option value="monthly" selected>Monthly</option>
            <option value="yearly">Yearly</option>
        </select>
    </div>

   

    <!-- SEARCH BUTTON -->
    <div class="col-md-1">
        <button type="button" id="search_btn"
            class="btn btn-primary waves-effect"
            style="margin-top:23px;">
            Search
        </button>
    </div>

    <!-- EXPORT -->
    <div class="col-md-1">
        <img src="<?= base_url();?>/public/img/excel.png"
             id="export_excel"
             style="cursor:pointer;margin-top:17px;width:45px;"
             title="Download Excel">
    </div>

</div>
</form>

</div>

<div class="table-responsive">

<table class="table table-striped" id="summary_table">
<thead class="table-dark">
<tr>
    <th>Period</th>
    <th>Total Jobs</th>
    <th>Labour Cost</th>
    <th>Material Cost</th>
    <th>Total Cost</th>
    <th>Total Profit</th>
</tr>
</thead>
<tbody></tbody>
</table>

</div>
<br /><br />
</div>

</div>


</div>
</div>

<?php echo view('includes/footer_scripts'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/public/app-assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/public/app-assets/vendors/css/tables/datatable/responsive.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/public/app-assets/vendors/css/tables/datatable/buttons.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/public/app-assets/vendors/css/tables/datatable/rowGroup.bootstrap5.min.css">
	
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
   <script src="<?php echo base_url(); ?>/public/assets/js/profitsummary_datatable.js"></script>	
   <script src="<?php echo base_url();?>/public/app-assets/vendors/js/forms/cleave/cleave.min.js"></script>
   <script src="<?php echo base_url();?>/public/app-assets/vendors/js/forms/cleave/addons/cleave-phone.us.js"></script>
 <script src="<?php echo base_url();?>/public/app-assets/js/scripts/forms/form-input-mask.js"></script>

 <style>.dataTables_length label{float: right;margin-right: 21px;}  
 .form-select-sm {padding-top: 9px!important;}
 #search_users_btn{margin-top:23px!important;}
 .select2-container--default .select2-selection--single .select2-selection__arrow b{margin-left: -4px;
    margin-top: -2px;position: relative!important;top: 50%;width: 0;left:0!important;height: 0!important;border-width:0px!important;}
 
 </style>
<?php echo view('includes/footer'); ?>