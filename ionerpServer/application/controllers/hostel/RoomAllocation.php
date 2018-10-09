<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Controller Logic for Account List and Add, Edit Functionality.
 * Variable and Function Naming: cammelCaseNotation is followed (First letter of the first word should be small and First letter of secon word onwards should be capital.)
 * Modification History:
 * Date				Modified By				Description
 * 27-10-2017		Deepa N G      	Added file headers, function headers & comments.
  ---------------------------------------------------------------------------------------------------------------------------------
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class RoomAllocation extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('hostel/RoomAllocation_model');
        $this->load->library('form_validation');
        /* 		
          Below mentioned headers are required to read the data coming from deifferent port.
          Access Control Headers.
         */
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: X-Requested-With');
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT');
    }

    /*
      Function to List the Assignment
      @param:
      @return:
      @result: Assignment List
      Created : 9/10/2017
     */

    public function index($flag = NULL) {

//        $accountingFormData = $this->readHttpRequest();
//        $formData = json_decode($accountingFormData);
//        $roomList = $this->RoomAllocation_model->getRoomList();
//        if ($flag == 1) {
//            return $roomList;
//        }
//        $data['roomList'] = $roomList;
//        echo json_encode($data);
    }

    public function getBuilding() {

        $buliding = $this->RoomAllocation_model->getbuildingList();
        $data = $buliding;
        echo json_encode($data);
    }

    public function selectRoom() {
        $roomFormData = $this->readHttpRequest();
        $formData = json_decode($roomFormData);
        $roomData = $this->RoomAllocation_model->selectRoom($formData);
        echo json_encode($roomData);
    }

    public function getroomdetails() {
        $roomFormData = $this->readHttpRequest();
        $formData = json_decode($roomFormData);
        $roomdetailsData = $this->RoomAllocation_model->getroomdetails($formData);
        echo json_encode($roomdetailsData);
    }

    public function loaddata() {
        $roomFormData = $this->readHttpRequest();
        $formData = json_decode($roomFormData);
        $roomallocation = $this->RoomAllocation_model->loaddata($formData);
        echo json_encode($roomallocation);
    }

    public function roomallotment() {
        $roomFormData = $this->readHttpRequest();
        $formData = json_decode($roomFormData);
        $createResult = $this->RoomAllocation_model->roomallotment($formData);
        if ($createResult == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }
        echo json_encode($data);
    }

    public function checkroomvacancy() {
        $roomFormData = $this->readHttpRequest();
        $formData = json_decode($roomFormData);
        $roomvacancy = $this->RoomAllocation_model->checkroomvacancy($formData);
        if ($roomvacancy == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }

        echo json_encode($data);
    }
    public function validregno(){
        $roomFormData = $this->readHttpRequest();
        $formData = json_decode($roomFormData);
        $validreg = $this->RoomAllocation_model->validregno($formData);

        if($validreg == true){
            $data['status'] = 'ok';
        }
        else{
            $data['status'] = 'fail';
        }
        echo json_encode($data);
        
    }

    public function issueitems() {
        $roomFormData = $this->readHttpRequest();
        $formData = json_decode($roomFormData);
        $issuitems = $this->RoomAllocation_model->issueitems($formData);
        echo json_encode($issuitems);
    }

    public function itemcode() {
        $itemcode = $this->RoomAllocation_model->itemcode();
        echo json_encode($itemcode);
    }

    public function itemname() {
        $itemname = $this->RoomAllocation_model->itemname();
        echo json_encode($itemname);
    }

    public function particularitemname() {
        $roomFormData = $this->readHttpRequest();
        $formData = json_decode($roomFormData);
        $name = $this->RoomAllocation_model->particularitemname($formData);
        echo json_encode($name);
    }

    public function inserthostelpersonitem() {
        $roomFormData = $this->readHttpRequest();
        $formData = json_decode($roomFormData);
        $hostelitem = $this->RoomAllocation_model->inserthostelpersonitem($formData);

        if ($hostelitem == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }
        echo json_encode($data);
    }

    public function gethostelhealthrecord() {
        $hosteldata = $this->readHttpRequest();
        $formData = json_decode($hosteldata);
        $gethostel = $this->RoomAllocation_model->gethostelhealthrecord($formData);
        echo json_encode($gethostel);
    }
    
    public function inserthealthrecord(){
        $hosteldata = $this->readHttpRequest();
        $formData = json_decode($hosteldata);
        $inserthostel = $this->RoomAllocation_model->inserthealthrecord($formData);
        if($inserthostel == true){
            $data['status'] = 'ok';
        }
        else{
            $data['status'] = 'fail';
        }
        echo json_encode($data);
        
    }
    public function deallocate(){
        $hosteldata = $this->readHttpRequest();
        $formData = json_decode($hosteldata);
        $getdeallocate = $this->RoomAllocation_model->deallocate($formData);
        echo json_encode($getdeallocate);
        
    }
    
    public function deallocateroom(){
        $hosteldata = $this->readHttpRequest();
        $formData = json_decode($hosteldata);
        $deallocateroom = $this->RoomAllocation_model->deallocateroom($formData);
        if($deallocateroom == true){
            $data['status'] ='ok';
        }
        else{
            $data['status'] = 'fail';
        }
        echo json_encode($data);
    }
      public function checkdates(){
        $hosteldata = $this->readHttpRequest();
        $formData = json_decode($hosteldata);
        $checkdates = $this->RoomAllocation_model->checkdates($formData);
        if($checkdates == true){
            $data['status'] ='ok';
        }
        else{
            $data['status'] = 'fail';
        }
        echo json_encode($data);
    }
    
    public function getreportitems(){
         $hosteldata = $this->readHttpRequest();
        $formData = json_decode($hosteldata);
        $reportitems = $this->RoomAllocation_model->getreportitems($formData);
        echo json_encode($reportitems);
        
    }
    public function getreporthealth(){
         $hosteldata = $this->readHttpRequest();
        $formData = json_decode($hosteldata);
        $reporthostel = $this->RoomAllocation_model->getreporthealth($formData);
        echo json_encode($reporthostel);
        
    }

    public function readHttpRequest() {
        $incomingFormData = file_get_contents('php://input');
        return $incomingFormData;
    }

}

?>