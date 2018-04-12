<?php

/**
 * Description          :   Controller logic for Criterion 1 (Diploma TIER 2) - vision mission.
 * Created              :   04-06-2017
 * Author               :   Shayista Mulla       
 * Modification History :
 * Date                     Modified by                      Description
  --------------------------------------------------------------------------------------------------------------- */
?>

<?php

defined('BASEPATH') or exit('No direct script access allowed');

class T2dip_c1_vision_mission extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('nba_sar/dip/tier2/criterion_1/t2dip_c1_vision_mission_model');
        $this->load->model('nba_sar/nba_sar_list_model');
    }

    /**
     * Show all navigation groups
     * @parameters  :
     * @return      : void
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
     * Function to get vision and mission of institute - Criterion 1.1
     * @parameters  :
     * @return      : view
     */
    public function vision_mission() {
        $organization_id = 1;
        $organisation_detail = $this->t2dip_c1_vision_mission_model->get_organisation_by_id($organization_id);

        $this->data ['vision'] = array(
            'value' => $organisation_detail [0] ['vision']
        );

        $this->data ['mission'] = array(
            'value' => $organisation_detail [0] ['mission']
        );

        return $this->load->view('nba_sar/dip/tier2/criterion_1/t2dip_c1_1_mission_vision_vw', $this->data, true);
    }

    /**
     * Function to get vision and mission of department - Criterion 1.1
     * @parameters  :
     * @return      : view
     */
    public function dept_vision_mission($id = '', $nba_sar_id = '', $nba_report_id = '', $other_info = array(), $is_export = 0, $param = '') {
        extract($other_info);
        $this->data ['view_id'] = $id;
        $this->data ['nba_report_id'] = $nba_report_id;
        $dept_vision_mission_data['dept'] = $this->display_dept_vision_mission($dept_id);
        $this->data ['is_export'] = $is_export;
        $dept_data = empty($dept_vision_mission_data ['dept']) ? '' : $dept_vision_mission_data ['dept'];
        $this->data['dept_vision_mission_data'] = $dept_data;

        if ($is_export) {
            return $dept_data;
        } else {
            return $this->load->view('nba_sar/dip/tier2/criterion_1/t2dip_c1_1_dept_mission_vision_vw', $this->data, true);
        }
    }

    /**
     * Function is to fetch department vision mission tabels - Criterion 1.1
     * @parameters  : Department id
     * @return      : tabel
     */
    public function display_dept_vision_mission($id = '') {
        if (empty($id)) {
            $dept_id = $this->input->post('dept');
        } else {
            $dept_id = $id;
        }
        $dept_detail = $this->t2dip_c1_vision_mission_model->get_dept_by_id($dept_id);
        $content_view = '';

        if (!($dept_detail == null)) {

            $this->data ['dept_vision'] = array(
                'value' => $dept_detail [0] ['dept_vision']
            );

            $this->data ['dept_mission'] = array(
                'value' => $dept_detail [0] ['dept_mission']
            );
            $content_view = $this->load->view('nba_sar/dip/tier2/criterion_1/t2dip_c1_1_dept_mission_vision_contents_vw', $this->data, true);
        } else {
            $content_view = '<b>No Data to Display </b>';
        }

        if (empty($id)) {
            echo $content_view;
        } else {
            return $content_view;
        }
    }

    /**
     * Function to fetch peo view - Criterion 1.2
     * @parameters  :
     * @return      : view
     */
    public function peo($id = '', $nba_sar_id = '', $nba_report_id = '', $other_info = array(), $is_export = 0, $param = '') {
        extract($other_info);
        $filters = $this->nba_sar_list_model->nba_filters_list($id, $nba_report_id);
        $this->data ['view_id'] = $id;
        $this->data ['nba_report_id'] = $nba_report_id;
        $container = $filter_list_data = array();

        if (!empty($filters ['filter_list'])) {
            foreach ($filters ['filter_list'] as $filter_list_value) {
                $filter_list_data [$filter_list_value ['filter_ids']] = $filter_list_value ['filter_value'];
                if ($filter_list_value ['filter_ids'] == 'curriculum_list') {
                    $container [$filter_list_value ['filter_ids']] = $this->display_peo($filter_list_value ['filter_value']);
                }
            }
        }

        $this->data ['filter_list'] = $filter_list_data;
        $this->data ['curriculum_list'] = $this->nba_sar_list_model->list_curriculum($pgm_id);
        $this->data ['is_export'] = $is_export;
        $peo_vw_id = empty($container ['curriculum_list']) ? '' : $container['curriculum_list'];
        $this->data ['peo_vw_id'] = $peo_vw_id;

        if ($is_export) {
            return $peo_vw_id;
        } else {
            return $this->load->view('nba_sar/dip/tier2/criterion_1/t2dip_c1_2_peo_vw', $this->data, true);
        }
    }

    /**
     * Function to fetch peo tabel - Criterion 1.2
     * @parameters  :
     * @return      : tabel
     */
    public function display_peo($id = '') {
        if (empty($id)) {
            $curriculum = $this->input->post('curriculum');
        } else {
            $curriculum = $id;
        }

        $peo_detail = $this->t2dip_c1_vision_mission_model->get_peo($curriculum);
        $this->data ['peo_list'] = $peo_detail;
        $content_view = $this->load->view('nba_sar/dip/tier2/criterion_1/t2dip_c1_2_peo_contents_vw', $this->data, true);

        if (empty($id)) {
            echo $content_view;
        } else {
            return $content_view;
        }
    }

    /**
     * Function to fetch PEO to ME view - Criterion 1.5
     * @parameters  :
     * @return      : view
     */
    public function peo_me_map($id = '', $nba_sar_id = '', $nba_report_id = '', $other_info = array(), $is_export = 0, $param = '') {
        extract($other_info);
        $filters = $this->nba_sar_list_model->nba_filters_list($id, $nba_report_id);
        $this->data ['view_id'] = $id;
        $this->data ['nba_report_id'] = $nba_report_id;
        $container = $filter_list_data = array();

        if (!empty($filters ['filter_list'])) {
            foreach ($filters ['filter_list'] as $filter_list_value) {
                $filter_list_data [$filter_list_value ['filter_ids']] = $filter_list_value ['filter_value'];
                if ($filter_list_value ['filter_ids'] == 'curriculum_list') {
                    $container [$filter_list_value ['filter_ids']] = $this->display_peo_me($dept_id, $filter_list_value ['filter_value']);
                }
            }
        }

        $this->data ['filter_list'] = $filter_list_data;
        $this->data ['curriculum_list'] = $this->nba_sar_list_model->list_curriculum($pgm_id);
        $this->data ['is_export'] = $is_export;
        $peomeList = empty($container ['curriculum_list']) ? '' : $container ['curriculum_list'];
        $this->data ['peomeList'] = $peomeList;

        if ($is_export) {
            return $peomeList;
        } else {
            return $this->load->view('nba_sar/dip/tier2/criterion_1/t2dip_c1_5_peo_me_map_vw', $this->data, true);
        }
    }

    /**
     * Function to fetch and display mapping details - PEO to ME - section 1.5
     * @parameters: department id, curriculum id
     * @return: load peo to me table view page
     */
    public function display_peo_me($id_department = '', $id_curriculum = '') {
        if (empty($id_curriculum)) {
            $curriculum = $this->input->post('curriculum');
            $department = $this->input->post('department');
        } else {
            $curriculum = $id_curriculum;
            $department = $id_department;
        }

        $peo_me_detail = $this->t2dip_c1_vision_mission_model->get_map_peo_me($curriculum, $department);
        $content_view = $this->load->view('nba_sar/dip/tier2/criterion_1/t2dip_c1_5_peo_me_table_vw', $peo_me_detail, true);

        if (empty($id_curriculum)) {
            echo $content_view;
        } else {
            return $content_view;
        }
    }

}

/*
 * End of file t2dip_c1_vision_mission.php
 * Location: .nba_sar/t2dip_c1_vision_mission.php 
 */
?>