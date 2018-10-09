<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Controller Logic for Route module with Add, Edit Functionality.
 * Variable and Function Naming: cammelCaseNotation is followed (First letter of the first word should be small and First letter of secon word onwards should be capital.)
 * Modification History:
 * Date			Modified By				Description
 * 29-06-2018		Suchitra V      	Added file headers, function headers & comments.
  ---------------------------------------------------------------------------------------------------------------------------------
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class IssueReturnBookStaff extends CI_Controller{
    
    public function __construct() {
        parent::__construct();
        $this->load->model('library/transaction_records/IssueReturnBookStaffModel');
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
      Function to get  emp id
      @param:
      @return:
      @result: emp id
      Created : 03/09/2018
     */
    
    public function getEmpId() {
        
        $rollNoFormData = $this->readHttpRequest();
        $formData = json_decode($rollNoFormData);
        $rollNo = $this->IssueReturnBookStaffModel->getEmpId($formData);
        echo json_encode($rollNo);
                
    }
    
    /*
      Function to get  emp id with emp id filter
      @param:
      @return:
      @result: emp id with emp id filter
      Created : 03/09/2018
     */
    
    public function getEmpIdFilter() {
        
        $rollNoFormData = $this->readHttpRequest();
        $formData = json_decode($rollNoFormData);
        $rollNo = $this->IssueReturnBookStaffModel->getEmpIdFilter($formData);
        echo json_encode($rollNo);
                
    }
    
     /*
      Function to get staff name, staff department and designation using emp id
      @param:
      @return:
      @result: staff name, staff department and designation
      Created : 03/09/2018
     */
    
    public function getStaffDetails() {
        
        $studentDataFormData = $this->readHttpRequest();
        $formData = json_decode($studentDataFormData);
        $studentData = $this->IssueReturnBookStaffModel->getStaffDetails($formData);
        echo json_encode($studentData);
                
    }
    
     /*
      Function to get category list
      @param:
      @return:
      @result: get category list
      Created : 03/09/2018
     */
    
    public function getCategory() {
        
        $categoryFormData = $this->readHttpRequest();
        $formData = json_decode($categoryFormData);
        $categoryData = $this->IssueReturnBookStaffModel->getCategoryData($formData);
        echo json_encode($categoryData);
                
    }
    
     /*
      Function to get sub category list
      @param:
      @return:
      @result: get sub category list
      Created : 03/09/2018
     */
    
    public function getSubCategory() {
        
        $subCategoryFormData = $this->readHttpRequest();
        $formData = json_decode($subCategoryFormData);
        $subCategoryData = $this->IssueReturnBookStaffModel->getSubCategoryData($formData);
        echo json_encode($subCategoryData);
                
    }
     /*
      Function to get book list through search
      @param:
      @return:
      @result: get sub category list
      Created : 04/09/2018
     */
    public function booksList(){
        $booksFormData = $this->readHttpRequest();
        $formData = json_decode($booksFormData);
        $booksData = $this->IssueReturnBookStaffModel->booksList($formData);
        echo json_encode($booksData);
    }
    
     /*
      Function to get book list through search
      @param:
      @return:
      @result: get sub category list
      Created : 04/09/2018
     */
    public function bookSearchList(){
        $bookSearchFormData = $this->readHttpRequest();
        $formData = json_decode($bookSearchFormData);
        $bookSearchData = $this->IssueReturnBookStaffModel->bookSearchList($formData);
        echo json_encode($bookSearchData);
    }
    
    /*
      Function to save issued books
      @param:
      @return: save issued books
      @result: save issued books
      Created : 04/09/2018
     */
    
    public function issueBooks(){
        
    $issueBooksData = $this->readHttpRequest();
    $formData = json_decode($issueBooksData);
    $issueBooksList = $this->IssueReturnBookStaffModel->issueBooks($formData);
    echo json_encode($issueBooksList);
        
    }
    
     /*
      Function to save return books
      @param:
      @return: save return books
      @result: save return books
      Created : 04/09/2018
     */
    
    public function returnBooks(){
        
    $returnBooksData = $this->readHttpRequest();
    $formData = json_decode($returnBooksData);
    $returnBooksList = $this->IssueReturnBookStaffModel->returnBooks($formData);
    echo json_encode($returnBooksList);
        
    }
    
    /*
      Function to get issued books
      @param:
      @return: get issued books
      @result: get issued books
      Created : 04/09/2018
     */
    
    public function loadIssueBooksTable(){
        
    $issuedBooksData = $this->readHttpRequest();
    $formData = json_decode($issuedBooksData);
    $issuedBooksList = $this->IssueReturnBookStaffModel->loadIssueBooksTable($formData);
    echo json_encode($issuedBooksList);
        
    }
    
    
    
     public function readHttpRequest() {
        $incomingFormData = file_get_contents('php://input');
        return $incomingFormData;
    }
        
}

