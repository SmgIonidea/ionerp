<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Controller Logic for Category List and Add, Edit Functionality.
 * Variable and Function Naming: cammelCaseNotation is followed (First letter of the first word should be small and First letter of secon word onwards should be capital.)
 * Modification History:
 * Date				Modified By				Description
 * 13-08-2018                    Suchitra V      	Added file headers, function headers & comments.
  ---------------------------------------------------------------------------------------------------------------------------------
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class category extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('library/master_records_model/category_model');
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
      Function to List the Category
      @param:
      @return:
      @result: Category List
      Created : 13/08/2018
     */
    
    public function getCategoryDetails() {
        
        $categoryFormData = $this->readHttpRequest();
        $formData = json_decode($categoryFormData);
        $categoryName = $this->category_model->getCategory($formData);
        echo json_encode($categoryName);
        
    }
    
    public function saveCategoryListData() {

        $categoryListFormData = $this->readHttpRequest();
        $formData = json_decode($categoryListFormData);
        $categoryList = $this->category_model->saveCategoryList($formData);
        echo json_encode($categoryList);
    }
    
    public function updateCategory(){
        
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $updateResult = $this->category_model->updateCategoryList($formData);       
        if ($updateResult == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }        
        echo json_encode($data);
    }
    
    public function deleteCategory(){
        
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $deleteResult = $this->category_model->deleteCategoryList($formData);        
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

