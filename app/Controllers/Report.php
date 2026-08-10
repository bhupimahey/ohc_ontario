<?php
namespace App\Controllers;
use App\Models\ReportModel;
use App\Controllers\BaseController;
use App\Libraries\auth_session;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class Report extends BaseController
{
	function __construct()
    {  
	    helper(['form', 'url']);
		$this->ReportModel     = new ReportModel();		
		$this->auth_session    = new auth_session();
	    $this->auth_session->admin_restrict();
	    $this->auth_session->role_restrict('S');
    }
	
   public function export_pending_payment(){
	    $report_data =  $this->ReportModel->export_pendingfee_report_list();	

	    $spreadsheet  = new Spreadsheet();      
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle("Pending Payment Details");
        
        $sheet->setCellValue('A1', 'JOBID#');
        $sheet->setCellValue('B1', 'CUSTOMER NAME');
        $sheet->setCellValue('C1', 'JOB LOCATION');
        $sheet->setCellValue('D1', 'WORK DETAILS');
		$sheet->setCellValue('E1', 'REMARKS');		
		$sheet->setCellValue('F1', 'TOTAL AMOUNT');
        $sheet->setCellValue('G1', 'RECEIVED');
		$sheet->setCellValue('H1', 'PENDING');
		
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
			
        foreach ($report_data as $rows){
            $sheet->setCellValue('A' . $counter,$rows['jobid']);
            $sheet->setCellValue('B' . $counter,$rows["customer_name"]);
            $sheet->setCellValue('C' . $counter, $rows["customer_location"]);
            $sheet->setCellValue('D' . $counter, $rows["work_details"]);
			$sheet->setCellValue('E' . $counter, $rows["remarks"]);
			$sheet->setCellValue('F' . $counter,$rows["total_amount"]);
            $sheet->setCellValue('G' . $counter, $rows["received"]);
            $sheet->setCellValue('H' . $counter, $rows["pending"]);
            $counter++;
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save(WRITEPATH.'Pending Payment.xlsx');
        return $this->response->download(WRITEPATH.'Pending Payment.xlsx', null);	 
   }	
   
   public function export_profit_summary()
{
    $report_type = $this->request->getGet('report_type');
    $daterange   = $this->request->getGet('daterange');

    $report_data = $this->ReportModel->export_profit_summary($report_type, '');

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle("Profit Summary");

    // HEADERS
    $sheet->setCellValue('A1', 'PERIOD');
    $sheet->setCellValue('B1', 'TOTAL JOBS');
    $sheet->setCellValue('C1', 'TOTAL LABOUR');
    $sheet->setCellValue('D1', 'TOTAL MATERIAL COST');
    $sheet->setCellValue('E1', 'TOTAL COST');
    $sheet->setCellValue('F1', 'TOTAL PROFIT');

    // Bold header
    $sheet->getStyle("A1:F1")->getFont()->setBold(true);

    $rowNumber = 2;

    foreach ($report_data as $row) {

        $sheet->setCellValue('A'.$rowNumber, $row['period']);
        $sheet->setCellValue('B'.$rowNumber, $row['total_jobs']);
        $sheet->setCellValue('C'.$rowNumber, $row['total_labour']);
        $sheet->setCellValue('D'.$rowNumber, $row['total_material']);
        $sheet->setCellValue('E'.$rowNumber, $row['total_cost']);
        $sheet->setCellValue('F'.$rowNumber, $row['total_profit']);

        $rowNumber++;
    }

    // Auto width
    foreach (range('A','F') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    $fileName = $report_type.' Profit Summary Report.xlsx';
    $writer = new Xlsx($spreadsheet);
    $writer->save(WRITEPATH . $fileName);

    return $this->response->download(WRITEPATH . $fileName, null);
}
   
   public function pending_payment()
    {
        if(isset($_GET['month_list_sel']))
          $month_list_sel = $_GET['month_list_sel'];
        else
          $month_list_sel =date('m');
          
        
      if(isset($_GET['filter_search_name']))
          $filter_search_name = $_GET['filter_search_name'];
        else
          $filter_search_name ='';
          
      if(isset($_GET['filter_search_month']))
          $filter_search_month = $_GET['filter_search_month'];
        else
          $filter_search_month =date('m');
          
     
      if(isset($_GET['filter_search_status']))
          $filter_search_status = $_GET['filter_search_status'];
        else
          $filter_search_status ='';
          
		$data['message_output']       = $this->message_output;
		$data['month_list_sel']       = $month_list_sel;
		$data['month_list']           = months_list();
		$data['filter_search_status'] = $filter_search_status;
		$data['filter_search_month']  = $filter_search_month;
		$data['filter_search_name']   = $filter_search_name;
	    return view('reports/pending_fee/view',$data);		
    } 
    
    
    public function profit_summary()
    {
        if(isset($_GET['month_list_sel']))
          $month_list_sel = $_GET['month_list_sel'];
        else
          $month_list_sel =date('m');
          
        
      if(isset($_GET['filter_search_name']))
          $filter_search_name = $_GET['filter_search_name'];
        else
          $filter_search_name ='';
          
      if(isset($_GET['filter_search_month']))
          $filter_search_month = $_GET['filter_search_month'];
        else
          $filter_search_month =date('m');
          
     
      if(isset($_GET['filter_search_status']))
          $filter_search_status = $_GET['filter_search_status'];
        else
          $filter_search_status ='';
          
		$data['message_output']       = $this->message_output;
		$data['month_list_sel']       = $month_list_sel;
		$data['month_list']           = months_list();
		$data['filter_search_status'] = $filter_search_status;
		$data['filter_search_month']  = $filter_search_month;
		$data['filter_search_name']   = $filter_search_name;
	    return view('reports/profit_summary/view',$data);		
    } 
    
    
     function ajax_staff_activity()
	 {
		echo $this->ReportModel->ajax_staffactivity_report();		
	 } 
	 
  public function export_staff_activity()
    {
    	
        $report_data =  $this->ReportModel->export_staffactivity_report();
	
	    $spreadsheet  = new Spreadsheet();      
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle("Staff Activity Details");

        $sheet->setCellValue('A1', '#JOBID');
        $sheet->setCellValue('B1', 'STAFF NAME');
        $sheet->setCellValue('C1', 'JOB TITLE');
		$sheet->setCellValue('D1', 'JOB START DATE');
		$sheet->setCellValue('E1', 'JOB COMPLETION DATE');
		$sheet->setCellValue('F1', 'HOURS WORKED');		
		$sheet->setCellValue('G1', 'CUSTOMER LOCATION');
		$sheet->setCellValue('H1', 'VISITED ON');
		
       
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
			
        foreach ($report_data as $rows){
            $sheet->setCellValue('A' . $counter,$rows["jobid"]);
            $sheet->setCellValue('B' . $counter, $rows["staff_name"]);
            $sheet->setCellValue('C' . $counter, $rows["job_title"]);
			$sheet->setCellValue('D' . $counter, $rows["job_start"]);
			$sheet->setCellValue('E' . $counter, $rows["job_end"]);
			$sheet->setCellValue('F' . $counter,$rows["hours_worked"]);
			$sheet->setCellValue('G' . $counter,$rows["customer_location"]);
			$sheet->setCellValue('H' . $counter,$rows["entry_on"]);
			
            $counter++;
           }

        $writer = new Xlsx($spreadsheet);
        $writer->save(WRITEPATH.'Staff Activity.xlsx');
        return $this->response->download(WRITEPATH.'Staff Activity.xlsx', null);	
    
    } 
  
  public function staff_activity()
    {
    	$data['message_output']       = $this->message_output;
    	$data['StaffDropdown']     = $this->ReportModel->staff_dropdown();
	    return view('reports/staff_activity/view',$data);		
    } 
 public function export_staff_payroll()
    {
    	
        $report_data =  $this->ReportModel->export_staffpayroll_report();
	
	    $spreadsheet  = new Spreadsheet();      
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle("Pending Payment Details");

        $sheet->setCellValue('A1', 'STAFF NAME');
        $sheet->setCellValue('B1', 'HOURS WORKED');
        $sheet->setCellValue('C1', 'HOURLY RATE');
		$sheet->setCellValue('D1', 'BALANCE');		
		$sheet->setCellValue('E1', 'DATE');
       
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
			
        foreach ($report_data as $rows){
            $sheet->setCellValue('A' . $counter,$rows["staff_name"]);
            $sheet->setCellValue('B' . $counter, $rows["hours_worked"]);
            $sheet->setCellValue('C' . $counter, $rows["basic_hour_rate"]);
			$sheet->setCellValue('D' . $counter, $rows["balance"]);
			$sheet->setCellValue('E' . $counter,$rows["month_date"]);
            $counter++;
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save(WRITEPATH.'Staff Payroll.xlsx');
        return $this->response->download(WRITEPATH.'Staff Payroll.xlsx', null);	
    
    }    
    
  
    public function staff_payroll()
    {
    	$data['message_output']       = $this->message_output;
    	$data['StaffDropdown']     = $this->ReportModel->staff_dropdown();
		$data['staff_result']     =  $this->ReportModel->ajax_staffpayroll_report();
		if(isset($_GET['filter_daterange']) && $_GET['filter_daterange']!=''){
			 $daterange  = explode("to",$_GET['filter_daterange']);
	        $start_date = $daterange[0];
			if(isset($daterange[1]))
	        $end_date   = $daterange[1];
		  else
			 $end_date   = $daterange[0];
		   
		}
		else{
			$start_date = date('Y-m-01');
			$end_date =date('Y-m-d');	
		}
		
		if(isset($_GET['filter_staff_id']) && $_GET['filter_staff_id']!=''){
			$filter_staff_id = $_GET['filter_staff_id'];
		}
		else
			$filter_staff_id =''; ;
		$data['filter_staff_id']   =  $filter_staff_id;
		$data['start_date']   =  $start_date;
		$data['end_date']     =  $end_date;
		
	    return view('reports/payroll/view',$data);		
    } 
    
    function job_info($job_id){
        return $this->db->table('jobs')->where('jobs_id', $job_id)->get()->getRowArray();
    }
     
    function add_pending_payment($submit=NULL){
        if($submit!='NULL'){
            $jobid               = $this->request->getVar('_token');
            $job_info            = $this->ReportModel->job_info($jobid ); 
            if(!$job_info) 	         return redirect()->to(base_url().'/report/pending_payment');  
            
            $total_rcvd_payments  = $this->ReportModel->job_rcvd_payments($jobid);
            
            if($total_rcvd_payments==$job_info['final_amount']){	
                $this->message_output->set_eror('Payment received already');
                } else{	
                    $amount_received     = $this->request->getVar('amount_received');
                    $payment_rcvd_by     = $this->request->getVar('payment_rcvd_by');
                    $remarks             = $this->request->getVar('remarks');
                    $payment_data        = array("jobs_id"=>$jobid,"amount_received"=>$amount_received,"remarks"=>$remarks,
                                                 "accepted_by" =>$this->session->get('s_user_id'),"entry_time"=>date("Y-m-d H:i:s")); 
                   $this->ReportModel->add_payment_received($jobid,$payment_data); 
                   $this->message_output->set_success('Payment received successfully');
            }		   	
		  return redirect()->to(base_url().'/report/pending_payment');
        }
    }
    
    function get_profit_summary()
	 {
		echo $this->ReportModel->ajax_get_profit_summary();		
	 }

    public function gross_revenue()
    {
        return view('reports/gross_revenue/view', [
            'message_output' => $this->message_output,
        ]);
    }

    function get_gross_revenue()
    {
        echo $this->ReportModel->ajax_get_gross_revenue();
    }

    public function export_gross_revenue()
    {
        $report_type = $this->request->getGet('report_type') ?: 'monthly';
        $report_data = $this->ReportModel->export_gross_revenue($report_type);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Gross Revenue');

        $sheet->setCellValue('A1', 'PERIOD');
        $sheet->setCellValue('B1', 'TOTAL JOBS');
        $sheet->setCellValue('C1', 'GROSS REVENUE');
        $sheet->getStyle('A1:C1')->getFont()->setBold(true);

        $rowNumber = 2;
        foreach ($report_data as $row) {
            $sheet->setCellValue('A' . $rowNumber, $row['period']);
            $sheet->setCellValue('B' . $rowNumber, $row['total_jobs']);
            $sheet->setCellValue('C' . $rowNumber, $row['gross_revenue']);
            $rowNumber++;
        }

        foreach (range('A', 'C') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $fileName = ucfirst($report_type) . ' Gross Revenue Report.xlsx';
        $writer = new Xlsx($spreadsheet);
        $writer->save(WRITEPATH . $fileName);

        return $this->response->download(WRITEPATH . $fileName, null);
    }

   function ajax_pendingfee_report_view()
	 {
		echo $this->ReportModel->ajax_pendingfee_report_list();		
	 } 
}