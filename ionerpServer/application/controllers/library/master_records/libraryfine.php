<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Controller Logic for Library Fine List and Add, Edit Functionality.
 * Variable and Function Naming: cammelCaseNotation is followed (First letter of the first word should be small and First letter of secon word onwards should be capital.)
 * Modification History:
 * Date				Modified By				Description
 * 20-08-2018                    Suchitra V      	Added file headers, function headers & comments.
  ---------------------------------------------------------------------------------------------------------------------------------
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class libraryfine extends CI_Controller {
    
  public function __construct() {
        parent::__construct();
        $this->load->model('library/master_records_model/libraryfine_model');
        $this->load->library('form_validation');
        /* 		
          Below mentioned headers are required to read the data coming from deifferent port.
          Access Control Headers.
         */
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: X-Requested-With');
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS,PUT');
    }  
    
    public function getLibraryFineDetails() {
        
        $libraryFineFormData = $this->readHttpRequest();
        $formData = json_decode($libraryFineFormData);
        $libraryFineList = $this->libraryfine_model->getLibraryFineData($formData);
        echo json_encode($libraryFineList);
        
    }
    
    public function saveLibraryFineList() {

        $libFineListFormData = $this->readHttpRequest();
        $formData = json_decode($libFineListFormData);
        $libFineList = $this->libraryfine_model->saveLibraryFineData($formData);
        echo json_encode($libFineList);
    }
    
    public function updateLibraryFineList(){
        
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $updateResult = $this->libraryfine_model->updateLibraryFineData($formData);       
        if ($updateResult == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }        
        echo json_encode($data);
    }
    
    public function deletelibFineList(){
        
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $deleteResult = $this->libraryfine_model->deleteLibraryFineData($formData);        
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