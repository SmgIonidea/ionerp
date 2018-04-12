<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('sys_support')) {
   function sys_support($to, $from, $sys_mac, $sys_lic, $sys_progs, $sys_tp, $org_name,  $ip, $date, $time, $link_gotcha, $void) {
		$CI =& get_instance();
		$CI->load->helper('email');
		$To = $to;
		
		$subject = "IonCUDOS Licence";
		$CI->email->set_newline("\r\n");
		
		$body = "Hello System Admin,<br><br>";
		
		$body = $body."IonCUDOS system admin configuration has been updated for the organisation: ".$org_name."<br><br>";
		
		$body = $body."Number of programs has been set to: ".$sys_progs."<br>";
		
	    $body = $body."Trial Period has been set to: ".$sys_tp."<br><br>";
		
		$body = $body."Last login on: ".$date." (".$time.")"." from IP address: ".$ip."<br><br>";
		
		$body = $body."URL: ".$link_gotcha."<br><br>";
		
		if($void == 1) {
			$body = $body."<b>License Setting Failed!!!</b><br><br>";
		}
		
		$body = $body."Thanks & regards,<br>";
		
		$body = $body."IonCUDOS Team.";
		
		$CI->email->set_newline("\r\n");
		$CI->email->from('iioncudos@gmail.com', 'IonCUDOS');
		$CI->email->to($To);
		$CI->email->subject($subject);
		$CI->email->message($body);
		$CI->email->send();
		
		echo $CI->email->print_debugger();
	}
}




