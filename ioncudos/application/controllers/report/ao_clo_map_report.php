<?php

/**
 * Description	:	Select Curriculum and then select the related term (semester) which
  will display related course. For each Course its related Assessment Occasions (AO)
  to Course Outcomes (CO) mapping grid will be displayed.

 * Created		:	Oct 26th, 2015

 * Author		:	Abhinay B.Angadi

 * Modification History:
 *   Date                Modified By                         Description
 * 27-10-2015		   Abhinay B.Angadi			File header, function headers, indentation 
  and comments.
  ---------------------------------------------------------------------------------------------- */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ao_clo_map_report extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('report/ao_clo_map/ao_clo_map_report_model');
    }

    /**
     * Function to check authentication, fetch curriculum details and load clo to po map report page
     * @return: load course learning objective to program outcome map report page
     */
    public function index() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            $curriculum_name = $this->ao_clo_map_report_model->clo_po();
            $data['curriculum_result'] = $curriculum_name['curriculum_result'];

            $data['curriculum_id'] = array(
                'name' => 'crclm_id',
                'id' => 'crclm_id',
                'class' => 'required',
                'type' => 'hidden',
            );

            $data['title'] = "AO To CO mapped Report";
            $this->load->view('report/ao_clo_map/ao_clo_map_report_vw', $data);
        }
    }

    /**
     * Function to fetch term details
     * @return: an object
     */
    public function select_term() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            $curriculum_id = $this->input->post('curriculum_id');
            $term_data = $this->ao_clo_map_report_model->clo_po_select($curriculum_id);
            $term_data = $term_data['term_result_data'];

            $i = 0;
            $list[$i] = '<option value=""> Select Term </option>';
            $i++;

            foreach ($term_data as $data) {
                $list[$i] = "<option value=" . $data['crclm_term_id'] . ">" . $data['term_name'] . "</option>";
                $i++;
            }

            $list = implode(" ", $list);
            echo $list;
        }
    }

    /**
     * Function to fetch course details
     * @return: course id and course title
     */
    public function term_course_details() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            $curriculum_id = $this->input->post('curriculum_id');
            $term_id = $this->input->post('curriculum_term_id');

            $term_data = $this->ao_clo_map_report_model->term_course_details($curriculum_id, $term_id);
            $term_data = $term_data['course_result_data'];

            $i = 0;
            $list[$i] = '<option value=""> Select Course </option>';
            $i++;

            foreach ($term_data as $data) {
                $list[$i] = "<option value=" . $data['crs_id'] . ">" . $data['crs_title'] . "</option>";
                $i++;
            }
            $list = implode(" ", $list);
            echo $list;
        }
    }

    /**
     * Function to display grid on select of course
     * @return: load course learning objective to program outcome table view page
     */
    public function ao_clo_mapping() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            $term_id = $this->input->post('curriculum_term_id');
            $curriculum_id = $this->input->post('curriculum_id');
            $course_id = $this->input->post('course_id');
            $ao_clo_details = $this->ao_clo_map_report_model->ao_clo_mapping($course_id, $term_id, $curriculum_id);

            if (!empty($ao_clo_details)) {
                $data['ao_clo_view'] = $this->load->view('report/ao_clo_map/ao_clo_contents_vw', $ao_clo_details, true);
            } else {
                $data['ao_clo_view'] = '';
            }
            if ($data['ao_clo_view'] != "")
                $this->load->view('report/ao_clo_map/ao_clo_map_report_table_vw', $data);
            else {
                $data= $this->lang->line('entity_clo_full').' ('.$this->lang->line('entity_clo').')';
                echo "<font color='red'>Assessment Occasions (AOs) to ". $data ." Mapping is not defined. </font>";
            }
        }
    }

    //Function to export mapping of course learning outcome to program outcome in .pdf format
    public function export_pdf() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            if (!$this->ion_auth->logged_in()) {
                //redirect them to the login page
                redirect('login', 'refresh');
            } elseif ((!$this->ion_auth->is_admin() && !$this->ion_auth->in_group('Program Owner') && !$this->ion_auth->in_group('Course Owner'))) {
                redirect('configuration/users/blank', 'refresh');
            } elseif ((!$this->ion_auth->is_admin() || !$this->ion_auth->in_group('Program Owner') || !$this->ion_auth->in_group('Course Owner'))) {
                $report = $this->input->post('pdf');
                ini_set('memory_limit', '500M');
                $header = '<p align="left"><b><font style="font-size:18; color:#8E2727;"> AO to CO Mapped Report (Coursewise)</font></b></p>';
                $content = $header . "<br>" . $report;
                $this->load->helper('pdf');
                pdf_create($content, 'ao_clo_map_report', 'L');

                return;
            }
        }
    }

}

/*
 * End of file ao_clo_map_report.php
 * Location: .report/ao_clo_map_report.php 
 */
?>