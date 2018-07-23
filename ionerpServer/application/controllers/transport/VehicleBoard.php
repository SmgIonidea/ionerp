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

class VehicleBoard extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('transport/VehicleBoard_model');
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
      Function to Fetch the Board & Vehicle List
      @param:
      @return:
      @result: Board & Vehicle List
      Created :13/07/2018
     */
    
    public function getVehicleBoardList(){
        
        $vehicleBoardFormData = $this->readHttpRequest();
        $formData = json_decode($vehicleBoardFormData);
        $vehicleBoardName = $this->VehicleBoard_model->getVehicleBoardList($formData);
        echo json_encode($vehicleBoardName);
    }
    
    
    
    /*
      Function to Fetch the Vehicles List
      @param:
      @return:
      @result: Vehicles List
      Created : 13/07/2018
     */
    
    public function getVehiclesList(){
        
        $vehiclesFormData = $this->readHttpRequest();
        $formData = json_decode($vehiclesFormData);
        $vehiclesName = $this->VehicleBoard_model->getVehiclesList($formData);
        echo json_encode($vehiclesName);
    }
    
    
     /*
      Function to edit the board , vehicle
      @param:
      @return:
      @result: board , vehicle to edit
      Created : 13/07/2018
     */

    public function getVehicleBoard() {
        $vehicleBoardEditData = $this->readHttpRequest();
        $formData = json_decode($vehicleBoardEditData);
        $vehicleBoardEditList = $this->VehicleBoard_model->getVehicleBoard($formData);
        if (!empty($vehicleBoardEditList)) {
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
      Created : 13/07/2018
     */
    public function saveVehicleBoard() {
        
        $boardListFormData = $this->readHttpRequest();
        $formData = json_decode($boardListFormData);
        $boardList = $this->VehicleBoard_model->saveVehicleBoard($formData);
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
      Created: 13/07/2018

     */
    public function readHttpRequest() {
        $incomingFormData = file_get_contents('php://input');
        return $incomingFormData;
    }
    
}

