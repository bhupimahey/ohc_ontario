<?php
namespace App\Controllers;
use App\Models\QuotationsModel;
use App\Controllers\BaseController;
use App\Libraries\auth_session;
class Quotations extends BaseController
{
	
	function __construct()
    {  
	    helper(['form', 'url']);
		$this->QuotationsModel   = new QuotationsModel();		
		$this->auth_session   = new auth_session();
	    $this->auth_session->admin_restrict();
	    $this->auth_session->role_restrict('S');
    }
	
  public function index()
    {
    	$data['message_output']    = $this->message_output;		
		$data['user_roles']        = UserTypes();
	    return view('quotations/view',$data);		
    }

	function ajax_quotations_view()
	 {
	   echo $this->QuotationsModel->ajax_quotations_list();		
	 }
 
  public function print_invoice($quotation_id)
    {
     	if(!$quotation_id)
		 return redirect()->to(base_url().'/quotations'); 
			
    	$quotation_info = $this->QuotationsModel->quotation_info($quotation_id);
		if(!$quotation_info)
		return redirect()->to(base_url().'/quotations'); 
		
		$customer_info  = $this->QuotationsModel->customer_info($quotation_info['customer_id']);
		
		$data['customer_info']     =$customer_info;	
        $data['quotation_info']     =$quotation_info;
        $data['quotation_id']       = $quotation_id;	
    	$data['message_output']    = $this->message_output;
    	$data['get_quotation_items']= $this->QuotationsModel->get_quotation_items($quotation_id);
	    return view('quotations/print',$data);		
    }
    
 public function detail($quotation_id)
    {
     	if(!$quotation_id)
		 return redirect()->to(base_url().'/quotations'); 
			
    	$quotation_info = $this->QuotationsModel->quotation_info($quotation_id);
		if(!$quotation_info)
		return redirect()->to(base_url().'/quotations'); 
		
		$customer_info  = $this->QuotationsModel->customer_info($quotation_info['customer_id']);
		
		$data['customer_info']     =$customer_info;	
        $data['quotation_info']     =$quotation_info;
        $data['quotation_id']       = $quotation_id;	
    	$data['message_output']    = $this->message_output;
    	$data['get_quotation_items']= $this->QuotationsModel->get_quotation_items($quotation_id);
	    return view('quotations/detail',$data);		
    }

	public function add()
    {
        
   	    	if($this->request->getMethod() == 'post'){
			$customer_id             = $this->request->getVar('customer_id');
		    $customer_location       = $this->request->getVar('customer_location');
	        $company_details         = $this->request->getVar('company_details');
	        $notes                   = $this->request->getVar('notes');
	        $service_item            = $this->request->getVar('service_item');
	        $hst_payment             = $this->request->getVar('hst_payment');
	        $deposit_payment         = $this->request->getVar('deposit_payment');
	        $service_cost            = $this->request->getVar('service_cost');
			$service_desc            = $this->request->getVar('service_desc');
			$rules = [				
				'customer_id' => [
					'label'  => 'Customer',
					'rules'  => 'required',
					'rules'  => "required",
					'errors' => [
						'required' => 'Please choose customer',
					   ]
					   ],				
				  'customer_location' => [
					'label'  => 'Location',
					'rules'  => "required",
					'errors' => [
						'required' => 'Please enter location',
					], 
				   ]
			    
			];
			
            if(!$this->validate($rules)){
            $data['validation'] = $this->validator->listErrors();
            }
            elseif(count($service_cost)=='0'){
             $data['validation'] = 'Service cost is required';   
                
            }
            else{
                if(!$hst_payment)
                 $hst_payment=0;
                if(!$deposit_payment)
                $deposit_payment =0;
                 
                 
				 $subtotal =0;
				 if($service_item){
				     foreach($service_item as $srkey =>$row){
				         if(isset($service_cost[$srkey]) && $service_cost[$srkey]>'0')
				              $subtotal = $subtotal+$service_cost[$srkey];
				        else
				              $subtotal = $subtotal+0;
				      }
				 }
				 
			
				$tax_amount = (($subtotal*$hst_payment)/100);
				
				$net_total   = ($subtotal+$tax_amount)-$deposit_payment;
		    	$insert_data = [
				          'customer_id'         => $customer_id,
				          'customer_location'   => $customer_location,
				          'company_detail'      => $company_details,
				          'notes'               => $notes,
				          'discount'            => "0",
				          "deposit"             => $deposit_payment,
				          'tax'                 => $hst_payment,
				          'tax_amount'          => $tax_amount,
				          'subtotal'            => $subtotal, 
				          'net_total'           => $net_total,
						  'submitted_by'         => $this->session->get('s_user_id'), 
						  'entry_time'          => date('Y-m-d H:i:s')			
					      ];				
				
					$quotation_id = $this->QuotationsModel->add_quotation($insert_data);
					$final_amount =0;
					if($service_item){
					    foreach($service_item as $jbbkey => $jbrow){
					        
					        if($jbrow!=''){
					            $itemsinfo  =  explode("||",$jbrow);
					            $item_id    =  $itemsinfo[0];
					            $item_price =   $itemsinfo[1];
					            $service_desc_val = $service_desc[$jbbkey];
					            $final_amount = $final_amount+$item_price;
					        
					            $get_iten_info = $this->QuotationsModel->get_service_item_info($item_id);
					           if($get_iten_info)
					              $item_name = $get_iten_info['pay_head_name'];
					           else
					             $item_name = 'N/a';
					         $jobinsert = array("quotation_id"=>$quotation_id,"item_id"=>$item_id,"item_name"=>$item_name,"item_desc"=>$service_desc_val,"item_cost"=>$item_price,"entry_time"=>date('Y-m-d H:i:s'));
					         $this->QuotationsModel->add_qoutation_items($jobinsert);
					      }
					    }
					    
					 }
					 
					
					
					$this->message_output->set_success('Record Inserted successfully');
					return redirect()->to(base_url().'/quotations'); 
					die;				
			     }			
		     }
		   
		 	$data['message_output']    = $this->message_output;	
		 	$data['CustomerDropdown']  = $this->QuotationsModel->AllCustomersDropdown();	
		 	$data['PaymentHeads']     = $this->QuotationsModel->payment_heads();
		    
		    return view('quotations/add',$data);		
    }
	
