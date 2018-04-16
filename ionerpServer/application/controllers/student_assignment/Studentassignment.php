<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Controller Logic for StudentAssignment List 
 * Variable and Function Naming: cammelCaseNotation is followed (First letter of the first word should be small and First letter of secon word onwards should be capital.)
 * Modification History:
 * Date				Modified By				Description
 * 07-12-2017		         Indira A                	Added file headers, function headers & comments.
  ---------------------------------------------------------------------------------------------------------------------------------
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Studentassignment extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('student_assignment/studentassignment_model');
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
      Function to List the StudentAssignments
      @param:
      @return:
      @result: Assignment,Date and Totalmarks List
      Created : 07/12/2017
     */

    public function getStudentAssignment($flag = NULL) {
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $studentAssignment = $this->studentassignment_model->get_studentassignment_details($formData);
        if ($flag == 1) {
            return $studentAssignment;
        }
        echo json_encode($studentAssignment);
    }

    /*
      Global Function to read the file contents from Angular Http Request.
      @param:
      @return:
      @result: Get Http Request COntent
      Created: 24/10/2017

     */

    public function readHttpRequest() {
        $incomingFormData = file_get_contents('php://input');

        return $incomingFormData;
    }

}
