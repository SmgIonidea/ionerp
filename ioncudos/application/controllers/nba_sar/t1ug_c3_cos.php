<?php

/**
 * Description          :	Controller logic for Criterion 3(3.1) (TIER 1) - Correlation between the courses and the PO and PSO.
 * Created              :	04-04-2017
 * Author               :	Shayista Mulla
 * Modification History :
 * Date                         Modified by                      Description
  --------------------------------------------------------------------------------------------------------------- */
?>
<?php

defined('BASEPATH') or exit('No direct script access allowed');

class T1ug_c3_cos extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('nba_sar/ug/tier1/criterion_3/t1ug_c3_cos_model');
        $this->load->model('nba_sar/nba_sar_list_model');
    }

    /**
     * Show all navigation groups
     * @parameters  :
     * @return      :
     */
    public function index() {
        if (!$this->ion_auth->logged_in()) {
            // redirect them to the login page
            redirect('dashboard/dashboard', 'refresh');
        } else if (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            // redirect them to the home page because they must be an administrator or program owner to view this
            redirect('curriculum/clo/blank', 'refresh');
        } else {
            $data ['node_id'] = $this->input->post('node_id', true);
            $data ['include_js'] = $this->nba_sar_list_model->get_js($data ['node_id']);
            $this->load->view('nba_sar/list/index', $data);
        }
    }

    /**
     * Function to display Program articulation matrix and course articulation matrix - Section 3.1
     * @parameters  :
     * @return      : view
     */
    public function course_po($id = '', $nba_sar_id = '', $nba_report_id = '', $other_info = array(), $is_export = 0, $param = '') {
        extract($other_info);
        $filters = $this->nba_sar_list_model->nba_filters_list($id, $nba_report_id);
        $this->data['view_id'] = $id;
        $this->data['nba_report_id'] = $nba_report_id;
        $curriculum_id = '';
        $filter_list_data = array();
        $content = '';
        $cos_checkbox_list = array();

        if (!empty($filters['filter_list'])) {

            foreach ($filters['filter_list'] as $filter_list_value) {
                $filter_list_data [$filter_list_value ['filter_ids']] = $filter_list_value ['filter_value'];

                if ($filter_list_value['filter_ids'] == 'curriculum_list') {
                    $curriculum_id = $filter_list_value['filter_value'];
                } else if ($filter_list_value['filter_ids'] == 'cos_checkbox_list') {
                    array_push($cos_checkbox_list, $filter_list_value['filter_value']);
                }
            }

            $cos_checkbox = implode(',', $cos_checkbox_list);
            $content = $this->display_course_po($curriculum_id, $cos_checkbox, $is_export, $id, $nba_report_id);
        }

        $this->data['filter_list'] = $filter_list_data;
        $this->data['curriculum_list'] = $this->nba_sar_list_model->list_curriculum($pgm_id);
        $this->data['course_po'] = empty($content) ? '' : $content;
        $this->data['is_export'] = $is_export;

        return $this->load->view('nba_sar/ug/tier1/criterion_3/t1ug_c3_1_course_po_vw', $this->data, true);
    }

    /**
     * Function to fetch Program articulation matrix and course articulation matrix tables - Section - 3.1
     * @parameters  : curriculum id,selected course id's and export status
     * @return      : tables
     */
    public function display_course_po($curriculum_id = '', $selected_courses = NULL, $is_export = null, $id = NULL, $nba_report_id = NULL) {
        if (empty($curriculum_id)) {
            $curriculum = $this->input->post('curriculum');
        } else {
            $curriculum = $curriculum_id;
        }

        $data_course_po = $this->t1ug_c3_cos_model->course_po_mapping($curriculum);
        $course_list = $this->nba_sar_list_model->display_course_list($curriculum, 0);
        $this->data['data_course_po'] = $data_course_po;
        $this->data['co_po_map'] = $this->clo_po_mapping($curriculum, $selected_courses);
        $this->data_one['selected_courses'] = $selected_courses;
        $this->data_one['clo_detail'] = $course_list;
        $this->data_one['is_export'] = $is_export;
        $this->data_one['view_id'] = $id;
        $this->data_one['nba_report_id'] = $nba_report_id;
        $this->data['crs_list_view'] = $this->load->view('nba_sar/ug/tier1/criterion_3/t1ug_c3_1_course_view', $this->data_one, true);

        if (!empty($data_course_po)) {
            $data['course_po'] = $this->load->view('nba_sar/ug/tier1/criterion_3/t1ug_c3_1_course_po_contents_vw', $this->data, true);
        } else {
            $data['course_po'] = '';
        }

        if (empty($curriculum_id)) {
            echo json_encode($data['course_po']);
        } else {
            return $data['course_po'];
        }
    }

    /**
     * Function to fetch the mapping of clo to po and mapping of clo to pso - Section - 3.1
     * @parameters  : curriculum id and selected course id's
     * @return      : tables
     */
    public function clo_po_mapping($crclm_id = NULL, $selected_courses = NULL) {
        if ($crclm_id != NULL) {
            $mapping_data = '';

            if ($selected_courses != NULL) {
                $data_without_pso = $this->t1ug_c3_cos_model->clo_po_mapping($crclm_id, $selected_courses, 0);
                $this->data['without_pso'] = $this->load->view('nba_sar/ug/tier1/criterion_3/t1ug_c3_1_course_co_po_contents_vw', $data_without_pso, true);
                $data_with_pso = $this->t1ug_c3_cos_model->clo_po_mapping($crclm_id, $selected_courses, 1);
                $this->data['with_pso'] = $this->load->view('nba_sar/ug/tier1/criterion_3/t1ug_c3_1_course_co_po_contents_vw', $data_with_pso, true);
                $mapping_data = $this->load->view('nba_sar/ug/tier1/criterion_3/t1ug_c3_1_course_co_po_vw', $this->data, true);
            }
            return $mapping_data;
        } else {
            $crs_id_array = $this->input->post('crs_id_array');
            $crclm_id = $this->input->post('crclm_id');
            $mapping_data = '';

            if (!empty($crs_id_array)) {
                $crs_ids = implode(",", $crs_id_array);
                $data_without_pso = $this->t1ug_c3_cos_model->clo_po_mapping($crclm_id, $crs_ids, 0);
                $this->data['without_pso'] = $this->load->view('nba_sar/ug/tier1/criterion_3/t1ug_c3_1_course_co_po_contents_vw', $data_without_pso, true);
                $data_with_pso = $this->t1ug_c3_cos_model->clo_po_mapping($crclm_id, $crs_ids, 1);
                $this->data['with_pso'] = $this->load->view('nba_sar/ug/tier1/criterion_3/t1ug_c3_1_course_co_po_contents_vw', $data_with_pso, true);
                $mapping_data = $this->load->view('nba_sar/ug/tier1/criterion_3/t1ug_c3_1_course_co_po_vw', $this->data);
            }
            echo $mapping_data;
        }
    }

}

/*
 * End of file t1ug_c3_cos.php
 * Location: .nba_sar/t1ug_c3_cos.php
 */
?>