<?php
namespace App\Models;
use CodeIgniter\Model;

class JobsModel extends Model	{
	
   	 protected $table = 'jobs';
	 protected $primaryKey = 'jobs_id';
	 public function __construct() {
	     
    parent::__construct();        
       $db = \Config\Database::connect();	
       $this->session = \Config\Services::session();
    }

	public function update_job($job_id,$data){
		$this->db->table('jobs')->where('jobs_id', $job_id)->update($data);
	}

	public function add_payment_history($job_id,$final_amount){
			$payment_data = array("jobs_id"=>$job_id,"amount_received"=>$final_amount,"accepted_by"=>$this->session->get('s_user_id'),"entry_time"=>date("Y-m-d H:i:s"));
	    	$this->db->table('payment_history')->insert($payment_data);
	}
	
	public function add_workinghours($data){
	    	$this->db->table('staff_job_activity')->insert($data);
		return $this->db->insertID();
	    
	}
	
	public function staff_has_workinghours($staff_id,$date){
	    $all_staff     = $this->db->table('staff_job_activity')->where('staff_id',$staff_id)->where('DATE(entry_time)',$date)->get()->getResult();  
	    return  $all_staff;
	}
	
	public function add_job($data){
		$this->db->table('jobs')->insert($data);
		return $this->db->insertID();
	}
   
   public function add_job_items($data){
		$this->db->table('job_items')->insert($data);
			
	}
  
  public function AllCustomersDropdown(){
	  $all_customers     = $this->db->table('customers')->get()->getResult(); 
	  $final_list       = array();
	  $final_list['']   = 'Choose Customer';
	  if( $all_customers){
		foreach( $all_customers as $row)
			$final_list[$row->customer_id] = ucwords($row->full_name).' '.$row->phone_no;
	  }
      return $final_list;	  
   }	
   
   public function payment_heads(){
	  $all_heads     = $this->db->table('payment_heads')->get()->getResult(); 
	  $final_list       = array();
	  $final_list['']   = 'Choose';
	  if( $all_heads){
		foreach( $all_heads as $row)
			$final_list[$row->phead_id.'||'.$row->pay_head_cost] = ucwords($row->pay_head_name);
	  }
    return $final_list;
   }
   
   
   
   public function staff_dropdown(){
	  $all_staff     = $this->db->table('users')->where('account_type', '3')->get()->getResult(); 
	  $final_list       = array();
	  if( $all_staff){
		foreach( $all_staff as $row)
			$final_list[$row->account_id] = ucwords($row->first_name).' '.ucwords($row->last_name);
	  }
    return $final_list;
   }
   
   function InsertPhotos($data){
       $this->db->table('job_photos')->insert($data);
   }
   
   function get_job_item_info($phead_id){
	  return $this->db->table('payment_heads')->where('phead_id', $phead_id)->get()->getRowArray();   	   
   }
   
   function customer_info($customer_id){
	  return $this->db->table('customers')->where('customer_id', $customer_id)->get()->getRowArray();   	   
   }
   
    function staff_info($account_id){
	  return $this->db->table('users')->where('account_id', $account_id)->where('account_type', '3')->get()->getRowArray();   	   
   }
   function photo_info($id){
      return $this->db->table('job_photos')->where('id', $id)->get()->getRowArray();   	   
   }
   
   function delete_photo_info($id){
       $this->db->table('job_photos')->where('id', $id)->delete();
   }

   function job_info($job_id){
	  return $this->db->table('jobs')->where('jobs_id', $job_id)->get()->getRowArray();   	   
   }
   
   function AllJobPhotos($job_id){
       return $this->db->table('job_photos')->where('jobs_id', $job_id)->get()->getResultArray(); 
   }
   
   function get_job_items($job_id){
	  return $this->db->table('job_items')->where('jobs_id', $job_id)->get()->getResultArray();   	   
   }
  
