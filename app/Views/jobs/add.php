<?php
$header = array('title' => 'Add Job');?>
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
                        <div class="card-header border-bottom">
                                <h4 class="card-title">Add Job Details</h4>
								<a href='javascript:void(0);' class="btn btn-primary me-1 waves-effect waves-float waves-light addnewcustomer_modal">Add New Customer</a>
                         </div>
                         <div class="card-body py-2 my-25">
                                <!-- header section -->
                                <?php echo $message_output->run(); ?>
                                <form class="mt-2 pt-50" name="enquiry-form" id="enquiry-form" method="post" acton="<?php echo base_url();?>/jobs/add" enctype="multipart/form-data">
                                    <div class="row">									
                                        <div class="col-12 col-sm-3 mb-1">
                                            <label class="form-label" for="firstName">Choose Customer</label>
											<div id="custdiv">
                                            <?php  
                                             echo form_dropdown("customer_id",$CustomerDropdown,'','class="form-control select2" id="customer_id" required');
										   ?></div>
                                        </div>										 
                                     	  <div class="col-12 col-sm-2 mb-1">
                                            <label class="form-label" for="motherName">Job Start Date</label>
                                          <input type="date" name="job_start" id="job_start" class="form-control flatpickr-basic" required readonly>												
											
                                        </div>
                                        
                                          <div class="col-12 col-sm-2 mb-1">
                                            <label class="form-label" for="motherName">Job Completion Date</label>
                                            
										 <input type="date" name="job_completion" id="job_completion" class="form-control flatpickr-basic" readonly>										
											
                                        </div>
                                        
                                         <div class="col-12 col-sm-4 mb-1">
                                            <label class="form-label" for="motherName">Assigned To</label>
                                             <?php 
											 $sel_job_assigned_to=',';
                                             echo form_dropdown("job_assigned_to[]",$StaffDropdown,explode(",",$sel_job_assigned_to),'class="form-control select2" id="job_assigned_to" required');
										   ?>	
									    </div>
                                        	 <div class="col-12 col-sm-12 mb-1">
                                            <label class="form-label" for="PinCode">Customer Address</label>
                                            <textarea  class="form-control" cols="30" rows="3" id="customer_location" name="customer_location"></textarea>
                                        </div>
                                        
                                        
								 <div class="col-6 col-sm-6 mb-1">
                                            <label class="form-label" for="PinCode">Work Details</label>
                                            <textarea type="text" class="form-control" id="work_details" name="work_details"></textarea>
                                        </div>
                                        
                                         
                                 <div class="col-6 col-sm-6 mb-1">
                                         <label class="form-label" for="PinCode">Remarks</label>
                                          <textarea type="text" class="form-control" id="our_remarks" name="our_remarks" ></textarea>
                                  </div> 
                      
									<div class="col-12 col-sm-12 mb-1">
                                        <table class="table table-striped imagestable" border="1">
                                          <thead>
                                            <tr>
                                              <th>Payment Heads</th>    
                                              <th>Cost</th>
                                              <th>Action</th>
                                            </tr>
                                          </thead>
                                          <tbody>
                                            <tr class="images_table_row">
                                              <td>
                                             <?php  
                                                echo form_dropdown("job_item[]",$PaymentHeads,'','class="form-control job_items_list" id="job_item1" data-id="1" ');
										     ?>
										   </td>    
                                              <td><input type="text" name="job_cost[]" id="job_cost1" data-id="1" class="form-control countcost"></td>
                                              <td><div class="form-group">
                                                  <input type="button" class="btn btn-primary btn-sm"  id="addmoredocuments" name="addmoredocuments" value="Add">
                                                </div></td>
                                            </tr>
                                          </tbody>
                                        </table><br>
                                      </div>										
                                 
                                        
					 	<div class="col-4 col-sm-5 mb-1">
                                            <label class="form-label" for="PinCode">Advanced Payment(if any)</label>
                                            <input type="text" class="form-control" id="advanced_payment" name="advanced_payment" />
                                            
                                            
                                
                                
                      </div> 
                      
                     	<div class="col-2 col-sm-2 mb-1">
                            <label class="form-label" for="hst_applied"> HST(13%) Applied</label>
                          <div class="form-check">
                                  <input class="form-check-input" type="checkbox" value="1" name="hst_applied" id="hst_applied" checked>
                                  
                                </div>
                      </div>
                     
					<div class="col-5 col-sm-4 mb-1">
                                            <label class="form-label" for="PinCode">Net Payment</label>
                                             
                                            <input type="text" class="form-control" id="net_payment" name="net_payment" readonly/>
                      </div> 					
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary mt-1 me-1 waves-effect waves-float waves-light">Save changes</button>
                                            
                                        </div>
										
                                    
                                </form>
                                <!--/ form -->
                            </div>
                        </div>
	 </div>
	</div>
	
