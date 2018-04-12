<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Controller Logic for Department Adding, Editing and Disabling/Enabling operations performed through this file.	  
 * Modification History:
 * Date				Modified By				Description
 * 1-12-2014		Jevi V. G.       	Added file headers, function headers & comments. 
  ---------------------------------------------------------------------------------------------------------------------------------
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Scheduler extends CI_Controller {

    public function __construct() {
        parent::__construct();
		
        $this->load->helper('url');
        $this->load->model('scheduler/scheduler_model');
    }

   
    public function index() {
               
               $this->schedule_reports();
            
        }
		
	public function schedule_reports(){
	
	$to_cc_email_data = $this->scheduler_model->get_email_data_to_cc(2);
	$generate_xls_data = $this->scheduler_model->generate_xls();
	$this->load->helper('support');
	$this->load->helper('to_excel');
	$this->load->helper('file');
	$from_email = 'iioncudos@gmail.com';
	$from_name = 'IonCUDOS';
	$fields = array('Department','Program','Curriculum','Curriculum Owner','PEO Count','PO Count','Courses Count','Curriculum Total Credits','Defined Credits for Courses','Topic Count','TLO Count');
	$subject = 'Curricula Adequacy Report';
	$message = 'This is an automated email from Curriculum Design Software. Please find the attachment for the Curricula Adequacy Report.';
	$worksheet_name = 'Adequacy Report';
	$filename = 'adequacy_report.xls';
	$send_to = '';
	$send_cc = '';
	$email_to ='';
	$email_cc ='';
	$email_to_list = explode(',',$to_cc_email_data['email_to'][0]['to_email']);
	$email_cc_list = explode(',',$to_cc_email_data['email_cc'][0]['cc_email']);
	to_excel($generate_xls_data,$filename, $fields,$worksheet_name);
	 foreach($email_to_list as $result){
		
		$send_to = '<'.$result.'>,';
		$email_to = $email_to . $send_to;
		
		}
	foreach($email_cc_list as $result_cc){
			
			$send_cc = '<'.$result_cc.'>,';
			$email_cc = $email_cc.$send_cc;
			
		}
		$file = 'uploads/ioncudos_auto_reports/adequacy_report.xls';
		adequacy_report($email_to,$email_cc, $from_email, $subject,$message,$file); 
		
	}
}
