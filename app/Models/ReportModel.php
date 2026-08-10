<?php

namespace App\Models;

use CodeIgniter\Model;

class ReportModel extends Model	{
	 public function __construct() {
       parent::__construct();        
       $db = \Config\Database::connect();	
    }
 
 
public function export_profit_summary($report_type, $daterange)
{

    /*
    ===============================
    DATE FILTER
    ===============================
    */

    $dateWhere = "";

    if (!empty($daterange)) {

        $range = explode("to",$daterange);

        $start_date = trim($range[0]);
        $end_date   = trim($range[1]);

        $dateWhere = " AND DATE(j.job_start_date) >= '$start_date'
                       AND DATE(j.job_start_date) <= '$end_date'";
    }


    /*
    ===============================
    PERIOD SETTINGS
    ===============================
    */

    switch ($report_type) {

        case "weekly":

            $periodDisplay = "
            CONCAT(
                DATE_FORMAT(DATE_SUB(j.job_start_date,INTERVAL WEEKDAY(j.job_start_date) DAY),'%d %b'),
                ' - ',
                DATE_FORMAT(
                    DATE_ADD(DATE_SUB(j.job_start_date,INTERVAL WEEKDAY(j.job_start_date) DAY),INTERVAL 6 DAY),
                    '%d %b %Y'
                )
            )";

            $periodKey = "YEAR(j.job_start_date)*100 + WEEK(j.job_start_date)";
            $groupBy   = "YEAR(j.job_start_date),WEEK(j.job_start_date)";
            $poPeriodKey = "YEAR(po.po_date)*100 + WEEK(po.po_date)";
            break;


        case "yearly":

            $periodDisplay = "YEAR(j.job_start_date)";
            $periodKey     = "YEAR(j.job_start_date)";
            $groupBy       = "YEAR(j.job_start_date)";
            $poPeriodKey   = "YEAR(po.po_date)";
            break;


        default: // monthly

            $periodDisplay = "DATE_FORMAT(j.job_start_date,'%b %Y')";
            $periodKey     = "YEAR(j.job_start_date)*100 + MONTH(j.job_start_date)";
            $groupBy       = "YEAR(j.job_start_date),MONTH(j.job_start_date)";
            $poPeriodKey   = "YEAR(po.po_date)*100 + MONTH(po.po_date)";
            break;
    }



$sql = "

SELECT
    j.period,
    j.total_jobs,
    j.total_labour,

    (j.total_material + IFNULL(po.po_material,0)) AS total_material,

    (j.total_labour + j.total_material + IFNULL(po.po_material,0)) AS total_cost,

    (j.total_revenue - (j.total_labour + j.total_material + IFNULL(po.po_material,0))) AS total_profit


FROM (

    SELECT
        $periodDisplay AS period,
        $periodKey     AS period_key,

        COUNT(j.jobs_id) AS total_jobs,

        SUM(j.job_revenue) AS total_revenue,

        SUM(j.labour_cost) AS total_labour,

        SUM(j.material_cost) AS total_material

    FROM (

        SELECT
            j.jobs_id,
            j.final_amount AS job_revenue,
            j.job_start_date,

            SUM(CASE WHEN ji.item_name='Labour Cost'
                     THEN ji.item_cost ELSE 0 END) AS labour_cost,

            SUM(CASE WHEN ji.item_name='Material Cost'
                     THEN ji.item_cost ELSE 0 END) AS material_cost

        FROM jobs j
        LEFT JOIN job_items ji ON ji.jobs_id=j.jobs_id

        WHERE j.jobs_id > 0
        AND j.job_start_date IS NOT NULL
        $dateWhere

        GROUP BY j.jobs_id

    ) j

    GROUP BY $groupBy

) j


LEFT JOIN (

    SELECT
        $poPeriodKey AS period_key,

        SUM(
            poi.item_total +
            (CASE WHEN po.tax_applied=1 THEN (poi.item_total*13)/100 ELSE 0 END)
            + po.shipping_amount
            - po.discount_amount
        ) AS po_material

    FROM purchase_orders po
    JOIN poitems poi ON poi.poid = po.poid

    GROUP BY period_key

) po ON po.period_key = j.period_key


ORDER BY j.period_key DESC

";

    return $this->db->query($sql)->getResultArray();
}

public function ajax_get_profit_summary()
{
    $request = service('request');

    $draw   = intval($request->getPost("draw"));
    $start  = intval($request->getPost("start"));
    $length = intval($request->getPost("length"));

    $report_type = $request->getPost("report_type");
    $daterange   = $request->getPost("daterange");

    if ($length < 1) {
        $length = 10;
    }

    $dateWhere = "";

    if (!empty($daterange)) {

        $range = explode("to",$daterange);

        $start_date = trim($range[0]);
        $end_date   = trim($range[1]);

        $dateWhere = " AND DATE(j.job_start_date) >= '$start_date'
                       AND DATE(j.job_start_date) <= '$end_date'";
    }

    /*
    ==========================
    PERIOD SETTINGS
    ==========================
    */

    switch ($report_type) {

        case "weekly":

            $periodDisplay = "
            CONCAT(
                DATE_FORMAT(DATE_SUB(j.job_start_date,INTERVAL WEEKDAY(j.job_start_date) DAY),'%d %b'),
                ' - ',
                DATE_FORMAT(DATE_ADD(DATE_SUB(j.job_start_date,INTERVAL WEEKDAY(j.job_start_date) DAY),INTERVAL 6 DAY),'%d %b %Y')
            )";

            $periodKey = "YEAR(j.job_start_date)*100 + WEEK(j.job_start_date)";
            $groupBy = "YEAR(j.job_start_date),WEEK(j.job_start_date)";

            $poPeriodKey = "YEAR(po.po_date)*100 + WEEK(po.po_date)";
            break;


        case "yearly":

            $periodDisplay = "YEAR(j.job_start_date)";
            $periodKey = "YEAR(j.job_start_date)";
            $groupBy = "YEAR(j.job_start_date)";

            $poPeriodKey = "YEAR(po.po_date)";
            break;


        default:

            $periodDisplay = "DATE_FORMAT(j.job_start_date,'%b %Y')";
            $periodKey = "YEAR(j.job_start_date)*100 + MONTH(j.job_start_date)";
            $groupBy = "YEAR(j.job_start_date),MONTH(j.job_start_date)";

            $poPeriodKey = "YEAR(po.po_date)*100 + MONTH(po.po_date)";
            break;
    }


$sql = "

SELECT
    j.period,
    j.total_jobs,
    j.total_labour,

    (j.total_material + IFNULL(po.po_material,0)) AS total_material,

    (j.total_labour + j.total_material + IFNULL(po.po_material,0)) AS total_cost,

    (j.total_revenue - (j.total_labour + j.total_material + IFNULL(po.po_material,0))) AS total_profit

FROM (

    SELECT
        $periodDisplay AS period,
        $periodKey AS period_key,

        COUNT(j.jobs_id) AS total_jobs,

        SUM(j.job_revenue) AS total_revenue,

        SUM(j.labour_cost) AS total_labour,

        SUM(j.material_cost) AS total_material

    FROM (

        SELECT
            j.jobs_id,
            j.final_amount AS job_revenue,
            j.job_start_date,

            SUM(CASE WHEN ji.item_name='Labour Cost'
                     THEN ji.item_cost ELSE 0 END) AS labour_cost,

            SUM(CASE WHEN ji.item_name='Material Cost'
                     THEN ji.item_cost ELSE 0 END) AS material_cost

        FROM jobs j
        LEFT JOIN job_items ji ON ji.jobs_id=j.jobs_id

       WHERE j.jobs_id > 0
AND j.job_start_date IS NOT NULL
$dateWhere

        GROUP BY j.jobs_id

    ) j

    GROUP BY $groupBy

) j

LEFT JOIN (

    SELECT
        $poPeriodKey AS period_key,

        SUM(
            poi.item_total +
            (CASE WHEN po.tax_applied=1 THEN (poi.item_total*13)/100 ELSE 0 END)
            + po.shipping_amount
            - po.discount_amount
        ) AS po_material

    FROM purchase_orders po
    JOIN poitems poi ON poi.poid=po.poid

    GROUP BY period_key

) po ON po.period_key = j.period_key

ORDER BY j.period_key DESC
";


    $totalQuery = $this->db->query($sql);
    $totalRecords = count($totalQuery->getResult());

    $sql .= " LIMIT $start,$length";

    $result = $this->db->query($sql)->getResultArray();

    $data = [];

    foreach($result as $row){

        $data[] = [

            "period"=>$row['period'],
            "total_jobs"=>$row['total_jobs'],
            "total_labour"=>number_format($row['total_labour'],2),
            "total_material"=>number_format($row['total_material'],2),
            "total_cost"=>number_format($row['total_cost'],2),
            "total_profit"=>number_format($row['total_profit'],2)

        ];
    }

    return json_encode([
        "draw"=>$draw,
        "recordsTotal"=>$totalRecords,
        "recordsFiltered"=>$totalRecords,
        "data"=>$data
    ]);
}
   function get_job_item_info($phead_id){
	  return $this->db->table('payment_heads')->where('phead_id', $phead_id)->get()->getRowArray();   	   
   }
   
