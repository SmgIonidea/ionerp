<?php
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: 
 * Modification History:
 * Created		:	28-09-2015. 
 * Date				Modified By				Description
 29-09-2015			Bhagyalaxmi 
  ---------------------------------------------------------------------------------------------------------------------------------
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Setting extends CI_Controller {

    public function __construct() {
        parent::__construct();
      //  $this->load->model('curriculum/import_user/import_user_model');
    }
	    public function index() {
 
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') )) {
            redirect('configuration/users/blank');
        } else {
			//var_dump("hj");
			$this->load->view('curriculum/setting/setting_vw'); 
        }
    }
}
