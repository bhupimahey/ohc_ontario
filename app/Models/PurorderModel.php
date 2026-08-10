<?php
namespace App\Models;
use CodeIgniter\Model;

class PurorderModel extends Model	{
	 protected $table = 'purchase_orders';
	 protected $primaryKey = 'poid';
	 public function __construct() {
	     
    parent::__construct();      
	
       $db = \Config\Database::connect();	
       $this->session = \Config\Services::session();
    }

	public function update_po_order($poid,$data){
		$this->db->table('purchase_orders')->where('poid', $poid)->update($data);
	}

	public function add_po_order($data){
		$this->db->table('purchase_orders')->insert($data);
		return $this->db->insertID();
	}
   
   public function add_po_items($data){
		$this->db->table('poitems')->insert($data);
			
	}

    public function AllJobsDropdown(){
	  $all_customers    = $this->db->table('jobs')->where('job_status','open')->get()->getResultArray(); 
	  $final_list       = array();
	  $final_list['']   = 'Choose Job';
	  if( $all_customers){
		foreach( $all_customers as $row)
			$final_list[$row['jobs_id']] =  sprintf( '%05d', $row['jobs_id'] );
	  }
      return $final_list;	  
   }
   
  public function AllVendorsDropdown(){
	  $all_customers     = $this->db->table('vendors')->get()->getResult(); 
	  $final_list       = array();
	  $final_list['']   = 'Choose Vendor';
	  if( $all_customers){
		foreach( $all_customers as $row)
			$final_list[$row->vendor_id] = ucwords($row->full_name).' '.$row->phone_no;
	  }
      return $final_list;	  
   }	
   
 
   
   function vendor_info($vendor_id){
	  return $this->db->table('vendors')->where('vendor_id', $vendor_id)->get()->getRowArray();   	   
   }
  
   function po_info($poid){
	  return $this->db->table('purchase_orders')->where('poid', $poid)->get()->getRowArray();   	   
   }
   
   function get_po_items($poid){
	  return $this->db->table('poitems')->where('poid', $poid)->get()->getResultArray();   	   
   }
  
 
    
