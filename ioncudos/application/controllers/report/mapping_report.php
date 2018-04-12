<?php

/**
 * Description		:	Generates Mapping Report

 * Created		:	February 23 2016

 * Author		:	Shayista Mulla

 * Modification History:
 *   Date                Modified By                         Description
 * 
  ------------------------------------------------------------------------------------------ */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Mapping_report extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('report/mapping_report/mapping_report_model');
    }

    /**
     * Function is to check authentication, to fetch curriculum details and to load mapping report page
     * @return: load lesson plan view page
     */
    public function index() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            $current_curriculum = $this->mapping_report_model->fetch_curriculum();

            $data['curriculum'] = $current_curriculum;
            $data['course'] = array(
                'name' => 'course',
                'id' => 'course',
                'class' => 'required',
                'placeholder' => 'Select Course'
            );

            $data['po_list'] = array(
                'name' => 'po_list',
                'id' => 'po_list',
                'class' => 'required'
            );
            $data['title'] = "Mapping Report";
            $this->load->view('report/mapping_report/mapping_report_vw', $data);
        }
    }

    /**
     * Function to fetch term details
     * @return: an object
     */
    public function fetch_term() {
        $curriculum_id = $this->input->post('curriculum_id');
        $data = $this->mapping_report_model->fetch_term($curriculum_id);
        $term_data = $data['term_name_result'];

        $i = 0;
        $list[$i] = '<option value = ""> Select Term </option>';
        $i++;

        foreach ($term_data as $data) {
            $list[$i] = "<option value = " . $data['crclm_term_id'] . ">" . $data['term_name'] . "</option>";
            $i++;
        }
        $list = implode(" ", $list);
        echo $list;
    }

    /**
     * Function is to check authentication, to fetch program outcomes
     * @return: list of program outcomes.
     */
    public function fetch_po_statement() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            $curriculum_id = $this->input->post('curriculum_id');
            $option_id = $this->input->post('option_id');
            $data = $this->mapping_report_model->fetch_po_statement($curriculum_id);

            if ($option_id == 1) {
                foreach ($data as $po) {
                    echo '<tr><td style="font-size:12px"><b><font color="#8E2727">' . $po['peo_reference'] . '. ' . '</font></b>' . $po['peo_statement'] . '</td></tr></br>';
                }
            } else if ($option_id == 2) {
                foreach ($data as $po) {
                    //if ($po['pso_flag'] == 0) {
                    echo '<tr><td style="font-size:12px"><b><font color="#8E2727">' . $po['peo_reference'] . '. ' . '</font></b>' . $po['peo_statement'] . '</td></tr></br>';
                    //}
                }
            } else {
                foreach ($data as $po) {
                    //if ($po['pso_flag'] == 1) {
                    echo '<tr><td style="font-size:12px"><b><font color="#8E2727">' . $po['peo_reference'] . '. ' . '</font></b>' . $po['peo_statement'] . '</td></tr></br>';
                    //}
                }
            }
        }
    }

    /*
     * Function is to fetch the course list of particular curriculum and term.
     * @param - curriculum id and term id is used to fetch the list of courses related to that curriculum and term.
     * returns the list of courses.
     */

    public function fetch_course() {
        $crclm_id = $this->input->post('curriculum_id');
        $term_id = $this->input->post('term_id');

        $course_data = $this->mapping_report_model->course_drop_down_fill($crclm_id, $term_id);
        $course_data = $course_data['course_list'];

        $i = 0;
        $list[$i] = '<option value="">Select Course</option>';
        $i++;
        foreach ($course_data as $data) {
            $list[$i] = "<option value=" . $data['crs_id'] . ">" . $data['crs_title'] . "</option>";
            $i++;
        }
        $list = implode(" ", $list);
        echo $list;
    }

    /*
     * Function is to fetch peo to po mapped grid of particular curriculum and term.
     * @param - curriculum id and term id is used to fetch the list of mapping data related to that curriculum and term.
     * returns the mapping grid.
     */

    public function map_po_to_peo() {
        $curriculum_id = $this->input->post('curriculum_id');
        $option_id = $this->input->post('option_id');
        $results = $this->mapping_report_model->map_po_peo($curriculum_id);
        $data['justification'] = $this->mapping_report_model->peo_justification($curriculum_id);
        $data['option_id'] = $option_id;
        $data['po_list'] = $results['po_list'];
        $data['peo_list'] = $results['peo_list'];
        $data['crclm_id'] = $curriculum_id;
        $data['mapped_po_peo'] = $results['mapped_po_peo'];
        $data['map_level'] = $results['map_level'];
        $data['title'] = $this->lang->line('so') . ' to PEO Mapping Page';
        $this->load->view('report/mapping_report/map_peo_to_po_table_vw', $data);
    }

    /*
     * Function is to fetch co to po mapped grid of particular curriculum and term.
     * @param - curriculum id and term id is used to fetch the list of mapping data related to that curriculum and term.
     * returns the mapping grid.
     */

    public function map_co_to_po() {

        $curriculum_id = $this->input->post('crclm_id');
        $term_id = $this->input->post('term_id');
        $course_id = $this->input->post('course_id');
        $co_to_po_data = $this->mapping_report_model->map_co_to_po($curriculum_id, $term_id, $course_id);
        $co_to_po_data['justification'] = $this->mapping_report_model->justification_details($curriculum_id, $term_id, $course_id);
        $co_to_po_data['option_id'] = $this->input->post('option_id');
        $co_to_po_data['status'] = $this->input->post('map_list');
        $this->load->view('report/mapping_report/map_co_to_po_table_vw', $co_to_po_data);
    }

    /* Function is used to generates a PDF file of the mapped report view.
     * @param-
     * @retuns - the PDF file of mapped report details.
     */

    public function export_pdf() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            $report = $this->input->post('pdf');
            $curriculum = $this->input->post('curr');
            ini_set('memory_limit', '528M');

            $header .= '<b><font style="font-size:16; color:green;">' . "Curriculum: " . $curriculum . "</font></b>";
            $this->load->helper('pdf_helper');
            $report = $header . $report;
            pdf_create($report, 'mapping_report', 'L');
            return;
        }
    }

}
