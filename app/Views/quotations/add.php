<?php $header = array('title' => 'Add Quotation');?>
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
                                <h4 class="card-title">Add Quotation Details</h4>
                         </div>
                         <div class="card-body py-2 my-25">
                                <!-- header section -->
                                <?php echo $message_output->run(); ?>
                                <form class="mt-2 pt-50" name="enquiry-form" id="enquiry-form" method="post" acton="<?php echo base_url();?>/quotations/add" enctype="multipart/form-data">
                                    <div class="row">									
                                        <div class="col-12 col-sm-6 mb-1">
                                            <label class="form-label" for="firstName">Choose Customer</label>
                                            <?php  
                                             echo form_dropdown("customer_id",$CustomerDropdown,'','class="form-control select2" id="customer_id" required');
										   ?>
                                        </div>										
                                     
										  <div class="col-12 col-sm-6 mb-1">
                                            <label class="form-label" for="motherName">Company Details</label>
                                           <input type="text" class="form-control" id="company_details" name="company_details">											
											
                                        </div>
                                        	 <div class="col-12 col-sm-12 mb-1">
                                            <label class="form-label" for="PinCode">Customer Location</label>
                                            <textarea  class="form-control" cols="30" rows="3" id="customer_location" name="customer_location"></textarea>
                                        </div>
                                
                                         
                                 <div class="col-6 col-sm-6 mb-1">
                                         <label class="form-label" for="PinCode">Notes</label>
                                          <textarea type="text" class="form-control" id="notes" name="notes" ></textarea>
                                  </div> 
                      
									<div class="col-12 col-sm-12 mb-1">
                                        <table class="table table-striped imagestable" border="1">
                                          <thead>
                                            <tr>
                                              <th>Service</th>    
                                              <th>Description</th>
                                              <th>Amount</th>
                                              <th>Action</th>
                                            </tr>
                                          </thead>
                                          <tbody>
                                            <tr class="images_table_row">
                                              <td><?php echo form_dropdown("service_item[]",$PaymentHeads,'','class="form-control job_items_list" data-id="1" '); ?>
										   </td>  
										      <td><input type="text" class="form-control" name="service_desc[]"></td>
                                              <td><input type="text" name="service_cost[]" id="service_cost1" data-id="1"  class="form-control countcost"></td>
                                              <td><div class="form-group">
                                                 <input type="button" class="btn btn-primary btn-sm"  id="addmoredocuments" name="addmoredocuments" value="Add">
                                                </div></td>
                                            </tr>
                                          </tbody>
                                        </table><br>
                                      </div>										
                               
                               
                               	<div class="col-2 col-sm-2 mb-1">
                                            <label class="form-label" for="PinCode">Sub Total</label>
                                             <input type="text" class="form-control" id="subtotal" name="subtotal" readonly/>
                                 </div> 
                      	<div class="col-2 col-sm-2 mb-1">
                                            <label class="form-label" for="PinCode">H.S.T(%)</label>
                                             <input type="text" class="form-control" id="hst_payment" name="hst_payment"/>
                                 </div> 
                       
                        	<div class="col-2 col-sm-2 mb-1">
                                            <label class="form-label" for="PinCode">Deposit(if any)</label>
                                            <input type="text" class="form-control" id="deposit_payment" name="deposit_payment" />
                      </div> 
                      
                       	<div class="col-2 col-sm-2 mb-1">
                                            <label class="form-label" for="PinCode">Total</label>
                                            <input type="text" class="form-control" id="total_payment" name="total_payment" readonly/>
                      </div> 
                      
					
				    	<div class="col-3 col-sm-3 mb-1">
                                            <label class="form-label" for="PinCode">Balance</label>
                                            <input type="text" class="form-control" id="balance" name="balance" readonly/>
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
<?php echo view('includes/footer_scripts'); ?>
<script src="<?php echo base_url();?>/public/app-assets/vendors/js/forms/cleave/cleave.min.js"></script>
<script src="<?php echo base_url();?>/public/app-assets/vendors/js/forms/cleave/addons/cleave-phone.us.js"></script>
<script src="<?php echo base_url();?>/public/app-assets/js/scripts/forms/form-input-mask.js"></script>
<script src="<?php echo base_url();?>/public/app-assets/js/scripts/forms/form-validation.js"></script>
<script> 
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
		$(".images_table_row div").last().replaceWith( btnvalue )
		 
		$("#tr"+imgrow_counter+" input[type=file]").val("");
		$("#tr"+imgrow_counter+" input[type=text]").val("");
		$("#tr"+imgrow_counter+" #addmoredocuments").remove();
		$("#tr"+imgrow_counter+" div .col-md-3").remove();
		$("#tr"+imgrow_counter+" div .col-md-9").attr('class', 'col-md-12');
		
		
    	$("#tr"+imgrow_counter+" select").attr('data-id', imgrow_counter);
    	$("#tr"+imgrow_counter+" input").eq(0).attr('data-id', imgrow_counter);
    	$("#tr"+imgrow_counter+" input").eq(1).attr('data-id', imgrow_counter);
		
		$("#tr"+imgrow_counter+" select").attr('id', 'service_item'+imgrow_counter);
		$("#tr"+imgrow_counter+" input").eq(0).attr('id', 'service_desc'+imgrow_counter);
		$("#tr"+imgrow_counter+" input").eq(1).attr('id', 'service_cost'+imgrow_counter);
		
		imgrow_counter=imgrow_counter+1;
		
		
	 calculate_total();
	 
    });  
   function calculate_total(){
	  var subtotal=0;
	 $( ".countcost" ).each(function( index ) {
	    if($(this).val()!='')
	      subtotal= parseFloat(subtotal)+parseFloat($(this).val());
	 }); 
	 
	 var hstperc = $("#hst_payment").val();
	 var hstperc_payment = ((subtotal*hstperc)/100);
	    $("#subtotal").val(subtotal);
	   var advanced_payment_val = $("#advanced_payment").val();
	        
	   var total =   subtotal+hstperc_payment;
	   
	    $("#total_payment").val(total);   
	    
	   if(advanced_payment_val)
	     $("#balance").val(total-advanced_payment_val);
	 else
	     $("#balance").val(total);
	}
	
    
     $(document).on("change",".countcost", function (event) {		
         
          var subtotal=0;
	 $( ".countcost" ).each(function( index ) {
	    // alert($(this).val());
	    
	    if($(this).val()!='')
	      subtotal= parseFloat(subtotal)+parseFloat($(this).val());
	     
	 }); 
	 $("#subtotal").val(subtotal);

	   var advanced_payment_val = $("#advanced_payment").val();
	     var hst_val           = $("#hst_payment").val();
	     if(hst_val!='')
	     var hst_payment = ((subtotal*hst_val)/100);
	     else
	     var hst_payment =0;
	     
	   var total =   subtotal-hst_val;
	   
	    $("#total_payment").val(total);   
	    
	   if(advanced_payment_val)
	     $("#balance").val(total-advanced_payment_val);
	 else
	     $("#balance").val(total); 
	   
   });	
	
    $(document).on("change","#hst_payment", function (event) {	
	
	
	var subtotal=0;
	 $( ".countcost" ).each(function( index ) {
	    // alert($(this).val());
	    
	    if($(this).val()!='')
	      subtotal= parseFloat(subtotal)+parseFloat($(this).val());
	     
	 }); 
	 
	 var hstperc = $(this).val();
	 var hstperc_payment = ((subtotal*hstperc)/100);
	    $("#subtotal").val(subtotal);

	   var advanced_payment_val = $("#advanced_payment").val();
	   
	     
	   var total =   subtotal+hstperc_payment;
	   
	    $("#total_payment").val(total);   
	    
	   if(advanced_payment_val)
	     $("#balance").val(total-advanced_payment_val);
	 else
	     $("#balance").val(total);
	});
	
    
     $(document).on("change",".countcost", function (event) {		
         
          var subtotal=0;
	 $( ".countcost" ).each(function( index ) {
	    // alert($(this).val());
	    
	    if($(this).val()!='')
	      subtotal= parseFloat(subtotal)+parseFloat($(this).val());
	     
	 });
	 $("#subtotal").val(subtotal);

	   var advanced_payment_val = $("#advanced_payment").val();
	     var hst_val           = $("#hst_payment").val();
	     if(hst_val!='')
	     var hst_payment = ((subtotal*hst_val)/100);
	     else
	     var hst_payment =0;
	     
	   var total =   subtotal+hst_val;
	   
	    $("#total_payment").val(total);   
	    
	   if(advanced_payment_val)
	     $("#balance").val(total-advanced_payment_val);
	 else
	     $("#balance").val(total);
	   
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
 
 $("#advanced_payment").on("change",function(){
	 var advanced_payment_val = $(this).val();
	 
	 var counter_sum=0;
	 $( ".countcost" ).each(function( index ) {
	      counter_sum= parseFloat(counter_sum)+parseFloat($(this).val());
	     
	 });
	 
	 if( typeof counter_sum === 'undefined')
	  counter_sum =0;
	  
	 // console.log(advanced_payment_val+"--"+counter_sum);
	 
	  $("#net_payment").val(counter_sum-advanced_payment_val);
     
 });


$(document).on("change",".job_items_list",function(){
	 var job_id_info = $(this).val();
	  var job_counter  = $(this).data("id");
	  if(job_id_info=='')
	   $("#service_cost"+job_counter).val('0');
	  else
	 $("#service_cost"+job_counter).val(PaymentHeads[job_id_info]);
	 
	 console.log(job_id_info);
	 console.log(PaymentHeads);
	 
	 
	 var counter_sum=0;
	 $( ".countcost" ).each(function( index ) {
	      counter_sum= parseFloat(counter_sum)+parseFloat($(this).val());
	     
	 });
	 
	 
	 	 var advanced_payment = $("#advanced_payment").val();
	 if(advanced_payment >0 )
	   var final_sum = counter_sum -advanced_payment;
	 
	 else
	     var final_sum = counter_sum;
	     
	 $("#net_payment").val(final_sum);
	
 });
</script>

<style>
.select2-container--default .select2-selection--single .select2-selection__arrow b{margin-left: -4px;
    margin-top: -2px;position: relative!important;top: 50%;width: 0;left:0!important;height: 0!important;border-width:0px!important;}
</style>
<?php echo view('includes/footer'); ?>	