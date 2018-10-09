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

class CollectItems extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('hostel/CollectItems_model');
        $this->load->library('form_validation');
        /* 		
          Below mentioned headers are required to read the data coming from deifferent port.
          Access Control Headers.
         */
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: X-Requested-With');
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS,PUT');
    }

   

    public function getBuildingList() {

        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $data = $this->CollectItems_model->getBuildList($formData);       
        echo json_encode($data);
    }

    /*
      Function to Add the Assignment
      @param: name, initialdate,enddate,totalmarks,instruction
      @return: Assignment List
      @result: Assignment List
      Created : 10/10/2017
     */

    public function getReturnableItems() {       
        $accountingFormData = $this->readHttpRequest();
        $formData = json_decode($accountingFormData);         
        $createResult = $this->CollectItems_model->returnableItems($formData);        
        if ($createResult == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }
      echo json_encode($createResult);
    }  
    
    
   
      public function readHttpRequest() {
        $incomingFormData = file_get_contents('php://input');
        return $incomingFormData;
    }
   

}

?>