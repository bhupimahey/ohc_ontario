<?php
namespace App\Controllers;
use App\Models\PurorderModel;
use App\Controllers\BaseController;
use App\Libraries\auth_session;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class Purchase_orders extends BaseController
{
	
	function __construct()
    {  
	    helper(['form', 'url','text']);
		$this->PurorderModel   = new PurorderModel();		
		$this->auth_session   = new auth_session();
	    $this->auth_session->admin_restrict();
	    $this->auth_session->role_restrict('S');
    }

  public function index()
    {
        $data['message_output']       = $this->message_output;		
		$data['all_vendors']          = $this->PurorderModel->AllVendorsDropdown();
		$data['JobsDropdown']         = $this->PurorderModel->AllJobsDropdown();
	    return view('purchase_orders/view',$data);		
    }
 

 function export_purchase_orders()
	 {
	    $po_data =  $this->PurorderModel->export_purchaseorders_list();	
	    
	    $spreadsheet = new Spreadsheet();      
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle("Purchase Orders");

        $sheet->setCellValue('A1', '#ORDERNO');
        $sheet->setCellValue('B1', 'VENDOR NAME');
        $sheet->setCellValue('C1', 'VENDOR ADDRESS');
        $sheet->setCellValue('D1', 'TOTAL');
        $sheet->setCellValue('E1', 'SHIPPING');
        $sheet->setCellValue('F1', 'DISCOUNT');
		$sheet->setCellValue('G1', 'REMARKS');		
		$sheet->setCellValue('H1', 'ORDER DATE');
		
        $counter = 2;	
		$spreadsheet->getActiveSheet()->getStyle("A1:E1")->getFont()->setBold( true );
		foreach (range('A', $spreadsheet->getActiveSheet()->getHighestDataColumn()) as $col) {
				$spreadsheet->getActiveSheet()
						->getColumnDimension($col)
						->setAutoSize(true);
			} 
			
        foreach ($po_data as $rows){
            $sheet->setCellValue('A' . $counter,$rows["ponumber"]);
            $sheet->setCellValue('B' . $counter, $rows["vendor_name"]);
            $sheet->setCellValue('C' . $counter, $rows["address"]);
            $sheet->setCellValue('D' . $counter, $rows["total"]);
			$sheet->setCellValue('E' . $counter, $rows["shipping_amount"]);
			$sheet->setCellValue('F' . $counter,$rows["discount_amount"]);
            $sheet->setCellValue('G' . $counter, $rows["remarks"]);
            $sheet->setCellValue('H' . $counter, $rows["po_date"]);
		
            $counter++; 
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save(WRITEPATH.'Purchase Orders.xlsx');
        return $this->response->download(WRITEPATH.'Purchase Orders.xlsx', null);	 

	 }
	 
	function ajax_purchaseorders_view()
	 {
		echo $this->PurorderModel->ajax_po_list();		
	 }
 
 
	public function add($jobid=NULL)
    {
        if($jobid==NULL){
        return redirect()->to(base_url().'/purchase_orders'); 
					die;
        }
   	    	if($this->request->getMethod() == 'post'){
		
			$vendor_id      = $this->request->getVar('vendor_id');
		    $podate         = $this->request->getVar('podate');
	        $ordernumber    = $this->request->getVar('ordernumber');
	        $our_remarks    = $this->request->getVar('our_remarks');	
			$items          = $this->request->getVar('item');
	        $itemdesc       = $this->request->getVar('itemdesc');
	        $itemqty        = $this->request->getVar('itemqty');
	        $itemprice      = $this->request->getVar('itemprice');
			$hst_applied    = $this->request->getVar('hst_applied');
			
			$discount_payment      = $this->request->getVar('discount_payment');
			$shipping_payment      = $this->request->getVar('shipping_payment');
			$our_terms             = $this->request->getVar('our_terms');
			
			$rules = [				
				'vendor_id' => [
					'label'  => 'Vendor',
					'rules'  => 'required',
					'rules'  => "required",
					'errors' => [
						'required' => 'Please choose vendor',
					   ]
					   ],				
				  'podate' => [
					'label'  => 'Date',
					'rules'  => "required",
					'errors' => [
						'required' => 'Please enter order date',
					], 
				   ],
		      	'ordernumber' => [
					'label'  => 'Order No',
					'rules'  => "required",
					'errors' => [
						'required' => 'Please enter order number',
					], 
				   ],			   	
			    
			];
			
            if(!$this->validate($rules)){
            $data['validation'] = $this->validator->listErrors();
            }else{
           		
           		
           	if($podate!='' && $podate!='0000-00-00')	
           	  $podate =date('Y-m-d',strtotime($podate));
           	 else
           	 $podate ='0000-00-00';
			$insert_data = [
				          'ponumber'       => $ordernumber,
				          'vendor_id'      => $vendor_id,
				          'jobid'          => $jobid,
				          'po_date'        => $podate,
						  'remarks'        => $our_remarks,	
						  'ip_address'     => get_client_ip(), 
						  'entry_time'     => date('Y-m-d H:i:s'),
						  'tax_applied'    => $hst_applied,
	                      'termsinfo'      => $our_terms,
	                      'discount_amount'=> $discount_payment,
	                      'shipping_amount'=> $shipping_payment
					      ];				
					
	        	
					$po_id = $this->PurorderModel->add_po_order($insert_data);
					if($items){
					    foreach($items as $itmkey => $item_name){
					        
					        if($item_name!=''){
					            $itemdesc     =   $itemdesc[$itmkey];
								$itemqty      =   $itemqty[$itmkey];
								$itemprice    =   $itemprice[$itmkey];
					            $item_total   =   $itemqty*$itemprice;
					            $jobinsert = array("poid"=>$po_id,"item_name"=>$item_name,"item_desc"=>$itemdesc,"item_qty"=>$itemqty,"unit_price"=>$itemprice,
							                       "item_total"=>$item_total,"ip_address"=>get_client_ip(),"entry_time"=>date('Y-m-d H:i:s'));
					            $this->PurorderModel->add_po_items($jobinsert);
					      }
					    }
					    
					 }
					 
					 if(isset($_FILES['bill_photo']['name']) && ($_FILES['bill_photo']['name']!='')){
						if (!file_exists('public/documents/users/')) {
							mkdir('public/documents/bills', 0777, true);
						 }
						 if ($_FILES['bill_photo']['name'] != "") {
						     
						     $ext = pathinfo($_FILES['bill_photo']['name'], PATHINFO_EXTENSION);
							$updated_image_data['bill_photo'] = md5(rand(10000000, 20000000)) . '.'.$ext;
							move_uploaded_file($_FILES['bill_photo']['tmp_name'], 'public/documents/bills/' . $updated_image_data['bill_photo']);
							$this->PurorderModel->update_po_order($po_id,$updated_image_data);
						 }			
						}
						
					 
					$this->message_output->set_success('Record Inserted successfully');
					return redirect()->to(base_url().'/purchase_orders'); 
					die;				
			     }			
		     }
		    $data['jobid']            = $jobid;
		 	$data['message_output']   = $this->message_output;	
		 	$data['VendorsDropdown']  = $this->PurorderModel->AllVendorsDropdown();	
		 	
		 	
		
		 return view('purchase_orders/add',$data);		
    }
	
	public function edit($po_id)
    {
		if(!$po_id)
			return redirect()->to(base_url().'/purchase_orders'); 
		
	     if($this->request->getMethod() == 'post'){
	    
			$vendor_id      = $this->request->getVar('vendor_id');
		    $podate         = $this->request->getVar('podate');
	        $ordernumber    = $this->request->getVar('ordernumber');
	        $our_remarks    = $this->request->getVar('our_remarks');	
			$items          = $this->request->getVar('item');
	        $itemdesc       = $this->request->getVar('itemdesc');
	        $itemqty        = $this->request->getVar('itemqty');
	        $itemprice      = $this->request->getVar('itemprice');
			$hst_applied    = $this->request->getVar('hst_applied');
			
			$discount_payment      = $this->request->getVar('discount_payment');
			$shipping_payment      = $this->request->getVar('shipping_payment');
			$our_terms             = $this->request->getVar('our_terms');
			
			$rules = [				
				'vendor_id' => [
					'label'  => 'Vendor',
					'rules'  => 'required',
					'rules'  => "required",
					'errors' => [
						'required' => 'Please choose vendor',
					   ]
					   ],				
				  'podate' => [
					'label'  => 'Date',
					'rules'  => "required",
					'errors' => [
						'required' => 'Please enter order date',
					], 
				   ],
		      	'ordernumber' => [
					'label'  => 'Order No',
					'rules'  => "required",
					'errors' => [
						'required' => 'Please enter order number',
					], 
				   ],			   	
			    
			];
			
            if(!$this->validate($rules)){
            $data['validation'] = $this->validator->listErrors();
            }else{
			
			if($podate!='' && $podate!='0000-00-00')	
           	  $podate =date('Y-m-d',strtotime($podate));
           	 else
           	 $podate ='0000-00-00';	
			$update_data = [
				          'ponumber'       => $ordernumber,
				          'vendor_id'      => $vendor_id,
				          'po_date'        => $podate,
						  'remarks'        => $our_remarks,	
						  'tax_applied'    => $hst_applied,
	                      'termsinfo'      => $our_terms,
	                      'discount_amount'=> $discount_payment,
	                      'shipping_amount'=> $shipping_payment
					      ];					
						
					$this->PurorderModel->update_po_order($po_id,$update_data);
					if($items){
					     $this->PurorderModel->delete_po_items($po_id);
					    foreach($items as $itmkey => $item_name){
					        
					        if($item_name!=''){
					            $sel_itemdesc     =   $itemdesc[$itmkey];
								$sel_itemqty      =   $itemqty[$itmkey];
								$sel_itemprice    =   $itemprice[$itmkey];
					            $sel_item_total   =   $sel_itemqty*$sel_itemprice;
					            $jobinsert    =   array("poid"=>$po_id,"item_name"=>$item_name,"item_desc"=>$sel_itemdesc,"item_qty"=>$sel_itemqty,"unit_price"=>$sel_itemprice,
							                       "item_total"=>$sel_item_total,"ip_address"=>get_client_ip(),"entry_time"=>date('Y-m-d H:i:s'));
							                       
							                       echo'<pre>';
							                       print_r($jobinsert);
					            $this->PurorderModel->add_po_items($jobinsert);
					           }
					       }
					    
					   }
				
					    if(isset($_FILES['bill_photo']['name']) && ($_FILES['bill_photo']['name']!='')){
						if (!file_exists('public/documents/users/')) {
							mkdir('public/documents/bills', 0777, true);
						 }
						 if ($_FILES['bill_photo']['name'] != "") {
						     
						     $ext = pathinfo($_FILES['bill_photo']['name'], PATHINFO_EXTENSION);
							$updated_image_data['bill_photo'] = md5(rand(10000000, 20000000)) . '.'.$ext;
							move_uploaded_file($_FILES['bill_photo']['tmp_name'], 'public/documents/bills/' . $updated_image_data['bill_photo']);
							$this->PurorderModel->update_po_order($po_id,$updated_image_data);
						 }			
						}
				
					$this->message_output->set_success('Record updated successfully');
					return redirect()->to(base_url().'/purchase_orders'); 
					die;				
			     }			
		     }
		
		$data['validation']        = '';
		$data['po_info']           = $this->PurorderModel->po_info($po_id);
		$data['po_id']             = $po_id;	
		$data['message_output']    = $this->message_output;	
		$data['VendorsDropdown']   = $this->PurorderModel->AllVendorsDropdown();
		$data['get_po_items']      = $this->PurorderModel->get_po_items($po_id);
		
	    return view('purchase_orders/edit',$data);		
    }
    
   
   public function deleteRecord($id)
	{
	   if(!$id)
			return redirect()->to(base_url().'/purchase_orders');  
			
	    $po_info = $this->PurorderModel->po_info($id);
	    
	    if(!$po_info)
			return redirect()->to(base_url().'/purchase_orders');  
	    
	        
	     $DeleteRec=$this->PurorderModel->deleteRecord($id);
		if($DeleteRec){
			$this->message_output->set_success('Record Deleted successfully');
		} else {
			$this->message_output->set_error( 'Please try again! Error while deleting record.');
		}
	
	   
		return redirect()->to(base_url().'/purchase_orders'); 	
	} 
	
}
