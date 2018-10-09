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

class IssueReturnBookStudent extends CI_Controller{
    
    public function __construct() {
        parent::__construct();
        $this->load->model('library/transaction_records/IssueReturnBookStudentModel');
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
      Function to get  roll no
      @param:
      @return:
      @result: roll no
      Created : 23/08/2018
     */
    
    public function getRegNo() {
        
        $rollNoFormData = $this->readHttpRequest();
        $formData = json_decode($rollNoFormData);
        $rollNo = $this->IssueReturnBookStudentModel->getRegNo($formData);
        echo json_encode($rollNo);
                
    }
    
    /*
      Function to get  roll no with roll no filter
      @param:
      @return:
      @result: roll no with roll no filter
      Created : 23/08/2018
     */
    
    public function getRegNoFilter() {
        
        $rollNoFormData = $this->readHttpRequest();
        $formData = json_decode($rollNoFormData);
        $rollNo = $this->IssueReturnBookStudentModel->getRegNoFilter($formData);
        echo json_encode($rollNo);
                
    }
    
     /*
      Function to get student name, student class using reg no
      @param:
      @return:
      @result: student name, student class
      Created : 23/08/2018
     */
    
    public function getStudentDetails() {
        
        $studentDataFormData = $this->readHttpRequest();
        $formData = json_decode($studentDataFormData);
        $studentData = $this->IssueReturnBookStudentModel->getStudentDetails($formData);
        echo json_encode($studentData);
                
    }
    
     /*
      Function to get category list
      @param:
      @return:
      @result: get category list
      Created : 23/08/2018
     */
    
    public function getCategory() {
        
        $categoryFormData = $this->readHttpRequest();
        $formData = json_decode($categoryFormData);
        $categoryData = $this->IssueReturnBookStudentModel->getCategoryData($formData);
        echo json_encode($categoryData);
                
    }
    
     /*
      Function to get sub category list
      @param:
      @return:
      @result: get sub category list
      Created : 23/08/2018
     */
    
    public function getSubCategory() {
        
        $subCategoryFormData = $this->readHttpRequest();
        $formData = json_decode($subCategoryFormData);
        $subCategoryData = $this->IssueReturnBookStudentModel->getSubCategoryData($formData);
        echo json_encode($subCategoryData);
                
    }
     /*
      Function to get book list through search
      @param:
      @return:
      @result: get sub category list
      Created : 27/08/2018
     */
    public function booksList(){
        $booksFormData = $this->readHttpRequest();
        $formData = json_decode($booksFormData);
        $booksData = $this->IssueReturnBookStudentModel->booksList($formData);
        echo json_encode($booksData);
    }
    
     /*
      Function to get book list through search
      @param:
      @return:
      @result: get sub category list
      Created : 27/08/2018
     */
    public function bookSearchList(){
        $bookSearchFormData = $this->readHttpRequest();
        $formData = json_decode($bookSearchFormData);
        $bookSearchData = $this->IssueReturnBookStudentModel->bookSearchList($formData);
        echo json_encode($bookSearchData);
    }
    
    /*
      Function to save issued books
      @param:
      @return: save issued books
      @result: save issued books
      Created : 27/08/2018
     */
    
    public function issueBooks(){
        
    $issueBooksData = $this->readHttpRequest();
    $formData = json_decode($issueBooksData);
    $issueBooksList = $this->IssueReturnBookStudentModel->issueBooks($formData);
    echo json_encode($issueBooksList);
        
    }
    
     /*
      Function to save return books
      @param:
      @return: save return books
      @result: save return books
      Created : 28/08/2018
     */
    
    public function returnBooks(){
        
    $returnBooksData = $this->readHttpRequest();
    $formData = json_decode($returnBooksData);
    $returnBooksList = $this->IssueReturnBookStudentModel->returnBooks($formData);
    echo json_encode($returnBooksList);
        
    }
    
    /*
      Function to get issued books
      @param:
      @return: get issued books
      @result: get issued books
      Created : 28/08/2018
     */
    
    public function loadIssueBooksTable(){
        
    $issuedBooksData = $this->readHttpRequest();
    $formData = json_decode($issuedBooksData);
    $issuedBooksList = $this->IssueReturnBookStudentModel->loadIssueBooksTable($formData);
    echo json_encode($issuedBooksList);
        
    }
    
    
    
     public function readHttpRequest() {
        $incomingFormData = file_get_contents('php://input');
        return $incomingFormData;
    }
        
}

