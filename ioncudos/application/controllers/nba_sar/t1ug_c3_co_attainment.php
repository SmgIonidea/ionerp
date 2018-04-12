<?php

/**
 * Description          :   Controller logic for Criterion 3 (TIER 1) - Attainment of CO ,PO and PSO.
 * Created              :   01-05-2017
 * Author               :   Shayista Mulla
 * Modification History :
 * Date                     Modified by                      Description
  --------------------------------------------------------------------------------------------------------------- */
?>
<?php

defined('BASEPATH') or exit('No direct script access allowed');

class T1ug_c3_co_attainment extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('nba_sar/nba_sar_list_model');
        $this->load->model('nba_sar/ug/tier1/criterion_3/t1ug_c3_co_attainment_model');
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
     * Function to display Program and Course Assessment Methods - section 3.2.1
     * @parameters  :
     * @return      : view
     */
    public function attainment_co($id = '', $nba_sar_id = '', $nba_report_id = '', $other_info = array(), $is_export = 0, $param = '') {
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
        $this->data['ao_method_id'] = @$ao_method_id = $filter_list_data['course_rubrics_list'];
        $this->data['filter_list'] = @$crclm_id;
        $this->data['curriculum_list'] = $this->nba_sar_list_model->list_curriculum($pgm_id);

        if ($course_id) {
            $this->data['occasion_list'] = $this->course_assement_occasions_list($course_id, $crclm_id, $term_id);
        }

        if ($crclm_id) {
            $this->data['term_list'] = $this->t1ug_c3_co_attainment_model->get_term_list($crclm_id);

            if ($term_id) {
                $this->data['course_list'] = $this->nba_sar_list_model->list_course($crclm_id, $term_id);
            }
        }

        $this->data['assessment_methods'] = $this->t1ug_c3_co_attainment_model->pgm_assessment_methods($pgm_id);
        $this->data['is_export'] = $is_export;

        return $this->load->view('nba_sar/ug/tier1/criterion_3/t1ug_c3_2_1_co_attainment_vw', $this->data, true);
    }

    /**
     * Function to form term dropdown - section 3.2.1
     * @parameters  :
     * @return      :
     */
    public function get_term_list() {
        $crclm_id = $this->input->post('curriculum');
        $crclm_list = $this->t1ug_c3_co_attainment_model->get_term_list($crclm_id);
        $options = '<option value>Select Term</option>';

        foreach ($crclm_list as $list) {
            $options .= '<option value="' . $list['crclm_term_id'] . '">' . $list['term_name'] . '</option>';
        }

        echo $options;
    }

    /**
     * Function to form course dropdown - 3.2.1
     * @parameters  :
     * @return      :
     */
    public function get_course_list() {
        $crclm_id = $this->input->post('crclm_id');
        $term_id = $this->input->post('term_id');
        $course_list = $this->t1ug_c3_co_attainment_model->get_course_list($crclm_id, $term_id);
        $options = '<option value>Select Course</option>';

        foreach ($course_list as $list) {
            $options .= '<option value="' . $list['crs_id'] . '">' . $list['crs_title'] . '</option>';
        }

        echo $options;
    }

    /**
      Function to get the Assement Occasions of Course - section 3.2.1
      @parameters   :
      @return       :
     */
    public function course_assement_occasions_list($course_id=NULL, $crclm_id=NULL, $term_id=NULL) {
        if ($course_id == NULL) {
            $course_id = $this->input->post('course_id');
            $crclm_id = $this->input->post('crclm_id') ? $this->input->post('crclm_id') : '';
            $term_id = $this->input->post('term_id') ? $this->input->post('term_id') : '';
            $assement_list = $this->t1ug_c3_co_attainment_model->course_assement_occasions_list($course_id, $crclm_id, $term_id);
            $this->data['assement_list'] = $assement_list;
            $content_view = $this->load->view('nba_sar/ug/tier1/criterion_3/t1ug_c3_2_1_course_assessment_method_vw', $this->data, true);
            echo $content_view;
        } else {
            $assement_list = $this->t1ug_c3_co_attainment_model->course_assement_occasions_list($course_id, $crclm_id, $term_id);
            $this->data['assement_list'] = $assement_list;
            $content_view = $this->load->view('nba_sar/ug/tier1/criterion_3/t1ug_c3_2_1_course_assessment_method_vw', $this->data, true);
            return $content_view;
        }
    }

    /**
     * Function to display attainment qpd rubrics - section 3.2.1
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
            $this->data['assessment_occasion_list'] = $this->t1ug_c3_co_attainment_model->get_course_assessment_occasion($crclm_id, $term_id, $course_id);
        }

        $this->data['term_list'] = $this->t1ug_c3_co_attainment_model->get_term_list($crclm_id);
        $this->data['course_list'] = $this->nba_sar_list_model->list_course($crclm_id, $term_id);
        $this->data['is_export'] = $is_export;

        if ($is_export) {
            $this->data['rubrics_attainment_co_vw'] = $this->load->view('nba_sar/ug/tier1/criterion_3/t1ug_c3_2_1_co_rubrics_attainment_vw', $this->data, true);
            return $this->data['rubrics_attainment_co_vw'];
        } else {
            return $this->load->view('nba_sar/ug/tier1/criterion_3/t1ug_c3_2_1_co_rubrics_attainment_vw', $this->data, true);
        }
    }

    /**
     * Function to display CO attainment qpd rubrics - section 3.2.1
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
            $rubrics_data = $this->t1ug_c3_co_attainment_model->attainment_qpd_rubrics($crclm_id, $course_id, $term_id, $ao_method_id);
            $rubric_course_co = $this->table->generate($rubrics_data);
            echo $rubric_course_co;
            exit;
        } else {
            if ($ao_method_id) {
                $rubrics_data = $this->t1ug_c3_co_attainment_model->attainment_qpd_rubrics($crclm_id, $course_id, $term_id, $ao_method_id);
                $rubric_course_co = $this->table->generate($rubrics_data);
            } else {
                $rubric_course_co = "";
            }

            return $rubric_course_co;
        }
    }

    /**
     * Function to fetch course rubric AO list - section 3.2.1
     * @parameters  : 
     * @return      : course rubric AO list
     */
    public function get_course_assessment_occasion_list() {
        $crclm_id = $this->input->post('crclm_id');
        $term_id = $this->input->post('term_id');
        $course_id = $this->input->post('course_id');
        $assessment_occasion_list = $this->t1ug_c3_co_attainment_model->get_course_assessment_occasion($crclm_id, $term_id, $course_id);
        $options = '<option value>Select Rubrics</option>';

        foreach ($assessment_occasion_list as $occasion) {
            $options .= '<option value="' . $occasion['ao_method_id'] . '">' . $occasion['ao_method_name'] . '</option>';
        }
        echo $options;
    }

    /**
     * Function to display co attainment of course for selected curriculum - section 3.2.2
     * @parameters  :
     * @return      : view
     */
    public function all_co_attainment($id = '', $nba_sar_id = '', $nba_report_id = '', $other_info = array(), $is_export = 0, $param = '') {
        extract($other_info);
        $filters = $this->nba_sar_list_model->nba_filters_list($id, $nba_report_id);
        $this->data['view_id'] = $id;
        $this->data['nba_report_id'] = $nba_report_id;
        $crs_list = $filter_list_data = $content = array();
        $curriculum_id = '';
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
            $this->data['attainment_co_vw'] = $this->load->view('nba_sar/ug/tier1/criterion_3/t1ug_c3_2_2_all_co_attainment_vw', $this->data, true);
            return $this->data['attainment_co_vw'];
        } else {
            return $this->load->view('nba_sar/ug/tier1/criterion_3/t1ug_c3_2_2_all_co_attainment_vw', $this->data, true);
        }
    }

    /**
     * Function to populate the courses grid. - section 3.2.2
     * @parameters  :
     * @return      : view
     */
    public function display_crs_grid($curriculum=NULL, $selected_courses = NULL) {
        if ($curriculum == NULL) {
            $curriculum = $this->input->post('curriculum');
            $crs_data = $this->nba_sar_list_model->display_course_list($curriculum, 0);
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
            $this->data['view_id'] = $nba_report_id;
            $this->data['nba_report_id'] = $id;
            $this->data['clo_detail'] = $crs_data;
            $this->data['selected_courses'] = $selected_courses;
            $content_view = $this->load->view('nba_sar/ug/tier1/criterion_3/t1ug_c3_2_2_crs_grid_vw', $this->data, true);
            $content['crs_view'] = $content_view;
            echo json_encode($content);
        } else {
            $crs_data = $this->nba_sar_list_model->display_course_list($curriculum, 0);
            $this->data['clo_detail'] = $crs_data;
            $this->data['selected_courses'] = $selected_courses;
            $content_view = $this->load->view('nba_sar/ug/tier1/criterion_3/t1ug_c3_2_2_crs_grid_vw', $this->data, true);
            return $content_view;
        }
    }

    /**
     * Function to fetch the course target level - section 3.2.2
     * @parameters  :
     * @return      : view
     */
    public function co_target_levels($curriculum = NULL, $selected_courses = NULL) {
        if ($curriculum != NULL && $selected_courses != NULL) {
            $result = $this->t1ug_c3_co_attainment_model->course_target_level($selected_courses);
            $result_data['course_waise_co_attainment'] = $result['course_wise_co_attainment'];
            $result_vw = $this->load->view('nba_sar/ug/tier1/criterion_3/t1ug_c3_2_2_co_target_level_vw', $result_data, true);
            return $result_vw;
        } else {
            $crs_id_array = $this->input->post('crs_id_array');

            if (!empty($crs_id_array)) {
                $crs_ids = implode(",", $crs_id_array);
                $result = $this->t1ug_c3_co_attainment_model->course_target_level($crs_ids);
                $result_data['course_waise_co_attainment'] = $result['course_wise_co_attainment'];
                $result_vw = $this->load->view('nba_sar/ug/tier1/criterion_3/t1ug_c3_2_2_co_target_level_vw', $result_data, true);
                echo $result_vw;
            }
        }
    }

    /**
     * Function to display curriculum survey attainment - section 3.3.1
     * @parameters  :
     * @return      : view
     */
    public function curriculum_survey_attainment_tools($id = '', $nba_sar_id = '', $nba_report_id = '', $other_info = array(), $is_export = 0, $param = '') {
        extract($other_info);
        $filters = $this->nba_sar_list_model->nba_filters_list($id, $nba_report_id);
        $this->data['view_id'] = $id;
        $this->data['nba_report_id'] = $nba_report_id;
        $crs_list = $filter_list_data = $content = array();
        $curriculum_id = '';

        if (!empty($filters['filter_list'])) {
            foreach ($filters['filter_list'] as $filter_list_value) {
                $filter_list_data [$filter_list_value ['filter_ids']] = $filter_list_value ['filter_value'];
                if ($filter_list_value['filter_ids'] == 'curriculum_survey_list') {
                    $curriculum_id = $filter_list_value['filter_value'];
                }
            }
        }

        $this->data['curriculum_survey_list'] = $this->nba_sar_list_model->list_curriculum($pgm_id);
        $this->data['crclm_survey_id'] = @$filter_crclm_survey_id = $filter_list_data['curriculum_survey_list'];
        $this->data['survey_id'] = @$filter_survey_id = $filter_list_data['survey_list'];
        $this->data['is_export'] = $is_export;

        if (@$filter_crclm_survey_id) {
            $this->data['survey_list'] = $this->t1ug_c3_co_attainment_model->get_curriculum_survey_list($pgm_id, @$filter_crclm_survey_id);

            if (@$filter_survey_id) {
                $this->data['crclm_survey_attain_vw'] = @$crclm_survey_attain_vw = $this->fetch_curriculum_survey_attainment_tools(@$filter_survey_id);
            }
        }

        if ($is_export) {
            $this->data['attainment_co_vw'] = $this->load->view('nba_sar/ug/tier1/criterion_3/t1ug_c3_3_1_curriculum_survey_attainment_tools_vw', $this->data, true);
            return $this->data['attainment_co_vw'];
        } else {
            return $this->load->view('nba_sar/ug/tier1/criterion_3/t1ug_c3_3_1_curriculum_survey_attainment_tools_vw', $this->data, true);
        }
    }

    /**
     * Function to fetch curriculum survey attainment table - section 3.3.1
     * @parameters  :
     * @return      : view
     */
    public function fetch_curriculum_survey_attainment_tools($survey_id = NULL) {
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

        if ($survey_id) {
            $survey_data = $this->t1ug_c3_co_attainment_model->fetch_curriculum_survey_attainment_tools($survey_id);
            $survey_data = $this->table->generate($survey_data);
            return $survey_data;
        } else {
            $survey_id = $this->input->post('survey_id');
            $survey_data = $this->t1ug_c3_co_attainment_model->fetch_curriculum_survey_attainment_tools($survey_id);
            $survey_data = $this->table->generate($survey_data);
            echo $survey_data;
            exit;
        }
    }

    /**
     * Function to display PO and PSO attainment of curriculum - section 3.3.2
     * @parameters  : 
     * @return      : view
     */
    public function all_po_attainment($id = '', $nba_sar_id = '', $nba_report_id = '', $other_info = array(), $is_export = 0, $param = '') {
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
                $this->data['crs_wise_po_attain_vw'] = $crs_wise_po_attainment;
            }
        }

        if ($is_export) {
            $this->data['attainment_co_vw'] = $this->load->view('nba_sar/ug/tier1/criterion_3/t1ug_c3_3_2_all_po_attainment_vw', $this->data, true);
            return $this->data['attainment_co_vw'];
        } else {
            return $this->load->view('nba_sar/ug/tier1/criterion_3/t1ug_c3_3_2_all_po_attainment_vw', $this->data, true);
        }
    }

    /**
     * Function to display course wise po attainment and PO indirect attainment - section 3.3.2
     * @parameters  : 
     * @return      : view
     */
    public function course_wise_po_attainment($curriculum=NULL) {
        if ($curriculum == NULL) {
            $crclm_id = $this->input->post('curriculum');
            $po_data = $this->t1ug_c3_co_attainment_model->for_all_po_mapping($crclm_id);
            $this->data['po_direct'] = $po_data['po_direct'];
            $this->data['po_indirect'] = $po_data['po_indirect'];
            $view_data = $this->load->view('nba_sar/ug/tier1/criterion_3/t1ug_c3_3_2_all_po_attainment_display_vw', $this->data, true);
            echo $view_data;
        } else {
            $po_data = $this->t1ug_c3_co_attainment_model->for_all_po_mapping($curriculum);
            $this->data['po_direct'] = $po_data['po_direct'];
            $this->data['po_indirect'] = $po_data['po_indirect'];
            $view_data = $this->load->view('nba_sar/ug/tier1/criterion_3/t1ug_c3_3_2_all_po_attainment_display_vw', $this->data, true);
            return $view_data;
        }
    }

    /**
     * Function to display curriculum survey attainment - section 3.3.2
     * @parameters  :
     * @return      : view
     */
    public function curriculum_survey_attainment($id = '', $nba_sar_id = '', $nba_report_id = '', $other_info = array(), $is_export = 0, $param = '') {
        extract($other_info);
        $filters = $this->nba_sar_list_model->nba_filters_list($id, $nba_report_id);
        $this->data['view_id'] = $id;
        $this->data['nba_report_id'] = $nba_report_id;
        $crs_list = $filter_list_data = $content = array();
        $curriculum_id = '';

        if (!empty($filters['filter_list'])) {
            foreach ($filters['filter_list'] as $filter_list_value) {
                $filter_list_data [$filter_list_value ['filter_ids']] = $filter_list_value ['filter_value'];
                if ($filter_list_value['filter_ids'] == 'curriculum_survey_list') {
                    $curriculum_id = $filter_list_value['filter_value'];
                }
            }
        }

        $this->data['curriculum_survey_list'] = $this->nba_sar_list_model->list_curriculum($pgm_id);
        $this->data['crclm_survey_id'] = @$filter_crclm_survey_id = $filter_list_data['curriculum_survey_list'];
        $this->data['survey_id'] = @$filter_survey_id = $filter_list_data['survey_list'];
        $this->data['is_export'] = $is_export;

        if (@$filter_crclm_survey_id) {
            $this->data['survey_list'] = $this->t1ug_c3_co_attainment_model->get_curriculum_survey_list($pgm_id, @$filter_crclm_survey_id);

            if (@$filter_survey_id) {
                $this->data['crclm_survey_attain_vw'] = @$crclm_survey_attain_vw = $this->fetch_curriculum_survey_attainment(@$filter_survey_id);
            }
        }

        if ($is_export) {
            $this->data['attainment_co_vw'] = $this->load->view('nba_sar/ug/tier1/criterion_3/t1ug_c3_3_2_curriculum_survey_attainment_vw', $this->data, true);
            return $this->data['attainment_co_vw'];
        } else {
            return $this->load->view('nba_sar/ug/tier1/criterion_3/t1ug_c3_3_2_curriculum_survey_attainment_vw', $this->data, true);
        }
    }

    /**
     * Function to for curriculum survey dropdown - section 3.3.1,3.3.2
     * @parameters  :
     * @return      : survey list
     */
    public function fetch_curriculum_survey_list() {
        $crclm_id = $this->input->post('crclm_id');
        $pgm_id = $this->input->post('pgm_id');
        $survey_list = $this->t1ug_c3_co_attainment_model->get_curriculum_survey_list($pgm_id, $crclm_id);
        $options = '<option value>Select Survey</option>';
        foreach ($survey_list as $list) {
            $options .= '<option value="' . $list['survey_id'] . '">' . $list['name'] . '</option>';
        }
        echo $options;
    }

    /**
     * Function to fetch curriculum survey attainment table - section 3.3.2
     * @parameters  :
     * @return      : view
     */
    public function fetch_curriculum_survey_attainment($survey_id = NULL) {
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

        if ($survey_id) {
            $survey_data = $this->t1ug_c3_co_attainment_model->fetch_curriculum_survey_attainment($survey_id);
            $survey_data = $this->table->generate($survey_data);
            return $survey_data;
        } else {
            $survey_id = $this->input->post('survey_id');
            $survey_data = $this->t1ug_c3_co_attainment_model->fetch_curriculum_survey_attainment($survey_id);
            $survey_data = $this->table->generate($survey_data);
            echo $survey_data;
            exit;
        }
    }

    /**
     * Function to delete filter id stored - section 3.1,3.2.2
     * @parameters  : 
     * @return      : A boolean value
     */
    public function clear_filter() {
        $view_form = $this->input->post('view_form', true);
        $nba_sar_id = $this->input->post('nba_sar_id', true);
        $data = $this->input->post('view_nba_id', true);
        $ids = explode('__', $data);
        $id_value = explode('_', $ids[0]);
        $filter_nba_sar_view_id = $id_value[0];
        $filter_nba_id = $id_value[1];
        echo $this->t1ug_c3_co_attainment_model->clear_filter($nba_sar_id, $filter_nba_sar_view_id, $filter_nba_id);
        exit();
    }

}

/* * End of file t1ug_c3_co_attainment.php
 * Location: .nba_sar/t1ug_c3_co_attainment.php 
 */
?>