<?php
namespace App\Models;

use CodeIgniter\Model;

class DashboardModel extends Model	{
	
    public function __construct() {
        parent::__construct();        
       $this->db = \Config\Database::connect();	
    }
    
 public function get_overdue_payments()
{
    $sql = "
        SELECT
            j.jobs_id,
            j.customer_id,
            j.final_amount,
            j.pending_amount,
            j.job_completion_date,
            DATEDIFF(CURDATE(), j.job_completion_date) AS overdue_days
        FROM jobs j
        WHERE j.pending_amount > 0
        AND j.job_completion_date IS NOT NULL
        AND DATEDIFF(CURDATE(), j.job_completion_date) >= 15
        ORDER BY overdue_days DESC
    ";

    $rows = $this->db->query($sql)->getResultArray();

    foreach ($rows as &$row) {

        $row['severity'] =
            ($row['overdue_days'] >= 30) ? 'critical' : 'warning';

        $cust = $this->db->table('customers')
            ->select('full_name')
            ->where('customer_id', $row['customer_id'])
            ->get()->getRowArray();

        $row['customer_name'] = $cust['full_name'] ?? 'Unknown';
    }

    return $rows;
}

   
   public function total_jobs($status=''){
       
       if($status!='')
       return $this->db->table('jobs')->where('job_status', $status)->countAllResults();
       else
		return $this->db->table('jobs')->countAllResults();
		
	}
   
   function gross_collection($FiscalYearDate){

    $sql = "
        SELECT SUM(final_amount) AS gross_collection
        FROM jobs
        WHERE job_start_date >= ?
        AND job_start_date <= ?
    ";

    $row = $this->db->query($sql,[
        $FiscalYearDate['start_date'],
        $FiscalYearDate['end_date']
    ])->getRowArray();

    return $row['gross_collection'] ?? 0;
}
    
    function material_cost($FiscalYearDate){

    /*
    =============================
    JOB MATERIAL COST
    =============================
    */

    $sql = "

    SELECT SUM(t.material_cost) AS material_cost

    FROM (

        SELECT 
            j.jobs_id,
            SUM(CASE WHEN ji.item_name='Material Cost'
                     THEN ji.item_cost ELSE 0 END) AS material_cost

        FROM jobs j
        LEFT JOIN job_items ji ON ji.jobs_id=j.jobs_id

        WHERE j.job_start_date >= ?
        AND j.job_start_date <= ?

        GROUP BY j.jobs_id

    ) t
    ";

    $row = $this->db->query($sql,[
        $FiscalYearDate['start_date'],
        $FiscalYearDate['end_date']
    ])->getRowArray();

    $job_material = $row['material_cost'] ?? 0;

    /*
    =============================
    PURCHASE ORDER COST
    =============================
    */

    $builder = $this->db->table('purchase_orders');
    $builder->where('po_date >=',$FiscalYearDate['start_date']);
    $builder->where('po_date <=',$FiscalYearDate['end_date']);

    $po_result = $builder->get()->getResultArray();

    $po_total = 0;

    foreach($po_result as $values){

        $items = $this->get_po_items($values['poid']);

        $item_total = 0;

        foreach($items as $itm){
            $item_total += $itm['item_total'];
        }

        $tax = ($values['tax_applied']==1) ? ($item_total*13)/100 : 0;

        $po_total += $item_total + $tax + $values['shipping_amount'] - $values['discount_amount'];
    }

    return $job_material + $po_total;
}

    function get_po_items($poid){
	  return $this->db->table('poitems')->where('poid', $poid)->get()->getResultArray();   	   
   }
   
 public function total_received($FiscalYearDate)
{
    $sql = "
        SELECT COALESCE(SUM(ph.amount_received), 0) AS total_received
        FROM payment_history ph
        INNER JOIN jobs j ON j.jobs_id = ph.jobs_id
        WHERE j.job_start_date >= ?
        AND j.job_start_date <= ?
    ";

    $row = $this->db->query($sql, [
        $FiscalYearDate['start_date'],
        $FiscalYearDate['end_date']
    ])->getRowArray();

    return $row['total_received'] ?? 0;
}
    
    function job_has_items($jobid){
        $builder = $this->db->table('job_items');
         $builder->where('jobs_id',$jobid);
          $row  = $builder->get()->getRowArray();
          if($row)
             return "1";
             else
             return "0";
    }
    
    
    function labour_cost($FiscalYearDate){

    $sql = "

    SELECT SUM(t.labour_cost) AS labour_cost

    FROM (

        SELECT 
            j.jobs_id,
            SUM(CASE WHEN ji.item_name='Labour Cost'
                     THEN ji.item_cost ELSE 0 END) AS labour_cost

        FROM jobs j
        LEFT JOIN job_items ji ON ji.jobs_id=j.jobs_id

        WHERE j.job_start_date >= ?
        AND j.job_start_date <= ?

        GROUP BY j.jobs_id

    ) t
    ";

    $row = $this->db->query($sql,[
        $FiscalYearDate['start_date'],
        $FiscalYearDate['end_date']
    ])->getRowArray();

    return $row['labour_cost'] ?? 0;
}
    
    function profit(){
        $builder = $this->db->table('jobs');     
        $builder->select('SUM(final_amount) as gross_collection');      
        $row  = $builder->get()->getRowArray();
        return  $row['gross_collection']; 
        
    }
   
   function customer_info($customer_id){
	  return $this->db->table('customers')->where('customer_id', $customer_id)->get()->getRowArray();   	   
   }
   
