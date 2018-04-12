<?php
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Controller Logic for Help list Add Update and delete Page.	  
 * Modification History:
 * Date							Modified By								Description
 * 26-08-2013                   Mritunjay B S                           Added file headers, function headers & comments.
 * 03-09-2013					Mritunjay B S							Changed function names and variable names.
  ---------------------------------------------------------------------------------------------------------------------------------
 */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Upload extends CI_Controller {

   public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->library('form_validation');
        //$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
       // $this->load->model('configuration/helpmanagement/help_content_model');
    }

    /**
     *  Function is to check the user logged in  and to display the help content list.
     *  @param - ------.
     * Loads the list view page.
     */
    public function index() {
        //permission_start

        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page.

            redirect('login', 'refresh');
        } elseif (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this.
            redirect('configuration/users/blank', 'refresh');
        }

        //permission_end
        else {
           
            $this->load->view('upload_vw');
        }
    }



}

/* End of file form.php */
/* Location: ./application/controllers/form.php */
?>