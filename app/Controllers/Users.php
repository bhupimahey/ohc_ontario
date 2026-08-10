<?php
namespace App\Controllers;
use App\Models\UsersModel;
use App\Controllers\BaseController;
use App\Libraries\auth_session;
class Users extends BaseController
{
	
	function __construct()
    {  
	    helper(['form', 'url']);
		$this->UsersModel   = new UsersModel();		
		$this->auth_session   = new auth_session();
	    $this->auth_session->admin_restrict();
	    $this->auth_session->role_restrict('S');
    }
	
  public function index()
    {
		$data['message_output']    = $this->message_output;		
		$data['user_roles']        = UserTypes();
	    return view('users/view',$data);		
    }

	function ajax_users_view()
	 {
		echo $this->UsersModel->ajax_teachers_list();		
	 }
 
 
	public function add()
    {
        
   		if($this->request->getMethod() == 'post'){
			$teacherLoginId             = $this->request->getVar('teacherLoginId');
		    $teacherPassword            = $this->request->getVar('teacherPassword');
	        $teacherAddress             = $this->request->getVar('parentAddress');
	        $teacherMobile              = $this->request->getVar('teacherMobile');
	        $teacherAddress             = $this->request->getVar('teacherAddress');
	        $teacherFname               = $this->request->getVar('teacherFname');
	        $teacherLname               = $this->request->getVar('teacherLname');
	        $account_type               = $this->request->getVar('user_role');
			$rules = [				
				'teacherLoginId' => [
					'label'  => 'Login Id',
					'rules'  => 'required',
					'rules'  => "required",
					'errors' => [
						'required' => 'Please enter login id',
					   ]
					   ],				
				  'teacherPassword' => [
					'label'  => 'Password',
					'rules'  => "required",
					'errors' => [
						'required' => 'Please enter password',
					], 
				   ],
		      	'teacherFname' => [
					'label'  => 'First Name',
					'rules'  => "required",
					'errors' => [
						'required' => 'Please enter first name',
					], 
				   ],
				   
				   'teacherLname' => [
					'label'  => 'Last Name',
					'rules'  => "required",
					'errors' => [
						'required' => 'Please enter last name',
					], 
				   ],
				'teacherMobile' => [
					'label'  => 'Mobile',
					'rules'  => "required",
					'errors' => [
						'required' => 'Please enter mobile',
					], 
				   ],
				'teacherAddress' => [
					'label'  => 'Address',
					'rules'  => "required",
					'errors' => [
						'required' => 'Please enter address',
					], 
				   ]	
			    
			];
			
            if(!$this->validate($rules)){
            $data['validation'] = $this->validator->listErrors();
            }else{
				
				$mother_kids_of ='';
				if(isset($_POST['student_ids']) && ($account_type=='5' || $account_type=='6') ){
				    $mother_kids_of = implode(",",$_POST['student_ids']);
				}
				
				$insert_data = [
				          'account_username'   => $teacherLoginId,
				          'account_key'        => password_hash($teacherPassword, PASSWORD_DEFAULT),
				          'show_password'      => $teacherPassword,
				          'first_name'         => $teacherFname,
				          'last_name'          => $teacherLname,
				          'mobile_no'          => $teacherMobile,
				          'ip_address'         => $_SERVER['REMOTE_ADDR'],
				          'created_by'         => $this->session->get('s_user_id'),
						  'address'            => $teacherAddress,		
						  'account_status'     => '1', 
						  'account_type'       => $account_type,
					      'entry_time'         => date('Y-m-d H:i:s')			
					      ];				
						
					$teacher_id = $this->UsersModel->add_teacher($insert_data);
					
					if(isset($_FILES['teacherPhoto']['name']) && ($_FILES['teacherPhoto']['name']!='')){
						if (!file_exists('public/documents/users/')) {
							mkdir('public/documents/users', 0777, true);
						 }
						 if ($_FILES['teacherPhoto']['name'] != "") {
							$updated_image_data['photo_path'] = md5(rand(10000000, 20000000)) . '.jpg';
							move_uploaded_file($_FILES['teacherPhoto']['tmp_name'], 'public/documents/users/' . $updated_image_data['photo_path']);
							$this->UsersModel->update_teacher_image($updated_image_data,$teacher_id);
						 }			
						}	
						
						
					$this->message_output->set_success('Record Inserted successfully');
					return redirect()->to(base_url().'/users'); 
					die;				
			     }			
		     }
		   
		 
		 $data['user_roles']  = UserTypes();     
		 return view('users/add',$data);		
    }
	