</div></div></div>	
<?php
echo $markcomplete_div_modal ='<div class="modal fade" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" id="addcustomerdiv"> <div class="modal-dialog" role="document">
        <div class="modal-content modal-lg">
            <div class="modal-header">
                <h5 class="" lass="modal-title" id="exampleModalLabel">Add Customer</h5>
                    <button type="button" class="close close_addpaymnt_model" data-id="addcustomerdiv" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>            
                <div class="modal-body">
				   <div id="ajaxresp"></div>
                   <form action="#" id="custmodalfrm" method="POST">
                <input type="hidden" name="_method" value="POST">              
				<input type="hidden" name="code">
                <div class="modal-body">
                    <div class="row">
                    <div class="col-12 col-sm-6 mb-1">
					  <label class="form-label" for="firstName">Full Name*</label>
                      <input type="text" class="form-control" id="customerFlname" name="customerFlname" required>
                   </div>
				   <div class="col-12 col-sm-6 mb-1">
				      <label class="form-label" for="firstName">Mobile*</label>
				      <input type="text" class="form-control phone-number-mask" id="customerMobile" name="customerMobile" required>
                   </div>	
				    <div class="col-12 col-sm-6 mb-1">
				      <label class="form-label" for="firstName">Email</label>
				      <input type="email" class="form-control" id="customerEmail" name="customerEmail">
                   </div>
				   <div class="col-12 col-sm-6 mb-1">
				      <label class="form-label" for="firstName">Photo</label>
				      <input type="file" class="form-control" id="customerPhoto" name="customerPhoto">
                   </div>
				 <div class="col-12 col-sm-12 mb-1">
				      <label class="form-label" for="firstName">Address*</label>
				      <textarea cols="20" rows="5" class="form-control" id="customerAddress" name="customerAddress" required></textarea>
                   </div>  
				   </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary close_addpaymnt_model" data-id="addcustomerdiv">Close</button>
                    <button type="button" class="btn btn-sm btn-success" id="save_modal_customer">Save</button>
                </div>
            </form>
                </div>                        
        </div>
    </div>
</div></div>';
?>
<?php echo view('includes/footer_scripts'); ?>
		<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/public/app-assets/vendors/css/pickers/pickadate/pickadate.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/public/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/public/app-assets/css/plugins/forms/pickers/form-flat-pickr.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/public/app-assets/css/plugins/forms/pickers/form-pickadate.css">
    
  <script src="<?php echo base_url();?>/public/app-assets/vendors/js/forms/cleave/cleave.min.js"></script>
  <script src="<?php echo base_url();?>/public/app-assets/vendors/js/forms/cleave/addons/cleave-phone.us.js"></script>
  <script src="<?php echo base_url();?>/public/app-assets/js/scripts/forms/form-input-mask.js"></script>
  <script src="<?php echo base_url();?>/public/app-assets/js/scripts/forms/form-validation.js"></script>
  <script src="<?php echo base_url();?>/public/app-assets/vendors/js/pickers/pickadate/picker.js"></script>
    <script src="<?php echo base_url();?>/public/app-assets/vendors/js/pickers/pickadate/picker.date.js"></script>
    <script src="<?php echo base_url();?>/public/app-assets/vendors/js/pickers/pickadate/picker.time.js"></script>
    <script src="<?php echo base_url();?>/public/app-assets/vendors/js/pickers/pickadate/legacy.js"></script>
    <script src="<?php echo base_url();?>/public/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js"></script>
    <script src="<?php echo base_url();?>/public/app-assets/js/scripts/forms/pickers/form-pickers.js"></script>
<style>
   .errors ul{
	 list-style: none;
    display: inline;
    padding: 0;
   color:red; }
</style>
<script> 

function calculate_total(){

    var total_job_payment = 0;

    $(".images_table_row").each(function(){

        var cost = $(this).find(".countcost").val();
        var head_text = $(this).find(".job_items_list option:selected").text();

        if(head_text.trim() == "Total Job Payment"){
            total_job_payment = parseFloat(cost) || 0;
        }

    });

    var hst_amount = 0;

    if($("#hst_applied").prop('checked')){
        hst_amount = (total_job_payment * 13) / 100;
    }

    var final_amount = total_job_payment + hst_amount;

    var advanced_payment = parseFloat($("#advanced_payment").val()) || 0;

    var net_amount = final_amount - advanced_payment;

    $("#net_payment").val(net_amount.toFixed(2));
}


