<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Controller Logic for board list module with Add, Edit Functionality.
 * Variable and Function Naming: cammelCaseNotation is followed (First letter of the first word should be small and First letter of secon word onwards should be capital.)
 * Modification History:
 * Date			Modified By				Description
 * 03-07-2018		Suchitra V      	Added file headers, function headers & comments.
  ---------------------------------------------------------------------------------------------------------------------------------
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class DriverReport extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('transport/DriverReport_model');
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
      Function to Fetch the Driver & Vehicle List
      @param:
      @return:
      @result: Driver & Vehicle List
      Created : 13/07/2018
     */
    
    public function getDriverReportList(){
        
        $driverVehicleFormData = $this->readHttpRequest();
        $formData = json_decode($driverVehicleFormData);
        $driverVehicleName = $this->DriverReport_model->getDriverReportList($formData);
        echo json_encode($driverVehicleName);
    }
    
    
    
  
    
     /*
      Global Function to read the file contents from Angular Http Request.
      @param:
      @return:
      @result: Get Http Request COntent
      Created: 13/07/2018

     */
    public function readHttpRequest() {
        $incomingFormData = file_get_contents('php://input');
        return $incomingFormData;
    }
    
}

