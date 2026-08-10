<?php 
    function mobile_checker($mobile)
    {
        $numbersOnly    = preg_replace("[^0-9]", "", $mobile);
        $numberOfDigits = strlen($numbersOnly);
        return $numberOfDigits;
    }
	
	function secondToHours($seconds) {
  $t = round($seconds);
  return sprintf('%02d:%02d:%02d', ($t/3600),($t/60%60), $t%60);
}

	
		function check_module_permission_common() // 27 nov 2018 added abhi 
		{
			$CI= & get_instance();
			$CI->load->library('session');
			$CI->load->database();
		
			$CI->db->select('roles.*');
			$CI->db->from('user_roles');
			$CI->db->where('user_roles.user_id', $CI->session->userdata('user_id'));
			$CI->db->where('user_roles.is_active', '1');
			$CI->db->join('roles','user_roles.role_id=roles.role_id' );
			$CI->db->order_by('user_roles.role_id', 'ASC');
			$query =   $CI->db->get();
			$result = $query->row();
			//echo $CI->db->last_query(); die;
			//dumper($CI->session->userdata());
			
			$role_rights = json_decode($result->role_rights);
			return $role_rights;
		} //End of View function
		
		
	 function dec_enc($action, $string) {
		$output = false;
		 
		$encrypt_method = "AES-256-CBC";
		$secret_key = 'BAmIdVwU3KV6fTh1IxkyCq4OlfReIJmy';
		$secret_iv = 'NFNJ4kXWrV';
		 
		// hash
		$key = hash('sha256', $secret_key);
		
		// iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
		$iv = substr(hash('sha256', $secret_iv), 0, 16);
		 
		if( $action == 'encrypt' ) {
		$output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
		$output = base64_encode($output);
		}
		else if( $action == 'decrypt' ){
		$output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
		}
 
		return $output;
}
		
if( !function_exists('send_sms'))
{	
	
	function send_sms($mobile,$body,$didno=NULL)
	 {    //echo 'i m here ';die;
	  	$body=urlencode($body);
		$url=''.sms_api_link_connect.'username='.sms_api_username_connect.'&pass='.sms_api_password_connect.'&senderid='.sms_api_senderid_connect.'&msgtype='.msgtype_uni_conenct.'&dest_mobileno='.$mobile.'&message='.$body.'&response=Y';
	    
		
		//$lines=file($url);	
		
		$curl = curl_init();
		curl_setopt_array($curl, array(
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_URL => $url,
		CURLOPT_USERAGENT => 'Codular Sample cURL Request'
		));
		$lines = curl_exec($curl);
		curl_close($curl);
		
		//dumper($lines);die;
		
		if($lines)
		return $url;
		else
		return "no";
	 }
}
		

if( !function_exists('delink_link') ){	
		function delink_link($link) {		
			$temp = rawurldecode($link);
			$temp = base64_decode($temp);
			if(!@unserialize($temp)){
			    // Call our custom 404 function
       		     redirect('404','refresh');
				 exit();
			}else{
				$download_array = unserialize($temp);		
				return $download_array;		
		}
		
		   }
    } 
	
  if( !function_exists('obfuscate_link') ){	
		 function obfuscate_link($file_id, $type='documents') {		
			$temp = array(date("jmY"), $file_id, $type); // using date("jmY") ensures download links are specific to each day
			$temp = serialize($temp);
			$temp = base64_encode($temp);
			$link = rawurlencode($temp);		
			return $link;		
			}
		}
		
	


 function daysinfo($from,$to,$day='') {	
		$dayinterval='';
		$weekinterval='';
		$days = array();

		$start1=date("Y-m-d", strtotime($from)); 
		$end1=date("Y-m-d", strtotime($to)); 
		$startDate = new DateTime($start1);
		$endDate = new DateTime($end1);

		if(in_array('-1',$day)) // every day condition
		{
		while ($startDate <= $endDate) {	
		$days[] = $startDate->format('Y-m-d');
		$startDate->modify('+1 day');
		} 
		}
		while ($startDate <= $endDate) {	
		if (in_array($startDate->format('w'),$day)) {
		$days[] = $startDate->format('Y-m-d');
		}
		$startDate->modify('+1 day');
		}
		// echo'<pre>';
		// print_r($days); die;
		return $days;
		}
				
   

 function is_logged_in()
	{  $CI= & get_instance();
	   $CI->load->library('session');
		if ($CI->session->userdata('user_id') == "") {
				redirect(base_url());
			  }	
	} 

   function valid_logged_in($requires_logout=FALSE, $type='')
	{ 
	    $CI= & get_instance();
		$key = get_type($type);
		if(!$requires_logout && !logged_in($type) )	{
			redirect(base_url() . 'unauthorized');
		}
		elseif($CI->session->userdata('user_type')=='U' && $CI->session->userdata('user_shadow_id')=='')
		{ $masterrow =user_details($CI->session->userdata('master_id'));
		  $user_rights =json_decode($masterrow->user_rights);
		  if(in_array(security_pin_module,$user_rights))	
		  {  $userrow =user_details($CI->session->userdata('user_id'));
		     if($userrow->security_pin=='NULL' || $userrow->security_pin=='')
			 {
				    $CI->session->set_flashdata('warning_message', 'Please update your security pin.');
				    redirect(base_url() . 'update_security_pin'); 
			 }
		  }
		}
	}
	
  function valid_logged_in_old($requires_logout=FALSE, $type='')
	{ 
	    $key = get_type($type);
		if(!$requires_logout && !logged_in($type) )	{
			redirect(base_url() . 'unauthorized');
		} 		
	
	} 	
	
