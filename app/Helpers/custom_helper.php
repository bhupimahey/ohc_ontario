<?php 

function months_list(){
    $all_months = array();
    $all_months[''] ='Choose Month';
    for ($m=1; $m<=12; $m++) {
     $month  = date('F', mktime(0,0,0,$m, 1, date('Y')));
     
     if($m<=9)
     $show_m = '0'.$m;
     else
     $show_m = $m;
     $all_months[$show_m] = $month;
     }
   return $all_months; 
    
}
function get_client_ip() {    $ipaddress = '';    if (getenv('HTTP_CLIENT_IP'))        $ipaddress = getenv('HTTP_CLIENT_IP');    else if(getenv('HTTP_X_FORWARDED_FOR'))        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');    else if(getenv('HTTP_X_FORWARDED'))        $ipaddress = getenv('HTTP_X_FORWARDED');    else if(getenv('HTTP_FORWARDED_FOR'))        $ipaddress = getenv('HTTP_FORWARDED_FOR');    else if(getenv('HTTP_FORWARDED'))       $ipaddress = getenv('HTTP_FORWARDED');    else if(getenv('REMOTE_ADDR'))        $ipaddress = getenv('REMOTE_ADDR');    else        $ipaddress = 'UNKNOWN';    return $ipaddress;}


function UserTypes(){
    $UtypesArray= array();
    $UtypesArray['']= 'Choose';
    $UtypesArray['2'] ='Superadmin';
    $UtypesArray['4'] ='Subadmin';
    return $UtypesArray; 
}


function getAge($dob){
    $birth_date      = new DateTime($dob);
    $current_date    = new DateTime();
    $diff            = $birth_date->diff($current_date);
    $years           = $diff->y . " Y, " . $diff->m . " M ";
    return $years;   
  }  

function CalculateTime($times) {
        $i = 0;
        foreach ($times as $time) {
            sscanf($time, '%d:%d', $hour, $min);
            $i += $hour * 60 + $min;
        }

        if($h = floor($i / 60)) {
            $i %= 60;
        }

        return sprintf('%02d:%02d', $h, $i);
    }

function AmountInWords(float $amount)
{
   $amount_after_decimal = round($amount - ($num = floor($amount)), 2) * 100;
   // Check if there is any number after decimal
   $amt_hundred = null;
   $count_length = strlen($num);
   $x = 0;
   $string = array();
   $change_words = array(0 => '', 1 => 'One', 2 => 'Two',
     3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six',
     7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
     10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve',
     13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
     16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen',
     19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
     40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty',
     70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety');
    $here_digits = array('', 'Hundred','Thousand','Lakh', 'Crore');
    while( $x < $count_length ) {
      $get_divider = ($x == 2) ? 10 : 100;
      $amount = floor($num % $get_divider);
      $num = floor($num / $get_divider);
      $x += $get_divider == 10 ? 1 : 2;
      if ($amount) {
       $add_plural = (($counter = count($string)) && $amount > 9) ? 's' : null;
       $amt_hundred = ($counter == 1 && $string[0]) ? ' and ' : null;
       $string [] = ($amount < 21) ? $change_words[$amount].' '. $here_digits[$counter]. $add_plural.' 
       '.$amt_hundred:$change_words[floor($amount / 10) * 10].' '.$change_words[$amount % 10]. ' 
       '.$here_digits[$counter].$add_plural.' '.$amt_hundred;
        }
   else $string[] = null;
   }
   
   $implode_to_Rupees = implode('', array_reverse($string));
   $get_paise = ($amount_after_decimal > 0) ? "And " . ($change_words[$amount_after_decimal / 10] . " 
   " . $change_words[$amount_after_decimal % 10]) . ' Paise' : '';
   return ($implode_to_Rupees ? $implode_to_Rupees . 'Rupees ' : '') . $get_paise;
}

 

if(!function_exists('randomPassword')) {
      //generates a random password of length minimum 8 
//contains at least one lower case letter, one upper case letter,
// one number and one special character, 
//not including ambiguous characters like iIl|1 0oO 
	function randomPassword($len = 8) {

		//enforce min length 8
		if($len < 8)
			$len = 8;
		
		//define character libraries - remove ambiguous characters like iIl|1 0oO
		$sets     = array();
		$sets[]   = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
		$sets[]   = 'abcdefghjkmnpqrstuvwxyz';
		$sets[]   = '23456789';
		$sets[]   = '~!@#$%^&*(){}[],./?';
		$password = '';
		
		//append a character from each set - gets first 4 characters
		foreach ($sets as $set) {
			$password .= $set[array_rand(str_split($set))];
		   }

		//use all characters to fill up to $len
		while(strlen($password) < $len) {
			//get a random set
			$randomSet = $sets[array_rand($sets)];
			
			//add a random char from the random set
			$password .= $randomSet[array_rand(str_split($randomSet))]; 
		}
		
		//shuffle the password string before returning!
		return str_shuffle($password);
	}
}