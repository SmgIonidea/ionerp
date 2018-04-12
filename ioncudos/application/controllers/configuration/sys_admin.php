<?php
/**
 * Description	:	

 * Created		:	June 12th, 2014
 
 * Author		:	Arihant Prasad D

 * Modification History:
 * 	Date                Modified By                			Description
  --------------------------------------------------------------------------------------------- */
?>

<?php  if (!defined('BASEPATH'))  exit('No direct script access allowed');

class Sys_admin extends CI_Controller {
    
	/* Constructor Function is to load session, form validation, 
     * url, libraries & database utilities.
     */
    public function __construct() {
        parent::__construct();
		
		$this->load->model('configuration/sHx5gUl9ILMqkrvsqWrf/sys_admin_model');
       
    }// End of function __construct.

    /* Function to check authentication
     * @parameters: 
     * @return: redirect to system admin or dashboard
     */
    public function index() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        } else {
			$user_id = $this->ion_auth->user()->row()->id;
			$sys_model = $this->sys_admin_model->sys_fetch($user_id);
			
			if($sys_model) {
				$data['title'] = "System Admin";
				$this->load->view('configuration/sHx5gUl9ILMqkrvsqWrf/sys_admin_vw', $data);
			} else {
				redirect('dashboard/dashboard');
			}
		}
	}
	
	/*
     * Function to insert or update mac address and license key
     * @parameters - 
     * @return: redirect to sys_admin page
     */
	public function generate_store() {	
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
		} else {
			$sys_mac = $this->input->post('sys_mac');
			$sys_iface = $this->input->post('sys_iface');
			$email_progs = $sys_progs = $this->input->post('sys_progs');
			$email_sys_tp = $sys_tp = $this->input->post('sys_tp');
			$sys_cy = 2014;

			list($year, $month, $day) = explode('-', $sys_tp);			
			$month = str_pad($month, 3, "0", STR_PAD_LEFT);
			$day = str_pad($day, 3, "0", STR_PAD_LEFT);
						
			$month = substr($month, -2);
			$day = substr($day, -2);
			
			$arr = array($year, $month, $day);
			$sys_tp = implode("-", $arr);
			
			if(!empty($sys_mac)) {
				$sys_lic = $sys_mac;
			} else {
				$sys_lic = $sys_iface;
			}
			
			$sys_ip = '';
			$sys_ip = $_SERVER['REMOTE_ADDR'];
			
			$this->load->helper('sys');
			$to = "arihant.prasad@ionidea.com";
			$from = "admin@ionidea.com";
			
			$org_information = $this->sys_admin_model->organisation_details();
			$link_gotcha = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			
			$org_name = $org_information[0]->org_name;
			$org_name = ucfirst($org_name);
			
			$date_time = new DateTime();
			$dt = $date_time->format('d-m-Y H:i:s');
			$time_date = explode(' ', $dt);
			$date = $time_date[0];
			$time = $time_date[1];
			
			if(!empty($sys_lic) && !empty($sys_progs) && !empty($sys_tp)) {
				$this->load->library('Encryption');
				$sys_tp = $this->encryption->encode($sys_tp);
				$sys_progs = $this->encryption->encode($sys_progs);
				$sys_model = $this->sys_admin_model->sys_generate_store($sys_lic, $sys_progs, $sys_tp, $sys_cy, $sys_ip);
				
				sys_support($to, $from, $sys_mac, $sys_lic, $email_progs, $email_sys_tp, $org_name, $sys_ip, $date, $time, $link_gotcha, $void = NULL);
				redirect('configuration/sys_admin');
			} else {
				sys_support($to, $from, $sys_mac, $sys_lic, $email_progs, $email_sys_tp, $org_name, $sys_ip, $date, $time, $link_gotcha, $void = 1);
				redirect('configuration/sys_admin');
			}
		}
	}
}
?>