  function ajax_po_list(){
		
			
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
        
        if(isset($_REQUEST['filter_vendor_id']) && $_REQUEST['filter_vendor_id']!=''){
		 	$this->where('vendor_id',trim($_REQUEST['filter_vendor_id']));
	   }
	  
	   if(isset($_REQUEST['filter_job_id']) && $_REQUEST['filter_job_id']!=''){
		 	$this->where('jobid',trim($_REQUEST['filter_job_id']));
	   }
	   
	   
       if(isset($_REQUEST['filter_daterange']) && $_REQUEST['filter_daterange']!=''){
            $daterange  = explode("to",$_REQUEST['filter_daterange']);
	        $start_date = $daterange[0];
	        $end_date   = $daterange[1];
	        $this->where("po_date >=",$start_date);
	        $this->where("po_date <=",$end_date);
	   }
	   
       
        $this->orderBy('po_date','DESC');
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
		
		 if(isset($_REQUEST['filter_vendor_id']) && $_REQUEST['filter_vendor_id']!=''){
		 	$this->where('vendor_id',trim($_REQUEST['filter_vendor_id']));
	   }
	   
	    if(isset($_REQUEST['filter_job_id']) && $_REQUEST['filter_job_id']!=''){
		 	$this->where('jobid',trim($_REQUEST['filter_job_id']));
	   }
	  
       if(isset($_REQUEST['filter_daterange']) && $_REQUEST['filter_daterange']!=''){
            $daterange  = explode("to",$_REQUEST['filter_daterange']);
	        $start_date = $daterange[0];
	        $end_date   = $daterange[1];
	        $this->where("po_date >=",$start_date);
	        $this->where("po_date <=",$end_date);
	   }
	   
	   
		$this->orderBy('po_date','DESC');
        $result = $this->findAll($length,$start);
    	//	echo $this->db->getLastQuery();

		foreach($result as $values)
			{
			   $po_items_div='';	
			  $po_items_div .=' <table class="table" id="customers_table">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Sr.No.</th>
										    <th>Item</th>
										    <th>Description</th>
                                            <th>Qty</th>
                                            <th>Unit Price</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead><tbody>';	
			   $id = ($id + 1);
			   
	         
			    $vendor_info  = $this->vendor_info($values['vendor_id']);
				$vendor_name ='';
				 if($vendor_info){
					$vendor_name  = $vendor_info['full_name'];					
				}
				$item_sub_total=0;
				$cctt=0;
				$po_items =  $this->get_po_items($values['poid']);
				if($po_items){
					foreach($po_items as $itmrow){
					    $item_sub_total=$item_sub_total+$itmrow['item_total'];
						$cctt++;
						$po_items_div .=' 
                                        <tr>
                                            <td>'.$cctt.'.</td>
										    <td>'.$itmrow['item_name'].'</td>
										    <td>'.$itmrow['item_desc'].'</td>
                                            <td>'.$itmrow['item_qty'].'</td>
                                            <td>'.$itmrow['unit_price'].'</td>
                                            <td>'.$itmrow['item_total'].'</td>
                                        </tr>';
					}
					
				}
				 $po_items_div .= '</tbody></table>';
				$job_assigned_to=array();
				if($job_assigned_to){
					foreach($job_assigned_to as $staff_id){
						if($staff_id>0){
					
						$working_hours_div .='<div class="row"><div class="form-group col-lg-4">
                         <label for="invoice_to_contact" class="form-control-label font-weight-bold">Item</label><br>
						 &nbsp;dfgdfgdfg
					  </div>
                    <div class="form-group col-lg-4">
                         <label for="invoice_to_contact" class="form-control-label font-weight-bold">Description</label>
                          <input type="text" class="form-control form-control-lg" id="working_hours'.$staff_id.'" name="working_hours['.$staff_id.']" placeholder="HH.MM" value="" required>
                    </div>
                    <div class="form-group col-lg-4">
                         <label for="invoice_to_contact" class="form-control-label font-weight-bold">Qty</label>
                          <input type="text" class="form-control form-control-lg" id="working_hours'.$staff_id.'" name="working_hours['.$staff_id.']" placeholder="HH.MM" value="" required>
                    </div> 
					<div class="form-group col-lg-4">
                         <label for="invoice_to_contact" class="form-control-label font-weight-bold">Unit Price</label>
                          <input type="text" class="form-control form-control-lg" id="working_hours'.$staff_id.'" name="working_hours['.$staff_id.']" placeholder="HH.MM" value="" required>
                    </div>
                     <div class="form-group col-lg-4">
                         <label for="invoice_to_contact" class="form-control-label font-weight-bold">Total</label>
                          <input type="date" class="form-control form-control-lg" id="working_date'.$staff_id.'" name="working_date['.$staff_id.']"  value="'.date('Y-m-d').'" required>
                    </div>
                    </div>';
						}
					}
				}			
			   
			    $del_path       = base_url().'/purchase_orders/deleteRecord/'.$values['poid'];
			   
			  
			   $user_actions = '<div class="dropdown">
													<button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0 waves-effect waves-float waves-light" data-bs-toggle="dropdown">
															<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
														</button>
														<div class="dropdown-menu dropdown-menu-end">
															<a class="dropdown-item" href="'.base_url().'/purchase_orders/edit/'.$values['poid'].'">
																<span>Edit</span>
															</a>
															<a class="dropdown-item" onclick="return confirm_delete(\''.$del_path.'\')" href="javascript:void(0);">
																<span>Delete</span>
															</a>
																										</a>
												            
														</div>
													</div>';	     
			        
	
			
	$items_modal ='<div class="modal fade" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" id="workinghoursdiv'.$values['poid'].'"> <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="" lass="modal-title" id="exampleModalLabel">Purchase Order Items</h5>
                    <button type="button" class="close close_addpaymnt_model" data-id="workinghoursdiv'.$values['poid'].'" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>            
                <div class="modal-body">
				    '.$po_items_div.'	
               </div>
                <div class="modal-footer">
                </div>
            
                </div>                        
        </div>
    </div>
</div></div>';

if($values['tax_applied']=='1'){
    $tax_applied_total = ($item_sub_total*13)/100;
    $tax_applied ='<br><small>HST(13%)</small>';
  }
else{
 $tax_applied ='';
 $tax_applied_total='0';
}
				
				 if($values['bill_photo']!=''){
				   $show_image = '<a href="'.base_url().'/public/documents/bills/'.$values['bill_photo'].'" target="-blank">View</a>';
			   }
			   else{
				  $show_image  = '';
			   }
			   
               
				$total = $tax_applied_total+$item_sub_total+$values['shipping_amount']-$values['discount_amount'];
			    $records["data"][] = array(	
			           $values['ponumber'],
			           sprintf( '%05d', $values['jobid'] ),
					   '<strong>'.ucwords($vendor_name).'</strong>'.'<br><small>'.wordwrap($vendor_info['address'],15,'<br>').'</small><br><small>Shipping: '.$values['shipping_amount'].'</small><br><small>Discount: '.$values['discount_amount'].'</small>'.$tax_applied,
					   '<a href="javascript:void(0)" class="addworkinghours" data-id="'.$values['poid'].'">View</a>',
					  number_format($total,2),
					 $show_image,
					  $values['remarks'],
					   date('d M,Y',strtotime($values['po_date'])),
					  $user_actions.$items_modal
				     );
		     }
		   
		   $records["draw"]            = $sEcho;
		   $records["recordsTotal"]    = $iTotalRecords;
		   $records["recordsFiltered"] = $iTotalRecords;
		   return json_encode($records);
		} 
   
