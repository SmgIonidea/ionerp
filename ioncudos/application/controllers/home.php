<?php
/**
* Description	:	Controller Logic for User login management through credentials(username & password) 
					are being authenticated & presented with the respective view.
*					
*Created		:	25-03-2013. 
*		  
* Modification History:
* Date				Modified By				Description
* 19-08-2013		Abhinay B.Angadi        Added file headers, function headers, indentations & comments.
* 26-08-2013		Abhinay B.Angadi		Variable naming, Function naming & Code cleaning.
* 21-10-2014		Arihant Prasad			Check License period
---------------------------------------------------------------------------------------------------
*/
?>

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {
	
	public function __construct()	{
		parent::__construct();
		$this->load->model('login/login_model');
		$this->load->library('Session');
		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), 
														$this->config->item('error_end_delimiter', 'ion_auth'));
			if (!$this->ion_auth->logged_in()) {
				redirect('login','refresh');
				}			
	}// End of function __construct.
		
	// Function is used to load the login page & to make validations as well authentication checks.
	public function index()	{
		if ($this->ion_auth->logged_in()) {
            //redirect them to the dashboard page
            //redirect("dashboard/dashboard", 'refresh');
		if($this->ion_auth->in_group('Student')){
			$this->load->view('login/stud_login_landing_vw');
		}else{
			$this->load->view('login/login_landing_vw');
		}
			
        }
	}
}