<?php
$header = array('title' => 'Modify Job');

if($job_info){
	$sel_customer_id          = $job_info['customer_id'];
	$sel_customer_location    = $job_info['customer_location'];
	$sel_work_details         = $job_info['work_details'];
	$sel_remarks              = $job_info['remarks'];
	$sel_advanced_payment     = $job_info['advanced_payment'];
	$sel_job_assigned_to      = $job_info['job_assigned_to'];
	$sel_final_amount         = $job_info['final_amount'];
	$sel_job_start            =  $job_info['job_start_date'];
	$sel_job_completion       =  $job_info['job_completion_date'];
	$hst_applied              = $job_info['hst_applied'];
	
		if($sel_job_completion!='' && $sel_job_completion!='0000-00-00' && $sel_job_completion!='1970-01-01')	
           	  $sel_job_completion =date('Y-m-d',strtotime($sel_job_completion));
           	 else
           	 $sel_job_completion ='0000-00-00';
           	 
           	 
}
else{
	$sel_customer_id         ='';
	$sel_customer_location   = '';
	$sel_work_details        ='';
	$sel_remarks             ='';
	$sel_advanced_payment    ='';
	$sel_job_assigned_to     ='';
	$sel_final_amount        ='';
	$sel_job_completion      = '';
	$sel_job_start           = '';
	$hst_applied='0';
   }
 ?>
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
                                <h4 class="card-title">Modify Job Details</h4>
                         </div>
                         <div class="card-body py-2 my-25">
                                <!-- header section -->
                                <?php echo $message_output->run(); ?>
                                <form class="mt-2 pt-50" name="enquiry-form" id="enquiry-form" method="post" acton="<?php echo base_url();?>/jobs/edit/<?php echo $job_id;?>" enctype="multipart/form-data">
                                    <div class="row">									
                                        <div class="col-12 col-sm-3 mb-1">
                                            <label class="form-label" for="firstName">Choose Customer</label>
                                            <?php  
                                             echo form_dropdown("customer_id",$CustomerDropdown,$sel_customer_id,'class="form-control select2" id="customer_id" required');
										   ?>
                                        </div>										
                                     
									
                                        
                                          <div class="col-12 col-sm-2 mb-1">
                                            <label class="form-label" for="motherName">Job Start Date</label>
                                          <input type="date" name="job_start" id="job_start" class="form-control flatpickr-basic" value="<?php echo $sel_job_start;?>" required readonly>												
											
                                        </div>
                                        
                                          <div class="col-12 col-sm-2 mb-1">
                                            <label class="form-label" for="motherName">Job Completion Date</label>
                                            
										 <input type="date" name="job_completion" id="job_completion" class="form-control flatpickr-basic" value="<?php echo $sel_job_completion;?>" readonly>										
											
                                        </div>
                                        
										  <div class="col-12 col-sm-4 mb-1">
                                            <label class="form-label" for="motherName">Assigned To</label>
                                            
										 <?php 
                                             echo form_dropdown("job_assigned_to[]",$StaffDropdown,explode(",",$sel_job_assigned_to),'class="form-control select2" id="job_assigned_to" required');
										   ?>											
											
                                        </div>
										
                                        	 <div class="col-12 col-sm-12 mb-1">
                                            <label class="form-label" for="PinCode">Customer Address</label>
                                            <textarea  class="form-control" cols="30" rows="3" id="customer_location" name="customer_location"><?php echo $sel_customer_location;?></textarea>
                                        </div>
                                        
                                        
								 <div class="col-6 col-sm-6 mb-1">
                                            <label class="form-label" for="PinCode">Work Details</label>
                                            <textarea type="text" class="form-control" id="work_details" name="work_details"><?php echo $sel_work_details;?></textarea>
                                        </div>
                                        
                                         
                                 <div class="col-6 col-sm-6 mb-1">
                                         <label class="form-label" for="PinCode">Remarks</label>
                                          <textarea type="text" class="form-control" id="our_remarks" name="our_remarks" ><?php echo $sel_remarks;?></textarea>
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
                                            <?php
                                            $counter=300;
                                            $net_amount =0;
                                            if($get_job_items){
                                              foreach($get_job_items as $jbrow){
                                                  
                                                  $net_amount = $net_amount+$jbrow['item_cost'];
                                                  ?>
                                                 <tr id="tr<?php echo $counter;?>">
                                              <td>
                                             <?php  
                                                echo form_dropdown("job_item[]",$PaymentHeads,$jbrow['item_id'].'||0.00','class="form-control job_items_list" id="job_item" data-id="'.$counter.'" ');
										     ?>
										   </td>    
                                              <td><input type="text" name="job_cost[]" id="job_cost<?php echo $counter;?>" data-id="<?php echo $counter;?>" value="<?php echo $jbrow['item_cost'];?>" class="form-control countcost"></td>
                                              <td><div class="form-group">
                                                  <input type="button" class="btn btn-primary btn-sm remove_image" onClick="removeitem('<?php echo $counter;?>')"  data-id="<?php echo $counter;?>" value="Remove">
                                               </div> </td>
                                            </tr>   
                                                  <?php
                                                  
                                              $counter++;    
                                              }  
                                                
                                            }
                                            ?>
                                              
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
                                 
                                        
					 	<div class="col-6 col-sm-4 mb-1">
                                            <label class="form-label" for="PinCode">Advanced Payment(if any)</label>
                                            <input type="text" class="form-control" id="advanced_payment" name="advanced_payment" value="<?php echo $sel_advanced_payment;?>" />
                      </div> 
                      
                      	<div class="col-2 col-sm-2 mb-1">
                            <label class="form-label" for="hst_applied"> HST(13%) Applied</label>
                          <div class="form-check">
                              <?php if($hst_applied>0){ ?>
                                  <input class="form-check-input" type="checkbox" value="1" name="hst_applied" id="hst_applied" checked>
                                  <?php } else { ?>
                                  <input class="form-check-input" type="checkbox" value="1" name="hst_applied" id="hst_applied">
                                  
                                  <?php } ?>
                                  
                                </div>
                      </div>
                      
					<div class="col-6 col-sm-4 mb-1">
                                            <label class="form-label" for="PinCode">Net Payment</label>
                                            <?php if($sel_advanced_payment >0)
                                             $final_net = $net_amount-$sel_advanced_payment;
                                             else
                                              $final_net = $net_amount;
                                              ?>
                                            <input type="text" class="form-control" id="net_payment" name="net_payment"value="<?php echo number_format($final_net,2);?>" readonly/>
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
    
