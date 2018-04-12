<?php

/**
 * Description          :	Controller Logic for Course Module(List, Add, Edit & Delete).
 * Created		:	09-04-2013. 
 * Modification History:
 * Date				Modified By				Description
 * 10-09-2013		Abhinay B.Angadi        Added file headers, function headers, indentations & comments.
 * 11-09-2013		Abhinay B.Angadi		Variable naming, Function naming & Code cleaning.
 * 11-04-2014		Jevi V G     	        Added help function. 
 * 02-12-2015		Bhagyalaxmi 			Added total weightage of cia add tee
 * 04-01-2016		Shayista Mulla 			Added loading image and cookies.
 */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Course extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('curriculum/course/course_model');
        $this->load->model('curriculum/course/addcourse_model');
    }

    // End of function __construct.

    /* Function is used to check the user logged_in & his user group & to load course list view.
     * @param-
     * @retuns - the list view of all courses.
     */

    public function index($curriculum_id = '0') {
        //permission_start 
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Program Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        }
        //permission_end 
        else {
            $crclm_list = $this->course_model->crclm_fill();
            $data['curriculum_id'] = $curriculum_id;
            $data['curriculum_data'] = $crclm_list['res2'];
            $data['title'] = 'Course List Page';
            $this->load->view('curriculum/course/list_course_vw', $data);
        }
    }

// End of function index.

    /* Function is used to load static list view of course.
     * @param-
     * @retuns - the static (read only) list view of all course details.
     */

    public function static_index() {
        //permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        }
        //permission_end 
        else {
            $crclm_list = $this->course_model->crclm_fill();
            $data['curriculum_data'] = $crclm_list['res2'];
            $data['title'] = 'Course List Page';
            $this->load->view('curriculum/course/static_list_course_vw', $data);
        }
    }

// End of function static_index.

    /* Function is used to load the add view of course.
     * @param-
     * @returns - add view of course.
     */

    public function add_course() {
        //permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Program Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        }
        //permission_end 
        else {
            $data['crs_id'] = array(
                'name' => 'crs_id',
                'id' => 'crs_id',
                'class' => 'required ',
                'type' => 'hidden',
                'value' => '',
                'size' => '1'
            );
            $data['crclm_id'] = array(
                'name' => 'crclm_id',
                'id' => 'crclm_id',
                'class' => 'required input-medium',
                'type' => 'text',
                'placeholder' => 'Select Curriculum',
                'value' => '',
                'size' => '20',
                'onchange' => 'select_term();',
                'required' => ''
            );
            $data['crclm_term_id'] = array(
                'name' => 'crclm_term_id',
                'id' => 'crclm_term_id',
                'class' => 'required input-medium',
                'type' => 'text',
                'value' => '',
                'placeholder' => 'Select Term',
                'size' => '20',
                'readonly' => ''
            );
            $data['crs_type_id'] = array(
                'name' => 'crs_type_id',
                'id' => 'crs_type_id',
                'class' => 'required input-medium',
                'type' => 'text',
                'value' => '',
                'placeholder' => 'Select Course Type',
                'size' => '20',
                'required' => ''
            );
            $data['crs_domain_id'] = array(
                'name' => 'crs_domain_id',
                'id' => 'crs_domain_id',
                'class' => 'required input-medium',
                'type' => 'text',
                'value' => '',
                'placeholder' => 'Select Course Domain',
                'size' => '20',
                'required' => ''
            );
            $data['crs_code'] = array(
                'name' => 'crs_code',
                'id' => 'crs_code',
                'class' => 'input-medium noSpecialChars2',
                'type' => 'text',
                'placeholder' => 'Enter Course Code',
                'value' => '',
                'size' => '20',
                'required' => ''
            );
            $data['crs_mode'] = array(
                'name' => 'crs_mode',
                'id' => 'crs_mode',
                'class' => 'required ',
                'type' => 'hidden',
                'value' => '',
                'size' => '20'
            );
            $data['crs_title'] = array(
                'name' => 'crs_title',
                'id' => 'crs_title',
                'class' => 'input-medium',
                'type' => 'text',
                'placeholder' => 'Enter Course Title',
                'value' => '',
                'size' => '20',
                'required' => ''
            );
            $data['crs_acronym'] = array(
                'name' => 'crs_acronym',
                'id' => 'crs_acronym',
                'class' => 'required input-medium',
                'type' => 'text',
                'placeholder' => 'Enter Acronym',
                'value' => '',
                'size' => '20',
                'required' => ''
            );
            $data['co_crs_owner'] = array(
                'name' => 'co_crs_owner',
                'id' => 'co_crs_owner',
                'class' => '',
                'type' => 'textarea',
                'placeholder' => 'Enter Co-Course Owner Name(s)'
            );
            $data['clo_owner_id'] = array(
                'name' => 'clo_owner_id',
                'id' => 'clo_owner_id',
                'class' => 'required ',
                'type' => 'text',
                'placeholder' => 'Select User',
                'size' => '20',
                'value' => '',
                'required' => ''
            );
            $data['dept_id'] = array(
                'name' => 'dept_id',
                'id' => 'dept_id',
                'class' => 'required input-medium',
                'type' => 'text',
                'placeholder' => 'Select Reviewer Department ',
                'size' => '20',
                'value' => '',
                'required' => ''
            );
            $data['course_designer'] = array(
                'name' => 'course_designer',
                'id' => 'course_designer',
                'class' => 'required ',
                'type' => 'text',
                'placeholder' => 'Select User ',
                'size' => '20',
                'value' => '',
                'required' => ''
            );
            $data['course_reviewer'] = array(
                'name' => 'course_reviewer',
                'id' => 'course_reviewer',
                'class' => 'required span8',
                'type' => 'text',
                'placeholder' => 'Select User ',
                'size' => '20',
                'value' => '',
                'required' => ''
            );
            $data['last_date'] = array(
                'name' => 'last_date',
                'id' => 'last_date',
                'class' => 'required input-medium rightJustified ',
                'type' => 'text',
                'placeholder' => 'Enter Date ',
                'size' => '20',
                'value' => '',
                'required' => ''
            );
            $data['lect_credits'] = array(
                'name' => 'lect_credits',
                'id' => 'lect_credits',
                'class' => 'required onlyDigit rightJustified span2',
                'type' => 'text',
                'placeholder' => '',
                'value' => '',
                'size' => '1'
            );
            $data['tutorial_credits'] = array(
                'name' => 'tutorial_credits',
                'id' => 'tutorial_credits',
                'class' => 'required span2 onlyDigit rightJustified',
                'type' => 'text',
                'value' => '',
                'placeholder' => '',
                'size' => '1',
                'required' => ''
            );
            $data['practical_credits'] = array(
                'name' => 'practical_credits',
                'id' => 'practical_credits',
                'class' => 'required onlyDigit span2   rightJustified ',
                'type' => 'text',
                'value' => '',
                'placeholder' => '',
                'size' => '1',
                'required' => ''
            );
            $data['self_study_credits'] = array(
                'name' => 'self_study_credits',
                'id' => 'self_study_credits',
                'class' => 'required span2 onlyDigit rightJustified',
                'type' => 'text',
                'value' => '',
                'placeholder' => '',
                'size' => '1',
                'required' => ''
            );
            $data['total_credits'] = array(
                'name' => 'total_credits',
                'id' => 'total_credits',
                'class' => 'span2 onlyDigit rightJustified',
                'type' => 'text',
                'placeholder' => '',
                'size' => '1',
                'readonly' => ''
            );
            $data['contact_hours'] = array(
                'name' => 'contact_hours',
                'id' => 'contact_hours',
                'class' => 'required span2 onlyDigit rightJustified',
                'type' => 'text',
                'placeholder' => '',
                'value' => '',
                'size' => '1',
                'required' => ''
            );
            $data['cie_marks'] = array(
                'name' => 'cie_marks',
                'id' => 'cie_marks',
                'class' => 'required span2 onlyDigit rightJustified',
                'type' => 'text',
                'value' => '',
                'placeholder' => '',
                'size' => '1',
                'required' => ''
            );
            $data['mid_term_marks'] = array(
                'name' => 'mid_term_marks',
                'id' => 'mid_term_marks',
                'class' => 'required span2 onlyDigit rightJustified',
                'type' => 'text',
                'value' => '',
                'placeholder' => '',
                'size' => '1',
                'required' => ''
            );
            $data['see_marks'] = array(
                'name' => 'see_marks',
                'id' => 'see_marks',
                'class' => 'required span2 onlyDigit rightJustified',
                'type' => 'text',
                'value' => '',
                'placeholder' => '',
                'size' => '1',
                'required' => ''
            );
            $data['ss_marks'] = array(
                'name' => 'ss_marks',
                'id' => 'ss_marks',
                'class' => 'required span2 onlyDigit rightJustified',
                'type' => 'text',
                'value' => '',
                'placeholder' => '',
                'size' => '1',
                'required' => ''
            );
            // Added by bhagya S S
            $data['total_cia_weightage'] = array(
                'name' => 'total_cia_weightage',
                'id' => 'total_cia_weightage',
                'class' => 'span2 onlyDigit rightJustified total_wt allownumericwithdecimal',
                'value' => '',
                'placeholder' => '',
                'size' => '1'
            );           
			$data['total_mte_weightage'] = array(
                'name' => 'total_mte_weightage',
                'id' => 'total_mte_weightage',
                'class' => 'span2 onlyDigit rightJustified total_wt',
                'value' => '',
                'placeholder' => '',
                'size' => '1'
            );
            $data['total_tee_weightage'] = array(
                'name' => 'total_tee_weightage',
                'id' => 'total_tee_weightage',
                'class' => 'span2 onlyDigit rightJustified total_wt',
                'value' => '',
                'placeholder' => '',
                'size' => '1'
            );
            $data['total_weightage'] = array(
                'name' => 'total_weightage',
                'id' => 'total_weightage',
                'class' => 'span2 onlyDigit rightJustified  percentage  credits_validation',
                'value' => '',
                'placeholder' => '',
                'size' => '5',
                'readonly' => ''
            );
            $data['total_marks'] = array(
                'name' => 'total_marks',
                'id' => 'total_marks',
                'class' => 'span2 onlyDigit rightJustified',
                'type' => 'text',
                'value' => '',
                'placeholder' => '',
                'size' => '1'
            );
            $data['see_duration'] = array(
                'name' => 'see_duration',
                'id' => 'see_duration',
                'class' => 'required span2 onlyDigit rightJustified',
                'type' => 'text',
                'value' => '',
                'placeholder' => '',
                'size' => '1',
                'required' => ''
            );

            $data['bloom_domain'] = $this->addcourse_model->get_all_bloom_domain();
            $data['curriculumlist'] = $this->addcourse_model->dropdown_curriculum_name();
            $data['departmentlist'] = $this->course_model->dropdown_department();
            $data['userlist'] = $this->course_model->dropdown_userlist();
            $data['courselist'] = $this->addcourse_model->dropdown_course_type_name();   
			$data['mte_flag'] = $this->addcourse_model->fetch_organisation_data();
            $dept_id = $this->ion_auth->user()->row()->user_dept_id;
            $data['course_domain_list'] = $this->addcourse_model->dropdown_course_domain_name($dept_id); 
            $data['termlist'] = $this->addcourse_model->dropdown_term_name();
            $data['title'] = 'Course Add Page';
            $this->load->view('curriculum/course/add_course_vw', $data);
        }
    }

    public function fetch_clo_bl_flag() {
        $crclm_id = $this->input->post('crclm_id');
        $clo_bl_flag = $this->addcourse_model->fetch_clo_bl_flag_add($crclm_id);
        echo ($clo_bl_flag[0]['clo_bl_flag']);
    }

    public function fetch_clo_bl_flag_edit($crclm_id, $crs_id) {
        // $crclm_id = $this->input->post('crclm_id');
        $clo_bl_flag = $this->addcourse_model->fetch_clo_bl_flag($crclm_id, $crs_id);
        return ($clo_bl_flag[0]['clo_bl_flag']);
    }