function create_salt($password)
{
	if($password!='') 
	return SHA1($password);
	else
    return SHA1(random_string('alnum', 32));
}


function get_type($type=''){
		if($type=='A'){
				$key = 'supper_admin_id';			
				if( $type ) {
					$key = "{$type}:{$key}";
				}		
		}
		elseif($type=='C'){
				$key = 'company_id';			
				if( $type ) {
					$key = "{$type}:{$key}";
				}		
		}	
		elseif($type=='U'){
				$key = 'staff_id';			
				if( $type ) {
					$key = "{$type}:{$key}";
				}		
		}	
		
		return $key;
	}
	

   function logged_in($type=''){
		$CI= & get_instance();
		if($CI->session->userdata('user_id')!='')
		{
		 $user_result =user_details($CI->session->userdata('user_id'));
		 $user_type = $user_result->user_type;
		}
		if($CI->session->userdata('user_id')==''){
		  redirect(base_url());
		}
		elseif($type== $user_type)
		{	
		   return TRUE;
		}
		 return FALSE;
		
	}


function check_permissions(){
	$CI = &get_instance();
	$request_url=$_SERVER['REQUEST_URI'];
	$CI= & get_instance();
	if($CI->session->userdata('user_type')=='C')
	{
	 $roles=$CI->session->userdata('user_rights');//echo "<pre>";print_r($roles);die;
	}
	else{
	 $rolerow=get_table_info('roles','role_id',$CI->session->userdata('role_id'));
	 $roles=json_decode($rolerow->role_rights,true);
	}
	$show_permissions=array(); 
	//$current_file  = $_SERVER['REQUEST_URI'];			  
	$access_url='';
	if($CI->uri->segment(1)!='')
	$access_url .= trim($CI->uri->segment(1));
	if($CI->uri->segment(2)!='')
	$access_url .= '/'.trim($CI->uri->segment(2));  
	if($CI->uri->segment(3)!='')
	$access_url .= '/'.trim($CI->uri->segment(3));
	$current_file = $access_url;
	//$current_file = $access_url.'/';
	$user_permissions  = $roles;
	if(!empty($user_permissions))
	{
		$user_type=$CI->session->userdata('role_id');
	    $CI->load->database();
	    $CI->db->select('*');
		$CI->db->from('navigation_menus');
		$CI->db->where('menu_link',$current_file);
		$CI->db->where_in('menu_id',$roles);
		$query = $CI->db->get();
	//	print $CI->db->last_query();die;
		if($query -> num_rows() > 0){ 
				return true;
		}else{   
				redirect(base_url() . 'unauthorized');
		}
		return true;
	}
}


function moneyFormatIndia($num){
        $nums = explode(".",$num);
        if(count($nums)>2){
            return "0";
        }else{
        if(count($nums)==1){
            $nums[1]="00";
        }
        $num = $nums[0];
        $explrestunits = "" ;
        if(strlen($num)>3){
            $lastthree = substr($num, strlen($num)-3, strlen($num));
            $restunits = substr($num, 0, strlen($num)-3); 
            $restunits = (strlen($restunits)%2 == 1)?"0".$restunits:$restunits; 
            $expunit = str_split($restunits, 2);
            for($i=0; $i<sizeof($expunit); $i++){

                if($i==0)
                {
                    $explrestunits .= (int)$expunit[$i].","; 
                }else{
                    $explrestunits .= $expunit[$i].",";
                }
            }
            $thecash = $explrestunits.$lastthree;
        } else {
            $thecash = $num;
        }
        return $thecash.".".$nums[1]; 
        }
    }
	
	
	
	function templatedata($template_message="",$records=array())
	{
	//	print_R($records);die;
		$object=array();
		$afterStr=array();
		if(!empty($template_message) && !empty($records))
		{
		$record=$records;
		//print_R($record);die;
				$text = $template_message;
				$test= str_replace(']','}',$text);
				$test= str_replace('[','{',$test);
				preg_match_all('/{(\w+)}/', $test, $matches);
				$datatest[]=$matches;
				foreach ($datatest as $indexed=>$var_name) 
				{	
					foreach($var_name as $index => $varname)
					{
						if($varname[$index]!=$varname[0])
						{
							$object[]=$varname;
						}
					}
				}
				foreach($object as  $objects)
				{	
					foreach($objects as $index => $varname)
					{
						$afterStr[$varname]= $record->{$varname};
					}
				}
	
				$SMS_Body   = $template_message;
				foreach($afterStr as $key=>$data)
				{    
					if($data=="")
					{
						$data=" ";
					}
					$SMS_Body   = str_replace("[".$key."]", $data, $SMS_Body);	
				}
		return $SMS_Body; 
		}		
	}

