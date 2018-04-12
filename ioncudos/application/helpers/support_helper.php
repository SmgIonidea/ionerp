<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('contact_support'))
{
     function contact_support($to, $from, $subject,$body,$number) {
		$CI =& get_instance();
		$CI->load->helper('email');
		$To = $to;
		$subject = $subject;
		$CI->email->set_newline("\r\n");
		$cell = "Cell:".$number;
	    $body =$body."\n".$cell;
		$CI->email->set_newline("\r\n");
		$CI->email->from('iioncudos@gmail.com', 'IonCUDOS');
		$CI->email->to($To);
		$CI->email->subject($subject);
		$CI->email->message($body);
		$CI->email->send();
		echo $CI->email->print_debugger();
		
	}
	
	 function adequacy_report($to,$cc, $from, $subject,$body,$file) {
		$CI =& get_instance();
		$CI->load->helper('email');
		$To = $to;
		$CC = $cc;
		$subject = $subject;
		$CI->email->set_newline("\r\n");
	    $body =$body;
		$CI->email->set_newline("\r\n");
		$CI->email->from('iioncudos@gmail.com', 'IonCUDOS');
		$CI->email->to($To);
		$CI->email->cc($CC);
		$CI->email->subject($subject);
		$CI->email->message($body);
		$CI->email->attach($file);
		if ( ! $CI->email->send()){
			return false;
		}
		return true;
		
		//echo $CI->email->print_debugger();
		
	}
}
