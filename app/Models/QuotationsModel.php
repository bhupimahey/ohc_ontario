<?php
namespace App\Models;
use CodeIgniter\Model;

class QuotationsModel extends Model	{
    	 protected $table = 'quotations';
	 protected $primaryKey = 'quotation_id';
	 public function __construct() {
	       
        parent::__construct();        
       $db = \Config\Database::connect();	
    }
       
  	public function update_quotation($quotation_id,$data){
		$this->db->table('quotations')->where('quotation_id', $quotation_id)->update($data);
	}
	
  	public function add_quotation($data){
		$this->db->table('quotations')->insert($data);
		return $this->db->insertID();
	}
  
    public function add_qoutation_items($data){
		$this->db->table('quotation_items')->insert($data);
			
	}
	
  public function remove_quotation_items($quotation_id){
      	$this->db->table('quotation_items')->where('quotation_id', $quotation_id)->delete();
      
  }	
	
   function get_service_item_info($phead_id){
	  return $this->db->table('payment_heads')->where('phead_id', $phead_id)->get()->getRowArray();   	   
   }
   
  function ajax_quotations_list(){
		
	
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
	   
	   
	   if(isset($_REQUEST['filter_daterange']) && $_REQUEST['filter_daterange']!=''){
	        $daterange  = explode("to",$_REQUEST['filter_daterange']);
	        $start_date = $daterange[0];
	        $end_date   = $daterange[1];
	        $this->where("entry_time >=",$start_date);
	        $this->where("entry_time <=",$end_date);
        }
        $this->orderBy('entry_time','DESC');
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
	   
	 if(isset($_REQUEST['filter_daterange']) && $_REQUEST['filter_daterange']!=''){
	        $daterange  = explode("to",$_REQUEST['filter_daterange']);
	        $start_date = $daterange[0];
	        $end_date   = $daterange[1];
	        $this->where("entry_time >=",$start_date);
	        $this->where("entry_time <=",$end_date);
        }
	   
		$this->orderBy('entry_time','DESC');
        $result = $this->findAll($length,$start);
	
		foreach($result as $values)
			{
			    $id = ($id + 1);
			    $del_path       = base_url().'/quotations/deleteRecord/'.$values['quotation_id'];
			    $customer_info  = $this->customer_info($values['customer_id']);
			    if($customer_info){
			      $customer_name    = $customer_info['full_name'];
			      $customer_mobile  = $customer_info['phone_no'];
			        
			    }
			    else{
			    $customer_name    =  '';
			    $customer_mobile  =  '';
			    }
			    $user_actions = '
			    <a href="'.base_url().'/quotations/detail/'.$values['quotation_id'].'" data-bs-toggle="tooltip" class="text-body" data-bs-placement="top" aria-label="View Quotation" data-bs-original-title="View Quotation" aria-describedby="tooltip653257"><svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg></a>
			    <a href="'.base_url().'/quotations/edit/'.$values['quotation_id'].'" data-bs-toggle="tooltip" class="text-body" data-bs-placement="top" aria-label="Send Mail" data-bs-original-title="Send Mail" aria-describedby="tooltip517464"> <div class="css-9a5dmo"><svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></div></a>
			   ';
                    	
			  $records["data"][] = array(	
			         '#'.sprintf('%05d', $values['quotation_id']),
			         $customer_name.'<br>'.$customer_mobile,
                     wordwrap($values['customer_location'],'30','<br>'),
					 
					 wordwrap($values['notes'],'30','<br>'),
					 '$'.number_format($values['subtotal'],2),
					 '$'.number_format($values['net_total'],2),
					 date('d M,Y',strtotime($values['entry_time'])),
					 $user_actions
			       );
			}
		   $records["draw"]            = $sEcho;
		   $records["recordsTotal"]    = $iTotalRecords;
		   $records["recordsFiltered"] = $iTotalRecords;
		   return json_encode($records);
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

 function customer_info($customer_id){
	  return $this->db->table('customers')->where('customer_id', $customer_id)->get()->getRowArray();   	   
   }
   
   function quotation_info($quotation_id){
	  return $this->db->table('quotations')->where('quotation_id', $quotation_id)->get()->getRowArray();   	   
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
  function get_quotation_items($quotation_id){
	  return $this->db->table('quotation_items')->where('quotation_id', $quotation_id)->get()->getResultArray();   	   
   }   
    
}