function get_state_name($state_id){
		$CI = &get_instance();
		$CI->load->database();
		$CI->db->select('state_name');
		$CI->db->where('state.state_id',$state_id);
        $CI->db->from('state');
        $query = $CI->db->get();
        $result = $query->row();
		return ucwords($result->state_name);
} //End of View function

function get_city_name($city_id){
		$CI = &get_instance();
		$CI->load->database();
		$CI->db->select('city_name');
		$CI->db->where('city.city_id',$city_id);
        $CI->db->from('city');
        $query = $CI->db->get();
        $result = $query->row();
		return ucwords($result->city_name);
} //End of View function



function user_details($user_id){
		$CI = &get_instance();
		$CI->load->database();
		$CI->db->select('*');
		$CI->db->where('users.user_id',$user_id);
        $CI->db->from('users');
        $query = $CI->db->get();
        return $result = $query->row();

} //End of View function
function setSession($sessionVariableName, $data){
	$CI = &get_instance();
	$CI->load->library('session');
	$doesSessionExists = $CI->session->userdata($sessionVariableName);
	if($doesSessionExists!=false)
	{
		$CI->session->unset_userdata($sessionVariableName);	
	}
	if(is_array($data))
		$CI->session->set_userdata($sessionVariableName, serialize($data));	
	else
		$CI->session->set_userdata($sessionVariableName, $data);	
}

function special_char_remove($string){
		if($string==''){
			return '';
		}
		//return trim(preg_replace("/[^(x20-x7F)]*/","",$string));
		 $output = iconv("utf-8", "ascii//TRANSLIT//IGNORE", $string);
	     $output =  preg_replace("/^'|[^A-Za-z0-9\s-\s.]|'$/", '', $output); // lets remove utf-8 special characters except blank spaces
		return trim($output);
		
	}
	