	public function edit($quotation_id)
    {
		if(!$quotation_id)
			return redirect()->to(base_url().'/quotations'); 
		
	    if($this->request->getMethod() == 'post'){
			$customer_id             = $this->request->getVar('customer_id');
		    $customer_location       = $this->request->getVar('customer_location');
	        $company_details         = $this->request->getVar('company_details');
	        $notes                   = $this->request->getVar('notes');
	        $service_item            = $this->request->getVar('service_item');
	        $hst_payment             = $this->request->getVar('hst_payment');
	        $deposit_payment         = $this->request->getVar('deposit_payment');
	        $service_cost            = $this->request->getVar('service_cost');
			$service_desc            = $this->request->getVar('service_desc');
		
			$rules = [				
				'customer_id' => [
					'label'  => 'Customer',
					'rules'  => 'required',
					'rules'  => "required",
					'errors' => [
						'required' => 'Please choose customer',
					   ]
					   ],				
				  'customer_location' => [
					'label'  => 'Location',
					'rules'  => "required",
					'errors' => [
						'required' => 'Please enter location',
					], 
				   ]			    
			   ];			
            if(!$this->validate($rules)){
            $data['validation'] = $this->validator->listErrors();
            }else{
                
				 $subtotal =0;
				 if($service_item){
				     foreach($service_item as $srkey =>$row){
				        if($row!='') 
				        $subtotal = $subtotal+$service_cost[$srkey];
				      }
				 }
				$tax_amount  = (($subtotal*$hst_payment)/100);
			
				$net_total   = ($subtotal+$tax_amount)-$deposit_payment;
		    	$update_data = [
				          'customer_location'   => $customer_location,
				          'company_detail'      => $company_details,
				          'notes'               => $notes,
				          'discount'            => "0",
				          "deposit"             => $deposit_payment,
				          'tax'                 => $hst_payment,
				          'tax_amount'          => $tax_amount,
				          'subtotal'            => $subtotal, 
				          'net_total'           => $net_total
					      ];				
					      
			
				
					$this->QuotationsModel->update_quotation($quotation_id,$update_data);
					$final_amount =0;
					if($service_item){
					    	$this->QuotationsModel->remove_quotation_items($quotation_id);
					    foreach($service_item as $jbbkey => $jbrow){
					        
					        if($jbrow!=''){
					            $itemsinfo  =  explode("||",$jbrow);
					            $item_id    =  $itemsinfo[0];
					             $item_price  = $service_cost[$jbbkey];
					            $final_amount = $final_amount+$item_price;
					            $service_desc_val =  $service_desc[$jbbkey];
					           
					            $get_iten_info = $this->QuotationsModel->get_service_item_info($item_id);
					           if($get_iten_info)
					              $item_name = $get_iten_info['pay_head_name'];
					           else
					             $item_name = 'N/a';
							 
					         $jobinsert = array("quotation_id"=>$quotation_id,"item_id"=>$item_id,"item_desc"=>$service_desc_val,"item_name"=>$item_name,"item_cost"=>$item_price,"entry_time"=>date('Y-m-d H:i:s'));
					         $this->QuotationsModel->add_qoutation_items($jobinsert);
					      }
					    }
					    
					 }
					
					$this->message_output->set_success('Record Updated successfully');
					return redirect()->to(base_url().'/quotations'); 
					die;				
			     }			
		     }
		   
		$data['message_output']     = $this->message_output;
        $data['quotation_id']       = $quotation_id;		
		$data['CustomerDropdown']   = $this->QuotationsModel->AllCustomersDropdown();	
		$data['PaymentHeads']       = $this->QuotationsModel->payment_heads();		
		$data['validation']         = '';
		$data['quotation_info']     = $this->QuotationsModel->quotation_info($quotation_id);			
		$data['get_quotation_items']= $this->QuotationsModel->get_quotation_items($quotation_id);		
	    return view('quotations/edit',$data);		
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
