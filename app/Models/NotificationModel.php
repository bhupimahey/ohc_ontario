<?php
namespace App\Models;
use CodeIgniter\Model;

class NotificationModel extends Model	{

   protected $table      = 'notifications';
   protected $primaryKey = 'notification_id';
	 
   public function __construct() {
        parent::__construct();        
        $db = \Config\Database::connect();	
    }

	public function update_notification($data, $pmt_id){
		$this->db->table('notifications')->where('notification_id', $pmt_id)->update($data);
		return 1;
	}

   public function AllusersDropdown(){
	 $all_category     = $this->db->table('users')->orderBy('first_name','ASC')->get()->getResult(); 
   	  $final_list       = array();
	  if( $all_category){
		foreach( $all_category as $row)
			$final_list[$row->account_id] = ucwords($row->first_name).' '.ucwords($row->last_name);
	  }
      return $final_list;	  
   }
   
   public function AllstudentsDropdown(){
	 $all_category     = $this->db->table('admissions')->where('is_left', '0')->get()->getResult(); 
   	  $final_list       = array();
	  if( $all_category){
		foreach( $all_category as $row)
			$final_list[$row->admission_id] = ucwords($row->first_name).' '.ucwords($row->last_name).'(father : '.ucwords($row->father_name).')';
	  }
      return $final_list;	  
   }
   
    public function ajax_students_wise_list(){
	  $all_category     = $this->db->table('admissions')->where('is_left', '0')->get()->getResult(); 
	  $final_list       = array();
	  if( $all_category){
		foreach( $all_category as $row)
			$final_list[$row->admission_id] = ucwords($row->first_name).' '.ucwords($row->last_name).'(father : '.ucwords($row->father_name).')';
	  } 
      return form_dropdown("filter_user_ids[]",$final_list,'','class="form-control select2" id="filter_user_ids" multiple="true"'); 
   }
   
   function ajax_users_role_wise_list($account_type){
      $all_category     = $this->db->table('users')->where('account_type', $account_type)->orderBy('first_name','ASC')->get()->getResult(); 
   	  $final_list       = array();
	  if( $all_category){
		foreach( $all_category as $row)
			$final_list[$row->account_id] = ucwords($row->first_name).' '.ucwords($row->last_name).'-'.$row->mobile_no;
	  }
      return form_dropdown("filter_user_ids[]",$final_list,'','class="form-control select2" id="filter_user_ids" multiple="true"'); 
   }
   
   public function add_notification($data){
		$this->db->table('notifications')->insert($data);
		return $this->db->insertID();	
	  }
 
  public function notification_info($notification_id){
	  return $this->db->table('notifications')->where('notification_id', $notification_id)->get()->getRowArray();   	   
   }
   
  function ajax_notification_list(){
		$all_roles        =  UserTypes();
		array_push($all_roles,'Students');
		$AllusersDropdown = $this->AllusersDropdown();
		$AllstudentsDropdown = $this->AllstudentsDropdown();
	//	echo '<pre>';
	//	print_r($AllusersDropdown);
		if(isset($_REQUEST['length']) && $_REQUEST['length']!=''){
	       $length = $_REQUEST['length'];	       
	     }
		else{
		  $length = 10;		 
	 	 }
		
		
	   if(isset($_REQUEST['start']) && $_REQUEST['start']!=''){
			$start  = $_REQUEST['start'];
	   }	
       else
           $start  = 0;		
        
        if(isset($_REQUEST['filter_notification_for']) && $_REQUEST['filter_notification_for']!=''){
		 	$this->where('filter_notification_for',trim($_REQUEST['filter_notification_for']));
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
		
	    if(isset($_REQUEST['filter_notification_for']) && $_REQUEST['filter_notification_for']!=''){
		 	$this->where('filter_notification_for',trim($_REQUEST['filter_notification_for']));
	    }
	    
        $this->orderBy('entry_time','DESC');
        $result = $this->findAll($length,$start);
        //	echo $this->getLastQuery();
		foreach($result as $values)
			{
			   
			  if(isset($values['notification_student_ids']) && $values['notification_student_ids']!=''){
			       $all_user_ids = explode(',',trim($values['notification_student_ids']));
			       $sub_user_ids = '';
			       foreach($all_user_ids as $ukey => $user_ids){
			           if(isset($AllstudentsDropdown[trim($user_ids)]))
			            $sub_user_ids .= '<li><strong>'.$AllstudentsDropdown[trim($user_ids)].'</strong></li><br>';
			          
			          }
			       $sub_user_ids =  rtrim($sub_user_ids,",");
			     }
			  else if(isset($values['notification_ids']) && $values['notification_ids']!='') {
			       $all_user_ids = explode(',',trim($values['notification_ids']));
			       $sub_user_ids = '';
			       foreach($all_user_ids as $ukey => $user_ids){
			           if(isset($AllusersDropdown[trim($user_ids)]))
			            $sub_user_ids .= '<li><strong>'.$AllusersDropdown[trim($user_ids)].'</strong></li><br>';
			          }
			          
			       $sub_user_ids =  rtrim($sub_user_ids,",");
			     }
			  else
			    $sub_user_ids    ='<li><strong>All</strong></li>';
			 
			 
			    $id            = ($id + 1);
			    $del_path      = base_url().'/notifications/deleteRecord/'.$values['notification_id'];
			    $user_actions  = '<div class="dropdown">
													<button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0 waves-effect waves-float waves-light" data-bs-toggle="dropdown">
														<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
														</button>
														<div class="dropdown-menu dropdown-menu-end">
															<a class="dropdown-item" href="'.base_url().'/notifications/edit/'.$values['notification_id'].'">
																<span>Edit</span>
															</a>
															<a class="dropdown-item" onclick="return confirm_delete(\''.$del_path.'\')" href="javascript:void(0);">
														
																<span>Delete</span>
															</a>
													
														</div>
								 </div>';	
			
			    $records["data"][] = array(	
			          $values['notification_title'],
			          wordwrap($values['notification_body'],'50','<br>'),
                      $all_roles[$values['notification_for']],
                      '<small><ul>'.$sub_user_ids.'</ul></small>',
					  date('d M,Y',strtotime($values['entry_time'])),
					  $user_actions
				     );
		     }
		   
		   $records["draw"]            = $sEcho;
		   $records["recordsTotal"]    = $iTotalRecords;
		   $records["recordsFiltered"] = $iTotalRecords;
		   return json_encode($records);
		}	
		
    public function deleteRecord($id){
		$this->db->table('notifications')->where('notification_id', $id)->delete();
		return 1;
	}
}