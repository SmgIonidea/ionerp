<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Controller Logic for Sub category List and Add, Edit Functionality.
 * Variable and Function Naming: cammelCaseNotation is followed (First letter of the first word should be small and First letter of secon word onwards should be capital.)
 * Modification History:
 * Date				Modified By				Description
 * 16-08-2018                   Suchitra V      	Added file headers, function headers & comments.
  ---------------------------------------------------------------------------------------------------------------------------------
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class subcategory extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('library/master_records_model/subcategory_model');
        $this->load->library('form_validation');
        /* 		
          Below mentioned headers are required to read the data coming from deifferent port.
          Access Control Headers.
         */
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: X-Requested-With');
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS,PUT');
    }
    
    public function getSubCategoryData() {
        
        $subCategoryFormData = $this->readHttpRequest();
        $formData = json_decode($subCategoryFormData);
        $subCategoryName = $this->subcategory_model->getSubCategoryDetails($formData);
        echo json_encode($subCategoryName);
        
    }
    
    public function saveSubCategory(){
        
        $subCategoryListFormData = $this->readHttpRequest();
        $formData = json_decode($subCategoryListFormData);
        $subCategoryList = $this->subcategory_model->saveSubCategoryListData($formData);
        echo json_encode($subCategoryList);
    }
    
    public function getCatName(){
        $categoryFormData = $this->readHttpRequest();
        $formData = json_decode($categoryFormData);
        $categoryName = $this->subcategory_model->getCategoryName($formData);
        echo json_encode($categoryName);
        
    }
    
    public function updateSubCategoryDetails(){
        
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $updateResult = $this->subcategory_model->updateSubcategory($formData);       
        if ($updateResult == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }        
        echo json_encode($data);
    }
    
    public function deleteSubCategory(){
        
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $deleteResult = $this->subcategory_model->deleteSubCategoryData($formData);        
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