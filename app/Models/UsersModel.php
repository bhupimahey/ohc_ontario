<?php
namespace App\Models;
use CodeIgniter\Model;

class UsersModel extends Model	{
	
   	 protected $table = 'users';
	 protected $primaryKey = 'account_id';
	 public function __construct() {
	     
        parent::__construct();        
       $db = \Config\Database::connect();	
    }
    
     function teacher_photos(){
       $all_category     = $this->db->table('users')->where('account_type', '4')->orderBy('first_name','ASC')->get()->getResultArray(); 
	  $final_list       = array();
	  if( $all_category){
		 foreach( $all_category as $row)
		 	$final_list[$row['account_id']]  = $row['photo_path'];
	     }
	   return $final_list;	  
    }
    
	
	public function update_teacher($data, $account_id){
		$this->db->table('users')->where('account_id', $account_id)->update($data);
		return 1;
	}
	
	public function update_teacher_image($data, $account_id){
		$this->db->table('users')->where('account_id', $account_id)->update($data);
		return 1;
	}

	public function add_teacher($data){
		$this->db->table('users')->insert($data);
		return $this->db->insertID();	
	}



   function teacher_info($account_id){
	  return $this->db->table('users')->where('account_id', $account_id)->get()->getRowArray();   	   
   }
   
  function ajax_teachers_list(){
		
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
        
        if(isset($_REQUEST['filter_search_name']) && $_REQUEST['filter_search_name']!=''){
		 	$this->like('first_name',trim($_REQUEST['filter_search_name']));
	   }
	   
	    if(isset($_REQUEST['filter_search_mobile']) && $_REQUEST['filter_search_mobile']!=''){
		 	$this->like('mobile_no',trim($_REQUEST['filter_search_mobile']));
	   }
        
       if(isset($_REQUEST['filter_user_role']) && $_REQUEST['filter_user_role']!=''){
		  $this->like('account_type',trim($_REQUEST['filter_user_role']));
	   }
	   
        $this->where('account_type','4');
        $this->orderBy('first_name','ASC');
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
		   $this->like('first_name',trim($_REQUEST['filter_search_name']));
	   }
	   
	    if(isset($_REQUEST['filter_search_mobile']) && $_REQUEST['filter_search_mobile']!=''){
		  $this->like('mobile_no',trim($_REQUEST['filter_search_mobile']));
	   }
	   
	    if(isset($_REQUEST['filter_user_role']) && $_REQUEST['filter_user_role']!=''){
		  $this->like('account_type',trim($_REQUEST['filter_user_role']));
	   }
	   
	   
	    $this->where('account_type','4');
		$this->orderBy('first_name','ASC');
        $result = $this->findAll($length,$start);
	//	echo $this->db->getLastQuery();

		foreach($result as $values)
			{
			   $id = ($id + 1);
			
			   
			   
			   if($values['photo_path']!=''){
				   $show_image = base_url().'/public/documents/users/'.$values['photo_path'];
			   }
			   else{
				  $show_image  = base_url().'/public/documents/placeholder.png';
			   }
			   
			   $del_path       = base_url().'/users/deleteRecord/'.$values['account_id'];
			   
			   
			   $user_actions = '<a  href="'.base_url().'/users/edit/'.$values['account_id'].'"><span class="badge bg-primary">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-star me-25"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                                            <span>Edit</span>
                                        </span></a>
                                        
                                      	<a  onclick="return confirm_delete(\''.$del_path.'\')" href="javascript:void(0);"> <span class="badge bg-danger">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-star me-25"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                                            <span>Delete</span>
                                        </span> </a>';
			
			if($values['account_status']=='1')
			  $user_status ='<span class="badge rounded-pill badge-light-success me-1">Active</span>';
			else
			$user_status ='<span class="badge rounded-pill badge-light-danger me-1">Inactive</span>';
			
				$full_name = ucwords($values['first_name']).' '.ucwords($values['last_name']);
			    $records["data"][] = array(			
			          $id.'.',
                      '<img src="'.$show_image.'" width="50px;" height="50px;" style="border-radius: 100px;"><br><strong>'.$full_name.'</strong><br>'.$user_status,				
					  $values['show_password'],
					  
					  $values['mobile_no'],
					  wordwrap($values['address'],'30','<br>'),
					  '<span class="badge rounded-pill badge-light-primary me-1">'.$all_roles[$values['account_type']].'</span>',
					  $user_actions
				     );
		     }
		   
		   $records["draw"]            = $sEcho;
		   $records["recordsTotal"]    = $iTotalRecords;
		   $records["recordsFiltered"] = $iTotalRecords;
		   return json_encode($records);
		}	
		

		
public function deleteRecord($id){
		$this->db->table('users')->where('account_id', $id)->where('account_type','4')->delete();
		return 1;
	}
}