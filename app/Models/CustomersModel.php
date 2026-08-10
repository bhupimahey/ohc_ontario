<?php
namespace App\Models;
use CodeIgniter\Model;

class CustomersModel extends Model	{
	
   	 protected $table = 'customers';
	 protected $primaryKey = 'customer_id';
	 public function __construct() {
	     
        parent::__construct();        
       $db = \Config\Database::connect();	
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
   

  public function update_customer($data, $customer_id){
		$this->db->table('customers')->where('customer_id', $customer_id)->update($data);
		return 1;
	}
	
	public function update_customer_image($data, $customer_id){
		$this->db->table('customers')->where('customer_id', $customer_id)->update($data);
		return 1;
	}

	public function add_customer($data){
		$this->db->table('customers')->insert($data);
		return $this->db->insertID();	
	}

   function customer_info($customer_id){
	  return $this->db->table('customers')->where('customer_id', $customer_id)->get()->getRowArray();   	   
   }
   
  function ajax_customers_list(){
		
			if(isset($_REQUEST['filter_export']) && $_REQUEST['filter_export']!='')
			    $filter_export="1";
		    else
		       $filter_export="0";
		    
	
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
        
        if(isset($_REQUEST['filter_search_name']) && $_REQUEST['filter_search_name']!=''){
		 	$this->like('full_name',trim($_REQUEST['filter_search_name']));
	   }
	   
	    if(isset($_REQUEST['filter_search_mobile']) && $_REQUEST['filter_search_mobile']!=''){
		 	$this->like('phone_no',trim($_REQUEST['filter_search_mobile']));
	   }
	   if(isset($_REQUEST['filter_daterange']) && $_REQUEST['filter_daterange']!=''){
	        $daterange  = explode("to",$_REQUEST['filter_daterange']);
	        $start_date = $daterange[0];
	        $end_date   = $daterange[1];
	        $this->where("entry_time >=",$start_date);
	        $this->where("entry_time <=",$end_date);
        }
        $this->orderBy('full_name','ASC');
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
		
		if(isset($_REQUEST['filter_search_name']) && $_REQUEST['filter_search_name']!=''){
		   $this->like('full_name',trim($_REQUEST['filter_search_name']));
	   }
	   
	    if(isset($_REQUEST['filter_search_mobile']) && $_REQUEST['filter_search_mobile']!=''){
		  $this->like('phone_no',trim($_REQUEST['filter_search_mobile']));
	   }
	   
	 if(isset($_REQUEST['filter_daterange']) && $_REQUEST['filter_daterange']!=''){
	        $daterange  = explode("to",$_REQUEST['filter_daterange']);
	        $start_date = $daterange[0];
	        $end_date   = $daterange[1];
	        $this->where("entry_time >=",$start_date);
	        $this->where("entry_time <=",$end_date);
        }
	   
		$this->orderBy('full_name','ASC');
		if($filter_export=="1")
        $result = $this->findAll();
        else
         $result = $this->findAll($length,$start);
	//echo $this->db->GetLastQuery();
		foreach($result as $values)
			{
			   $id = ($id + 1);
			   
			   if($values['photo_path']!=''){
				   $show_image = base_url().'/public/documents/customers/'.$values['photo_path'];
			   }
			   else{
				  $show_image  = base_url().'/public/documents/placeholder.png';
			   }
			   
			   $del_path       = base_url().'/customers/deleteRecord/'.$values['customer_id'];
			   
			   
			    $user_actions = '<a  href="'.base_url().'/customers/edit/'.$values['customer_id'].'"><span class="badge bg-primary">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-star me-25"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                                            <span>Edit</span>
                                        </span></a>
                                        
                                      ';
                    	
			
				$full_name = ucwords($values['full_name']);
			    $records["data"][] = array(		
			          $id,
                      '<img src="'.$show_image.'" width="50px;" height="50px;" style="border-radius: 100px;"><br><strong>'.$full_name.'</strong>',				
					  $values['phone_no'],
					   $values['email_id'],
					  wordwrap($values['address'],'30','<br>'),
					  date('d M,Y',strtotime($values['entry_time'])),
					  $user_actions
				     );
		     }
		   
		   $records["draw"]            = $sEcho;
		   $records["recordsTotal"]    = $iTotalRecords;
		   $records["recordsFiltered"] = $iTotalRecords;
		   return json_encode($records);
		}
		
   function ajax_export_list(){
	    $start=0;
		if(isset($_GET['length']) && $_GET['length']!=''){
	       $length = $_GET['length'];	       
	     }
		else{
		  $length = 10;		 
		}
	    
        if(isset($_GET['filter_search_name']) && $_GET['filter_search_name']!=''){
		 	$this->like('full_name',trim($_GET['filter_search_name']));
	   }
	   
	    if(isset($_GET['filter_search_mobile']) && $_GET['filter_search_mobile']!=''){
		 	$this->like('phone_no',trim($_GET['filter_search_mobile']));
	   }
	   if(isset($_GET['filter_daterange']) && $_GET['filter_daterange']!=''){
	        $daterange  = explode("to",$_GET['filter_daterange']);
	        $start_date = $daterange[0];
	        $end_date   = $daterange[1];
	        $this->where("entry_time >=",$start_date);
	        $this->where("entry_time <=",$end_date);
        }
        $this->orderBy('full_name','ASC');
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
		
		if(isset($_GET['filter_search_name']) && $_GET['filter_search_name']!=''){
		   $this->like('full_name',trim($_GET['filter_search_name']));
	   }
	   
	    if(isset($_GET['filter_search_mobile']) && $_GET['filter_search_mobile']!=''){
		  $this->like('phone_no',trim($_GET['filter_search_mobile']));
	   }
	   
	 if(isset($_GET['filter_daterange']) && $_GET['filter_daterange']!=''){
	        $daterange  = explode("to",$_GET['filter_daterange']);
	        $start_date = $daterange[0];
	        $end_date   = $daterange[1];
	        $this->where("entry_time >=",$start_date);
	        $this->where("entry_time <=",$end_date);
        }
	   
		$this->orderBy('full_name','ASC');
	     $result = $this->findAll();
	//echo $this->db->GetLastQuery();
		foreach($result as $values)
			{
			   $id = ($id + 1);
			
				$full_name = ucwords($values['full_name']);
			    $records["data"][] = array(		
			         "full_name"=>$full_name,				
					 "phone_no"=>$values['phone_no'],
					 "email_id"=> $values['email_id'],
					 "address"=>$values['address'],
					 "entry_on"=>date("d M,Y",strtotime($values['entry_time'])),
				     );
		     }
		   
		  return $records["data"];
		}
}