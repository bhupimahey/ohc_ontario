<?php
$header = array(
	'title' => 'View Quotation Detail'
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
       <div class="container-xxl flex-grow-1 container-p-y">
            
            

<div class="row invoice-preview">
  <!-- Invoice -->
  <div class="col-xl-9 col-md-8 col-12 mb-md-0 mb-4">
    <div class="card invoice-preview-card">
      <div class="card-body">
        <div class="d-flex justify-content-between flex-xl-row flex-md-column flex-sm-row flex-column m-sm-3 m-0">
          <div class="mb-xl-0 mb-4">
            <div class="d-flex svg-illustration mb-4 gap-2 align-items-center">
                    <img src="<?php echo base_url();?>/public/documents/invoice-logo.jpeg" style="height: 120px;margin-top: -30px;" />
           
            </div>
            <p class="mb-0">3424 Dorcas Street Mississauga</p>
            <p class="mb-0">+1 (416) 200-0905</p>
            <p class="mb-0">info@hvacohc.ca</p>
           <p class="mb-0">Ontario M9W 0B5, Canada</p>
          
          </div>
          <div>
            <h4 class="fw-medium mb-2">INVOICE #<?php echo sprintf('%05d', $quotation_info['quotation_id']);?></h4>
            <div class="mb-2 pt-1">
              <span>Date Issues:</span>
              <span class="fw-medium"><?php echo date('M d, Y',strtotime($quotation_info['entry_time']));?></span>
            </div>
           
          </div>
        </div>
      </div>
      <hr class="my-0">
      <div class="card-body">
        <div class="row p-sm-2 p-0">
          <div class="col-xl-6 col-md-12 col-sm-5 col-12 mb-xl-0 mb-md-4 mb-sm-0 mb-4">
            <h6 class="mb-3">Invoice To:</h6>
            <p class="mb-1"><?php echo $customer_info['full_name'];?></p>
            <p class="mb-1"><?php echo $customer_info['address'];?></p>
            <p class="mb-1"><?php echo $customer_info['phone_no'];?></p>
            <p class="mb-0"><?php echo $customer_info['email_id'];?></p>
          </div>
          <div class="col-xl-6 col-md-12 col-sm-7 col-12">
            <h6 class="mb-4">Bill To:</h6>
            <table>
              <tbody>
                <tr>
                  <td class="pe-4">Total Due:</td>
                  <td class="fw-medium">$<?php echo $quotation_info['net_total'];?></td>
                </tr>
                <tr>
                  <td class="pe-4">Bank name:</td>
                  <td>American Bank</td>
                </tr>
                <tr>
                  <td class="pe-4">Country:</td>
                  <td>United States</td>
                </tr>
                <tr>
                  <td class="pe-4">IBAN:</td>
                  <td>ETD95476213874685</td>
                </tr>
                <tr>
                  <td class="pe-4">SWIFT code:</td>
                  <td>BR91905</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
       </div>
       <div class="row col-md-12">
             <div class="table-responsive border-top">
        <table class="table">
          <thead>
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
              <td class="text-nowrap"><?php echo $jbrow['item_name'];?></td>
              <td class="text-nowrap"><?php echo $jbrow['item_desc'];?></td>
              <td>$<?php echo $jbrow['item_cost'];?></td>
              <td>1</td>
             <td>$<?php echo $jbrow['item_cost'];?></td>
            </tr>
            <?php } } ?>
            <tr>
              <td colspan="3" class="align-top px-4 py-4">
                <p class="mb-2 mt-3">
                  <span class="ms-3 fw-medium">Salesperson:</span>
                  <span>Alfie Solomons</span>
                </p>
                <span class="ms-3">Thanks for your business</span>
              </td>
              <td class="text-end pe-3 py-4">
                <p class="mb-2 pt-3">Subtotal:</p>
                <p class="mb-2">Deposit:</p>
                <p class="mb-2">Tax(H.S.T):</p>
                <p class="mb-0 pb-3">Total:</p>
              </td>
              <td class="ps-2 py-4">
                <p class="fw-medium mb-2 pt-3">$<?php echo $quotation_info['subtotal'];?></p>
                <p class="fw-medium mb-2">$<?php echo $quotation_info['deposit'];?></p>
                <p class="fw-medium mb-2">$<?php echo $quotation_info['tax_amount'];?>(<?php echo $quotation_info['tax'];?>%)</p>
                <p class="fw-medium mb-0 pb-3">$<?php echo $quotation_info['net_total'];?></p>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
         </div>
      <div class="card-body mx-3">
        <div class="row">
          <div class="col-12">
            <span class="fw-medium">Note:</span>
            <span>It was a pleasure working with you and your team. We hope you will keep us in mind for future freelance
              projects. Thank You!</span>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- /Invoice -->

  <!-- Invoice Actions -->
  <div class="col-xl-3 col-md-4 col-12 invoice-actions">
    <div class="card">
      <div class="card-body">
        <!--<button class="btn btn-primary d-grid w-100 mb-2 waves-effect waves-light" data-bs-toggle="offcanvas" data-bs-target="#sendInvoiceOffcanvas">
          <span class="d-flex align-items-center justify-content-center text-nowrap"><i class="ti ti-send ti-xs me-2"></i>Send Invoice</span>
        </button> 
       -->
        <a  class="btn btn-primary d-grid w-100 mb-2 waves-effect waves-light" target="_blank" href="<?php echo base_url();?>/quotations/print_invoice/<?php echo $quotation_id;?>">
          Print
        </a>
        <a href="<?php echo base_url();?>/quotations/edit/<?php echo $quotation_id;?>" class="btn btn-label-secondary d-grid w-100 mb-2 waves-effect">
          Edit Quotations
        </a>
       
      </div>
    </div>
  </div>
  <!-- /Invoice Actions -->
