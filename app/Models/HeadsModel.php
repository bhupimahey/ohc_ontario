<?php
namespace App\Models;
use CodeIgniter\Model;

class HeadsModel extends Model	{
	
   	 protected $table = 'payment_heads';
	 protected $primaryKey = 'phead_id';
	 public function __construct() {
	     
        parent::__construct();        
       $db = \Config\Database::connect();	
    }
  
	
	public function update_payment_head($data, $phead_id){
		$this->db->table('payment_heads')->where('phead_id', $phead_id)->update($data);
		return 1;
	}
	

	public function add_payment_head($data){
		$this->db->table('payment_heads')->insert($data);
		return $this->db->insertID();	
	}


   function payment_heads_info($phead_id){
	  return $this->db->table('payment_heads')->where('phead_id', $phead_id)->get()->getRowArray();   	   
   }
   
  function ajax_heads_list(){
		
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
        
       
        $this->orderBy('pay_head_name','ASC');
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
	
		$this->orderBy('pay_head_name','ASC');
        $result = $this->findAll($length,$start);
	//	echo $this->db->getLastQuery();

		foreach($result as $values)
			{
			   $id = ($id + 1);
			
			    $user_actions = '<a class="btn btn-primary btn-sm" href="'.base_url().'/heads/edit/'.$values['phead_id'].'">
																<span>Edit</span>
															</a>';	
		
				$full_name = ucwords($values['pay_head_name']);
			    $records["data"][] = array(			
			         $id,
                     '<strong>'.$full_name.'</strong>',				
					  '$'.$values['pay_head_cost'],
					  $user_actions
				     );
		     }
		   
		   $records["draw"]            = $sEcho;
		   $records["recordsTotal"]    = $iTotalRecords;
		   $records["recordsFiltered"] = $iTotalRecords;
		   return json_encode($records);
		}	
		

}