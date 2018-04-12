<?php

/**
 * Description          :   Controller logic for Criterion 5 (Diploma TIER 2) - Faculty Information and Contributions.
 * Created              :   07-06-2016
 * Author               :   Shayista Mulla 
 * Modification History :  
 * Date                     Modified By                 Description
  ---------------------------------------------------------------------------------------------------------- */
?>
<?php

defined('BASEPATH') or exit('No direct script access allowed');

class T2dip_c5_faculty extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('nba_sar/dip/tier2/criterion_5/t2dip_faculty_contribution_model');
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
            var_dump($data);
            $this->load->view('nba_sar/list/index', $data);
        }
    }

    /*
     * Function to fetch Faculty Information and contribution - section 5
     * @parameters  :
     * @return      : view
     */

    public function faculty_information_contribution($id = '', $nba_sar_id = '', $nba_report_id = '', $other_info = array(), $is_export = 0, $param = '') {
        extract($other_info);
        $filters = $this->nba_sar_list_model->nba_filters_list($id, $nba_report_id);
        $this->data['view_id'] = $id;
        $this->data['nba_report_id'] = $nba_report_id;
        $crs_list = $filter_list_data = $content = '';
        $year_id = '';

        if (!empty($filters ['filter_list'])) {
            foreach ($filters ['filter_list'] as $filter_list_value) {
                $filter_list_data [$filter_list_value ['filter_ids']] = $filter_list_value ['filter_value'];
                if ($filter_list_value ['filter_ids'] == 'year_list') {
                    $year_id = $filter_list_value['filter_value'];
                }
            }
            $content = $this->fetch_faculty_information_contributions($year_id);
        }

        $this->data['filter_list'] = $filter_list_data;
        $this->data['faculty_data'] = empty($content) ? '' : $content;

        if ($is_export) {
            return $content;
        }

        return $this->load->view('nba_sar/dip/tier2/criterion_5/t2dip_c5_faculty_information_contribution_vw', $this->data, true);
    }

    /*
     * Function to fetch faculty information contributions table - section 5
     * @parameters  : year id
     * @return      : table view
     */

    public function fetch_faculty_information_contributions($year_id = NULL) {
        if ($year_id == NULL) {
            $year = $this->input->post('year');
            $dept_id = $this->input->post('dept_id');
            $pgm_id = $this->input->post('pgm_id');
            $data['faculty_data'] = $this->t2dip_faculty_contribution_model->fetch_faculty_information_contribution($year, $dept_id);
            echo $this->load->view('nba_sar/dip/tier2/criterion_5/t2dip_c5_faculty_information_contribution_table_vw', $data, true);
        } else {
            $year = $year_id;
            $dept_id = $this->input->post('dept_id');
            $pgm_id = $this->input->post('pgm_id');
            $data['faculty_data'] = $this->t2dip_faculty_contribution_model->fetch_faculty_information_contribution($year, $dept_id);
            return $this->load->view('nba_sar/dip/tier2/criterion_5/t2dip_c5_faculty_information_contribution_table_vw', $data, true);
        }
    }

    /*
     * Function to fetch student faculty ratio view - Criterion 5.1 
     * @parameters  :
     * @return      : table
     */

    public function student_faculty_ratio($id = '', $nba_sar_id = '', $nba_report_id = '', $other_info = array(), $is_export = 0, $param = '') {
        extract($other_info);
        $filters = $this->nba_sar_list_model->nba_filters_list($id, $nba_report_id);
        $this->data['view_id'] = $id;
        $this->data['nba_report_id'] = $nba_report_id;
        $crs_list = $filter_list_data = $content = '';
        $curriculum_id = '';

        if (!empty($filters ['filter_list'])) {
            foreach ($filters ['filter_list'] as $filter_list_value) {
                $filter_list_data [$filter_list_value ['filter_ids']] = $filter_list_value ['filter_value'];
                if ($filter_list_value ['filter_ids'] == 'curriculum_list') {
                    $curriculum_id = $filter_list_value['filter_value'];
                }
            }
            $content = $this->fetch_student_faculty_ratio_sfr($curriculum_id);
        }

        if ($is_export) {
            return $content;
        }

        $this->data['crclm_id'] = @$crclm_id = $filter_list_data['curriculum_list'];
        $this->data['filter_list'] = $filter_list_data;
        $this->data['curriculum_list'] = $this->nba_sar_list_model->list_curriculum_year($pgm_id);
        $this->data['student_faculty_ratio'] = empty($content) ? '' : $content;

        return $this->load->view('nba_sar/dip/tier2/criterion_5/t2dip_c5_1_student_faculty_ratio_vw', $this->data, true);
    }

    /*
     * Function to fetch student faculty ratio table - section 5.1
     * @parameters  : curriculum id
     * @return      : table 
     */

    public function fetch_student_faculty_ratio_sfr($curriculum_id = NULL) {
        $this->load->library('table');
        $template = array(
            'table_open' => '<table class="table table-bordered" id="faculty_retention_info" >',
            'thead_open' => '',
            'thead_close' => '',
            'heading_row_start' => '<tr>',
            'heading_row_end' => '</tr>',
            'heading_cell_start' => '<th class="background-nba orange" width="250">',
            'heading_cell_end' => '</th>',
            'tbody_open' => '<tbody>',
            'tbody_close' => '</tbody>',
            'row_start' => '<tr>',
            'row_end' => '</tr>',
            'cell_start' => '<td align="right">',
            'cell_end' => '</td>',
            'row_alt_start' => '<tr>',
            'row_alt_end' => '</tr>',
            'cell_alt_start' => '<td>',
            'cell_alt_end' => '</td>',
            'table_close' => '</table>'
        );

        $this->table->set_template($template);

        if ($curriculum_id == NULL) {
            $crclm_id = $this->input->post('curriculum');
            $dept_id = $this->input->post('dept_id');
            $pgm_id = $this->input->post('pgm_id');
            $student_faculty_ratio_info = $this->t2dip_faculty_contribution_model->fetch_student_faculty_ratio_sfr($crclm_id, $pgm_id);
            echo $this->table->generate($student_faculty_ratio_info);
            exit;
        } else {
            $crclm_id = $curriculum_id;
            $dept_id = $this->input->post('dept_id');
            $pgm_id = $this->input->post('pgm_id');
            $student_faculty_ratio_info = $this->t2dip_faculty_contribution_model->fetch_student_faculty_ratio_sfr($crclm_id, $pgm_id);
            $table = $this->table->generate($student_faculty_ratio_info);
            return $table;
        }
    }

    /*
     * Function to fetch  faculty qualification - Criterion 5.2
     * @parameters  :
     * @return      : view
     */

    public function faculty_qualification($id = '', $nba_sar_id = '', $nba_report_id = '', $other_info = array(), $is_export = 0, $param = '') {
        extract($other_info);
        $filters = $this->nba_sar_list_model->nba_filters_list($id, $nba_report_id);
        $this->data['view_id'] = $id;
        $this->data['nba_report_id'] = $nba_report_id;
        $crs_list = $filter_list_data = $content = '';
        $curriculum_id = '';

        if (!empty($filters ['filter_list'])) {
            foreach ($filters ['filter_list'] as $filter_list_value) {
                $filter_list_data [$filter_list_value ['filter_ids']] = $filter_list_value ['filter_value'];
                if ($filter_list_value ['filter_ids'] == 'curriculum_list') {
                    $curriculum_id = $filter_list_value['filter_value'];
                }
                $content = $this->fetch_faculty_qualification($curriculum_id);
            }
        }

        if ($is_export) {
            return $content;
        }

        $this->data['crclm_id'] = @$crclm_id = $filter_list_data['curriculum_list'];
        $this->data['filter_list'] = $filter_list_data;
        $this->data['curriculum_list'] = $this->nba_sar_list_model->list_curriculum($pgm_id);
        $this->data['curriculum_year_list'] = $this->nba_sar_list_model->list_curriculum_year($pgm_id);
        $this->data['faculty_qualification'] = empty($content) ? '' : $content;
        return $this->load->view('nba_sar/dip/tier2/criterion_5/t2dip_c5_2_faculty_qualification_vw', $this->data, true);
    }

    /*
     * Function to fetch  faculty qualification tabel - section 5.2
     * @parameters  : curriculum id
     * @return      : tabel
     */

    public function fetch_faculty_qualification($curriculum_id = NULL) {
        $this->load->library('table');
        $template = array(
            'table_open' => '<table class="table table-bordered" id="faculty_qualification_info" >',
            'thead_open' => '',
            'thead_close' => '',
            'heading_row_start' => '<tr>',
            'heading_row_end' => '</tr>',
            'heading_cell_start' => '<th class="background-nba orange" width="250">',
            'heading_cell_end' => '</th>',
            'tbody_open' => '<tbody>',
            'tbody_close' => '</tbody>',
            'row_start' => '<tr>',
            'row_end' => '</tr>',
            'cell_start' => '<td align="right">',
            'cell_end' => '</td>',
            'row_alt_start' => '<tr>',
            'row_alt_end' => '</tr>',
            'cell_alt_start' => '<td>',
            'cell_alt_end' => '</td>',
            'table_close' => '</table>'
        );

        $this->table->set_template($template);

        if ($curriculum_id == NULL) {
            $curriculum = $this->input->post('curriculum');
            $dept_id = $this->input->post('dept_id');
            $pgm_id = $this->input->post('pgm_id');
            $faculty_qualification_info = $this->t2dip_faculty_contribution_model->fetch_faculty_qualification($curriculum, $dept_id, $pgm_id);
            echo $this->table->generate($faculty_qualification_info);
            exit;
        } else {
            $curriculum = $curriculum_id;
            $dept_id = $this->input->post('dept_id');
            $pgm_id = $this->input->post('pgm_id');
            $faculty_qualification_info = $this->t2dip_faculty_contribution_model->fetch_faculty_qualification($curriculum, $dept_id, $pgm_id);
            $table = $this->table->generate($faculty_qualification_info);
            return $table;
        }
    }

    /*
     * Function to fetch faculty Retention - Criterion 5.3
     * @parameters  :
     * @return      : view
     */

    public function faculty_retention($id = '', $nba_sar_id = '', $nba_report_id = '', $other_info = array(), $is_export = 0, $param = '') {
        extract($other_info);
        $filters = $this->nba_sar_list_model->nba_filters_list($id, $nba_report_id);
        $this->data['view_id'] = $id;
        $this->data['nba_report_id'] = $nba_report_id;
        $crs_list = $filter_list_data = $content = '';
        $curriculum_id = '';

        if (!empty($filters ['filter_list'])) {
            foreach ($filters ['filter_list'] as $filter_list_value) {
                $filter_list_data [$filter_list_value ['filter_ids']] = $filter_list_value ['filter_value'];
                if ($filter_list_value ['filter_ids'] == 'curriculum_list') {
                    $curriculum_id = $filter_list_value['filter_value'];
                }
                $content = $this->fetch_faculty_retention($curriculum_id);
            }
        }

        if ($is_export) {
            return $content;
        }

        $this->data['crclm_id'] = @$crclm_id = $filter_list_data['curriculum_list'];
        $this->data['filter_list'] = $filter_list_data;
        $this->data['curriculum_list'] = $this->nba_sar_list_model->list_curriculum($pgm_id);
        $this->data['curriculum_year_list'] = $this->nba_sar_list_model->list_curriculum_year($pgm_id);
        $this->data['faculty_retention'] = empty($content) ? '' : $content;
        return $this->load->view('nba_sar/dip/tier2/criterion_5/t2dip_c5_3_faculty_retention_vw', $this->data, true);
    }

    /*
     * Function to fetch faculty Retention table - section 5.3
     * @parameters  :
     * @return      : tabel
     */

    public function fetch_faculty_retention($curriculum_id = NULL) {
        $this->load->library('table');
        $template = array(
            'table_open' => '<table class="table table-bordered" id="faculty_retention_info" >',
            'thead_open' => '',
            'thead_close' => '',
            'heading_row_start' => '<tr>',
            'heading_row_end' => '</tr>',
            'heading_cell_start' => '<th class="background-nba orange" width="100">',
            'heading_cell_end' => '</th>',
            'tbody_open' => '<tbody>',
            'tbody_close' => '</tbody>',
            'row_start' => '<tr>',
            'row_end' => '</tr>',
            'cell_start' => '<td align="right">',
            'cell_end' => '</td>',
            'row_alt_start' => '<tr>',
            'row_alt_end' => '</tr>',
            'cell_alt_start' => '<td>',
            'cell_alt_end' => '</td>',
            'table_close' => '</table>'
        );

        $this->table->set_template($template);

        if ($curriculum_id == NULL) {
            $curriculum = $this->input->post('curriculum');
            $dept_id = $this->input->post('dept_id');
            $pgm_id = $this->input->post('pgm_id');
            $faculty_retention_info = $this->t2dip_faculty_contribution_model->fetch_faculty_retention($curriculum, $dept_id, $pgm_id);
            echo $this->table->generate($faculty_retention_info);
            exit;
        } else {
            $curriculum = $curriculum_id;
            $dept_id = $this->input->post('dept_id');
            $pgm_id = $this->input->post('pgm_id');
            $faculty_retention_info = $this->t2dip_faculty_contribution_model->fetch_faculty_retention($curriculum, $dept_id, $pgm_id);
            $table = $this->table->generate($faculty_retention_info);
            return $table;
        }
    }

    /*
     * Function to fetch  faculty development - section 5.4
     * @parameters  :
     * @return      : view
     */

    public function faculty_development($id = '', $nba_sar_id = '', $nba_report_id = '', $other_info = array(), $is_export = 0, $param = '') {
        extract($other_info);
        $filters = $this->nba_sar_list_model->nba_filters_list($id, $nba_report_id);
        $this->data['view_id'] = $id;
        $this->data['nba_report_id'] = $nba_report_id;
        $crs_list = $filter_list_data = $content = '';
        $year_id = '';

        if (!empty($filters ['filter_list'])) {
            foreach ($filters ['filter_list'] as $filter_list_value) {
                $filter_list_data [$filter_list_value ['filter_ids']] = $filter_list_value ['filter_value'];
                if ($filter_list_value ['filter_ids'] == 'year_list') {
                    $year_id = $filter_list_value['filter_value'];
                }
            }
            $content = $this->fetch_faculty_development_training($year_id);
        }

        $this->data['filter_list'] = $filter_list_data;
        $this->data['development_training_data'] = empty($content) ? '' : $content;

        if ($is_export) {
            return $content;
        }

        return $this->load->view('nba_sar/dip/tier2/criterion_5/t2dip_c5_4_faculty_development_vw', $this->data, true);
    }

    /*
     * Function to fetch faculty information contributions table - section 5.4
     * @parameters  : year id
     * @return      : table view
     */

    public function fetch_faculty_development_training($year_id = NULL) {
        if ($year_id == NULL) {
            $year = $this->input->post('year');
            $dept_id = $this->input->post('dept_id');
            $pgm_id = $this->input->post('pgm_id');
            $data['development_training_data'] = $this->t2dip_faculty_contribution_model->fetch_faculty_development_training($year, $dept_id, $pgm_id);
            echo $this->load->view('nba_sar/dip/tier2/criterion_5/t2dip_c5_4_faculty_development_table_vw', $data, true);
        } else {
            $year = $year_id;
            $dept_id = $this->input->post('dept_id');
            $pgm_id = $this->input->post('pgm_id');
            $data['development_training_data'] = $this->t2dip_faculty_contribution_model->fetch_faculty_development_training($year, $dept_id, $pgm_id);
            return $this->load->view('nba_sar/dip/tier2/criterion_5/t2dip_c5_4_faculty_development_table_vw', $data, true);
        }
    }

    /*
     * Function to fetch  faculty consultancy - section 5.5
     * @parameters  :
     * @return      : view
     */

    public function faculty_consultancy($id = '', $nba_sar_id = '', $nba_report_id = '', $other_info = array(), $is_export = 0, $param = '') {
        extract($other_info);
        $this->data ['view_id'] = $id;
        $this->data ['nba_report_id'] = $nba_report_id;
        $dept_id = $this->input->post('dept_id');
        $this->data['consultancy_projects'] = $this->t2dip_faculty_contribution_model->fetch_consultancy_projects($dept_id);
        $this->data ['is_export'] = $is_export;
        return $this->load->view('nba_sar/dip/tier2/criterion_5/t2dip_c5_5_faculty_consultancy_vw', $this->data, true);
    }

    /*
     * Function to fetch  faculty performance appraisal - section 5.6
     * @parameters:
     * @return:
     */

    public function faculty_performance_appraisal($id = '', $nba_sar_id = '', $nba_report_id = '', $other_info = array(), $is_export = 0, $param = '') {
        extract($other_info);
        $filters = $this->nba_sar_list_model->nba_filters_list($id, $nba_report_id);
        $this->data['view_id'] = $id;
        $this->data['nba_report_id'] = $nba_report_id;
        $crs_list = $filter_list_data = $content = '';
        $year_id = '';

        if (!empty($filters ['filter_list'])) {
            foreach ($filters ['filter_list'] as $filter_list_value) {
                $filter_list_data [$filter_list_value ['filter_ids']] = $filter_list_value ['filter_value'];
                if ($filter_list_value ['filter_ids'] == 'year_list') {
                    $year_id = $filter_list_value['filter_value'];
                }
            }
            $content = $this->fetch_faculty_performance_appraisal($year_id);
        }

        $this->data['filter_list'] = $filter_list_data;
        $this->data['performance_appraisal_data'] = empty($content) ? '' : $content;

        if ($is_export) {
            return $content;
        }

        return $this->load->view('nba_sar/dip/tier2/criterion_5/t2dip_c5_6_faculty_performance_appraisal_vw', $this->data, true);
    }

    /*
     * Function to fetch faculty performance appraisal table - section 5.6
     * @parameters  : year id
     * @return      : table view
     */

    public function fetch_faculty_performance_appraisal($year_id = NULL) {
        if ($year_id == NULL) {
            $year = $this->input->post('year');
            $dept_id = $this->input->post('dept_id');
            $pgm_id = $this->input->post('pgm_id');
            $data['performance_appraisal_data'] = $this->t2dip_faculty_contribution_model->fetch_faculty_performance_appraisal($year, $dept_id);
            echo $this->load->view('nba_sar/dip/tier2/criterion_5/t2dip_c5_6_faculty_performance_appraisal_table_vw', $data, true);
        } else {
            $year = $year_id;
            $dept_id = $this->input->post('dept_id');
            $pgm_id = $this->input->post('pgm_id');
            $data['performance_appraisal_data'] = $this->t2dip_faculty_contribution_model->fetch_faculty_performance_appraisal($year, $dept_id);
            return $this->load->view('nba_sar/dip/tier2/criterion_5/t2dip_c5_6_faculty_performance_appraisal_table_vw', $data, true);
        }
    }

}

/*
 * End of file t2dip_c5_faculty.php 
 * Location: .nba_sar/t2dip_c5_faculty.php
 */
?>