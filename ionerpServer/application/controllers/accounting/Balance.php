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

class Balance extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('accounting/Balance_model');
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
        $balanceSheetFormData = $this->readHttpRequest();
        $formData = json_decode($balanceSheetFormData);

        $blanceList = $this->Balance_model->getBalancesheetList($formData);
        echo json_encode($blanceList);
    }

    public function getpaidout() {
        $balanceSheetFormData = $this->readHttpRequest();
        $formData = json_decode($balanceSheetFormData);

        $outblanceList = $this->Balance_model->getpaidout($formData);
        echo json_encode($outblanceList);
    }

    public function getFinancialYear() {
        $financeyear = $this->Balance_model->getFinancialYear();
        echo json_encode($financeyear);
    }

    public function getPaidinTotal() {
        $balanceSheetFormData = $this->readHttpRequest();
        $formData = json_decode($balanceSheetFormData);

        $totalpaidin = $this->Balance_model->getPaidinTotal($formData);
        echo json_encode($totalpaidin);
        
    }

    public function getPaidoutTotal() {
        $balanceSheetFormData = $this->readHttpRequest();
        $formData = json_decode($balanceSheetFormData);

        $totalpaidout = $this->Balance_model->getPaidoutTotal($formData);
        echo json_encode($totalpaidout);
        
    }

    public function readHttpRequest() {
        $incomingFormData = file_get_contents('php://input');
        return $incomingFormData;
    }

}

?>