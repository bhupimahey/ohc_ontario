<?php
namespace App\Controllers;
use App\Models\JobsModel;
use App\Controllers\BaseController;
use App\Libraries\auth_session;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class Jobs extends BaseController
{
	
	function __construct()
    {  
	    helper(['form', 'url','text']);
		$this->JobsModel   = new JobsModel();		
		$this->auth_session   = new auth_session();
	    $this->auth_session->admin_restrict();
	    $this->auth_session->role_restrict('S');
    }

function subMins($ihour, $imin, $iminutes)
{
    $hours=floor($iminutes/60);
    $mins=$iminutes%60;
    
    $rhours=$ihour-$hours;
    $rmins=$imin-$mins;
    
    if($rmins<0)
    {
        $rmins=60+$rmins;
        $rhours=$rhours-1;
    }
    
    if($rhours<0)
    {
        $rhours=24+$rhours;
    }

    return $rhours.'.'.$rmins;
}

  public function index()
    {
        
     
		$data['message_output']       = $this->message_output;		
		$data['all_staff']            = $this->JobsModel->staff_dropdown();
		$data['all_customer']         = $this->JobsModel->AllCustomersDropdown();
	    return view('jobs/view',$data);		
    }
    
    public function deletePhoto($id)
{
    $photo =$this->JobsModel->photo_info($id);

    if (!$photo) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Photo not found'
        ]);
    }

    $jobId   = $photo['jobs_id'];
    $fileName = $photo['file_name'];

    $fullPath  = WRITEPATH . 'uploads/jobs/' . $jobId . '/full/' . $fileName;
    $thumbPath = WRITEPATH . 'uploads/jobs/' . $jobId . '/thumb/' . $fileName;

    // Delete files if exist
    if (file_exists($fullPath)) {
        unlink($fullPath);
    }

    if (file_exists($thumbPath)) {
        unlink($thumbPath);
    }

    // Delete DB record
    $this->JobsModel->delete_photo_info($id);

    return $this->response->setJSON([
        'status' => 'success'
    ]);
}
    
   public function uploadPhotos()
{
    helper('job_image');

    $jobId = $this->request->getPost('job_id');
    $files = $this->request->getFileMultiple('photos');

    if (!$files) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'No files received'
        ]);
    }

   
    if (isset($files)) {

        foreach ($files as $file) {


            if ($file->isValid()) {

                 $fileName = uploadOptimizedJobImage($file, $jobId);
                 
                if ($fileName) {
                    $this->JobsModel->InsertPhotos([
                        'jobs_id' => $jobId,
                        'file_name' => $fileName
                    ]);
                }
            }else{
                echo $file .' is not valid <br>';
            }
        }
    }

    return $this->response->setJSON(['status' => 'success']);
}
    
    public function photos($jobid=NULL)
    {
        if($jobid==NULL){
        return redirect()->to(base_url().'/jobs'); 
					die;
        }
     
		$data['message_output']       = $this->message_output;
		$data['photos']         = $this->JobsModel->AllJobPhotos($jobid);
	     $data['jobid']            = $jobid;
	    return view('jobs/photos',$data);		
    }
    
    
   	public function getcustomer_location($customer_id){
	  $customer_info =   $this->JobsModel->customer_info($customer_id);
	  if($customer_info)
	   echo $customer_info['address'];
	   else
	   echo '';
	}

 function export_jobs()
	 {
	    $jobs_data =  $this->JobsModel->export_jobs_list();		
	    $spreadsheet = new Spreadsheet();      
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle("Jobs Details");

        $sheet->setCellValue('A1', '#JobId');
        $sheet->setCellValue('B1', 'Customer Name');
        $sheet->setCellValue('C1', 'Work Details');
		$sheet->setCellValue('D1', 'Staff Name');		
		$sheet->setCellValue('E1', 'Payemnt Status');
        $sheet->setCellValue('F1', 'Job Status');
		$sheet->setCellValue('G1', 'Remarks');
		$sheet->setCellValue('H1', 'Entry Date');
		
        $counter = 2;	
		$spreadsheet->getActiveSheet()->getStyle("A1")->getFont()->setBold( true );
		$spreadsheet->getActiveSheet()->getStyle("B1")->getFont()->setBold( true );
		$spreadsheet->getActiveSheet()->getStyle("C1")->getFont()->setBold( true );
		$spreadsheet->getActiveSheet()->getStyle("D1")->getFont()->setBold( true );
		$spreadsheet->getActiveSheet()->getStyle("E1")->getFont()->setBold( true );
		$spreadsheet->getActiveSheet()->getStyle("F1")->getFont()->setBold( true );
		$spreadsheet->getActiveSheet()->getStyle("G1")->getFont()->setBold( true );
		$spreadsheet->getActiveSheet()->getStyle("H1")->getFont()->setBold( true );
		foreach (range('A', $spreadsheet->getActiveSheet()->getHighestDataColumn()) as $col) {
				$spreadsheet->getActiveSheet()
						->getColumnDimension($col)
						->setAutoSize(true);
			} 
			
        foreach ($jobs_data as $rows){
            $sheet->setCellValue('A' . $counter,$rows["jobid"]);
            $sheet->setCellValue('B' . $counter, $rows["job_name"]);
            $sheet->setCellValue('C' . $counter, $rows["job_title"]);
			$sheet->setCellValue('D' . $counter, $rows["staff_name"]);
			$sheet->setCellValue('E' . $counter,$rows["payment_status"]);
            $sheet->setCellValue('F' . $counter, $rows["job_status"]);
            $sheet->setCellValue('G' . $counter, $rows["remarks"]);
			$sheet->setCellValue('H' . $counter, $rows["entry_date"]);
            $counter++;
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save(WRITEPATH.'Jobs Details.xlsx');
        return $this->response->download(WRITEPATH.'Jobs Details.xlsx', null);	 

	 }
	 
	function ajax_jobs_view()
	 {
		echo $this->JobsModel->ajax_jobs_list();		
	 }
 
 
public function add()
{

if($this->request->getMethod() == 'post'){
            
            $customer_id       = $this->request->getVar('customer_id');
            $customer_location = $this->request->getVar('customer_location');
            $job_assigned_to   = $this->request->getVar('job_assigned_to');
            $work_details      = $this->request->getVar('work_details');
            $our_remarks       = $this->request->getVar('our_remarks');
            $job_items         = $this->request->getVar('job_item');
            $advanced_payment  = $this->request->getVar('advanced_payment');
            $job_cost          = $this->request->getVar('job_cost');
            $job_start         = $this->request->getVar('job_start');
            $job_completion    = $this->request->getVar('job_completion');
            $hst_applied       = $this->request->getVar('hst_applied');
            
            $rules = [
            
            'customer_id'=>[
            'label'=>'Customer',
            'rules'=>'required',
            'errors'=>['required'=>'Please choose customer']
            ],
            
            'job_assigned_to'=>[
            'label'=>'Job Assigned',
            'rules'=>'required',
            'errors'=>['required'=>'Please choose job assigned']
            ]
            
            ];
            
            if(!$this->validate($rules)){
            $data['validation'] = $this->validator->listErrors();
            }
            else{
            
            if($job_completion!='' && $job_completion!='0000-00-00')
            $show_job_completion = date('Y-m-d',strtotime($job_completion));
            else
            $show_job_completion ='0000-00-00';
            
            $insert_data = [
            
            'customer_id'=>$customer_id,
            'customer_location'=>$customer_location,
            'work_details'=>$work_details,
            'remarks'=>$our_remarks,
            'job_assigned_to'=>",".implode(",",$job_assigned_to).',',
            'job_status'=>'open',
            'submited_by'=>$this->session->get('s_user_id'),
            'entry_time'=>date('Y-m-d H:i:s'),
            'job_start_date'=>date('Y-m-d',strtotime($job_start)),
            'job_completion_date'=>$show_job_completion
            
            ];
            
            $job_id = $this->JobsModel->add_job($insert_data);
            
            $total_job_payment = 0;
            $total_job_cost = 0;
            
            if($job_items){
            
            foreach($job_items as $jbkey=>$jbrow){
            
            if($jbrow!=''){
            
            $itemsinfo = explode("||",$jbrow);
            $item_id   = $itemsinfo[0];
            $item_price= $job_cost[$jbkey];
            
            $get_iten_info = $this->JobsModel->get_job_item_info($item_id);
            
            if($get_iten_info)
            $item_name = $get_iten_info['pay_head_name'];
            else
            $item_name = 'N/a';
            
            if($item_name == "Total Job Payment"){
            $total_job_payment = $item_price;
            }else{
            $total_job_cost += $item_price;
            }
            
            $jobinsert=[
            
            "jobs_id"=>$job_id,
            "item_id"=>$item_id,
            "item_name"=>$item_name,
            "item_cost"=>$item_price,
            "entry_time"=>date('Y-m-d H:i:s')
            
            ];
            
            $this->JobsModel->add_job_items($jobinsert);
            
            }
            
            }
            
            }
            
            $job_profit = $total_job_payment - $total_job_cost;
            
            $final_amount = $total_job_payment;
            
            if($hst_applied){
            
            $hst_amount = ($total_job_payment*13)/100;
            $final_amount = $total_job_payment + $hst_amount;
            
            $update_data=[
            
            "hst_applied"=>1,
            "final_amount"=>$final_amount,
            "job_revenue"=>$total_job_payment,
            "job_cost"=>$total_job_cost,
            "job_profit"=>$job_profit
            
            ];
            
            }else{
            
            $update_data=[
            
            "hst_applied"=>0,
            "final_amount"=>$total_job_payment,
            "job_revenue"=>$total_job_payment,
            "job_cost"=>$total_job_cost,
            "job_profit"=>$job_profit
            
            ];
            
            }
            
            $this->JobsModel->update_job($job_id,$update_data);
            
            if($advanced_payment>0)
            $this->JobsModel->add_payment_history($job_id,$advanced_payment);
            else
            $this->JobsModel->add_payment_history($job_id,0);
            
            $this->JobsModel->update_job($job_id, [
                'advanced_payment' => (float)$advanced_payment
            ]);

            
            $this->message_output->set_success('Record Inserted successfully');
            return redirect()->to(base_url().'/jobs');
            die;
            
            }
            
            }
            
            $data['message_output']=$this->message_output;
            $data['CustomerDropdown']=$this->JobsModel->AllCustomersDropdown();
            $data['StaffDropdown']=$this->JobsModel->staff_dropdown();
            $data['PaymentHeads']=$this->JobsModel->payment_heads();
            $data['user_roles']=UserTypes();
            
            return view('jobs/add',$data);

}

public function edit($job_id)
{

if(!$job_id)
return redirect()->to(base_url().'/jobs');

if($this->request->getMethod() == 'post'){

$customer_id       = $this->request->getVar('customer_id');
$customer_location = $this->request->getVar('customer_location');
$job_assigned_to   = $this->request->getVar('job_assigned_to');
$work_details      = $this->request->getVar('work_details');
$our_remarks       = $this->request->getVar('our_remarks');
$job_items         = $this->request->getVar('job_item');
$job_cost          = $this->request->getVar('job_cost');
$job_start         = $this->request->getVar('job_start');
$job_completion    = $this->request->getVar('job_completion');
$hst_applied       = $this->request->getVar('hst_applied');
$advanced_payment  = $this->request->getVar('advanced_payment');

if($job_completion!='' && $job_completion!='0000-00-00')
$show_job_completion=date('Y-m-d',strtotime($job_completion));
else
$show_job_completion='0000-00-00';

$update_jbdata=[

'customer_id'=>$customer_id,
'customer_location'=>$customer_location,
'work_details'=>$work_details,
'remarks'=>$our_remarks,
'job_assigned_to'=>",".implode(",",$job_assigned_to).',',
'job_start_date'=>date('Y-m-d',strtotime($job_start)),
'job_completion_date'=>$show_job_completion

];

$this->JobsModel->update_job($job_id,$update_jbdata);

$this->JobsModel->delete_job_items($job_id);

$total_job_payment=0;
$total_job_cost=0;

if($job_items){

foreach($job_items as $jbkey=>$jbrow){

if($jbrow!=''){

$itemsinfo = explode("||",$jbrow);
$item_id   = $itemsinfo[0];
$item_price= $job_cost[$jbkey];

$get_iten_info=$this->JobsModel->get_job_item_info($item_id);

if($get_iten_info)
$item_name=$get_iten_info['pay_head_name'];
else
$item_name='N/a';

if($item_name=="Total Job Payment"){
$total_job_payment=$item_price;
}else{
$total_job_cost+=$item_price;
}

$jobinsert=[

"jobs_id"=>$job_id,
"item_id"=>$item_id,
"item_name"=>$item_name,
"item_cost"=>$item_price,
"entry_time"=>date('Y-m-d H:i:s')

];

$this->JobsModel->add_job_items($jobinsert);

}

}

}

$job_profit = $total_job_payment - $total_job_cost;

$final_amount=$total_job_payment;

if($hst_applied){

$hst_amount=($total_job_payment*13)/100;
$final_amount=$total_job_payment+$hst_amount;

$update_data=[

"hst_applied"=>1,
"final_amount"=>$final_amount,
"job_revenue"=>$total_job_payment,
"job_cost"=>$total_job_cost,
"job_profit"=>$job_profit

];

}else{

$update_data=[

"hst_applied"=>0,
"final_amount"=>$total_job_payment,
"job_revenue"=>$total_job_payment,
"job_cost"=>$total_job_cost,
"job_profit"=>$job_profit

];

}

$this->JobsModel->update_job($job_id,$update_data);

 
 $oldData = $this->JobsModel->job_info($job_id);
$old_advance = (float)($oldData['advanced_payment'] ?? 0);
$new_advance = (float)$advanced_payment;

$diff = $new_advance - $old_advance;

if($diff != 0){
    $this->JobsModel->add_payment_history($job_id, $diff);
}

$this->JobsModel->update_job($job_id, [
    'advanced_payment' => $new_advance
]);

$this->message_output->set_success('Record updated successfully');
return redirect()->to(base_url().'/jobs');

}

$data['validation']='';
$data['job_info']=$this->JobsModel->job_info($job_id);
$data['job_id']=$job_id;
$data['message_output']=$this->message_output;
$data['CustomerDropdown']=$this->JobsModel->AllCustomersDropdown();
$data['StaffDropdown']=$this->JobsModel->staff_dropdown();
$data['PaymentHeads']=$this->JobsModel->payment_heads();
$data['get_job_items']=$this->JobsModel->get_job_items($job_id);

return view('jobs/edit',$data);

}

    
   function mark_completed_job($submit=NULL){
       if($submit!='NULL'){
            $jobid               = $this->request->getVar('_token');
            $job_info            = $this->JobsModel->job_info($jobid ); 
            if(!$job_info) 	         return redirect()->to(base_url().'/jobs');
            
             $completed_on     =date('Y-m-d',strtotime($this->request->getVar('completed_on')));
            
            $this->JobsModel->update_job($jobid,array('job_completion_date'=>$completed_on,'job_status'=>'closed'));
            
             $this->message_output->set_success('Job completed successfully');
            		   	
		   return redirect()->to(base_url().'/jobs');
		   
       }
       
       
   }    
    
    
   function add_working_hours($submit=NULL){
        if($submit!='NULL'){
            
            
            $jobid               = $this->request->getVar('_token');
            $job_info            = $this->JobsModel->job_info($jobid ); 
            if(!$job_info) 	         return redirect()->to(base_url().'/jobs');  
            $working_hours       = $this->request->getVar('working_hours');
			$remarks             = $this->request->getVar('remarks');
			$working_date        =   $this->request->getVar('working_date');
		
		
			$job_assigned_to     = explode(",",rtrim(ltrim($job_info['job_assigned_to'],","),","));
			if($job_assigned_to){
					foreach($job_assigned_to as $staff_id){
						if($staff_id>0){
							$staff_info         = $this->JobsModel->staff_info($staff_id);
							$staff_hourly_rate  = $staff_info['hourly_rate'];
							
							if(isset($working_hours[$staff_id]) && $working_hours[$staff_id]!=''){
							$staff_work_hours   = $working_hours[$staff_id];
							
							$sel_working_date   = date('Y-m-d H:i:s',strtotime($working_date[$staff_id]));
							
							if($staff_work_hours >getenv('HoursDeduction')){
							$working_hours_info   = explode(".",$staff_work_hours);
							$working_hours_val    = $working_hours_info[0];
							if(isset($working_hours_info[1]))
							$working_minutes_val  = $working_hours_info[1];
							else
							$working_minutes_val  =0;
							//$final_working_hours  = $this->subMins($working_hours_val,$working_minutes_val,30); // minus 30 mins if job hours exceeded from 4 hours
						  }
						else{
							//$final_working_hours  =   $staff_work_hours;  
						  }
						  
                        $final_working_hours  =   $staff_work_hours; 
						$total_bal            =   $final_working_hours*$staff_hourly_rate;
						
			
						 $hours_data        = array("job_id"=>$jobid,"staff_id"=>$staff_id,"hours_worked"=>$staff_work_hours,"hourly_rate"=>$staff_hourly_rate,"remarks"=>$remarks,
						                             "entry_time"=>$sel_working_date,"added_by"=>$this->session->get('s_user_id'));
					     $this->JobsModel->add_workinghours($hours_data);
						}
						}
					}
			}
		  $this->message_output->set_success('Working hours added successfully');
          return redirect()->to(base_url().'/jobs');
        }
    } 
   
   public function updatestatus($job_id){
      	if(!$job_id)
			return redirect()->to(base_url().'/jobs');  
       
       $DeleteRec=$this->JobsModel->update_job($job_id,array('job_status'=>'closed'));
	   $this->message_output->set_success('Record closed successfully');
	   return redirect()->to(base_url().'/jobs'); 	
   }
   
   public function deleteRecord($id)
	{
	   if(!$id)
			return redirect()->to(base_url().'/jobs');  
			
	    $job_info = $this->JobsModel->job_info($id);
	    
	    if(!$job_info)
			return redirect()->to(base_url().'/jobs');  
	    
	    if($job_info['job_status']=='closed')
	      	$this->message_output->set_error( 'Please try again! Error while deleting record.');
	    else{
	        
	     $DeleteRec=$this->JobsModel->deleteRecord($id);
		if($DeleteRec){
			$this->message_output->set_success('Record Deleted successfully');
		} else {
			$this->message_output->set_error( 'Please try again! Error while deleting record.');
		}
		    
	    }
	    
	   
		return redirect()->to(base_url().'/jobs'); 	
	} 
	
}