// End of function add_course.

    /* Function is used to search a course from course table.
     * @param - 
     * @returns- a string value.
     */

    public function course_title_search() {
        $crclm_id = $this->input->post('crclm_id');
        $crs_code = $this->input->post('crs_code');
        $crs_title = $this->input->post('crs_title');
        $results = $this->addcourse_model->course_title_search($crclm_id, $crs_title, $crs_code);
        if ($results == 0) {
            echo 'valid';
        } else {
            echo 'invalid';
        }
    }

// End of function course_title_search.

    /* Function is used to search a course from course table.
     * @param - 
     * @returns- a string value.
     */

    public function course_title_search_edit() {
        $crs_id = $this->input->post('crs_id');
        $crclm_id = $this->input->post('crclm_id');
        $crs_code = $this->input->post('crs_code');
        $crs_title = $this->input->post('crs_title');
        $results = $this->course_model->course_title_search_edit($crclm_id, $crs_title, $crs_code, $crs_id);
        if ($results == '0') {
            echo 'valid';
        } else {
            echo 'invalid';
        }
    }

    // End of function course_title_search_edit.

    /* Function is used to fetch a curriculum id, term id & course id from course table.
     * @param - curriculum id.
     * @returns- an object.
     */

    public function course_details_by_crclm_id($crclm_id = NULL) {
        $course_details = $this->addcourse_model->course_details_by_crclm_id($crclm_id);
        header('Content-Type: application/x-json; charset=utf-8');
        echo(json_encode($course_details));
    }

    // End of function course_details_by_crclm_id.

    /* Function is used to fetch a term id & term name from crclm_terms table.
     * @param - curriculum id.
     * @returns- an object.
     */

    public function term_details_by_crclm_id($crclm_id = NULL) {
        $data['termlist'] = $this->addcourse_model->dropdown_term_name();
        header('Content-Type: application/x-json; charset=utf-8');
        echo(json_encode($data['termlist']));
    }

    // End of function term_details_by_crclm_id.

    /* Function is used to add a new course details.
     * @param-
     * @returns - updated list view of course.
     */

    public function insert_course() {
        //permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Program Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        }
        //permission_end 
        else {

            $pre_courses_exploded = explode(",", $_POST['hidden-tags']);
            $pre_courses = implode('<>', $pre_courses_exploded);
            //course table data
            $crclm_id = $this->input->post('crclm_id');
            $crclm_term_id = $this->input->post('crclm_term_id');
            $crs_type_id = $this->input->post('crs_type_id');
            $crs_code = $this->input->post('crs_code');
            $crs_mode = $this->input->post('crs_mode');
            $crs_title = $this->input->post('crs_title');
            $crs_acronym = $this->input->post('crs_acronym');
            $co_crs_owner = $this->input->post('co_crs_owner');
            $crs_domain_id = $this->input->post('crs_domain_id');
            $lect_credits = $this->input->post('lect_credits');
            $tutorial_credits = $this->input->post('tutorial_credits');
            $practical_credits = $this->input->post('practical_credits');
            $self_study_credits = 0;
            $total_credits = $this->input->post('total_credits');
            $contact_hours = $this->input->post('contact_hours');
            $cie_marks = $this->input->post('cie_marks');
            $see_marks = $this->input->post('see_marks');
            $ss_marks = 0;
            $total_marks = $this->input->post('total_marks');
            $see_duration = $this->input->post('see_duration');
            //course_clo_owner
            $course_designer = $this->input->post('course_designer');
            //course_clo_reviewer
            $course_reviewer = $this->input->post('course_reviewer');
            $review_dept = $this->input->post('review_dept');
            $last_date = $this->input->post('last_date');
            //predecessor courses array
            $pre_courses;
        }
    }

    //End of function insert_course


    /* Function is used to generate table of course details.
     * @param- 
     * @returns - an object.
     */

    public function display_course_details() {
        $crclm_id = $this->input->post('crclm_id');
        $crclm_term_id = $this->input->post('crclm_term_id');
        $course_data = $this->addcourse_model->course_details($crclm_id);
        $blank = ' ';
        $output = ' ';
        $term_id = ' ';
        $i = 1;
        $total = 0;
        $data['course_details'] = $course_data;
        $count = sizeof($data['course_details']);
        for ($k = 0; $k < $count; $k++) {
            if ($term_id != $data['course_details'][$k]['crclm_term_id']) {
                $output.= '<tr style="font-size:12px;">';
                $total = $data['course_details'][$k]['total_theory_courses'] + $data['course_details'][$k]['total_practical_courses'];
                $output.= '<td colspan="2" style="color : blue"><b>' . $data['course_details'][$k]['term_name'] . '</b></td>' .
                        '<td colspan="2" style="color : blue"><b>Term Total Courses:-  ' . $total . ' ( ' . $data['course_details'][$k]['total_theory_courses'] . '-(Theory) + ' . $data['course_details'][$k]['total_practical_courses'] . '-(Practical)' . ' )</b></td>' .
                        '<td colspan="5" style="color : blue"><b>Term Total Credits:-  ' . $data['course_details'][$k]['term_credits'] . '</b></td>' .
                        '<td colspan="2" style="color : blue"><b>Term Duration:-  ' . $data['course_details'][$k]['term_duration'] . '(weeks)</b></td>';
                $output.= '</tr>';
                $term_id = $data['course_details'][$k]['crclm_term_id'];
                $output.= '<tr style="font-size:12px;">';
                $output.= '<td><b>Sl No.' . $blank . '</b></td>' .
                        '<td colspan="1"><b>Code' . $blank . '</b></td>' .
                        '<td colspan="1"><b>Course' . $blank . '</b></td>' .
                        '<td colspan="1"><b>Core / Elective ' . $blank . '</b></td>' .
                        '<td colspan="1"><b>Acronym' . $blank . '</b></td>' .
                        '<td colspan="1"><b>L' . $blank . '</b></td>' .
                        '<td colspan="1"><b>T' . $blank . '</b></td>' .
                        '<td colspan="1"><b>P' . $blank . '</b></td>' .
                        '<td colspan="1"><b>Credits' . $blank . '</b></td>' .
                        '<td colspan="1"><b>Course Designer' . $blank . '</b></td>' .
                        '<td colspan="1"><b>Mode' . $blank . '</b></td>';
                $output.= '</tr>';
            }
            if ($data['course_details'][$k]['crs_mode'] == 1) {
                $msg = 'Practical';
            } else {
                $msg = 'Theory';
            }
            $output.= '<tr style="font-size:12px;">';
            $output.= '<td>' . $i . '</td>' .
                    '<td>' . $data['course_details'][$k]['crs_code'] . '</td>' .
                    '<td>' . $data['course_details'][$k]['crs_title'] . '</td>' .
                    '<td>' . $data['course_details'][$k]['crs_type_name'] . '</td>' .
                    '<td>' . $data['course_details'][$k]['crs_acronym'] . '</td>' .
                    '<td>' . $data['course_details'][$k]['lect_credits'] . '</td>' .
                    '<td>' . $data['course_details'][$k]['tutorial_credits'] . '</td>' .
                    '<td>' . $data['course_details'][$k]['practical_credits'] . '</td>' .
                    '<td>' . $data['course_details'][$k]['total_credits'] . '</td>' .
                    '<td>' . $data['course_details'][$k]['title'] . ' ' . ucfirst($data['course_details'][$k]['first_name']) . ' ' . ucfirst($data['course_details'][$k]['last_name']) . '</td>' .
                    '<td>' . $msg . '</td>';
            $i++;
            $total = 0;
            $output.= '</tr>';
        }
        echo $output;
    }

    //End of function display_course_details.

    /* Function is used to generate table of PEO details.
     * @param- 
     * @returns - an object.
     */

    public function display_peo_details() {
        $crclm_id = $this->input->post('crclm_id');
        $peo_data = $this->addcourse_model->display_peo_details($crclm_id);
        $output = ' ';
        $i = 1;
        $data['peo_details'] = $peo_data;
        $count = sizeof($data['peo_details']);
        for ($k = 0; $k < $count; $k++) {
            $output.= '<tr style="font-size:12px;">';
            $output.= '<td>' . $i . '</td>';
            $output.= '<td>' . $data['peo_details'][$k]['peo_statement'] . '</td>';
            $output.= '</tr>';
            $i++;
        }
        echo $output;
    }

    //End of function display_peo_details.

    /* Function is used to generate table of PO details.
     * @param- 
     * @returns - an object.
     */

    public function display_po_details() {
        $crclm_id = $this->input->post('crclm_id');
        $po_data = $this->addcourse_model->display_po_details($crclm_id);
        $i = 1;
        $output = ' ';
        $data['po_details'] = $po_data;
        $count = sizeof($data['po_details']);
        for ($k = 0; $k < $count; $k++) {
            $output.= '<tr style="font-size:12px;">';
            $output.= '<td>' . $i . '</td>';
            $output.= '<td>' . $data['po_details'][$k]['po_statement'] . '</td>';
            $output.= '</tr>';
            $i++;
        }
        echo $output;
    }

    //End of function display_po_details.

    /* Function is used to generate table of Curriculum details.
     * @param- 
     * @returns - an object.
     */

    public function display_crclm_details() {
        $crclm_id = $this->input->post('crclm_id');
        $crclm_data = $this->addcourse_model->display_crclm_details($crclm_id);
        $output = ' ';
        $data['crclm_details'] = $crclm_data;
        $count = sizeof($data['crclm_details']);
        for ($k = 0; $k < $count; $k++) {
            $output.= '<tr style="font-size:12px;">';
            $output.= '<td>' . $data['crclm_details'][$k]['crclm_name'] . '</td>';
            $output.= '<td>' . $data['crclm_details'][$k]['crclm_description'] . '</td>';
            $output.= '<td>' . $data['crclm_details'][$k]['total_credits'] . '</td>';
            $output.= '<td>' . $data['crclm_details'][$k]['total_terms'] . '</td>';
            $output.= '<td>' . $data['crclm_details'][$k]['start_year'] . '</td>';
            $output.= '<td>' . $data['crclm_details'][$k]['end_year'] . '</td>';
            $output.= '<td>' . $data['crclm_details'][$k]['title'] . ' ' . ucfirst($data['crclm_details'][$k]['first_name']) . ' ' . ucfirst($data['crclm_details'][$k]['last_name']) . '</td>';
            $output.= '</tr>';
        }
        echo $output;
    }

    //End of function display_crclm_details.

    /* Function is used to fetch course names from course table.
     * @param- live data (live search data)
     * @returns - an object.
     */

    public function course_name() {
        $data['courselist'] = $this->addcourse_model->autoCompleteDetails();
        header('Content-Type: application/x-json; charset=utf-8');
        echo(json_encode($data['courselist']));
    }

    //End of function course_name.

    /* Function is used to fetch term names from crclm_terms table.
     * @param- 
     * @returns - an object.
     */

    public function select_termlist() {
        $crclm_id = $this->input->post('crclm_id');
        $term_data = $this->course_model->term_fill($crclm_id);
        $term_data = $term_data['res2'];
        $i = 0;
        $list[$i] = '<option value="">Select Term</option>';
        $i++;
        foreach ($term_data as $data) {
            $list[$i] = "<option value = " . $data['crclm_term_id'] . ">" . $data['term_name'] . "</option>";
            $i++;
        }
        $list = implode(" ", $list);
        echo $list;
    }

    //End of function select_termlist.

    /* Function is used to delete a course from course table.
     * @param- curriculum id
     * @returns - a boolean value.
     */

    public function course_delete($crs_id) {
        //permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Program Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        }
        //permission_end 
        else {
            $result = $this->course_model->course_delete($crs_id);
            redirect('curriculum/course', 'refresh');
            return true;
        }
    }

    //End of function course_delete.

    /* Function is used to initiate CLO creation process.
     * @param- course id
     * @returns - a boolean value.
     */

    function publish_course($crs_id) {
	
        //permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Program Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        }
        //permission_end 
        else {
            $data = $this->course_model->publish_course_curriculum($crs_id);
            $crclm_term_id = $data['0']['crclm_term_id'];
            $crclm_id = $data['0']['crclm_id'];
            $entity_id = '4';
            $particular_id = $crs_id;
            $sender_id = $this->ion_auth->user()->row()->id;
            $data = $this->course_model->publish_course_receiver($crs_id, $crclm_term_id);
            $reviewer_data = $this->course_model->publish_course_reviewer($crs_id, $crclm_term_id);
            $term_name = $data['term'][0]['term_name'];
            $course_name = $data['course'][0]['crs_title'];
            $description = 'Term(Semester):- ' . $term_name;
            $reviewer_description = $description . ', Course:- ' . $course_name . ' is created, you have been chosen as a Course Reviewer.';
            $description = $description . ', Course:- ' . $course_name . ' is created, proceed to create COs.';
            $receiver_id = $data['0']['clo_owner_id'];   // Course Owner User-id
            $reviewer_id = $reviewer_data['0']['validator_id']; // Course Reviewer User-id
            $reviewer_url = base_url('');
            $url = base_url('curriculum/clo/clo_add/' . $crclm_id . '/' . $crclm_term_id . '/' . $crs_id);
            $reviewer_description = $reviewer_description;
            $description = $description;
            $state = '1';
            $status = '1';

            $results = $this->course_model->publish_course($crclm_id, $crclm_term_id , $crs_id , $entity_id, $particular_id, $sender_id, $receiver_id, $url, $description, $state, $status, $crclm_term_id, $reviewer_description, $reviewer_id);
            $term_name = $results['term'][0]['term_name'];
            $course_name = $results['course'][0]['crs_title'];
            $addition_data = array();

            //mail items
            $receiver_id = $results['receiver_id'];
            $cc = '';
            $links = $results['url'];
            $entity_id = $results['entity_id'];
            $state = $results['state'];
            $crclm_id = $results['crclm_id'];
            $addition_data['term'] = $term_name;
            $reviewer_state = 7;
            $addition_data['course'] = $course_name;
            $status_update = $this->course_model->publish_course_update_status($crs_id);
            $this->ion_auth->send_email($receiver_id, $cc, $links, $entity_id, $state, $crclm_id, $addition_data);
            $this->ion_auth->send_email($reviewer_id, $cc, $reviewer_url, $entity_id, $reviewer_state, $crclm_id, $addition_data);

            redirect('curriculum/course', 'refresh');
            return true;
        }
    }

    //End of function publish_course.

    /* Function is used to generate List of Course Grid (Table).
     * @param- 
     * @returns - an object.
     */

    public function show_course() {
        $crclm_id = $this->input->post('crclm_id');
        $term_id = $this->input->post('crclm_term_id');
        $course_data = $this->course_model->course_list($crclm_id, $term_id);
        $i = 1;
        $msg = '';
        $del = '';
        $publish = '';
        $data = $course_data['crs_list_data'];
        $crs_list = array();
        
        foreach ($data as $crs_data) {
            if ($crs_data['crs_mode'] == 1) {
                $msg = 'Practical';
            } else if ($crs_data['crs_mode'] == 0) {
                $msg = 'Theory';
            } else {
                $msg = 'Theory with Lab';
            }
			
			$check_mandatory_data_set_or_not  = $this->course_model->check_mandatory_data_set_or_not($crs_data['crs_id']);
			if($crs_data['clo_bl_flag'] == 0){
				if($check_mandatory_data_set_or_not[0]['flag'] == 1){
				$bloom_option_mandatory = '<a role = "button"  data-clo_bl_flag = "'. $crs_data['clo_bl_flag'].'" data-toggle = "modal" title = "Course - COs to Bloom\'s Level Mapping Status" href = "" data-confirmation_msg = "Are you sure that you want to make Course Outcome Bloom\'s Level (s) mandatory ? " class = " bloom_option_mandatory btn btn-small btn-warning myTagRemover get_crs_id" id = "' . $crs_data['crs_id'] . '" ><i></i>Optional</a>';
				}else{
					$bloom_option_mandatory = '<a role = "button"   href = "#myModal_mandatory_error" data-confirmation_msg = "" class = " bloom_option_mandatory_error btn btn-small btn-warning myTagRemover get_crs_id" id = "' . $crs_data['crs_id'] . '" ><i></i>Optional</a>';
				}
			
			}else{
			$bloom_option_mandatory = '<a role = "button"    data-confirmation_msg = "Are you sure that you want to make Course Outcome Bloom\'s Level (s) Optional ? " data-toggle = "modal" title = "Course - COs to Bloom\'s Level Mapping Status" href = "" class = " bloom_option_mandatory  btn btn-small btn-success myTagRemover get_crs_id" id = "' . $crs_data['crs_id'] . '" data-clo_bl_flag = "'. $crs_data['clo_bl_flag'].'" ><i></i>Mandatory</a>';;
			}
			
			$weightage_flag = 0;
			if($crs_data['cia_flag'] == 1 || $crs_data['mte_flag'] == 1 ||  $crs_data['tee_flag'] == 1){ $weightage_flag = 1;}
			
            if ($crs_data['status'] == 0) {
                $del = '<center><a role = "button"  data-toggle = "modal" title = "Remove" href = "#myModal" class = "myTagRemover icon-remove get_crs_id" id = "' . $crs_data['crs_id'] . '" ></a></center>';
                if ($crs_data['crs_domain_id'] == NULL) {
                    $publish = '<a role = "button"  data-toggle = "modal" title = "Assign "' . $this->lang->line('course_owner_full') . '" & Course Reviewer" href = "#myModal4" class = "btn btn-small btn-danger myTagRemover get_crs_id" id = "' . $crs_data['crs_id'] . '" ><i></i>Incomplete</a>';
                } else {
                    $publish = '<a role = "button"  data-toggle = "modal" title = "CO creation Pending" href = "" class = " check_weightage btn btn-small btn-warning myTagRemover get_crs_id" id = "' . $crs_data['crs_id'] . '" data-weightage = "'. $weightage_flag .'" ><i></i>Pending</a>';
                }
            } else {
                $del = '<center><a role = "button"  data-toggle = "modal" title = "Remove" href = "#myModal" class = "myTagRemover icon-remove get_crs_id" id = "' . $crs_data['crs_id'] . '" ></a></center>';
                $publish = '<a role = "button"  data-toggle = "modal" title = "CO creation Initiated" href = "#" class = "btn btn-small btn-success myTagRemover get_crs_id" id = "' . $crs_data['crs_id'] . '" ><i></i>Initiated</a>';
            }            
            if ($crs_data['clo_bl_flag'] == "0") {
                $color = "#000000";
                $title = "";
                $title = ' Blooms Level(s) not Mandatory .';
            } else {
              //  $color = "#bd1111";
				$color = "#000000";
                $title = ' Blooms Level(s) Mandatory .';
            }
            $crs_title = "<a title ='" . $title . "' style='text-decoration:none;'><span style=\"color: $color\"> " . $crs_data['crs_title'] . "</span></a>";
            $crs_code =  "<a title ='" . $title . "' style='text-decoration:none;'><span style=\"color: $color\"> " . $crs_data['crs_code'] .  "</span></a>";
            $reviewer_id = $crs_data['validator_id'];
            $user = $this->ion_auth->user($reviewer_id)->row();   
			$total_credit_details = "L : " . $crs_data['lect_credits'] . "\r\n". "T : ".$crs_data['tutorial_credits'] . "\r\n" ."P : " .$crs_data['practical_credits'];
			$total_credits = "<a style='text-decoration:none;' class = 'cursor_pointer' title='". $total_credit_details ."' >" . $crs_data['total_credits']. "</a>";
            $crs_list[] = array(
                'sl_no' => '<style="text-align:right;">'.$i,
                'crs_id' => $crs_data['crs_id'],
                'crs_code' => $crs_code,
                'crs_title' => $crs_title,
                'crs_type_name' => $crs_data['crs_type_name'],
                'crs_acronym' => $crs_data['crs_acronym'],
                'lect_credits' =>  $crs_data['lect_credits'] ,
                'tutorial_credits' => $crs_data['tutorial_credits'],
                'practical_credits' => $crs_data['practical_credits'],
                'self_study_credits' => $crs_data['self_study_credits'],
                'total_credits' => $total_credits,
                'contact_hours' => $crs_data['contact_hours'],
                'cie_marks' => $crs_data['cie_marks'],
                'see_marks' => $crs_data['see_marks'],
                'total_marks' => $crs_data['total_marks'],
                'crs_mode' => $msg,
                'mng_section' => '<a class="cursor_pointer assign_course_instructor" data-crs_id="' . $crs_data['crs_id'] . '" data-crs_name="' . $crs_data['crs_title'] . '" data-crs_code="' . $crs_data['crs_code'] . '" >Assign Course Instructor(s)</a>',
                'see_duration' => $crs_data['see_duration'],
                'username' => $crs_data['title'] . ' ' . ucfirst($crs_data['first_name']) . ' ' . ucfirst($crs_data['last_name']),
                'reviewer' => $crs_data['usr_title'] . ' ' . ucfirst($crs_data['usr_first_name']) . ' ' . ucfirst($crs_data['usr_last_name']),
                'crs_id_edit' => '<center><a title = "Edit" href = "' . base_url('curriculum/course/edit_course') . '/' . $crs_data['crs_id'] . '"><i class = "myTagRemover icon-pencil"></i></a></center>',
                'crs_id_delete' => $del,       
                'publish' => $publish,
				'bloom_option_mandatory'=> $bloom_option_mandatory
            );
            $i++;
        }
        echo json_encode($crs_list);
    }
	
	public function bloom_option_mandatory(){	
		  $crs_id = $this->input->post('crs_id');
		  $clo_bl_flag = $this->input->post('clo_bl_flag');
		  $result = $this->course_model->bloom_option_mandatory($crs_id , $clo_bl_flag );
	}