   function customer_info($customer_id){
	  return $this->db->table('customers')->where('customer_id', $customer_id)->get()->getRowArray();   	   
   }
   
    function staff_info($account_id){
	  return $this->db->table('users')->where('account_id', $account_id)->where('account_type', '3')->get()->getRowArray();   	   
   }

   function job_info($job_id){
	  return $this->db->table('jobs')->where('jobs_id', $job_id)->get()->getRowArray();   	   
   }
   
   function get_job_items($job_id){
	  return $this->db->table('job_items')->where('jobs_id', $job_id)->get()->getResultArray();   	   
   }
   
     function payment_history($job_id){
	  return $this->db->table('payment_history')->where('jobs_id', $job_id)->orderBy('entry_time','DESC')->get()->getResultArray();   	   
   }
   
   function add_payment_received($jobid,$payment_data){
	  $this->db->table('payment_history')->insert($payment_data);
	   
	   $job_info        = $this->job_info($jobid);
	   
	   $advance_payment  = $job_info['advanced_payment'];
	   $payment_history = $this->payment_history($jobid);
	   $total_rcvd =0;
	   foreach($payment_history as $prow){
		  $total_rcvd = $total_rcvd+ $prow['amount_received']; 
		   
	   }
	    $total_rcvd = number_format((float)$total_rcvd, 2, '.', '');
	    $final_amount = number_format((float)$job_info['final_amount'], 2, '.', '');
	    
		if($final_amount==$total_rcvd)		
	        $pending_amount ='0';
	    else{
			 $pending_amount = $job_info['final_amount']-($total_rcvd+$advance_payment);
		}
	
	   $this->db->table('jobs')->where('jobs_id',$jobid)->update(array("pending_amount"=>$pending_amount,"received_amount"=>$total_rcvd));
	   
	   
	   
   }
      
 
   public function staff_dropdown(){
	  $all_staff     = $this->db->table('users')->where('account_type', '3')->get()->getResult(); 
	  $final_list       = array();
	  $final_list['']   = 'Choose';
	  if( $all_staff){
		foreach( $all_staff as $row)
			$final_list[$row->account_id] = ucwords($row->first_name).' '.ucwords($row->last_name);
	  }
    return $final_list;
   }
   
   
    function export_staffactivity_report(){
		 $start  = 0;	
       if(isset($_GET['length']) && $_GET['length']!=''){
	       $length = $_GET['length'];	       
	     }
		else{
		  $length = 10;		 
		}
	   if(isset($_GET['start']) && $_GET['start']!=''){
			$start  = $_GET['start'];
	   }	
       else
        $start  = 0;
        
        $builder     =  $this->db->table("staff_job_activity");
        $builder->orderBy('entry_time'); 
	   	if(isset($_GET['filter_daterange']) && $_GET['filter_daterange']!=''){
            $daterange  = explode("to",$_GET['filter_daterange']);
	        $start_date = $daterange[0];
	        $end_date   = $daterange[1];
	        $builder->where("DATE(entry_time) >=",$start_date);
	        $builder->where("DATE(entry_time) <=",$end_date);
	     }
	    
         if(isset($_GET['filter_staff_id']) && $_GET['filter_staff_id']!=''){
           $filter_staff_id = $_GET['filter_staff_id'];
           $builder->where("staff_id",$filter_staff_id);
         }
         $builder->orderBy('DATE(entry_time)','DESC'); 
		$iTotalRecords   = $builder->countAllResults();								
		$iDisplayLength  = intval($length);
		$iDisplayLength  = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
		$iDisplayStart   = intval($start);
		if(isset($_GET['draw']))
		$sEcho           = intval($_GET['draw']);
	   else
		$sEcho=1;
		$records         = array();
		$records["data"] = array();
		$end             = $iDisplayStart + $iDisplayLength;
		$end             = $end > $iTotalRecords ? $iTotalRecords : $end;
		$id              = 0;
		
	if(isset($_GET['filter_daterange']) && $_GET['filter_daterange']!=''){
            $daterange  = explode("to",$_GET['filter_daterange']);
	        $start_date = $daterange[0];
	        $end_date   = $daterange[1];
	        $builder->where("DATE(entry_time) >=",$start_date);
	        $builder->where("DATE(entry_time) <=",$end_date);
	     }
	    
         if(isset($_GET['filter_staff_id']) && $_GET['filter_staff_id']!=''){
           $filter_staff_id = $_GET['filter_staff_id'];
           $builder->where("staff_id",$filter_staff_id);
         }
   	     $builder->orderBy('DATE(entry_time)','DESC'); 
	  	 $result =$builder->get()->getResultArray();
		
		foreach($result as $row)
			{
			      $id               = ($id + 1);
			      $staff_info       = $this->staff_info($row['staff_id']);
			      if($staff_info){
			      $staff_name       =  $staff_info['first_name'].' '.$staff_info['last_name'];
			     
			      $job_info         = $this->job_info($row['job_id']);
				  if($job_info){
				  $job_title        =  $job_info['work_details'];
				  
				  
				  
				  if($job_info['job_start_date']!='0000-00-00' && $job_info['job_start_date']!='1970-01-01')
				  $job_start_date =date('d M,Y',strtotime($job_info['job_start_date']));
				  else
				  $job_start_date ='';
				  
				   if($job_info['job_completion_date']!='0000-00-00' && $job_info['job_completion_date']!='1970-01-01')
				  $job_completion_date =date('d M,Y',strtotime($job_info['job_completion_date']));
				  else
				  $job_completion_date ='';
				  
				  
                  
  
				  $customer_location=  $job_info['customer_location'];
				  
				  $records["data"][] = array(
				      "jobid"=>sprintf( '%05d', $row['job_id'] ),
			          "staff_name"=>ucwords($staff_name),
					  "job_title"=>$job_title,
					  "job_start"=>$job_start_date,
					  "job_end"=>$job_completion_date,
					  "hours_worked"=>$row['hours_worked'],
					  "customer_location"=>$customer_location,
					  "entry_on"=>date('d M,Y',strtotime($row['entry_time']))
					  );  
			      }
			      }
			    
		     }
		  
		   return $records["data"];
		}
		