<script>

var PaymentHeads = [];

<?php
foreach($PaymentHeads as $HeadsKey => $HeadsRow){
    if($HeadsKey != ''){
        $heads_key_info = explode('||',$HeadsKey);
?>
PaymentHeads['<?php echo $HeadsKey;?>'] = '<?php echo $heads_key_info[1];?>';
<?php
    }
}
?>

function calculate_total(){

    var total_job_payment = 0;

    $(".imagestable tbody tr").each(function(){

        var cost = parseFloat($(this).find(".countcost").val()) || 0;
        var head_text = $(this).find(".job_items_list option:selected").text().trim();

        if(head_text === "Total Job Payment"){
            total_job_payment = cost;
        }

    });

    var hst_amount = 0;

    if($("#hst_applied").prop("checked")){
        hst_amount = (total_job_payment * 13) / 100;
    }

    var final_amount = total_job_payment + hst_amount;

    var advanced_payment = parseFloat($("#advanced_payment").val()) || 0;

    var net_amount = final_amount - advanced_payment;

    $("#net_payment").val(net_amount.toFixed(2));
}



function removeitem(id){
    $("#tr"+id).remove();
    calculate_total();
}



$(document).ready(function(){

    calculate_total();

    $("#customer_id").select2({placeholder:"Choose",allowClear:true});
    $("#job_assigned_to").select2({placeholder:"Choose",allowClear:true});



    // COST CHANGE
    $(document).on("keyup change",".countcost",function(){
        calculate_total();
    });



    // PAYMENT HEAD CHANGE
    $(document).on("change",".job_items_list",function(){

        var job_id_info = $(this).val();
        var job_counter = $(this).data("id");

        if(job_id_info=='')
            $("#job_cost"+job_counter).val('0');
        else
            $("#job_cost"+job_counter).val(PaymentHeads[job_id_info]);

        calculate_total();

    });



    // HST CHANGE
    $("#hst_applied").on("change",function(){
        calculate_total();
    });



    // ADVANCE PAYMENT CHANGE
    $("#advanced_payment").on("keyup change",function(){
        calculate_total();
    });



    // ADD ROW
    var imgrow_counter = 2;

    $(document).on("click","#addmoredocuments",function(e){

        e.preventDefault();

        var result = $(".images_table_row").first().clone();

        $(".images_table_row").last().after(result);

        $(".images_table_row").last().attr("id","tr"+imgrow_counter);

        $("#tr"+imgrow_counter+" input").val("");

        $("#tr"+imgrow_counter+" select").val("");

        $("#tr"+imgrow_counter+" select").attr("data-id",imgrow_counter);
        $("#tr"+imgrow_counter+" input").attr("data-id",imgrow_counter);

        $("#tr"+imgrow_counter+" select").attr("id","job_item"+imgrow_counter);
        $("#tr"+imgrow_counter+" input").attr("id","job_cost"+imgrow_counter);

        var btn='<input type="button" class="btn btn-primary btn-sm remove_image" data-id="'+imgrow_counter+'" value="Remove">';
        $("#tr"+imgrow_counter+" td:last").html(btn);

        imgrow_counter++;

        calculate_total();

    });



    // REMOVE ROW
    $(document).on("click",".remove_image",function(){

        var id = $(this).data("id");

        $("#tr"+id).remove();

        calculate_total();

    });



    // CUSTOMER ADDRESS AUTOLOAD
    $("#customer_id").on("change",function(){

        var customer_id = $(this).val();

        var url = baseurl + "/jobs/getcustomer_location/" + customer_id;

        $.get(url,function(res){
            $("#customer_location").val(res);
        });

    });

});

</script>

<style>
.select2-container--default .select2-selection--single .select2-selection__arrow b{margin-left: -4px;
    margin-top: -2px;position: relative!important;top: 50%;width: 0;left:0!important;height: 0!important;border-width:0px!important;}
</style>
<?php echo view('includes/footer'); ?>	