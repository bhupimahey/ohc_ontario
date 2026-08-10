<?php
namespace App\Controllers;
use App\Models\StaffModel;
use App\Controllers\BaseController;
use App\Libraries\auth_session;
class Staff extends BaseController
{
	
	function __construct()
    {  
	    helper(['form', 'url']);
		$this->StaffModel   = new StaffModel();		
		$this->auth_session   = new auth_session();
	    $this->auth_session->admin_restrict();
	    $this->auth_session->role_restrict('S');
    }
	
  public function index()
    {
		$data['message_output']    = $this->message_output;		
	    return view('staff/view',$data);		
    }

	function ajax_staff_view()
	 {
		echo $this->StaffModel->ajax_staff_list();		
	 }
 
 
	public function add()
    {
        
   		if($this->request->getMethod() == 'post'){
			
	        $staffAddress             = $this->request->getVar('staffAddress');
	        $staffMobile              = $this->request->getVar('staffMobile');
	        $staffFname               = $this->request->getVar('staffFname');
	        $staffLname               = $this->request->getVar('staffLname');
	        $hourly_rate                 = $this->request->getVar('hourly_rate');
	        
			$rules = [				
				'staffFname' => [
					'label'  => 'First Name',
					'rules'  => "required",
					'errors' => [
						'required' => 'Please enter first name',
					], 
				   ],
				   
				   'staffLname' => [
					'label'  => 'Last Name',
					'rules'  => "required",
					'errors' => [
						'required' => 'Please enter last name',
					], 
				   ],
				'staffMobile' => [
					'label'  => 'Mobile',
					'rules'  => "required",
					'errors' => [
						'required' => 'Please enter mobile',
					], 
				   ],
				'staffAddress' => [
					'label'  => 'Address',
					'rules'  => "required",
					'errors' => [
						'required' => 'Please enter address',
					], 
				   ],
			  'hourly_rate' => [
					'label'  => 'Hourly Paid',
					'rules'  => "required",
					'errors' => [
						'required' => 'Please enter hourly rate',
					], 
				   ] 	
			    
			];
			
            if(!$this->validate($rules)){
            $data['validation'] = $this->validator->listErrors();
            }else{
			
				
				$insert_data = [
				          'account_username'   => date('sih'),
				          'account_key'        => password_hash(date('sih'), PASSWORD_DEFAULT),
				          'show_password'      => date('sih'),
				          'first_name'         => $staffFname,
				          'last_name'          => $staffLname,
				          'mobile_no'          => $staffMobile,
				          'account_type'       => '3',
				          'ip_address'         => $_SERVER['REMOTE_ADDR'],
				          'created_by'         => $this->session->get('s_user_id'),
						  'address'            => $staffAddress,		
						  'account_status'     => '1', 
					      'entry_time'         => date('Y-m-d H:i:s'),
					      'hourly_rate'        => $hourly_rate,
					      ];				
						
					$staff_id                  = $this->StaffModel->add_staff($insert_data);
					if($staff_id){
					
					if(isset($_FILES['staffPhoto']['name']) && ($_FILES['staffPhoto']['name']!='')){
						if (!file_exists('public/documents/staff/')) {
							mkdir('public/documents/staff', 0777, true);
						 }
						 if ($_FILES['staffPhoto']['name'] != "") {
							$updated_image_data['photo_path'] = md5(rand(10000000, 20000000)) . '.jpg';
							move_uploaded_file($_FILES['staffPhoto']['tmp_name'], 'public/documents/staff/' . $updated_image_data['photo_path']);
							$this->StaffModel->update_staff_image($updated_image_data,$staff_id);
						   }			
						}	
						
						
					$this->message_output->set_success('Record Inserted successfully');
					return redirect()->to(base_url().'/staff'); 
					}
					else{
					    $this->message_output->set_error('Mobile already present, try another one');
				        return redirect()->to(base_url().'/staff'); 
					   }
					
					die;				
			     }			
		     }
		   
		    
		 return view('staff/add');		
    }
	
	public function edit($staff_id)
    {
		if(!$staff_id)
			return redirect()->to(base_url().'/staff'); 
		
		if($this->request->getMethod() == 'post'){
		
		    $staffAddress             = $this->request->getVar('staffAddress');
	        $staffMobile              = $this->request->getVar('staffMobile');
	        $staffFname               = $this->request->getVar('staffFname');
	        $staffLname               = $this->request->getVar('staffLname');
	        $user_status              = $this->request->getVar('staffStatus');
	        $hourly_rate                 = $this->request->getVar('hourly_rate');
	        
			$rules = [				
				'staffFname' => [
					'label'  => 'First Name',
					'rules'  => "required",
					'errors' => [
						'required' => 'Please enter first name',
					], 
				   ],
				   
				'staffMobile' => [
					'label'  => 'Mobile',
					'rules'  => "required",
					'errors' => [
						'required' => 'Please enter mobile',
					], 
				   ],
				'staffAddress' => [
					'label'  => 'Address',
					'rules'  => "required",
					'errors' => [
						'required' => 'Please enter address',
					], 
				   ],
			  'hourly_rate' => [
					'label'  => 'Hourly Paid',
					'rules'  => "required",
					'errors' => [
						'required' => 'Please enter hourly rate',
					], 
				   ] 	
			   
			];
			
			$this->validate($rules);
            if(!$this->validate($rules)){
            $data['validation'] = $this->validator->listErrors();
            }else{				
                
                
				if(isset($_FILES['staffPhoto']['name']) && ($_FILES['staffPhoto']['name']!='')){
                    
                    if (!file_exists('public/documents/staff/')) {
    					mkdir('public/documents/staff/', 0777, true);
    			     }
    			     
    		   	     if ($_FILES['staffPhoto']['name'] != "") {
    					$updated_image_data['photo_path'] = md5(rand(10000000, 20000000)) . '.jpg';
    					move_uploaded_file($_FILES['staffPhoto']['tmp_name'], 'public/documents/staff/' . $updated_image_data['photo_path']);
    					
    					$this->StaffModel->update_staff_image($updated_image_data,$staff_id);
    			        }
                  }
                
                
                   
                    $updated_data = [
						  'first_name'         => $staffFname,
				          'last_name'          => $staffLname,
				          'mobile_no'          => $staffMobile,
				          'address'            => $staffAddress,
				          'account_status'     => $user_status,
				          'hourly_rate'        => $hourly_rate,
					      ];	
					      
				    	$template=$this->StaffModel->update_staff($updated_data,$staff_id);   
                
			
					$this->message_output->set_success('Record Updated successfully');
					return redirect()->to(base_url().'/staff'); 
					die;				
			     }			
		     }		
		
		$data['validation']        = '';
		$data['staff_info']      = $this->StaffModel->staff_info($staff_id);
		$data['staff_id']        = $staff_id;	
		    
	    return view('staff/edit',$data);		
    }
    
   public function deleteRecord($id)
	{	 
	    $DeleteRec=$this->StaffModel->deleteRecord($id);
		if($DeleteRec){
			$this->message_output->set_success('Record Deleted successfully');
		} else {
			$this->message_output->set_error( 'Please try again! Error while deleting record.');
		}
		return redirect()->to(base_url().'/staff'); 	
	}    

	
}
