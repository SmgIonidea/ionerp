<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class MaintenanceDetails extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('transport/MaintenanceDetails_model');
        $this->load->library('form_validation');
        /* 		
          Below mentioned headers are required to read the data coming from deifferent port.
          Access Control Headers.
         */
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: X-Requested-With');
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS,PUT');
        /*
          Global variable to read the file contents from Angular Http Request.
         */
        $incomingFormData = file_get_contents('php://input');
    }

    public function getMaintenanceStatus() {
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $data = $this->MaintenanceDetails_model->get_maintainance_detailstatus($formData);

        echo json_encode($data);
    }

    public function readHttpRequest() {
        $incomingFormData = file_get_contents('php://input');
        return $incomingFormData;
    }

    public function getMaintenanceList() {

        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $data = $this->MaintenanceDetails_model->get_maintainance_list($formData);
        echo json_encode($data);
    }

    public function getBusList() {

        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $data = $this->MaintenanceDetails_model->getBusList($formData);
        echo json_encode($data);
    }

    public function getVoucherList() {

        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $data = $this->MaintenanceDetails_model->getVoucherList($formData);
        echo json_encode($data);
    }

    public function getLedgerList() {

        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $data = $this->MaintenanceDetails_model->getLedgerList($formData);
        echo json_encode($data);
    }

    public function getMaintenanceDetails() {

        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $editDetails = $this->MaintenanceDetails_model->getMaintenanceDetails($formData);
    }

    public function getVoucherDeatils() {
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $voucherDetails = $this->MaintenanceDetails_model->getVoucherDeatils($formData);
        echo json_encode($voucherDetails);
    }
    
    public function editMaintenance(){
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $maintananceDetails = $this->MaintenanceDetails_model->editMaintenance($formData);
        echo json_encode($maintananceDetails);
        
    }
    
    public function delMaintenance(){
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $delMaintananceDetails = $this->MaintenanceDetails_model->delMaintenance($formData);
        echo json_encode($delMaintananceDetails);
        
        
    }

}
