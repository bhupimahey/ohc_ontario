<?php
namespace App\Controllers;
use App\Models\CustomersModel;
use App\Controllers\BaseController;
use App\Libraries\auth_session;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class Customers extends BaseController
{
	
	function __construct()
    {  
	    helper(['form', 'url']);
		$this->CustomersModel   = new CustomersModel();		
		$this->auth_session   = new auth_session();
	    $this->auth_session->admin_restrict();
	    $this->auth_session->role_restrict('S');
    }
	
  public function index()
    {
		$data['message_output']    = $this->message_output;	
	    return view('customers/view',$data);		
    }

	function ajax_customers_view()
	 {
		echo $this->CustomersModel->ajax_customers_list();		
	 }
 

 function export_customers()
	 {
		$customers_data =  $this->CustomersModel->ajax_export_list(); 		
	    $spreadsheet = new Spreadsheet();      
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle("Customer Details");

        $sheet->setCellValue('A1', 'CUSTOMER NAME');
        $sheet->setCellValue('B1', 'CUSTOMER PHONE');
        $sheet->setCellValue('C1', 'CUSTOMER EMAIL');
		$sheet->setCellValue('D1', 'CUSTOMER ADDRESS');
		$sheet->setCellValue('E1', 'ENTRY ON');
        $counter = 2;	
		$spreadsheet->getActiveSheet()->getStyle("A1")->getFont()->setBold( true );
		$spreadsheet->getActiveSheet()->getStyle("B1")->getFont()->setBold( true );
		$spreadsheet->getActiveSheet()->getStyle("C1")->getFont()->setBold( true );
		$spreadsheet->getActiveSheet()->getStyle("D1")->getFont()->setBold( true );
		$spreadsheet->getActiveSheet()->getStyle("E1")->getFont()->setBold( true );
		foreach (range('A', $spreadsheet->getActiveSheet()->getHighestDataColumn()) as $col) {
				$spreadsheet->getActiveSheet()
						->getColumnDimension($col)
						->setAutoSize(true);
			} 
			
        foreach ($customers_data as $rows){
            $sheet->setCellValue('A' . $counter,$rows["full_name"]);
            $sheet->setCellValue('B' . $counter, $rows["phone_no"]);
            $sheet->setCellValue('C' . $counter, $rows["email_id"]);
			$sheet->setCellValue('D' . $counter, $rows["address"]);
			$sheet->setCellValue('E' . $counter, $rows["entry_on"]);
            $counter++;
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save(WRITEPATH.'Customer Details.xlsx');
        return $this->response->download(WRITEPATH.'Customer Details.xlsx', null);

	 }
	 
 public function ajax_save_customer()
    {
   		if($this->request->getMethod() == 'post'){
		
	        $customerMobile              = $this->request->getVar('customerMobile');
	        $customerFlname              = $this->request->getVar('customerFlname');
	        $customerEmail               = $this->request->getVar('customerEmail');
	        $customerAddress             = $this->request->getVar('customerAddress');
	        
			$rules = [				
				'customerFlname' => [
					'label'  => 'Full Name',
					'rules'  => "required",
					'errors' => [
						'required' => 'Please enter full name',
					], 
				   ],
				'customerMobile' => [
					'label'  => 'Mobile',
					'rules'  => "required",
					'errors' => [
						'required' => 'Please enter mobile',
					], 
				   ],
				'customerAddress' => [
					'label'  => 'Address',
					'rules'  => "required",
					'errors' => [
						'required' => 'Please enter address',
					], 
				   ]	
			    
			];
			
            if(!$this->validate($rules)){
              echo "0|||".$this->validator->listErrors().'|||0';
			  
            }else{			
				$insert_data = [
				          'full_name'          => $customerFlname,
				          'email_id'           => $customerEmail,
				          'address'            => $customerAddress,
				          'phone_no'           => $customerMobile,
				          'ip_address'         => $_SERVER['REMOTE_ADDR'],
				          'created_by'         => $this->session->get('s_user_id'),
						  'entry_time'         => date('Y-m-d H:i:s')			
					      ];				
						
					$customer_id = $this->CustomersModel->add_customer($insert_data);
					if($customer_id){					
					if(isset($_FILES['customerPhoto']['name']) && ($_FILES['customerPhoto']['name']!='')){
						if (!file_exists('public/documents/customers/')) {
							mkdir('public/documents/customers', 0777, true);
						 }
						 if ($_FILES['customerPhoto']['name'] != "") {
							$updated_image_data['photo_path'] = md5(rand(10000000, 20000000)) . '.jpg';
							move_uploaded_file($_FILES['customerPhoto']['tmp_name'], 'public/documents/customers/' . $updated_image_data['photo_path']);
							$this->CustomersModel->update_customer_image($updated_image_data,$teacher_id);
						 }			
						}					
					$CustomerDropdown = $this->CustomersModel->AllCustomersDropdown();	
					 echo "1|||".form_dropdown("customer_id",$CustomerDropdown,$customer_id,'class="form-control select2" id="customer_id" required').'|||'.$customerAddress;
					}
					else{
					echo "0|||0";					    
					}
					die;				
			     }			
		     }
		 
    }
  
	public function add()
    {
        
   		if($this->request->getMethod() == 'post'){
		
	        $customerMobile              = $this->request->getVar('customerMobile');
	        $customerFlname              = $this->request->getVar('customerFlname');
	        $customerEmail               = $this->request->getVar('customerEmail');
	        $customerAddress             = $this->request->getVar('customerAddress');
	        
			$rules = [				
				'customerFlname' => [
					'label'  => 'Full Name',
					'rules'  => "required",
					'errors' => [
						'required' => 'Please enter full name',
					], 
				   ],
				'customerMobile' => [
					'label'  => 'Mobile',
					'rules'  => "required",
					'errors' => [
						'required' => 'Please enter mobile',
					], 
				   ],
				'customerAddress' => [
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
			
				$insert_data = [
				          'full_name'          => $customerFlname,
				          'email_id'           => $customerEmail,
				          'address'            => $customerAddress,
				          'phone_no'           => $customerMobile,
				          'ip_address'         => $_SERVER['REMOTE_ADDR'],
				          'created_by'         => $this->session->get('s_user_id'),
						  'entry_time'         => date('Y-m-d H:i:s')			
					      ];				
						
					$customer_id = $this->CustomersModel->add_customer($insert_data);
					if($customer_id){
					
					if(isset($_FILES['customerPhoto']['name']) && ($_FILES['customerPhoto']['name']!='')){
						if (!file_exists('public/documents/customers/')) {
							mkdir('public/documents/customers', 0777, true);
						 }
						 if ($_FILES['customerPhoto']['name'] != "") {
							$updated_image_data['photo_path'] = md5(rand(10000000, 20000000)) . '.jpg';
							move_uploaded_file($_FILES['customerPhoto']['tmp_name'], 'public/documents/customers/' . $updated_image_data['photo_path']);
							$this->CustomersModel->update_customer_image($updated_image_data,$teacher_id);
						 }			
						}	
						
						
					$this->message_output->set_success('Record Inserted successfully');
					return redirect()->to(base_url().'/customers'); 
					}
					else{
					 $this->message_output->set_success('Record already exists');
					return redirect()->to(base_url().'/customers');    
					    
					}
					die;				
			     }			
		     }
		 
		 return view('customers/add');		
    }
	
	public function edit($customer_id)
    {
		if(!$customer_id)
			return redirect()->to(base_url().'/customers'); 
		
		if($this->request->getMethod() == 'post'){
		
	        $customerMobile          = $this->request->getVar('customerMobile');
	        $customerFlname         = $this->request->getVar('customerFlname');
	        $customerEmail          = $this->request->getVar('customerEmail');
	        $customerAddress        = $this->request->getVar('customerAddress');
	        
			$rules = [				
				'customerFlname' => [
					'label'  => 'Full Name',
					'rules'  => "required",
					'errors' => [
						'required' => 'Please enter full name',
					], 
				   ],
				'customerMobile' => [
					'label'  => 'Mobile',
					'rules'  => "required",
					'errors' => [
						'required' => 'Please enter mobile',
					], 
				   ],
				'customerAddress' => [
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
               
             
				if(isset($_FILES['customerPhoto']['name']) && ($_FILES['customerPhoto']['name']!='')){
                    
                    if (!file_exists('public/documents/customers/')) {
    					mkdir('public/documents/customers/', 0777, true);
    			     }
    			     
    		   	     if ($_FILES['customerPhoto']['name'] != "") {
    					$updated_image_data['photo_path'] = md5(rand(10000000, 20000000)) . '.jpg';
    					move_uploaded_file($_FILES['customerPhoto']['tmp_name'], 'public/documents/customers/' . $updated_image_data['photo_path']);
    					
    					$this->CustomersModel->update_customer_image($updated_image_data,$customer_id);
    			        }
                  }
                
                    $updated_data = [
						  'full_name'          => $customerFlname,
				          'email_id'           => $customerEmail,
				          'address'            => $customerAddress,
				          'phone_no'           => $customerMobile
					      ];	
					      
				    	$template=$this->CustomersModel->update_customer($updated_data,$customer_id);   
                 
			
					$this->message_output->set_success('Record Updated successfully');
					return redirect()->to(base_url().'/customers'); 
					die;				
			     }			
		     }		
		
		$data['validation']     = '';
		$data['customer_info']  = $this->CustomersModel->customer_info($customer_id);
		$data['customer_id']    = $customer_id;	
		
	    return view('customers/edit',$data);		
    }
    
   public function deleteRecord($id)
	{	 
	    $DeleteRec=$this->CustomersModel->deleteRecord($id);
		if($DeleteRec){
			$this->message_output->set_success('Record Deleted successfully');
		} else {
			$this->message_output->set_error( 'Please try again! Error while deleting record.');
		}
		return redirect()->to(base_url().'/customers'); 	
	}    
	
}