//End of function show_course.

    /* Function is used to load edit view of course.
     * @param - course id
     * @returns- edit view of course.
     */

    public function edit_course($crs_id) {
        //permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Program Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        }
        //permission_end 
        else {
            $data['course_details'] = $this->course_model->course_details($crs_id);
           
            $crclm_id = $data['course_details']['0']['crclm_id'];
            $data['curriculum_details'] = $this->course_model->curriculum_details($crclm_id);
            $data['po_list'] = $this->course_model->po_list($crclm_id);
            $data['peo_list'] = $this->course_model->peo_list($crclm_id);
            $data['term_details'] = $this->course_model->term_details($crclm_id);
            $data['course_detailslist'] = $this->course_model->course_detailslist($crclm_id);
            $dept_id = $this->ion_auth->user()->row()->user_dept_id;
            $data['course_domain_list'] = $this->addcourse_model->dropdown_course_domain_name($dept_id);
            $data['curriculumlist'] = $this->course_model->dropdown_curriculumlist();
            $data['coursetypelist'] = $this->course_model->dropdown_coursetypelist($crclm_id);
            $data['departmentlist'] = $this->course_model->dropdown_department();
            $data['crs_id'] = $crs_id;
            $data['course_owner_details'] = $this->course_model->course_owner_details($crs_id);
            $reviewer_dept_id = $data['course_owner_details']['reviewer_details']['0']['dept_id'];
            $data['userlist'] = $this->course_model->reviewer_dropdown_userlist($reviewer_dept_id);
            $loggedin_user_id = $this->ion_auth->user()->row()->id;
            $data['co_userlist'] = $this->course_model->owner_dropdown_userlist($loggedin_user_id);
            //to fetch Prerequisite Courses
            $data['predessor_data'] = $this->course_model->predessor_details($crs_id);
            if ($this->form_validation->run() == false) {
                $data['crs_id'] = array(
                    'name' => 'crs_id',
                    'id' => 'crs_id',
                    'class' => 'required',
                    'type' => 'hidden',
                    'value' => $data['course_details']['0']['crs_id']
                );
                $data['crclm_id'] = array(
                    'name' => 'crclm_id',
                    'id' => 'crclm_id',
                    'class' => 'required input-medium',
                    'type' => 'text',
                    'placeholder' => 'Select Curriculum',
                    'value' => $data['course_details']['0']['crclm_id'],
                    'onchange' => 'select_term();'
                );
                $data['crclm_term_id'] = array(
                    'name' => 'crclm_term_id',
                    'id' => 'crclm_term_id',
                    'class' => 'required input-medium',
                    'type' => 'text',
                    'placeholder' => 'Select Term',
                    'value' => $data['course_details']['0']['crclm_term_id']
                );
                $data['crs_type_id'] = array(
                    'name' => 'crs_type_id',
                    'id' => 'crs_type_id',
                    'class' => 'required input-medium',
                    'type' => 'text',
                    'placeholder' => 'Select Course Type',
                    'value' => $data['course_details']['0']['crs_type_id']
                );
                $data['crs_domain_id'] = array(
                    'name' => 'crs_domain_id',
                    'id' => 'crs_domain_id',
                    'class' => 'required input-medium',
                    'type' => 'text',
                    'placeholder' => 'Select Course Type',
                    'value' => $data['course_details']['0']['crs_domain_id']
                );
                $data['crs_code'] = array(
                    'name' => 'crs_code',
                    'id' => 'crs_code',
                    'class' => 'input-medium noSpecialChars2 required',
                    'type' => 'text',
                    'placeholder' => 'Enter Course Code',
                    'value' => $data['course_details']['0']['crs_code'],
                    'required' => ''
                );
                $data['crs_mode'] = array(
                    'name' => 'crs_mode',
                    'id' => 'crs_mode',
                    'class' => 'noSpecialChars required',
                    'type' => 'hidden',
                    'value' => $data['course_details']['0']['crs_mode']
                );
                $data['crs_title'] = array(
                    'name' => 'crs_title',
                    'id' => 'crs_title',
                    'class' => 'required input-medium',
                    'type' => 'text',
                    'placeholder' => 'Enter Course Title',
                    'value' => $data['course_details']['0']['crs_title'],
                    'required' => ''
                );
                $data['crs_acronym'] = array(
                    'name' => 'crs_acronym',
                    'id' => 'crs_acronym',
                    'class' => 'noSpecialChars input-medium required',
                    'type' => 'text',
                    'placeholder' => 'Enter Acronym',
                    'value' => $data['course_details']['0']['crs_acronym'],
                    'required' => ''
                );

                $data['predecessor_course_id'] = array(
                    'name' => 'predecessor_course_id',
                    'id' => 'predecessor_course_id',
                    'class' => 'input-medium',
                    'type' => 'text',
                    'placeholder' => '',
                    'required' => ''
                );
                $data['co_crs_owner_edit'] = array(
                    'name' => 'co_crs_owner_edit',
                    'id' => 'co_crs_owner_edit',
                    'class' => 'char-counter',
                    'cols' => '20',
                    'rows' => '2',
                    'type' => 'textarea',
                    'maxlength' => "2000",
                    'placeholder' => '',
                    'value' => $data['course_details']['0']['co_crs_owner']
                );
                $data['clo_owner_id'] = array(
                    'name' => 'clo_owner_id',
                    'id' => 'clo_owner_id',
                    'class' => 'required input-medium',
                    'type' => 'text',
                    'placeholder' => 'Select User',
                    'value' => $data['course_owner_details']['owner_details']['0']['clo_owner_id']
                );
                $data['review_dept'] = array(
                    'name' => 'review_dept',
                    'id' => 'review_dept',
                    'class' => 'required input-medium',
                    'type' => 'text',
                    'placeholder' => 'Select Department ',
                    'value' => $data['course_owner_details']['reviewer_details']['0']['dept_id']
                );
                $data['validator_id'] = array(
                    'name' => 'validator_id',
                    'id' => 'validator_id',
                    'class' => 'required input-medium',
                    'type' => 'text',
                    'placeholder' => 'Select User ',
                    'value' => $data['course_owner_details']['reviewer_details']['0']['validator_id']
                );
                $data['last_date'] = array(
                    'name' => 'last_date',
                    'id' => 'last_date',
                    'class' => 'required input-medium rightJustified ',
                    'type' => 'text',
                    'placeholder' => 'Enter Date ',
                    'value' => $data['course_owner_details']['reviewer_details']['0']['last_date'],
                    'required' => ''
                );
                $data['lect_credits'] = array(
                    'name' => 'lect_credits',
                    'id' => 'lect_credits',
                    'class' => 'required onlyDigit rightJustified span2',
                    'type' => 'text',
                    'placeholder' => '',
                    'value' => $data['course_details']['0']['lect_credits']
                );
                $data['tutorial_credits'] = array(
                    'name' => 'tutorial_credits',
                    'id' => 'tutorial_credits',
                    'class' => 'required onlyDigit rightJustified span2',
                    'type' => 'text',
                    'placeholder' => '',
                    'value' => $data['course_details']['0']['tutorial_credits']
                );
                $data['total_credits'] = array(
                    'name' => 'total_credits',
                    'id' => 'total_credits',
                    'class' => 'span2 onlyDigit rightJustified',
                    'type' => 'text',
                    'placeholder' => '',
                    'readonly' => '',
                    'value' => $data['course_details']['0']['total_credits']
                );
                $data['practical_credits'] = array(
                    'name' => 'practical_credits',
                    'id' => 'practical_credits',
                    'class' => 'span2 onlyDigit rightJustified required',
                    'type' => 'text',
                    'placeholder' => '',
                    'value' => $data['course_details']['0']['practical_credits']
                );
                $data['self_study_credits'] = array(
                    'name' => 'self_study_credits',
                    'id' => 'self_study_credits',
                    'class' => 'required span2 onlyDigit rightJustified',
                    'type' => 'text',
                    'value' => $data['course_details']['0']['self_study_credits'],
                    'placeholder' => '',
                    'size' => '1',
                    'required' => ''
                );
                $data['contact_hours'] = array(
                    'name' => 'contact_hours',
                    'id' => 'contact_hours',
                    'class' => 'required span2 onlyDigit rightJustified',
                    'type' => 'text',
                    'value' => $data['course_details']['0']['contact_hours'],
                    'placeholder' => '',
                    'size' => '1',
                    'required' => ''
                );
                $data['cie_marks'] = array(
                    'name' => 'cie_marks',
                    'id' => 'cie_marks',
                    'class' => 'required span2 onlyDigit rightJustified',
                    'type' => 'text',
                    'value' => $data['course_details']['0']['cie_marks'],
                    'placeholder' => '',
                    'size' => '1',
                    'required' => ''
                );
                $data['mid_term_marks'] = array(
                    'name' => 'mid_term_marks',
                    'id' => 'mid_term_marks',
                    'class' => 'required span2 onlyDigit rightJustified',
                    'type' => 'text',
                    'value' => $data['course_details']['0']['mid_term_marks'],
                    'placeholder' => '',
                    'size' => '1',
                    'required' => ''
                );
                $data['see_marks'] = array(
                    'name' => 'see_marks',
                    'id' => 'see_marks',
                    'class' => 'required span2 onlyDigit rightJustified',
                    'type' => 'text',
                    'value' => $data['course_details']['0']['see_marks'],
                    'placeholder' => '',
                    'size' => '1',
                    'required' => ''
                );
                $data['ss_marks'] = array(
                    'name' => 'ss_marks',
                    'id' => 'ss_marks',
                    'class' => 'required span2 onlyDigit rightJustified',
                    'type' => 'text',
                    'value' => $data['course_details']['0']['ss_marks'],
                    'placeholder' => '',
                    'size' => '1',
                    'required' => ''
                );
                $data['total_marks'] = array(
                    'name' => 'total_marks',
                    'id' => 'total_marks',
                    'class' => 'span2 onlyDigit rightJustified',
                    'type' => 'text',
                    'value' => $data['course_details']['0']['total_marks'],
                    'placeholder' => '',
                    'size' => '1'
                );
                $data['see_duration'] = array(
                    'name' => 'see_duration',
                    'id' => 'see_duration',
                    'class' => 'required span2 onlyDigit rightJustified',
                    'type' => 'text',
                    'value' => $data['course_details']['0']['see_duration'],
                    'placeholder' => '',
                    'size' => '1',
                    'required' => ''
                );
                $data['curriculum'] = array(
                    'name' => 'curriculum',
                    'id' => 'curriculum',
                    'class' => 'required',
                    'type' => 'hidden',
                    'value' => $data['course_details']['0']['crclm_id']
                );
                // Added by bhagya S S
                $data['total_cia_weightage'] = array(
                    'name' => 'total_cia_weightage',
                    'id' => 'total_cia_weightage',
                    'class' => 'span2 onlyDigit rightJustified total_wt allownumericwithdecimal',
                    'value' => $data['course_details']['0']['total_cia_weightage'],
                    'placeholder' => '',
                    'size' => '1'
                );
					$data['total_mte_weightage'] = array(
                'name' => 'total_mte_weightage',
                'id' => 'total_mte_weightage',
                'class' => 'span2 onlyDigit rightJustified total_wt',
                'value' => $data['course_details']['0']['total_mte_weightage'],
                'placeholder' => ' ',
                'size' => '1'
            );
                $data['total_tee_weightage'] = array(
                    'name' => 'total_tee_weightage',
                    'id' => 'total_tee_weightage',
                    'class' => 'span2 onlyDigit rightJustified total_wt',
                    'value' => $data['course_details']['0']['total_tee_weightage'],
                    'placeholder' => '',
                    'size' => '1'
                );

                $total_wt = (float) ($data['course_details']['0']['total_cia_weightage'] + $data['course_details']['0']['total_tee_weightage']);
                $data['total_weightage'] = array(
                    'name' => 'total_weightage',
                    'id' => 'total_weightage',
                    'class' => 'span2 onlyDigit rightJustified percentage credits_validation',
                    'value' => $total_wt,
                    'placeholder' => '',
                    'size' => '5',
                    'readonly' => ''
                );
                $data['title'] = 'Course Edit Page';
                $data['bld_active'][] = $data['course_details']['0']['cognitive_domain_flag'];
                $data['bld_active'][] = $data['course_details']['0']['affective_domain_flag'];
                $data['bld_active'][] = $data['course_details']['0']['psychomotor_domain_flag'];
				
				$data['cia_flag'] =  $data['course_details']['0']['cia_flag'];
				$data['mte_flag'] =  $data['course_details']['0']['mte_flag'];
				$data['tee_flag'] =  $data['course_details']['0']['tee_flag'];
				
                $data['clo_bl_flag'] = $this->fetch_clo_bl_flag_edit($crclm_id, $crs_id);
				$data['mte_flag_org'] = $this->addcourse_model->fetch_organisation_data();
				
                $data['bloom_domain'] = $this->addcourse_model->get_all_bloom_domain();
                $this->load->view('curriculum/course/edit_course_vw', $data);
            }
        }
    }

