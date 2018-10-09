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

class AddBuilding extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('hostel/AddBuilding_model');
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
        $buildingList = $this->AddBuilding_model->getBuildingList();
        if ($flag == 1) {
            return $buildingList;
        }
        $data['buildingList'] = $buildingList;
        echo json_encode($data);
    }
    public  function checkBuliding(){
        $buildingFormData = $this->readHttpRequest();
        $formData = json_decode($buildingFormData);
        $buildresult = $this->AddBuilding_model->checkBuliding($formData);
        if($buildresult == true){
            $data['status'] = 'ok';
        }
        else{
            $data['status'] = 'fail';
        }
        echo json_encode($data);
        
    }
    
    public function createBuilding(){
        $buildingFormData = $this->readHttpRequest();
        $formData = json_decode($buildingFormData);
        $buildresult = $this->AddBuilding_model->createbuilding($formData);
        if($buildresult == true){
            $data['status'] = 'ok';
        }
        else{
            $data['status'] = 'fail';
        }
        echo json_encode($data);
    }
    
    public function updateBuilding(){
       $buildingFormData = $this->readHttpRequest();
        $formData = json_decode($buildingFormData);
        $updateResult = $this->AddBuilding_model->updateBuilding($formData);
        
        if($updateResult == true){
            $data['status'] = 'ok';
        }
        else
        {
            $data['status'] = 'fail';
        }
        echo json_encode($data);
    }
        public function checkroomDel(){
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $deleteResult = $this->AddBuilding_model->checkroomDel($formData);
        
        if ($deleteResult == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }
        echo json_encode($data);
        
    }

    public function deleteBuildingName() {
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $deleteResult = $this->AddBuilding_model->deleteBuildingName($formData);
       
        if ($deleteResult == true) {
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

?>