</div>

<!-- Offcanvas -->
<!-- Send Invoice Sidebar -->
<div class="offcanvas offcanvas-end" id="sendInvoiceOffcanvas" aria-hidden="true">
  <div class="offcanvas-header my-1">
    <h5 class="offcanvas-title">Send Invoice</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body pt-0 flex-grow-1">
    <form>
      <div class="mb-3">
        <label for="invoice-from" class="form-label">From</label>
        <input type="text" class="form-control" id="invoice-from" value="shelbyComapny@email.com" placeholder="company@email.com">
      </div>
      <div class="mb-3">
        <label for="invoice-to" class="form-label">To</label>
        <input type="text" class="form-control" id="invoice-to" value="qConsolidated@email.com" placeholder="company@email.com">
      </div>
      <div class="mb-3">
        <label for="invoice-subject" class="form-label">Subject</label>
        <input type="text" class="form-control" id="invoice-subject" value="Invoice of purchased Admin Templates" placeholder="Invoice regarding goods">
      </div>
      <div class="mb-3">
        <label for="invoice-message" class="form-label">Message</label>
        <textarea class="form-control" name="invoice-message" id="invoice-message" cols="3" rows="8">Dear Queen Consolidated,
          Thank you for your business, always a pleasure to work with you!
          We have generated a new invoice in the amount of $95.59
          We would appreciate payment of this invoice by 05/11/2021</textarea>
      </div>
      <div class="mb-4">
        <span class="badge bg-label-primary">
          <i class="ti ti-link ti-xs"></i>
          <span class="align-middle">Invoice Attached</span>
        </span>
      </div>
      <div class="mb-3 d-flex flex-wrap">
        <button type="button" class="btn btn-primary me-3 waves-effect waves-light" data-bs-dismiss="offcanvas">Send</button>
        <button type="button" class="btn btn-label-secondary waves-effect" data-bs-dismiss="offcanvas">Cancel</button>
      </div>
    </form>
  </div>
</div>
<!-- /Send Invoice Sidebar -->


<!-- /Offcanvas -->


            
          </div>
    </div>
    <!-- END: Content-->
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
   <script src="<?php echo base_url(); ?>/public/assets/js/quotation_datatable.js"></script>	
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