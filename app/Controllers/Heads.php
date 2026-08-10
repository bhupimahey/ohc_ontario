<?php
namespace App\Controllers;
use App\Models\HeadsModel;
use App\Controllers\BaseController;
use App\Libraries\auth_session;
class Heads extends BaseController
{
	
	function __construct()
    {  
	    helper(['form', 'url']);
		$this->HeadsModel     = new HeadsModel();		
		$this->auth_session   = new auth_session();
	    $this->auth_session->admin_restrict();
	    $this->auth_session->role_restrict('S');
    }
	
  public function index()
    {
		$data['message_output']    = $this->message_output;		
		$data['user_roles']        = UserTypes();
	    return view('payment_heads/view',$data);		
    }

	function ajax_heads_view()
	 {
		echo $this->HeadsModel->ajax_heads_list();		
	 }
 
 
	public function add()
    {
        
   		if($this->request->getMethod() == 'post'){
			$paymentHeadName             = $this->request->getVar('paymentHeadName');
		    $paymentHeadCost             = $this->request->getVar('paymentHeadCost');
	      
			$rules = [				
				'paymentHeadName' => [
					'label'  => 'Payment Head',
					'rules'  => 'required',
					'rules'  => "required",
					'errors' => [
						'required' => 'Please enter payment head name',
					   ]
					   ],				
				  'paymentHeadCost' => [
					'label'  => 'Head Cost',
					'rules'  => "required",
					'errors' => [
						'required' => 'Please enter payment head cost',
					], 
				   ],
			];
			
            if(!$this->validate($rules)){
            $data['validation'] = $this->validator->listErrors();
            }else{
				
				$insert_data = [
				          'pay_head_name'   => $paymentHeadName,
				          'pay_head_cost'   => $paymentHeadCost,
				          'entry_time'      => date('Y-m-d H:i:s')			
					      ];				
						
					$this->HeadsModel->add_payment_head($insert_data);
					$this->message_output->set_success('Record Inserted successfully');
					return redirect()->to(base_url().'/heads'); 
					die;				
			     }			
		     }
		     
		 return view('payment_heads/add');		
    }
	
	public function edit($head_id)
    {
		if(!$head_id)
			return redirect()->to(base_url().'/heads'); 
		
		if($this->request->getMethod() == 'post'){
		
		    $paymentHeadName             = $this->request->getVar('paymentHeadName');
		    $paymentHeadCost             = $this->request->getVar('paymentHeadCost');
				$rules = [				
				'paymentHeadName' => [
					'label'  => 'Payment Head',
					'rules'  => 'required',
					'rules'  => "required",
					'errors' => [
						'required' => 'Please enter payment head name',
					   ]
					   ],				
				  'paymentHeadCost' => [
					'label'  => 'Head Cost',
					'rules'  => "required",
					'errors' => [
						'required' => 'Please enter payment head cost',
					], 
				   ],
			];
			
			$this->validate($rules);
            if(!$this->validate($rules)){
            $data['validation'] = $this->validator->listErrors();
            }else{				
                
                    $updated_data = [
						    'pay_head_name'   => $paymentHeadName,
				            'pay_head_cost'   => $paymentHeadCost,
					      ];	
					      
				    	$template=$this->HeadsModel->update_payment_head($updated_data,$head_id);   
                   }
			
					$this->message_output->set_success('Record Updated successfully');
					return redirect()->to(base_url().'/heads'); 
					die;				
			  		
		     }		
		
		$data['validation']        = '';
		$data['head_info']      = $this->HeadsModel->payment_heads_info($head_id);
		$data['head_id']        = $head_id;	
	    return view('payment_heads/edit',$data);		
    }
  
	
}
