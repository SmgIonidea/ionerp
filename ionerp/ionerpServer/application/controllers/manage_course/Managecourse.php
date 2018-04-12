<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Controller Logic for ManageCourse List, Insert and  Edit Functionality.
 * Variable and Function Naming: cammelCaseNotation is followed (First letter of the first word should be small and First letter of secon word onwards should be capital.)
 * Modification History:
 * Date				Modified By				Description
 * 27-10-2017		         Pallavi G       	Added file headers, function headers & comments.
  ---------------------------------------------------------------------------------------------------------------------------------
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Managecourse extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('manage_course/managecourse_model');
        $this->load->library('form_validation');
        /* 		
          Below mentioned headers are required to read the data coming from deifferent port.
          Access Control Headers.
         */
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: X-Requested-With');
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS,PUT');
    }
    
    public function getProgram() {
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);

        $result = $this->managecourse_model->get_program_details($formData);
        echo json_encode($result);
    }

    public function getCurriculum() {
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $result = $this->managecourse_model->get_curriculum_details($formData);
        echo json_encode($result);
    }

    public function getTerm() {
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $result = $this->managecourse_model->get_term_details($formData);
        echo json_encode($result);
    }

    public function getCourse() {
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $result = $this->managecourse_model->get_course_details($formData);
        echo json_encode($result);
    }

    public function getSection() {
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $result = $this->managecourse_model->get_section_details($formData);
        echo json_encode($result);
    }

    /*
      Function to List the ManageCourse
      @param:
      @return:
      @result: Topic and Instructor list
      Created : 23/11/2017
     */

    public function getManageCourse($flag = NULL) {
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $manageCourse = $this->managecourse_model->get_managecourse_details($formData);
        if ($flag == 1) {
            return $manageCourse;
        }
        echo json_encode($manageCourse);
    }

    /*
      Function to get status
      @param:
      @return:
      @result: fetching the data according the ststus flag
      Created : 27/11/2017
     */

    public function getManageCourseStatus($flag = NULL) {
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $data = $this->managecourse_model->get_managecourse_status($formData);
        if ($data == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }
        echo json_encode($data);
    }
    
    
    /*
      Function to get status
      @param:
      @return:
      @result: fetching the data according the ststus flag
      Created : 28/11/2017
     */

    public function getManageCourseProceedStatus($flag = NULL) {
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $data = $this->managecourse_model->get_managecourse_proceedstatus($formData);
        if ($data == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }
        echo json_encode($data);
    }
    
    
    public function getManageCourse1($flag = NULL) {
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $manageCourse = $this->managecourse_model->get_managecourse_details1($formData);
        if ($flag == 1) {
            return $manageCourse;
        }
        echo json_encode($manageCourse);
    }

    /*
      Function to List the ManageCourseInstructor
      @param:
      @return:
      @result: Instructor list
      Created : 10/10/2017
     */

    public function getManageCoursedrop($flag = NULL) {
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);

        $manageDropdownlist = $this->managecourse_model->get_managecourse_dropdown($formData);
        if ($flag == 1) {
            return $manageDropdownlist;
        }
        echo json_encode($manageDropdownlist);
    }

    /*
      Function to  Edit the ManageCourseInstructor
      @param:
      @return:
      @result: Edited List
      Created : 23/11/2017
     */

    public function manageInstructorUpdate() {
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $createResult = $this->managecourse_model->update_manage_course($formData);
        if ($createResult == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }
        echo json_encode($data);
    }

    /*
      Function to Edit the status
      @param:
      @return:
      @result: status result from 0 to 1
      Created : 23/11/2017
     */

    public function proceedToDelivery() {
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $createResult = $this->managecourse_model->update_proceed_delivery($formData);
        if ($createResult == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }
        echo json_encode($data);
    }

    /*
      Function to Insert the Delivery lesson schedule
      @param:
      @return:
      @result: Insert data from dlvry_map_instructor_topic and topic_lesson_schedule to dlvry_lesson_schedule
      Created : 24/11/2017
     */

    public function insertToDelivery() {
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $createResult = $this->managecourse_model->insert_lesson_schedule($formData);
//        var_dump($createResult);exit;
        if ($createResult == true) {
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
      Created: 9/10/2017

     */

    public function readHttpRequest() {
        $incomingFormData = file_get_contents('php://input');

        return $incomingFormData;
    }

}