     function ajax_staffactivity_report(){
       if(isset($_REQUEST['length']) && $_REQUEST['length']!=''){
	       $length = $_REQUEST['length'];	       
	     }
		else{
		  $length = 10;		 
		}
	   if(isset($_REQUEST['start']) && $_REQUEST['start']!=''){
			$start  = $_REQUEST['start'];
	   }	
       else
        $start  = 0;
        
        $builder     =  $this->db->table("staff_job_activity");
        $builder->orderBy('DATE(entry_time)','DESC'); 
	   	if(isset($_REQUEST['filter_daterange']) && $_REQUEST['filter_daterange']!=''){
            $daterange  = explode("to",$_REQUEST['filter_daterange']);
	        $start_date = $daterange[0];
	        $end_date   = $daterange[1];
	        $builder->where("DATE(entry_time) >=",$start_date);
	        $builder->where("DATE(entry_time) <=",$end_date);
	     }
	    
         if(isset($_REQUEST['filter_staff_id']) && $_REQUEST['filter_staff_id']!=''){
           $filter_staff_id = $_REQUEST['filter_staff_id'];
           $builder->where("staff_id",$filter_staff_id);
         }
         
		$iTotalRecords   = $builder->countAllResults();								
		$iDisplayLength  = intval($length);
		$iDisplayLength  = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
		$iDisplayStart   = intval($start);
		$sEcho           = intval($_REQUEST['draw']);
		$records         = array();
		$records["data"] = array();
		$end             = $iDisplayStart + $iDisplayLength;
		$end             = $end > $iTotalRecords ? $iTotalRecords : $end;
		$id              = 0;
		
	if(isset($_REQUEST['filter_daterange']) && $_REQUEST['filter_daterange']!=''){
            $daterange  = explode("to",$_REQUEST['filter_daterange']);
	        $start_date = $daterange[0];
	        $end_date   = $daterange[1];
	        $builder->where("DATE(entry_time) >=",$start_date);
	        $builder->where("DATE(entry_time) <=",$end_date);
	     }
	    
         if(isset($_REQUEST['filter_staff_id']) && $_REQUEST['filter_staff_id']!=''){
           $filter_staff_id = $_REQUEST['filter_staff_id'];
           $builder->where("staff_id",$filter_staff_id);
         }
   	     $builder->orderBy('DATE(entry_time)','DESC'); 
	  	 $builder->limit($length,$start);
		 $result =$builder->get()->getResultArray();
		
		foreach($result as $row)
			{
			      $id               = ($id + 1);
			      $staff_info       = $this->staff_info($row['staff_id']);
			      
			      if($staff_info){
			          $staff_name       =  $staff_info['first_name'].' '.$staff_info['last_name'];
			          
			      $job_info         = $this->job_info($row['job_id']);
			      if( $job_info)
				  {
				  $job_title        =  $job_info['work_details'];
				  
				  
				  
				  if($job_info['job_start_date']!='0000-00-00' && $job_info['job_start_date']!='1970-01-01')
				  $job_start_date =date('d M,Y',strtotime($job_info['job_start_date']));
				  else
				  $job_start_date ='';
				  
				   if($job_info['job_completion_date']!='0000-00-00' && $job_info['job_completion_date']!='1970-01-01')
				  $job_completion_date =date('d M,Y',strtotime($job_info['job_completion_date']));
				  else
				  $job_completion_date ='';
				  
				  
                  $job_date ='<p class="mb-1"><strong>Job Started: </strong>'.$job_start_date.'<br>
                              <strong>Job Completion: </strong>'.$job_completion_date.'</p>' ;
                              
              
  
  
				  $customer_location=  $job_info['customer_location'];
				  
				  $records["data"][] = array(
				      sprintf( '%05d', $row['job_id'] ),
			          ucwords($staff_name),
					  wordwrap($job_title,'15','<br>').'<br>'.$job_date,
					  $row['hours_worked'],
					  wordwrap($customer_location,'20','<br>'),
					  date('d M,Y',strtotime($row['entry_time']))
					  );  
				  }
			      }
			    
		     }
		  
		   $records["draw"]            = $sEcho;
		   $records["recordsTotal"]    = $iTotalRecords;
		   $records["recordsFiltered"] = $iTotalRecords;
		   return json_encode($records);
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

    return $rhours.':'.$rmins;
} 

