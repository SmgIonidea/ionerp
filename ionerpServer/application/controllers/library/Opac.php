<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Controller Logic for reports
 * Variable and Function Naming: cammelCaseNotation is followed (First letter of the first word should be small and First letter of secon word onwards should be capital.)
 * Modification History:
 * Date				Modified By				Description
 * 23-08-2018		Avinash P     	Added file headers, function headers & comments.
  ---------------------------------------------------------------------------------------------------------------------------------
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Opac extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('library/Opac_model');
        $this->load->library('form_validation');
        /* 		
          Below mentioned headers are required to read the data coming from deifferent port.
          Access Control Headers.
         */
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: X-Requested-With');
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS,PUT');
    }

    public function readHttpRequest() {
        $incomingFormData = file_get_contents('php://input');
        return $incomingFormData;
    }

    public function request_header() {

        $formData = $this->readHttpRequest();
        return json_decode($formData);
    }

   

    /*
      Function to get all books details 
      @param:
      @return: get book details 
      @result: get book details 
      Created : 05/09/2018
     */
    public function getBooksAvail() {
        $formData = $this->Opac_model->getBooksAvail();
        echo json_encode($formData);
    }

    /*
      Function to get category 
      @param:
      @return: get category 
      @result: get category 
      Created : 05/09/2018
     */
    public function getCategoryData() {

        $formData = $this->Opac_model->getCategoryData();
        echo json_encode($formData);
    }
    
    /*
      Function to get sub category
      @param:
      @return: get sub category
      @result: get sub category
      Created : 05/09/2018
     */
    public function getSubCategoryData() {

        $formData = $this->readHttpRequest();
        $subCategoryData = $this->Opac_model->getSubCategoryData($formData);
        echo json_encode($subCategoryData);
    }
    
    /*
      Function to search books
      @param:
      @return: books list based on search criteria
      @result: books list based on search criteria
      Created : 06/09/2018
     */
    public function filterBooks() {
        $formData = $this->readHttpRequest();
        $bookData = json_decode($formData);
        $filterbookData = $this->Opac_model->filterBooks($bookData);
        echo json_encode($filterbookData);
    }

}