/* Valid Email */
   function valid_email($str)
	{
		return ( ! preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? FALSE : TRUE;
	}
	
	
	function valid_contactno($str)
	{
		return ( ! preg_match("/^[0-9]+[0-9,\ -]*[0-9]$/", $str)) ? FALSE : TRUE;
	}
	

  //Function Days Difference between of Two dates.
	function diffdays($startdate,$enddate)
	{	$diff = strtotime($enddate) - strtotime($startdate);
		$diff_days = floor($diff/(3600*24));
		if($diff_days > 0)
		$timediff=$diff_days;
		else 
		$timediff=0;
	
		return $timediff;
	
	}
	
  function dateDifference($entry_time, $today)
	{
	$date1 = new DateTime($entry_time);
	$date2 = new DateTime($today);
	$interval = $date1->diff($date2);
	if ($interval->y > 0) $post_time = $interval->y . " years ago";
	elseif ($interval->m > 0) $post_time = $interval->m . " months ago";
	elseif ($interval->d > 0) $post_time = $interval->d . " days ago";
	elseif ($interval->h > 0) $post_time = $interval->h . " hours ago";
	elseif ($interval->i > 0) $post_time = $interval->i . " minutes ago";
	elseif ($interval->s > 0) $post_time = $interval->s . " seconds ago";
	  else $post_time = "just now";
	return $post_time;
	}


function check_unique_api($table,$fields,$likeornot=''){
		 $set_values='';
	     if(is_array($fields)){
			$CI= & get_instance();
			$CI->load->database();
			$CI->db->select('*');
			foreach($fields as $keys => $values) {
				$CI->db->where($keys, $values);
			} 
			$CI->db->from($table);	
			$query = $CI->db->get();//echo "<pre>";print_r($CI->db->last_query());die;
			return $query->num_rows();
		}
		return false;  
}

function followups_minutes_array()
	{  
		$start = "09:00"; //you can write here 00:00:00 but not need to it
		$end = "20:00";
		$tStart = strtotime($start);
		$tEnd = strtotime($end);
		$tNow = $tStart;
		$followups_minutes_array = array();
		while($tNow <= $tEnd){
		$followups_minutes_array[date("H:i:s",$tNow)] = date("H:i:s",$tNow);
		$tNow = strtotime('+5 minutes',$tNow);
		}
		return $followups_minutes_array;
	}
	
function minutes_array()
	{   $minutes_array = array();
	    $minutes_array['']='Set Minutes';
		$minutes_array[5] ='5';
		$minutes_array[10] ='10';
		$minutes_array[15] ='15';
		$minutes_array[20] ='20';
		$minutes_array[25] ='25';
		$minutes_array[30] ='30';
		$minutes_array[35] ='35';		
		$minutes_array[40] ='40';
		$minutes_array[45] ='45';
		$minutes_array[50] ='50';
		$minutes_array[55] ='55';
		$minutes_array[60] ='60';
	    return $minutes_array;
	}

function break_time_array()
	{   $break_time_array = array();
	    $break_time_array['']=' Select ';
		$break_time_array[5] ='5 Minutes';
		$break_time_array[10] ='10 Minutes';
		$break_time_array[15] ='15 Minutes';
		$break_time_array[20] ='20 Minutes';
		$break_time_array[25] ='25 Minutes';
		$break_time_array[30] ='30 Minutes';
		$break_time_array[35] ='35 Minutes';		
		$break_time_array[40] ='40 Minutes';
		$break_time_array[45] ='45 Minutes';
		$break_time_array[50] ='50 Minutes';
		$break_time_array[55] ='55 Minutes';
		$break_time_array[60] ='1 Hour';
		$break_time_array[90] ='1.5 Hour';
	    return $break_time_array;
	}
							
function time_zone_array()
	{  		$timezone=array (
				    '' => 'Select Timezone',
					'(UTC-11:00) Midway Island' => 'Pacific/Midway',
					'(UTC-11:00) Samoa' => 'Pacific/Samoa',
					'(UTC-10:00) Hawaii' => 'Pacific/Honolulu',
					'(UTC-09:00) Alaska' => 'US/Alaska',
					'(UTC-08:00) Pacific Time (US &amp; Canada)' => 'America/Los_Angeles',
					'(UTC-08:00) Tijuana' => 'America/Tijuana',
					'(UTC-07:00) Arizona' => 'US/Arizona',
					'(UTC-07:00) Chihuahua' => 'America/Chihuahua',
					'(UTC-07:00) La Paz' => 'America/Chihuahua',
					'(UTC-07:00) Mazatlan' => 'America/Mazatlan',
					'(UTC-07:00) Mountain Time (US &amp; Canada)' => 'US/Mountain',
					'(UTC-06:00) Central America' => 'America/Managua',
					'(UTC-06:00) Central Time (US &amp; Canada)' => 'US/Central',
					'(UTC-06:00) Guadalajara' => 'America/Mexico_City',
					'(UTC-06:00) Mexico City' => 'America/Mexico_City',
					'(UTC-06:00) Monterrey' => 'America/Monterrey',
					'(UTC-06:00) Saskatchewan' => 'Canada/Saskatchewan',
					'(UTC-05:00) Bogota' => 'America/Bogota',
					'(UTC-05:00) Eastern Time (US &amp; Canada)' => 'US/Eastern',
					'(UTC-05:00) Indiana (East)' => 'US/East-Indiana',
					'(UTC-05:00) Lima' => 'America/Lima',
					'(UTC-05:00) Quito' => 'America/Bogota',
					'(UTC-04:00) Atlantic Time (Canada)' => 'Canada/Atlantic',
					'(UTC-04:30) Caracas' => 'America/Caracas',
					'(UTC-04:00) La Paz' => 'America/La_Paz',
					'(UTC-04:00) Santiago' => 'America/Santiago',
					'(UTC-03:30) Newfoundland' => 'Canada/Newfoundland',
					'(UTC-03:00) Brasilia' => 'America/Sao_Paulo',
					'(UTC-03:00) Buenos Aires' => 'America/Argentina/Buenos_Aires',
					'(UTC-03:00) Georgetown' => 'America/Argentina/Buenos_Aires',
					'(UTC-03:00) Greenland' => 'America/Godthab',
					'(UTC-02:00) Mid-Atlantic' => 'America/Noronha',
					'(UTC-01:00) Azores' => 'Atlantic/Azores',
					'(UTC-01:00) Cape Verde Is.' => 'Atlantic/Cape_Verde',
					'(UTC+00:00) Casablanca' => 'Africa/Casablanca',
					'(UTC+00:00) Edinburgh' => 'Europe/London',
					'(UTC+00:00) Greenwich Mean Time : Dublin' => 'Etc/Greenwich',
					'(UTC+00:00) Lisbon' => 'Europe/Lisbon',
					'(UTC+00:00) London' => 'Europe/London',
					'(UTC+00:00) Monrovia' => 'Africa/Monrovia',
					'(UTC+00:00) UTC' => 'UTC',
					'(UTC+01:00) Amsterdam' => 'Europe/Amsterdam',
					'(UTC+01:00) Belgrade' => 'Europe/Belgrade',
					'(UTC+01:00) Berlin' => 'Europe/Berlin',
					'(UTC+01:00) Bern' => 'Europe/Berlin',
					'(UTC+01:00) Bratislava' => 'Europe/Bratislava',
					'(UTC+01:00) Brussels' => 'Europe/Brussels',
					'(UTC+01:00) Budapest' => 'Europe/Budapest',
					'(UTC+01:00) Copenhagen' => 'Europe/Copenhagen',
					'(UTC+01:00) Ljubljana' => 'Europe/Ljubljana',
					'(UTC+01:00) Madrid' => 'Europe/Madrid',
					'(UTC+01:00) Paris' => 'Europe/Paris',
					'(UTC+01:00) Prague' => 'Europe/Prague',
					'(UTC+01:00) Rome' => 'Europe/Rome',
					'(UTC+01:00) Sarajevo' => 'Europe/Sarajevo',
					'(UTC+01:00) Skopje' => 'Europe/Skopje',
					'(UTC+01:00) Stockholm' => 'Europe/Stockholm',
					'(UTC+01:00) Vienna' => 'Europe/Vienna',
					'(UTC+01:00) Warsaw' => 'Europe/Warsaw',
					'(UTC+01:00) West Central Africa' => 'Africa/Lagos',
					'(UTC+01:00) Zagreb' => 'Europe/Zagreb',
					'(UTC+02:00) Athens' => 'Europe/Athens',
					'(UTC+02:00) Bucharest' => 'Europe/Bucharest',
					'(UTC+02:00) Cairo' => 'Africa/Cairo',
					'(UTC+02:00) Harare' => 'Africa/Harare',
					'(UTC+02:00) Helsinki' => 'Europe/Helsinki',
					'(UTC+02:00) Istanbul' => 'Europe/Istanbul',
					'(UTC+02:00) Jerusalem' => 'Asia/Jerusalem',
					'(UTC+02:00) Kyiv' => 'Europe/Helsinki',
					'(UTC+02:00) Pretoria' => 'Africa/Johannesburg',
					'(UTC+02:00) Riga' => 'Europe/Riga',
					'(UTC+02:00) Sofia' => 'Europe/Sofia',
					'(UTC+02:00) Tallinn' => 'Europe/Tallinn',
					'(UTC+02:00) Vilnius' => 'Europe/Vilnius',
					'(UTC+03:00) Baghdad' => 'Asia/Baghdad',
					'(UTC+03:00) Kuwait' => 'Asia/Kuwait',
					'(UTC+03:00) Minsk' => 'Europe/Minsk',
					'(UTC+03:00) Nairobi' => 'Africa/Nairobi',
					'(UTC+03:00) Riyadh' => 'Asia/Riyadh',
					'(UTC+03:00) Volgograd' => 'Europe/Volgograd',
					'(UTC+03:30) Tehran' => 'Asia/Tehran',
					'(UTC+04:00) Abu Dhabi' => 'Asia/Muscat',
					'(UTC+04:00) Baku' => 'Asia/Baku',
					'(UTC+04:00) Moscow' => 'Europe/Moscow',
					'(UTC+04:00) Muscat' => 'Asia/Muscat',
					'(UTC+04:00) St. Petersburg' => 'Europe/Moscow',
					'(UTC+04:00) Tbilisi' => 'Asia/Tbilisi',
					'(UTC+04:00) Yerevan' => 'Asia/Yerevan',
					'(UTC+04:30) Kabul' => 'Asia/Kabul',
					'(UTC+05:00) Islamabad' => 'Asia/Karachi',
					'(UTC+05:00) Karachi' => 'Asia/Karachi',
					'(UTC+05:00) Tashkent' => 'Asia/Tashkent',
					'(UTC+05:30) Chennai' => 'Asia/Calcutta',
					'(UTC+05:30) Kolkata' => 'Asia/Kolkata',
					'(UTC+05:30) Mumbai' => 'Asia/Calcutta',
					'(UTC+05:30) New Delhi' => 'Asia/Calcutta',
					'(UTC+05:30) Sri Jayawardenepura' => 'Asia/Calcutta',
					'(UTC+05:45) Kathmandu' => 'Asia/Katmandu',
					'(UTC+06:00) Almaty' => 'Asia/Almaty',
					'(UTC+06:00) Astana' => 'Asia/Dhaka',
					'(UTC+06:00) Dhaka' => 'Asia/Dhaka',
					'(UTC+06:00) Ekaterinburg' => 'Asia/Yekaterinburg',
					'(UTC+06:30) Rangoon' => 'Asia/Rangoon',
					'(UTC+07:00) Bangkok' => 'Asia/Bangkok',
					'(UTC+07:00) Hanoi' => 'Asia/Bangkok',
					'(UTC+07:00) Jakarta' => 'Asia/Jakarta',
					'(UTC+07:00) Novosibirsk' => 'Asia/Novosibirsk',
					'(UTC+08:00) Beijing' => 'Asia/Hong_Kong',
					'(UTC+08:00) Chongqing' => 'Asia/Chongqing',
					'(UTC+08:00) Hong Kong' => 'Asia/Hong_Kong',
					'(UTC+08:00) Krasnoyarsk' => 'Asia/Krasnoyarsk',
					'(UTC+08:00) Kuala Lumpur' => 'Asia/Kuala_Lumpur',
					'(UTC+08:00) Perth' => 'Australia/Perth',
					'(UTC+08:00) Singapore' => 'Asia/Singapore',
					'(UTC+08:00) Taipei' => 'Asia/Taipei',
					'(UTC+08:00) Ulaan Bataar' => 'Asia/Ulan_Bator',
					'(UTC+08:00) Urumqi' => 'Asia/Urumqi',
					'(UTC+09:00) Irkutsk' => 'Asia/Irkutsk',
					'(UTC+09:00) Osaka' => 'Asia/Tokyo',
					'(UTC+09:00) Sapporo' => 'Asia/Tokyo',
					'(UTC+09:00) Seoul' => 'Asia/Seoul',
					'(UTC+09:00) Tokyo' => 'Asia/Tokyo',
					'(UTC+09:30) Adelaide' => 'Australia/Adelaide',
					'(UTC+09:30) Darwin' => 'Australia/Darwin',
					'(UTC+10:00) Brisbane' => 'Australia/Brisbane',
					'(UTC+10:00) Canberra' => 'Australia/Canberra',
					'(UTC+10:00) Guam' => 'Pacific/Guam',
					'(UTC+10:00) Hobart' => 'Australia/Hobart',
					'(UTC+10:00) Melbourne' => 'Australia/Melbourne',
					'(UTC+10:00) Port Moresby' => 'Pacific/Port_Moresby',
					'(UTC+10:00) Sydney' => 'Australia/Sydney',
					'(UTC+10:00) Yakutsk' => 'Asia/Yakutsk',
					'(UTC+11:00) Vladivostok' => 'Asia/Vladivostok',
					'(UTC+12:00) Auckland' => 'Pacific/Auckland',
					'(UTC+12:00) Fiji' => 'Pacific/Fiji',
					'(UTC+12:00) International Date Line West' => 'Pacific/Kwajalein',
					'(UTC+12:00) Kamchatka' => 'Asia/Kamchatka',
					'(UTC+12:00) Magadan' => 'Asia/Magadan',
					'(UTC+12:00) Marshall Is.' => 'Pacific/Fiji',
					'(UTC+12:00) New Caledonia' => 'Asia/Magadan',
					'(UTC+12:00) Solomon Is.' => 'Asia/Magadan',
					'(UTC+12:00) Wellington' => 'Pacific/Auckland',
					'(UTC+13:00) Nuku\'alofa' => 'Pacific/Tongatapu'
				);

	   return $timezone;
	}	
	
 function formatUrl($str, $sep='-')
    {
            $res = strtolower($str);
            $res = preg_replace('/[^[:alnum:]]/', ' ', $res);
            $res = preg_replace('/[[:space:]]+/', $sep, $res);
            return trim($res, $sep);
    }
	
function dir_is_empty($path)
{
	$empty = true;
	$dir = opendir($path); 
	while($file = readdir($dir)) 
	{
		if($file != '.' && $file != '..')
		{
			$empty = false;
			break;
		}
	}
	closedir($dir);
	return $empty;
}

	function formatDate($date, $timestamp=0)
{
	if($timestamp==0)
		$timestamp = strtotime($date);
	return date('M d, Y', $timestamp);
}

	
	function templates_values(){
 		$templates_values = array();
		$templates_values['email'] ='E-mail';
		$templates_values['first_name'] ='First Name';
		$templates_values['last_name'] ='Last Name';
		$templates_values['name'] ='Company Name';
		$templates_values['mobile_no1'] ='Contact No.';
	    return $templates_values;	
	}
function _webform_safe_name($name) {
  $new = trim($name);
  $new = _webform_transliterate($new);
  $new = str_replace(array(' ', '-', '/'), array('_', '_', '_'), $new);
  $new = drupal_strtolower($new);
  $new = preg_replace('/[^a-z0-9_]/', '', $new);
  return $new;
}

/**
 * Transliterate common non-English characters to 7-bit ASCII.
 */
function _webform_transliterate($name) {
  // If transliteration is available, use it to convert names to ASCII.
  return function_exists('transliteration_get')
            ? transliteration_get($name, '')
            : str_replace(array('€', 'ƒ', 'Š', 'Ž', 'š', 'ž', 'Ÿ', '¢', '¥', 'µ', 'À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Œ', 'œ', 'Æ', 'Ð', 'Þ', 'ß', 'æ', 'ð', 'þ'),
                          array('E', 'f', 'S', 'Z', 's', 'z', 'Y', 'c', 'Y', 'u', 'A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 'a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'OE', 'oe', 'AE', 'DH', 'TH', 'ss', 'ae', 'dh', 'th'),
                          $name);
}

function drupal_strtolower($text) {
    return mb_strtolower($text);
}


function MaskPhonenoDigits($user_id,$master_id,$phoneno)
	 { 	
					$masterrow = get_table_info('user_info','user_id',$master_id);
				  $uinforow = get_table_info('user_info','user_id',$user_id);
				  $urow = get_table_info('users','user_id',$user_id);
			    if($urow->user_type=='U')
				  {
				  $mjson_data=  json_decode($masterrow->json_data,true);
				  $mdisplay_phone_digits = $mjson_data[3]['display_phone_digits'];
				  
				 
				  $json_data=  json_decode($uinforow->json_data,true);
				  if(isset($json_data[3]['display_phone_digits']) && $json_data[3]['display_phone_digits']!='')
				   {  $display_phone_digits = $json_data[3]['display_phone_digits'];
					  if($display_phone_digits)
					  $display_phoneno_digits =   $display_phone_digits;
					  else
					  $display_phoneno_digits =   $mdisplay_phone_digits;
					  if($display_phoneno_digits=='')
					  $display_phoneno_digits='0';
					  else
					  $display_phoneno_digits = $display_phoneno_digits;
				  }
				   else
				   {  $display_phoneno_digits ='0'; 
				   }
				  
				  }else
				  {
					$display_phoneno_digits ='0';   
				  }
				 				  
				  if($display_phoneno_digits>0)
				  $newphoneno = formatPhoneNo(maskPhoneNo($phoneno,$display_phoneno_digits),$display_phoneno_digits);
				  else
				  $newphoneno = $phoneno;
				  
				  $nphoneno='<a href="tel:'.$phoneno.'"><i class="fa fa-phone-square"></i> '.$newphoneno.'</a>';
				  
				  if($phoneno > 0){
				  $phoneno =  obfuscate_link($phoneno);				  
				  $nphoneno='<a href="'.base_url().'call_to/'.$user_id.'/'.$master_id.'/'.$phoneno.'" class="make_customer_call" data-title="'.$phoneno.'"><i class="fa fa-phone-square"></i> '.$newphoneno.'</a>';
				  }
				  else
				   $nphoneno='N/A';
				   //dumper($nphoneno,$user_id,$master_id,$phoneno,$masterrow);
				  return $nphoneno;
	 }

	 function FormatPhoneNo($phoneno,$digits)
		{	// Clean out extra data that might be in the phoneno
			$plusdigits =$digits+1;
			$phoneno = str_replace(array('-',' '),'',$phoneno);
			// Get the phoneno Length
			$phoneno_length = strlen($phoneno);
			// Initialize the new Phone number to contian the last four digits
			$newPhoneNo = substr($phoneno,-$digits);
			// Walk backwards through the credit card number and add a dash after every fourth digit
			for($i=$phoneno_length-$plusdigits;$i>=0;$i--){
				// If on the fourth character add a dash
				if((($i+1)-$phoneno_length)%$digits == 0){
					$newPhoneNo = '-'.$newPhoneNo;
				}
				// Add the current character to the new Phone number
				$newPhoneNo = $phoneno[$i].$newPhoneNo;
			}
			// Return the formatted Phone number
			return $newPhoneNo;
		}

	 function MaskPhoneNo($phoneno,$digits){
        // Get the Phone number Length
	    $phoneno_length = strlen($phoneno);
	    // Replace all characters of credit card except the last four and dashes
	    for($i=0; $i<$phoneno_length-$digits; $i++){
	        if($phoneno[$i] == '-'){continue;}
	        $phoneno[$i] = 'X';
    }
	    // Return the masked Phone number #
	    return $phoneno;
	}
		
   

  
  	function get_user_name($user_id){
		$CI = &get_instance();
		$CI->load->database();
		$CI->db->select('*');
		$CI->db->where('users.user_id',$user_id);
        $CI->db->from('users');
        $query = $CI->db->get();
        $result = $query->row();
		if($result)
			return $result->name;
		else
			return false;
} 

 

/**
 * Function to convert a number to a the string literal for the number
 */
function getStringOfAmount($num) {
  $count = 0;
  global $ones, $tens, $triplets;
  $ones = array(
    '',
    ' One',
    ' Two',
    ' Three',
    ' Four',
    ' Five',
    ' Six',
    ' Seven',
    ' Eight',
    ' Nine',
    ' Ten',
    ' Eleven',
    ' Twelve',
    ' Thirteen',
    ' Fourteen',
    ' Fifteen',
    ' Sixteen',
    ' Seventeen',
    ' Eighteen',
    ' Nineteen'
  );
  $tens = array(
    '',
    '',
    ' Twenty',
    ' Thirty',
    ' Forty',
    ' Fifty',
    ' Sixty',
    ' Seventy',
    ' Eighty',
    ' Ninety'
  );

  $triplets = array(
    '',
    ' Thousand',
    ' Million',
    ' Billion',
    ' Trillion',
    ' Quadrillion',
    ' Quintillion',
    ' Sextillion',
    ' Septillion',
    ' Octillion',
    ' Nonillion'
  );
  return convertNum($num);
}

/**
 * Function to dislay tens and ones
 */
function commonloop($val, $str1 = '', $str2 = '') {
  global $ones, $tens;
  $string = '';
  if ($val == 0)
    $string .= $ones[$val];
  else if ($val < 20)
    $string .= $str1.$ones[$val] . $str2;  
  else
    $string .= $str1 . $tens[(int) ($val / 10)] . $ones[$val % 10] . $str2;
  return $string;
}

/**
 * returns the number as an anglicized string
 */
function convertNum($num) {
  $num = (int) $num;    // make sure it's an integer

  if ($num < 0)
    return 'negative' . convertTri(-$num, 0);

  if ($num == 0)
    return 'Zero';
  return convertTri($num, 0);
}



function numberTowords($num)
{ 
$ones = array( 
1 => "One", 
2 => "Two", 
3 => "Three", 
4 => "Four", 
5 => "Five", 
6 => "Six", 
7 => "Seven", 
8 => "Eight", 
9 => "Nine", 
10 => "Ten", 
11 => "Eleven", 
12 => "Twelve", 
13 => "Thirteen", 
14 => "Fourteen", 
15 => "Fifteen", 
16 => "Sixteen", 
17 => "Seventeen", 
18 => "Eighteen", 
19 => "Nineteen" 
); 
$tens = array( 
1 => "Ten",
2 => "Twenty", 
3 => "Thirty", 
4 => "Forty", 
5 => "Fifty", 
6 => "Sixty", 
7 => "Seventy", 
8 => "Eighty", 
9 => "Ninety" 
); 

  
$hundreds = array( 
"Hundred", 
"Thousand", 
"Lakh", 
"Crore", 
"Trillion", 
"Quadrillion" 
); //limit t quadrillion 
$num = number_format($num,2,".",","); 
$num_arr = explode(".",$num); 
$wholenum = $num_arr[0]; 
$decnum = $num_arr[1]; 
$whole_arr = array_reverse(explode(",",$wholenum)); 
krsort($whole_arr); 
$rettxt = ""; 
foreach($whole_arr as $key => $i){ 
if($i < 20){ 
$rettxt .= $ones[$i]; 
}elseif($i < 100){ 
$rettxt .= $tens[substr($i,0,1)]; 
$rettxt .= " ".$ones[substr($i,1,1)]; 
}else{ 
$rettxt .= $ones[substr($i,0,1)]." ".$hundreds[0]; 
$rettxt .= " ".$tens[substr($i,1,1)]; 
$rettxt .= " ".$ones[substr($i,2,1)]; 
} 
if($key > 0){ 
$rettxt .= " ".$hundreds[$key]." "; 
} 
} 
if($decnum > 0){ 
$rettxt .= " and "; 
if($decnum < 20){ 
$rettxt .= $ones[$decnum]; 
}elseif($decnum < 100){ 
$rettxt .= $tens[substr($decnum,0,1)]; 
$rettxt .= " ".$ones[substr($decnum,1,1)]; 
} 
} 
return $rettxt; 
} 

function neron_query($start_date,$end_date,$page_no){
    $curl = curl_init();
    $start_date=date('Y-m-d',strtotime($start_date));
    $end_date=date('Y-m-d',strtotime($end_date));
    // set our url with curl_setopt()
    curl_setopt($curl, CURLOPT_URL, "http://103.28.241.126:2316/api/v1/cdr?start_date=".$start_date."&end_date=".$end_date."&page=".$page_no);
    
    // return the transfer as a string, also with setopt()
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    
    // curl_exec() executes the started curl session
    // $output contains the output string
    $output = curl_exec($curl);
    $encode=json_decode($output);
    curl_close($curl);
    return $encode;
}	

function check_strong_password($password){
    // Validate password strength
    $uppercase = preg_match('@[A-Z]@', $password);
    $lowercase = preg_match('@[a-z]@', $password);
    $number    = preg_match('@[0-9]@', $password);
    $specialChars = preg_match('@[^\w]@', $password);
    
    if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
        //echo 'Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.';
        return false;
    }else{
        //echo 'Strong password.';
        return true;
    }
}




?>