 function worked_history($staff_id,$sdate, $edate){
	  return $this->db->table('staff_job_activity')->where('DATE(entry_time) >=',$sdate)->where('DATE(entry_time) <=',$edate)->where('staff_id', $staff_id)->orderBy('entry_time','DESC')->get()->getResultArray();   	   
   }
   
  function export_staffpayroll_report(){
               
    $builder     =  $this->db->table("staff_job_activity");		
	if(isset($_GET['filter_daterange']) && $_GET['filter_daterange']!=''){
            $daterange  = explode("to",$_GET['filter_daterange']);
	        $start_date = $daterange[0];
	        $end_date   = $daterange[1];
	        $builder->where("DATE(entry_time) >=",$start_date);
	        $builder->where("DATE(entry_time) <=",$end_date);
	     }
	    else{
			$start_date = date('Y-m-01');
			$end_date =date('Y-m-d');
		   $builder->where("DATE(entry_time) >=",date('Y-m-01'));
	        $builder->where("DATE(entry_time) <=",date('Y-m-d'));	
			
		}
         if(isset($_GET['filter_staff_id']) && $_GET['filter_staff_id']!=''){
           $filter_staff_id = $_GET['filter_staff_id'];
           $builder->where("staff_id",$filter_staff_id);
         }
		
   	     $builder->orderBy('activity_id','DESC'); 
	  	 $result =$builder->get()->getResultArray();
		 $working_hours="0.00";
		 $records= array();
		$total_worked='0.00';		 
		  $staff_hours_array = array();
		  foreach($result as $row)
			{
				$working_hours_info   = explode(".",$row['hours_worked']);
				$working_hours_val    = $working_hours_info[0];
			    if(isset($working_hours_info[1]))
				 $working_minutes_val  = $working_hours_info[1];
				else
			 	$working_minutes_val  =0;
				if($working_hours_val>4){
                  $hoursAsDecimal  = $this->subMins($working_hours_val,$working_minutes_val,30); // minus 30 mins if job hours exceeded from 4 hours
               }
                  else{                
                  $hoursAsDecimal =$row['hours_worked'].'.00';
                  }
				
				$staff_hours_array[$row['staff_id']][]=$hoursAsDecimal;
				
				$staff_info        =  $this->staff_info($row['staff_id']);
			    $staff_name        =  $staff_info['first_name'].' '.$staff_info['last_name'];
				
	$all_staff[$row['staff_id']]=array(                        				
			          'staff_name'=>ucwords($staff_name),
					  'basic_rate'=>$row['hourly_rate'],	  
					 
					 );  
				
			}
			
	
	$records=array();		
	foreach($all_staff as $staff_id => $row){
		
		$staff_hours = CalculateTime($staff_hours_array[$staff_id]);
		
		 list($h, $m) = explode(':',$staff_hours);  
				  $decimal = $m/60;  //get minutes as decimal
				  $hoursAsDecimal = $h+$decimal;
				  			  
		
		$records[]=array(                   			  
			         "staff_name"=> ucwords($row['staff_name']),
					 "hours_worked" => $hoursAsDecimal,
					 "basic_hour_rate"=>'$'.$row['basic_rate'],
					 "balance" => '$'.number_format($hoursAsDecimal*$row['basic_rate'],2),
                     "month_date"=>date('d,M,Y',strtotime($start_date)).'-'.date('d,M,Y',strtotime($end_date))					 
					 );  
	}	
		
		   return $records;
		}
 
