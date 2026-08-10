<?php
$header = array('title' => 'Modify Purchase Order');?>
<?php echo view('includes/header',$header); ?>
<body class="vertical-layout vertical-menu-modern  navbar-floating footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="">

    <!-- BEGIN: Header-->
   <?php echo view('includes/inner_header'); ?>

    <!-- BEGIN: Main Menu-->
    <?php echo view('includes/menu'); ?>
    <?php
    if($po_info){
	$sel_ponumber          = $po_info['ponumber'];
	$sel_vendor_id         = $po_info['vendor_id'];
	$sel_po_date           = $po_info['po_date'];
	$sel_remarks           = $po_info['remarks'];
	$sel_termsinfo         = $po_info['termsinfo'];
	$sel_tax_applied       = $po_info['tax_applied'];
	$sel_discount_amount   = $po_info['discount_amount'];
	$sel_shipping_amount   = $po_info['shipping_amount'];
	
	
		if($sel_po_date!='0000-00-00' && $sel_po_date!='1970-01-01')	
           	  $sel_po_date =date('Y-m-d',strtotime($sel_po_date));
           	 else
           	 $sel_po_date ='0000-00-00';
           	 
           	 
}
else{
	$sel_ponumber         ='';
	$sel_vendor_id   = '';
	$sel_po_date        ='';
	$sel_remarks             ='';
	$sel_termsinfo    ='';
	$sel_tax_applied     ='';
	$sel_discount_amount        ='';
	$sel_shipping_amount      = '';

   }
   ?>
    
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
                                <h4 class="card-title">Modify Purchase Order</h4>
								
                         </div>
                         <div class="card-body py-2 my-25">
                                <!-- header section -->
                                <?php echo $message_output->run(); ?>
                                <form class="mt-2 pt-50" name="enquiry-form" id="enquiry-form" method="post" acton="<?php echo base_url();?>/purchase_orders/edit/<?php echo $po_id;?>" enctype="multipart/form-data">
                                    <div class="row">									
                                        <div class="col-12 col-sm-4 mb-1">
                                            <label class="form-label" for="firstName">Choose Vendor</label>
											<div id="custdiv">
                                            <?php  
                                             echo form_dropdown("vendor_id",$VendorsDropdown,$sel_vendor_id,'class="form-control select2" id="vendor_id" required');
										   ?></div>
                                        </div>										 
                                     	  <div class="col-12 col-sm-4 mb-1">
                                            <label class="form-label" for="motherName">Date</label>
                                          <input type="date" name="podate" id="podate" class="form-control flatpickr-basic" value="<?php echo date("Y-m-d");?>" required readonly>												
											
                                        </div>
                                        
                                          <div class="col-12 col-sm-4 mb-1">
                                            <label class="form-label" for="motherName">Order No</label>
                                            
										 <input type="text" name="ordernumber" id="ordernumber" class="form-control" value="<?php echo $sel_ponumber;?>">										
											
                                        </div>
                                        
                                 
                                         
                                 <div class="col-12 col-sm-12 mb-1">
                                         <label class="form-label" for="PinCode">Notes</label>
                                          <textarea type="text" class="form-control" id="our_remarks" name="our_remarks" ><?php echo $sel_remarks;?></textarea>
                                  </div> 
                      
									<div class="col-12 col-sm-12 mb-1">
                                        <table class="table table-striped imagestable" border="1">
                                          <thead>
                                            <tr>
                                              <th>Item</th>    
                                              <th>Description</th>
											  <th>Qty</th>
											  <th>Unit Price</th>
											  <th>Total</th>
                                              <th>Action</th>
                                            </tr>
                                          </thead>
                                          <tbody>
                                    <?php
                                    $countr=200;
                                    if($get_po_items){ 
                                      foreach($get_po_items as $row){ ?>
                                            <tr class="images_table_row" id="tr<?php echo $countr;?>">
                                              <td>
                                             <input type="text" name="item[]" id="item<?php echo $countr;?>" class="form-control" value="<?php echo $row['item_name'];?>">
										   </td>  
											  <td>
                                             <input type="text" name="itemdesc[]" id="itemdesc<?php echo $countr;?>"  class="form-control" value="<?php echo $row['item_desc'];?>">
										   </td>  
										   <td>
                                             <input type="text" name="itemqty[]" id="itemqty<?php echo $countr;?>"  class="form-control qty" data-id="<?php echo $countr;?>" value="<?php echo $row['item_qty'];?>">
										   </td>
											<td>
                                             <input type="text" name="itemprice[]" id="itemprice<?php echo $countr;?>"  class="form-control uprice" data-id="<?php echo $countr;?>" value="<?php echo $row['unit_price'];?>">
										   </td> 
											<td>
                                             <input type="text" name="itemtotal[]" id="itemtotal<?php echo $countr;?>"  class="form-control countcost" data-id="<?php echo $countr;?>" value="<?php echo $row['item_total'];?>">
										   </td>	
                                           <td><div class="form-group">
                                                  <input type="button" class="btn btn-primary btn-sm" onClick="removetr('<?php echo $countr;?>')"  data-id="<?php echo $countr;?>" value="Remove">
                                                </div></td>
                                            </tr>
                                         <?php  $countr++;}  } ?>
                                         
                                         <tr class="images_table_row">
                                              <td>
                                             <input type="text" name="item[]" id="item1" class="form-control" value="">
										   </td>  
											  <td>
                                             <input type="text" name="itemdesc[]" id="itemdesc1"  class="form-control" value="">
										   </td>  
										   <td>
                                             <input type="text" name="itemqty[]" id="itemqty1"  class="form-control qty" data-id="1" value="">
										   </td>
											<td>
                                             <input type="text" name="itemprice[]" id="itemprice1"  class="form-control uprice" data-id="1" value="">
										   </td> 
											<td>
                                             <input type="text" name="itemtotal[]" id="itemtotal1"  class="form-control countcost" data-id="1" value="">
										   </td>	
                                           <td><div class="form-group">
                                                 <input type="button" class="btn btn-primary btn-sm"  id="addmoredocuments" name="addmoredocuments" value="Add">
                                                </div></td>
                                            </tr>
                                       
                                          </tbody>
                                        </table><br>
                                      </div>										
                        
                     	<div class="col-2 col-sm-2 mb-1">
                            <label class="form-label" for="hst_applied"> HST(13%) Applied</label>
                          <div class="form-check">
                              <?php if($sel_tax_applied=="1") { ?>
                                  <input class="form-check-input" type="checkbox" value="1" name="hst_applied" id="hst_applied" checked>
                                  <?php } else{ ?>
                                  <input class="form-check-input" type="checkbox" value="1" name="hst_applied" id="hst_applied"> 
                                  <?php } ?>
                                </div>
                      </div>
                      	<div class="col-3 col-sm-3 mb-1">
                                            <label class="form-label" for="PinCode">Discount</label>
                                             
                                            <input type="text" class="form-control" id="discount_payment" name="discount_payment" value="<?php echo $sel_discount_amount;?>"/>
                      </div> 
                     	<div class="col-3 col-sm-3 mb-1">
                                            <label class="form-label" for="PinCode">Shipping</label>
                                             
                                            <input type="text" class="form-control" id="shipping_payment" name="shipping_payment" value="<?php echo $sel_shipping_amount;?>"/>
                      </div> 
					<div class="col-3 col-sm-3 mb-1">
                                            <label class="form-label" for="PinCode">Net Payment</label>
                                             
                                            <input type="text" class="form-control" id="net_payment" name="net_payment" readonly/>
                      </div> 
                       <div class="col-12 col-sm-4 mb-1">
                                         <label class="form-label" for="PinCode">Upload Bill</label>
                                        <input type="file" class="form-control" id="bill_photo" name="bill_photo">
                                  </div>
                                  
                        <div class="col-12 col-sm-8 mb-1">
                                         <label class="form-label" for="PinCode">Terms</label>
                                          <textarea type="text" class="form-control" id="our_terms" name="our_terms" ><?php echo $sel_termsinfo;?></textarea>
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
  function calcualteqtybalance(){
       $( ".qty" ).each(function( index ) {
	     if($(this).val()!=''){
	         
	         var did = $(this).data("id");
	         var priceval = $("#itemprice"+did).val();
	         if(typeof priceval ==="undefined")
	            priceval=0;
	         else if(priceval=='' || priceval=='0')
	         priceval=0;
	         var caltotal = parseFloat($(this).val())*parseFloat(priceval);
	         
	         
	         
	         $("#itemtotal"+did).val(caltotal);
	         
	         
	         
	     }
	     
	 });
           
    }
    
    $('input[name="hst_applied"]').change(function () {
    if (this.checked) {
        calculate_total();
    }else
     calculate_total();
});
    
    
    function calcualtepricebalance(){
       $( ".uprice" ).each(function( index ) {
	     if($(this).val()!=''){
	         
	         var did = $(this).data("id");
	         var itemqtyval = $("#itemqty"+did).val();
	         if(typeof itemqtyval ==="undefined")
	            itemqtyval=0;
	         
	         
	         
	         var caltotal = parseFloat($(this).val())*parseFloat(itemqtyval);
	         
	         console.log(caltotal);
	         $("#itemtotal"+did).val(caltotal);
	         
	         
	         
	     }
	     
	 });
      
        
    }
   function calculate_total(){
        var counter_sum=0;
        $( ".countcost" ).each(function( index ) {
             if($(this).val()!='')
	      counter_sum= parseFloat(counter_sum)+parseFloat($(this).val());
	    });
	  var advanced_payment_val = $("#advanced_payment").val();  
	  
	  if($("#hst_applied").prop('checked') == true){
	      counter_sum_hst = ((counter_sum*13)/100);
	      
	      counter_sum = counter_sum+counter_sum_hst;
	  }
	  
	  
	  
	         var discount_payment = $("#discount_payment").val();
	         if(discount_payment=='')
	          discount_payment=0;
	          
	          var shipping_payment = $("#shipping_payment").val();
	          if(shipping_payment=='')
	             shipping_payment=0;
	             
	          
	          var finalamnt = parseFloat(counter_sum)+parseFloat(shipping_payment)-parseFloat(discount_payment);
	          
	          
	     $("#net_payment").val(parseFloat(finalamnt).toFixed(2)); 
    }   