 function export_purchaseorders_list(){
		 if(isset($_REQUEST['filter_vendor_id']) && $_REQUEST['filter_vendor_id']!=''){
		 	$this->where('vendor_id',trim($_REQUEST['filter_vendor_id']));
	      }
	      
	      if(isset($_REQUEST['filter_job_id']) && $_REQUEST['filter_job_id']!=''){
		 	$this->where('jobid',trim($_REQUEST['filter_job_id']));
	   } 
	  
       if(isset($_REQUEST['filter_daterange']) && $_REQUEST['filter_daterange']!=''){
            $daterange  = explode("to",$_REQUEST['filter_daterange']);
	        $start_date = $daterange[0];
	        $end_date   = $daterange[1];
	        $this->where("po_date >=",$start_date);
	        $this->where("po_date <=",$end_date);
	      }
	   
		$this->orderBy('po_date','DESC');
        $result = $this->findAll();
    	//	echo $this->db->getLastQuery();

		foreach($result as $values)
			{
			   
		
			    $vendor_info  = $this->vendor_info($values['vendor_id']);
				$vendor_name ='';
				 if($vendor_info){
					$vendor_name  = $vendor_info['full_name'];					
				}
				$item_sub_total=0;
				$cctt=0;
				$po_items =  $this->get_po_items($values['poid']);
				if($po_items){
					foreach($po_items as $itmrow){
					    $item_sub_total=$item_sub_total+$itmrow['item_total'];
					}}
			
               
				$total =$item_sub_total+$values['shipping_amount']-$values['discount_amount'];
			    $records["data"][] = array(	
			           "ponumber"=> $values['ponumber'],
					   "vendor_name"=>ucwords($vendor_name),
					   "address"=>$vendor_info['address'],
					  "total"=>number_format($total,2),
					  "shipping_amount"=>$values['shipping_amount'],
					  "discount_amount"=>$values['discount_amount'],
					  "remarks"=>$values['remarks'],
					  "po_date"=>date('d M,Y',strtotime($values['po_date'])),
					 
				     );
		     }
		   
		   return $records["data"];
		} 
   
   public function delete_po_items($poid){
     	$this->db->table('poitems')->where('poid', $poid)->delete();
   }
		
  public function deleteRecord($poid){
        $this->db->table('poitems')->where('poid', $poid)->delete();
		$this->db->table('purchase_orders')->where('poid', $poid)->delete();
		return 1;
	}
}