   function ajax_staffpayroll_report(){
	   $all_staff=array();
	   $records=array();
     
        
        $builder     =  $this->db->table("staff_job_activity");
		
		
	if(isset($_GET['filter_daterange']) && $_GET['filter_daterange']!=''){
            $daterange  = explode("to",$_GET['filter_daterange']);
	        $start_date = $daterange[0];
			if(isset($daterange[1]))
	        $end_date   = $daterange[1];
		  else
			 $end_date   = $daterange[0];
	        $builder->where("DATE(entry_time) >=",$start_date);
	        $builder->where("DATE(entry_time) <=",$end_date);
	     }
	    else{
			$start_date = date('Y-m-01');
			$end_date =date('Y-m-d');
		   $builder->where("DATE(entry_time) >=",date('Y-m-01'));
	        $builder->where("DATE(entry_time) <=",date('Y-m-t'));	
			
		}
         if(isset($_GET['filter_staff_id']) && $_GET['filter_staff_id']!=''){
           $filter_staff_id = $_GET['filter_staff_id'];
           $builder->where("staff_id",$filter_staff_id);
         }
		 
		
   	     $builder->orderBy('entry_time','DESC'); 
	  	
		 $result =$builder->get()->getResultArray();
		
		  $total_worked='0.00';		 
		  $staff_hours_array = array();
		  foreach($result as $row)
			{
				$working_hours_info   = explode(".",$row['hours_worked']);
				$working_hours_val    = $working_hours_info[0];
			    if(isset($working_hours_info[1]))
				 $working_minutes_val  = $working_hours_info[1];
				else
			 	$working_minutes_val  =0;
				if($working_hours_val>4){
                  $hoursAsDecimal  = $this->subMins($working_hours_val,$working_minutes_val,30); // minus 30 mins if job hours exceeded from 4 hours
               }
                  else{                
                  $hoursAsDecimal =$row['hours_worked'].'.00';
                  }
				
				$staff_hours_array[$row['staff_id']][]=$hoursAsDecimal;
				
				$staff_info        =  $this->staff_info($row['staff_id']);
			    $staff_name        =  $staff_info['first_name'].' '.$staff_info['last_name'];
				$jobs_history = '';
	            	$jobs_history .='<table class="table table--light style--two">
                        <thead>
                            <tr>
                                <th>#ID</th>
								<th>Job</th>
								<th>Customer Location</th>
								<th>Hours Worked</th>
								<th>On Dated</th>
                            </tr>
                        </thead><tbody>';
						$payment_counter=0;
				$job_worked_history =$this->worked_history($row['staff_id'],$start_date,$end_date);
			    if($job_worked_history){
			       foreach($job_worked_history as $histryrow){
					   $job_info        = $this->job_info($histryrow['job_id']);
					   if($job_info){
					        $job_title       = $job_info['work_details'];
					         $customer_location       = $job_info['customer_location'];
					  
					  
			           $payment_counter++;
			           	$jobs_history .='<tr>
			                    <td>'.sprintf( '%05d', $job_info['jobs_id'] ).'</td>
								<td>'. wordwrap($job_title,'15','<br>').'</td>
								<td>'. wordwrap($customer_location,'15','<br>').'</td>
								<td>'.$histryrow['hours_worked'].'</td>
								<td>'.date('d M, Y',strtotime($histryrow['entry_time'])).'</td>
			                    </tr>';	
			                 }    
			                    
			           } 
			    }
			    else{
			    	$jobs_history .='<tr>
			                    <td colspan="5" align="center">No record found</td>
			                    </tr>';	    
			        
			    }
			  $jobs_history .='</tbody></table>';

			  $jobs_activity_link_modal ='<div class="modal fade" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true"  id="activityBy'.$row['staff_id'].'"> <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-dialog-scrollable">
            <div class="modal-header">
                <h5 class="" lass="modal-title" id="exampleModalLabel">Jobs History</h5>
                    <button type="button" class="close close_paymenthist_model" data-id="activityBy'.$row['staff_id'].'"aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
            </div>            
                <div class="modal-body">
                    '.$jobs_history.'
                </div>                        
        </div>
    </div>
</div></div>';
	$all_staff[$row['staff_id']]=array(                        				
			          'staff_name'=>ucwords($staff_name),
					  'basic_rate'=>$row['hourly_rate'],				  
					  'jobs_activity_link_modal'=>$jobs_activity_link_modal
					 );  
				
			}
			
	
	$records=array();		
	foreach($all_staff as $staff_id => $row){
		
		$staff_hours = CalculateTime($staff_hours_array[$staff_id]);
		
		 list($h, $m) = explode(':',$staff_hours);  
				  $decimal = $m/60;  //get minutes as decimal
				  $hoursAsDecimal = $h+$decimal;
				  			  
		
		$records[]=array(                   			  
			          "staff_name"=> ucwords($row['staff_name']),
					  "link"=>'<a href="javascript:void(0)" title="" class="btn btn-sm btn-success activitymodal" data-id="'.$staff_id.'" data-code="3XMWKPD1U167">'.$hoursAsDecimal.'</a>'.$row['jobs_activity_link_modal'],
					  "basic_rate"=>$row['basic_rate'],
					  'balance'=>'$'.number_format($hoursAsDecimal*$row['basic_rate'],2)				  
					 );  
	}		
	
		return $records;	
	      
		 	
			
		}
		
