<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Users extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('user');
        $this->load->library('form_validation');        
        $this->layout->layout = 'ionerp_layout';
        $this->layout->layoutsFolder = 'ionerp_layout'; 
		header('Access-Control-Allow-Origin: *');    
		header('Access-Control-Allow-Headers: X-Requested-With');
		header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
		}
	}
	
?>