// End of function edit_course.

    /* Function is used to update the details of a course.
     * @param -
     * @returns- updated list view of course.
     */

    public function update() {
        //permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Program Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        }
        //permission_end 
        else {
            $pre_courses_exploded = explode(",", $_POST['hidden-tags']);
            $pre_courses = implode('<>', $pre_courses_exploded);
            //course table
            $crs_id = $this->input->post('crs_id');
            $crclm_id = $this->input->post('crclm_id');
            $crclm_term_id = $this->input->post('crclm_term_id');
            $crs_type_id = $this->input->post('crs_type_id');
            $crs_code = $this->input->post('crs_code');
            $crs_mode = $this->input->post('crs_mode');
            $crs_title = $this->input->post('crs_title');
            $crs_acronym = $this->input->post('crs_acronym');
            $co_crs_owner = $this->input->post('co_crs_owner_edit');
            $crs_domain_id = $this->input->post('crs_domain_id');
            $lect_credits = $this->input->post('lect_credits');
            $tutorial_credits = $this->input->post('tutorial_credits');
            $practical_credits = $this->input->post('practical_credits');
            $self_study_credits = $this->input->post('self_study_credits');
            $total_credits = $this->input->post('total_credits');
            $contact_hours = $this->input->post('contact_hours');
            $cie_marks = $this->input->post('cie_marks');
            $see_marks = $this->input->post('see_marks');
            $ss_marks = 0;
            $total_marks = $this->input->post('total_marks');
            $see_duration = $this->input->post('see_duration');
            //course_clo_owner
            $course_designer = $this->input->post('clo_owner_id');
            //course_clo_reviewer
            $review_dept = $this->input->post('review_dept');
            $course_reviewer = $this->input->post('validator_id');
            $last_date = $this->input->post('last_date');
            //predecessor courses array
            $pre_courses;
            // delete predecessor courses array
            $del_pre_courses = $this->input->post('delete_crs_id');

            $course_added = $this->course_model
                    ->update_course(
                    //course table data
                    $crs_id, $crclm_id, $crclm_term_id, $crs_type_id, $crs_code, $crs_mode, $crs_title, $crs_acronym, $co_crs_owner, $crs_domain_id, $lect_credits, $tutorial_credits, $practical_credits, $self_study_credits, $total_credits, $contact_hours, $cie_marks, $see_marks, $ss_marks, $total_marks, $see_duration,
                    //course_clo_owner
                    $course_designer,
                    //course_clo_reviewer
                    $review_dept, $course_reviewer, $last_date,
                    //predecessor courses array
                    $pre_courses,
                    // delete predecessor courses array
                    $del_pre_courses ,
					
					$cia_check , $mte_check , $tee_check
            );
            redirect('curriculum/course', 'refresh');
        }
    }