		  function job_rcvd_payments($job_id){
      $builder = $this->db->table('payment_history');
      $builder->select('SUM(amount_received) as total_received');
      $builder->where('jobs_id', $job_id);
      $response = $builder->get()->getRowArray();  
      if($response)
      return $response['total_received'];
      else
      return "0";
  }   
  
    
  function ajax_pendingfee_report_list(){
       
        
        if(isset($_REQUEST['length']) && $_REQUEST['length']!=''){
	       $length = $_REQUEST['length'];	       
	     }
		else{
		  $length = 10;		 
		}
	   if(isset($_REQUEST['start']) && $_REQUEST['start']!=''){
			$start  = $_REQUEST['start'];
	   }	
       else
        $start  = 0;		  
         
       
         if(isset($_REQUEST['filter_search_name']) && $_REQUEST['filter_search_name']!=''){
          
          $filter_search_name = addslashes($_REQUEST['filter_search_name']);
           $builder_cust      =  $this->db->table("customers");
           $builder_cust->where("( LOWER(full_name) LIKE '%".strtolower($filter_search_name)."%'  OR 
                             LOWER(address) LIKE '%".strtolower($filter_search_name)."%'  OR 
                             LOWER(phone_no) LIKE '%".strtolower($filter_search_name)."%'
                             ) ");
           	$cust_result = $builder_cust->get()->getResultArray();
           
           	
           	if($cust_result){
           	    $customer_ids=array();
           	    foreach($cust_result as $custrow){
           	       $customer_ids[$custrow['customer_id']]= $custrow['customer_id'];
           	       }
           	       $customer_ids = implode(",",$customer_ids);
             	}else
        $customer_ids='0';
             	
           }
        else
        $customer_ids='0';
       
     
     
     
         $sql='';
         $sql .= "select a.entry_time,a.customer_id,a.jobs_id,a.final_amount,sum(d.amount_received) as `tt` from jobs a
                INNER JOIN  payment_history d ON d.jobs_id = a.jobs_id  
                group by d.jobs_id 
                HAVING sum(d.amount_received) !=a.final_amount ";
       if(isset($_REQUEST['filter_search_name']) && $_REQUEST['filter_search_name']!=''){
                $sql .= " AND a.customer_id IN(".$customer_ids.") ";
         
       }
      
         if(isset($_REQUEST['filter_daterange']) && $_REQUEST['filter_daterange']!=''){
            $daterange  = explode("to",$_REQUEST['filter_daterange']);
	        $start_date = $daterange[0];
	        $end_date   = $daterange[1];
	        $sql .=" AND DATE(a.entry_time) >=".$start_date." AND DATE(a.entry_time) <=".$end_date;
	        
	     }
	      $sql .=" order by a.entry_time";
         
      
        
         $query = $this->db->query($sql);
        
       
		$iTotalRecords   = $query->getNumRows();						
		$iDisplayLength  = intval($length);
		$iDisplayLength  = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
		$iDisplayStart   = intval($start);
		$sEcho           = intval($_REQUEST['draw']);
		$records         = array();
		$records["data"] = array();
		$end             = $iDisplayStart + $iDisplayLength;
		$end             = $end > $iTotalRecords ? $iTotalRecords : $end;
		$id              = 0;
	
        if(isset($_REQUEST['filter_search_name']) && $_REQUEST['filter_search_name']!=''){
           
          $filter_search_name = addslashes($_REQUEST['filter_search_name']);
           $builder_cust      =  $this->db->table("customers");
           $builder_cust->where("( LOWER(full_name) LIKE '%".strtolower($filter_search_name)."%'  OR 
                             LOWER(address) LIKE '%".strtolower($filter_search_name)."%'  OR 
                             LOWER(phone_no) LIKE '%".strtolower($filter_search_name)."%'
                             ) ");
           	$cust_result =$builder_cust->get()->getResultArray();
           	if($cust_result){
           	    $customer_ids=array();
           	    foreach($cust_result as $custrow){
           	       $customer_ids[$custrow['customer_id']]= $custrow['customer_id'];
           	       }
           	       
           	       	$customer_ids = implode(",",$customer_ids);
             	}
           	
           }
         else
         $customer_ids=0;
         
         $sql='';
         $sql .= "select *,sum(d.amount_received) as amount_received  from jobs a
                INNER JOIN  payment_history d ON d.jobs_id = a.jobs_id  
                group by d.jobs_id 
                HAVING sum(d.amount_received) !=a.final_amount ";
       if(isset($_REQUEST['filter_search_name']) && $_REQUEST['filter_search_name']!=''){
                $sql .= " AND a.customer_id IN(".$customer_ids.") ";
         
       }
         
         if(isset($_REQUEST['filter_daterange']) && $_REQUEST['filter_daterange']!=''){
            $daterange  = explode("to",$_REQUEST['filter_daterange']);
	        $start_date = $daterange[0];
	        $end_date   = $daterange[1];
	        $sql .=" AND DATE(a.entry_time) >=".$start_date." AND DATE(a.entry_time) <=".$end_date;
	        
	     }
	      $sql .=" order by a.entry_time limit ".$start.', '.$length;
	      
	  //	 echo $sql;
		 $result =$this->db->query($sql)->getResultArray();
		
		foreach($result as $row)
			{
			    	$payment_history = '';
	            	$payment_history .='<table class="table table--light style--two">
                        <thead>
                            <tr>
                                <th>Sr.No.</th>
								<th>Received Amount</th>
								<th>Remarks</th>
								<th>On Dated</th>
                            </tr>
                        </thead><tbody>';
                        $payment_counter=0;
			    $job_payment_hostory =$this->payment_history($row['jobs_id']);
			    if($job_payment_hostory){
			       foreach($job_payment_hostory as $paymentrow){
			           $payment_counter++;
			           	$payment_history .='<tr>
			                    <td>'.$payment_counter.'. </td>
								<td>$'.number_format($paymentrow['amount_received'],2).'</td>
								<td>'.$paymentrow['remarks'].'</td>
								<td>'.date('d M, Y',strtotime($paymentrow['entry_time'])).'</td>
			                    </tr>';	
			           
			       } 
			        
			        
			    }
			    else{
			    	$payment_history .='<tr>
			                    <td colspan="4" align="center">No record found</td>
			                    </tr>';	    
			        
			    }
			  $payment_history .='</tbody></table>';	
			  	$payment_link_modal ='<div class="modal fade" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true"  id="paymentBy'.$row['jobs_id'].'"> <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="" lass="modal-title" id="exampleModalLabel">Payment History</h5>
                    <button type="button" class="close close_paymenthist_model" data-id="paymentBy'.$row['jobs_id'].'"aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
            </div>            
                <div class="modal-body">
                    '.$payment_history.'
                </div>                        
        </div>
    </div>
</div></div>';

$addpayment_link_modal ='<div class="modal fade" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" id="AddPaymentBy'.$row['jobs_id'].'"> <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="" lass="modal-title" id="exampleModalLabel">Add Payment</h5>
                    <button type="button" class="close close_addpaymnt_model" data-id="AddPaymentBy'.$row['jobs_id'].'" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>            
                <div class="modal-body">
                   <form action="'.base_url().'/report/add_pending_payment/submit" method="POST">
                <input type="hidden" name="_token" value="'.$row['jobs_id'].'"> 
				<input type="hidden" name="_method" value="POST">              
				<input type="hidden" name="code">
                <div class="modal-body">
                   
                    <div class="form-group col-lg-12">
                         <label for="invoice_to_contact" class="form-control-label font-weight-bold">Amount</label>
                          <input type="text" class="form-control form-control-lg" id="amount_received" name="amount_received" placeholder="Enter Received Amount" value="" required="">
                    </div>
					
					 <div class="form-group col-lg-12">
                         <label for="invoice_to_contact" class="form-control-label font-weight-bold">Received Through</label>
                          <select name="payment_rcvd_by" id="payment_rcvd_by" class="form-control select">
                          <option value="cash">Cash</option>
                          <option value="online_transfer">Online Transfer</option>
                          <option value="cheque_upi">Cheque/UPI</option
                          </option>
                          </select>
                    </div>
                    
                     <div class="form-group col-lg-12">
                         <label for="invoice_to_contact" class="form-control-label font-weight-bold">Remarks</label>
                          <textarea name="remarks" id="remarks" class="form-control form-control-lg"></textarea>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary close_addpaymnt_model" data-id="AddPaymentBy'.$row['jobs_id'].'">Close</button>
                    <button type="submit" class="btn btn-sm btn-success">Save</button>
                </div>
            </form>
                </div>                        
        </div>
    </div>
</div></div>';
			  
			  
			     $id                   = ($id + 1);
			     $customer_info = $this->customer_info($row['customer_id']);
			     if($customer_info)
			      $customer_name      =  $customer_info['full_name'];
			      $customer_location  =  $row['customer_location'];
				  $work_details       =  $row['work_details'];	
				  $remarks            =  $row['remarks'];
				  if(empty($remarks))
				  $remarks ='N/A';
				  
				  $total_amount       =  $row['final_amount'];
				  $received           =  $row['amount_received'];
				 
				  
				  $payment_link   = '<a href="javascript:void(0)" title="" class="btn btn-sm btn-primary addpayment" data-id="'.$row['jobs_id'].'" data-code="3XMWKPD1U167">Add</a> <br /><a href="javascript:void(0)" title="" style="margin-top:2px;" class="btn btn-sm btn-success payment" data-id="'.$row['jobs_id'].'" data-code="3XMWKPD1U167">History</a>';
                        
                 
				  $records["data"][] = array(	
				      sprintf( '%05d', $row['jobs_id'] ),
				       wordwrap($customer_location,15,'<br>'),
			          ucwords($customer_name),
					 
					  wordwrap($work_details,15,'<br>'),
					  wordwrap($remarks,15,'<br>'),						 
					  '$'.$total_amount,
					  '$'.$received,
					  '$'.($total_amount-$received),
					  $payment_link.$payment_link_modal.$addpayment_link_modal 
				     );  
			    
		     }
		  
		   $records["draw"]            = $sEcho;
		   $records["recordsTotal"]    = $iTotalRecords;
		   $records["recordsFiltered"] = $iTotalRecords;
		   return json_encode($records);
		   
		}	

