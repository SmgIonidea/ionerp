<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * Description	:	Tier II CO Level Attainment

 * Created		:	December 14th, 2015

 * Author		:	 Shivaraj B

 * Modification History:
 * Date				Modified By				Description

  ---------------------------------------------------------------------------------------------- */

class Co_level_attainment extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        }
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('assessment_attainment/tier_ii/co_level_attainment/co_level_attainment_model');
        $this->load->model('survey/Survey');
    }

    /* Topics
     * Function is to check for user login and to display the list.
     * And fetches data for the Program drop down box.
     * @param - ------.
     * returns the list of topics and its contents.
     */

    public function index() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            $data['deptlist'] = $this->co_level_attainment_model->dropdown_dept_title();
            $data['crclm_data'] = $this->co_level_attainment_model->crlcm_drop_down_fill();
            $data['title'] = "Course Level Attainment";
            $this->load->view('assessment_attainment/tier_ii/co_level_attainment/co_level_attainment_vw', $data);
        }
    }

    /*
     * Function is to fetch term list for term drop down box.
     * @param - ------.
     * returns the list of terms.
     */

    public function select_term() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            $crclm_id = $this->input->post('crclm_id');
            $term_data = $this->co_level_attainment_model->term_drop_down_fill($crclm_id);

            $i = 0;
            $list[$i++] = '<option value="">Select Term</option>';
            //$list[$i++] = '<option value="select_all_term">Select All Terms</option>';

            foreach ($term_data as $data) {
                $list[$i] = "<option value=" . $data['crclm_term_id'] . ">" . $data['term_name'] . "</option>";
                $i++;
            }
            $list = implode(" ", $list);
            echo $list;
        }
    }

    /*
     * Function is to fetch term list for term drop down box.
     * @param - ------.
     * returns the list of terms.
     */

    public function select_course() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            $term = $this->input->post('term_id');
            $term_data = $this->co_level_attainment_model->course_drop_down_fill($term);


            if ($term_data) {
                $i = 0;
                $list[$i++] = '<option value="">Select Course</option>';
                //$list[$i++] = '<option value="select_all_course"><b>Select all Courses</b></option>';
                foreach ($term_data as $data) {
                    $list[$i] = "<option value=" . $data['crs_id'] . ">" . $data['crs_title'] . "</option>";
                    $i++;
                }
            } else {
                $i = 0;
                $list[$i++] = '<option value="">Select Course</option>';
                $list[$i] = '<option value="">No Assessment Data Imported to Courses</option>';
            }
            $list = implode(" ", $list);
            echo $list;
        }
    }

    /*
     * Function is to fetch term list for term drop down box.
     * @param - ------.
     * returns the list of terms.
     */

    public function select_survey() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            $course = $this->input->post('course');
            $survey_data = $this->co_level_attainment_model->survey_drop_down_fill($course);


            if ($survey_data) {
                $i = 0;
                $list[$i++] = '<option value="">Select Survey</option>';
                //$list[$i++] = '<option value="select_all_course"><b>Select all Courses</b></option>';
                foreach ($survey_data as $data) {
                    $list[$i] = "<option value=" . $data['survey_id'] . ">" . $data['name'] . "</option>";
                    $i++;
                }
            } else {
                $i = 0;
                $list[$i++] = '<option value="">Select Survey</option>';
                $list[$i] = '<option value="">No Surveys to display</option>';
            }
            $list = implode(" ", $list);
            echo $list;
        }
    }

    /*
     * Function is to fetch occasions list for occasion drop down box.
     * @param - ------.
     * returns the list of occasions.
     */

    public function select_occasion() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            $crclm_id = $this->input->post('crclm_id');
            $term_id = $this->input->post('term_id');
            $crs_id = $this->input->post('crs_id');
            $occasion_data = $this->co_level_attainment_model->occasion_fill($crclm_id, $term_id, $crs_id);
            $i = 0;
            $list = array();
            if ($occasion_data) {
                $list[$i++] = '<option value="">Select Occasion</option>';
                foreach ($occasion_data as $data) {
                    $list[$i] = "<option value=" . $data['ao_id'] . ">" . $data['ao_description'] . "</option>";
                    $i++;
                }
                $list[$i++] = '<option value="all_occasion">All Occasion</option>';
                $list = implode(" ", $list);
            }
            if ($list) {
                echo $list;
            } else {
                echo 0;
            }
        }
    }

    public function getCOSurveyReportData() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            $survey_id = $this->input->post('survey_id');
            //var_dump($survey_id);exit;

            $graph_data = $this->co_level_attainment_model->getCOSurveyReportData($survey_id);

            if ($graph_data) {
                echo json_encode($graph_data);
            } else {
                echo 0;
            }
        }
    }

    public function getDirectIndirectCOAttaintmentData() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            //var_dump($_POST);//exit;
            $survey_id = $this->input->post('survey_id');
            $course_id = $this->input->post('course_comparison');
            $direct_attainment = $this->input->post('direct_attainment_val');
            $indirect_attainment = $this->input->post('indirect_attainment_val');
            $type_data = $this->input->post('type_data');
            $occasion = $this->input->post('occasion');

            if ($type_data == 1) {
                $qp_rolled_out = $this->co_level_attainment_model->qp_direct_indirect($course_id);
                $qpd_id = $qp_rolled_out[0]['qpd_id'];
                $qpd_type = 5;
                $usn = NULL;
            } else if ($type_data == 2) {
                if ($occasion != 'all_occasion') {
                    $qpd_id = $occasion;
                    $qpd_type = 3;
                    $usn = NULL;
                } else {
                    $qpd_id = NULL;
                    $qpd_type = 3;
                    $usn = NULL;
                }
            } else {
                $qpd_id = NULL;
                $qpd_type = 'BOTH';
                $usn = NULL;
            }

            $graph_data = $this->co_level_attainment_model->getDirectIndirectCOAttaintmentData($course_id, $qpd_id, $usn, $qpd_type, $direct_attainment, $indirect_attainment, $survey_id, $type_data);

            if ($graph_data) {
                echo json_encode($graph_data);
            } else {
                echo 0;
            }
        }
    }

    // Finalize CO Attainment and store onto the table direct_attainment
    public function finalize_getDirectIndirectCOAttaintmentData() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            $survey_id = $this->input->post('survey_id');
            $course_id = $this->input->post('course_comparison');
            $direct_attainment = $this->input->post('direct_attainment_val');
            $indirect_attainment = $this->input->post('indirect_attainment_val');
            $type_data = $this->input->post('type_data');
            $occasion = $this->input->post('occasion');

            if ($type_data == 1) {
                $qp_rolled_out = $this->co_level_attainment_model->qp_direct_indirect($course_id);
                $qpd_id = $qp_rolled_out[0]['qpd_id'];
                $qpd_type = 5;
                $usn = NULL;
            } else if ($type_data == 2) {
                if ($occasion != 'all_occasion') {
                    $qpd_id = $occasion;
                    $qpd_type = 3;
                    $usn = NULL;
                } else {
                    $qpd_id = NULL;
                    $qpd_type = 3;
                    $usn = NULL;
                }
            } else {
                $qpd_id = NULL;
                $qpd_type = 'BOTH';
                $usn = NULL;
            }
            $graph_data = $this->co_level_attainment_model->finalize_getDirectIndirectCOAttaintmentData($course_id, $qpd_id, $usn, $qpd_type, $direct_attainment, $indirect_attainment, $survey_id, $type_data);

            if ($graph_data) {
                echo 1;
            } else {
                echo 0;
            }
        }
    }

    public function export_to_pdf_indirect_attainment() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            $this->load->library('MPDF56/mpdf');
            $course_indirect_attainment_graph_data = $this->input->post('course_outcome_indirect_attainment_graph_data_hidden');
            $mpdf = new mPDF('utf-8', '', '', 0, 15, 15, 40, 16, 9, 10, 9, 11, 'A4-L');
            $mpdf->SetDisplayMode('fullpage');
            $stylesheet = 'twitterbootstrap/css/table.css';
            $stylesheet = file_get_contents($stylesheet);

            $mpdf->WriteHTML($stylesheet, 1);
            $html = "<html><body>" . $course_indirect_attainment_graph_data . "</body></html>";

            $mpdf->SetHTMLHeader('<img src="' . base_url() . 'twitterbootstrap/img/pdf_header.png"/>');
            $mpdf->SetHTMLFooter('<img src="' . base_url() . 'twitterbootstrap/img/pdf_footer.png"/>');
            $mpdf->AddPage('L');
            $mpdf->WriteHTML($html);
            $mpdf->Output();
            return;
        }
    }