$(document).ready(function(){

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
    	$("#tr"+imgrow_counter+" input:eq(0)").attr('data-id', imgrow_counter);
		$("#tr"+imgrow_counter+" input:eq(1)").attr('data-id', imgrow_counter);
		$("#tr"+imgrow_counter+" input:eq(2)").attr('data-id', imgrow_counter);
		$("#tr"+imgrow_counter+" input:eq(3)").attr('data-id', imgrow_counter);
		$("#tr"+imgrow_counter+" input:eq(4)").attr('data-id', imgrow_counter);
		
		
		
		$("#tr"+imgrow_counter+" input:eq(0)").attr('id', 'item'+imgrow_counter);
		$("#tr"+imgrow_counter+" input:eq(1)").attr('id', 'itemdesc'+imgrow_counter);
		$("#tr"+imgrow_counter+" input:eq(2)").attr('id', 'itemqty'+imgrow_counter);
		$("#tr"+imgrow_counter+" input:eq(3)").attr('id', 'itemprice'+imgrow_counter);
		$("#tr"+imgrow_counter+" input:eq(4)").attr('id', 'itemtotal'+imgrow_counter);
		
		imgrow_counter=imgrow_counter+1;	
		calculate_total();
    });  
    
  
    
    
  
    
   $(document).on("click",".remove_image", function (event) {						   
		event.preventDefault();	
		var colorid = $(this).attr("data-id");
		if(colorid!=''){
		    console.log("#tr"+colorid);
		  $("#tr"+colorid).remove();
		  	calculate_total();
	 
		 }										
	});  
	
   $("#customer_id").select2({ placeholder: "Choose","allowClear":"true" });
   $("#job_assigned_to").select2({ placeholder: "Choose","allowClear":"true" });
	 
	 
});


