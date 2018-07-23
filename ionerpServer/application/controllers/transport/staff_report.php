<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Controller Logic for Driver list module with Add, Edit Functionality.
 * Variable and Function Naming: cammelCaseNotation is followed (First letter of the first word should be small and First letter of secon word onwards should be capital.)
 * Modification History:
 * Date			Modified By				Description
 * 19-07-2018		Suchitra V      	Added file headers, function headers & comments.
  ---------------------------------------------------------------------------------------------------------------------------------
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class staff_report extends CI_Controller {
    
     public function __construct() {
        parent::__construct();
        $this->load->model('transport/staff_report_model');
        $this->load->library('form_validation');
        /* 		
          Below mentioned headers are required to read the data coming from deifferent port.
          Access Control Headers.
         */
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: X-Requested-With');
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS,PUT');
    }
    
    /*
      Function to Fetch the existing staff-wise transport details
      @param:
      @return:
      @result: transport details
      Created : 19/07/2018
     */
    
    public function getStaffWiseReportData(){
        
        $staffReportData = $this->readHttpRequest();
        $formData = json_decode($staffReportData);
        $staffReport = $this->staff_report_model->getStaffWiseReport($formData);
        echo json_encode($staffReport);
    }
    
    public function readHttpRequest() {
        $incomingFormData = file_get_contents('php://input');
        return $incomingFormData;
    }
    
}