<?php
/*
 * Description	:	Email schduler

 * Created		:	October 21st, 2015

 * Author		:	 Shivaraj B
----------------------------------------------------------------------------------------------*/
?>
<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Email_scheduler extends CI_Controller{

	 function __construct(){
		parent::__construct();
		$this->load->model('scheduler/email_schduler','',TRUE);
	}
        
/*
 * Name: index
 * @purpose: Function to Send Application Action Due email as well as to send Survey Bulk emails to the Students and Stakeholders.
 * @param : email IDs as string with delimiter <> & |.
 * @return : Email Success Message.  
 */
	function index(){
		$email_array = explode('|',EMAIL_ARRAY);
		if(!empty($email_array)){
			foreach($email_array as $e_array){
				$email_vals = explode('<>',$e_array);
				$config['protocol']    = 'smtp';
				$config['smtp_host']    = '74.125.68.109';
				//$config['smtp_host']    = 'smtp.gmail.com';
				$config['smtp_port']    = '465';
				$config['smtp_crypto'] = 'ssl';
				$config['smtp_timeout'] = '10';
				$config['smtp_user']    = $email_vals[0];
				$config['smtp_pass']    = $email_vals[1];
				$config['charset']    = 'utf-8';
				$config['wordwrap']    = 'false';
				$config['newline']    = "\r\n";
				$config['mailtype'] = 'html'; // or html
				$config['validation'] = TRUE; // bool whether to validate email or not 
				$config['from_email'] = 'ionscpdev@gmail.com';
				$config['from_name'] = 'Ioncudos';
				$config['subject'] = 'NBA/SAR Report';
				$config['message'] = 'Please find the NBA/SAR report attached';
				$config['export_path'] = './uploads/nba_reports/';
				$this->email->initialize($config); // Initializing Emails
				$counter = 0;
				$email_list = $this->email_schduler->get_schduled_emails_list();
					$data['email_list'] = $email_list;
					$val =  $this->ion_auth->user()->row();
					$org_name = $val->org_name->org_name; //organisation name
					foreach ($email_list as $value) {
						$status = $this->send_scheduled_email($value['from'],$value['to'],$value['subject'],$value['email_body'],$org_name);
						if($status){
							$this->email_schduler->update_email_status($value['email_details_id']);
						}
						$counter++;
						if($counter == 489){
							break;
						}else{
							continue;
						}
					}
			}
		}else{
				echo 'No Email IDs Present!!';
		}
		
		$this->load->view('scheduler/email_vw',$data);
	}

	function send_scheduled_email($from=null,$to=null,$subject=null,$body=null,$org_name='College'){
		$this->email->clear();
		$this->email->set_newline("\r\n");
		$this->email->from($from, COLLEGE_NAME);
		$this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($body);
		ini_set('memory_limit', -1);
		ini_set('max_execution_time', 0);
        if ($this->email->send()) {
			// echo '<br>pass'.$this->email->print_debugger();
            return true;
        } else {
			// echo '<br>fail'.$this->email->print_debugger();
            return false;
        }
	}
	
	function test_cron(){
        $this->email_schduler->test_cron();
    }
}