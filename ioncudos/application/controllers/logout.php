<?php
/**
* Description	:	Controller Logic for User session & logout management.
*
*Created		:	25-03-2013.  
*		  
* Modification History:
* Date				Modified By				Description
*
* 19-08-2013		Abhinay B.Angadi        Added file headers, function headers, indentations & comments.
* 26-08-2013		Abhinay B.Angadi		Variable naming, Function naming & Code cleaning.
* 02-05-2016		Bhagyalaxmi S S			Added functions    to show log history 
-------------------------------------------------------------------------------------------------
*/
?>

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logout extends CI_Controller {
	public function __construct()
	{		
		parent::__construct();
		$this->load->model('login/login_model');
		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), 
														$this->config->item('error_end_delimiter', 'ion_auth'));
		
		if (isset($_SERVER['HTTP_COOKIE'])) {
			$cookies = explode(';', $_SERVER['HTTP_COOKIE']);
			
			foreach($cookies as $cookie) {
				$parts = explode('=', $cookie);
				$name = trim($parts[0]);
				setcookie($name, '', 1);
				setcookie($name, '', 1, '/');
			}
		}
	}// End of function __construct.
	
	// Function is used to close all the logged-in user sessions & reverts back to the login page.
	public function index() {
		$loggedin_user_id = $this->ion_auth->user()->row()->id;
		$last_login = $this->ion_auth->user()->row()->login_time;
	
		//log the user out
		$logout = $this->ion_auth->logout();
	
	 	date_default_timezone_set('Asia/Kolkata'); 
					// this sets time zone to IST
		$date_val = date_format(new DateTime(),'Y-m-d H:i:s'); 
		$data = $this->login_model->store_login_out_date($loggedin_user_id,$date_val ,$last_login); 
		//redirect them to the login page
		$this->session->set_flashdata('message', $this->ion_auth->messages());
		redirect('login', 'refresh');
	}// End of function index.

}// End of Class Logout.


/* End of file logout.php  
*Location: ./application/controllers/logout.php 
*/

?>