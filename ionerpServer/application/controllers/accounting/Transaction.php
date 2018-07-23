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

class Transaction extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('accounting/Transaction_model');
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

//        $accountingFormData = $this->readHttpRequest();
//        $formData = json_decode($accountingFormData);
        $transactionList = $this->Transaction_model->getTransactionList();
        if ($flag == 1) {
            return $transactionList;
        }
        $data = $transactionList;

        echo json_encode($data);
        
    }

    public function getVoucherType() {
//        $assignmentFormData = $this->readHttpRequest();
//        $formData = json_decode($assignmentFormData);
        $voucherType = $this->Transaction_model->getVoucherType();
        echo json_encode($voucherType);
    }
    public function getparticulars(){
        $voucherType = $this->Transaction_model->getparticulars();
        echo json_encode($voucherType);
    }

    /*
      Function to Add the Assignment
      @param: name, initialdate,enddate,totalmarks,instruction
      @return: Assignment List
      @result: Assignment List
      Created : 10/10/2017
     */

    public function vounchernum(){
        $vouchernumresult = $this->Transaction_model->vounchernum();
        echo json_encode($vouchernumresult);
    }
    
    public function createVoucher() {
        
        $voucherFormData = $this->readHttpRequest();
        $formData = json_decode($voucherFormData);
        $voucherResult = $this->Transaction_model->createVoucher($formData);
        $assignmentListFlag = 1;
        //  $assignmentList = $this->index($assignmentListFlag); //  call department List
        if ($voucherResult == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }
        //  $data['assignmentList'] = $assignmentList;
        echo json_encode($data);
    }

    public function updateVoucher() {
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $updateResult = $this->Transaction_model->updateVoucher($formData);
        
        if ($updateResult == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }
        
        echo json_encode($data);
    }

    public function deleteVoucher() {
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $deleteResult = $this->Transaction_model->deleteVoucher($formData);
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
    
      public function readHttpRequest() {
        $incomingFormData = file_get_contents('php://input');
        return $incomingFormData;
    }
}

?>