	function export_pendingfee_report_list(){
        $start  = 0;	
        
	   	
        if(isset($_GET['length']) && $_GET['length']!=''){
	       $length = $_GET['length'];	       
	     }
		else{
		  $length = 10;		 
		}
	   if(isset($_GET['start']) && $_GET['start']!=''){
			$start  = $_GET['start'];
	   }	
       else
        $start  = 0;		  
         
        
         if(isset($_GET['filter_search_name']) && $_GET['filter_search_name']!=''){
           
          $filter_search_name = addslashes($_GET['filter_search_name']);
           $builder_cust      =  $this->db->table("customers");
           $builder_cust->where("( LOWER(full_name) LIKE '%".strtolower($filter_search_name)."%'  OR 
                             LOWER(address) LIKE '%".strtolower($filter_search_name)."%'  OR 
                             LOWER(phone_no) LIKE '%".strtolower($filter_search_name)."%'
                             ) ");
           	$cust_result =$builder_cust->get()->getResultArray();
           	if($cust_result){
           	    $customer_ids=array();
           	    foreach($cust_result as $custrow){
           	       $customer_ids[$custrow['customer_id']]= $custrow['customer_id'];
           	       }
           	       $customer_ids = implode(",",$customer_ids);
             	}
           }
        else
        $customer_ids=0;
        
        $sql='';
         $sql .= "select a.customer_id,a.jobs_id,a.final_amount,sum(d.amount_received) as `tt` from jobs a
                INNER JOIN  payment_history d ON d.jobs_id = a.jobs_id  
                group by d.jobs_id 
                HAVING sum(d.amount_received) !=a.final_amount ";
        if(isset($_REQUEST['filter_search_name']) && $_REQUEST['filter_search_name']!=''){
                $sql .= " AND a.customer_id IN(".$customer_ids.") ";
         
       }
         
         if(isset($_REQUEST['filter_daterange']) && $_REQUEST['filter_daterange']!=''){
            $daterange  = explode("to",$_REQUEST['filter_daterange']);
	        $start_date = $daterange[0];
	        $end_date   = $daterange[1];
	        $sql .=" AND DATE(a.entry_time) >=".$start_date." AND DATE(a.entry_time) <=".$end_date;
	        
	     }
	      $sql .=" order by a.entry_time";
         
         
         $query = $this->db->query($sql);
        
       
		$iTotalRecords   = $query->getNumRows();
		
        
									
		$iDisplayLength  = intval($length);
		$iDisplayLength  = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
		$iDisplayStart   = intval($start);
		if(isset($_GET['draw']))
		$sEcho           = intval($_GET['draw']);
	   else
		$sEcho=1;
		$records         = array();
		$records["data"] = array();
		$end             = $iDisplayStart + $iDisplayLength;
		$end             = $end > $iTotalRecords ? $iTotalRecords : $end;
		$id              = 0;
		
	
	     
        if(isset($_GET['filter_search_name']) && $_GET['filter_search_name']!=''){
           
          $filter_search_name = addslashes($_GET['filter_search_name']);
           $builder_cust      =  $this->db->table("customers");
           $builder_cust->where("( LOWER(full_name) LIKE '%".strtolower($filter_search_name)."%'  OR 
                             LOWER(address) LIKE '%".strtolower($filter_search_name)."%'  OR 
                             LOWER(phone_no) LIKE '%".strtolower($filter_search_name)."%'
                             ) ");
           	$cust_result =$builder_cust->get()->getResultArray();
           	if($cust_result){
           	  $customer_ids=array();
           	    foreach($cust_result as $custrow){
           	       $customer_ids[$custrow['customer_id']]= $custrow['customer_id'];
           	       }
           	      
           	       $customer_ids = implode(",",$customer_ids);
             	}
           	
           }
         else
         $customer_ids=0;
        
        $sql='';
         $sql .= "select *,sum(d.amount_received) as amount_received  from jobs a
                INNER JOIN  payment_history d ON d.jobs_id = a.jobs_id  
                group by d.jobs_id 
                HAVING sum(d.amount_received) !=a.final_amount ";
         if(isset($_REQUEST['filter_search_name']) && $_REQUEST['filter_search_name']!=''){
                $sql .= " AND a.customer_id IN(".$customer_ids.") ";
         
       }
         
         if(isset($_REQUEST['filter_daterange']) && $_REQUEST['filter_daterange']!=''){
            $daterange  = explode("to",$_REQUEST['filter_daterange']);
	        $start_date = $daterange[0];
	        $end_date   = $daterange[1];
	        $sql .=" AND DATE(a.entry_time) >=".$start_date." AND DATE(a.entry_time) <=".$end_date;
	        
	     }
	      $sql .=" order by a.entry_time ";
	      
	  	 
		 $result =$this->db->query($sql)->getResultArray();
		
         	
		foreach($result as $row)
			{
			     $id                   = ($id + 1);
			     $customer_info = $this->customer_info($row['customer_id']);
			     if($customer_info)
			      $customer_name      =  $customer_info['full_name'];
			      $customer_location  =  $row['customer_location'];
				  $work_details       =  $row['work_details'];	
				  $remarks            =  $row['remarks'];				  
				  $total_amount       =  $row['final_amount'];
				  $received           =  $row['amount_received'];
				  
				  $records["data"][] = array(
				     "jobid"=> sprintf( '%05d', $row['jobs_id'] ), 
			         "customer_name"=> ucwords($customer_name),
					 "customer_location"=> $customer_location,
					 "work_details"=>$work_details,
					 "remarks"=> $remarks,						 
					 "total_amount"=> '$'.$total_amount,
					 "received"=> '$'.$received,
					 "pending"=> '$'.($total_amount-$received)
				     );  
			    
		     }		  
		   return $records["data"];
		}

