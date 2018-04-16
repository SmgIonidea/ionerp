<?php

/**
 * Description: Controller logic for Activity List, Add, Edit and Delete in faculty screen
 * Author: Ranjita Naik
 * Created: 15-11-2017
 * Modification History:
 * Date				Modified By				Description
 */


if(!defined('BASEPATH'))
    exit('No direct script access allowed');

class ActivityFaculty extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('activity/faculty/ActivityFaculty_model');
    }


    /**
     * Function to get activity list
     * Params: 
     * Return: activity list
     */

    public function index() {
        $activityData = $this->readHttpRequest();
        $formData = json_decode($activityData);

        $activityList = $this->ActivityFaculty_model->getActivityList($formData);
        
        echo json_encode($activityList);
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
     * Function to add an activity
     * Params: 
     * Return: status
     */
    
    public function createActivity() {
        $incomingData = $this->readHttpRequest();
        $formData = json_decode($incomingData);

        $createResult = $this->ActivityFaculty_model->createActivity($formData);

        if($createResult == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }

        echo json_encode($data);
    }


    /**
     * Function to update an activity
     * Params: 
     * Return: status
     */
    
    public function updateActivity() {
        $incomingData = $this->readHttpRequest();
        $formData = json_decode($incomingData);

        $updateResult = $this->ActivityFaculty_model->updateActivity($formData);

        if($updateResult == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }

        echo json_encode($data);
    }


    /**
     * Function to filter activities
     * Params: 
     * Return: activity list
     */
    
    public function filterActivity() {
        $incomingData = $this->readHttpRequest();
        $formData = json_decode($incomingData);

        $activityList = $this->ActivityFaculty_model->filterActivity($formData);
        
        echo json_encode($activityList);
    }


    /**
     * Function to delete an Activity
     * Params: 
     * Return: status
     */

    public function deleteActivity() {
        $incomingData = $this->readHttpRequest();
        $formData = json_decode($incomingData);

        $deleteResult = $this->ActivityFaculty_model->deleteActivity($formData);

        if($deleteResult == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }
        
        echo json_encode($data);
    }


    /**
     * Function to get student usn and name
     * Params: 
     * Return: student list
     */

    public function getStudentNames() {
        $incomingData = $this->readHttpRequest();
        $data = json_decode($incomingData);

        $studentListResult = $this->ActivityFaculty_model->getStudentNames($data);

        echo json_encode($studentListResult);
    }


    /**
     * Function to get activity details to display in manage students page
     * Params: 
     * Return: activity details
     */
    
    public function getActivityDetails() {
        $incomingData = $this->readHttpRequest();
        $id = json_decode($incomingData);

        $activityDet = $this->ActivityFaculty_model->getActivityDetails($id);

        echo json_encode($activityDet);
    }


    /**
     * Function to get student usn
     * Params: 
     * Return: student usn
     */

    public function getStudentUsn() {
        $incomingData = $this->readHttpRequest();
        $data = json_decode($incomingData);

        $studentUsn = $this->ActivityFaculty_model->getStudentUsn($data);

        echo json_encode($studentUsn);
    }


    /**
     * Function to save student list
     * Params: 
     * Return: status
     */

    public function saveStudent() {
        $incomingData = $this->readHttpRequest();
        $formData = json_decode($incomingData);

        $studentSave = $this->ActivityFaculty_model->saveStudent($formData);
        
        if($studentSave == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }
        
        echo json_encode($data);
    }


    /**
     * Function to get student list to disable
     * Params: 
     * Return: student id
     */
    
    public function getStudentIdDisable() {
        $incomingData = $this->readHttpRequest();
        $data = json_decode($incomingData);

        $studentId = $this->ActivityFaculty_model->getStudentIdDisable($data);
       
        echo json_encode($studentId);
    }


    /**
     * Function to get entity id and delivery config value
     * Params: 
     * Return: entity id and delivery config
     */
    
    public function getDeliveryConfig() {
        $deliveryConfig = $this->ActivityFaculty_model->getDeliveryConfig();

        echo json_encode($deliveryConfig);
    }


    /**
     * Function to get delivery flag
     * Params: 
     * Return: delivery config
     */

    public function getDeliveryFlag() {
        $incomingData = $this->readHttpRequest();
        $data = json_decode($incomingData);

        $deliveryFlag = $this->ActivityFaculty_model->getDeliveryFlag($data);

        echo json_encode($deliveryFlag);
    }

    /**
     * Function to get student data to calculate progress
     * Params: 
     * Return: student list
     */
    
    public function getStudentsToGetProgress() {
        $activityData = $this->readHttpRequest();
        $formData = json_decode($activityData);

        $studentProgressList = $this->ActivityFaculty_model->getStudentsToGetProgress($formData);
        
        echo json_encode($studentProgressList);
    }


    /**
     * Function to get curriculum list
     * Params: 
     * Return: curriculum list
     */
    
    public function getCurriculum() {
        $incomingData = $this->readHttpRequest();
        $formData = json_decode($incomingData);

        $curriculumList = $this->ActivityFaculty_model->getCurriculum($formData);

        echo json_encode($curriculumList);
    }


    /**
     * Function to get term list
     * Params: 
     * Return: term list
     */

    public function getTerm() {
        $incomingData = $this->readHttpRequest();
        $formData = json_decode($incomingData);

        $result = $this->ActivityFaculty_model->getTerm($formData);

        echo json_encode($result);
    }


    /**
     * Function to get course list
     * Params: 
     * Return: course list
     */

    public function getCourse() {
        $incomingData = $this->readHttpRequest();
        $formData = json_decode($incomingData);

        $result = $this->ActivityFaculty_model->getCourse($formData);

        echo json_encode($result);
    }


    /**
     * Function to get section list
     * Params: 
     * Return: section list
     */
    
    public function getSection() {
        $incomingData = $this->readHttpRequest();
        $formData = json_decode($incomingData);

        $result = $this->ActivityFaculty_model->getSection($formData);

        echo json_encode($result);
    }


    /**
     * Function to get topic list
     * Params: 
     * Return: topic list
     */
    
    public function getTopic() {
        $incomingData = $this->readHttpRequest();
        $formData = json_decode($incomingData);

        $result = $this->ActivityFaculty_model->getTopic($formData);

        echo json_encode($result);
    }


    /**
     * Function to get rubrics finalize status
     * Params: 
     * Return: finalize status
     */
    
    public function getRubricsFinalizeStatus() {
        $rubricsData = $this->readHttpRequest();
        $formData = json_decode($rubricsData);

        $finalizeStatus = $this->ActivityFaculty_model->getRubricsFinalizeStatus($formData);

        echo json_encode($finalizeStatus);
    }

}
?>