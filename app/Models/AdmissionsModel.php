<?php
namespace App\Models;
use CodeIgniter\Model;
class AdmissionsModel extends Model	{	
	 protected $table = 'admissions';
	 protected $primaryKey = 'admission_id';
	 public function __construct() {
        parent::__construct();        
        $db = \Config\Database::connect();	
    }

	public function add_doctor($data,$full_name){
	    $this->db->table('doctors')->insert($data);
		return $this->db->insertID();		
	}

	public function update_doctor($data,$doctor_id){
	 	$this->db->table('doctors')->where('doctor_id', $doctor_id)->update($data);
		return 1;
	}
	
    public function ajax_referred_category_wise_list($category_id,$sel_doctor_id){
       
      $all_category     = $this->db->table('doctors')->where('catagory_id', $category_id)->get()->getResult(); 
	  $final_list       = array();
	  $final_list['']   = 'Choose';
	  if( $all_category){
		foreach( $all_category as $row)
			$final_list[$row->doctor_id] = ucwords($row->full_name);
	  }
	  
	  if($sel_doctor_id!='0')
	    $show_doctor_id = $sel_doctor_id;
	    else
	    $show_doctor_id = '';
	    
	 $final_list['9999'] ='Other';
     return form_dropdown("doctor_id",$final_list,$show_doctor_id,'class="form-control select2" id="doctor_id" required');
   }
   
   public function student_notification_info($admission_id){
       return  $this->db->table('notifications')->where('notification_student_ids LIKE "%,'.$admission_id.',%" ')->orderBy('entry_time','DESC')->get()->getResultArray(); 
   }
   
   public function student_billing_info($admission_id){
        return $this->db->table('users_fee')->where('admission_id', $admission_id)->orderBy('entry_time','DESC')->get()->getResultArray();  
   }
   
   public function GetDoctorCategory($doctor_id){
     return $this->db->table('doctors')->where('doctor_id', $doctor_id)->get()->getRow(); 
   }
	
  public function AllCategoryDropdown(){
	  $all_category     = $this->db->table('enquiry_category')->get()->getResult(); 
	  $final_list       = array();
	  $final_list['']   = 'Choose Category';
	  if( $all_category){
		foreach( $all_category as $row)
			$final_list[$row->encatg_id] = ucwords($row->category_name);
	  }
      return $final_list;	  
   }	
	
   public function add_admission_fee_status($data){
       $this->db->table('admission_fee_data')->insert($data);
   }	
	
   public function add_admission($data,$enquiry_id){
	    if($enquiry_id >0){ 	  
        $this->update_walkings(array('current_status'=>'c','conversion_date'=>date('Y-m-d H:i:s')), $enquiry_id); 			  
	    $exists = $this->db->table('admissions')->where('enquiry_id', $enquiry_id)->countAllResults(); 
	     if($exists==0){
		    $this->db->table('admissions')->insert($data);
		    return $this->db->insertID();
		 }
		 else
			return 0;
	    }
	    else{
	        $is_exists  =  $this->db->table('admissions')->where('LOWER(first_name)', strtolower($data['first_name']))->where('father_mobile', trim($data['father_mobile']))->countAllResults();  
	        if($is_exists >0)
	        return "9999999"; // already exists
	        else{
	             $this->db->table('admissions')->insert($data);  
	             return $this->db->insertID();
	        }
	    }
	}
	
	public function update_walkings($data, $id){
		$this->db->table('walkings')->where('walking_id', $id)->update($data);
		return 1;
	}
 
   public function update_admission($data, $id){
		$this->db->table('admissions')->where('admission_id', $id)->update($data);
		return 1;
	}

   public function deleteRecord($id){
		$this->db->table('admissions')->where('admission_id', $id)->delete();
		return 1;
	}

   public function markleftRecord($id){
	   $this->db->table('admissions')->where('admission_id',$id)->update(array('is_left'=>'1'));
	   return 1;
	}
	
   public function allreferredlist($category_id){
       
      $all_category     = $this->db->table('doctors')->where('catagory_id', $category_id)->get()->getResult(); 
	  $final_list       = array();
	  if( $all_category){
		foreach( $all_category as $row)
			$final_list[$row->doctor_id] = $row->doctor_id;
	  }
	   return $final_list;
    }
	
