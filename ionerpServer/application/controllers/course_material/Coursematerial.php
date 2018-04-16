<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Controller Logic for Course Material List and Add, Edit, Delete Functionality.
 * Variable and Function Naming: cammelCaseNotation is followed (First letter of the first word should be small and First letter of secon word onwards should be capital.)
 * Modification History:
 * Date				Modified By				Description
 * 25-10-2017                    Indira A       	Added file headers, function headers & comments.
  ---------------------------------------------------------------------------------------------------------------------------------
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Coursematerial extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('course_material/Coursematerial_model');
        $this->load->library('form_validation');
    }

    /*
      Function to List the Course Material
      @param:
      @return:
      @result: Course Material List
      Created : 09-10-2017
     */

    public function index($flag = NULL) {
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $ShareCourseList = $this->Coursematerial_model->getShareCourseMaterialList($formData);

        if ($flag == 1) {
            return $ShareCourseList;
        }
        $data['ShareCourseList'] = $ShareCourseList;
        echo json_encode($data);
    }

    /*
      Function to List the Course Material
      @param:
      @return:
      @result: Course Material List
      Created : 09-10-2017
     */

    public function receive($flag = NULL) {
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $ReceiveCourseList = $this->Coursematerial_model->getReceiveCourseMaterialList($formData);

        if ($flag == 1) {
            return $ReceiveCourseList;
        }
        $data['ReceiveCourseList'] = $ReceiveCourseList;
        echo json_encode($data);
    }

    /*
      Function to List the Topics
      @param:
      @return:
      @result: Topics List
      Created : 11-10-2017
     */

    public function topic($flag = NULL) {
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $TopicList = $this->Coursematerial_model->getTopicList($formData);

        if ($flag == 1) {
            return $TopicList;
        }
        echo json_encode($TopicList);
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

    /*
      Function to Add the Course Material
      @param: filename or url, topics, information
      @return: Course Material List
      @result: Course Material List
      Created : 10-10-2017
     */

    public function createShareCourseMaterial() {
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $createResult = $this->Coursematerial_model->createShareCourseMaterial($formData);
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
      Function to Update the Course Material
      @param: filename or url, topics, information, matId
      @return: Course Material List
      @result: Course Material List
      Created : 11-10-2017
     */

    public function updateShareCourseMaterial() {
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);

        $updateResult = $this->Coursematerial_model->updateShareCourseMaterial($formData);
//        $shareCrsListFlag = 1;
        if ($updateResult == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }
//        $shareCourseMaterialList = $this->index($shareCrsListFlag); //  call department List
//        $data['shareCourseMaterialList'] = $shareCourseMaterialList;
        echo json_encode($data);
    }

    /*
      Function to Delete the Course Material
      @param: filename or url, topics, information, matId
      @return: Course Material List
      @result: Course Material List
      Created : 11-10-2017
     */

    public function deleteShareCourseMaterial() {
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);

        $deleteResult = $this->Coursematerial_model->deleteShareCourseMaterial($formData);

        if ($deleteResult == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }
//        $shareCourseMaterialList = $this->index($shareCrsListFlag); //  call department List
//        $data['shareCourseMaterialList'] = $shareCourseMaterialList;
        echo json_encode($data);
    }

    /*
      Function to Upload File
      @param:
      @return:
      @result:
      Created : 12-10-2017
     */

    public function upload($curriculum, $course) {

//        $incomingFormData = $this->readHttpRequest();
//        $formData = json_decode($incomingFormData);

        $fileUploadResult = $this->Coursematerial_model->fileUpload($curriculum, $course);
//        $shareCrsListFlag = 1;
        if ($fileUploadResult == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }
//        $shareCourseMaterialList = $this->index($shareCrsListFlag); //  call department List
//        $data['shareCourseMaterialList'] = $shareCourseMaterialList;
        echo json_encode($data);
    }
    /*
      Function to Update Uploaded File
      @param:
      @return:
      @result:
      Created : 12-10-2017
     */
     public function uploadUpdate($matId, $curriculum, $course) {

//        $incomingFormData = $this->readHttpRequest();
//        $formData = json_decode($incomingFormData);
        $fileUploadUpdateResult = $this->Coursematerial_model->fileUploadUpdate($matId, $curriculum, $course);
//        $shareCrsListFlag = 1;
        if ($fileUploadUpdateResult == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }
//        $shareCourseMaterialList = $this->index($shareCrsListFlag); //  call department List
//        $data['shareCourseMaterialList'] = $shareCourseMaterialList;
        echo json_encode($data);
    }
}

?>
