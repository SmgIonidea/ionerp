<?php

/**
 * Description          :   Controller logic for Criterion 3 (3.1) ( Diploma TIER 2) - Correlation between the courses and the PO and PSO.
 * Created              :   04-06-2017
 * Author               :   Shayista Mulla
 * Modification History :
 * Date                     Modified by                      Description
  --------------------------------------------------------------------------------------------------------------- */
?>
<?php

defined('BASEPATH') or exit('No direct script access allowed');

class T2dip_c3_cos extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('nba_sar/dip/tier2/criterion_3/t2dip_c3_cos_model');
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
     * Function to display Course Outcomes (COs) - section 3.1.1
     * @parameters  : 
     * @return      : view
     */
    public function co($id = '', $nba_sar_id = '', $nba_report_id = '', $other_info = array(), $is_export = 0, $param = '') {
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
                } else {
                    $crs_list[$filter_list_value ['filter_value']] = true;
                }
            }
            $content = $this->display_co($curriculum_id, $crs_list, $id, $nba_report_id, $param);
        }

        $this->data['filter_list'] = $filter_list_data;
        $this->data['curriculum_list'] = $this->nba_sar_list_model->list_curriculum($pgm_id);
        $this->data['is_export'] = $is_export;
        $this->data['co_vw'] = empty($content['co_view']) ? '' : $content['co_view'];
        $this->data['clo_vw'] = empty($content['clo_view']) ? '' : $content['clo_view'];

        if ($is_export) {
            return $content['clo_view'];
        } else {
            return $this->load->view('nba_sar/dip/tier2/criterion_3/t2dip_c3_1_1_cos_vw', $this->data, true);
        }
    }

    /**
     * Function to display Course list (semester-wise) and Course Outcomes (COs) - section 3.1.1
     * @parameters  : 
     * @return      : view
     */
    public function display_co($curriculum_id = '', $crs_list = array(), $nba_sar_view_id = '', $nba_report_id = '', $param='') {
        if (empty($curriculum_id)) {
            $curriculum = $this->input->post('curriculum');
            $view_nba_id = $this->input->post('view_nba_id');
            $view_nba_id_contrainer = explode('_', $view_nba_id);
            $this->data ['view_id'] = $nba_sar_view_id = $view_nba_id_contrainer[0];
            $this->data ['nba_report_id'] = $nba_report_id = $view_nba_id_contrainer[1];
            $filters = $this->nba_sar_list_model->nba_filters_list($nba_sar_view_id, $nba_report_id);

            if (!empty($filters['filter_list'])) {
                foreach ($filters['filter_list'] as $filter_list_value) {
                    $filter_list_data [$filter_list_value ['filter_ids']] = $filter_list_value ['filter_value'];
                    if ($filter_list_value['filter_ids'] == 'cos_checkbox_list') {
                        $crs_list[$filter_list_value ['filter_value']] = true;
                    }
                }
            }
        } else {
            $curriculum = $curriculum_id;
        }

        $clo_detail = $this->nba_sar_list_model->display_course_list($curriculum, 0);
        $this->data ['clo_detail'] = $clo_detail;
        $this->data ['crs_list'] = $crs_list;
        $content_view = $this->load->view('nba_sar/dip/tier2/criterion_3/t2dip_c3_1_1_cos_contents_vw', $this->data, true);
        $clo_view = $this->display_clo($curriculum, $nba_sar_view_id, $nba_report_id);
        $content['co_view'] = $content_view;
        $content['clo_view'] = $clo_view;

        if (empty($curriculum_id)) {
            echo json_encode($content);
        } else {
            return $content;
        }
    }

    /**
     * Function to display Course Outcomes (COs) - section 3.1.1
     * @parameters  : 
     * @return      : view
     */
    public function display_clo($curriculum = '', $nba_sar_view_id = '', $nba_report_id = '') {
        if (empty($curriculum)) {
            $view_form = $this->input->post('view_form', true);
            $curriculum_id = $this->input->post('curriculum', true);
            $crs_list = $this->input->post('crs_id_array');
            $clo_detail = $this->t2dip_c3_cos_model->get_clo($curriculum_id, '', '', 0, $crs_list);
        } else {
            $clo_detail = $this->t2dip_c3_cos_model->get_clo($curriculum, $nba_sar_view_id, $nba_report_id);
        }

        $this->data['clo_detail'] = $clo_detail;
        $content['clo_view'] = $this->load->view('nba_sar/dip/tier2/criterion_3/t2dip_c3_1_1_clo_contents_vw', $this->data, true);

        if (empty($curriculum)) {
            echo json_encode($content);
        } else {
            return $content['clo_view'];
        }
    }

    /**
     * Function to display CO-PO matrices of courses selected in 3.1.1 - section 3.1.2
     * @parameters  : 
     * @return      : view
     */
    public function co_po($id = '', $nba_sar_id = '', $nba_report_id = '', $other_info = array(), $is_export = 0, $param = '') {
        $data_without_pso = $this->t2dip_c3_cos_model->clo_po_mapping($param, 0);
        $this->data['without_pso'] = $this->load->view('nba_sar/dip/tier2/criterion_3/t2dip_c3_1_2_co_po_contents_vw', $data_without_pso, true);
        $data_with_pso = $this->t2dip_c3_cos_model->clo_po_mapping($param, 1);
        $this->data['with_pso'] = $this->load->view('nba_sar/dip/tier2/criterion_3/t2dip_c3_1_2_co_po_contents_vw', $data_with_pso, true);

        if ($is_export) {
            return $this->data['without_pso'] . $this->data['with_pso'];
        } else {
            return $this->load->view('nba_sar/dip/tier2/criterion_3/t2dip_c3_1_2_co_po_vw', $this->data, true);
        }
    }

    /**
     * Function to display course - PO matrice - section 3.1.3
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

        if (!empty($filters['filter_list'])) {
            foreach ($filters['filter_list'] as $filter_list_value) {
                $filter_list_data [$filter_list_value ['filter_ids']] = $filter_list_value ['filter_value'];
                if ($filter_list_value['filter_ids'] == 'curriculum_list') {
                    $curriculum_id = $filter_list_value['filter_value'];
                }
            }
            if ($curriculum_id != '') {
                $content = $this->display_course_po($curriculum_id);
            }
        }

        $this->data['filter_list'] = $filter_list_data;
        $this->data['curriculum_list'] = $this->nba_sar_list_model->list_curriculum($pgm_id);
        $this->data['course_po'] = empty($content) ? '' : $content;

        if ($is_export) {
            return $content;
        } else {
            return $this->load->view('nba_sar/dip/tier2/criterion_3/t2dip_c3_1_3_course_po_vw', $this->data, true);
        }
    }

    /**
     * Function to display course - PO matrice tabel- section 3.1.3
     * @parameters  : curriculum id
     * @return      : table
     */
    public function display_course_po($curriculum_id = '') {
        if (empty($curriculum_id)) {
            $curriculum = $this->input->post('curriculum');
        } else {
            $curriculum = $curriculum_id;
        }

        $data_course_po = $this->t2dip_c3_cos_model->course_po_mapping($curriculum);

        if (!empty($data_course_po)) {
            $data['course_po'] = $this->load->view('nba_sar/dip/tier2/criterion_3/t2dip_c3_1_3_course_po_contents_vw', $data_course_po, true);
        } else {
            $data['course_po'] = '';
        }

        if (empty($curriculum_id)) {
            echo json_encode($data['course_po']);
        } else {
            return $data['course_po'];
        }
    }

}

/*
 * End of file t2dip_c3_cos.php
 * Location: .nba_sar/t2dip_c3_cos.php 
 */
?>