   public function AllDoctorsDropdown(){
	  $all_doctors     = $this->db->table('doctors')->get()->getResult(); 
	  $final_list      = array();
	  $final_list['']  = 'Choose';
	  if( $all_doctors){
		foreach( $all_doctors as $row)
			$final_list[$row->doctor_id] = ucwords($row->full_name);
	  }
      return $final_list;	  
   }
   
   function admission_info($admission_id){
	  return $this->db->table('admissions')->where('admission_id', $admission_id)->get()->getRow();   	   
   }
   
   function instructor_info($instructor_id){
	  return $this->db->table('users')->where('account_id', $instructor_id)->get()->getRowArray();   	   
   }
   
   function enquiry_info($walking_id){
	  return $this->db->table('walkings')->where('walking_id', $walking_id)->get()->getRow();   	   
   }
      
  function admission_history($admission_id){
       return $this->db->table('admission_installments')->where('admission_id', $admission_id)->get()->getResult();   	    
      
    }      
   
  function admission_detail_info($admission_id){
       return $this->db->table('admissions')->where('admission_id', $admission_id)->get()->getRowArray();   	    
      
    } 
    
  public function get_category_from_referr($referr_id){
	   return  $this->db->table('doctors')->where('doctor_id', $referr_id)->get()->getRow(); 
	} 
      
      
  function ajax_admissions_list(){
		
		$all_category        = $this->AllCategoryDropdown();
		$session_timing_list = session_timing_list();
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
				
	 	$all_doctors = $this->AllDoctorsDropdown();
                $this->orderBy('first_name');
        
		if(isset($_REQUEST['filter_search_name']) && $_REQUEST['filter_search_name']!='')
	    		$this->where('( first_name LIKE "%'.$_REQUEST['filter_search_name'].'%"  OR  last_name LIKE "%'.$_REQUEST['filter_search_name'].'%" OR mother_name LIKE "%'.$_REQUEST['filter_search_name'].'%"  )');	
		
		if(isset($_REQUEST['filter_search_mobile']) && $_REQUEST['filter_search_mobile']!='')
	    		$this->where('(  mother_mobile LIKE "%'.$_REQUEST['filter_search_mobile'].'%" OR father_mobile LIKE "%'.$_REQUEST['filter_search_mobile'].'%"  )');	
				
	    if(isset($_REQUEST['filter_doctor_id']) && $_REQUEST['filter_doctor_id']!='')
	        $this->where("doctor_id",$_REQUEST['filter_doctor_id']);
       
	   if(isset($_REQUEST['filter_enquiry_type']) && $_REQUEST['filter_enquiry_type']!='')
	        $this->where("enquiry_type",$_REQUEST['filter_enquiry_type']);
      
      	if(isset($_REQUEST['filter_daterange']) && $_REQUEST['filter_daterange']!=''){
	        $daterange  = explode("to",$_REQUEST['filter_daterange']);
	        $start_date = $daterange[0];
	        $end_date   = $daterange[1];
	        $this->where("entry_time >=",$start_date);
	        $this->where("entry_time <=",$end_date);
         }
         
        $this->where("is_left","0");
         
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
			
		$this->orderBy('first_name');       
 		if(isset($_REQUEST['filter_search_name']) && $_REQUEST['filter_search_name']!='')
	    		$this->where('( first_name LIKE "%'.$_REQUEST['filter_search_name'].'%"  OR  last_name LIKE "%'.$_REQUEST['filter_search_name'].'%" OR mother_name LIKE "%'.$_REQUEST['filter_search_name'].'%"  )');	
		
		if(isset($_REQUEST['filter_search_mobile']) && $_REQUEST['filter_search_mobile']!='')
	    		$this->where('(  mother_mobile LIKE "%'.$_REQUEST['filter_search_mobile'].'%" OR father_mobile LIKE "%'.$_REQUEST['filter_search_mobile'].'%"  )');	
		
	    if(isset($_REQUEST['filter_doctor_id']) && $_REQUEST['filter_doctor_id']!='')
	        $this->where("doctor_id",$_REQUEST['filter_doctor_id']);
	 
	     if(isset($_REQUEST['filter_enquiry_type']) && $_REQUEST['filter_enquiry_type']!='')
	        $this->where("enquiry_type",$_REQUEST['filter_enquiry_type']);
		
		if(isset($_REQUEST['filter_daterange']) && $_REQUEST['filter_daterange']!=''){
	        $daterange  = explode("to",$_REQUEST['filter_daterange']);
	        $start_date = $daterange[0];
	        $end_date   = $daterange[1];
	        $this->where("entry_time >=",$start_date);
	        $this->where("entry_time <=",$end_date);
         }
         $this->where("is_left","0"); 
        $result = $this->findAll($length,$start);	
		foreach($result as $values)
			{
			    $id = ($id + 1);
			   
			    if(isset($all_doctors[$values['doctor_id']]))
					$doctor_name = ucwords($all_doctors[$values['doctor_id']],' ');
				 else
					$doctor_name = '-';
				
				
				if($values['enquiry_type']=='1'){
				   $show_enquiry ='<span class="badge rounded-pill badge-light-primary me-1">OPD</span>';
				}
				elseif($values['enquiry_type']=='2'){
				   $show_enquiry ='<span class="badge rounded-pill badge-light-info me-1">Assessment</span>';				
				  }
				  else
				   $show_enquiry ='<span class="badge rounded-pill badge-light-success me-1">Direct</span>';
				   
			//	 $joining_info = date('d M,Y',strtotime($values['date_of_joining']));   
				 
				 
				  $referr_category = $this->get_category_from_referr($values['doctor_id']);
			 	  if($referr_category){
			               $referr_category_name = $all_category[$referr_category->catagory_id];
				   }
			           else{
			                $referr_category_name = 'N/A';  
			              }                
                 
				 
				 $user_actions = '<div class="dropdown">
													<button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0 waves-effect waves-float waves-light" data-bs-toggle="dropdown">
															<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
														</button>
														<div class="dropdown-menu dropdown-menu-end">
															<a class="dropdown-item" href="'.base_url().'/admissions/edit/'.$values['admission_id'].'">
															<svg viewBox="0 0 20 20" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
																<span>Edit</span>
															</a>
														<a class="dropdown-item" href="'.base_url().'/admissions/mark_left/'.$values['admission_id'].'">
															<svg viewBox="0 0 20 20" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="23" y1="11" x2="17" y2="11"></line></svg>
																<span>Mark Left</span>
															</a>
														</div>
													</div>';
				 
				 $users_action_links ='<a class="badge rounded-pill badge-light-primary me-1" href="'.base_url().'/admissions/edit/'.$values['admission_id'].'"><small class="fw-bolder">Edit</small></a><br>
									<a class="badge rounded-pill badge-light-primary me-1" style="margin-top:10px;" href="'.base_url().'/admissions/mark_left/'.$values['admission_id'].'"><small class="fw-bolder">Mark Left</small></a>';
				
				 $dobexplode = explode("-",$values['dob']);
				 
				 if(isset($dobexplode[0])){
				    $current_age = getAge($values['dob']); 
				     
				 }
				 else{
				   	$current_age = $values['dob'];  
				 }
			
				$age_on_admissions = str_replace('years','Y, ',$values['age_on_admission']);
				$age_on_admissions = str_replace('months','M',$age_on_admissions);
				
			     $records["data"][] = array(				     
					  '<a href="'.base_url().'/admissions/student_view/'.$values['admission_id'].'" target="_blank">'.$values['first_name'].'&nbsp;'.$values['last_name'].'</a><br><strong>'. $current_age.'</strong><br><strong>'.ucwords($values['sex']).'</strong><br><br>'.$users_action_links,
					  '<strong>'.$age_on_admissions.'</strong>',
					  $values['mother_name'].'<br><small>'.$values['mother_mobile'].'</small><br><small>'.$values['mother_occupation'].'</small>',
					  $values['father_name'].'<br><small>'.$values['father_mobile'].'</small><br><small>'.$values['father_occupation'].'</small>',						 
					  wordwrap($doctor_name,20,"<br>").'<br><span class="badge rounded-pill badge-light-primary me-1">'.$referr_category_name.'</span><br><br>'. $show_enquiry,
					  wordwrap($values['address'],25,"<br>")  ,
					 
					  date('d M, Y',strtotime($values['entry_time']))
					//  $joining_info
					  
				     );
		     }
		   
		   $records["draw"]            = $sEcho;
		   $records["recordsTotal"]    = $iTotalRecords;
		   $records["recordsFiltered"] = $iTotalRecords;
		   return json_encode($records);
		}	
		
		
 function ajax_left_admissions_list(){
			$all_category        = $this->AllCategoryDropdown();
		$session_timing_list = session_timing_list();
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
				
	 	$all_doctors = $this->AllDoctorsDropdown();
        $this->orderBy('first_name');
        
		if(isset($_REQUEST['filter_search_name']) && $_REQUEST['filter_search_name']!='')
	    		$this->where('( first_name LIKE "%'.$_REQUEST['filter_search_name'].'%"  OR  last_name LIKE "%'.$_REQUEST['filter_search_name'].'%" OR mother_name LIKE "%'.$_REQUEST['filter_search_name'].'%"  )');	
		
		if(isset($_REQUEST['filter_search_mobile']) && $_REQUEST['filter_search_mobile']!='')
	    		$this->where('(  mother_mobile LIKE "%'.$_REQUEST['filter_search_mobile'].'%" OR father_mobile LIKE "%'.$_REQUEST['filter_search_mobile'].'%"  )');	
				
	    if(isset($_REQUEST['filter_doctor_id']) && $_REQUEST['filter_doctor_id']!='')
	        $this->where("doctor_id",$_REQUEST['filter_doctor_id']);
       
	   if(isset($_REQUEST['filter_enquiry_type']) && $_REQUEST['filter_enquiry_type']!='')
	        $this->where("enquiry_type",$_REQUEST['filter_enquiry_type']);
      
      	if(isset($_REQUEST['filter_daterange']) && $_REQUEST['filter_daterange']!=''){
	        $daterange  = explode("to",$_REQUEST['filter_daterange']);
	        $start_date = $daterange[0];
	        $end_date   = $daterange[1];
	        $this->where("entry_time >=",$start_date);
	        $this->where("entry_time <=",$end_date);
         }
         
        $this->where("is_left","1");
         
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
			
		$this->orderBy('first_name');       
 		if(isset($_REQUEST['filter_search_name']) && $_REQUEST['filter_search_name']!='')
	    		$this->where('( first_name LIKE "%'.$_REQUEST['filter_search_name'].'%"  OR  last_name LIKE "%'.$_REQUEST['filter_search_name'].'%" OR mother_name LIKE "%'.$_REQUEST['filter_search_name'].'%"  )');	
		
		if(isset($_REQUEST['filter_search_mobile']) && $_REQUEST['filter_search_mobile']!='')
	    		$this->where('(  mother_mobile LIKE "%'.$_REQUEST['filter_search_mobile'].'%" OR father_mobile LIKE "%'.$_REQUEST['filter_search_mobile'].'%"  )');	
		
	    if(isset($_REQUEST['filter_doctor_id']) && $_REQUEST['filter_doctor_id']!='')
	        $this->where("doctor_id",$_REQUEST['filter_doctor_id']);
	 
	     if(isset($_REQUEST['filter_enquiry_type']) && $_REQUEST['filter_enquiry_type']!='')
	        $this->where("enquiry_type",$_REQUEST['filter_enquiry_type']);
		
		if(isset($_REQUEST['filter_daterange']) && $_REQUEST['filter_daterange']!=''){
	        $daterange  = explode("to",$_REQUEST['filter_daterange']);
	        $start_date = $daterange[0];
	        $end_date   = $daterange[1];
	        $this->where("entry_time >=",$start_date);
	        $this->where("entry_time <=",$end_date);
         }
         $this->where("is_left","1"); 
        $result = $this->findAll($length,$start);	
		foreach($result as $values)
			{
			    $id = ($id + 1);
			   
			    if(isset($all_doctors[$values['doctor_id']]))
					$doctor_name = ucwords($all_doctors[$values['doctor_id']],' ');
				 else
					$doctor_name = '-';
				
			 $referr_category = $this->get_category_from_referr($values['doctor_id']);
			 	  if($referr_category){
			               $referr_category_name = $all_category[$referr_category->catagory_id];
				   }
			           else{
			                $referr_category_name = 'N/A';  
			              }
			    
			   // $dobexplode = explode("-",$values['dob']);
				 
			//	 if(isset($dobexplode[0])){
				 //   $current_age = getAge($values['dob']); 
				     
				// }
				// else{
				   	$current_age = $values['dob'];  
				 //}
				 
			              
			if($values['enquiry_type']=='1'){
				   $show_enquiry ='<span class="badge rounded-pill badge-light-primary me-1">OPD</span>';
				}
				elseif($values['enquiry_type']=='2'){
				   $show_enquiry ='<span class="badge rounded-pill badge-light-info me-1">Assessment</span>';				
				  }
				  else
				   $show_enquiry ='<span class="badge rounded-pill badge-light-success me-1">Direct</span>';
				   
				 $joining_info = date('d M,Y',strtotime($values['date_of_joining']));   
				 
				 
			     $records["data"][] = array(				     
					  $values['first_name'].' '.$values['last_name'].'<br><strong>'. $current_age.'</strong><br><strong>'.ucwords($values['sex']).'</strong>',
					  $values['mother_name'].'<br><small>'.$values['mother_mobile'].'</small><br><small>'.$values['mother_occupation'].'</small>',
					  $values['father_name'].'<br><small>'.$values['father_mobile'].'</small><br><small>'.$values['father_occupation'].'</small>',						 
					   wordwrap($doctor_name,20,"<br>").'<br><span class="badge rounded-pill badge-light-primary me-1">'.$referr_category_name.'</span><br><br>'. $show_enquiry,
					  wordwrap($values['address'],25,"<br>")  ,
					  date('d M,Y',strtotime($values['entry_time'])).'<br>'
					
				     );
		     }
		   
		   $records["draw"]            = $sEcho;
		   $records["recordsTotal"]    = $iTotalRecords;
		   $records["recordsFiltered"] = $iTotalRecords;
		   return json_encode($records);
		}		


