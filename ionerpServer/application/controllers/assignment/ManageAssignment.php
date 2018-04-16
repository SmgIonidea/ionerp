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

class ManageAssignment extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('assignment/ManageAssignment_model');
        $this->load->library('form_validation');
        /* 		
          Below mentioned headers are required to read the data coming from deifferent port.
          Access Control Headers.
         */
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: X-Requested-With');
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS,PUT');
    }

    public function index() {
        $assignmentFormData = $this->readHttpRequest();
        $formData = json_decode($assignmentFormData);
        $assignmentName = $this->ManageAssignment_model->getAssignmentName($formData);
        echo json_encode($assignmentName);
    }

    public function getTotalMarks() {
        
        $assignmentFormData = $this->readHttpRequest();
        $formData = json_decode($assignmentFormData);
        $assignmentMarks = $this->ManageAssignment_model->getAssignmentName($formData);

        foreach ($assignmentMarks as $item) {
            $total = $item['total_marks'];
        }
        $sum = 0;

        $checkQuest = $this->ManageAssignment_model->getAssignmentTotalMarks($formData);
        if (!empty($checkQuest)) {
            foreach ($checkQuest as $val) {
                $sumMarks = $val['que_max_marks'];
//                $sum = floatval($sum) + floatval($sumMarks);
                $sum_marks = $sum + $sumMarks;
                // to display number in decimal format
               $sum = number_format($sum_marks, 2);
            }
            $totalMarks = $sum . '/' . $total;
        } else {
            $totalMarks = '0.00/' . $total;
        }

        echo json_encode($totalMarks);
    }

    public function getQuestionMarks() {
        $assignmentFormData = $this->readHttpRequest();
        $formData = json_decode($assignmentFormData);
        $id = $formData->id;
        $assignmentQuesMarks = $this->ManageAssignment_model->getAssignmentName($id);
        $total1 = 0;
        foreach ($assignmentQuesMarks as $item) {
            $total = $item['total_marks'];
        }
        $checkQuestData = $this->ManageAssignment_model->getAssignmentTotalMarks($id);
        if (!empty($checkQuestData)) {
            foreach ($checkQuestData as $val) {
                $sumMarks = $val['que_max_marks'];
//                $sum = floatval($sum) + floatval($sumMarks);
                $total1 = $total1 + $sumMarks;
            }
            $total = $total - $total1;
        }
        $marks = $formData->marks;

        if ($marks <= $total) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }
        echo json_encode($data);
    }

    public function getquestionmarksedit() {
        $assignmentFormData = $this->readHttpRequest();
        $formData = json_decode($assignmentFormData);
        $id = $formData->id;
        $assignmentQuesMarks = $this->ManageAssignment_model->getAssignmentName($id);
        $total1 = 0;
        foreach ($assignmentQuesMarks as $item) {
            $total = $item['total_marks'];
        }
        $questionid = $formData->aq_id;
        $QuesMarks = $this->ManageAssignment_model->getParticularMarks($questionid, $id);
        foreach ($QuesMarks as $marksval) {
            $parmark = $marksval['que_max_marks'];
        }
        $checkQuestData = $this->ManageAssignment_model->getAssignmentTotalMarks($id);
        if (!empty($checkQuestData)) {
            foreach ($checkQuestData as $val) {
                $sumMarks = $val['que_max_marks'];
//                $sum = floatval($sum) + floatval($sumMarks);
                $total1 = $total1 + $sumMarks;
            }

            $grandtotal = $total1 - $parmark;
        }

        $marks = $formData->marks;
        $newmarks = $marks + $grandtotal;

        if ($newmarks <= $total) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }
        echo json_encode($data);
    }

    public function checkQuestionExsist() {
        $assignmentFormData = $this->readHttpRequest();
        $formData = json_decode($assignmentFormData);
        $newQuesNo = $this->ManageAssignment_model->checkQuestionExsist($formData);
        echo json_encode($newQuesNo);
    }

    public function getCloDropdown() {

        $assignmentFormData = $this->readHttpRequest();
        $formData = json_decode($assignmentFormData);
        $assignmentClo = $this->ManageAssignment_model->getClo($formData);
        echo json_encode($assignmentClo);
    }

    public function getPloDropdown() {
//       exit;
        $assignmentFormData = $this->readHttpRequest();
        $formData = json_decode($assignmentFormData);
        $assignmentPlo = $this->ManageAssignment_model->getPlo($formData);
//        var_dump($assignmentPlo);
//        exit();
        echo json_encode($assignmentPlo);
    }

    public function getTopicDropdown() {
        $assignmentFormData = $this->readHttpRequest();
        $formData = json_decode($assignmentFormData);
        $assignmentTopic = $this->ManageAssignment_model->gettopic($formData);
        echo json_encode($assignmentTopic);
    }

    public function getTloDropdown() {
        $assignmentFormData = $this->readHttpRequest();
        $formData = json_decode($assignmentFormData);
        $assignmentTlo = $this->ManageAssignment_model->getTlo($formData);
//        var_dump($assignmentTlo);
//        exit();
        echo json_encode($assignmentTlo);
    }

    public function getBloomDropdown() {
        $assignmentFormData = $this->readHttpRequest();
        $formData = json_decode($assignmentFormData);
        $assignmentBloom = $this->ManageAssignment_model->getBloom($formData);
//        var_dump($assignmentTlo);
//        exit();
        echo json_encode($assignmentBloom);
    }

    public function getDifficultyDropdown() {
        $assignmentFormData = $this->readHttpRequest();
        $formData = json_decode($assignmentFormData);
        $assignmentDifficulty = $this->ManageAssignment_model->getDifficultyLevel($formData);
        echo json_encode($assignmentDifficulty);
    }

    public function getQuestionTypeDropdown() {
        $assignmentFormData = $this->readHttpRequest();
        $formData = json_decode($assignmentFormData);
        $assignmentQuestionType = $this->ManageAssignment_model->getQuestionType($formData);
        echo json_encode($assignmentQuestionType);
    }

    public function readHttpRequest() {
        $incomingFormData = file_get_contents('php://input');
        return $incomingFormData;
    }

    public function createAssignmentQuestions() {
        $assignmentQuestionFormData = $this->readHttpRequest();
        $formData = json_decode($assignmentQuestionFormData);
        $createResult = $this->ManageAssignment_model->createAssignmentQuestions($formData);
//                var_dump($createResult);
//                exit;
//		$depListFlag = 1;
//		$departmentList = $this->index($depListFlag); //  call department List
        if ($createResult == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }
//		$data['departmentList'] = $departmentList;
        echo json_encode($data);
    }

    public function getAssignmentQuest() {
        $assignmentFormData = $this->readHttpRequest();
        $formData = json_decode($assignmentFormData);
        $assignmentQuest = $this->ManageAssignment_model->getAssignmentQuest($formData);
        echo json_encode($assignmentQuest);
    }

    public function getAssignmentQuestions() {
        $assignmentFormData = $this->readHttpRequest();
        $formData = json_decode($assignmentFormData);
        $assignmentQuestions = $this->ManageAssignment_model->getAssignmentQuestions($formData);

        if ($assignmentQuestions == 'no questions') {
            $data['status'] = 'no questions';
        } else if ($assignmentQuestions == true) {

            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }

        echo json_encode($data);
    }

    public function getConfig() {
        $configFormData = $this->readHttpRequest();
        $formData = json_decode($configFormData);
        $configData = $this->ManageAssignment_model->getConfig($formData);
//        print_r($configData);exit;
        echo json_encode($configData);
    }

    public function getSectionName($flag = NULL) {
        $assignmentFormData = $this->readHttpRequest();
        $formData = json_decode($assignmentFormData);
        $sectionName = $this->ManageAssignment_model->getSectionName($formData);
        if ($flag == 1) {
            return $sectionName;
        }
//        $data['sectionName'] = $assignmentList;
        echo json_encode($sectionName);
    }

    public function geteditAssignmentQuest() {
        $assignmentFormData = $this->readHttpRequest();
        $formData = json_decode($assignmentFormData);
        $assignmentEditQuest = $this->ManageAssignment_model->editQuestionData($formData);
        echo json_encode($assignmentEditQuest);
    }

    public function getStudentSubmissionStatus() {

        $submissionFormData = $this->readHttpRequest();
        $formData = json_decode($submissionFormData);
        $StudentSubmissionStatus = $this->ManageAssignment_model->getStudentSubmissionStatus($formData);
        if ($StudentSubmissionStatus == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }
        echo json_encode($data);
    }

    public function getSubmissionStatus() {
        $assignmentFormData = $this->readHttpRequest();
        $formData = json_decode($assignmentFormData);
        $getSubmissionStatusQuest = $this->ManageAssignment_model->getStudentSubmissionStatus($formData);
//        echo json_encode($getSubmissionStatusQuest);
        if ($getSubmissionStatusQuest == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }
        echo json_encode($data);
    }

    public function updateAssignmentQuestion() {
        $assignmentQuestionFormData = $this->readHttpRequest();
        $formData = json_decode($assignmentQuestionFormData);
//        var_dump($formData);
//        exit;
        $upadteResult = $this->ManageAssignment_model->updateAssignmentQuestions($formData);
//        var_dump($upadteResult); 
        if ($upadteResult == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }
//		var_dump($data); exit;
//		['departmentList'] = $departmentList;
        echo json_encode($data);
    }

    public function deleteQuestion() {
//        $data = array();
        $assignmentFormData = $this->readHttpRequest();
        $formData = json_decode($assignmentFormData);

        $deleted = $this->ManageAssignment_model->deleteQuestionModel($formData);
//        var_dump($deleted);exit;
        if ($deleted == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }
//        var_dump($data); exit;
        echo json_encode($data);
    }

    public function deleteMultipleQuestion() {
//        $data = array();
        $assignmentFormData = $this->readHttpRequest();
        $formData = json_decode($assignmentFormData);

        $deleted = $this->ManageAssignment_model->deleteMultipleQuestionModel($formData);
//        var_dump($deleted);exit;
        if ($deleted == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }
//        var_dump($data); exit;
        echo json_encode($data);
    }

    public function upload($curriculum, $course) {
        $fileUploadResult = $this->ManageAssignment_model->fileUpload($curriculum, $course);
        if ($fileUploadResult == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }
        echo json_encode($data);
    }

    public function uploadUpdate($aqId, $curriculum, $course) {
        $fileUploadUpdateResult = $this->ManageAssignment_model->fileUploadUpdate($aqId, $curriculum, $course);
        if ($fileUploadUpdateResult == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }
        echo json_encode($data);
    }

}

?>