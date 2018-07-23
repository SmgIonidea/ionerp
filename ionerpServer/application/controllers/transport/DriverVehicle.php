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

class DriverVehicle extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('transport/DriverVehicle_model');
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
      Created : 09/07/2018
     */
    
    public function getDriverVehicleList(){
        
        $driverVehicleFormData = $this->readHttpRequest();
        $formData = json_decode($driverVehicleFormData);
        $driverVehicleName = $this->DriverVehicle_model->getDriverVehicleList($formData);
        echo json_encode($driverVehicleName);
    }
    
    
    
    /*
      Function to Fetch the Driver List
      @param:
      @return:
      @result: Driver List
      Created : 09/07/2018
     */
    
    public function getDriverList(){
        
        $driverFormData = $this->readHttpRequest();
        $formData = json_decode($driverFormData);
        $driverName = $this->DriverVehicle_model->getDriverList($formData);
        echo json_encode($driverName);
    }
    
    
     /*
      Function to edit the driver , vehicle
      @param:
      @return:
      @result: driver , vehicle to edit
      Created : 02/07/2018
     */

    public function getDriverVehicle() {
        $driverVehicleEditData = $this->readHttpRequest();
        $formData = json_decode($driverVehicleEditData);
        $driverVehicleEditList = $this->DriverVehicle_model->getDriverVehicle($formData);
        if (!empty($driverVehicleEditList)) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }
        echo json_encode($data);
    }
    
    /*
      Function to save the driver , vehicle
      @param:
      @return:
      @result: save driver , vehicle 
      Created : 10/07/2018
     */
    public function saveDriverVehicle() {
        
        $boardListFormData = $this->readHttpRequest();
        $formData = json_decode($boardListFormData);
        $boardList = $this->DriverVehicle_model->saveDriverVehicle($formData);
         if ($boardList == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }
        echo json_encode($data);
    }
    
    
     /*
      Global Function to read the file contents from Angular Http Request.
      @param:
      @return:
      @result: Get Http Request COntent
      Created: 10/07/2018

     */
    public function readHttpRequest() {
        $incomingFormData = file_get_contents('php://input');
        return $incomingFormData;
    }
    
}

