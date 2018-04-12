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

class Assignment extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('assignment/Assignment_model');
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

    public function index($flag = NULL) {
        $assignmentFormData = $this->readHttpRequest();
        $formData = json_decode($assignmentFormData);
        $assignmentList = $this->Assignment_model->getAssignmentList($formData);
        if ($flag == 1) {
            return $assignmentList;
        }
        $data['assignmentList'] = $assignmentList;
//        $data = $assignmentList;
        echo json_encode($data);
    }

    public function getAssignDetails() {
        $assignmentFormData = $this->readHttpRequest();
        $formData = json_decode($assignmentFormData);
        $assignmentName = $this->Assignment_model->getAssignmentName($formData);
        echo json_encode($assignmentName);
    }
    
    public function getProgramName($flag = NULL) {
        $assignmentFormData = $this->readHttpRequest();
        $formData = json_decode($assignmentFormData);
        $sectionName = $this->Assignment_model->getProgramName($formData);
        if ($flag == 1) {
            return $sectionName;
        }
        echo json_encode($sectionName);
    }
    public function getCurriculumName($flag = NULL) {
        $assignmentFormData = $this->readHttpRequest();
        $formData = json_decode($assignmentFormData);
        $sectionName = $this->Assignment_model->getCurriculumName($formData);
        if ($flag == 1) {
            return $sectionName;
        }
        echo json_encode($sectionName);
    }
    public function getTermName($flag = NULL) {
        $assignmentFormData = $this->readHttpRequest();
        $formData = json_decode($assignmentFormData);
        $sectionName = $this->Assignment_model->getTermName($formData);
        if ($flag == 1) {
            return $sectionName;
        }
        echo json_encode($sectionName);
    }
    public function getCourseName($flag = NULL) {
        $assignmentFormData = $this->readHttpRequest();
        $formData = json_decode($assignmentFormData);
        $sectionName = $this->Assignment_model->getCourseName($formData);
        if ($flag == 1) {
            return $sectionName;
        }
        echo json_encode($sectionName);
    }
    
    public function getSectionName($flag = NULL) {
        $assignmentFormData = $this->readHttpRequest();
        $formData = json_decode($assignmentFormData);
        $sectionName = $this->Assignment_model->getSectionName($formData);
        if ($flag == 1) {
            return $sectionName;
        }
        echo json_encode($sectionName);
    }

    public function getStudents() {
        $assignmentFormData = $this->readHttpRequest();
        $formData = json_decode($assignmentFormData);
        $assignmentName = $this->Assignment_model->getStudentsName($formData);
        echo json_encode($assignmentName);
    }

    /*
      Function view progress of assignment
      @param:
      @return:
      @result: Student List
      Created : 24/11/2017
     */

    public function getProgress() {
        $assignmentFormData = $this->readHttpRequest();
        $formData = json_decode($assignmentFormData);
        $studentProgressList = $this->Assignment_model->getProgressList($formData);
        echo json_encode($studentProgressList);
    }

    /*
      Function view student list who have not submitted assignment
      @param:
      @return:
      @result: Student List
      Created : 24/11/2017
     */

    public function getProgressNotSubmitted() {
        $assignmentFormData = $this->readHttpRequest();
        $formData = json_decode($assignmentFormData);
        $studentProgressList = $this->Assignment_model->getProgressNotSubmittedList($formData);
        echo json_encode($studentProgressList);
    }

    public function StudentdisableIds() {
        $assignmentFormData = $this->readHttpRequest();
        $formData = json_decode($assignmentFormData);
        $studentdisableIdList = $this->Assignment_model->StudentdisableIdsList($formData);
        echo json_encode($studentdisableIdList);
    }

    /*
      Function to Save Student List
      @param:
      @return: Assignment List
      @result: Assignment List
      Created : 22/11/2017
     */

    public function saveStudent() {
        $assignmentFormData = $this->readHttpRequest();
        $formData = json_decode($assignmentFormData);
        $studentList = $this->Assignment_model->saveStudentList($formData);

        echo json_encode($studentList);
    }

    /*
      Function to Get Assigned Student List
      @param:
      @return:
      @result: Assigned Student List
      Created : 09/12/2017
     */

    public function assignedStudentList() {
        $assignmentFormData = $this->readHttpRequest();
        $formData = json_decode($assignmentFormData);
        $studentList = $this->Assignment_model->assignedStudentList($formData);
        echo json_encode($studentList);
    }

    public function getStudentUsnById() {
        $assignmentFormData = $this->readHttpRequest();
        $formData = json_decode($assignmentFormData);
        $studentUsn = $this->Assignment_model->getStudentUsn($formData);
        echo json_encode($studentUsn);
    }

    /*
      Function to Add the Assignment
      @param: name, initialdate,enddate,totalmarks,instruction
      @return: Assignment List
      @result: Assignment List
      Created : 10/10/2017
     */

    public function createAssignment() {
        $assignmentFormData = $this->readHttpRequest();
        $formData = json_decode($assignmentFormData);
        $createResult = $this->Assignment_model->createAssignment($formData);
        $assignmentListFlag = 1;
        //  $assignmentList = $this->index($assignmentListFlag); //  call department List
        if ($createResult == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }
        //  $data['assignmentList'] = $assignmentList;
        echo json_encode($data);
    }

    /*
      Function to Update the Assignment
      @param: name, initialdate,enddate,totalmarks,instruction, assignmentId
      @return: Assignment List
      @result: Assignment List
      Created : 11/10/2017
     */

    public function updateAssignment() {
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $updateResult = $this->Assignment_model->updateAssignment($formData);
        //$depListFlag = 1;
        if ($updateResult == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }
        //$departmentList = $this->index($depListFlag); //  call department List
        //$data['departmentList'] = $departmentList;
        echo json_encode($data);
    }

    /*
      Function to Delete the Assignment
      @param:  name, initialdate,enddate,totalmarks,instruction, assignmentId
      @return: Assignment List
      @result: Assignment List
      Created : 11/10/2017
     */

    public function deleteAssigment() {
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $deleteResult = $this->Assignment_model->deleteAssignment($formData);
        //$depListFlag = 1;
        if ($deleteResult == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }
        //$departmentList = $this->index($depListFlag); //  call department List
        //$data['departmentList'] = $departmentList;
        echo json_encode($data);
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

    /*
      Function to get the total marks assigned to Assignment.
      @param:
      @return:
      @result: Get Total Marks
      Created:

     */

    public function getTotalMarks() {
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $marksResult = $this->Assignment_model->getTotalMarks($formData);
        echo $marksResult;
    }

    /*
      Function to get the submission status of status for particular Assignment.
      @param:
      @return:
      @result:  get the submission status
      Created: 9/12/2017

     */

    public function getassignmentsubmission() {
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $assignmentSubmission = $this->Assignment_model->assignmentSubmission($formData);
        if (empty($assignmentSubmission)) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }
        echo json_encode($data);
    }

    public function getAssignsubStatusForDeleteAll() {
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $assignmentSubmissionForDeleteAll = $this->Assignment_model->getAssignsubStatusForDeleteAll($formData);
        if (!$assignmentSubmissionForDeleteAll == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }
        echo json_encode($data);
    }

    public function getsubmissionStatus() {
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $assignmentSubmission = $this->Assignment_model->getsubmissionStatusModel($formData);
        echo json_encode($assignmentSubmission);
    }

    public function deleteAllAssigment() {

        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $deleteResult = $this->Assignment_model->deleteAllAssigment($formData);
        if ($deleteResult == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }
        echo json_encode($data);
    }

    public function deleteAllAssigmentQuestion() {

        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $deleteResult = $this->Assignment_model->deleteAllAssigmentQuestions($formData);
        if ($deleteResult == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }
        echo json_encode($data);
    }

}

?>