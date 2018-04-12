<?php 
class Email_library {

    private $CI;

    function __construct() {
        $this->CI = &get_instance();
        $this->CI->config->load('email');
	}
	public function email_error_handler($number, $message, $file, $line, $vars){
		log_message('error', $message);
	}
	
	public function send_email($email_id, $filepath){
		$this->CI->load->library('email');
		$from_email = $this->CI->config->item('from_email');
        $from_name = $this->CI->config->item('from_name');
		//$subject = $this->CI->lang->line('subject');
		$subject =  $this->CI->config->item('subject');
		$message =  $this->CI->config->item('message');	
		//$message = $this->CI->lang->line('message');	
		$this->CI->email->from($from_email, $from_name);
		$this->CI->email->to($email_id); 
		$this->CI->email->subject($subject);
		$this->CI->email->message($message);  
		$this->CI->email->attach($filepath);
		set_error_handler(array(&$this, 'email_error_handler'));
		if ( ! $this->CI->email->send()){
			$this->CI->email->clear(TRUE);
			return false;
		}
		$this->CI->email->clear(TRUE);
		return true;
	}
}
?>