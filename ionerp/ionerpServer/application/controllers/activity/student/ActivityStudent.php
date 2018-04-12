<?php

/**
 * Description: Controller logic to list student add and list in Activity student screen
 * Author: Ranjita Naik
 * Created: 19-12-2017
 * Modification History:
 * Date				Modified By				Description
 */

if(!defined('BASEPATH'))
    exit('No direct script access allowed');

class ActivityStudent extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('activity/student/ActivityStudent_model');

        /**	
         * Below mentioned headers are required to read the data coming from different ports
         * Access Control Headers
         */

        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: X-Requested-With');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, PATCH, DELETE');
    }


    /**
     * Function to get curriculum list
     * Params: 
     * Return: 
     */

    public function index() {
        $data = $this->readHttpRequest();
        $data = json_decode($data);

        $crclmList = $this->ActivityStudent_model->getCrclm($data);
        
        echo json_encode($crclmList);
    }


    /**
     * Function to get data from http
     * Params: 
     * Return: data
     */

    public function readHttpRequest() {
        $incomingData = file_get_contents('php://input');

        return $incomingData;
    }


    /**
     * Function to get term list
     * Params: 
     * Return: 
     */
    
    public function getTerm() {
        $data = $this->readHttpRequest();
        $data = json_decode($data);

        $termList = $this->ActivityStudent_model->getTerm($data);
        
        echo json_encode($termList);
    }


    /**
     * Function to get course list
     * Params: 
     * Return: 
     */

    public function getCourse() {
        $data = $this->readHttpRequest();
        $data = json_decode($data);

        $courseList = $this->ActivityStudent_model->getCourse($data);
        
        echo json_encode($courseList);
    }

    /**
     * Function to get student activity
     * Params: 
     * Return: 
     */

    public function getActivity() {
        $data = $this->readHttpRequest();
        $data = json_decode($data);

        $stuActivityList = $this->ActivityStudent_model->getActivity($data);
        
        echo json_encode($stuActivityList);
    }


    /**
     * Function to draft save the Activity Answer
     * Params: 
     * Return: 
     */
    
    public function saveStudentActivityAnswer() {
        $incomingData = $this->readHttpRequest();
        $formData = json_decode($incomingData);

        $saveResult = $this->ActivityStudent_model->saveStudentActivityAnswer($formData);

        if($saveResult == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }

        echo json_encode($data);
    }


    /**
     * Function to submit the Activity Answer
     * Params: 
     * Return: 
     */
    
    public function submitStudentActivityAnswer() {
        $incomingData = $this->readHttpRequest();
        $formData = json_decode($incomingData);

        $submitResult = $this->ActivityStudent_model->submitStudentActivityAnswer($formData);

        if($submitResult == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }

        echo json_encode($data);
    }


    /**
     * Function to submit the url
     * Params: 
     * Return: 
     */
    
    public function submitStudentActivityUrl() {
        $incomingData = $this->readHttpRequest();
        $formData = json_decode($incomingData);

        $submitResult = $this->ActivityStudent_model->submitStudentActivityUrl($formData);

        if($submitResult == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }

        echo json_encode($data);
    }


    /**
     * Function to upload file to a folder
     * Params: 
     * Return: 
     */
    
    public function upload() {
        $fileUploadResult = $this->ActivityStudent_model->upload();

        if($fileUploadResult == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }

        echo json_encode($data);
    }


    /**
     * Function to save uploaded file name
     * Params: 
     * Return: 
     */

    public function submitStudentActivityFile() {
        $incomingData = $this->readHttpRequest();
        $formData = json_decode($incomingData);

        $submitResult = $this->ActivityStudent_model->submitStudentActivityFile($formData);

        if($submitResult == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }

        echo json_encode($data);
    }


    /**
     * Function to fetch answer content
     * Params: 
     * Return: 
     */
    
    public function fetchAnswerContent() {
        $incomingData = $this->readHttpRequest();
        $data = json_decode($incomingData);

        $ansConent = $this->ActivityStudent_model->fetchAnswerContent($data);

        echo json_encode($ansConent);
    }


    /**
     * Function get answer status
     * Params: 
     * Return: 
     */
    
    public function getAnsStatus() {
        $incomingData = $this->readHttpRequest();
        $data = json_decode($incomingData);

        $deliveryFlag = $this->ActivityStudent_model->getAnsStatus($data);

        echo json_encode($deliveryFlag);
    }


    /**
     * Function to list criteria
     * Params: 
     * Return: 
     */
    
    public function listCriteria() {
        $incomingData = $this->readHttpRequest();
        $data = json_decode($incomingData);

        $criteriaList = $this->ActivityStudent_model->listCriteria($data);

        echo json_encode($criteriaList);
    }


    /**
     * Function to get criteria range
     * Params: 
     * Return: 
     */
    
    public function getCriteriaRange(){
        $incomingData = $this->readHttpRequest();
        $data = json_decode($incomingData);

        $criteriaRange = $this->ActivityStudent_model->getCriteriaRange($data);

        echo json_encode($criteriaRange);
    }


    /**
     * Function get activity details to display in rubrics list page
     * Params: 
     * Return: 
     */
    
    public function getActivityDetails() {
        $incomingData = $this->readHttpRequest();
        $id = json_decode($incomingData);

        $activityDet = $this->ActivityStudent_model->getActivityDetails($id);

        echo json_encode($activityDet);
    }


    /**
     * Function to generate rubrics list report pdf
     * Params: reads data from http
     * Return: 
     */
    
    public function exportRubricsListPdf() {
        $incomingData = $this->readHttpRequest();
        $rubrics_data = json_decode($incomingData);

        $curriculumName = $rubrics_data->curriculumName;
        $termName = $rubrics_data->termName;
        $courseName = $rubrics_data->courseName;
        $colspanScaleAssessment = $rubrics_data->colspanScaleAssessment;

        $activityDetails = json_decode(json_encode($rubrics_data->activityDetails), true);
        $criteriaData = json_decode(json_encode($rubrics_data->criteriaData), true);
        $rubricsRange = json_decode(json_encode($rubrics_data->rubricsRange), true);
        $i = 1;

        $content = '<table style="width: 100%;">
              <tr>
                <td>
                  <strong>Curriculum:</strong>
                  <label style="color: #0000FF;">'.$curriculumName.'</label>
                </td>
                <td>
                  <strong>Term:</strong>
                  <label style="color: #0000FF;">'.$termName.'</label>
                </td>
                <td>
                  <strong>Course:</strong>
                  <label style="color: #0000FF;">'.$courseName.'</label>
                </td>
              </tr>

              <tr>
                <td>
                  <strong>Activity Name:</strong>
                  <label style="color: #0000FF;">'.$activityDetails[0]["ao_method_name"].'</label>
                </td>
                <td>
                  <strong>Initial Date:</strong>
                  <label style="color: #0000FF;">'.date("d-m-Y", strtotime($activityDetails[0]["initiate_date"])).'</label>
                </td>
                <td>
                  <strong>End Date:</strong>
                  <label style="color: #0000FF;">'.date("d-m-Y", strtotime($activityDetails[0]["end_date"])).'</label>
                </td>
              </tr>
            </table><br><br>
            <table class="table table-bordered" style="width: 100%;">
              <thead>
                <tr>
                  <th rowspan="2">Sl No.</th>
                  <th rowspan="2">Criteria</th>
                  <th colspan="'.$colspanScaleAssessment.'" style="text-align: center;">Scale of Assessment</th>
                </tr><tr>';
                    foreach($rubricsRange as $range) {
                        $content .= '<th style="text-align: center;">';
                        if(!empty($range["criteria_range_name"])) {
                            $content .= $range["criteria_range_name"].':'.$range["criteria_range"];
                        } else {
                            $content .= $range["criteria_range"];
                        }
                    }
                $content .= '</tr></thead><tbody>';

                foreach($criteriaData as $crData) {
                    $content .= '<tr><td style="text-align: right;">'.$i.'</td><td>'.$crData["criteria"].'</td>';
                        foreach($crData["criteriaDesc"] as $criteriaDesc) {
                            $content .= '<td>'.$criteriaDesc["criteria_description"].'</td>';
                        }
                    $content .= '</tr>';
                    $i++;
                }
              $content .= '</tbody></table>';

        ini_set('memory_limit', '500M');
        $this->load->helper('pdf_helper');
        $pdfCreateResult = pdf_create($content,'rubrics_report','L');

        if($pdfCreateResult == "") {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }

        echo json_encode($data);
    }


    /**
     * Function to list criteria with secured and total marks
     * Params: 
     * Return: 
     */
    
    public function listCriteriaMarks() {
        $incomingData = $this->readHttpRequest();
        $data = json_decode($incomingData);

        $criteriaList = $this->ActivityStudent_model->listCriteriaMarks($data);

        echo json_encode($criteriaList);
    }


    /**
     * Function to generate detailed marks report pdf
     * Params: reads data from http
     * Return: 
     */
    
    public function exportDetailedMarksPdf() {
        $incomingData = $this->readHttpRequest();
        $rubrics_data = json_decode($incomingData);

        $curriculumName = $rubrics_data->curriculumName;
        $termName = $rubrics_data->termName;
        $courseName = $rubrics_data->courseName;
        $colspanScaleAssessment = $rubrics_data->colspanScaleAssessment;
        $securedTotalMarks = $rubrics_data->securedTotalMarks;
        $totalMarks = $rubrics_data->totalMarks;

        $activityDetails = json_decode(json_encode($rubrics_data->activityDetails), true);
        $criteriaData = json_decode(json_encode($rubrics_data->criteriaData), true);
        $rubricsRange = json_decode(json_encode($rubrics_data->rubricsRange), true);
        $i = 1;
        $colspan = $colspanScaleAssessment + 2;

        $content = '<table style="width: 100%;">
              <tr>
                <td>
                  <strong>Curriculum:</strong>
                  <label style="color: #0000FF;">'.$curriculumName.'</label>
                </td>
                <td>
                  <strong>Term:</strong>
                  <label style="color: #0000FF;">'.$termName.'</label>
                </td>
                <td>
                  <strong>Course:</strong>
                  <label style="color: #0000FF;">'.$courseName.'</label>
                </td>
              </tr>

              <tr>
                <td>
                  <strong>Activity Name:</strong>
                  <label style="color: #0000FF;">'.$activityDetails[0]["ao_method_name"].'</label>
                </td>
                <td>
                  <strong>Initial Date:</strong>
                  <label style="color: #0000FF;">'.date("d-m-Y", strtotime($activityDetails[0]["initiate_date"])).'</label>
                </td>
                <td>
                  <strong>End Date:</strong>
                  <label style="color: #0000FF;">'.date("d-m-Y", strtotime($activityDetails[0]["end_date"])).'</label>
                </td>
              </tr>
            </table><br><br>
            <table class="table table-bordered" style="width: 100%;">
              <thead>
                <tr>
                  <th rowspan="2">Sl No.</th>
                  <th rowspan="2">Criteria</th>
                  <th colspan="'.$colspanScaleAssessment.'" style="text-align: center;">Scale of Assessment</th>
                  <th rowspan="2">Marks Secured</th>
                </tr><tr>';
                    foreach($rubricsRange as $range) {
                        $content .= '<th style="text-align: center;">';
                        if(!empty($range["criteria_range_name"])) {
                            $content .= $range["criteria_range_name"].':'.$range["criteria_range"];
                        } else {
                            $content .= $range["criteria_range"];
                        }
                    }
                $content .= '</tr></thead><tbody>';

                foreach($criteriaData as $crData) {
                    $content .= '<tr><td style="text-align: right;">'.$i.'</td><td>'.$crData["criteria"].'</td>';
                        foreach($crData["criteriaDesc"] as $criteriaDesc) {
                            $content .= '<td>'.$criteriaDesc["criteria_description"].'</td>';
                        }
                    $content .= '<td>'.$crData["secured_marks"].'</td></tr>';
                    $i++;
                }
              $content .= '<tr><th colspan="'.$colspan.'">Total Marks</th><th>'
                .$securedTotalMarks.'/'.$totalMarks.'</th></tr></tbody></table>';

        ini_set('memory_limit', '500M');
        $this->load->helper('pdf_helper');
        $pdfCreateResult = pdf_create($content,'detailed_marks_report','L');

        if($pdfCreateResult == "") {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }

        echo json_encode($data);
    }

}
?>