  function job_has_items($jobid){
        $builder = $this->db->table('job_items');
         $builder->where('jobs_id',$jobid);
          $row  = $builder->get()->getRowArray();
          if($row)
             return "1";
             else
             return "0";
    }
  function job_rcvd_payments($job_id){
      $builder = $this->db->table('payment_history');
      $builder->select('SUM(amount_received) as total_received');
      $builder->where('jobs_id', $job_id);
      $response = $builder->get()->getRowArray();  
      if($response)
      return $response['total_received'];
      else
      return "0";
  }   
  
   function get_po_info($jobid){
       $get_po_items=[];
	  $poid_info =  $this->db->table('purchase_orders')->where('jobid', $jobid)->get()->getRowArray();   
	  if($poid_info){
	      
	      $poid = $poid_info['poid']; 
	      $get_po_items = $this->get_po_items($poid);
	     
	      
	  }
	   return $get_po_items;
   }
   
   
    function get_po_items($poid){
	  return $this->db->table('poitems')->where('poid', $poid)->get()->getResultArray();   	   
   }
   
  
    function payment_history($job_id){
	  return $this->db->table('payment_history')->where('jobs_id', $job_id)->orderBy('entry_time','DESC')->get()->getResultArray();   	   
   }
  
  public function get_job_labour_cost($job_id)
{
    $sql = "

    SELECT SUM(t.labour_cost) AS labour_cost

    FROM (

        SELECT 
            j.jobs_id,

            SUM(
                CASE 
                    WHEN ji.item_name='Labour Cost'
                    THEN ji.item_cost 
                    ELSE 0 
                END
            ) AS labour_cost

        FROM jobs j
        LEFT JOIN job_items ji ON ji.jobs_id=j.jobs_id

        WHERE j.jobs_id = ?

        GROUP BY j.jobs_id

    ) t
    ";

    $row = $this->db->query($sql,[$job_id])->getRowArray();

    return $row['labour_cost'] ?? 0;
}


public function get_job_material_cost($job_id)
{

    /*
    =============================
    JOB MATERIAL COST
    =============================
    */

    $sql = "

    SELECT SUM(
        CASE 
            WHEN ji.item_name='Material Cost'
            THEN ji.item_cost
            ELSE 0
        END
    ) AS material_cost

    FROM job_items ji

    WHERE ji.jobs_id = ?
    ";

    $row = $this->db->query($sql,[$job_id])->getRowArray();

    $job_material = $row['material_cost'] ?? 0;



    /*
    =============================
    PURCHASE ORDER COST
    =============================
    */

    $sql = "

    SELECT 

        SUM(
            poi.item_total
            +
            (CASE WHEN po.tax_applied=1 THEN (poi.item_total*13)/100 ELSE 0 END)
            +
            po.shipping_amount
            -
            po.discount_amount
        ) AS po_total

    FROM purchase_orders po
    JOIN poitems poi ON poi.poid = po.poid

    WHERE po.jobid = ?
    ";

    $row = $this->db->query($sql,[$job_id])->getRowArray();

    $po_total = $row['po_total'] ?? 0;



    return $job_material + $po_total;
}

