<?php

/**
 * Description          :   Controller logic for Criterion 2 (Pharmacy TIER 2) - Teaching Learning Process.
 * Created              :   17-06-2017
 * Author               :   Shayista Mulla
 * Modification History :
 * Date                     Modified by                      Description
  --------------------------------------------------------------------------------------------------------------- */
?>
<?php

defined('BASEPATH') or exit('No direct script access allowed');

class T2pharm_c2_teaching_learning_process extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('nba_sar/pharm/tier2/criterion_2/t2pharm_c2_teaching_process_model');
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
     * Function to display Question Paper - section 2.2.2
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
        $this->data['term_list'] = $this->nba_sar_list_model->list_term($crclm_id);
        $this->data['is_export'] = $is_export;

        if ($is_export && @$qpd_id) {
            $qp_details = $this->t2pharm_c2_teaching_process_model->get_qpd_details(@$qpd_id);
            $content = $this->generate_model_qp_modal_tee_static($pgm_id, $qp_details['crclm_id'], $qp_details['crclm_term_id'], $qp_details['crs_id'], $qp_details['qpd_type'], "1", $qp_details['qpd_id']);
            $this->data['export_data'] = $content;
        } else {
            $this->data['export_data'] = "";
        }

        if ($curriculum_id) {
            $this->data['course_list_data'] = $this->t2pharm_c2_teaching_process_model->list_curriculum_courses($crclm_id);

            if ($course_id) {
                $this->data['qp_list'] = $this->t2pharm_c2_teaching_process_model->list_course_qp($crclm_id, $course_id);
            }
        }

        return $this->load->view('nba_sar/pharm/tier2/criterion_2/t2pharm_c2_2_2_qp_vw', $this->data, true);
    }

    /**
     * Function to get the list of courses for a curriculum
     * @parameters  :
     * @return      :
     */
    public function display_curriculum_courses() {
        $crclm_id = $this->input->post('curriculum');
        $data ['course_list'] = $this->t2pharm_c2_teaching_process_model->list_curriculum_courses($crclm_id);
        $option_crclm = '<option value>Select Course</option>';

        foreach ($data ['course_list'] as $crs) {
            $option_crclm .= '<option value="' . $crs['crs_id'] . '" attr="' . $crs['crs_id'] . '/' . $crs['crclm_term_id'] . '">' . $crs['crs_title'] . '</option>';
        }

        echo $option_crclm;
    }

    /**
     * Function to get the list of questionpapers asscociated with the selected curriculum and course
     * @parameters  :
     * @return      :
     */
    public function display_course_qp() {
        $crclm_id = $this->input->post('curriculum');
        $crs_id = $this->input->post('course');
        $data ['qp_list'] = $this->t2pharm_c2_teaching_process_model->list_course_qp($crclm_id, $crs_id);
        $option_crclm = '<option value>Select Question Paper</option>';

        foreach ($data ['qp_list'] as $qp) {
            $option_crclm .= '<option value="' . $qp['qpd_id'] . '" attr="' . $qp['qpd_id'] . '/' . $qp['qpd_type'] . '">' . $qp['qpd_title'] . '</option>';
        }

        echo $option_crclm;
    }

    /**
     * Function to display Question Paper tabel- section 2.2.2
     * @parameters  :
     * @return      : view
     */
    public function generate_model_qp_modal_tee() {
        $pgm_id = $this->input->post('pgmtype');
        $crclm_id = $this->input->post('crclm_id');
        $term_id = $this->input->post('term_id');
        $crs_id = $this->input->post('crs_id');
        $qp_type = $this->input->post('qp_type');
        $qpd_id = $this->input->post('qpd_id');
        $nba_flag = $this->input->post('nba_flag');

        $result = $this->t2pharm_c2_teaching_process_model->qp_details($crclm_id, $term_id, $crs_id, $qp_type, $qpd_id);
        $model_qp_data['meta_data'] = $result['qp_meta_data'];
        $table = '';
        $qp_mapping_entity = $this->t2pharm_c2_teaching_process_model->qp_mapping_entity();
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
        echo $this->load->view('nba_sar/pharm/tier2/criterion_2/t2pharm_c2_2_2_qp_display_vw', $model_qp_data);
    }

    /**
     * Function to display Question Paper table for export - section 2.2.2
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

        $result = $this->t2pharm_c2_teaching_process_model->qp_details($crclm_id, $term_id, $crs_id, $qp_type, $qpd_id);
        $model_qp_data['meta_data'] = $result['qp_meta_data'];
        $table = '';
        $qp_mapping_entity = $this->t2pharm_c2_teaching_process_model->qp_mapping_entity();
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
     * Function to get CO and Bloom's level image - section 2.2.2
     * @parameters  :
     * @return      : 
     */
    public function create_graph_image() {
        $co_graph = $this->input->post('co_graph');
        $bloom_graph = $this->input->post('bloom_graph');
        $nba_sar_id = $this->input->post('nba_sar_id');
        $nba_report_id = $this->input->post('nba_report_id');

        $path = 'nba_sar_graphs/';

        //create the folder if it's not already existing
        if (!is_dir($path)) {
            $mask = umask(0);
            mkdir($path, 0777);
            umask($mask);
        }

        $img_path = 'nba_sar_graphs/co_graph_' . $nba_sar_id . '_' . $nba_report_id . '.png';
        list($type, $co_graph) = explode(';', $co_graph);
        list(, $co_graph) = explode(',', $co_graph);
        $co_graph = base64_decode($co_graph);

        if (!file_exists($img_path)) {
            $fh = fopen($img_path, 'w');
        }

        file_put_contents($img_path, $co_graph);

        $img_path = 'nba_sar_graphs/bloom_graph_' . $nba_sar_id . '_' . $nba_report_id . '.png';
        list($type, $bloom_graph) = explode(';', $bloom_graph);
        list(, $bloom_graph) = explode(',', $bloom_graph);
        $bloom_graph = base64_decode($bloom_graph);

        if (!file_exists($img_path)) {
            $fh = fopen($img_path, 'w');
        }

        file_put_contents($img_path, $bloom_graph);

        echo true;
    }

    /**
     * Function to display course outcome graph
     * @parameters  :
     * @return      :
     */
    public function get_qp_course_outcome($id = '', $nba_sar_id = '', $nba_report_id = '', $other_info = array(), $is_export = 0, $param = '') {
        $filters = $this->nba_sar_list_model->nba_filters_list($param, $nba_report_id);
        $crs_list = $filter_list_data = array();
        $this->data['course_outcome'] = '';

        if (!empty($filters['filter_list'])) {
            foreach ($filters['filter_list'] as $filter_list_value) {
                $filter_list_data [$filter_list_value ['filter_ids']] = $filter_list_value ['filter_value'];
            }
        }

        $this->data['nba_report_id'] = $nba_report_id;
        $this->data['nba_sar_id'] = $nba_sar_id;
        $this->data['course_id'] = @$course_id = $filter_list_data['course_list'];
        $this->data['qpd_id'] = @$qpd_id = $filter_list_data['qp_list'];
        $this->data['is_export'] = $is_export;

        if (@$qpd_id) {
            $this->data['course_outcome'] = $this->t2pharm_c2_teaching_process_model->getCOLevelMarksDistribution($course_id, $qpd_id);
        }

        return $this->load->view('nba_sar/pharm/tier2/criterion_2/t2pharm_c2_2_2_qp_course_outcome_vw', $this->data, true);
    }

    /**
     * Function to display bloom level graph
     * @parameters  :
     * @return      :
     */
    public function get_qp_bloom_level($id = '', $nba_sar_id = '', $nba_report_id = '', $other_info = array(), $is_export = 0, $param = '') {
        $filters = $this->nba_sar_list_model->nba_filters_list($param, $nba_report_id);
        $crs_list = $filter_list_data = array();
        $this->data['bloom_level'] = '';

        if (!empty($filters['filter_list'])) {
            foreach ($filters['filter_list'] as $filter_list_value) {
                $filter_list_data [$filter_list_value ['filter_ids']] = $filter_list_value ['filter_value'];
            }
        }

        $this->data['nba_report_id'] = $nba_report_id;
        $this->data['nba_sar_id'] = $nba_sar_id;
        $this->data['course_id'] = @$course_id = $filter_list_data['course_list'];
        $this->data['qpd_id'] = @$qpd_id = $filter_list_data['qp_list'];
        $this->data['is_export'] = $is_export;

        if (@$qpd_id) {
            $this->data['bloom_level'] = $this->t2pharm_c2_teaching_process_model->getBloomsLevelMarksDistribution_custom($course_id, $qpd_id);
        }

        return $this->load->view('nba_sar/pharm/tier2/criterion_2/t2pharm_c2_2_2_qp_bloom_level_vw', $this->data, true);
    }

    /**
     * Function to get the Course Outcome Planned Coverage distribution for the selected course and question paper
     * @parameters  :
     * @return      :
     */
    public function course_co_planned_coverage() {
        $crs_id = $this->input->post('crs_id');
        $qpd_id = $this->input->post('qpd_id');
        $course['co_outcome'] = $this->t2pharm_c2_teaching_process_model->getCOLevelMarksDistribution($crs_id, $qpd_id);

        $clo_code = array();
        $clo_total_marks_dist = array();
        $clo_percentage_dist = array();
        $clo_statement_dist = array();

        foreach ($course['co_outcome'] as $clo_data) {
            $clo_code[] = $clo_data['clocode'];
            $clo_total_marks_dist[] = $clo_data['TotalMarks'];
            $clo_percentage_dist[] = $clo_data['PercentageDistribution'];
            $clo_statement_dist[] = $clo_data['clo_statement'];
        }

        $data['clo_code'] = implode(",", $clo_code);
        $data['total_marks'] = implode(",", $clo_total_marks_dist);
        $data['percentage'] = implode(",", $clo_percentage_dist);
        $data['clo_stmt'] = implode(",", $clo_statement_dist);

        echo json_encode($data);
        exit;
    }

    /**
     * Function to get the Course Bloom's Level Marks distribution for the selected course and question paper
     * @parameters  :
     * @return      :
     */
    public function course_bloom_level_marks_distribution() {
        $crs_id = $this->input->post('crs_id');
        $qpd_id = $this->input->post('qpd_id');
        $course['blooms_level_marks'] = $this->t2pharm_c2_teaching_process_model->getBloomsLevelMarksDistribution_custom($crs_id, $qpd_id);
        //BloomsLevelMarksDistribution
        $blooms_level_marks_dist = array();
        $total_marks_marks_dist = array();
        $percentage_distribution_marks_dist = array();
        $bloom_level_marks_description = array();

        foreach ($course['blooms_level_marks'] as $bloom_lvl_marks_data) {
            $blooms_level_marks_dist[] = $bloom_lvl_marks_data['BloomsLevel'];
            $total_marks_marks_dist[] = $bloom_lvl_marks_data['TotalMarks'];
            $percentage_distribution_marks_dist[] = $bloom_lvl_marks_data['PercentageDistribution'];
            $bloom_level_marks_description[] = $bloom_lvl_marks_data['description'];
        }

        $data['blooms_level_marks_dist'] = implode(",", $blooms_level_marks_dist);
        $data['total_marks_marks_dist'] = implode(",", $total_marks_marks_dist);
        $data['percentage_distribution_marks_dist'] = implode(",", $percentage_distribution_marks_dist);
        $data['bloom_level_marks_description'] = implode(",", $bloom_level_marks_description);

        echo json_encode($data);
        exit;
    }

    /**
     * Function to display Quality of student projects - section 2.2.3
     * @parameters  : 
     * @return      : view
     */
    public function get_co_po_mapping($id = '', $nba_sar_id = '', $nba_report_id = '', $other_info = array(), $is_export = 0, $param = '') {
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
        $this->data['filter_list'] = @$crclm_id;
        $this->data['term_id'] = @$term_id = $filter_list_data['term_list'];
        $this->data['course_id'] = @$course_id = $filter_list_data['course_list'];
        $this->data['filter_list'] = @$crclm_id;
        $this->data['curriculum_list'] = $this->nba_sar_list_model->list_curriculum($pgm_id);
        $this->data['term_list'] = $this->nba_sar_list_model->list_term($crclm_id);
        $this->data['course_list'] = $this->nba_sar_list_model->list_course($crclm_id, $term_id);

        if (@$crclm_id && @$course_id) {
            $content['component_view'] = $this->get_quality_student_project(@$crclm_id, @$course_id);
        } else {
            $content['component_view'] = '';
        }

        $this->data['quality_student_project'] = @$quality_student_project = empty($content['component_view']) ? '' : $content['component_view'];
        $this->data['is_export'] = $is_export;
        return $this->load->view('nba_sar/pharm/tier2/criterion_2/t2pharm_c2_2_3_student_quality_projects_vw', $this->data, true);
    }

    /**
     * Function to Get the Term List - section - 2.2.3,3.2.1
     * @parameters  :
     * @return      :
     */
    public function get_term_list() {
        $crclm_id = $this->input->post('curriculum');
        $crclm_list = $this->t2pharm_c2_teaching_process_model->get_term_list($crclm_id);
        $options = '<option value>Select Term</option>';
        foreach ($crclm_list as $list) {
            $options .= '<option value="' . $list['crclm_term_id'] . '">' . $list['term_name'] . '</option>';
        }
        echo $options;
    }

    /**
     * Function to get the Course List - section 3.2.1 
     * @parameters  :
     * @return      :
     */
    public function get_course_list() {
        $crclm_id = $this->input->post('crclm_id');
        $term_id = $this->input->post('term_id');
        $course_list = $this->t2pharm_c2_teaching_process_model->get_course_list($crclm_id, $term_id);
        $options = '<option value>Select Course</option>';
        foreach ($course_list as $list) {
            $options .= '<option value="' . $list['crs_id'] . '">' . $list['crs_title'] . '</option>';
        }
        echo $options;
    }

    /**
     * Function to display Quality of student projects tabel - section 2.2.3
     * @parameters  : 
     * @return      : table
     */
    public function get_quality_student_project($crclm_id = NULL, $course_id = NULL) {
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

        if ($crclm_id == NULL) {
            $crclm_id = $this->input->post('crclm_id');
            $term_id = $this->input->post('term_id');
            $course_id = $this->input->post('course_id');
            $co_po_map = $this->t2pharm_c2_teaching_process_model->get_quality_student_project($crclm_id, $course_id);
            $student_project_quality = $this->table->generate($co_po_map);
            echo json_encode($student_project_quality);
            exit;
        } else {
            $co_po_map = $this->t2pharm_c2_teaching_process_model->get_quality_student_project($crclm_id, $course_id);
            $student_project_quality = $this->table->generate($co_po_map);
            return $student_project_quality;
        }
    }

    /**
     * Function to display companies visited at program level - section 2.2.4
     * @parameters  : program id
     * @return      :
     */
    public function get_program_companies_visited($pgm_id = NULL) {
        $this->load->library('table');
        $template = array(
            'table_open' => '<table class="table table-bordered" id="program_companies_visited" >',
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
        $pgm_id = $this->input->post('pgm_id');
        $this->table->set_template($template);

        $companies_visited = $this->t2pharm_c2_teaching_process_model->fetch_program_companies_visited($pgm_id);

        return $this->table->generate($companies_visited);
    }

    /**
     * Function to dispaly industry internship view - section 2.2.5
     * @parameters  :
     * @return      :
     */
    public function get_industry_internship($id = '', $nba_sar_id = '', $nba_report_id = '', $other_info = array(), $is_export = 0, $param = '') {
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
            $content['component_view'] = $this->display_industry_internship($curriculum_id);
        }
        $this->data['is_export'] = $is_export;
        $this->data['crclm_id'] = @$crclm_id = $filter_list_data['curriculum_list'];
        $this->data['filter_list'] = @$crclm_id;
        $this->data['curriculum_list'] = $this->nba_sar_list_model->list_curriculum($pgm_id);
        $this->data['industry_internship'] = empty($content['component_view']) ? '' : $content['component_view'];
        return $this->load->view('nba_sar/pharm/tier2/criterion_2/t2pharm_c2_2_4_initiative_industry_intership_vw', $this->data, true);
    }

    /**
     * Function to fetch industry internship table - section 2.2.5
     * @parameters  : 
     * @return      : table
     */
    public function display_industry_internship($curriculum = NULL) {
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

        if ($this->input->post('curriculum')) {
            $curriculum = $this->input->post('curriculum');
            $industry_internship = $this->t2pharm_c2_teaching_process_model->fetch_industry_internship($curriculum);
            $data['industry_internship'] = $this->table->generate($industry_internship);
            echo json_encode($data);
            exit;
        } else {
            $industry_internship = $this->t2pharm_c2_teaching_process_model->fetch_industry_internship($curriculum);
            return $this->table->generate($industry_internship);
        }
    }

}

/* * End of file t2pharm_c2_teaching_learning_process.php
 * Location: .nba_sar/t2pharm_c2_teaching_learning_process.php 
 */
?>