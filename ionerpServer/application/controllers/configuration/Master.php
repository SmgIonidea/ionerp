<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Controller Logic for Department List and Add, Edit Functionality.
 * Variable and Function Naming: cammelCaseNotation is followed (First letter of the first word should be small and First letter of secon word onwards should be capital.)
 * Modification History:
 * Date				Modified By				Description
 * 15-08-2017		Mritunjay B S       	Added file headers, function headers & comments.
  ---------------------------------------------------------------------------------------------------------------------------------
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Master extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('configuration/master/Master_model');
        $this->load->library('form_validation');
        $this->load->library('title');
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

    public function index() {
        
    }

    public function title_name() {
        $data = $this->title->title();
        echo json_encode($data);
    }

    public function getProgram() {
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);

        $result = $this->Master_model->get_program_details($formData);
        echo json_encode($result);
    }

    public function getCurriculum() {
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $result = $this->Master_model->get_curriculum_details($formData);
        echo json_encode($result);
    }

    public function getTerm() {
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $result = $this->Master_model->get_term_details($formData);
        echo json_encode($result);
    }

    public function getCourse() {
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $result = $this->Master_model->get_course_details($formData);
        echo json_encode($result);
    }

    public function getSection() {
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $result = $this->Master_model->get_section_details($formData);
        echo json_encode($result);
    }
    public function getTopics(){
        $topic = $this->readHttpRequest();
        $topic = json_decode($topic);
        $result = $this->Master_model->getTopicDetails($topic);
        echo json_encode($result);
        
    }

    public function drop() {

        $dropdata = $this->readHttpRequest();
        $dropdata = json_decode($dropdata);
        $a = $dropdata->curclmDrop;
        $b = $dropdata->termDrop;
        $sql = "select crs_code,crs_title from course where crclm_id='$a' and crclm_term_id='$b' ";
        $query = $this->db->query($sql);
        $result = $query->result();
        echo json_encode($result);
    }

    public function readHttpRequest() {
        $incomingFormData = file_get_contents('php://input');

        return $incomingFormData;
    }
    
}

?>