  function ajax_jobs_list(){
		
		$all_roles =  UserTypes();
		
		
		if(isset($_REQUEST['length']) && $_REQUEST['length']!=''){
	       $length = $_REQUEST['length'];	       
	     }
		else{
		  $length = 10;		 
		}
	   if(isset($_REQUEST['start']) && $_REQUEST['start']!=''){
			$start  =$_REQUEST['start'];
	   }	
       else
        $start  = 0;		
        
        if(isset($_REQUEST['filter_customer_id']) && $_REQUEST['filter_customer_id']!=''){
		 	$this->where('customer_id',trim($_REQUEST['filter_customer_id']));
	   }
	   
	   
	   if(isset($_REQUEST['filter_job_status']) && $_REQUEST['filter_job_status']!=''){
		 	$this->where('job_status',trim($_REQUEST['filter_job_status']));
	   }
	   
	  
	    if(isset($_REQUEST['filter_user_id']) && $_REQUEST['filter_user_id']!=''){
		 	$this->where('job_assigned_to',trim($_REQUEST['filter_user_id']));
	   }
        
       if(isset($_REQUEST['filter_daterange']) && $_REQUEST['filter_daterange']!=''){
            $daterange  = explode("to",$_REQUEST['filter_daterange']);
	        $start_date = $daterange[0];
	        
	        if(isset($daterange[1]))
	        $end_date   = $daterange[1];
	        else
	        $end_date   = $daterange[0];
	        
	        $this->where("job_start_date >=",$start_date);
	        $this->where("job_start_date <=",$end_date);
	   }
	   
       
        $this->orderBy('job_start_date','DESC');
		$iTotalRecords   = $this->countAllResults();								
		$iDisplayLength  = intval($length);
		$iDisplayLength  = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
		$iDisplayStart   = intval($start);
		$sEcho           = intval($_REQUEST['draw']);
		$records         = array();
		$records["data"] = array();
		$end             = $iDisplayStart + $iDisplayLength;
		$end             = $end > $iTotalRecords ? $iTotalRecords : $end;
		$id              = 0;
		
		if(isset($_REQUEST['filter_customer_id']) && $_REQUEST['filter_customer_id']!=''){
		 	$this->where('customer_id',trim($_REQUEST['filter_customer_id']));
	   }
	   
	    if(isset($_REQUEST['filter_user_id']) && $_REQUEST['filter_user_id']!=''){
		 	$this->where('job_assigned_to',','.trim($_REQUEST['filter_user_id']).',');
	   }
      
       if(isset($_REQUEST['filter_job_status']) && $_REQUEST['filter_job_status']!=''){
		 	$this->where('job_status',trim($_REQUEST['filter_job_status']));
	   }
	   
       if(isset($_REQUEST['filter_daterange']) && $_REQUEST['filter_daterange']!=''){
            $daterange  = explode("to",$_REQUEST['filter_daterange']);
	        $start_date = $daterange[0];
	         if(isset($daterange[1]))
	        $end_date   = $daterange[1];
	        else
	        $end_date   = $daterange[0];
	        $this->where("job_start_date >=",$start_date);
	        $this->where("job_start_date <=",$end_date);
	   }
	   
	   
		$this->orderBy('job_start_date','DESC');
        $result = $this->findAll($length,$start);
	//	echo $this->db->getLastQuery();

		foreach($result as $values)
			{
			    
			    $advanced_payment = $values['advanced_payment'] ?? 0;
			$working_hours_div='';				
			   $id = ($id + 1);
			   
			   $poitems_total=0;
			   $get_po_info = $this->get_po_info($values['jobs_id']);
			   if($get_po_info){ foreach($get_po_info as $porow){
			       $poitems_total = $poitems_total+$porow['item_total'];
			       
			   }
			       
			   }
			   
			   
			   $payment_history = '';
	            	$payment_history .='<strong>Purchase Order : '.$poitems_total.' </strong><br><table class="table table--light style--two">
                        <thead>
                            <tr>
                                <th>Sr.No.</th>
								<th>Received Amount</th>
								<th>Remarks</th>
								<th>On Dated</th>
                            </tr>
                        </thead><tbody>';
                        $payment_counter=0;
			    $job_payment_hostory =$this->payment_history($values['jobs_id']);
			    if($job_payment_hostory){
			       foreach($job_payment_hostory as $paymentrow){
			           $payment_counter++;
			           	$payment_history .='<tr>
			                    <td>'.$payment_counter.'. </td>
								<td>$'.number_format($paymentrow['amount_received'],2).'</td>
								<td>'.$paymentrow['remarks'].'</td>
								<td>'.date('d M, Y',strtotime($paymentrow['entry_time'])).'</td>
			                    </tr>';	
			           
			       } 
			        
			        
			    }
			    else{
			    	$payment_history .='<tr>
			                    <td colspan="4" align="center">No record found</td>
			                    </tr>';	    
			        
			    }
			  $payment_history .='</tbody></table>';	
			  	$payment_link_modal ='<div class="modal fade" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true"  id="paymentBy'.$values['jobs_id'].'"> <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="" lass="modal-title" id="exampleModalLabel">Payment History</h5>
                    <button type="button" class="close close_paymenthist_model" data-id="paymentBy'.$values['jobs_id'].'"aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
            </div>            
                <div class="modal-body">
                    '.$payment_history.'
                </div>                        
        </div>
    </div>
</div></div>';



			   
			    $job_has_items = $this->job_has_items($values['jobs_id']);
	         
	            $total_rcvd_payments  = $this->job_rcvd_payments($values['jobs_id']);
	            $job_total    = $values['final_amount'];
	         
			    $customer_info  = $this->customer_info($values['customer_id']);
				$job_assigned_to  = explode(",",rtrim(ltrim($values['job_assigned_to'],","),","));
				$staf_names='';
				
				if($job_assigned_to){
					foreach($job_assigned_to as $staff_id){
						if($staff_id>0){
						$staff_info     = $this->staff_info($staff_id);
						$staff_name  = ucwords($staff_info['first_name']).'&nbsp;'.ucwords($staff_info['last_name']);
						$staf_names .=ucwords(strtolower($staff_name)).'<br>';
						
						$working_hours_div .='<div class="row"><div class="form-group col-lg-4">
                         <label for="invoice_to_contact" class="form-control-label font-weight-bold">Staff Name</label><br>
						 '.ucwords($staff_info['first_name']).'&nbsp;'.ucwords($staff_info['last_name']).'
					  </div>
                    <div class="form-group col-lg-4">
                         <label for="invoice_to_contact" class="form-control-label font-weight-bold">Working Hours</label>
                          <input type="text" class="form-control form-control-lg" id="working_hours'.$staff_id.'" name="working_hours['.$staff_id.']" placeholder="HH.MM" value="" required>
                    </div>
                    
                     <div class="form-group col-lg-4">
                         <label for="invoice_to_contact" class="form-control-label font-weight-bold">Entry On</label>
                          <input type="date" class="form-control form-control-lg" id="working_date'.$staff_id.'" name="working_date['.$staff_id.']"  value="'.date('Y-m-d').'" required>
                    </div>
                    </div>';
						}
					}
				}			
			   $staf_names = rtrim($staf_names,",");
			
			    $del_path       = base_url().'/jobs/deleteRecord/'.$values['jobs_id'];
			    $status_path    = base_url().'/jobs/updatestatus/'.$values['jobs_id'];
			    $job_status     = $values['job_status'];
			    
			    
			    $payment_link   = '<a href="javascript:void(0)" title="" style="margin-top:2px;" class="dropdown-item payment" data-id="'.$values['jobs_id'].'" data-code="3XMWKPD1U167"><span>Payment History</span></a>	<a class="dropdown-item" data-id="'.$values['jobs_id'].'" href="'.base_url().'/jobs/photos/'.$values['jobs_id'].'"><span>Add Photos</span></a>
			    ';
                 
                 
			    
			    if($job_status=='closed' && $values['pending_amount']=='0'){
			   $user_actions = '	<a class="dropdown-item" href="'.base_url().'/jobs/edit/'.$values['jobs_id'].'"><span>Edit</span></a><a href="javascript:void(0)" title="" style="margin-top:2px;" class="dropdown-item payment" data-id="'.$values['jobs_id'].'" data-code="3XMWKPD1U167"><span>Payment History</span></a>	<a class="dropdown-item" data-id="'.$values['jobs_id'].'" href="'.base_url().'/jobs/photos/'.$values['jobs_id'].'"><span>Add Photos</span></a>';	     
			    }
			   else  if($job_status=='closed' && $values['pending_amount'] >0){
			  $user_actions = '<div class="dropdown">
													<button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0 waves-effect waves-float waves-light" data-bs-toggle="dropdown">
															<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
														</button>
														<div class="dropdown-menu dropdown-menu-end">
															<a class="dropdown-item" href="'.base_url().'/jobs/edit/'.$values['jobs_id'].'">
																<span>Edit</span>
															</a>'.$payment_link.'
															
														</div>
													</div>';	        
			    }
			    else{
			   $user_actions = '<div class="dropdown">
													<button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0 waves-effect waves-float waves-light" data-bs-toggle="dropdown">
															<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
														</button>
														<div class="dropdown-menu dropdown-menu-end">
															<a class="dropdown-item" href="'.base_url().'/jobs/edit/'.$values['jobs_id'].'">
																<span>Edit</span>
															</a>
															<a class="dropdown-item" onclick="return confirm_delete(\''.$del_path.'\')" href="javascript:void(0);">
																<span>Delete</span>
															</a>
															<a class="dropdown-item markcomplete" data-id="'.$values['jobs_id'].'" href="javascript:void(0);"><span>Mark Completed</span></a>												</a>
												            <a href="javascript:void(0)" class="dropdown-item addworkinghours" data-id="'.$values['jobs_id'].'">Add Working Hours</a> 
												        	<a class="dropdown-item" data-id="'.$values['jobs_id'].'" href="'.base_url().'/purchase_orders/add/'.$values['jobs_id'].'"><span>Add Purchase Order</span></a>'.$payment_link.'
														
														</div>
													</div>';	     
			        
			    }
			    
    	$labour_cost = $this->get_job_labour_cost($values['jobs_id']);
        $material_cost = $this->get_job_material_cost($values['jobs_id']);
        
        $po_total = 0;
        $get_po_info = $this->get_po_info($values['jobs_id']);
        if($get_po_info){
            foreach($get_po_info as $porow){
                $po_total += $porow['item_total'];
            }
        }
        
        $total_material_cost = $material_cost + $po_total;
        
        
        // ✅ Total received from payment history
$total_collected = $total_rcvd_payments;

// ✅ Pending
$total_pending = $job_total - $total_collected;

// ✅ Profit
$profit = $job_total - ($labour_cost + $total_material_cost);


        $cost_modal = '
<div class="modal fade" id="costModal'.$values['jobs_id'].'" tabindex="-1">
<div class="modal-dialog">
<div class="modal-content">

<div class="modal-header">
<h5 class="modal-title">Job Financial Details</h5>
<button type="button" class="close" data-bs-dismiss="modal">
<span>&times;</span>
</button>
</div>

<div class="modal-body">

<table class="table table-bordered">

<thead>
<tr>
<th>Details</th>
<th>Amount</th>
</tr>
</thead>

<tbody>

<tr>
<td><strong>Total Job Amount</strong></td>
<td>$'.number_format($job_total,2).'</td>
</tr>

<tr>
<td>Total Collected</td>
<td class="text-success">$'.number_format($total_collected,2).'</td>
</tr>

<tr>
<td>Total Pending</td>
<td class="text-warning">$'.number_format($total_pending,2).'</td>
</tr>

<tr>
<td>Labour Cost</td>
<td>$'.number_format($labour_cost,2).'</td>
</tr>

<tr>
<td>Material Cost (Material + PO)</td>
<td>$'.number_format($total_material_cost,2).'</td>
</tr>

<tr>
<td><strong>Profit</strong></td>
<td class="'.($profit >= 0 ? 'text-success' : 'text-danger').'">
$'.number_format($profit,2).'
</td>
</tr>
<tr>
<td>Advance Payment</td>
<td>$'.number_format($advanced_payment,2).'</td>
</tr>
</tbody>

</table>

</div>

</div>
</div>
</div>';

$advanced_paymemt_tag='';
if($advanced_payment > 0){
      $advanced_paymemt_tag='<span class="advance-icon" data-bs-toggle="tooltip" title="Advance: $'.number_format($advanced_payment,2).'">
            💰
        </span>';
}
    


	if($job_has_items>0){

    $pending_amount = $job_total - $total_rcvd_payments;

    if($total_rcvd_payments == $job_total){

        $payemnt_status = '
        <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#costModal'.$values['jobs_id'].'">
        $'.number_format($job_total,2).$advanced_paymemt_tag.'
        </a>
        <br>
        <span class="badge rounded-pill badge-light-success">Cleared</span>';

    }else{

        $payemnt_status = '
        <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#costModal'.$values['jobs_id'].'">
        $'.number_format($pending_amount,2).$advanced_paymemt_tag.'
        </a>
        <br>
        <span class="badge rounded-pill badge-light-warning">Pending</span>';
    }

}
else{

    $payemnt_status = '<span class="badge rounded-pill badge-light-info">Open</span>';

}
			
			if($values['job_status']=='closed' || ($values['job_completion_date']!='0000-00-00' && $values['job_completion_date']!='1970-01-01' ))
			  $job_status ='<span class="badge rounded-pill badge-light-success me-1">Closed</span>';
			else
			   $job_status ='<span class="badge rounded-pill badge-light-warning me-1">Open</span>';
			   
			   
				$full_name = ucwords($customer_info['full_name']);
				
				
				 
                 
		
		$markcomplete_div_modal ='<div class="modal fade" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" id="markcompletediv'.$values['jobs_id'].'"> <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="" lass="modal-title" id="exampleModalLabel">Mark Completed</h5>
                    <button type="button" class="close close_addpaymnt_model" data-id="markcompletediv'.$values['jobs_id'].'" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>            
                <div class="modal-body">
                   <form action="'.base_url().'/jobs/mark_completed_job/submit" method="POST">
                <input type="hidden" name="_token" value="'.$values['jobs_id'].'"> 
				<input type="hidden" name="_method" value="POST">              
				<input type="hidden" name="code">
                <div class="modal-body">
                   
                    <div class="form-group col-lg-12">
                         <label for="invoice_to_contact" class="form-control-label font-weight-bold">Completed On</label>
                          <input type="text" class="form-control form-control-lg flatpickr-basic" id="completed_on" name="completed_on" value="'.date('Y-m-d').'"  required>
                    </div>
				   
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary close_addpaymnt_model" data-id="markcompletediv'.$values['jobs_id'].'">Close</button>
                    <button type="submit" class="btn btn-sm btn-success">Save</button>
                </div>
            </form>
                </div>                        
        </div>
    </div>
</div></div>';


	$addworking_hours_modal ='<div class="modal fade" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" id="workinghoursdiv'.$values['jobs_id'].'"> <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="" lass="modal-title" id="exampleModalLabel">Add Working Hours</h5>
                    <button type="button" class="close close_addpaymnt_model" data-id="workinghoursdiv'.$values['jobs_id'].'" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>            
                <div class="modal-body">
                   <form action="'.base_url().'/jobs/add_working_hours/submit" method="POST">
                <input type="hidden" name="_token" value="'.$values['jobs_id'].'"> 
				<input type="hidden" name="_method" value="POST">              
				<input type="hidden" name="code">
                <div class="modal-body">
                   
				    '.$working_hours_div.'
				    
                     <div class="form-group col-lg-12">
                         <label for="invoice_to_contact" class="form-control-label font-weight-bold">Remarks</label>
                          <textarea name="remarks" id="remarks" class="form-control form-control-lg"></textarea>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary close_addpaymnt_model" data-id="workinghoursdiv'.$values['jobs_id'].'">Close</button>
                    <button type="submit" class="btn btn-sm btn-success">Save</button>
                </div>
            </form>
                </div>                        
        </div>
    </div>
</div></div>';
				
				$modal_data='<div class="modal fade" id="RemarksModal'.$values['jobs_id'].'" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel'.$values['jobs_id'].'" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel'.$values['jobs_id'].'">'.$values['work_details'].' remarks</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onClick="closemodal();">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       '.$values['remarks'].'
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onClick="closemodal();" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>';

 if($values['job_completion_date']=='0000-00-00'){
    $job_completion_date = "NILL";
 }
 else
   $job_completion_date  = date('d M,Y',strtotime($values['job_completion_date']));

if($values['job_start_date']!='0000-00-00')
  $job_date ='<p class="mb-1"><strong>Job Started: </strong>'.date('d M,Y',strtotime($values['job_start_date'])).'<br>
              <strong>Job Completion: </strong>'.$job_completion_date.'</p>' ;
  else
  $job_date ='';
				
			    $records["data"][] = array(	
			           sprintf( '%05d', $values['jobs_id'] ),
                      '<strong>'.$full_name.'</strong>'.'<br><small>'.wordwrap($values['customer_location'],15,'<br>').'</small>',
					   ucwords(strtolower($values['work_details'])).$job_date,
					  $staf_names,
					  $payemnt_status.$cost_modal,
					  $job_status,
					  ($values['remarks']!='')?$modal_data.'<a href="javascript:void(0);" data-id="'.$values['jobs_id'].'" class="view_remarks_modal">View</a>':'',
					   date('d M,Y',strtotime($values['entry_time'])),
					  $user_actions.$addworking_hours_modal.$markcomplete_div_modal.$payment_link_modal
				     );
		     }
		   
		   $records["draw"]            = $sEcho;
		   $records["recordsTotal"]    = $iTotalRecords;
		   $records["recordsFiltered"] = $iTotalRecords;
		   return json_encode($records);
		} 
   
