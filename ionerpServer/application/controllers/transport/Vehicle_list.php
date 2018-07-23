<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Controller Logic for Route list module with Add, Edit Functionality.
 * Variable and Function Naming: cammelCaseNotation is followed (First letter of the first word should be small and First letter of secon word onwards should be capital.)
 * Modification History:
 * Date			Modified By				Description
 * 06-07-2018		Suchitra V      	Added file headers, function headers & comments.
  ---------------------------------------------------------------------------------------------------------------------------------
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Vehicle_list extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('transport/Vehicle_list_model');
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
      Function to Fetch the existing vehicle details
      @param:
      @return:
      @result: vehicle details
      Created : 06/07/2018
     */
    
    public function getVehicleDetails(){
        
        $vehicleFormData = $this->readHttpRequest();
        $formData = json_decode($vehicleFormData);
        $vehicleName = $this->Vehicle_list_model->getVehicleList($formData);
        echo json_encode($vehicleName);
    }
    
    public function saveVehicleDetails(){
        
        $vehicleListFormData = $this->readHttpRequest();
        $formData = json_decode($vehicleListFormData);
        $vehicleList = $this->Vehicle_list_model->saveVehicleList($formData);
        echo json_encode($vehicleList);
        
    }
    
    public function getTransportTypeList(){
        $typeListFormData = $this->readHttpRequest();
        $formData = json_decode($typeListFormData);
        $typeList = $this->Vehicle_list_model->getTransportType($formData);
        echo json_encode($typeList);
    }
    
    public function updateVehicleDetails(){
        
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $updateResult = $this->Vehicle_list_model->updateVehicleList($formData);       
        if ($updateResult == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }        
        echo json_encode($data);
        
    }
    
    public function deleteVehicleData(){
        
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $updateResultStatus = $this->Vehicle_list_model->deleteVehicleList($formData);   

        if ($updateResultStatus == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }   
        echo json_encode($data);
    }


    public function readHttpRequest() {
        $incomingFormData = file_get_contents('php://input');
        return $incomingFormData;
    }
    
}