<?php
namespace App\Controllers;
use App\Models\DashboardModel;
use App\Controllers\BaseController;
use App\Libraries\auth_session;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class Dashboard extends BaseController
{
	
	function __construct()
    {  
	    helper(['form', 'url']);
		$this->DashboardModel = new DashboardModel();		
		$this->auth_session   = new auth_session();
	    $this->auth_session->admin_restrict();
	    $this->auth_session->role_restrict('S');
    }
    
   public function overdue_notifications()
{
    $data = $this->DashboardModel->get_overdue_payments();

    if (empty($data)) {
        return $this->response->setJSON([]);
    }

    return $this->response->setJSON($data);
}


    public function index()
    {
        $data['message_output']    = $this->message_output;	
	    $data['total_jobs']        = $this->DashboardModel->total_jobs(); 
	    $data['total_customers']   = $this->DashboardModel->total_customers();
	    $data['total_quotations']  = $this->DashboardModel->total_quotations();
	    $data['total_staff']       = $this->DashboardModel->total_staff();
	    $data['latest_jobs']       = $this->DashboardModel->latest_jobs('10');
	 
	    return view('dashboard',$data);	
    }
    	function calculateFiscalYearForDate($month) 
		{		if($month > 4)	{	
		$y  = date('Y');	
		$pt = date('Y');	
		$fy = array("start_date"=>"01-01-".$y ,"end_date"=>"31-12-".$pt);
		}  
		else {	
		$y = date('Y');
		$pt = date('Y');
        $fy = array("start_date"=>"01-01-".$y ,"end_date"=>"31-12-".$pt);
		}
		return $fy;
		}
    
	
	function export_accounts()
	 {
	 if(isset($_GET['filter_fromdate']) && isset($_GET['filter_todate'])){
            $FiscalYearDate = array("start_date"=>trim($_GET['filter_fromdate']),"end_date"=>trim($_GET['filter_todate']) );
            
        }
        else{
        $FiscalYearDate = $this->calculateFiscalYearForDate(date('m')); 
        }
    
        
		$startdate = date('d_M_Y',strtotime($FiscalYearDate['start_date'])).'-'.date('d_M_Y',strtotime($FiscalYearDate['end_date']));
	$file_name = "accounts_data-".$startdate;
    $gross_collection  = $this->DashboardModel->gross_collection($FiscalYearDate); 
	$material_cost     = $this->DashboardModel->material_cost($FiscalYearDate);
	$labour_cost       = $this->DashboardModel->labour_cost($FiscalYearDate);
	$total_received    = $this->DashboardModel->total_received($FiscalYearDate);
	
	
    $total_pending     = ($gross_collection-$total_received);	
	$total_profit      = ($gross_collection-($material_cost+$labour_cost));	
	
       $spreadsheet  = new Spreadsheet();      
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle("Accounts Listing");

        $sheet->setCellValue('A1', 'GROSS COLLECTION');
        $sheet->setCellValue('B1', 'MATERIAL COLLECTION');
        $sheet->setCellValue('C1', 'LABOUR COST');
		$sheet->setCellValue('D1', 'PROFIT');		
		$sheet->setCellValue('E1', 'TOTAL RECEIVED');
        $sheet->setCellValue('F1', 'TOTAL PENDING');
		
        $counter = 2;	
		$spreadsheet->getActiveSheet()->getStyle("A1")->getFont()->setBold( true );
		$spreadsheet->getActiveSheet()->getStyle("B1")->getFont()->setBold( true );
		$spreadsheet->getActiveSheet()->getStyle("C1")->getFont()->setBold( true );
		$spreadsheet->getActiveSheet()->getStyle("D1")->getFont()->setBold( true );
		$spreadsheet->getActiveSheet()->getStyle("E1")->getFont()->setBold( true );
		$spreadsheet->getActiveSheet()->getStyle("F1")->getFont()->setBold( true );
		foreach (range('A', $spreadsheet->getActiveSheet()->getHighestDataColumn()) as $col) {
				$spreadsheet->getActiveSheet()
						->getColumnDimension($col)
						->setAutoSize(true);
			} 
			
      
            $sheet->setCellValue('A' . 2, '$'.number_format($gross_collection,2));
            $sheet->setCellValue('B' . 2, '$'.number_format($material_cost,2));
            $sheet->setCellValue('C' . 2, '$'.number_format($labour_cost,2));
		
			$sheet->setCellValue('D' . 2, '$'.number_format($total_profit,2));
				$sheet->setCellValue('E' . 2, '$'.number_format($total_received,2));
            $sheet->setCellValue('F' . 2, '$'.number_format($total_pending,2));
            $counter++;
        

        $writer = new Xlsx($spreadsheet);
        $writer->save('Accounts Listing.xlsx');
        return $this->response->download('Accounts Listing.xlsx', null);
		
	 }
	 
     public function accounts()
    {	
        
        if(isset($_GET['filter_fromdate']) && isset($_GET['filter_todate'])){
            $FiscalYearDate = array("start_date"=>trim($_GET['filter_fromdate']),"end_date"=>trim($_GET['filter_todate']) );
            
        }
        else{
        $FiscalYearDate = $this->calculateFiscalYearForDate(date('m')); 
        }
        
       
        
        $data['FiscalYearDate']        = $FiscalYearDate;
        $data['message_output']    = $this->message_output;	
	    $data['gross_collection']  = $this->DashboardModel->gross_collection($FiscalYearDate); 
	    $data['material_cost']     = $this->DashboardModel->material_cost($FiscalYearDate);
	    $data['labour_cost']       = $this->DashboardModel->labour_cost($FiscalYearDate);
	    $data['total_received']       = $this->DashboardModel->total_received($FiscalYearDate);
	    return view('accounts',$data);	
    }
    
    
	public function logout()
    {
	   $this->session->remove('s_user_type');
       $this->session->remove('s_user_id');
       $this->session->remove('s_name');
       $this->session->remove('s_photo_path');
       $this->session->destroy();
       return redirect()->to(base_url().'/login');  		
    }
}