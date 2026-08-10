<?php
namespace App\Models;

use CodeIgniter\Model;

class CommonModel extends Model	{
	
    public function __construct() {
        parent::__construct();        
       $db = \Config\Database::connect();	
    }
   
   public function total_enquiry_list(){
       
      $all_doctors     = $this->db->table('doctors')->get()->getResult(); 
	  $final_list      = array();
	  $final_list['']  = 'Choose';
	  if( $all_doctors){
		foreach( $all_doctors as $row)
			$final_list[$row->doctor_id] = ucwords($row->full_name);
	  }
      return $final_list;	  
	}
}