// End of function update.

    /* Function is used to fetch term names from crclm_terms table.
     * @param- 
     * @returns - an object.
     */

    public function select_term() {
        $crclm_id = $this->input->post('crclm_id');
        $term_data = $this->course_model->term_details($crclm_id);
        $i = 0;
        $list[$i] = '<option value="">Select Term</option>';
        $i++;
        foreach ($term_data as $data) {
            $list[$i] = "<option value=" . $data['crclm_term_id'] . ">" . $data['term_name'] . "</option>";
            $i++;
        }
        $list = implode(" ", $list);
        echo $list;
    }

// End of function select_term.

    /* Function is used to fetch course type names from crclm_crs_type_map table.
     * @param- 
     * @returns - an object.
     */

    public function select_course_type() {
        $crclm_id = $this->input->post('crclm_id');
        $result = $this->course_model->fetch_course_type($crclm_id);
        $i = 0;
        $list[$i] = '<option value="">Select Course Type</option>';
        $i++;
        foreach ($result as $data) {
            $list[$i] = "<option value=" . $data['crs_type_id'] . ">" . $data['crs_type_name'] . "</option>";
            $i++;
        }
        $list = implode(" ", $list);
        echo $list;
    }

// End of function select_course_type.

    /* Function is used to fetch user names from users table.
     * @param- department id
     * @returns - an object.
     */

    public function reviewer_list() {
        $dept_id = $this->input->post('dept_id');
        $user_data = $this->course_model->dropdown_userlist2($dept_id);
        if ($user_data) {
            $i = 0;
            $list[$i] = '<option value="">Select User</option>';
            $i++;
            foreach ($user_data as $data) {
                $list[$i] = "<option value=" . $data['id'] . " title='" . $data['email'] . "'>" . $data['title'] . " " . ucfirst($data['first_name']) . " " . ucfirst($data['last_name']) . "</option>";
                $i++;
            }
            $list = implode(" ", $list);
            echo $list;
        } else {
            $i = 0;
            $list[$i] = '<option value="">No User in this Department </option>';
            $list = implode(" ", $list);
            echo $list;
        }
    }

