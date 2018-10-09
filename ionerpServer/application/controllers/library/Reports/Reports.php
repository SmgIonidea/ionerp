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

class Reports extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('library/reports/Reports_model');
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

    public function getBooksData() {
        $formData = $this->Reports_model->getBooksData();
        echo json_encode($formData);
    }

    public function getBooksavail() {
        $formData = $this->Reports_model->getBooksavail();
        echo json_encode($formData);
    }

    public function filterBooks() {
        $formData = $this->readHttpRequest();
        $bookData = json_decode($formData);
        $filterbookData = $this->Reports_model->filterBooks($bookData);
        echo json_encode($filterbookData);
    }

    public function checkAvailability() {
        $formData = $this->readHttpRequest();
        $searchData = json_decode($formData);
        $bookAvailData = $this->Reports_model->checkAvailability($searchData);
        echo json_encode($bookAvailData);
    }

    public function getCategoryData() {

        $formData = $this->Reports_model->getCategoryData();
        echo json_encode($formData);
    }

    public function getSubCategoryData() {

        $formData = $this->readHttpRequest();
        $subCategoryData = $this->Reports_model->getSubCategoryData($formData);
        echo json_encode($subCategoryData);
    }

    public function getBookDetails() {
        $formData = $this->readHttpRequest();
        $bookId = json_decode($formData);
        $bookDetails = $this->Reports_model->getBookDetails($bookId);
        echo json_encode($bookDetails);
    }

//******************* student Report******************//
    
    public function getId() {
        $formData = $this->readHttpRequest();
        $userType = json_decode($formData);
        $formData = $this->Reports_model->getId($userType);
        echo json_encode($formData);
    }

    public function getClassDetails() {

        $formData = $this->readHttpRequest();
        $regId = json_decode($formData);
        $bookDetails = $this->Reports_model->getClassDetails($regId);
        echo json_encode($bookDetails);
    }

    public function getStaffDetails() {
        $formData = $this->readHttpRequest();
        $staffId = json_decode($formData);
        $bookDetails = $this->Reports_model->getStaffDetails($staffId);
        echo json_encode($bookDetails);
    }

    public function getBookIssuedDetails() {

        $formData = $this->readHttpRequest();
        $studentRegId = json_decode($formData);

        $bookDetails = $this->Reports_model->getBookIssuedDetails($studentRegId);
        echo json_encode($bookDetails);
    }

    public function updateLibFineData() {

        $formData = $this->readHttpRequest();
        $fineDataId = json_decode($formData);

        $bookDetails = $this->Reports_model->updateLibFineData($fineDataId);
        echo json_encode($bookDetails);
    }

    /*
      Function to get book issued to student list
      @param:
      @return: get book  issued to student list
      @result: get book issued to student list
      Created : 07/09/2018
     */

    public function getIssuedStudentList() {

        $formData = $this->readHttpRequest();
        $classData = json_decode($formData);
        $classDetails = $this->Reports_model->getIssuedStudentList($classData);
        echo json_encode($classDetails);
    }

    /*
      Function to get book issued to staff list
      @param:
      @return: get book  issued to staff list
      @result: get book issued to staff list
      Created : 11/09/2018
     */

    public function getIssuedStaffList() {

        $formData = $this->readHttpRequest();
        $classData = json_decode($formData);
        $classDetails = $this->Reports_model->getIssuedStaffList($classData);
        echo json_encode($classDetails);
    }

    /*
      Function to get book issued to student list
      @param:
      @return: get book  issued to student list
      @result: get book issued to student list
      Created : 07/09/2018
     */

    public function searchFines() {

        $formData = $this->readHttpRequest();
        $classData = json_decode($formData);
        $classDetails = $this->Reports_model->searchFines($classData);
        echo json_encode($classDetails);
    }
    
    /*
      Function to get classes
      @param:
      @return: get classes
      @result: get classes
      Created : 07/09/2018
     */

    public function getClasses() {

        $formData = $this->readHttpRequest();
        $classData = json_decode($formData);
        $classDetails = $this->Reports_model->getClasses($classData);
        echo json_encode($classDetails);
    }
    
    /*
      Function to get book issued to student list
      @param:
      @return: get book  issued to student list
      @result: get book issued to student list
      Created : 07/09/2018
     */

    public function searchClass() {

        $formData = $this->readHttpRequest();
        $classData = json_decode($formData);
        $classDetails = $this->Reports_model->searchClass($classData);
        echo json_encode($classDetails);
    }

}