$(document).on("keyup change",".countcost",function(){
    calculate_total();
});


$(".addnewcustomer_modal").on("click",function(){
	$("#addcustomerdiv").modal("show");
})

$("#save_modal_customer").on("click",function(){
	var formData = new FormData();
    formData.append('customerPhoto', $('#customerPhoto')[0].files[0]);
	formData.append('customerFlname',$("#customerFlname").val());
	formData.append('customerEmail',$("#customerEmail").val());
	formData.append('customerMobile',$("#customerMobile").val());
	formData.append('customerAddress',$("#customerAddress").val());
    $.ajax({
              url: baseurl +"/customers/ajax_save_customer",
              type: "POST",
              data: formData,
              processData: false,  // tell jQuery not to process the data
              contentType: false   // tell jQuery not to set contentType
            }).done(function( response ) {
				var response_info = response.split("|||");
				var response_val = response_info[0];
				var resposne_text = response_info[1];
				var cust_address = response_info[2];
				if(response_val=='0')
					$("#ajaxresp").html(resposne_text);
				 else if(response_val=='1'){
					 $("#custdiv").html(resposne_text);
					 $('#customer_id').select2();
					 $(".modal").modal('hide');
					 $("#customer_location").val(cust_address);
				 }
       });		
	return false;
})

var PaymentHeads=[];
$(document).ready(function(){
<?php
     foreach($PaymentHeads as $HeadsKey => $HeadsRow){
         if($HeadsKey !=''){
               $heads_key_info = explode('||',$HeadsKey);
         ?>
         PaymentHeads['<?php echo $HeadsKey;?>']='<?php echo $heads_key_info[1];?>';
         <?php
         }
     }
    
    ?>
      var imgrow_counter=2;
	$(document).on("click","#addmoredocuments", function (event) {						   
		event.preventDefault();			    
		var result = $(".images_table_row").first().clone();
		$(".images_table_row").last().after(result);	
		$(".images_table_row").last().attr("id","tr"+imgrow_counter);	
		var btnvalue='<input type="button" class="btn btn-primary btn-sm remove_image"  data-id="'+imgrow_counter+'" value="Remove">';
		$(".images_table_row div").last().replaceWith( btnvalue );
		$("#tr"+imgrow_counter+" input[type=file]").val("");
		$("#tr"+imgrow_counter+" input[type=text]").val("");
		$("#tr"+imgrow_counter+" #addmoredocuments").remove();
		$("#tr"+imgrow_counter+" div .col-md-3").remove();
		$("#tr"+imgrow_counter+" div .col-md-9").attr('class', 'col-md-12');
    	$("#tr"+imgrow_counter+" select").attr('data-id', imgrow_counter);
		$("#tr"+imgrow_counter+" input").attr('data-id', imgrow_counter);
		$("#tr"+imgrow_counter+" select").attr('id', 'job_item'+imgrow_counter);
		$("#tr"+imgrow_counter+" input").attr('id', 'job_cost'+imgrow_counter);
		imgrow_counter=imgrow_counter+1;	
		calculate_total();
    });  
    
   
   $(document).on("click",".remove_image", function (event) {						   
		event.preventDefault();	
		var colorid = $(this).attr("data-id");
		if(colorid!=''){
		  $("#tr"+colorid+"").remove();
		  	calculate_total();
	 
		 }										
	});  
	
   $("#customer_id").select2({ placeholder: "Choose","allowClear":"true" });
   $("#job_assigned_to").select2({ placeholder: "Choose","allowClear":"true" });
	 
	 
});

$("#customer_id").on("change",function(){
	 var customer_id = $(this).val();
     var url = baseurl +"/jobs/getcustomer_location/"+customer_id;
     $.ajax({
         type: "GET",
         url: url,
         dataType:"html",
         success: function(response){
               $("#customer_location").val(response);
           }
       });
 });
 
$("#advanced_payment").on("keyup change",function(){
    calculate_total();
});
$("#hst_applied").on("change",function(){
    calculate_total();
});

$(document).on("change",".job_items_list",function(){

    var job_id_info = $(this).val();
    var job_counter  = $(this).data("id");

    if(job_id_info=='')
        $("#job_cost"+job_counter).val('0');
    else
        $("#job_cost"+job_counter).val(PaymentHeads[job_id_info]);

    calculate_total(); // ⭐ IMPORTANT
});

</script>

<style>
.select2-container--default .select2-selection--single .select2-selection__arrow b{margin-left: -4px;
    margin-top: -2px;position: relative!important;top: 50%;width: 0;left:0!important;height: 0!important;border-width:0px!important;}
</style>
<?php echo view('includes/footer'); ?>	