// End of function reviewer_list.

    /* Function is used to fetch user names from users table.
     * @param- 
     * @returns - an object.
     */

    public function designer_list() {
        $crclm_id = $this->input->post('crclm_id');
        $user_data = $this->course_model->dropdown_userlist3($crclm_id);
        if ($user_data) {
            $i = 0;
            $list[$i] = '<option value="">Select User</option>';
            $i++;
            foreach ($user_data as $data) {
                $list[$i] = "<option value=" . $data['id'] . " title='" . $data['email'] . "'>" . $data['title'] . " " . ucfirst($data['first_name']) . " " . ucfirst($data['last_name']) . "</option>";
                $i++;
            }
            $list = implode(" ", $list);
            echo $list;
        } else {
            $i = 0;
            $list[$i] = '<option value="">No User in this Department </option>';
            $list = implode(" ", $list);
            echo $list;
        }
    }

// End of function designer_list.

    /**
     * Function to fetch help details related to course
     * @return: an object
     */
    function course_help() {
        $help_list = $this->course_model->course_help();

        if (!empty($help_list['help_data'])) {
            foreach ($help_list['help_data'] as $help) {
                $clo_po_id = '<i class="icon-black icon-file"> </i><a target="_blank" href="' . base_url('curriculum/course/help_content') . '/' . $help['serial_no'] . '">' . $help['entity_data'] . '</a></br>';
                echo $clo_po_id;
            }
        }

        if (!empty($help_list['file'])) {
            foreach ($help_list['file'] as $file_data) {
                $file = '<i class="icon-black icon-book"> </i><a target="_blank" href="' . base_url('uploads') . '/' . $file_data['file_path'] . '">' . $file_data['file_path'] . '</a></br>';
                echo $file;
            }
        }
    }

    /* Function to display help related to course in a new page
     * @parameters: help id
     * @return: load help view page
     */

    public function help_content($help_id) {
        $help_content = $this->course_model->help_content($help_id);
        $help['help_content'] = $help_content;
        $data['title'] = "Course Page";
        $this->load->view('curriculum/course/course_help_vw', $help);
    }

