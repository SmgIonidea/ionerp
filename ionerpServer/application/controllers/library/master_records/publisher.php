<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Controller Logic for Publihser List and Add, Edit Functionality.
 * Variable and Function Naming: cammelCaseNotation is followed (First letter of the first word should be small and First letter of secon word onwards should be capital.)
 * Modification History:
 * Date				Modified By				Description
 * 24-08-2018                   Suchitra V      	Added file headers, function headers & comments.
  ---------------------------------------------------------------------------------------------------------------------------------
 */


if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class publisher extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('library/master_records_model/publisher_model');
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
      Function to List the Publisher
      @param:
      @return:
      @result: Publisher List
      Created : 24/08/2018
     */
    
    public function getPublisherDetails() {
        
        $publisherFormData = $this->readHttpRequest();
        $formData = json_decode($publisherFormData);
        $publisherName = $this->publisher_model->getPublisher($formData);
        echo json_encode($publisherName);
        
    }
    
    public function savePublisherSupplierData(){
        
        $publisherFormData = $this->readHttpRequest();
        $formData = json_decode($publisherFormData);
        $publisherName = $this->publisher_model->savePublisherSupplierList($formData);
        echo json_encode($publisherName);
        
    }
    
    public function updatePublisherSupplierData(){
        
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $updateResult = $this->publisher_model->updatePublisherSupplierList($formData);       
        if ($updateResult == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }        
        echo json_encode($data);
    }
    
    public function deletePublisherSupplierData(){
        
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $deleteResult = $this->publisher_model->deletePublisherSupplierDetails($formData);        
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