//End of function

    public function export_to_pdf_direct_indirect_attainment() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            $this->load->library('MPDF56/mpdf');
            $course_direct_indirect_attainment_graph_data = $this->input->post('direct_indirect_attain_data_hidden');
            $mpdf = new mPDF('utf-8', '', '', 0, 15, 15, 40, 16, 9, 10, 9, 11, 'A4-L');
            $mpdf->SetDisplayMode('fullpage');
            $stylesheet = 'twitterbootstrap/css/table.css';
            $stylesheet = file_get_contents($stylesheet);

            $mpdf->WriteHTML($stylesheet, 1);
            $html = "<html><title>Co Indirect Attainment| IonCUDOS</title><body>" . $course_direct_indirect_attainment_graph_data . "</body></html>";

            $mpdf->SetHTMLHeader('<img src="' . base_url() . 'twitterbootstrap/img/pdf_header.png"/>');
            $mpdf->SetHTMLFooter('<img src="' . base_url() . 'twitterbootstrap/img/pdf_footer.png"/>');
            $mpdf->AddPage('L');
            $mpdf->WriteHTML($html);
            $mpdf->Output();
            return;
        }
    }

    /*
     * Function is to fetch qpd_id of all terms TEE
     * @param - ------.
     * returns the list of qpd_id.
     */

    public function select_qpd_all_terms() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            $crclm_id = $this->input->post('crclm_id');
            $term_id = $this->input->post('term_id');
            $tee_qpd = $this->co_level_attainment_model->select_qpd_all_terms($crclm_id, $term_id);
            $i = 0;
            $tee_qpd_list = array();
            if ($tee_qpd) {
                foreach ($tee_qpd as $data) {
                    $tee_qpd_list[$i++] = $data['qpd_id'];
                }
                $tee_qpd_list = implode(",", $tee_qpd_list);
            }
            if ($tee_qpd_list) {
                echo $tee_qpd_list;
            } else {
                echo 0;
            }
        }
    }

    /**/

    public function getCourseCOThreshholdAttainment() {
        $entity_see = $this->lang->line('entity_see');
        $entity_cie = $this->lang->line('entity_cie');
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            $crs = $this->input->post('course');
            $type = $this->input->post('type');
            if ($type == 'tee') {
                $qp_rolled_out = $this->co_level_attainment_model->qp_rolled_out($crs);
                if ($qp_rolled_out) {
                    //$type = 'TEE';
                    $type = $entity_see;
                    $graph_data = $this->co_level_attainment_model->getCourseCOThreshholdAttainment($crs, $qp_rolled_out[0]['qpd_id'], $type);
                    if ($graph_data) {
                        echo json_encode($graph_data);
                    }
                } else {
                    echo 0;
                }
            } else if ($type == 'cia') {
                $qpd_id = $this->input->post('qpd_id');
                //$type = 'CIA';
                $type = $entity_cie;

                $graph_data = $this->co_level_attainment_model->getCourseCOThreshholdAttainment($crs, $qpd_id, $type);
                if ($graph_data) {
                    echo json_encode($graph_data);
                } else {
                    echo 0;
                }
            } else if ($type == 'cia_tee') {
                $qpd_id = NULL;
                $graph_data = $this->co_level_attainment_model->getCourseCOThreshholdAttainment($crs, $qpd_id, $type);
                if ($graph_data) {
                    echo json_encode($graph_data);
                } else {
                    echo 0;
                }
            }
        }
    }

    public function getCourseCOAttainment() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            $crs = $this->input->post('course');
            $type = $this->input->post('type');
            if ($type == 'tee') {
                $qp_rolled_out = $this->co_level_attainment_model->qp_rolled_out($crs);
                if ($qp_rolled_out) {
                    $type = 'TEE';
                    $graph_data = $this->co_level_attainment_model->getCourseCOAttainment($crs, $qp_rolled_out[0]['qpd_id'], $type);
                    if ($graph_data) {
                        echo json_encode($graph_data);
                    }
                } else {
                    echo 0;
                }
            } else if ($type == 'cia') {
                $qpd_id = $this->input->post('qpd_id');
                $type = 'CIA';
                $graph_data = $this->co_level_attainment_model->getCourseCOAttainment($crs, $qpd_id, $type);
                if ($graph_data) {
                    echo json_encode($graph_data);
                } else {
                    echo 0;
                }
            } else if ($type == 'cia_tee') {
                $qpd_id = NULL;
                $type = 'BOTH';
                $graph_data = $this->co_level_attainment_model->getCourseCOAttainment($crs, $qpd_id, $type);
                if ($graph_data) {
                    echo json_encode($graph_data);
                } else {
                    echo 0;
                }
            }
        }
    }

    /**/

    public function CourseBloomsLevelAttainment() {
        $entity_see = $this->lang->line('entity_see');
        $entity_cie = $this->lang->line('entity_cie');
        $both = $entity_cie . ' & ' . $entity_see;
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            $crs = $this->input->post('course');
            $type = $this->input->post('type');
            if ($type == 'tee') {
                $qp_rolled_out = $this->co_level_attainment_model->qp_rolled_out($crs);
                if ($qp_rolled_out) {
                    //$type = 'TEE';
                    $type = $entity_see;

                    $graph_data = $this->co_level_attainment_model->CourseBloomsLevelAttainment($crs, $qp_rolled_out[0]['qpd_id'], $type);

                    if ($graph_data) {
                        echo json_encode($graph_data);
                    }
                } else {
                    echo 0;
                }
            } else if ($type == 'cia') {
                $qpd_id = $this->input->post('qpd_id');
                //$type = 'CIA';
                $type = $entity_cie;

                $graph_data = $this->co_level_attainment_model->CourseBloomsLevelAttainment($crs, $qpd_id, $type);
                if ($graph_data) {
                    echo json_encode($graph_data);
                } else {
                    echo 0;
                }
            } else if ($type == 'cia_tee') {
                $qpd_id = NULL;
                //$type='BOTH';
                $type = $both;

                $graph_data = $this->co_level_attainment_model->CourseBloomsLevelAttainment($crs, $qpd_id, $type);
                if ($graph_data) {
                    echo json_encode($graph_data);
                } else {
                    echo 0;
                }
            }
        }
    }

    /**/

    public function CourseBloomsLevelThresholdAttainment() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            $crs = $this->input->post('course');
            $type = $this->input->post('type');
            if ($type == 'tee') {
                $qp_rolled_out = $this->co_level_attainment_model->qp_rolled_out($crs);
                if ($qp_rolled_out) {
                    $type = 'TEE';
                    $graph_data = $this->co_level_attainment_model->CourseBloomsLevelThresholdAttainment($crs, $qp_rolled_out[0]['qpd_id'], $type);

                    if ($graph_data) {
                        echo json_encode($graph_data);
                    }
                } else {
                    echo 0;
                }
            } else if ($type == 'cia') {
                $qpd_id = $this->input->post('qpd_id');
                $type = 'CIA';
                $graph_data = $this->co_level_attainment_model->CourseBloomsLevelThresholdAttainment($crs, $qpd_id, $type);
                if ($graph_data) {
                    echo json_encode($graph_data);
                } else {
                    echo 0;
                }
            } else if ($type == 'cia_tee') {
                $qpd_id = NULL;
                $type = 'BOTH';
                $graph_data = $this->co_level_attainment_model->CourseBloomsLevelThresholdAttainment($crs, $qpd_id, $type);
                if ($graph_data) {
                    echo json_encode($graph_data);
                } else {
                    echo 0;
                }
            }
        }
    }

    public function BloomsLevelMarksDistribution() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            $crs = $this->input->post('course');
            $type = $this->input->post('type');
            if ($type == 'tee') {
                $qp_rolled_out = $this->co_level_attainment_model->qp_rolled_out($crs);
                if ($qp_rolled_out) {
                    $graph_data = $this->co_level_attainment_model->BloomsLevelMarksDistribution($crs, $qp_rolled_out[0]['qpd_id']);
                    if ($graph_data) {
                        echo json_encode($graph_data);
                    }
                } else {
                    echo 0;
                }
            } else if ($type == 'cia') {
                $qpd_id = $this->input->post('qpd_id');

                $graph_data = $this->co_level_attainment_model->BloomsLevelMarksDistribution($crs, $qpd_id);
                if ($graph_data) {
                    echo json_encode($graph_data);
                } else {
                    echo 0;
                }
            }
            /* $qp_rolled_out = $this->co_level_attainment_model->qp_rolled_out($crs);
              $graph_data = $this->co_level_attainment_model->BloomsLevelMarksDistribution($crs, $qp_rolled_out[0]['qpd_id']);

              if($graph_data) {
              echo json_encode($graph_data);
              } else {
              echo 0;
              } */
        }
    }

    /**/

    public function BloomsLevelPlannedCoverageDistribution() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            $crs = $this->input->post('course');
            $type = $this->input->post('type');
            if ($type == 'tee') {
                $qp_rolled_out = $this->co_level_attainment_model->qp_rolled_out($crs);
                if ($qp_rolled_out) {
                    $graph_data = $this->co_level_attainment_model->BloomsLevelPlannedCoverageDistribution($crs, $qp_rolled_out[0]['qpd_id']);
                    if ($graph_data) {
                        echo json_encode($graph_data);
                    }
                } else {
                    echo 0;
                }
            } else if ($type == 'cia') {
                $qpd_id = $this->input->post('qpd_id');

                $graph_data = $this->co_level_attainment_model->BloomsLevelPlannedCoverageDistribution($crs, $qpd_id);
                if ($graph_data) {
                    echo json_encode($graph_data);
                } else {
                    echo 0;
                }
            }
            /* $qp_rolled_out = $this->co_level_attainment_model->qp_rolled_out($crs);
              $graph_data = $this->co_level_attainment_model->BloomsLevelPlannedCoverageDistribution($crs, $qp_rolled_out[0]['qpd_id']);

              if($graph_data) {
              echo json_encode($graph_data);
              } else {
              echo 0;
              } */
        }
    }

    /**/

    public function CoursePOCOAttainment() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            $crs = $this->input->post('course');
            $type = $this->input->post('type');
            $qpd_id = $this->input->post('qpd_id');

            if ($qpd_id == 'all_occasion' || $qpd_id == 'undefined') {
                $qpd_id = NULL;
            }
            if ($type == 'tee') {
                $type = 'TEE';
            } else if ($type == 'cia')
                $type = 'CIA';
            else if ($type == 'cia_tee')
                $type = 'BOTH';

            $po_list = $this->co_level_attainment_model->course_po_list($crs);

            $graph_data = $this->co_level_attainment_model->CoursePOCOAttainment($po_list, $crs, $qpd_id, $type);

            if ($graph_data) {
                echo json_encode($graph_data);
            } else {
                echo 0;
            }
        }
    }

    /* Function - To create PDF of the displayed graph
     *
     *
     */

    public function export_to_pdf() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            $course_outcome_attainment_graph_data = $this->input->post('course_outcome_attainment_graph_data_hidden');

            $pdf_content = "<p>" . $course_outcome_attainment_graph_data . "</p>";

            $this->load->helper('pdf');
            pdf_create($pdf_content, 'co_level_attainment', 'L');
            return;
        }
    }

