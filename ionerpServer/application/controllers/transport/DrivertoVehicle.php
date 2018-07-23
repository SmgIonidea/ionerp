<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class DrivertoVehicle extends CI_Controller {

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
        /*
          Global variable to read the file contents from Angular Http Request.
         */
        $incomingFormData = file_get_contents('php://input');
    }

    
      /*
      Global Function to get the vehiclenumber details from Board and Route list.
      @param:
      @return:
      @result: Get vehiclenumber details
      Created: 9/10/2017

     */
     public function getDrivertoVehicle() {
//        $vehicleFormData = $this->readHttpRequest();
//        $formData = json_decode($vehicleFormData);
        $driverFormData = $this->DriverVehicle_model->getDriverName();        
        echo json_encode($driverFormData);
        return $driverFormData;
    }
    
     /*
      Global Function to read the file contents from Angular Http Request.
      @param:
      @return:
      @result: Get Http Request COntent
      Created: 9/10/2017

     */

    public function readHttpRequest() {
        $incomingFormData = file_get_contents('php://input');
        return $incomingFormData;
    }

}

?>