  function export_jobs_list(){
		$start=0;
		$all_roles =  UserTypes();
		
		
		if(isset($_GET['length']) && $_GET['length']!=''){
	       $length = $_GET['length'];	       
	     }
		else{
		  $length = 10;		 
		}
	   if(isset($_GET['start']) && $_GET['start']!=''){
			$start  =$_GET['start'];
	   }	
       else
        $start  = 0;		
        
        if(isset($_GET['filter_customer_id']) && $_GET['filter_customer_id']!=''){
		 	$this->where('customer_id',trim($_GET['filter_customer_id']));
	   }
	   
	   
	   if(isset($_GET['filter_job_status']) && $_GET['filter_job_status']!=''){
		 	$this->where('job_status',trim($_GET['filter_job_status']));
	   }
	   
	  
	    if(isset($_GET['filter_user_id']) && $_GET['filter_user_id']!=''){
		 	$this->where('job_assigned_to',trim($_GET['filter_user_id']));
	   }
        
       if(isset($_GET['filter_daterange']) && $_GET['filter_daterange']!=''){
            $daterange  = explode("to",$_GET['filter_daterange']);
	        $start_date = $daterange[0];
	        $end_date   = $daterange[1];
	        $this->where("job_start_date >=",$start_date);
	        $this->where("job_start_date <=",$end_date);
	   }
	   
       
        $this->orderBy('job_start_date','DESC');
		$iTotalRecords   = $this->countAllResults();								
		$iDisplayLength  = intval($length);
		$iDisplayLength  = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
		$iDisplayStart   = intval($start);
		if(isset($_GET['draw']))
		$sEcho           = intval($_GET['draw']);
	   else
		$sEcho=1;
		$records         = array();
		$records["data"] = array();
		$end             = $iDisplayStart + $iDisplayLength;
		$end             = $end > $iTotalRecords ? $iTotalRecords : $end;
		$id              = 0;
		
		if(isset($_GET['filter_customer_id']) && $_GET['filter_customer_id']!=''){
		 	$this->where('customer_id',trim($_GET['filter_customer_id']));
	   }
	   
	    if(isset($_GET['filter_user_id']) && $_GET['filter_user_id']!=''){
		 	$this->where('job_assigned_to',','.trim($_GET['filter_user_id']).',');
	   }
      
       if(isset($_GET['filter_job_status']) && $_GET['filter_job_status']!=''){
		 	$this->where('job_status',trim($_GET['filter_job_status']));
	   }
	   
       if(isset($_GET['filter_daterange']) && $_GET['filter_daterange']!=''){
            $daterange  = explode("to",$_GET['filter_daterange']);
	        $start_date = $daterange[0];
	        $end_date   = $daterange[1];
	        $this->where("job_start_date >=",$start_date);
	        $this->where("job_start_date <=",$end_date);
	   }
	   
	   
		$this->orderBy('job_start_date','DESC');
        $result = $this->findAll();
	//	echo $this->db->getLastQuery();

		foreach($result as $values)
			{
			$working_hours_div='';				
			   $id = ($id + 1);
			   
			   $total_rcvd_payments  = $this->job_rcvd_payments($values['jobs_id']);
	            $job_total    = $values['final_amount'];
	            
	            
			    $job_has_items = $this->job_has_items($values['jobs_id']);
			    $customer_info  = $this->customer_info($values['customer_id']);
				$job_assigned_to  = explode(",",rtrim(ltrim($values['job_assigned_to'],","),","));
				$staf_names='';
				
				if($job_assigned_to){
					foreach($job_assigned_to as $staff_id){
						if($staff_id>0){
						$staff_info     = $this->staff_info($staff_id);
						$staff_name  = ucwords($staff_info['first_name']).' '.ucwords($staff_info['last_name']);
						$staf_names .=ucwords(strtolower($staff_name)).',';
						
						}
					}
				}			
			   $staf_names = rtrim($staf_names,",");
			
			   
			    $job_status     = $values['job_status'];
			    
		
		
			if($job_has_items>0){	
			if($job_total==$total_rcvd_payments){
			  $payemnt_status = 'Cleared';
			}
			else{
			   $payemnt_status = number_format(($job_total-$total_rcvd_payments),2).'(Pending)';
			}
		}
		else{
		  $payemnt_status = 'Open';   
		    
		}
		
		
			
			if($values['job_status']=='closed')
			  $job_status ='Closed';
			else
			   $job_status ='Open';
			   
			   
				$full_name = ucwords($customer_info['full_name']);
				

			 if($values['job_completion_date']=='0000-00-00'){
				$job_completion_date = "NILL";
			 }
			 else
			   $job_completion_date  = date('d M,Y',strtotime($values['job_completion_date']));

			if($values['job_start_date']!='0000-00-00')
			  $job_date ='Job Started: '.date('d M,Y',strtotime($values['job_start_date'])).'<br>
						  Job Completion:'.$job_completion_date;
			  else
			  $job_date ='';
				
			    $records["data"][] = array(	
			          "jobid"=> sprintf( '%05d', $values['jobs_id'] ),
                      "job_name"=>$full_name,
					  "job_title"=> ucwords(strtolower($values['work_details'])).$job_date,
					  "staff_name"=>$staf_names,
					  "payment_status"=>$payemnt_status,
					  "job_status"=>$job_status,
					  "remarks"=>$values['remarks'],
					 "entry_date"=>date('d M,Y',strtotime($values['entry_time']))
					  
				     );
		     }

		   return $records["data"];
		}	
 
   public function delete_job_items($job_id){
     	$this->db->table('job_items')->where('jobs_id', $job_id)->delete();
   }
		
		public function get_total_job_payment($job_id)
{
    $row = $this->db->table('job_items')
        ->select('item_cost')
        ->where('jobs_id', $job_id)
        ->where('item_name', 'Total Job Payment')
        ->get()->getRowArray();

    return $row['item_cost'] ?? 0;
}
  public function deleteRecord($id){
		$this->db->table('jobs')->where('jobs_id', $id)->delete();
		
		$builder1 = $this->db->table('purchase_orders'); 
        $po_result  = $builder1->get()->getResultArray();
        $item_sub_total=0;
        if($po_result){
            $poid = $po_result['poid'];
            $this->db->table('poitems')->where('poid', $poid)->delete();
        }
		
		$this->db->table('purchase_orders')->where('jobid', $id)->delete();
		return 1;
	}
}