//End of function

    /*
     * Function is to fetch qpd_id of all terms both CIA & TEE
     * @param - ------.
     * returns the list of qpd_id.
     */

    public function select_cia_tee_all_terms() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            $crclm_id = $this->input->post('crclm_id');
            $term_id = $this->input->post('term_id');
            if ($term_id == 'select_all_term') {
                $crs_id = '';
            } // To set crs_id if all terms of a curriculum is selected
            else {
                $crs_id = $this->input->post('crs_id');
            } // To set crs_id if all course of a term is selected
            $tee_qpd = $this->co_level_attainment_model->select_qpd_all_terms($crclm_id, $term_id); //To get qpd of selected term
            $occasion_data = $this->co_level_attainment_model->occasion_fill($crclm_id, $term_id, $crs_id); //To get qpd of CIA
            $i = 0;
            $qpd_list = array();
            if ($tee_qpd) {
                foreach ($tee_qpd as $tee_data) {
                    $qpd_list[$i++] = $tee_data['qpd_id'];
                }
            }
            if ($occasion_data) {
                foreach ($occasion_data as $cia_data) {
                    $qpd_list[$i++] = $cia_data['qpd_id'];
                }
            }
            $qpd_list = implode(",", $qpd_list);
            if ($qpd_list) {
                echo $qpd_list;
            } else {
                echo 0;
            }
        }
    }

    //Added by Shivaraj B
    //code starts here
    function get_tire_ii_clo_attainment() {
        $course = $this->input->post('course');
        $qpd_id = $this->input->post('qpd_id');
        if ($this->input->post('type') == "cia") {
            $type = 3;
        } else if ($this->input->post('type') == "tee") {
            $type = 5;
        } else if ($this->input->post('type') == "cia_tee") {
            $type = 0;
        }
        $col_data = $this->co_level_attainment_model->get_clo_attainment($course, $qpd_id, $type);
        echo json_encode($col_data);
    }

    function display_course_level_attainemnt() {
        $course_id = $this->input->post('crs_id');
        $cla_data = $this->co_level_attainment_model->get_crs_level_attainment($course_id);
        echo json_encode($cla_data);
    }

    function finalize_clo_attainment() {
        $course_id = $this->input->post('crs_id');
        $term_id = $this->input->post('term_id');
        $crclm_id = $this->input->post('crclm_id');
        $is_added = $this->co_level_attainment_model->finalize_clo_attainment($crclm_id, $term_id, $course_id);
        if ($is_added)
            echo "success";
        else
            echo "failed";
    }

    function get_co_questions() {
        $this->load->helper('text');
        $clo_id = $this->input->post('clo_id');
        $type = $this->input->post('type_data');
        $qpd_id = $this->input->post('qpd_id');
        $questions = $this->co_level_attainment_model->get_co_mapped_questions($clo_id, $type, $qpd_id);
        if (!empty($questions)) {
            $occa_table = '<h4>' . $questions[0]['clo_code'] . ": " . $questions[0]['clo_statement'] . '</h4>';
        }
        if ($questions) {
            $occa_table .= '<table id="po_table" class="table table-bordered dataTable" aria-describedby="example_info">';
            $occa_table .= '<thead>';
            $occa_table .= '<tr>';
            $occa_table .= '<th><center>Assessment</center></th>';
            $occa_table .= '<th><center>Q No.</center></th>';
            $occa_table .= '<th><center>Question Content</center></th>';
            $occa_table .= '<th><center>Marks</center></th>';
            $occa_table .= '</tr>';
            $occa_table .= '</thead>';
            $occa_table .= '<tbody>';
            $temp = 2;
            $temp_name = '';
            foreach ($questions as $occa) {
                $occa_table .= '<tr id="' . $temp . '">';
                if ($type == 2 || $type == "cia_tee") {
                    if (strlen($occa['ao_description']) > 0) {
                        $occa_table .= '<td>' . $this->lang->line('entity_cie') . '- ' . $occa['ao_description'] . '</td>';
                    } else {
                        $occa_table .= '<td>' . $occa['mt_details_name'] . '</td>';
                    }
                } else {
                    $occa_table .= '<td>' . $occa['mt_details_name'] . '</td>';
                }
                $occa_table .= '<td>' . str_replace("_", " ", $occa['qp_subq_code']) . '</td>';
                // $occa_table .= '<td title="'.$occa['qp_content'].'"><a class="cursor_pointer">View Question</a></td>';
                $occa_table .= '<td title="' . $occa['qp_content'] . '"><a class="cursor_pointer">' . character_limiter($occa['qp_content'], 40) . '</a></td>';
                $occa_table .= '<td style="text-align:right;">' . $occa['qp_subq_marks'] . '</td>';
                $occa_table .= '</tr>';
                $temp + 2;
            }
            $occa_table .= '</tbody>';
            $occa_table .= '</table>';
            echo $occa_table;
        } else {
            echo 0;
        }
    }

    function get_drilldown_for_co() {
        $clo_id = $this->input->post('clo_id');
        $type_data = $this->input->post('type_data');
        $ao_id = $this->input->post('qpd_id');
        $crs_id = $this->input->post('crs_id');
        $co_drilldown_data = $this->co_level_attainment_model->get_drilldown_for_co($clo_id, $ao_id, $crs_id);
        if ($co_drilldown_data) {
            echo json_encode($co_drilldown_data);
        } else {
            echo 0;
        }
    }
    
    function get_finalized_co_attainment_data(){
        $crclm_id = $this->input->post('crclm_id');
        $term_id = $this->input->post('term_id');
        $crs_id = $this->input->post('crs_id');
        $attainment_data = $this->co_level_attainment_model->get_finalized_co_attainment($crclm_id,$term_id,$crs_id);
        echo json_encode($attainment_data);
    }

}

//end of class
/* End of file co_level_attainment.php */
/* Location: ./application/controllers/tier_ii/co_level_attainment.php */