// Added by bhagyalaxmi S S

    /* Function is used to add a new course details.
     * @param-
     * @returns - updated list view of course.
     */
    public function insert_course_details() {
        //permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Program Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        }
        //permission_end 
        else {
		
			$cia_check = 	$this->input->post('cia_check');$mte_check = 	$this->input->post('mte_check');$tee_check = 	$this->input->post('tee_check');
			if(($cia_check)){  $cia_flag = 1;}else{ $cia_flag = 0;}	
			if(($mte_check)){  $mte_flag = 1;}else{ $mte_flag = 0;}
			if(($tee_check)){  $tee_flag = 1;}else{ $tee_flag = 0;}		
            $bld_1 = $this->input->post('bld_1');
            $bld_2 = $this->input->post('bld_2');
            $bld_3 = $this->input->post('bld_3');
            $pre_courses_exploded = explode(",", $_POST['hidden-tags']);
            $pre_courses = implode('<>', $pre_courses_exploded);
            //course table data
            $crclm_id = $this->input->post('crclm_id');
            $crclm_term_id = $this->input->post('crclm_term_id');
            $crs_type_id = $this->input->post('crs_type_id');
            $crs_code = $this->input->post('crs_code');
            $crs_mode = $this->input->post('crs_mode');
            $crs_title = $this->input->post('crs_title');
            $crs_acronym = $this->input->post('crs_acronym');
            $co_crs_owner = $this->input->post('co_crs_owner');
            $crs_domain_id = $this->input->post('crs_domain_id');
            $lect_credits = $this->input->post('lect_credits');
            $tutorial_credits = $this->input->post('tutorial_credits');
            $practical_credits = $this->input->post('practical_credits');
            $self_study_credits = 0;
            $total_credits = $this->input->post('total_credits');
            $contact_hours = $this->input->post('contact_hours');
            $cie_marks = $this->input->post('cie_marks');
            $mid_term_marks = $this->input->post('mid_term_marks');
            $see_marks = $this->input->post('see_marks');
            $ss_marks = 0;
            $total_marks = $this->input->post('total_marks');
            $see_duration = $this->input->post('see_duration');
            $total_cia_weightage = (float) $this->input->post('total_cia_weightage');
			$total_mte_weightage = (float) $this->input->post('total_mte_weightage');
            $total_tee_weightage = (float) $this->input->post('total_tee_weightage');
            //course_clo_owner
            $course_designer = $this->input->post('course_designer');
            //course_clo_reviewer
            $course_reviewer = $this->input->post('course_reviewer');
            $review_dept = $this->input->post('review_dept');
            $last_date = $this->input->post('last_date');
            $clo_bl_flag = $this->input->post('fetch_clo_bl_flag_val');
            //predecessor courses array
            $pre_courses;
            $course_added = $this->addcourse_model
                    ->insert_course_details(
                    //course table data
                    $crclm_id, $crclm_term_id, $crs_type_id, $crs_code, $crs_mode, $crs_title, $crs_acronym, $crs_domain_id, $lect_credits, $tutorial_credits, $practical_credits, $self_study_credits, $total_credits, $contact_hours, $cie_marks, $see_marks, $ss_marks, $mid_term_marks, $total_marks, $see_duration, $co_crs_owner,
                    //course_clo_owner
                    $course_designer,
                    //course_clo_reviewer
                    $course_reviewer, $review_dept, $last_date,
                    //predecessor courses array
                    $pre_courses,
                    //total weightage of cia and tee 		// added by bhagya
                    $total_cia_weightage, $total_mte_weightage , $total_tee_weightage, $bld_1, $bld_2, $bld_3, $clo_bl_flag , 
					
					$cia_flag , $mte_flag , $tee_flag
            );
            redirect('curriculum/course', 'refresh');
        }
    }

//End of function insert_course


    /* Function is used to update the details of a course.
     * @param -
     * @returns- updated list view of course.
     */

    public function update_course() {
        //permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Program Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        }
        //permission_end 
        else {
		
			$cia_check = 	$this->input->post('cia_check');$mte_check = 	$this->input->post('mte_check');$tee_check = 	$this->input->post('tee_check');

			if(($cia_check)){  $cia_flag = 1;}else{ $cia_flag = 0;}	
			if(($mte_check)){  $mte_flag = 1;}else{ $mte_flag = 0;}
			if(($tee_check)){  $tee_flag = 1;}else{ $tee_flag = 0;}
            $total_cia_weightage = (float) $this->input->post('total_cia_weightage');
			$total_mte_weightage = (float) $this->input->post('total_mte_weightage');			
            $total_tee_weightage = (float) $this->input->post('total_tee_weightage');
			
            $bld_1 = $this->input->post('bld_1');
            $bld_2 = $this->input->post('bld_2');
            $bld_3 = $this->input->post('bld_3');
            $pre_courses_exploded = explode(",", $_POST['hidden-tags']);
            $pre_courses = implode('<>', $pre_courses_exploded);
            //course table
            $crs_id = $this->input->post('crs_id');
            $crclm_id = $this->input->post('crclm_id');
            $crclm_term_id = $this->input->post('crclm_term_id');
            $crs_type_id = $this->input->post('crs_type_id');
            $crs_code = $this->input->post('crs_code');
            $crs_mode = $this->input->post('crs_mode');
            $crs_title = $this->input->post('crs_title');
            $crs_acronym = $this->input->post('crs_acronym');
            $co_crs_owner = $this->input->post('co_crs_owner_edit');
            $crs_domain_id = $this->input->post('crs_domain_id');
            $lect_credits = $this->input->post('lect_credits');
            $tutorial_credits = $this->input->post('tutorial_credits');
            $practical_credits = $this->input->post('practical_credits');
            $self_study_credits = 0;
            $total_credits = $this->input->post('total_credits');
            $contact_hours = $this->input->post('contact_hours');
            $cie_marks = $this->input->post('cie_marks');
            $mid_term_marks = $this->input->post('mid_term_marks');
            $see_marks = $this->input->post('see_marks');
            $ss_marks = 0;
            $total_marks = $this->input->post('total_marks');
            $see_duration = $this->input->post('see_duration');

            //course_clo_owner
            $course_designer = $this->input->post('clo_owner_id');
            //course_clo_reviewer
            $review_dept = $this->input->post('review_dept');
            $course_reviewer = $this->input->post('validator_id');
            $last_date = $this->input->post('last_date');
            $clo_bl_flag = $this->input->post('fetch_clo_bl_flag_val');
            //predecessor courses array
            $pre_courses;
            // delete predecessor courses array
            $del_pre_courses = $this->input->post('delete_crs_id');

            $course_added = $this->course_model
                    ->update_course_details(
                    //course table data
                    $crs_id, $crclm_id, $crclm_term_id, $crs_type_id, $crs_code, $crs_mode, $crs_title, $crs_acronym, $co_crs_owner, $crs_domain_id, $lect_credits, $tutorial_credits, $practical_credits, $self_study_credits, $total_credits, $contact_hours, $cie_marks, $see_marks, $ss_marks, $mid_term_marks, $total_marks, $see_duration,
                    //course_clo_owner
                    $course_designer,
                    //course_clo_reviewer
                    $review_dept, $course_reviewer, $last_date,
                    //predecessor courses array
                    $pre_courses,
                    // delete predecessor courses array
                    $del_pre_courses,
                    // total weightage of cia and tee
                    $total_cia_weightage, $total_mte_weightage , $total_tee_weightage, $bld_1, $bld_2, $bld_3, $clo_bl_flag ,
					
					$cia_flag , $mte_flag , $tee_flag
            );
            redirect('curriculum/course', 'refresh');
        }
    }

