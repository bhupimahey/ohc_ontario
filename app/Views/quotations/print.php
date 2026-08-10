<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
  <!-- BEGIN: Head-->
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
    <title>Print Quotation Detail </title>
    <link rel="apple-touch-icon" href="<?php echo base_url();?>/public/app-assets/images/ico/apple-icon-120.png">
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url();?>/public/app-assets/images/ico/favicon.ico">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600" rel="stylesheet">
    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>/public/app-assets/vendors/css/vendors.min.css">
    <!-- END: Vendor CSS-->
    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>/public/app-assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>/public/app-assets/css/bootstrap-extended.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>/public/app-assets/css/colors.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>/public/app-assets/css/components.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>/public/app-assets/css/themes/dark-layout.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>/public/app-assets/css/themes/bordered-layout.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>/public/app-assets/css/themes/semi-dark-layout.min.css">
    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>/public/app-assets/css/core/menu/menu-types/vertical-menu.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>/public/app-assets/css/plugins/forms/form-validation.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>/public/app-assets/css/pages/authentication.css">
    <!-- END: Page CSS-->
    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>/public/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo base_url();?>/public/app-assets/css/app-invoice-print.css" />
    <!-- END: Custom CSS-->
   <script> var baseurl ='<?php echo base_url();?>'; </script>
   <style>
       body{color:#000!important;}
table{color:#000!important;font-size:15px;}
   </style>
  </head>
  <body>

  
  <div class="invoice-print p-5">

  <div class="d-flex justify-content-between flex-row">
    <div class="mb-4">
      <div class="d-flex svg-illustration mb-3 gap-2">
      <img src="<?php echo base_url();?>/public/documents/invoice-logo.jpeg" style="height: 120px;margin-top: -30px;" />
      </div>
      <p class="mb-0">3424 Dorcas Street Mississauga</p>
            <p class="mb-0">+1 (416) 200-0905</p>
            <p class="mb-0">info@hvacohc.ca</p>
           <p class="mb-0">Ontario M9W 0B5, Canada</p>
    </div>
    <div>
      <h4 class="fw-medium">INVOICE #<?php echo sprintf('%05d', $quotation_info['quotation_id']);?></h4>
      <div class="mb-2">
        <span class="text-muted">Date Issues:</span>
        <span class="fw-medium"><?php echo date('M d, Y',strtotime($quotation_info['entry_time']));?></span>
      </div>
      
    </div>
  </div>

  <hr />

  <div class="row d-flex justify-content-between mb-4">
    <div class="col-sm-6 w-50">
      <h6>Invoice To:</h6>
     <p class="mb-1"><?php echo $customer_info['full_name'];?></p>
     <p class="mb-1"><?php echo $customer_info['address'];?></p>
     <p class="mb-1"><?php echo $customer_info['phone_no'];?></p>
     <p class="mb-0"><?php echo $customer_info['email_id'];?></p>
    </div>
    <div class="col-sm-6 w-50">
      <h6>Bill To:</h6>
      <table>
        <tbody>
          <tr>
            <td class="pe-3">Total Due:</td>
            <td class="fw-medium">$<?php echo $quotation_info['net_total'];?></td>
          </tr>
          <tr>
            <td class="pe-3">Bank name:</td>
            <td>American Bank</td>
          </tr>
          <tr>
            <td class="pe-3">Country:</td>
            <td>United States</td>
          </tr>
          <tr>
            <td class="pe-3">IBAN:</td>
            <td>ETD95476213874685</td>
          </tr>
          <tr>
            <td class="pe-3">SWIFT code:</td>
            <td>BR91905</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table m-0">
      <thead class="table-light">
        <tr>
              <th>Service</th>
              <th>Description</th>
              <th>Cost</th>
              <th>Qty</th>
              <th>Price</th>
            </tr>
      </thead>
      <tbody>
          <?php 
                $net_amount =0;
				    if($get_quotation_items){ 
						foreach($get_quotation_items as $jbrow){ ?>  
        <tr>
          <td><?php echo $jbrow['item_name'];?></td>
          <td><?php echo $jbrow['item_desc'];?></td>
          <td>$<?php echo $jbrow['item_cost'];?></td>
          <td>1</td>
          <td>$<?php echo $jbrow['item_cost'];?></td>
        </tr>
        <?php } } ?>
       
        <tr>
          <td colspan="3" class="align-top px-4 py-3">
            <p class="mb-2">
              <span class="me-1 fw-medium">Salesperson:</span>
              <span>Alfie Solomons</span>
            </p>
            <span>Thanks for your business</span>
          </td>
          <td class="text-end px-4 py-3">
            <p class="mb-2">Subtotal:</p>
            <p class="mb-2">Deposit:</p>
            <p class="mb-2">Tax:</p>
            <p class="mb-0">Total:</p>
          </td>
          <td class="px-4 py-3">
            <p class="fw-medium mb-2">$<?php echo $quotation_info['subtotal'];?></p>
            <p class="fw-medium mb-2">$<?php echo $quotation_info['deposit'];?></p>
            <p class="fw-medium mb-2">$<?php echo $quotation_info['tax_amount'];?>(<?php echo $quotation_info['tax'];?>%)</p>
            <p class="fw-medium mb-0">$<?php echo $quotation_info['net_total'];?></p>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  <div class="row">
    <div class="col-12">
      <span class="fw-medium">Note:</span>
      <span>It was a pleasure working with you and your team. We hope you will keep us in mind for future
        freelance projects. Thank You!</span>
    </div>
  </div>
</div>

  

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
   <script src="<?php echo base_url(); ?>/public/assets/js/quotation_datatable.js"></script>	
   <script src="<?php echo base_url();?>/public/app-assets/vendors/js/forms/cleave/cleave.min.js"></script>
   <script src="<?php echo base_url();?>/public/app-assets/vendors/js/forms/cleave/addons/cleave-phone.us.js"></script>
 <script src="<?php echo base_url();?>/public/app-assets/js/scripts/forms/form-input-mask.js"></script>
 <script src="<?php echo base_url();?>/public/app-assets/js/app-invoice-print.js"></script>
 <style>.dataTables_length label{float: right;margin-right: 21px;}  
 .form-select-sm {padding-top: 9px!important;}
 #search_users_btn{margin-top:23px!important;}
 .select2-container--default .select2-selection--single .select2-selection__arrow b{margin-left: -4px;
    margin-top: -2px;position: relative!important;top: 50%;width: 0;left:0!important;height: 0!important;border-width:0px!important;}
 
 </style>
<?php echo view('includes/footer'); ?>