<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Controller Logic for lesson List and Add, Edit Functionality.
 * Variable and Function Naming: cammelCaseNotation is followed (First letter of the first word should be small and First letter of secon word onwards should be capital.)
 * Modification History:
 * Date				Modified By				Description
 * 27-11-2017		Deepa N G      	Added file headers, function headers & comments.
  ---------------------------------------------------------------------------------------------------------------------------------
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class LessonSchedule extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('lesson_schedule/LessonSchedule_model');
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

    /*
      Function to List the lessonschedule
      @param:crclmvalue,termvalue,crsvalue,topicvalue,userid
      @return:
      @result: lessonschedule List
      Created : 27/11/2017
     */

    public function index($flag = NULL) {
        $lessonFormData = $this->readHttpRequest();
        $formData = json_decode($lessonFormData);
        $lessonscheduleList = $this->LessonSchedule_model->getlessonscheduleList($formData);
        if ($flag == 1) {
            return $lessonscheduleList;
        }
        $data = $lessonscheduleList;
        echo json_encode($data);
    }

    /*
      Function to get crclm list
      @param:insrtuctor_id
      @return:
      @result: crclm List
      Created : 24/11/2017
     */

    public function getcurriculum() {
        $lessonFormData = $this->readHttpRequest();
        $formData = json_decode($lessonFormData);
        $curriculumList = $this->LessonSchedule_model->getCurriculumList($formData);
        echo json_encode($curriculumList);
    }

    /*
      Function to update lss_schedule_status
      @param:less_id
      @return:
      @result: new lesson schedule List with changed status
      Created : 29/11/2017
     */

    public function inprogress() {
        $lessonFormData = $this->readHttpRequest();
        $formData = json_decode($lessonFormData);

        $updateResult = $this->LessonSchedule_model->updateInprogress($formData);

        if ($updateResult == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }
        echo json_encode($data);
    }

    /*
      Function to update lss_schedule_status
      @param:less_id
      @return:
      @result: new lesson schedule List with changed status
      Created : 29/11/2017
     */

    public function complete() {
        $lessonFormData = $this->readHttpRequest();
        $formData = json_decode($lessonFormData);

        $updateCompleteResult = $this->LessonSchedule_model->updateComplete($formData);

        if ($updateCompleteResult == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }
        echo json_encode($data);
    }

    /*
      Function to update lss_schedule_status
      @param:less_id
      @return:
      @result: new lesson schedule List with changed status
      Created : 29/11/2017
     */

    public function reopen() {
        $lessonFormData = $this->readHttpRequest();
        $formData = json_decode($lessonFormData);
        $updatereopenResult = $this->LessonSchedule_model->updatereopen($formData);

        if ($updatereopenResult == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }
        echo json_encode($data);
    }
    /*
      Function to get serial number to add new row
      @param:instructor_id ,topic_id
      @return:
      @result: results serial number to add new row
      Created : 29/11/2017
     */

    public function slno() {
        $lessonFormData = $this->readHttpRequest();
        $formData = json_decode($lessonFormData);
        $slnoResult = $this->LessonSchedule_model->slno($formData);

        if (!empty($slnoResult[0]['sl'])) {
            $lessonslno = $slnoResult[0]['sl'];
            $lessonslno = $lessonslno + 1;
        } else {
            $lessonslno = 1;
        }

        echo json_encode($lessonslno);
    }

    /*
      Function to get bloom level values
      @param:crclm_id
      @return:
      @result:bloom level list
      Created : 27/11/2017
     */

    public function getBloomDropdown() {
        $lessonFormData = $this->readHttpRequest();
        $formData = json_decode($lessonFormData);
        $lessonBloom = $this->ManageAssignment_model->getBloom($formData);
        echo json_encode($lessonBloom);
    }

    /*
      Function to get delivry method values
      @param:crclm_id
      @return:
      @result: delivery method list
      Created : 27/11/2017
     */

    public function getdeliveryMethodDrop() {
        $lessonFormData = $this->readHttpRequest();
        $formData = json_decode($lessonFormData);
        $lessonDelivery = $this->LessonSchedule_model->getDeliveryMethod($formData);
        echo json_encode($lessonDelivery);
    }

    /*
      Function to Add portion
      @param:  initialdate,enddate,portion,crclmid,termid,courseid,topicid,secid,instructorid
      @return: lesson  List
      @result: lesson List
      Created : 28/11/2017
     */

    public function createLessonSchedule() {
        $lessonFormData = $this->readHttpRequest();
        $formData = json_decode($lessonFormData);
        $lessonDelivery = $this->LessonSchedule_model->createLessonSchedule($formData);
        if ($lessonDelivery == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }
        echo json_encode($data);
    }

    /*
      Function to Update the Lesson Schedule
      @param: Slno,Delivery method,Bloom's level,Startdate,Completiondate,portioncovered
      @return: Lesson Schedule List
      @result: Lesson Schedule List
      Created : 6/12/2017
     */

    public function updateLesson() {
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $createResult = $this->LessonSchedule_model->update_lesson_schedule($formData);
        if ($createResult == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }
        echo json_encode($data);
    }

    /*
      Function to get the row data in lesson schedule
      @param: Slno,Delivery method,Bloom's level,Startdate,Completiondate,portioncovered
      @return: Lesson Schedule row data
      @result: Lesson Schedule row data
      Created : 6/12/2017
     */

    public function geteditlessonSchedule() {
        $assignmentFormData = $this->readHttpRequest();
        $formData = json_decode($assignmentFormData);
        $assignmentEditQuest = $this->LessonSchedule_model->editLessonData($formData);
        echo json_encode($assignmentEditQuest);
    }

    /*
      Function to disable portion covered
      @param: portioncovered
      @return: topic_lesson_schedule_flag=1
      @result: topic_lesson_schedule_flag=1
      Created : 6/12/2017
     */

    public function geteditlessonSchedule1($flag = NULL) {
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $data = $this->LessonSchedule_model->editLessonData1($formData);
        if ($data == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }
        echo json_encode($data);
    }
