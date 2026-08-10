<?php
namespace App\Controllers;
use App\Models\VendorsModel;
use App\Controllers\BaseController;
use App\Libraries\auth_session;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class Vendors extends BaseController
{
	
	function __construct()
    {  
	    helper(['form', 'url']);
		$this->VendorsModel   = new VendorsModel();		
		$this->auth_session   = new auth_session();
	    $this->auth_session->admin_restrict();
	    $this->auth_session->role_restrict('S');
    }
	
  public function index()
    {
		$data['message_output']    = $this->message_output;	
	    return view('vendors/view',$data);		
    }

	function ajax_vendors_view()
	 {
		echo $this->VendorsModel->ajax_vendors_list();		
	 }
 

 function export_vendors()
	 {
		$vendors_data =  $this->VendorsModel->ajax_export_list(); 		
	    $spreadsheet = new Spreadsheet();      
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle("Customer Details");

        $sheet->setCellValue('A1', 'VENDOR NAME');
        $sheet->setCellValue('B1', 'VENDOR PHONE');
        $sheet->setCellValue('C1', 'VENDOR EMAIL');
		$sheet->setCellValue('D1', 'VENDOR ADDRESS');
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
			
        foreach ($vendors_data as $rows){
            $sheet->setCellValue('A' . $counter,$rows["full_name"]);
            $sheet->setCellValue('B' . $counter, $rows["phone_no"]);
            $sheet->setCellValue('C' . $counter, $rows["email_id"]);
			$sheet->setCellValue('D' . $counter, $rows["address"]);
			$sheet->setCellValue('E' . $counter, $rows["entry_on"]);
            $counter++;
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save(WRITEPATH.'Vendor Details.xlsx');
        return $this->response->download(WRITEPATH.'Vendor Details.xlsx', null);

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
						
					$customer_id = $this->VendorsModel->add_customer($insert_data);
					if($customer_id){					
					if(isset($_FILES['customerPhoto']['name']) && ($_FILES['customerPhoto']['name']!='')){
						if (!file_exists('public/documents/vendors/')) {
							mkdir('public/documents/vendors', 0777, true);
						 }
						 if ($_FILES['customerPhoto']['name'] != "") {
							$updated_image_data['photo_path'] = md5(rand(10000000, 20000000)) . '.jpg';
							move_uploaded_file($_FILES['customerPhoto']['tmp_name'], 'public/documents/vendors/' . $updated_image_data['photo_path']);
							$this->VendorsModel->update_customer_image($updated_image_data,$teacher_id);
						 }			
						}					
					$CustomerDropdown = $this->VendorsModel->AllvendorsDropdown();	
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
		
	        $customerMobile              = $this->request->getVar('vendorMobile');
	        $customerFlname              = $this->request->getVar('vendorFlname');
	        $customerEmail               = $this->request->getVar('vendorEmail');
	        $customerAddress             = $this->request->getVar('vendorAddress');
	        
			$rules = [				
				'vendorFlname' => [
					'label'  => 'Full Name',
					'rules'  => "required",
					'errors' => [
						'required' => 'Please enter full name',
					], 
				   ],
				'vendorMobile' => [
					'label'  => 'Mobile',
					'rules'  => "required",
					'errors' => [
						'required' => 'Please enter mobile',
					], 
				   ],
				'vendorAddress' => [
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
						
					$vendor_id = $this->VendorsModel->add_vendor($insert_data);
					if($vendor_id >0){
					
					if(isset($_FILES['vendorPhoto']['name']) && ($_FILES['vendorPhoto']['name']!='')){
						if (!file_exists('public/documents/vendors/')) {
							mkdir('public/documents/vendors', 0777, true);
						 }
						 if ($_FILES['vendorPhoto']['name'] != "") {
							$updated_image_data['photo_path'] = md5(rand(10000000, 20000000)) . '.jpg';
							move_uploaded_file($_FILES['vendorPhoto']['tmp_name'], 'public/documents/vendors/' . $updated_image_data['photo_path']);
							$this->VendorsModel->update_vendor_image($updated_image_data,$vendor_id);
						 }			
						}	
						
						
					$this->message_output->set_success('Record Inserted successfully');
					return redirect()->to(base_url().'/vendors'); 
					}
					else{
					 $this->message_output->set_success('Record already exists');
					return redirect()->to(base_url().'/vendors');    
					    
					}
					die;				
			     }			
		     }
		 
		 return view('vendors/add');		
    }
	
	public function edit($vendor_id)
    {
		if(!$vendor_id)
			return redirect()->to(base_url().'/vendors'); 
		
		if($this->request->getMethod() == 'post'){
		
	        $customerMobile          = $this->request->getVar('vendorMobile');
	        $customerFlname         = $this->request->getVar('vendorFlname');
	        $customerEmail          = $this->request->getVar('vendorEmail');
	        $customerAddress        = $this->request->getVar('vendorAddress');
	        
			$rules = [				
				'vendorFlname' => [
					'label'  => 'Full Name',
					'rules'  => "required",
					'errors' => [
						'required' => 'Please enter full name',
					], 
				   ],
				'vendorMobile' => [
					'label'  => 'Mobile',
					'rules'  => "required",
					'errors' => [
						'required' => 'Please enter mobile',
					], 
				   ],
				'vendorAddress' => [
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
               
             
				if(isset($_FILES['vendorPhoto']['name']) && ($_FILES['vendorPhoto']['name']!='')){
                    
                    if (!file_exists('public/documents/vendors/')) {
    					mkdir('public/documents/vendors/', 0777, true);
    			     }
    			     
    		   	     if ($_FILES['vendorPhoto']['name'] != "") {
    					$updated_image_data['photo_path'] = md5(rand(10000000, 20000000)) . '.jpg';
    					move_uploaded_file($_FILES['vendorPhoto']['tmp_name'], 'public/documents/vendors/' . $updated_image_data['photo_path']);
    					
    					$this->VendorsModel->update_vendor_image($updated_image_data,$vendor_id);
    			        }
                  }
                
                    $updated_data = [
						  'full_name'          => $customerFlname,
				          'email_id'           => $customerEmail,
				          'address'            => $customerAddress,
				          'phone_no'           => $customerMobile
					      ];	
					      
				    	$template=$this->VendorsModel->update_vendor($updated_data,$vendor_id);   
                 
			
					$this->message_output->set_success('Record Updated successfully');
					return redirect()->to(base_url().'/vendors'); 
					die;				
			     }			
		     }		
		
		$data['validation']     = '';
		$data['vendor_info']  = $this->VendorsModel->vendor_info($vendor_id);
		$data['vendor_id']    = $vendor_id;	
		
	    return view('vendors/edit',$data);		
    }
    
   public function deleteRecord($id)
	{	 
	    $DeleteRec=$this->VendorsModel->deleteRecord($id);
		if($DeleteRec){
			$this->message_output->set_success('Record Deleted successfully');
		} else {
			$this->message_output->set_error( 'Please try again! Error while deleting record.');
		}
		return redirect()->to(base_url().'/vendors'); 	
	}    
	
}