    /**
     * Gross revenue = SUM(jobs.final_amount), same basis as Dashboard Gross Collection.
     * Grouped weekly or monthly by job_start_date.
     */
    private function gross_revenue_period_sql($report_type)
    {
        if ($report_type === 'weekly') {
            return [
                'display' => "CONCAT(
                    DATE_FORMAT(DATE_SUB(j.job_start_date, INTERVAL WEEKDAY(j.job_start_date) DAY), '%d %b'),
                    ' - ',
                    DATE_FORMAT(DATE_ADD(DATE_SUB(j.job_start_date, INTERVAL WEEKDAY(j.job_start_date) DAY), INTERVAL 6 DAY), '%d %b %Y')
                )",
                'key' => 'YEAR(j.job_start_date)*100 + WEEK(j.job_start_date)',
                'group' => 'YEAR(j.job_start_date), WEEK(j.job_start_date)',
            ];
        }

        return [
            'display' => "DATE_FORMAT(j.job_start_date, '%b %Y')",
            'key' => 'YEAR(j.job_start_date)*100 + MONTH(j.job_start_date)',
            'group' => 'YEAR(j.job_start_date), MONTH(j.job_start_date)',
        ];
    }

    private function build_gross_revenue_sql($report_type)
    {
        $period = $this->gross_revenue_period_sql($report_type);

        return "
SELECT
    {$period['display']} AS period,
    {$period['key']} AS period_key,
    COUNT(j.jobs_id) AS total_jobs,
    SUM(j.final_amount) AS gross_revenue
FROM jobs j
WHERE j.jobs_id > 0
  AND j.job_start_date IS NOT NULL
GROUP BY {$period['group']}
ORDER BY period_key DESC
";
    }

    public function ajax_get_gross_revenue()
    {
        $request = \Config\Services::request();
        $draw = intval($request->getPost('draw'));
        $start = intval($request->getPost('start'));
        $length = intval($request->getPost('length'));
        $report_type = $request->getPost('report_type') ?: 'monthly';

        if ($report_type !== 'weekly') {
            $report_type = 'monthly';
        }
        if ($length < 1) {
            $length = 10;
        }

        $sql = $this->build_gross_revenue_sql($report_type);
        $totalRecords = count($this->db->query($sql)->getResult());
        $sql .= " LIMIT $start, $length";
        $result = $this->db->query($sql)->getResultArray();

        $data = [];
        foreach ($result as $row) {
            $data[] = [
                'period' => $row['period'],
                'total_jobs' => $row['total_jobs'],
                'gross_revenue' => number_format((float) $row['gross_revenue'], 2),
            ];
        }

        return json_encode([
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $data,
        ]);
    }

    public function export_gross_revenue($report_type)
    {
        if ($report_type !== 'weekly') {
            $report_type = 'monthly';
        }

        $sql = $this->build_gross_revenue_sql($report_type);
        $result = $this->db->query($sql)->getResultArray();

        $rows = [];
        foreach ($result as $row) {
            $rows[] = [
                'period' => $row['period'],
                'total_jobs' => $row['total_jobs'],
                'gross_revenue' => number_format((float) $row['gross_revenue'], 2),
            ];
        }

        return $rows;
    }
}