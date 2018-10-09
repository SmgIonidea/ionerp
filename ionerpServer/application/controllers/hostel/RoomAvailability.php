<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Controller Logic for Account List and Add, Edit Functionality.
 * Variable and Function Naming: cammelCaseNotation is followed (First letter of the first word should be small and First letter of secon word onwards should be capital.)
 * Modification History:
 * Date				Modified By				Description
 * 27-10-2017		Deepa N G      	Added file headers, function headers & comments.
  ---------------------------------------------------------------------------------------------------------------------------------
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class RoomAvailability extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('hostel/AddRoom_model');
         $this->load->model('hostel/RoomAvailability_model');
        $this->load->library('form_validation');
        /* 		
          Below mentioned headers are required to read the data coming from deifferent port.
          Access Control Headers.
         */
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: X-Requested-With');
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT');
    }

    /*
      Function to List the Assignment
      @param:
      @return:
      @result: Assignment List
      Created : 9/10/2017
     */

    public function index($flag = NULL) {

//        $accountingFormData = $this->readHttpRequest();
//        $formData = json_decode($accountingFormData);
        $roomList = $this->AddRoom_model->getRoomList();
        if ($flag == 1) {
            return $roomList;
        }
        $data['roomList'] = $roomList;
        echo json_encode($data);
    }
    
    public function getBuilding(){
        
        $buliding = $this->RoomAvailability_model->getbuildingList();
        $data = $buliding;
        echo json_encode($data);
        
    }
    public function getRoomAvailability(){
        $roomFormData = $this->readHttpRequest();
        $formData = json_decode($roomFormData);
        $roomData = $this->RoomAvailability_model->getRoomAvailability($formData);
        echo json_encode($roomData);
    }

      public function readHttpRequest() {
        $incomingFormData = file_get_contents('php://input');
        return $incomingFormData;
    }
   
}

?>