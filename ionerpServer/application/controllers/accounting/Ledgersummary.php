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

class Ledgersummary extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('accounting/Ledgersummary_model');
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

    public function index() {
//        $balanceSheetFormData = $this->readHttpRequest();
//        $formData = json_decode($balanceSheetFormData);

        $ledgerTypeList = $this->Ledgersummary_model->getLedgerType();
        echo json_encode($ledgerTypeList);
    }

    public function getledgersummary() {
        $legsummaryFormData = $this->readHttpRequest();
        $formData = json_decode($legsummaryFormData);

        $legSummaryList = $this->Ledgersummary_model->getledgersummary($formData);
        echo json_encode($legSummaryList);
    }

    public function getOpeningBalance() {
        $legsummaryFormData = $this->readHttpRequest();
        $formData = json_decode($legsummaryFormData);

        $openBalList = $this->Ledgersummary_model->openingBalance($formData);
        echo json_encode($openBalList);
    }

    public function gettotal() {
        $legsummaryFormData = $this->readHttpRequest();
        $formData = json_decode($legsummaryFormData);

        $credittotal = $this->Ledgersummary_model->creditTotal($formData);
        $debittotal = $this->Ledgersummary_model->debitTotal($formData);
        
        
        foreach($credittotal as $key => &$val){ // Loop though one array
    $val2 = $debittotal[$key]; // Get the values from the other array
    $val += $val2; // combine 'em
        }
        echo json_encode($credittotal);
    }

    public function readHttpRequest() {
        $incomingFormData = file_get_contents('php://input');
        return $incomingFormData;
    }

}

?>