	public function edit($teacher_id)
    {
		if(!$teacher_id)
			return redirect()->to(base_url().'/users'); 
		
		if($this->request->getMethod() == 'post'){
		
		    $teacherPassword            = $this->request->getVar('teacherPassword');
	        $teacherMobile              = $this->request->getVar('teacherMobile');
	        $teacherAddress             = $this->request->getVar('teacherAddress');
	        $teacherFname               = $this->request->getVar('teacherFname');
	        $teacherLname               = $this->request->getVar('teacherLname');
	        $account_type               = $this->request->getVar('user_role');
	        $user_status                = $this->request->getVar('user_status');
			$rules = [				
				'teacherFname' => [
					'label'  => 'First Name',
					'rules'  => "required",
					'errors' => [
						'required' => 'Please enter first name',
					], 
				   ],
				   
				'teacherMobile' => [
					'label'  => 'Mobile',
					'rules'  => "required",
					'errors' => [
						'required' => 'Please enter mobile',
					], 
				   ],
				'teacherAddress' => [
					'label'  => 'Address',
					'rules'  => "required",
					'errors' => [
						'required' => 'Please enter address',
					], 
				   ]	
			   
			];
			
			$this->validate($rules);
            if(!$this->validate($rules)){
            $data['validation'] = $this->validator->listErrors();
            }else{				
                
                	$mother_kids_of ='';
    				if(isset($_POST['student_ids']) && ($account_type=='5' || $account_type=='6') ){
    				    $mother_kids_of = implode(",",$_POST['student_ids']);
    				}
				
			    
				if(isset($_FILES['teacherPhoto']['name']) && ($_FILES['teacherPhoto']['name']!='')){
                    
                    if (!file_exists('public/documents/users/')) {
    					mkdir('public/documents/users/', 0777, true);
    			     }
    			     
    		   	     if ($_FILES['teacherPhoto']['name'] != "") {
    					$updated_image_data['photo_path'] = md5(rand(10000000, 20000000)) . '.jpg';
    					move_uploaded_file($_FILES['teacherPhoto']['tmp_name'], 'public/documents/users/' . $updated_image_data['photo_path']);
    					
    					$this->UsersModel->update_teacher_image($updated_image_data,$teacher_id);
    			        }
                  }
                
                if($teacherPassword!=''){
                    
                     $updated_data = [
    						  'account_key'        => password_hash($teacherPassword, PASSWORD_DEFAULT),
    				          'show_password'      => $teacherPassword,
    				          'first_name'         => $teacherFname,
    				          'last_name'          => $teacherLname,
    				          'mobile_no'          => $teacherMobile,
    				          'address'            => $teacherAddress,
    				          'account_type'       => $account_type,
    				          'account_status'     => $user_status
    					    ];	
    					$template=$this->UsersModel->update_teacher($updated_data,$teacher_id);   
                }
                else{
                   
                    $updated_data = [
						  'first_name'         => $teacherFname,
				          'last_name'          => $teacherLname,
				          'mobile_no'          => $teacherMobile,
				          'address'            => $teacherAddress,
				          'account_type'       => $account_type,
				          'account_status'     => $user_status
					      ];	
					      
				    	$template=$this->UsersModel->update_teacher($updated_data,$teacher_id);   
                   }
			
					$this->message_output->set_success('Record Updated successfully');
					return redirect()->to(base_url().'/users'); 
					die;				
			     }			
		     }		
		
		$data['validation']        = '';
		$data['teacher_info']      = $this->UsersModel->teacher_info($teacher_id);
		$data['teacher_id']        = $teacher_id;	
		$data['user_roles']        = UserTypes();     
	    return view('users/edit',$data);		
    }
    
   public function deleteRecord($id)
	{	 
	    $DeleteRec=$this->UsersModel->deleteRecord($id);
		if($DeleteRec){
			$this->message_output->set_success('Record Deleted successfully');
		} else {
			$this->message_output->set_error( 'Please try again! Error while deleting record.');
		}
		return redirect()->to(base_url().'/users'); 	
	}    
	
}