$(document).on("change","#discount_payment",function(){
    	calculate_total();
});


$(document).on("change","#shipping_payment",function(){
    	calculate_total();
});

if($("#hst_applied").prop('checked') == true){
   	calculate_total();
}else{
  	calculate_total();  
}


$(document).on("change",".qty",function(){
    calcualteqtybalance();
    	calculate_total();
});

$(document).on("change",".uprice",function(){
    calcualtepricebalance();
    	calculate_total();
});


$(document).on("change",".countcost",function(){
     var advanced_payment_val = $("#advanced_payment").val();
	 var counter_sum=0;
	 $( ".countcost" ).each(function( index ) {
	     if($(this).val()!='')
	      counter_sum= parseFloat(counter_sum)+parseFloat($(this).val());
	     
	 });
	 
	  if($("#hst_applied").prop('checked') == true){
	      counter_sum_hst = ((counter_sum*13)/100);
	      
	      counter_sum = counter_sum+counter_sum_hst;
	  }
	  
	  
	 if(advanced_payment_val)
	     $("#net_payment").val(parseFloat(counter_sum-advanced_payment_val).toFixed(2));
	 else
	     $("#net_payment").val(parseFloat(counter_sum).toFixed(2));
	 
});



$(document).on("change",".job_items_list",function(){
	 var job_id_info = $(this).val();
	  var job_counter  = $(this).data("id");
	  if(job_id_info=='')
	   $("#job_cost"+job_counter).val('0');
	  else
	 $("#job_cost"+job_counter).val(PaymentHeads[job_id_info]);
	 
	 var counter_sum=0;
	 $( ".countcost" ).each(function( index ) {
	      if($(this).val()!='')
	      counter_sum= parseFloat(counter_sum)+parseFloat($(this).val());
	     
	 });
	 
	  if($("#hst_applied").prop('checked') == true){
	      counter_sum_hst = ((counter_sum*13)/100);
	      
	      counter_sum = counter_sum+counter_sum_hst;
	  }
	 
	 
	 	 var advanced_payment = $("#advanced_payment").val();
	 if(advanced_payment >0 )
	   var final_sum = counter_sum -advanced_payment;
	 
	 else
	     var final_sum = counter_sum;
	     
	     
	     
	 $("#net_payment").val(parseFloat(final_sum).toFixed(2)); 
	
 });


function removetr(id){
   $("#tr"+id).remove();
		  	calculate_total(); 
    
}
</script>

<style>
.select2-container--default .select2-selection--single .select2-selection__arrow b{margin-left: -4px;
    margin-top: -2px;position: relative!important;top: 50%;width: 0;left:0!important;height: 0!important;border-width:0px!important;}
</style>
<?php echo view('includes/footer'); ?>	