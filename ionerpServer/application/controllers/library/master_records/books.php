<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Controller Logic for Book List and Add, Edit Functionality.
 * Variable and Function Naming: cammelCaseNotation is followed (First letter of the first word should be small and First letter of secon word onwards should be capital.)
 * Modification History:
 * Date				Modified By				Description
 * 27-08-2018                    Suchitra V      	Added file headers, function headers & comments.
  ---------------------------------------------------------------------------------------------------------------------------------
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class books extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('library/master_records_model/books_model');
        $this->load->library('form_validation');
        /* 		
          Below mentioned headers are required to read the data coming from deifferent port.
          Access Control Headers.
         */
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: X-Requested-With');
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS,PUT');
    }
    
    public function getPublisherData(){
        
        $publisherFormData = $this->readHttpRequest();
        $formData = json_decode($publisherFormData);
        $publisherName = $this->books_model->getPublisherList($formData);
        echo json_encode($publisherName);
    }
    
    public function getSubCategory(){
        $subCatFormData = $this->readHttpRequest();
        $formData = json_decode($subCatFormData);
        $subCatName = $this->books_model->getSubCatList($formData);
        echo json_encode($subCatName);
    }


    public function getSupplierData(){
        
        $supplierFormData = $this->readHttpRequest();
        $formData = json_decode($supplierFormData);
        $supplierName = $this->books_model->getSupplierList($formData);
        echo json_encode($supplierName);
        
    }
    
    public function getBooksData(){
        
        $booksFormData = $this->readHttpRequest();
        $formData = json_decode($booksFormData);
        $booksName = $this->books_model->getBookList($formData);
        echo json_encode($booksName);
        
    }
    
    public function accessionNumberData(){
        
        $accessionFormData = $this->readHttpRequest();
        $formData = json_decode($accessionFormData);
        $accessionName = $this->books_model->getAccessionNum($formData);
        echo json_encode($accessionName);
        
    }

        public function saveBookData(){
        
        $bookListFormData = $this->readHttpRequest();
        $formData = json_decode($bookListFormData);
        $bookList = $this->books_model->saveBookList($formData);
        echo json_encode($bookList);
        
    }
    
    public function updateBookList(){
        
       $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $updateResult = $this->books_model->updateBookData($formData);       
        if ($updateResult == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }        
        echo json_encode($data);
        
    }


    public function deleteBookList() {
        
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $deleteResult = $this->books_model->deleteBookData($formData);        
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
