<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Controller Logic for Assignment List and Add, Edit Functionality.
 * Variable and Function Naming: cammelCaseNotation is followed (First letter of the first word should be small and First letter of secon word onwards should be capital.)
 * Modification History:
 * Date				Modified By				Description
 * 09-10-2017		Deepa N G      	Added file headers, function headers & comments.
  ---------------------------------------------------------------------------------------------------------------------------------
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class ReviewAssignment extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('assignment/ReviewAssignment_model');
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
      Function to List the Assignment
      @param:
      @return:
      @result: Assignment List
      Created : 9/10/2017
     */

//    public function index($flag = NULL) {
//        $assignmentFormData = $this->readHttpRequest();
//        $formData = json_decode($assignmentFormData);
//        $assignmentList = $this->Assignment_model->getAssignmentList($formData);
//        if ($flag == 1) {
//            return $assignmentList;
//        }
//        $data['assignmentList'] = $assignmentList;
//        echo json_encode($data);
//    }
    public function getAssignDetails() {
        $assignmentFormData = $this->readHttpRequest();
        $formData = json_decode($assignmentFormData);
        $assignmentName = $this->ReviewAssignment_model->getAssignmentName($formData);
        echo json_encode($assignmentName);
    }
    
     public function getStudents() {
        $assignmentFormData = $this->readHttpRequest();
        $formData = json_decode($assignmentFormData);
        $studentName = $this->ReviewAssignment_model->getStudentsName($formData);
        echo json_encode($studentName);
    }

    public function getStudentsNameById() {
        $assignmentFormData = $this->readHttpRequest();
        $formData = json_decode($assignmentFormData);
        $studentName = $this->ReviewAssignment_model->getStudentsNameById($formData);
        echo json_encode($studentName);
    }
    
    public function getViewAnswerId() {
        $assignmentFormData = $this->readHttpRequest();
        $formData = json_decode($assignmentFormData);
        $studentName = $this->ReviewAssignment_model->getViewAnswerIdList($formData);
        echo json_encode($studentName);
    }
   
    public function getQuestion() {
        $assignmentFormData = $this->readHttpRequest();
        $formData = json_decode($assignmentFormData);
        $studentAnswer = $this->ReviewAssignment_model->getQuestionList($formData);
        echo json_encode($studentAnswer);
    }
    
    public function getCorrectionStatus() {
        $assignmentFormData = $this->readHttpRequest();
        $formData = json_decode($assignmentFormData);
        $status = $this->ReviewAssignment_model->getCorrectionStatusFlag($formData);
        echo json_encode($status);
    }
    
     public function getStatus() {
        $assignmentFormData = $this->readHttpRequest();
        $formData = json_decode($assignmentFormData);
        $status = $this->ReviewAssignment_model->getStatusFlag($formData);
        echo json_encode($status);
    }
    
    public function saveAssignMarks() {
        $assignmentFormData = $this->readHttpRequest();
        $formData = json_decode($assignmentFormData);
        $studentAnswer = $this->ReviewAssignment_model->saveAssignMarks($formData);
       if ($studentAnswer == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }
        echo json_encode($data);   
    }
    public function getQuestionFlag() {
        $assignmentFormData = $this->readHttpRequest();
        $formData = json_decode($assignmentFormData);
        $studentAnswer = $this->ReviewAssignment_model->getQuestionFlagList($formData);
        echo json_encode($studentAnswer);
    }

    public function updateReworkStatus() {
        $assignmentFormData = $this->readHttpRequest();
        $formData = json_decode($assignmentFormData);
        $upadteResult = $this->ReviewAssignment_model->updateReworkStatus($formData);
        if ($upadteResult == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }
        echo json_encode($data);       
    }
    
    public function getAnswer(){
        $assignmentFormData = $this->readHttpRequest();
        $formData = json_decode($assignmentFormData);
        $studentUploadAnswer = $this->ReviewAssignment_model->getAnswerList($formData);
        echo json_encode($studentUploadAnswer);
    }

    public function getQuestionId(){
        $assignmentFormData = $this->readHttpRequest();
        $formData = json_decode($assignmentFormData);
        $studentList = $this->ReviewAssignment_model->getQuestionId($formData);
        echo json_encode($studentList);
    }
    
    /*
      Global Function to read the file contents from Angular Http Request.
      @param:
      @return:
      @result: Get Http Request COntent
      Created: 9/10/2017

     */

    public function readHttpRequest() {
        $incomingFormData = file_get_contents('php://input');
        return $incomingFormData;
    }

}

?>