/*
      Function to check to  Delete portion or not
      @param:less_schd_id
      @return:
      @result:returns true if that can be deleted by logged in user
      Created : 12/12/2017
     */
    public function checkTopiclessFlag(){
       $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $checkResult = $this->LessonSchedule_model->checkTopiclessFlag($formData);
        if ($checkResult == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }
        echo json_encode($data);
    }
    /*
      Function to Delete portion
      @param:
      @return:
      @result:
      Created : 30/11/2017
     */

    public function deleteLesson() {
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $deleteResult = $this->LessonSchedule_model->deleteLessonSchedule($formData);
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
      Function to Check Duplicate Serial Number
      @param:slno,lesson id,dropdownvalues
      @return:
      @result:slno
      Created : 11/01/2018
     */

    public function checkduplicateserialnumber() {
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $duplicateValue = $this->LessonSchedule_model->checkduplicateserialnumber($formData);
        if (empty($duplicateValue)) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }
        echo json_encode($data);
    }
    /*
      Function to Check Duplicate Serial Number In Edit
      @param:slno,lesson id,dropdownvalues
      @return:
      @result:slno
      Created : 12/01/2018
     */

    public function checkEditDuplicateSerialNum() {
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $slno = $formData->slno;

        $serialcheck = $this->LessonSchedule_model->serialexsists($formData);
        foreach ($serialcheck as $val) {
            $temp = $val->slno;
            if ($temp == $slno)
                {
                $data['status'] = 'ok';
            } 
            else {
                $duplicateValue = $this->LessonSchedule_model->checkduplicateserialnumber($formData);
                if (empty($duplicateValue)) {
                    $data['status'] = 'ok';
                } else {
                    $data['status'] = 'fail';
                }
            }
        }
        echo json_encode($data);       
    }

    public function readHttpRequest() {
        $incomingFormData = file_get_contents('php://input');
        return $incomingFormData;
    }

}

?>