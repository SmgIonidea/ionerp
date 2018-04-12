<?php

/**
 * Description          :	Controller logic for Criterion 2 (TIER 1) - Program curriculum.
 * Created              :	3-8-2015
 * Author               :       
 * Modification History :
 * Date                         Modified by                      Description
 * 3-8-2015                     Jevi V. G.              Added file headers, public function headers, indentations & comments.
 * 24-4-2016                    Arihant Prasad          Rework, indentation and code cleanup
  --------------------------------------------------------------------------------------------------------------- */
?>

<?php

defined('BASEPATH') or exit('No direct script access allowed');

class T1ug_c2_curriculum extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('nba_sar/ug/tier1/criterion_2/t1ug_curriculum_model');
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
     * Function to display Curriculum Structure - section 2.1.2
     * @parameters  : 
     * @return      : view
     */
    public function curriculum_structure($id = '', $nba_sar_id = '', $nba_report_id = '', $other_info = array(), $is_export = 0, $param = '') {
        extract($other_info);

        $filters = $this->nba_sar_list_model->nba_filters_list($id, $nba_report_id);

        $this->data['view_id'] = $id;
        $this->data['nba_report_id'] = $nba_report_id;
        $crs_list = $filter_list_data = $content = '';
        $curriculum_id = '';

        if (!empty($filters['filter_list'])) {
            foreach ($filters['filter_list'] as $filter_list_value) {
                $filter_list_data [$filter_list_value ['filter_ids']] = $filter_list_value ['filter_value'];
                if ($filter_list_value['filter_ids'] == 'curriculum_list') {
                    $curriculum_id = $filter_list_value['filter_value'];
                    $content = $this->display_curriculum_structure($filter_list_value ['filter_value']);
                }
            }
        } else {
            $filter_list_value ['filter_ids'] = '';
        }

        $this->data['filter_list'] = $filter_list_data;
        $this->data['curriculum_list'] = $this->nba_sar_list_model->list_curriculum($pgm_id);
        $this->data['is_export'] = $is_export;
        $this->data['curriculum_structure_vw'] = empty($content) ? '' : $content;

        if ($is_export) {
            return $content;
        } else {
            return $this->load->view('nba_sar/ug/tier1/criterion_2/t1ug_c2_1_2_curriculum_structure_vw', $this->data, true);
        }
    }

    /**
     * Function to fetch course details
     * @parameters  : curriculum id, nba view id, nba report id, 
     * @return      : object
     */
    public function display_curriculum_structure($id = '') {
        if (empty($id)) {
            $curriculum = $this->input->post('curriculum');
        } else {
            $curriculum = $id;
        }

        $component_detail = $this->t1ug_curriculum_model->get_curriculum_structure($curriculum);
        $this->data ['curriculum_structure_detail'] = $component_detail;

        $content_view = $this->load->view('nba_sar/ug/tier1/criterion_2/t1ug_c2_1_2_curriculum_structure_contents_vw', $this->data, true);
        $content['curriculum_structure_view'] = $content_view;

        if (empty($id)) {
            echo json_encode($content);
        } else {
            return $content['curriculum_structure_view'];
        }
    }

    /**
     * Function to display Curriculum Components - section 2.1.3
     * @parameters  :
     * @return      :
     */
    public function curriculum_components($id = '', $nba_sar_id = '', $nba_report_id = '', $other_info = array(), $is_export = 0, $param = '') {
        extract($other_info);

        $filters = $this->nba_sar_list_model->nba_filters_list($id, $nba_report_id);
        $this->data['view_id'] = $id;
        $this->data['nba_report_id'] = $nba_report_id;
        $crs_list = $filter_list_data = $content = array();
        $curriculum_id = '';

        if (!empty($filters['filter_list'])) {
            foreach ($filters['filter_list'] as $filter_list_value) {
                $filter_list_data [$filter_list_value ['filter_ids']] = $filter_list_value ['filter_value'];
                if ($filter_list_value['filter_ids'] == 'curriculum_list') {
                    $curriculum_id = $filter_list_value['filter_value'];
                }
            }
            $content = $this->display_curriculum_components($curriculum_id, $id, $nba_report_id, $param);
        }

        $this->data['filter_list'] = $filter_list_data;
        $this->data['curriculum_list'] = $this->nba_sar_list_model->list_curriculum($pgm_id);
        $this->data['is_export'] = $is_export;
        $this->data['component_vw'] = empty($content['component_view']) ? '' : $content['component_view'];

        if ($is_export) {
            return empty($content['component_view']) ? '' : $content['component_view'];
        } else {
            return $this->load->view('nba_sar/ug/tier1/criterion_2/t1ug_c2_1_3_component_vw', $this->data, true);
        }
    }

    /**
     * Function to fetch curriculum components table 
     * @parameters  : 
     * @return      : table
     */
    public function display_curriculum_components($id = '', $nba_sar_view_id = '', $nba_id = '', $param = '') {
        if (empty($id)) {
            $curriculum = $this->input->post('curriculum');
        } else {
            $curriculum = $id;
        }

        $component_detail = $this->t1ug_curriculum_model->get_curriculum_components($curriculum);
        $this->data ['component_detail'] = $component_detail;

        $content_view = $this->load->view('nba_sar/ug/tier1/criterion_2/t1ug_c2_1_3_component_contents_vw', $this->data, true);
        $content['component_view'] = $content_view;

        if (empty($id)) {
            echo json_encode($content);
        } else {
            return $content;
        }
    }

}

/* * End of file t1ug_c2_curriculum.php
 * Location: .nba_sar/t1ug_c2_curriculum.php 
 */
?>