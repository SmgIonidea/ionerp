<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Help extends CI_Controller {
	
	public function index() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else{
        	$this->load->view('help_vw');
        }
	}
}