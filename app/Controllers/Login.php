<?php
namespace App\Controllers;
use App\Models\LoginModel;
class Login extends BaseController
{
	public function __construct()
    {  
	$this->session 	= \Config\Services::session();
	
	}
    public function index()
    {	
	   
    if($this->session->get('s_user_type')){
	    return	redirect()->to('/dashboard');
		die();
	}
	else{
	   $renewalModel = new \App\Models\RenewalModel();
	   //echo password_hash('superadmin', PASSWORD_DEFAULT);	   
	   if($this->request->getMethod() == 'post'){
		    $model      = new LoginModel();
		    $email      = $this->request->getVar('login-email');
		    $password   = $this->request->getVar('login-password');
		    $rules = [				
				'login-email' => [
					'label'  => 'Email',
					'rules'  => 'required',
					'rules'  => "required",
					'errors' => [
						'required' => 'Please fill email',
					    ],
				      ],
				'login-password' => [
					'label'  => 'Password',
					'rules'  => 'required',
					'errors' => [
						'required' => 'Please fill password',
					  ],
				   ]				
			    ];
			
            if(!$this->validate($rules)){
               $data['validation'] = $this->validator->listErrors();
            }else{
				 $data = $model->where('account_username', $email)->first();
						
				 if($data){
				  $pass = $data['account_key'];
				  
				
				  $verify_pass = password_verify($password, $pass);
				  if($verify_pass){
					
					$this->session->set('s_user_id', $data['account_id']);
				       $this->session->set('s_email', $data['email_id']);					
					
					$this->session->set('s_photo_path', $data['photo_path']);	
					 
					 
					$this->session->set('logged_in', TRUE);
					if($data['account_type']=='1'){
					    $this->session->set('s_name', 'Administartor');
					    $this->session->set('s_user_type','S');
					   return redirect()->to(base_url().'/dashboard'); 
					}
					elseif($data['account_type']=='9'){
					    $this->session->set('s_name', $data['first_name'].' '.$data['last_name']);
					    $this->session->set('s_user_type','R');
					   return redirect()->to(base_url().'/receptionist/dashboard'); 
					}
				    else if($data['account_type']=='5'){ // teachers
				        $this->session->set('s_name', $data['first_name'].' '.$data['last_name']);
					    $this->session->set('s_user_type','T');
					    return redirect()->to(base_url().'/instructor/dashboard'); 
				    }
					   					
				 }				 
				 else{
					$this->message_output->set_error('Password not matched!!!');
					return redirect()->to(base_url().'/login'); 					
					}
			     }	
				else{
					$this->message_output->set_error('Details not found!!!');
					return redirect()->to(base_url().'/login'); 					
					}		
	          }
		   } 
		   
	    $view_data['message_output']  = $this->message_output;
	    $view_data['next_renewal']    = $renewalModel->getFormattedRenewalDate();
        return view('login', $view_data);
	}
	}
	public function auth()
    {
        $model = new LoginModel();
        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');

        $data = $model->where('account_username', $email)->first();
/* 		echo "<pre>";
		print_r($data);
		echo "</pre>"; */
        if($data){
            $pass = $data['password'];
			$verify_pass = password_verify($password, $pass);
            if($verify_pass){
                $ses_data = [
                    'user_id'     => $data['user_id'],
                    'email'       => $data['email'],
                    'name'        => $data['fname'],
                    'logged_in'   => TRUE
                ];
                
                $this->session->set('s_user_id', $data['user_id']);
				$this->session->set('s_user_type', $data['user_type']);
				$this->session->set('s_user_name', $data['fname']." ".$data['last_name']);
			
				
				//$this->session->setUserdata('user_id', $data['user_id']);
				//$session->setFlashdata('user_id', $data['user_id']);
				//$this->session->set('fname', $data['fname']);
				//$session->setFlashdata('fname', $data['fname']);
				//$this->session->set('logged_in',true);
				//$session->setFlashdata('logged_in', true);
				//$this->session->set('email',$data['email']);
				//$session->setFlashdata('email', $data['email']);
				//$this->session->setUserdata('user_type',$data['user_type']);
				//$session->setFlashdata('user_type', $data['user_type']);
				if($data['user_type']=='S'){ // S for superadmin
					$folder=superadmin;
					return redirect()->to($folder.'/user');
				}elseif($data['user_type']=='C'){ // C for company
					$folder=company;
					return redirect()->to($folder.'/dashboard');
				}elseif($data['user_type']=='A'){ // A for agent
					$folder=agent;
					return redirect()->to($folder.'/tickets');
				}
				
				//echo $data['user_type'];die;
				//$this->session->set('dir',$folder);
               // return redirect()->to($folder.'/dashboard');
            }else{
                $this->session->setFlashdata('msg', 'Wrong Password');
                return redirect()->to('/login');
            }
        }else{
            $this->session->setFlashdata('msg', 'Email not Found');
            return redirect()->to('/login');
        }
    }
	public function logout()
    {
        //$session = session();
        $this->session->destroy();
        return redirect()->to('/login');
    }
}
