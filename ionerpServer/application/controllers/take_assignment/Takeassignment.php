<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Controller Logic for Takeassignment List and Add, Edit, Delete Functionality.
 * Variable and Function Naming: cammelCaseNotation is followed (First letter of the first word should be small and First letter of secon word onwards should be capital.)
 * Modification History:
 * Date				Modified By				Description
 * 22-11-2017                    Indira A       	Added file headers, function headers & comments.
  ---------------------------------------------------------------------------------------------------------------------------------
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Takeassignment extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('take_assignment/takeassignment_model');
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
      Function to get the Assignment Details
      @param:
      @return:
      @result: Assignment Details
      Created : 22-11-2017
     */

    public function index($flag = NULL) {
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $AssignmentDetailsList = $this->takeassignment_model->getAssignmentDetails($formData);

        if ($flag == 1) {
            return $AssignmentDetailsList;
        }
        $data['AssignmentDetailsList'] = $AssignmentDetailsList;
        echo json_encode($data);
    }

    /*
      Function to get the Assignment Question Type
      @param:
      @return:
      @result: Assignment Question Type
      Created : 22-11-2017
     */

    public function assignmentQuestionType($flag = NULL) {
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $AssignmentQuestionTypeList = $this->takeassignment_model->getAssignmentQuestionType($formData);

        if ($flag == 1) {
            return $AssignmentQuestionTypeList;
        }
        $data['AssignmentQuestionTypeList'] = $AssignmentQuestionTypeList;
        echo json_encode($data);
    }
    
    /*
      Function to get the Assignment Answer Flag
      @param:
      @return:
      @result: Assignment Answer Flag
      Created : 23-11-2017
     */

    public function assignmentAnswerFlag() {
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $AssignmentAnswerFlag = $this->takeassignment_model->getAssignmentAnswerFlag($formData);

        if ($AssignmentAnswerFlag == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }
        echo json_encode($data);
    }
    
    /*
      Function to get the Assignment Answer Status
      @param:
      @return:
      @result: Assignment Answer Status
      Created : 23-11-2017
     */

    public function assignmentAnswerStatus($flag = NULL) {
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $AssignmentAnswerStatus = $this->takeassignment_model->getAssignmentAnswerStatus($formData);

        echo json_encode($AssignmentAnswerStatus);
    }
    
    /*
      Function to get the Assignment Content
      @param:
      @return:
      @result: Assignment Answer Content
      Created : 23-11-2017
     */

    public function assignmentAnswerContent($flag = NULL) {
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $AssignmentAnswerContent = $this->takeassignment_model->getAssignmentContent($formData);

        echo json_encode($AssignmentAnswerContent);
    }
    
    /*
      Function to get the Assignment TinyMce Content
      @param:
      @return:
      @result: Assignment Answer TinyMce Content
      Created : 23-11-2017
     */

    public function assignmentAnswerTinyMceContent($flag = NULL) {
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $AssignmentAnswerTinyMceContent = $this->takeassignment_model->getAssignmentTinyMceContent($formData);

        echo json_encode($AssignmentAnswerTinyMceContent);
    }
    
    /*
      Function to get the Assignment Submit Status
      @param:
      @return:
      @result: Assignment Answer Submit Status
      Created : 27-11-2017
     */

    public function assignmentAnswerSubmitStatus() {
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $AssignmentStatus = $this->takeassignment_model->getAssignmentSubmitStatus($formData);
        
        if ($AssignmentStatus == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }
//        $data['shareCourseMaterialList'] = $shareCourseMaterialList;
        echo json_encode($data);

    }

    /*
      Function to get the Assignment Questions
      @param:
      @return:
      @result: Assignment Questions
      Created : 22-11-2017
     */

    public function assignmentQuestions($flag = NULL) {
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $AssignmentQuestionsList = $this->takeassignment_model->getAssignmentQuestions($formData);

        if ($flag == 1) {
            return $AssignmentQuestionsList;
        }
        $data['AssignmentQuestionsList'] = $AssignmentQuestionsList;
        echo json_encode($data);
    }

    /*
      Function to get the Assignment Question File
      @param:
      @return:
      @result: Assignment Question File
      Created : 22-11-2017
     */

    public function assignmentQuestionFile($flag = NULL) {
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $AssignmentQuestionFileList = $this->takeassignment_model->getAssignmentQuestionFile($formData);

        echo json_encode($AssignmentQuestionFileList);
//        if ($flag == 1) {
//            return $AssignmentQuestionFileList;
//        }
//        $data['AssignmentQuestionFileList'] = $AssignmentQuestionFileList;
//        echo json_encode($data);
    }
    
    /*
      Function to save the Assignment Answers
      @param:
      @return:
      @result: Assignment Answers
      Created : 27-11-2017
     */

    public function saveStudentAssignmentAnswer() {
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $createResult = $this->takeassignment_model->saveStudentAssignmentAnswer($formData);
//        $shareCrsListFlag = 1;
//        $shareCourseMaterialList = $this->index($shareCrsListFlag); //  call department List
        if ($createResult == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }
//        $data['shareCourseMaterialList'] = $shareCourseMaterialList;
        echo json_encode($data);
    }

    /*
      Function to get the Assignment Answers
      @param:
      @return:
      @result: Assignment Answers
      Created : 22-11-2017
     */

    public function createStudentAssignmentAnswer() {
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $createResult = $this->takeassignment_model->createStudentAssignmentAnswer($formData);
//        $shareCrsListFlag = 1;
//        $shareCourseMaterialList = $this->index($shareCrsListFlag); //  call department List
        if ($createResult == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }
//        $data['shareCourseMaterialList'] = $shareCourseMaterialList;
        echo json_encode($data);
    }
    
    /*
      Function to Upload File
      @param:
      @return:
      @result:
      Created : 22-11-2017
     */

    public function upload() {
        
        $curriculum = $_GET['curr'];
        $course = $_GET['course'];
        $id = $_GET['id'];
        $stdId = $_GET['stdId'];

        $fileUploadResult = $this->takeassignment_model->fileUpload($curriculum, $course, $id, $stdId);
        if ($fileUploadResult == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }
        echo json_encode($data);
    }

    /*
      Global Function to read the file contents from Angular Http Request.
      @param:
      @return:
      @result: Get Http Request COntent
      Created: 09-10-2017

     */

    public function readHttpRequest() {
        $incomingFormData = file_get_contents('php://input');

        return $incomingFormData;
    }

}
?>
