<?php
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: 
 * Modification History:
 * Created		:	21-06-2017
 * Date				Modified By				Description
 * 02-06-2017 		Jyoti 				Dashboard for student login
  ---------------------------------------------------------------------------------------------------------------------------------
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dashboard_student extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        }
        $this->load->model('dashboard/dashboard_student_model');	
    }
    
    /**
     * Function to check authentication of logged in  user and to load the dashboard details in the dashboard page
     * @return: loads dashboard details for the student
     */
    public function index() {
        if (!$this->ion_auth->logged_in()) {
            redirect('login', 'refresh');
        } else {	
            $data['title'] = "Dashboard Page";	
            $this->load->view('dashboard/dashboard_student_vw', $data);
        }
    }
    
    /**
     * Function to check authentication of logged in  user and to load the dashboard details in the dashboard page
     * @return: loads dashboard details for the student
     */
    public function student_survey() {
        if (!$this->ion_auth->logged_in()) {
            redirect('login', 'refresh');
        } else {		
            $data['student_surveys'] = $this->dashboard_student_model->survey_data();
            $this->load->view('dashboard/dashboard_student_survey_vw', $data);
        }
    }
}