   function staff_info($account_id){
	  return $this->db->table('users')->where('account_id', $account_id)->get()->getRowArray();   	   
   }
   
    public function get_job_labour_cost($job_id)
{
    $sql = "

    SELECT SUM(t.labour_cost) AS labour_cost

    FROM (

        SELECT 
            j.jobs_id,

            SUM(
                CASE 
                    WHEN ji.item_name='Labour Cost'
                    THEN ji.item_cost 
                    ELSE 0 
                END
            ) AS labour_cost

        FROM jobs j
        LEFT JOIN job_items ji ON ji.jobs_id=j.jobs_id

        WHERE j.jobs_id = ?

        GROUP BY j.jobs_id

    ) t
    ";

    $row = $this->db->query($sql,[$job_id])->getRowArray();

    return $row['labour_cost'] ?? 0;
}


public function get_job_material_cost($job_id)
{

    /*
    =============================
    JOB MATERIAL COST
    =============================
    */

    $sql = "

    SELECT SUM(
        CASE 
            WHEN ji.item_name='Material Cost'
            THEN ji.item_cost
            ELSE 0
        END
    ) AS material_cost

    FROM job_items ji

    WHERE ji.jobs_id = ?
    ";

    $row = $this->db->query($sql,[$job_id])->getRowArray();

    $job_material = $row['material_cost'] ?? 0;



    /*
    =============================
    PURCHASE ORDER COST
    =============================
    */

    $sql = "

    SELECT 

        SUM(
            poi.item_total
            +
            (CASE WHEN po.tax_applied=1 THEN (poi.item_total*13)/100 ELSE 0 END)
            +
            po.shipping_amount
            -
            po.discount_amount
        ) AS po_total

    FROM purchase_orders po
    JOIN poitems poi ON poi.poid = po.poid

    WHERE po.jobid = ?
    ";

    $row = $this->db->query($sql,[$job_id])->getRowArray();

    $po_total = $row['po_total'] ?? 0;



    return $job_material + $po_total;
}

 public function latest_jobs($limit)
{
    $sql = "

    SELECT 
        j.jobs_id,
        j.customer_id,
        j.job_assigned_to,
        j.work_details,
        j.customer_location,
        j.job_status,
        j.entry_time,
        j.job_start_date,
        j.job_completion_date,
        j.hst_applied,
        j.advanced_payment,
        j.remarks,

        c.full_name AS customer_name,

        -- ✅ TOTAL JOB PAYMENT
        SUM(CASE 
            WHEN ji.item_name = 'Total Job Payment' 
            THEN ji.item_cost ELSE 0 
        END) AS job_total,

        -- ✅ LABOUR COST
        SUM(CASE 
            WHEN ji.item_name = 'Labour Cost' 
            THEN ji.item_cost ELSE 0 
        END) AS labour_cost,

        -- ✅ MATERIAL COST
        SUM(CASE 
            WHEN ji.item_name = 'Material Cost' 
            THEN ji.item_cost ELSE 0 
        END) AS material_cost,

        -- ✅ RECEIVED PAYMENT
        COALESCE(SUM(ph.amount_received),0) AS total_collected

    FROM jobs j

    LEFT JOIN customers c ON c.customer_id = j.customer_id

    LEFT JOIN job_items ji ON ji.jobs_id = j.jobs_id

    LEFT JOIN payment_history ph ON ph.jobs_id = j.jobs_id

    WHERE j.job_status = 'open'

    GROUP BY j.jobs_id

    ORDER BY j.entry_time DESC

    LIMIT $limit
    ";

    $result = $this->db->query($sql)->getResultArray();

    $final = [];

    foreach($result as $row){

        // ✅ HST
        $hst_amount = 0;
        if($row['hst_applied']){
            $hst_amount = ($row['job_total'] * 13) / 100;
        }

        // ✅ FINAL AMOUNT
        $final_amount = $row['job_total'] + $hst_amount;

        // ✅ PENDING
        $pending = $final_amount - $row['total_collected'];

        // ✅ PROFIT
        $profit = $row['job_total'] - ($row['labour_cost'] + $row['material_cost']);

        // ✅ STAFF NAMES (minimal query, acceptable)
        $staff_ids = explode(",", trim($row['job_assigned_to'], ","));
        $staff_names = '';

        if($staff_ids){
            $users = $this->db->table('users')
                ->select('account_id, first_name, last_name')
                ->whereIn('account_id', $staff_ids)
                ->get()->getResultArray();

            foreach($users as $u){
                $staff_names .= ucfirst($u['first_name']).' '.ucfirst($u['last_name']).'<br>';
            }
        }

        // ✅ FINAL DATA PACK
        $row['job_total']       = $row['job_total'];
        $row['hst_amount']      = $hst_amount;
        $row['final_amount']    = $final_amount;
        $row['total_pending']   = $pending;
        $row['profit']          = $profit;
        $row['staf_names']      = $staff_names;
        $row['job_has_items']   = ($row['job_total'] > 0) ? 1 : 0;

        $final[] = $row;
    }

    return $final;
}
   
   public function total_customers(){
     
	   return $this->db->table('customers')->countAllResults();
	}	
 
  public function total_quotations(){
		return $this->db->table('quotations')->countAllResults();
	}
	
  public function total_staff(){
		return $this->db->table('users')->where('account_type','3')->countAllResults();
	}
	


  function array_sort_by_column(&$arr, $col, $dir = SORT_DESC) {
     $sort_col = array();
     foreach ($arr as $key => $row) {
        $sort_col[$key] = $row[$col];
      }
     array_multisort($sort_col, $dir, $arr);
     return $arr;
    }


}