// End of function update.

    /* .
     * @param-
     * @retuns - the list view of all courses.
     */

    public function course_index($curriculum_id) {
        //permission_start 
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Program Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        }
        //permission_end 
        else {
            $crclm_list = $this->course_model->crclm_fill();
            $data['curriculum_id'] = $curriculum_id;
            $data['curriculum_data'] = $crclm_list['res2'];
            $data['title'] = 'Course List Page';
            ;
            $this->load->view('curriculum/course/list_course_vw', $data);
        }
    }

    /* Function is used to check whether bloom's domain is using in the co or tlo.
     * @param - 
     * @returns- a boolean value.
     */

    public function check_disable_bloom_domain() {
        $bld_id = $this->input->post('bld_id');
        $crs_id = $this->input->post('crs_id');
        $result = $this->course_model->check_disable_bloom_domain($bld_id, $crs_id);
        echo $result;
    }

    // End of function index.

    /*
     * Function to allot course instructor for section
     */
    public function assign_course_instructor() {
        $crs_id = $this->input->post('course_id');
        $trm_id = $this->input->post('term_id');
        $crclm_id = $this->input->post('crclm_id');
        $assign_course_instructor = $this->course_model->assign_course_instructor($crs_id, $trm_id, $crclm_id);

        // Displaying user list
        if ($assign_course_instructor['course_instructor_list']) {
            $i = 0;
            $list[$i] = '<option value="">Select User</option>';
            $i++;
            foreach ($assign_course_instructor['course_instructor_list'] as $data) {
                $list[$i] = "<option value=" . $data['id'] . " title='" . $data['email'] . "'>" . $data['title'] . " " . ucfirst($data['first_name']) . " " . ucfirst($data['last_name']) . "</option>";
                $i++;

                $course_instructor_array[$data['id']] = $data['title'] . " " . ucfirst($data['first_name']) . " " . ucfirst($data['last_name']);
            }
            $list = implode(" ", $list);
            $course_instructor_data['user_list'] = $list;
        } else {
            $i = 0;
            $list[$i] = '<option value="">No User in this Department </option>';
            $list = implode(" ", $list);
            $course_instructor_data['user_list'] = $list;
        }

        $table_row = '';
        $counter = 1;
        // Displaying Course Instructor List
        foreach ($assign_course_instructor['course_instructor_display'] as $ci) {
            $table_row .= '<tr>';
            $table_row .= '<td style="text-align:right;">' . $counter . '</td>';
            $table_row .= '<td>' . $ci['section'] . '</td>';
            $table_row .= '<td><font id="instructor_name_' . $counter . '">' . $ci['user_name'] . '</font> '
                    . ' <div id="show_instructor_dropdown_' . $counter . '" class="input-append" '
                    . ' style="display:none;">' . form_dropdown('instructor_list', $course_instructor_array, $ci['course_instructor_id'], ' '
                            . ' id="instructor_list_' . $counter . '" style="" class="instructor_list"') . ''
                    . ' <button type="button" name="save_data" id="save_data_' . $counter . '"'
                    . ' class="btn btn-primary save_data_button" data-save_counter = "' . $counter . '" '
                    . ' data-edit_id="' . $ci['mcci_id'] . '"><i class="icon-file icon-white"></i> Update</button></div></td>';

            $table_row .= '<td><center><a id="edit_instructor_' . $counter . '" data-edit_counter="' . $counter . '" class="cursor_pointer edit_instructor"><i class="icon-pencil"></i></a></center></td>';
            $table_row .= '<td><center><a data-crs_id="' . $ci['crs_id'] . '" data-section_name = "' . $ci['section'] . '" data-section_id = "' . $ci['section_id'] . '"  id="delete_instructor_' . $counter . '" data-delete_id = "' . $ci['mcci_id'] . '"  class="cursor_pointer delete_instructor"><i class="icon-remove"></i></a></center></td>';
            $table_row .= '</tr>';
            $counter++;
        }

        $course_instructor_data['course_instructor_display'] = $table_row;

        //Displaying Section Dropdown box
        $sectionList = '';
        if ($assign_course_instructor['section_list']) {

            $sectionList .= '<option value="">Select</option>';

            foreach ($assign_course_instructor['section_list'] as $section) {
                $sectionList .= "<option value=" . $section['mt_details_id'] . " title='Section/Divsion " . $section['mt_details_name'] . "'>" . $section['mt_details_name'] . "</option>";
            }
            $course_instructor_data['section_list'] = $sectionList;
        } else {
            $sectionList .= '<option value="">No Data </option>';
            $course_instructor_data['section_list'] = $sectionList;
        }
        echo json_encode($course_instructor_data);
    }

    /*
     * Function to assign course instructors to different sections.
     */

    public function add_new_course_instructor() {

        $section_id = $this->input->post('section_id');
        $instructor_id = $this->input->post('instructor_id');
        $ci_crclm_id = $this->input->post('ci_crclm_id');
        $ci_term_id = $this->input->post('ci_term_id');
        $ci_crs_id = $this->input->post('ci_crs_id');
        $assign_course_instructor = $this->course_model->add_course_instructor($section_id, $instructor_id, $ci_crclm_id, $ci_term_id, $ci_crs_id);
        $get_section_name = $this->course_model->get_section_name($section_id);
        if ($assign_course_instructor == '-1') {
            echo '-1';
        } else {
            $data['populate_table'] = $this->populate_table($ci_crs_id, $ci_crclm_id, $ci_term_id,$section_id,$instructor_id,$get_section_name);
            $data['section_options'] = '';
            if (!empty($assign_course_instructor)) {

            $data['section_options'] .= '<option value="">Select</option>';

            foreach ($assign_course_instructor as $section) {
                $data['section_options'] .= "<option value=" . $section['mt_details_id'] . " title='Section/Divsion " . $section['mt_details_name'] . "'>" . $section['mt_details_name'] . "</option>";
            }
            $data['section_list'] = $data['section_options'];
        } else {
            $data['section_options'] .= '<option value="">No Data </option>';
            $data['section_list'] = $data['section_options'];
        }
            echo json_encode($data);
        }
    }

    /*
     * Function to populate table.
     */

    public function populate_table($ci_crs_id = NULL, $ci_crclm_id = NULL, $ci_term_id = NULL, $section_id=NULL,$instructor_id=NULL,$section_name=NULL) {


        $populate_table = $this->course_model->generate_table($ci_crs_id, $ci_crclm_id, $ci_term_id);
        if ($populate_table['ins_list']) {

            foreach ($populate_table['ins_list'] as $data) {

                $course_instructor_array[$data['id']] = $data['title'] . " " . ucfirst($data['first_name']) . " " . ucfirst($data['last_name']);
            }
        }

        $counter = 1;
        $table_row = '';
        // Displaying Course Instructor List
        foreach ($populate_table['instructor_data'] as $ci) {
            $table_row .= '<tr>';
            $table_row .= '<td>' . $counter . '</td>';
            $table_row .= '<td>' . $ci['section'] . '</td>';
            $table_row .= '<td><font id="instructor_name_' . $counter . '">' . $ci['user_name'] . '</font> '
                    . ' <div id="show_instructor_dropdown_' . $counter . '" class="input-append" '
                    . ' style="display:none;">' . form_dropdown('instructor_list', $course_instructor_array, $ci['course_instructor_id'], ' '
                            . ' id="instructor_list_' . $counter . '" style="" class="instructor_list"') . ''
                    . ' <button type="button" name="save_data" id="save_data_' . $counter . '"'
                    . ' class="btn btn-primary save_data_button" data-save_counter = "' . $counter . '" '
                    . ' data-edit_id="' . $ci['mcci_id'] . '"><i class="icon-file icon-white"></i> Update</button></div></td>';

            $table_row .= '<td><center><a data-crs_id = "'.$ci_crs_id.'" data-section_name="'.$ci['section'].'" data-section_id="'.$ci['section_id'].'" id="edit_instructor_' . $counter . '" data-edit_counter="' . $counter . '" class="cursor_pointer edit_instructor"><i class="icon-pencil"></i></a></center></td>';
            $table_row .= '<td><center><a data-crs_id = "'.$ci_crs_id.'" data-section_name="'.$ci['section'].'" data-section_id="'.$ci['section_id'].'" id="delete_instructor_' . $counter . '" data-delete_id = "' . $ci['mcci_id'] . '" class="cursor_pointer delete_instructor"><i class="icon-remove"></i></a></center></td>';
            $table_row .= '</tr>';
            $counter++;
        }
        return $table_row;
    }

    /*
     * Function to Edit Save of Course Instructor.
     */

    public function edit_save_course_instructor() {
        $instructor_id = $this->input->post('instructor_id');
        $mcci_id = $this->input->post('mcci_id');
        $edit_result = $this->course_model->edit_save_instructor($instructor_id, $mcci_id);
        if ($edit_result == true) {
            echo 'true';
        } else {
            echo 'false';
        }
    }

    /*
     * Function to delete to course instructor.
     */

    public function delete_course_instructor() {
        $mcci_id = $this->input->post('delete_instructor');
        $crclm_id = $this->input->post('crclm_id');
        $term_id = $this->input->post('term_id');
        $course_id = $this->input->post('course_id');
        $sec_id = $this->input->post('section_id');
        $delete_record = $edit_result = $this->course_model->delete_instructor($mcci_id, $crclm_id, $term_id, $course_id, $sec_id);
       
            $data['populate_table'] = $this->populate_table($course_id, $crclm_id, $term_id);
            
            $data['section_options'] = '';
            if (!empty($delete_record)) {

            $data['section_options'] .= '<option value="">Select</option>';

            foreach ($delete_record as $section) {
                $data['section_options'] .= "<option value=" . $section['mt_details_id'] . " title='Section/Divsion " . $section['mt_details_name'] . "'>" . $section['mt_details_name'] . "</option>";
            }
            $data['section_list'] = $data['section_options'];
        } else {
            $data['section_options'] .= '<option value="">No Data </option>';
            $data['section_list'] = $data['section_options'];
        }
            echo json_encode($data);
        
    }

    /*
     * Function to check the co finalized or not
     */

    public function section_co_finalize() {
        $course_id = $this->input->post('crs_id');
        $sec_id = $this->input->post('section_id');
        $check_finalize = $edit_result = $this->course_model->section_co_finalize($course_id, $sec_id);
        echo $check_finalize;
    }

}
?>
