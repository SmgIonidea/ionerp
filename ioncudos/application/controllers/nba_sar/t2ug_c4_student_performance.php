<?php

/**
 * Description          :   Controller logic for Criterion 4 (TIER 2)- Student Performance
 * Created              :   20-12-2016
 * Author               :   Shayista Mulla 
 * Modification History :
 * Date                     Modified By			Description
  ------------------------------------------------------------------------------------- */
?>

<?php

defined('BASEPATH') or exit('No direct script access allowed');

class T2ug_c4_student_performance extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('nba_sar/nba_sar_list_model');
        $this->load->model('nba_sar/ug/tier2/criterion_4/t2ug_c4_student_performance_model');
    }

    /**
     * Show all navigation groups
     * @parameters  :
     * @return      :void
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
     * Function to fetch and display students' performance - Section 4
     * @parameters  :
     * @return      : view
     */
    public function students_performance($id = '', $nba_sar_id = '', $nba_report_id = '', $other_info = array(), $is_export = 0, $param = '') {
        extract($other_info);
        $filters = $this->nba_sar_list_model->nba_filters_list($id, $nba_report_id);
        $this->data['view_id'] = $id;
        $this->data['nba_report_id'] = $nba_report_id;
        $crs_list = $filter_list_data = $content = array();
        $curriculum_id = '';

        if (!empty($filters ['filter_list'])) {
            foreach ($filters ['filter_list'] as $filter_list_value) {
                $filter_list_data [$filter_list_value ['filter_ids']] = $filter_list_value ['filter_value'];
                if ($filter_list_value ['filter_ids'] == 'curriculum_list') {
                    $curriculum_id = $filter_list_value['filter_value'];
                }
            }
            $content = $this->fetch_student_performance($filter_list_value ['filter_value'], $pgm_id);
        }

        $this->data['crclm_id'] = @$crclm_id = $filter_list_data['curriculum_list'];
        $this->data['filter_list'] = $filter_list_data;
        $this->data['curriculum_list'] = $this->nba_sar_list_model->list_curriculum($pgm_id);
        $this->data['curriculum_year_list'] = $this->nba_sar_list_model->list_curriculum_year($pgm_id);
        $section_1 = empty($content) ? '' : $content;
        $this->data ['section_1'] = $section_1;
        $this->data['is_export'] = $is_export;

        return $this->load->view('nba_sar/ug/tier2/criterion_4/t2ug_c4_students_performance_vw', $this->data, true);
    }

    /**
     * function to fetch student performance tables - section 4
     * @parameters  : curriculum id,progrm id
     * @return      : student performance view
     */
    public function fetch_student_performance($curriculum_id = '', $pgm_id = '') {
        if ($this->input->post('curriculum_id')) {
            $curriculum_id = $this->input->post('curriculum_id');
            $pgm_id = $this->input->post('pgm_id');
            $result_year = $this->t2ug_c4_student_performance_model->fetch_curriculum_year($curriculum_id);
            $year = $result_year['start_year'];
            $stud_performance['section_1'] = $this->t2ug_c4_student_performance_model->fetch_student_performance($pgm_id, $year);
            $stud_performance['section_2'] = $this->t2ug_c4_student_performance_model->fetch_student_without_backlogs($pgm_id, $year);
            $stud_performance['section_3'] = $this->t2ug_c4_student_performance_model->fetch_students_graduated($pgm_id, $year);
            $this->data ['cay_value'] = $this->t2ug_c4_student_performance_model->fetch_curriculum_past_year($curriculum_id);
            $this->data ['section_1'] = $stud_performance['section_1'];
            $this->data ['section_2'] = $stud_performance['section_2'];
            $this->data ['section_3'] = $stud_performance['section_3'];
            $content_view = $this->load->view('nba_sar/ug/tier2/criterion_4/t2ug_c4_student_performance_table_vw', $this->data, true);
            echo $content_view;
        } else {
            $pgm_id = $pgm_id;
            $result_year = $this->t2ug_c4_student_performance_model->fetch_curriculum_year($curriculum_id);
            $year = $result_year['start_year'];
            $stud_performance['section_1'] = $this->t2ug_c4_student_performance_model->fetch_student_performance($pgm_id, $year);
            $stud_performance['section_2'] = $this->t2ug_c4_student_performance_model->fetch_student_without_backlogs($pgm_id, $year);
            $stud_performance['section_3'] = $this->t2ug_c4_student_performance_model->fetch_students_graduated($pgm_id, $year);
            $this->data ['cay_value'] = $this->t2ug_c4_student_performance_model->fetch_curriculum_past_year($curriculum_id);
            $this->data ['section_1'] = $stud_performance['section_1'];
            $this->data ['section_2'] = $stud_performance['section_2'];
            $this->data ['section_3'] = $stud_performance['section_3'];
            $content_view = $this->load->view('nba_sar/ug/tier2/criterion_4/t2ug_c4_student_performance_table_vw', $this->data, true);
            return $content_view;
        }
    }

    /**
     * Function to display Enrollment Ratio of particular progam and year.- section 4.1
     * @parameters  :
     * @return      : enrollment ratio view.
     */
    public function enrolment_ratio($id = '', $nba_sar_id = '', $nba_report_id = '', $other_info = array(), $is_export = 0, $param = '') {
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
        $this->data['filter_list'] = @$crclm_id;
        $this->data['curriculum_list'] = $this->nba_sar_list_model->list_curriculum($pgm_id);
        $this->data['is_export'] = $is_export;

        if ($crclm_id) {
            $this->data['enrolment_ratio_data'] = $this->fetch_enrolment_ratio($crclm_id);
        }

        return $this->load->view('nba_sar/ug/tier2/criterion_4/t2ug_c4_1_enrolment_ratio_vw', $this->data, true);
    }

    /*
     * Function to fetch the enrollment ratio table - section 4.1
     * @parameters  : curriculum id
     * @return      : enrollment ratio table view
     */

    public function fetch_enrolment_ratio($curriculum = NULL) {
        if ($curriculum == NULL) {
            //display view page
            $crclm_id = $this->input->post('curriculum');
            $ratio = $this->t2ug_c4_student_performance_model->fetch_enrolment_ratio($crclm_id);
            $this->data['enrolment_ratio'] = $ratio;
            $view_data = $this->load->view('nba_sar/ug/tier2/criterion_4/t2ug_c4_1_enrolment_ratio_table_vw', $this->data, true);
            echo $view_data;
        } else {
            //return back the value so that view page can be exported
            $ratio = $this->t2ug_c4_student_performance_model->fetch_enrolment_ratio($curriculum);
            $this->data['enrolment_ratio'] = $ratio;
            $view_data = $this->load->view('nba_sar/ug/tier2/criterion_4/t2ug_c4_1_enrolment_ratio_table_vw', $this->data, true);
            return $view_data;
        }
    }

    /*
     * Function to display the student success rate without backlog.- section 4.2.1
     * @parameters  :
     * @return      : student success rate view
     */

    public function success_rate_in_program($id = '', $nba_sar_id = '', $nba_report_id = '', $other_info = array(), $is_export = 0, $param = '') {
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
            $content = $this->fetch_success_rate_in_program($filter_list_value ['filter_value']);
        }

        $this->data['crclm_id'] = @$crclm_id = $filter_list_data['curriculum_list'];
        $this->data['filter_list'] = @$crclm_id;
        $this->data['curriculum_list'] = $this->nba_sar_list_model->list_curriculum($pgm_id);
        $success_rate = empty($content) ? '' : $content;
        $this->data ['success_rate'] = $success_rate;
        $this->data['is_export'] = $is_export;

        if ($is_export) {
            return $this->data['success_rate'];
        } else {
            return $this->load->view('nba_sar/ug/tier2/criterion_4/t2ug_c4_2_1_success_rate_vw', $this->data, true);
        }
    }

    /*
     * Function to fetch table view of success rates without backlog in the program.- section 4.2.1
     * @parameters  : curriculum id
     * @return      : table view
     */

    public function fetch_success_rate_in_program($curriculum = NULL) {
        if ($curriculum == NULL) {
            $crclm_id = $this->input->post('curriculum');
            $this->data['success_rate'] = $this->t2ug_c4_student_performance_model->fetch_success_rate($crclm_id);
            $this->data['average_si'] = $this->t2ug_c4_student_performance_model->fetch_success_rate_avg_si($crclm_id);
            echo $this->load->view('nba_sar/ug/tier2/criterion_4/t2ug_c4_2_1_success_rate_table_vw', $this->data, true);
        } else {
            $this->data['success_rate'] = $this->t2ug_c4_student_performance_model->fetch_success_rate($curriculum);
            $this->data['average_si'] = $this->t2ug_c4_student_performance_model->fetch_success_rate_avg_si($curriculum);
            return $this->load->view('nba_sar/ug/tier2/criterion_4/t2ug_c4_2_1_success_rate_table_vw', $this->data, true);
        }
    }

    /*
     * Function is to fetch success rate in stipulated period - Section 4.2.2
     * @parameters  :
     * @return      : success rate in stipulated period view.
     */

    public function success_rate_in_stipulated_period($id = '', $nba_sar_id = '', $nba_report_id = '', $other_info = array(), $is_export = 0, $param = '') {
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
        $this->data['filter_list'] = @$crclm_id;
        $this->data['curriculum_list'] = $this->nba_sar_list_model->list_curriculum($pgm_id);
        $this->data['is_export'] = $is_export;

        if (!empty($filter_list_data['curriculum_list'])) {
            $this->data['success_rate'] = $this->fetch_success_rate_in_stipulated_period($crclm_id);
        }

        if ($is_export) {
            $success_rate = empty($this->data['success_rate']) ? '' : $this->data['success_rate'];
            return $success_rate;
        } else {
            return $this->load->view('nba_sar/ug/tier2/criterion_4/t2ug_c4_2_2_success_rate_in_stipulated_period_vw', $this->data, true);
        }
    }

    /**
     * Function to fetch success rate in stipulated period table view - section 4.2.2
     * @parameters  : curriculum id
     * @return      : table view
     */
    public function fetch_success_rate_in_stipulated_period($curriculum=NULL) {
        if ($curriculum == NULL) {
            $crclm_id = $this->input->post('curriculum');
            $data['success_rate'] = $this->t2ug_c4_student_performance_model->fetch_success_rate_in_stipulated($crclm_id);
            $data['average_si'] = $this->t2ug_c4_student_performance_model->fetch_success_rate_in_stipulated_avg_si($crclm_id);
            echo $this->load->view('nba_sar/ug/tier2/criterion_4/t2ug_c4_2_2_success_rate_in_stipulated_period_table_vw', $data, true);
        } else {
            $data['success_rate'] = $this->t2ug_c4_student_performance_model->fetch_success_rate_in_stipulated($curriculum);
            $data['average_si'] = $this->t2ug_c4_student_performance_model->fetch_success_rate_in_stipulated_avg_si($curriculum);
            return $this->load->view('nba_sar/ug/tier2/criterion_4/t2ug_c4_2_2_success_rate_in_stipulated_period_table_vw', $data, true);
        }
    }

    /**
     * Function for to display academic performance in the third year - Section 4.3
     * @parameters  :
     * @return      : academic performance view
     */
    public function academic_performance_third_yr($id = '', $nba_sar_id = '', $nba_report_id = '', $other_info = array(), $is_export = 0, $param = '') {
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
        $this->data['filter_list'] = @$crclm_id;
        $this->data['curriculum_list'] = $this->nba_sar_list_model->list_curriculum($pgm_id);
        $this->data['is_export'] = $is_export;

        if (!empty($filter_list_data['curriculum_list'])) {
            $this->data['academic_performance'] = $this->fetch_academic_performance_third_yr($crclm_id);
        }

        if ($is_export) {
            $academic_performance = empty($this->data['academic_performance']) ? '' : $this->data['academic_performance'];
            return $academic_performance;
        } else {
            return $this->load->view('nba_sar/ug/tier2/criterion_4/t2ug_c4_3_academic_performance_third_yr_vw', $this->data, true);
        }
    }

    /**
     * Function is to fetch academic performance in third year table view - section 4.3
     * @parameters  : curriculum id
     * @return      : table view
     */
    public function fetch_academic_performance_third_yr($curriculum = NULL) {
        if ($curriculum == NULL) {
            $crclm_id = $this->input->post('curriculum');
            $data['academic_performance'] = $this->t2ug_c4_student_performance_model->fetch_academic_performance_third_yr($crclm_id);
            echo $this->load->view('nba_sar/ug/tier2/criterion_4/t2ug_c4_3_academic_performance_third_yr_table_vw', $data, true);
        } else {
            $data['academic_performance'] = $this->t2ug_c4_student_performance_model->fetch_academic_performance_third_yr($curriculum);
            return $this->load->view('nba_sar/ug/tier2/criterion_4/t2ug_c4_3_academic_performance_third_yr_table_vw', $data, true);
        }
    }

    /**
     * Function for to display academic performance in second year - section 4.4
     * @parameters  :
     * @return      : academic performance view
     */
    public function academic_performance_sec_yr($id = '', $nba_sar_id = '', $nba_report_id = '', $other_info = array(), $is_export = 0, $param = '') {
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
        $this->data['filter_list'] = @$crclm_id;
        $this->data['curriculum_list'] = $this->nba_sar_list_model->list_curriculum($pgm_id);
        $this->data['is_export'] = $is_export;

        if (!empty($filter_list_data['curriculum_list'])) {
            $this->data['academic_performance'] = $this->fetch_academic_performance_sec_yr($crclm_id);
        }

        if ($is_export) {
            $academic_performance = empty($this->data['academic_performance']) ? '' : $this->data['academic_performance'];
            return $academic_performance;
        } else {
            return $this->load->view('nba_sar/ug/tier2/criterion_4/t2ug_c4_4_academic_performance_sec_yr_vw', $this->data, true);
        }
    }

    /**
     * Function is to fetch academic performance in the second year table view - section 4.4
     * @parameters  : curriculum id
     * @return      : table view
     */
    public function fetch_academic_performance_sec_yr($curriculum = NULL) {
        if ($curriculum == NULL) {
            $crclm_id = $this->input->post('curriculum');
            $data['academic_performance'] = $this->t2ug_c4_student_performance_model->fetch_academic_performance_sec_yr($crclm_id);
            echo $this->load->view('nba_sar/ug/tier2/criterion_4/t2ug_c4_4_academic_performance_sec_yr_table_vw', $data, true);
        } else {
            $data['academic_performance'] = $this->t2ug_c4_student_performance_model->fetch_academic_performance_sec_yr($curriculum);
            return $this->load->view('nba_sar/ug/tier2/criterion_4/t2ug_c4_4_academic_performance_sec_yr_table_vw', $data, true);
        }
    }

    /**
     * Function for Placement, Higher Studies and Entrepreneurship - Section 4.5 
     * @parameters  :
     * @return      : Placement, Higher Studies and Entrepreneurship view
     */
    public function placement_higher_studies($id = '', $nba_sar_id = '', $nba_report_id = '', $other_info = array(), $is_export = 0, $param = '') {
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
        $this->data['filter_list'] = @$crclm_id;
        $this->data['curriculum_list'] = $this->nba_sar_list_model->list_curriculum($pgm_id);
        $this->data['is_export'] = $is_export;

        if (!empty($filter_list_data['curriculum_list'])) {
            $this->data['placement_higher_studies'] = $this->fetch_placement_higher_studies($crclm_id);
        }

        if ($is_export) {
            $placement_higher_studies = empty($this->data['placement_higher_studies']) ? '' : $this->data['placement_higher_studies'];
            return $placement_higher_studies;
        } else {
            return $this->load->view('nba_sar/ug/tier2/criterion_4/t2ug_c4_5_placement_higher_studies_vw', $this->data, true);
        }
    }

    /**
     * Function is to fetch Placement,Higher Studies and Entrepreneurship table view - Section 4.5
     * @parameters  : curriculum id
     * @return      : table view
     */
    public function fetch_placement_higher_studies($curriculum = NULL) {
        if ($curriculum == NULL) {
            $crclm_id = $this->input->post('curriculum');
            $data['placement_higher_studies'] = $this->t2ug_c4_student_performance_model->fetch_placement_higher_studies($crclm_id);
            echo $this->load->view('nba_sar/ug/tier2/criterion_4/t2ug_c4_5_placement_higher_studies_table_vw', $data, true);
        } else {
            $data['placement_higher_studies'] = $this->t2ug_c4_student_performance_model->fetch_placement_higher_studies($curriculum);
            return $this->load->view('nba_sar/ug/tier2/criterion_4/t2ug_c4_5_placement_higher_studies_table_vw', $data, true);
        }
    }

    /**
     * function for professional societies / chapters and organizing engineering event - section 4.6.1
     * @parameters  :
     * @return      : professional societies view
     */
    public function professional_societies($id = '', $nba_sar_id = '', $nba_report_id = '', $other_info = array(), $is_export = 0, $param = '') {
        extract($other_info);
        $this->data ['view_id'] = $id;
        $this->data ['nba_report_id'] = $nba_report_id;
        $dept_id = $this->input->post('dept_id');
        $this->data['professional_society'] = $this->fetch_professional_societies($dept_id);
        $this->data ['is_export'] = $is_export;

        return $this->load->view('nba_sar/ug/tier2/criterion_4/t2ug_c4_6_1_prof_societies_vw', $this->data, true);
    }

    /**
     * function to fetch professional societies details - section 4.6.1
     * @parameters  : department id
     * @return      : professional societies details
     */
    public function fetch_professional_societies($dept_id = NULL) {
        $prof_society = $this->t2ug_c4_student_performance_model->fetch_professional_societies($dept_id);
        return $prof_society;
    }

    /**
     * function for section 4.6.2 Publication of technical magazines, newsletters..etc - section 4.6.2
     * @parameters  :
     * @return      : Publication view
     */
    public function publications($id = '', $nba_sar_id = '', $nba_report_id = '', $other_info = array(), $is_export = 0, $param = '') {
        extract($other_info);
        $this->data['view_id'] = $id;
        $this->data['nba_report_id'] = $nba_report_id;
        $dept_id = $this->input->post('dept_id');
        $this->data['publications'] = $this->fetch_publications($dept_id);
        $this->data['is_export'] = $is_export;

        return $this->load->view('nba_sar/ug/tier2/criterion_4/t2ug_c4_6_2_publications_vw', $this->data, true);
    }

    /**
     * function to fetch publications details - section 4.6.2
     * @parameters  : department id
     * @return      : array
     */
    public function fetch_publications($dept_id = NULL) {
        $publications = $this->t2ug_c4_student_performance_model->fetch_publications($dept_id);
        return $publications;
    }

    /**
     * function to fetch inter-institute events by students - section 4.6.3
     * @parameters  :
     * @return      :
     */
    public function inter_institute_events($id = '', $nba_sar_id = '', $nba_report_id = '', $other_info = array(), $is_export = 0, $param = '') {
        extract($other_info);
        $this->data ['view_id'] = $id;
        $this->data ['nba_report_id'] = $nba_report_id;
        $dept_id = $this->input->post('dept_id');
        $this->data['inter_institute_events'] = $this->fetch_inter_institute_events($dept_id);
        $this->data ['is_export'] = $is_export;
        return $this->load->view('nba_sar/ug/tier2/criterion_4/t2ug_c4_6_3_inter_institute_events_vw', $this->data, true);
    }

    /**
     * function to fetch inter-institute events by students - section 4.6.3
     * @parameters  : department id
     * @return      : array
     */
    public function fetch_inter_institute_events($dept_id = NULL) {
        $inter_institute_events = $this->t2ug_c4_student_performance_model->fetch_inter_institute_events($dept_id);
        return $inter_institute_events;
    }

}

/*
 * End of file t2ug_c4_student_performance.php 
 * Location: .nba_sar/t2ug_c4_student_performance.php
 */
?>