 public function session_info($session_id){
       return  $this->db->table('teacher_sessions')->where('session_id',$session_id)->get()->getRowArray();  
    }
    
 public function session_attachment_info($session_id){
     return $this->session_info($session_id);
  }	
	
  function ajax_student_sessions_list(){
		
		$builder      = $this->db->table('teacher_sessions');
		$student_id   = $_REQUEST['filter_student_id'];
		$session_timing_list = session_timing_list();
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
				
	    $builder->where('student_id',$student_id);
        $builder->orderBy('session_date DESC, session_start_time DESC');
      
      	if(isset($_REQUEST['filter_daterange']) && $_REQUEST['filter_daterange']!=''){
	        $daterange  = explode("to",$_REQUEST['filter_daterange']);
	        $start_date = $daterange[0];
	        $end_date   = $daterange[1];
	        $builder->where("entry_time >=",$start_date);
	        $builder->where("entry_time <=",$end_date);
         }
         
		$iTotalRecords   = $builder->countAllResults();								
		$iDisplayLength  = intval($length);
		$iDisplayLength  = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
		$iDisplayStart   = intval($start);
		$sEcho           = intval($_REQUEST['draw']);
		$records         = array();
		$records["data"] = array();
		$end             = $iDisplayStart + $iDisplayLength;
		$end             = $end > $iTotalRecords ? $iTotalRecords : $end;
		$id              = 0;
			
		$builder->orderBy('session_date DESC, session_start_time DESC');     
 	    $builder->where('student_id',$student_id);
		if(isset($_REQUEST['filter_daterange']) && $_REQUEST['filter_daterange']!=''){
	        $daterange  = explode("to",$_REQUEST['filter_daterange']);
	        $start_date = $daterange[0];
	        $end_date   = $daterange[1];
	        $builder->where("entry_time >=",$start_date);
	        $builder->where("entry_time <=",$end_date);
           }
       
       $builder->limit($length,$start);
       $result = $builder->get()->getResultArray();
    
		foreach($result as $values)
			{
			    $id              = ($id + 1);
			    $instructor_info = $this->instructor_info($values['teacher_id']);
			    $teacher_name    = $instructor_info['first_name'].' '.$instructor_info['last_name']; 
			  	$session_title   = $values['session_title'];  
			  	$session_date    = date('d M, Y',strtotime($values['session_date'])); 
			  	$session_time    = $values['session_start_time'].'-'.$values['session_end_time']; 
			  	
			  	 $vw_path   =  base_url().'/admissions/view_session_attachments/'.$values['session_id'];
			  	$link_title =  'View Attachments';
			    $attachments_url = '<a href="javascript::void(0)" class="btn btn-outline-primary btn-rounded btn-sm ml-1" onclick="showLargeModal(\''.$vw_path.'\',\''.$link_title.'\')"><i class="mdi mdi-plus"></i>View</a>';
			  	
			     $records["data"][] = array(				     
					  $teacher_name,
					  $session_title,
					  $session_date.'<br>'.$session_time,						 
					  $attachments_url
				     );
		     }
		   
		   $records["draw"]            = $sEcho;
		   $records["recordsTotal"]    = $iTotalRecords;
		   $records["recordsFiltered"] = $iTotalRecords;
		   return json_encode($records);
		}			
}