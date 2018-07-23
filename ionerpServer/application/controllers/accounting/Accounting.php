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

class Accounting extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('accounting/Accounting_model');
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
        $accountingList = $this->Accounting_model->getAccountingList();
        if ($flag == 1) {
            return $accountingList;
        }
        $data['accountingList'] = $accountingList;
//        $data = $assignmentList;

        echo json_encode($data);
    }

    public function getUnderGrps() {
//        $assignmentFormData = $this->readHttpRequest();
//        $formData = json_decode($assignmentFormData);
        $undergrps = $this->Accounting_model->getUnderGrps();
        echo json_encode($undergrps);
    }

    /*
      Function to Add the Assignment
      @param: name, initialdate,enddate,totalmarks,instruction
      @return: Assignment List
      @result: Assignment List
      Created : 10/10/2017
     */

    public function createAccount() {
        $accountingFormData = $this->readHttpRequest();
        $formData = json_decode($accountingFormData);
        $createResult = $this->Accounting_model->createAccount($formData);
        $assignmentListFlag = 1;
        //  $assignmentList = $this->index($assignmentListFlag); //  call department List
        if ($createResult == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }
        //  $data['assignmentList'] = $assignmentList;
        echo json_encode($data);
    }

    public function updateAccount() {
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $updateResult = $this->Accounting_model->updateAccount($formData);
        //$depListFlag = 1;
        if ($updateResult == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }
        //$departmentList = $this->index($depListFlag); //  call department List
        //$data['departmentList'] = $departmentList;
        echo json_encode($data);
    }

    public function deleteaccount() {
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $deleteResult = $this->Accounting_model->deleteAccount($formData);
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