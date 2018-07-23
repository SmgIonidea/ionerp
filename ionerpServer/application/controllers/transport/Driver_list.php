<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Controller Logic for Driver list module with Add, Edit Functionality.
 * Variable and Function Naming: cammelCaseNotation is followed (First letter of the first word should be small and First letter of secon word onwards should be capital.)
 * Modification History:
 * Date			Modified By				Description
 * 09-07-2018		Suchitra V      	Added file headers, function headers & comments.
  ---------------------------------------------------------------------------------------------------------------------------------
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Driver_list extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('transport/Driver_list_model');
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
      Function to Fetch the existing vehicle details
      @param:
      @return:
      @result: vehicle details
      Created : 06/07/2018
     */
    
    public function getDriverDetails(){
        
        $driverFormData = $this->readHttpRequest();
        $formData = json_decode($driverFormData);
        $driverDetails = $this->Driver_list_model->getDriverData($formData);
        echo json_encode($driverDetails);
    }
    
    public function saveDriverDetails(){
        
        $driverListFormData = $this->readHttpRequest();
        $formData = json_decode($driverListFormData);
        $driverList = $this->Driver_list_model->saveDriverData($formData);
        echo json_encode($driverList);
    }
    
    public function updateDriverDetails(){
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $updateResult = $this->Driver_list_model->updateDriverData($formData);       
        if ($updateResult == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }        
        echo json_encode($data);
    }

    public function deleteDriverDetails(){
        
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $updateResultStatus = $this->Driver_list_model->deleteDriverData($formData);   

        if ($updateResultStatus == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }   
        echo json_encode($data);
        
    }
    
    /*
      Function to Upload Driver License File
      @param:
      @return:
      @result:
      Created : 19-07-2018
     */

    public function upload($driverName,$driverlicense) {

//        $incomingFormData = $this->readHttpRequest();
//        $formData = json_decode($incomingFormData);

        $fileUploadResult = $this->Driver_list_model->fileUpload($driverName,$driverlicense);
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
      Function to Update Uploaded Driver License File
      @param:
      @return:
      @result:
      Created : 19-07-2018
     */
     public function uploadUpdate($driverId, $driverName, $driverLicense) {

//        $incomingFormData = $this->readHttpRequest();
//        $formData = json_decode($incomingFormData);
        $fileUploadUpdateResult = $this->Driver_list_model->fileUploadUpdate($driverId, $driverName,$driverLicense);
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
    
    public function readHttpRequest() {
        $incomingFormData = file_get_contents('php://input');
        return $incomingFormData;
    }
    
}