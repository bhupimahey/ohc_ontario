<?php $header = array('title' => 'Gross Revenue Report'); ?>
<?php echo view('includes/header', $header); ?>

<body class="vertical-layout vertical-menu-modern navbar-floating footer-static" data-open="click" data-menu="vertical-menu-modern" data-col="">

<?php echo view('includes/inner_header'); ?>
<?php echo view('includes/menu'); ?>

<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row"></div>
        <div class="content-body">

            <div class="card">
                <div class="card-header border-bottom">
                    <h4 class="card-title">
                        <i data-feather="dollar-sign"></i>
                        Gross Revenue Report
                    </h4>
                </div>

                <div class="card-body mt-2">
                    <ul class="nav nav-tabs" role="tablist" id="gross_revenue_tabs">
                        <li class="nav-item">
                            <a class="nav-link active" href="javascript:void(0);" data-report-type="monthly" role="tab">Monthly</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" data-report-type="weekly" role="tab">Weekly</a>
                        </li>
                    </ul>

                    <div class="d-flex justify-content-end mt-1 mb-1">
                        <img src="<?= base_url(); ?>/public/img/excel.png"
                             id="export_excel"
                             style="cursor:pointer;width:45px;"
                             title="Download Excel"
                             alt="Export Excel">
                    </div>

                    <input type="hidden" id="report_type" value="monthly">

                    <div class="table-responsive">
                        <table class="table table-striped" id="gross_revenue_table">
                            <thead class="table-dark">
                            <tr>
                                <th>Period</th>
                                <th>Total Jobs</th>
                                <th>Gross Revenue</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php echo view('includes/footer_scripts'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/public/app-assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/public/app-assets/vendors/css/tables/datatable/responsive.bootstrap5.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/public/app-assets/vendors/css/tables/datatable/buttons.bootstrap5.min.css">

<script src="<?php echo base_url(); ?>/public/app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>/public/app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js"></script>
<script src="<?php echo base_url(); ?>/public/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js"></script>
<script src="<?php echo base_url(); ?>/public/app-assets/vendors/js/tables/datatable/responsive.bootstrap5.min.js"></script>
<script src="<?php echo base_url(); ?>/public/app-assets/vendors/js/tables/datatable/datatables.buttons.min.js"></script>
<script src="<?php echo base_url(); ?>/public/app-assets/vendors/js/tables/datatable/jszip.min.js"></script>
<script src="<?php echo base_url(); ?>/public/app-assets/vendors/js/tables/datatable/buttons.html5.min.js"></script>
<script src="<?php echo base_url(); ?>/public/assets/js/grossrevenue_datatable.js"></script>

<style>
.dataTables_length label { float: right; margin-right: 21px; }
.form-select-sm { padding-top: 9px !important; }
</style>
<?php echo view('includes/footer'); ?>
