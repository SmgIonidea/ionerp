<?php

/**
 * Description          :   Controller logic for NBA SAR Report - Section 8 (TIER II) - First Year Academics
 * Created              :   26-12-2016
 * Author               :   Shayista Mulla
 * Date                     Modified By                     Description
  --------------------------------------------------------------------------------------------------------- */
?>
<?php

defined('BASEPATH') or exit('No direct script access allowed');

class T2ug_c8_first_year_academics extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('nba_sar/nba_sar_list_model');
        $this->load->model('nba_sar/ug/tier2/criterion_8/t2ug_c8_first_year_academic_model');
        $this->load->model('nba_sar/ug/tier2/criterion_2/t2ug_c2_teaching_process_model');
        $this->load->model('nba_sar/ug/tier2/criterion_3/t2ug_c3_co_attainment_model');
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

    /*
     * Function to display first year student faculty ratio - section 8.1
     * @parameters  : 
     * @return      : load section 8.1 view page
     */

    public function first_year_sfr($id = '', $nba_sar_id = '', $nba_report_id = '', $other_info = array(), $is_export = 0, $param = '') {
        extract($other_info);
        $filters = $this->nba_sar_list_model->nba_filters_list($id, $nba_report_id);
        $this->data ['view_id'] = $id;
        $this->data ['nba_report_id'] = $nba_report_id;
        $content = $filter_list_data = array();

        if (!empty($filters ['filter_list'])) {
            foreach ($filters ['filter_list'] as $filter_list_value) {
                $filter_list_data [$filter_list_value ['filter_ids']] = $filter_list_value ['filter_value'];
                if ($filter_list_value ['filter_ids'] == 'curriculum_list') {
                    $content = $this->display_fysfr_grid($filter_list_value ['filter_value']);
                }
            }
        }

        $this->data ['filter_list'] = $filter_list_data;
        $this->data ['curriculum_list'] = $this->nba_sar_list_model->list_curriculum_year($pgm_id);
        $this->data ['is_export'] = $is_export;
        $fysfr_vw_id = empty($content) ? '' : $content;
        $this->data ['fysfr_vw_id'] = $fysfr_vw_id;

        if ($is_export) {
            return $fysfr_vw_id;
        } else {
            return $this->load->view('nba_sar/ug/tier2/criterion_8/t2ug_c8_1_first_year_sfr_vw', $this->data, true);
        }
    }

    /**
     * Function is to dispaly first year sfr grid table
     * @parameters  : curriculum id
     * @return      : table 
     */
    public function display_fysfr_grid($crclm_id = '') {
        if (empty($crclm_id)) {
            $curriculum = $this->input->post('curriculum');
        } else {
            $curriculum = $crclm_id;
        }

        $dept_id = $this->input->post('dept_id');
        $pgm_id = $this->input->post('pgm_id');
        $fysfr_detail = $this->t2ug_c8_first_year_academic_model->fysfr_grid($curriculum, $pgm_id);
        $this->data ['fysfr_list'] = $fysfr_detail;
        $content_view = $this->load->view('nba_sar/ug/tier2/criterion_8/t2ug_c8_1_first_year_sfr_grid_vw', $this->data, true);

        if (empty($crclm_id)) {
            echo $content_view;
        } else {
            return $content_view;
        }
    }

    /**
     * Function is to display facutly teaching fycc - section 8.2
     * @parameters  :
     * @return      :
     */
    public function facutly_teaching_fycc($id = '', $nba_sar_id = '', $nba_report_id = '', $other_info = array(), $is_export = 0, $param = '') {
        extract($other_info);
        $filters = $this->nba_sar_list_model->nba_filters_list($id, $nba_report_id);
        $this->data ['view_id'] = $id;
        $this->data ['nba_report_id'] = $nba_report_id;
        $content = $filter_list_data = array();

        if (!empty($filters ['filter_list'])) {
            foreach ($filters ['filter_list'] as $filter_list_value) {
                $filter_list_data [$filter_list_value ['filter_ids']] = $filter_list_value ['filter_value'];
                if ($filter_list_value ['filter_ids'] == 'curriculum_list') {
                    $content = $this->display_facutly_teaching_fycc($filter_list_value ['filter_value']);
                }
            }
        }

        $this->data ['filter_list'] = $filter_list_data;
        $this->data ['curriculum_list'] = $this->nba_sar_list_model->list_curriculum_year($pgm_id);
        $this->data ['is_export'] = $is_export;
        $fycc_vw_id = empty($content) ? '' : $content;
        $this->data ['fycc_vw_id'] = $fycc_vw_id;

        if ($is_export) {
            return $fycc_vw_id;
        } else {
            return $this->load->view('nba_sar/ug/tier2/criterion_8/t2ug_c8_2_quality_of_faculty_teaching_fycc_vw', $this->data, true);
        }
    }

    /**
     * Function is to display first year academic performance table.
     * @parameters  : curriculum id
     * @return      : table
     */
    public function display_facutly_teaching_fycc($crclm_id = '') {
        if (empty($crclm_id)) {
            $curriculum = $this->input->post('curriculum');
        } else {
            $curriculum = $crclm_id;
        }

        $dept_id = $this->input->post('dept_id');
        $pgm_id = $this->input->post('pgm_id');
        $fycc_detail = $this->t2ug_c8_first_year_academic_model->get_facutly_teaching_fycc($curriculum, $pgm_id, $dept_id);
        $this->data ['fycc_list'] = $fycc_detail;
        $content_view = $this->load->view('nba_sar/ug/tier2/criterion_8/t2ug_c8_2_quality_of_faculty_teaching_fycc_grid_vw', $this->data, true);

        if (empty($crclm_id)) {
            echo $content_view;
        } else {
            return $content_view;
        }
    }

    /**
     * Function is to display academic performance - section 8.3
     * @parameters  :
     * @return      : view
     */
    public function first_year_academic_performance($id = '', $nba_sar_id = '', $nba_report_id = '', $other_info = array(), $is_export = 0, $param = '') {
        extract($other_info);
        $filters = $this->nba_sar_list_model->nba_filters_list($id, $nba_report_id);
        $this->data ['view_id'] = $id;
        $this->data ['nba_report_id'] = $nba_report_id;
        $content = $filter_list_data = array();

        if (!empty($filters ['filter_list'])) {
            foreach ($filters ['filter_list'] as $filter_list_value) {
                $filter_list_data [$filter_list_value ['filter_ids']] = $filter_list_value ['filter_value'];
                if ($filter_list_value ['filter_ids'] == 'curriculum_list') {
                    $content = $this->display_fyap($filter_list_value ['filter_value']);
                }
            }
        }

        $this->data ['filter_list'] = $filter_list_data;
        $this->data ['curriculum_list'] = $this->nba_sar_list_model->list_curriculum_year($pgm_id);
        $this->data ['is_export'] = $is_export;
        $fyap_vw_id = empty($content) ? '' : $content;
        $this->data ['fyap_vw_id'] = $fyap_vw_id;

        if ($is_export) {
            return $fyap_vw_id;
        } else {
            return $this->load->view('nba_sar/ug/tier2/criterion_8/t2ug_c8_3_first_year_academic_performance_vw', $this->data, true);
        }
    }

    /**
     * Function is to academic performance table
     * @parameters  : curriculum id 
     * @return      : table
     */
    public function display_fyap($crclm_id = '') {
        if (empty($crclm_id)) {
            $curriculum = $this->input->post('curriculum');
        } else {
            $curriculum = $crclm_id;
        }

        $dept_id = $this->input->post('dept_id');
        $pgm_id = $this->input->post('pgm_id');
        $fyap_detail = $this->t2ug_c8_first_year_academic_model->get_academic_performance($curriculum, $pgm_id);
        $this->data ['academic_performance'] = $fyap_detail;
        $content_view = $this->load->view('nba_sar/ug/tier2/criterion_8/t2ug_c8_3_first_year_academic_performance_grid_vw', $this->data, true);

        if (empty($crclm_id)) {
            echo $content_view;
        } else {
            return $content_view;
        }
    }

    /**
     * Function to display program and course assessment methods - section 8.4.1
     * @parameters  :
     * @return      : view
     */
    public function assessment_method_for_co($id = '', $nba_sar_id = '', $nba_report_id = '', $other_info = array(), $is_export = 0, $param = '') {
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
        }

        $this->data['crclm_id'] = @$crclm_id = $filter_list_data['curriculum_list'];
        $this->data['term_id'] = @$term_id = $filter_list_data['term_list'];
        $this->data['course_id'] = @$course_id = $filter_list_data['course_list'];
        $this->data['filter_list'] = @$crclm_id;
        $this->data['curriculum_list'] = $this->nba_sar_list_model->list_curriculum($pgm_id);

        if ($course_id) {
            $this->data['occasion_list'] = $this->course_assement_occasions_list($course_id, $crclm_id, $term_id);
        }

        if ($crclm_id) {
            $this->data['term_list'] = $this->t2ug_c8_first_year_academic_model->get_term_list($crclm_id);

            if ($term_id) {
                $this->data['course_list'] = $this->nba_sar_list_model->list_course($crclm_id, $term_id);
            }
        }
        $this->data['assessment_methods'] = $this->t2ug_c3_co_attainment_model->pgm_assessment_methods($pgm_id);
        $this->data['is_export'] = $is_export;

        if ($is_export) {
            $this->data['attainment_co_vw'] = $this->load->view('nba_sar/ug/tier2/criterion_8/t2ug_c8_4_1_assessment_method_vw', $this->data, true);
            // yet to create view for report.
            return $this->data['attainment_co_vw'];
        } else {
            return $this->load->view('nba_sar/ug/tier2/criterion_8/t2ug_c8_4_1_assessment_method_vw', $this->data, true);
        }
    }

    /**
     * Function to form term list
     * @parameters  :
     * @return      : term list
     */
    public function get_term_list() {
        $crclm_id = $this->input->post('curriculum');
        $crclm_list = $this->t2ug_c8_first_year_academic_model->get_term_list($crclm_id);
        $options = '<option value>Select Term</option>';

        foreach ($crclm_list as $list) {
            $options .= '<option value="' . $list['crclm_term_id'] . '">' . $list['term_name'] . '</option>';
        }

        echo $options;
    }

    /**
     * Function to form course list
     * @parameters  :
     * @return      : course list
     */
    public function get_course_list() {
        $crclm_id = $this->input->post('crclm_id');
        $term_id = $this->input->post('term_id');
        $course_list = $this->t2ug_c8_first_year_academic_model->get_course_list($crclm_id, $term_id);
        $options = '<option value>Select Course</option>';

        foreach ($course_list as $list) {
            $options .= '<option value="' . $list['crs_id'] . '">' . $list['crs_title'] . '</option>';
        }

        echo $options;
    }

    /**
     * Function to form curriculum course list
     * @parameters  :
     * @return      : course list
     */
    public function display_curriculum_courses() {
        $crclm_id = $this->input->post('curriculum');
        $data ['course_list'] = $this->t2ug_c8_first_year_academic_model->list_curriculum_courses($crclm_id);
        $option_crclm = '<option value>Select Course</option>';

        foreach ($data ['course_list'] as $crs) {
            $option_crclm .= '<option value="' . $crs['crs_id'] . '" attr="' . $crs['crs_id'] . '/' . $crs['crclm_term_id'] . '">' . $crs['crs_title'] . '</option>';
        }

        echo $option_crclm;
        exit;
    }

    /**
     * Function to get the Assement Occasions of Course
     * @parameters  : course id ,curriculum id,term id
     * @return      : view
     */
    public function course_assement_occasions_list($course_id = NULL, $crclm_id=NULL, $term_id=NULL) {
        if ($course_id == NULL) {
            $course_id = $this->input->post('course_id');
            $crclm_id = $this->input->post('crclm_id') ? $this->input->post('crclm_id') : '';
            $term_id = $this->input->post('term_id') ? $this->input->post('term_id') : '';
            $this->data['assement_list'] = $this->t2ug_c3_co_attainment_model->course_assement_occasions_list($course_id, $crclm_id, $term_id);
            $content_view = $this->load->view('nba_sar/ug/tier2/criterion_3/t2ug_c3_2_1_course_assessment_method_vw', $this->data, true);
            echo $content_view;
        } else {
            $this->data['assement_list'] = $this->t2ug_c3_co_attainment_model->course_assement_occasions_list($course_id, $crclm_id, $term_id);
            $content_view = $this->load->view('nba_sar/ug/tier2/criterion_3/t2ug_c3_2_1_course_assessment_method_vw', $this->data, true);
            return $content_view;
        }
    }

    /**
     * Function to display Question Paper - section 8.4.1
     * @parameters  :
     * @return      : view
     */
    public function get_qp($id = '', $nba_sar_id = '', $nba_report_id = '', $other_info = array(), $is_export = 0, $param = '') {
        extract($other_info);
        $pgm_id = $this->input->post('pgm_id');
        $data ['curriculum_list'] = $this->nba_sar_list_model->list_curriculum($pgm_id);
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
        }

        $this->data['crclm_id'] = @$crclm_id = $filter_list_data['curriculum_list'];
        $this->data['course_id'] = @$course_id = $filter_list_data['course_list'];
        $this->data['qpd_id'] = @$qpd_id = $filter_list_data['qp_list'];
        $this->data['filter_list'] = @$crclm_id;
        $this->data['curriculum_list'] = $this->nba_sar_list_model->list_curriculum($pgm_id);
        $this->data['term_list'] = $this->t2ug_c8_first_year_academic_model->get_term_list($crclm_id);
        $this->data['is_export'] = $is_export;

        if ($is_export && @$qpd_id) {
            $qp_details = $this->t2ug_c2_teaching_process_model->get_qpd_details(@$qpd_id);
            $content = $this->generate_model_qp_modal_tee_static($pgm_id, $qp_details['crclm_id'], $qp_details['crclm_term_id'], $qp_details['crs_id'], $qp_details['qpd_type'], "1", $qp_details['qpd_id']);
            $this->data['export_data'] = $content;
        } else {
            $this->data['export_data'] = "";
        }

        if ($curriculum_id) {
            $this->data['course_list_data'] = $this->t2ug_c8_first_year_academic_model->list_curriculum_courses($crclm_id);

            if ($course_id) {
                $this->data['qp_list'] = $this->t2ug_c2_teaching_process_model->list_course_qp($crclm_id, $course_id);
            }
        }

        return $this->load->view('nba_sar/ug/tier2/criterion_8/t2ug_c8_4_1_qp', $this->data, true);
    }

    /**
     * Function to display Question Paper table - section 8.4.1
     * @parameters  :
     * @return      : table
     */
    public function generate_model_qp_modal_tee_static($pgm_id = NULL, $crclm_id = NULL, $crclm_term_id = NULL, $crs_id = NULL, $qp_type = NULL, $nba_flag = NULL, $qpd_id = NULL) {
        $pgm_id = $pgm_id;
        $crclm_id = $crclm_id;
        $term_id = $crclm_term_id;
        $crs_id = $crs_id;
        $qp_type = $qp_type;
        $qpd_id = $qpd_id;
        $nba_flag = $nba_flag;

        $result = $this->t2ug_c2_teaching_process_model->qp_details($crclm_id, $term_id, $crs_id, $qp_type, $qpd_id);
        $model_qp_data['meta_data'] = $result['qp_meta_data'];
        $table = '';
        $qp_mapping_entity = $this->t2ug_c2_teaching_process_model->qp_mapping_entity();
        $model_qp_data['entity_list'] = $qp_mapping_entity;
        $question_paper_data = $result['question_paper_data'];

        foreach ($question_paper_data as $question_data) {
            $table .= '<tr>';
            $table .= '<td>' . $question_data['qp_unit_code'] . '</td>';
            $table .= '<td>' . $question_data['qp_subq_code'] . '</td>';
            $table .= '<td>' . $question_data['qp_content'] . '</td>';

            foreach ($qp_mapping_entity as $entity) {
                if ($entity['entity_id'] == 11) {
                    $table .= '<td>' . $question_data['co'] . '</td>';
                }

                if ($entity['entity_id'] == 10) {
                    $table .= '<td>' . $question_data['topic'] . '</td>';
                }

                if ($entity['entity_id'] == 23) {
                    $table .= '<td>' . $question_data['level'] . '</td>';
                }

                if ($entity['entity_id'] == 6) {
                    $table .= '<td>' . $question_data['po'] . '</td>';
                }

                if ($entity['entity_id'] == 22) {
                    $table .= '<td>' . $question_data['pi'] . '</td>';
                }
            }

            $table .= '<td>' . $question_data['qp_subq_marks'] . '</td>';
            $table .= '</tr>';
        }

        $model_qp_data['qp_table'] = $table;
        return $model_qp_data;
    }

    /**
     * Function to display rubrics - section 8.4.1
     * @parameters  :
     * @return      : view
     */
    public function attainment_qpd_rubrics($id = '', $nba_sar_id = '', $nba_report_id = '', $other_info = array(), $is_export = 0, $param = '') {
        extract($other_info);
        $filters = $this->nba_sar_list_model->nba_filters_list($id, $nba_report_id);
        $pgm_id = $this->input->post('pgm_id');
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
        }

        $this->data['crclm_id'] = @$crclm_id = $filter_list_data['curriculum_list'];
        $this->data['term_id'] = @$term_id = $filter_list_data['term_list'];
        $this->data['course_id'] = @$course_id = $filter_list_data['course_list'];
        $this->data['ao_method_id'] = @$ao_method_id = $filter_list_data['course_rubrics_list'];
        $this->data['filter_list'] = @$crclm_id;
        $this->data['curriculum_list'] = $this->nba_sar_list_model->list_curriculum($pgm_id);

        if ($course_id) {
            $this->data['rubrics_data'] = $this->co_attainment_qpd_rubrics($course_id, $crclm_id, $term_id, $ao_method_id);
            $this->data['assessment_occasion_list'] = $this->t2ug_c3_co_attainment_model->get_course_assessment_occasion($crclm_id, $term_id, $course_id);
        }

        $this->data['term_list'] = $this->t2ug_c8_first_year_academic_model->get_term_list($crclm_id);
        $this->data['course_list'] = $this->nba_sar_list_model->list_course($crclm_id, $term_id);
        $this->data['is_export'] = $is_export;

        if ($is_export) {
            $this->data['rubrics_attainment_co_vw'] = $this->load->view('nba_sar/ug/tier1/criterion_8/t1ug_c8_4_1_co_rubrics_attainment_vw', $this->data, true);
            return $this->data['rubrics_attainment_co_vw'];
        } else {
            return $this->load->view('nba_sar/ug/tier2/criterion_8/t2ug_c8_4_1_co_rubrics_attainment_vw', $this->data, true);
        }
    }

    /**
     * Function to form rubrics details table - section 8.4.1
     * @parameters  :
     * @return      : table
     */
    public function co_attainment_qpd_rubrics($course_id = NULL, $crclm_id = NULL, $term_id = NULL, $ao_method_id = NULL) {
        $this->load->library('table');
        $template = array(
            'table_open' => '<table class="table table-bordered" id="industry_internship" >',
            'thead_open' => '',
            'thead_close' => '',
            'heading_row_start' => '<tr>',
            'heading_row_end' => '</tr>',
            'heading_cell_start' => '<th class="background-nba orange">',
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

        if ($crclm_id == NULL && $course_id == NULL && $term_id == NULL && $ao_method_id == NULL) {
            $crclm_id = $this->input->post('crclm_id');
            $course_id = $this->input->post('course_id');
            $term_id = $this->input->post('term_id');
            $ao_method_id = $this->input->post('ao_method_id');
            $rubrics_data = $this->t2ug_c3_co_attainment_model->attainment_qpd_rubrics($crclm_id, $course_id, $term_id, $ao_method_id);
            $rubric_course_co = $this->table->generate($rubrics_data);
            echo $rubric_course_co;
            exit;
        } else {
            if ($ao_method_id) {
                $rubrics_data = $this->t2ug_c3_co_attainment_model->attainment_qpd_rubrics($crclm_id, $course_id, $term_id, $ao_method_id);
            } else {
                $rubrics_data = "";
            }

            $rubric_course_co = $this->table->generate($rubrics_data);
            return $rubric_course_co;
        }
    }

    /*
     * Function to display the first year courses,CO target level and course wise CO attainment - section 8.4.2
     * @parameters  :
     * @return      : view
     */

    public function all_co_attainment($id = '', $nba_sar_id = '', $nba_report_id = '', $other_info = array(), $is_export = 0, $param = '') {
        extract($other_info);
        $filters = $this->nba_sar_list_model->nba_filters_list($id, $nba_report_id);
        $this->data['view_id'] = $id;
        $this->data['nba_report_id'] = $nba_report_id;
        $crs_list = $filter_list_data = $content = array();
        $cos_checkbox_list = array();
        $curriculum_id = '';

        if (!empty($filters['filter_list'])) {
            foreach ($filters['filter_list'] as $filter_list_value) {
                $filter_list_data [$filter_list_value ['filter_ids']] = $filter_list_value ['filter_value'];
                if ($filter_list_value['filter_ids'] == 'curriculum_list') {
                    $curriculum_id = $filter_list_value['filter_value'];
                } else if ($filter_list_value['filter_ids'] == 'cos_checkbox_list') {
                    array_push($cos_checkbox_list, $filter_list_value['filter_value']);
                }
            }
        }

        $this->data['filter_crclm_id'] = @$crclm_id = $filter_list_data['curriculum_list'];
        $this->data['filter_cos_checkbox'] = @$cos_checkbox = implode(',', $cos_checkbox_list);
        $this->data['curriculum_list'] = $this->nba_sar_list_model->list_curriculum($pgm_id);
        $this->data['is_export'] = $is_export;

        if ($crclm_id) {
            $crs_result = $this->display_crs_grid($filter_list_data['curriculum_list'], @$cos_checkbox);
            $this->data['crs_grid_vw'] = $crs_result;
            $crs_co_result = $this->co_target_levels($filter_list_data['curriculum_list'], @$cos_checkbox);
            $this->data['crs_co_table_vw'] = $crs_co_result;
        }

        if ($is_export) {
            $this->data['attainment_co_vw'] = $this->load->view('nba_sar/ug/tier2/criterion_8/t2ug_c8_4_2_co_attainment_vw', $this->data, true);
            return $this->data['attainment_co_vw'];
        } else {
            return $this->load->view('nba_sar/ug/tier2/criterion_8/t2ug_c8_4_2_co_attainment_vw', $this->data, true);
        }
    }

    /**
     * Function is to list courses in the grid to select.
     * @parameters  :
     * @return      : list of courses
     */
    public function display_crs_grid($curriculum = NULL, $selected_courses = NULL) {
        if ($curriculum == NULL) {
            $curriculum = $this->input->post('crclm_id');
            $crs_data = $this->nba_sar_list_model->display_course_list($curriculum, 1);
            $nba_data = explode('_', $this->input->post('view_nba_id'));
            $id = $nba_data[0];
            $nba_report_id = $nba_data[1];
            $filters = $this->nba_sar_list_model->nba_filters_list($id, $nba_report_id);
            $crs_list = $filter_list_data = $content = array();
            $curriculum_id = '';
            $cos_checkbox_list = array();

            if (!empty($filters['filter_list'])) {
                foreach ($filters['filter_list'] as $filter_list_value) {
                    $filter_list_data [$filter_list_value ['filter_ids']] = $filter_list_value ['filter_value'];
                    if ($filter_list_value['filter_ids'] == 'cos_checkbox_list') {
                        array_push($cos_checkbox_list, $filter_list_value['filter_value']);
                    }
                }
            }

            $this->data['filter_cos_checkbox'] = @$cos_checkbox = implode(',', $cos_checkbox_list);
            $this->data['view_id'] = $id;
            $this->data['nba_report_id'] = $nba_report_id;
            $this->data['clo_detail'] = $crs_data;
            $this->data['selected_courses'] = $selected_courses;
            $content_view = $this->load->view('nba_sar/ug/tier2/criterion_8/t2ug_c8_4_2_crs_grid_vw', $this->data, true);
            $content['crs_view'] = $content_view;
            echo $content_view;
        } else {
            $crs_data = $this->nba_sar_list_model->display_course_list($curriculum, 1);
            $this->data['clo_detail'] = $crs_data;
            $this->data['selected_courses'] = $selected_courses;
            $content_view = $this->load->view('nba_sar/ug/tier2/criterion_8/t2ug_c8_4_2_crs_grid_vw', $this->data, true);
            return $content_view;
        }
    }

    /*
     * Function is to fetch the course target level
     * @parameters  :
     * @return      : course target level
     */

    public function co_target_levels($curriculum = NULL, $selected_courses = NULL) {
        if ($curriculum != NULL && $selected_courses != NULL) {
            $result = $this->t2ug_c3_co_attainment_model->course_target_level($selected_courses);
            $result_data['course_waise_co_attainment'] = $result['course_wise_co_attainment'];
            $result_vw = $this->load->view('nba_sar/ug/tier2/criterion_3/t2ug_c3_2_2_co_target_level_vw', $result_data, true);
            return $result_vw;
        } else {
            $crs_id_array = $this->input->post('crs_id_array');

            if (!empty($crs_id_array)) {
                $crs_ids = implode(",", $crs_id_array);
                $result = $this->t2ug_c3_co_attainment_model->course_target_level($crs_ids);
                $result_data['course_waise_co_attainment'] = $result['course_wise_co_attainment'];
                $result_vw = $this->load->view('nba_sar/ug/tier2/criterion_3/t2ug_c3_2_2_co_target_level_vw', $result_data, true);
                echo $result_vw;
            }
        }
    }

    /*
     * Function to fetch the po attainment . - section 8.5.1
     * @parameters  :
     * @return      : view
     */

    public function first_year_all_po_attainment($id = '', $nba_sar_id = '', $nba_report_id = '', $other_info = array(), $is_export = 0, $param = '') {
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
        }

        $this->data['curriculum_list'] = $this->nba_sar_list_model->list_curriculum($pgm_id);
        $this->data['is_export'] = $is_export;

        if (!empty($filter_list_data)) {
            $this->data['filter_crclm_id'] = @$crclm_id = $filter_list_data['curriculum_list'];
            if ($filter_list_data['curriculum_list']) {
                $crs_wise_po_attainment = $this->course_wise_po_attainment($filter_list_data['curriculum_list']);
            }
            $this->data['crs_wise_po_attain_vw'] = $crs_wise_po_attainment;
        }

        if ($is_export) {
            $this->data['attainment_co_vw'] = $this->load->view('nba_sar/ug/tier2/criterion_8/t2ug_c8_5_1_first_year_all_po_attainment_vw', $this->data, true);
            //yet to create the report view for this.
            return $this->data['attainment_co_vw'];
        } else {
            return $this->load->view('nba_sar/ug/tier2/criterion_8/t2ug_c8_5_1_first_year_all_po_attainment_vw', $this->data, true);
        }
    }

    /*
     * Function to get the course wise po attainment data.
     * @parameters  :
     * @return      : tables view
     */

    public function course_wise_po_attainment($curriculum = NULL) {
        if ($curriculum == NULL) {
            $crclm_id = $this->input->post('curriculum');
            $po_data = $this->t2ug_c8_first_year_academic_model->for_all_po_mapping($crclm_id);
            $this->data['po_direct'] = $po_data['po_direct'];
            $this->data['po_indirect'] = $po_data['po_indirect'];
            $view_data = $this->load->view('nba_sar/ug/tier2/criterion_8/t2ug_c8_5_1_all_po_attainment_display_vw', $this->data, true);
            echo $view_data;
        } else {
            $po_data = $this->t2ug_c8_first_year_academic_model->for_all_po_mapping($curriculum);
            $this->data['po_direct'] = $po_data['po_direct'];
            $this->data['po_indirect'] = $po_data['po_indirect'];
            $view_data = $this->load->view('nba_sar/ug/tier2/criterion_8/t2ug_c8_5_1_all_po_attainment_display_vw', $this->data, true);
            return $view_data;
        }
    }

    /**
     * Function is to clear stored filter details - section 8.4.1
     * @parameters  :
     * @return      : a boolean value
     */
    public function clear_filter() {
        $view_form = $this->input->post('view_form', true);
        $nba_sar_id = $this->input->post('nba_sar_id', true);
        $data = $this->input->post('view_nba_id', true);
        $ids = explode('__', $data);
        $id_value = explode('_', $ids[0]);
        $filter_nba_sar_view_id = $id_value[0];
        $filter_nba_id = $id_value[1];
        echo $this->t2ug_c3_co_attainment_model->clear_filter($nba_sar_id, $filter_nba_sar_view_id, $filter_nba_id);
        exit();
    }

}

/*
 * End of file t2ug_c8_first_year_academics.php 
 * Location: .nba_sar/t2ug_c8_first_year_academics.php
 */
?>