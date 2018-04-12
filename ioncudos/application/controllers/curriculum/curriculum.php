<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description          : Controller Logic for Curriculum Adding, Editing and Disabling/Enabling operations performed through this file.	  
 * Modification History :
 * Date			 Modified By			Description
 * 05-09-2013		Mritunjay B S       	Added file headers, function headers & comments. 
 * 10-04-2014		Jevi V G     	        Added help function. 
 * 25-09-2014		Abhinay B.Angadi        Added Course Type Weightage distribution feature functions. 
 * 09-03-2016		Shivaraj B        	Added validation to check whether curriculum is already defined for the start year for selected program.
  ---------------------------------------------------------------------------------------------------------------------------------
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Curriculum extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('curriculum/curriculum/curriculum_model');
    }

    /*
     * Function is to check for user login. and to display the curriculum.
     * @param - ------.
     * returns the list of curriculums.
     */

    public function index() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman'))) {
            redirect('configuration/users/blank');
        } else {
            $limit = 4;
            $results = $this->curriculum_model->list_of_curriculum();
            $data['curriculum_list_result'] = $results['curriculum_list'];
            $data['curriculum_count'] = $results['crclm_count'];
            $data['title'] = "Curriculum List Page";
            $this->load->view('curriculum/curriculum/list_curriculum_vw', $data);
        }
    }

    /*
     * Function is to check for user login. and to display the static list of curriculum.
     * @param - ------.
     * returns the list of curriculums.
     */

    public function curriculum_static_index() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            $limit = 4;
            $results = $this->curriculum_model->list_of_curriculum();
            $data['curriculum_list_result'] = $results['curriculum_list'];
            $data['curriculum_count'] = $results['crclm_count'];
            $data['title'] = "Curriculum List Page";
            $this->load->view('curriculum/curriculum/static_list_curriculum_vw', $data);
        }
    }

    /*
     * Function is to initiating the next step after creating curriculum.
     * @param - ------.
     * @returns ------.
     */

    public function approve_publish_db() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman'))) {
            redirect('configuration/users/blank');
        } else {
            $crclm_id = $this->input->post('crclm_id');
            // var_dump($_POST);
            $results = $this->curriculum_model->approve_publish_db($crclm_id);
            //email
            $receiver_id = $results['owner_id'];
            $cc = '';
            $links = base_url('curriculum/peo/');
            $entity_id = 2;
            $state = 7;
            $curriculum_id = $crclm_id;
            $additional_data = '';

            $this->ion_auth->send_email($receiver_id, $cc, $links, $entity_id, $state, $curriculum_id, $additional_data);
        }
    }

    /*
     * Function is to display curriculum details in static list of curriculum.
     * @param - ------.
     * returns the list of curriculums.
     */

    public function static_details_curriculum($crclm_id) {

        if (!$this->ion_auth->logged_in()) {

            //redirect them to the login page
            redirect('login', 'refresh');
        } else {

            $this->data['curr_details'] = $this->curriculum_model->curriculum_details($crclm_id);
            $this->data['owner'] = $this->curriculum_model->curriculum_owner($crclm_id);
            $this->data['curr_details_dept'] = $this->curriculum_model->curriculum_owner_department($crclm_id);
            $this->data['term_details'] = $this->curriculum_model->static_term_details($crclm_id);
            $this->data['curriculum_peo_approver'] = $this->curriculum_model->curriculum_peo_approver($crclm_id);
            $this->data['clo_po_approver'] = $this->curriculum_model->clo_po_approver($crclm_id);
            $this->po_details = $this->data['curr_details_dept'] + $this->data['curriculum_peo_approver'];
            $this->clo_details = $this->data['curr_details_dept'] + $this->data['clo_po_approver'];
            $this->data['po_details'] = $this->po_details;
            $this->data['clo_details'] = $this->clo_details;

            $data['title'] = "Curriculum List Page";
            $this->load->view('curriculum/curriculum/static_curriculum_details_vw', $this->data);
        }
    }

    /*
      /*
     * Function is to display curriculum details in curriculum list page.
     * @param - ------.
     * returns the curriculums details.
     */

    public function details_curriculum($crclm_id) {
        if (!$this->ion_auth->logged_in()) {

            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            $this->data['curr_details'] = $this->curriculum_model->curriculum_details($crclm_id);
            $this->data['owner'] = $this->curriculum_model->curriculum_owner($crclm_id);
            $this->data['curr_details_dept'] = $this->curriculum_model->curriculum_owner_department($crclm_id);
            $this->data['term_details'] = $this->curriculum_model->static_term_details($crclm_id);
            $this->data['curriculum_peo_approver'] = $this->curriculum_model->curriculum_peo_approver($crclm_id);
            $this->data['clo_po_approver'] = $this->curriculum_model->clo_po_approver($crclm_id);
            $this->po_details = $this->data['curr_details_dept'] + $this->data['curriculum_peo_approver'];
            $this->clo_details = $this->data['curr_details_dept'] + $this->data['clo_po_approver'];
            $this->data['po_details'] = $this->po_details;
            $this->data['clo_details'] = $this->clo_details;
            $data['title'] = "Curriculum Details Page";
            $this->load->view('curriculum/curriculum/curriculum_details_vw', $this->data);
        }
    }

    /*
     * Function is to display state of curriculum.
     * @param - ------.
     * returns the state of the curriculum.
     */

    function curriculum_state() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman'))) {
            redirect('configuration/users/blank');
        } else {
            $crclm_id = $this->input->post('crclm_id');
            $status = $this->input->post('status');
            $result = $this->curriculum_model->curriculum_state($crclm_id, $status);
            return true;
        }
    }

    /*
     * Function is to create add curriculum view page.
     * @param - ------.
     * returns ---- .
     */

    public function add_curriculum() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman'))) {
            redirect('configuration/users/blank');
        } else {
            $data['crclm_description'] = array(
                'name' => 'crclm_description',
                'id' => 'crclm_description',
                'class' => '',
                'type' => 'text',
                'placeholder' => 'Enter Description',
                'rows' => '3',
                'maxlength' => "2000",
                'size' => '20');

            $data['start_year'] = array(
                'name' => 'start_year',
                'id' => 'start_year',
                'class' => 'text_align_right required onlyDigit',
                'type' => 'text',
                'size' => '1');
            $data['pgm_acronym'] = array(
                'name' => 'pgm_acronym',
                'id' => 'pgm_acronym',
                'class' => '',
                'type' => 'hidden');

            $data['end_year'] = array(
                'name' => 'end_year',
                'id' => 'end_year',
                'class' => 'text_align_right required onlyDigit duration',
                'type' => 'text',
                'size' => '4');

            $data['crclm_owner'] = array(
                'name' => 'crclm_owner',
                'id' => 'crclm_owner',
                'class' => 'required noSpecialChars',
                'type' => 'text',
                'placeholder' => 'Enter Name',
                'size' => '20');

            /* Term Detail */

            $data['term_name'] = array(
                'name' => 'term_name',
                'id' => 'term_name',
                'class' => 'required noSpecialChars',
                'type' => 'text',
                'placeholder' => 'Enter Name',
                'size' => '20');

            $data['dur'] = array(
                'name' => 'dur',
                'id' => 'dur',
                'class' => 'text_align_right required onlyDigit duration',
                'type' => 'text',
                'size' => '1');

            $data['credits'] = array(
                'name' => 'credits',
                'id' => 'credits',
                'class' => 'text_align_right required onlyDigit',
                'type' => 'text',
                'size' => '1');

            $data['theory'] = array(
                'name' => 'theory',
                'id' => 'theory',
                'class' => 'text_align_right required onlyDigit',
                'type' => 'text',
                'size' => '1');

            $data['practical'] = array(
                'name' => 'practical',
                'id' => 'practical',
                'class' => 'text_align_right required onlyDigit',
                'type' => 'text',
                'size' => '1');

            $data['total_terms'] = array(
                'name' => 'total_terms1',
                'id' => 'total_terms1',
                'type' => 'hidden');

            $data['total_credits'] = array(
                'name' => 'total_credits1',
                'id' => 'total_credits1',
                'type' => 'hidden');

            $data['programlist'] = $this->curriculum_model->dropdown_program_title();
            $data['departmentlist'] = $this->curriculum_model->dropdown_department();
            $data['userlist'] = $this->curriculum_model->dropdown_userlist();

            // Course Type drop-down not required so commented as it is populated through AJAX call
            //$data['course_type'] = $this->curriculum_model->course_list();

            $data['title'] = "Curriculum Add Page";
            $this->load->view('curriculum/curriculum/add_curriculum_vw', $data);
        }
    }

    /*
     * Function is to add  curriculum details.
     * @param - ------.
     * returns ---- .
     */

    public function add_curriculum_details() {

        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman'))) {
            redirect('configuration/users/blank');
        } else {

            //curriculum table
            $crclm_name = $this->input->post('crclm_name');
            $crclm_description = $this->input->post('crclm_description');
            $start_year = $this->input->post('start_year');
            $end_year = $this->input->post('end_year');
            $total_credits = $this->input->post('total_credits');
            $total_terms = $this->input->post('total_terms');
            $crclm_owner = $this->input->post('crclm_owner');
            $pgm_title = $this->input->post('pgm_title');

            // data to be insert in crclm_terms table.
            $counter = $this->input->post('counter');
            for ($i = 1; $i <= $counter; $i++) {
                $term_name[] = $this->input->post('term_name_' . $i);
                $term_duration[] = $this->input->post('term_duration_' . $i);
                $term_credits[] = $this->input->post('term_credits_' . $i);
                $total_theory_courses[] = $this->input->post('total_theory_courses_' . $i);
                $total_practical_courses[] = $this->input->post('total_practical_courses_' . $i);
                $academic_year[] = $this->input->post('term_year_' . $i);
            }
            //to read curriculum id before insertion read after insertion
            //peo table
            $entity_id_peo = 5;

            //curriculum id to be passed after insertion
            $dept_name_peo = $this->input->post('dept_name_peo');
            $username_peo = $this->input->post('username_peo');
            $last_date_peo = $this->input->post('last_date_peo');

            //po_clo owner table
            $entity_id_po_clo = 14;

            //curriculum id to be passed after insertion
            $dept_name_po_clo = $this->input->post('dept_name_po_clo');
            $username_po_clo = $this->input->post('username_po_clo');
            $last_date_po_clo = $this->input->post('last_date_po_clo');

            //po_clo owner table
            $entity_id_po_peo_mapping = 13;

            //curriculum id to be passed after insertion
            $dept_name_po_peo_mapping = $this->input->post('dept_name_po_peo_mapping');
            $username_po_peo_mapping = $this->input->post('username_po_peo_mapping');
            $last_date_po_peo_mapping = $this->input->post('last_date_peo');

            // Course Type CIA & TEE weightage distribution POST data
            $cloneCntr = $this->input->post('stack_counter');
            $counter = explode(',', $cloneCntr);
            $size = sizeof($counter);
            for ($k = 0; $k < $size; $k++) {
                $course_type_value[] = $this->input->post('course_type_value' . $counter[$k]);
            }

            $curriculum_added = $this->curriculum_model->insert_curriculum(
                    //curriculum table
                    $crclm_name, $crclm_description, $start_year, $end_year, $total_credits, $total_terms, $crclm_owner, $pgm_title,
                    //term table
                    $term_name, $term_duration, $term_credits, $total_theory_courses, $total_practical_courses,
                    //to read curriculum id before insertion read after insertion
                    //peo table
                    $entity_id_peo,
                    //curriculum id to be passed after insertion
                    $dept_name_peo, $username_peo, $last_date_peo,
                    //po_clo owner table
                    $entity_id_po_clo,
                    //curriculum id to be passed after insertion
                    $dept_name_po_clo, $username_po_clo, $last_date_po_clo,
                    //po_clo owner table
                    $entity_id_po_peo_mapping,
                    //curriculum id to be passed after insertion
                    $dept_name_po_peo_mapping, $username_po_peo_mapping, $last_date_peo,
                    //curriculum id to be passed after insertion Course Type post data
                    $course_type_value, $academic_year);
            redirect('curriculum/curriculum');
        }
    }

    /*
     * Function is to get the program details.
     * @param - program id is used to fetch the program details.
     * returns the total number of terms, total number of credit, program duration details.
     */

    public function program_details_by_program_id($pgm_id = NULL) {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman'))) {
            redirect('configuration/users/blank');
        } else {

            $program_details = $this->curriculum_model->program_details_by_program_id($pgm_id);
            header('Content-Type: application/x-json; charset=utf-8');
            echo(json_encode($program_details));
        }
    }

    /*
     * Function is to get the curriculum name.
     * @param - program id is used to fetch the curriculum name.
     * returns the curriculum name.
     */

    public function curriculum_name($pgm_id = NULL) {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman'))) {
            redirect('configuration/users/blank');
        } else {
            $curriculum_name = $this->curriculum_model->program_acronym($pgm_id);
            header('Content-Type: application/x-json; charset=utf-8');
            echo(json_encode($curriculum_name));
        }
    }

    /*
     * Function is to create curriculum edit page view.
     * @param - curriculum id is used fetch the details of the curriculum.
     * returns the curriculum details based on the curriculum id.
     */

    public function edit($crclm_id) {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman'))) {
            redirect('configuration/users/blank');
        } else {
            $data['course_type_weightage'] = $this->curriculum_model->course_type_weightage($crclm_id);
            $counter = array();
            $size = sizeof($data['course_type_weightage']);
            for ($k = 1; $k <= $size; $k++) {
                $counter[] = $k;
            }
            $imploded_count = implode(',', $counter);
            for ($i = 0; $i < count($data['course_type_weightage']); $i++) {
                $crs_type_id[$i] = $data['course_type_weightage'][$i]['course_type_id'];
                $data['course_type_name'][$i] = $this->curriculum_model->course_type_by_id($crs_type_id[$i]);
                $details = $this->curriculum_model->fetch_course_type_details($crs_type_id[$i]);
                if ($details) {
                    $data['crclm_comp_name'][$i] = $this->curriculum_model->crclm_comp_name($details[0]['crclm_component_id']);
                    $data['crs_type_desc'][$i] = $details[0]['crs_type_description'];
                }
            }

            $data['curriculum_details'] = $this->curriculum_model->curriculum_details_in_edit($crclm_id);
            $data['term_details'] = $this->curriculum_model->term_details($crclm_id);
            $data['crclm_id'] = $crclm_id;
            $data['crclm_description'] = array(
                'name' => 'crclm_description',
                'id' => 'crclm_description',
                'class' => '',
                'type' => 'text',
                'placeholder' => 'Enter Description',
                'value' => $data['curriculum_details']['0']['crclm_description'],
                'rows' => '3',
                'size' => '20',
                'maxlength' => "2000",
                'autofocus' => 'autofocus'
            );

            $data['start_year'] = array(
                'name' => 'start_year',
                'id' => 'start_year',
                'class' => 'text_align_right required yearpicker',
                'type' => 'text',
                'value' => $data['curriculum_details']['0']['start_year'],
                'onChange' => 'edit_populate_year();',
                'readonly' => 'readonly',
                'size' => '1');

            /*            $data['end_year'] = array(
              'name' => 'end_year',
              'id' => 'end_year',
              'class' => 'text_align_right required onlyDigit duration yearchange',
              'type' => 'text',
              'value' => $data['curriculum_details']['0']['end_year'],
              'size' => '4'); */

            $data['end_year'] = array(
                'name' => 'end_year',
                'id' => 'end_year',
                'class' => 'text_align_right required ',
                'type' => 'text',
                'value' => $data['curriculum_details']['0']['end_year'],
                'readonly' => 'readonly',
                'size' => '4');

            $data['crclm_owner'] = array(
                'name' => 'crclm_owner',
                'id' => 'crclm_owner',
                'class' => 'required noSpecialChars',
                'type' => 'text',
                'placeholder' => 'Enter Name',
                'size' => '20',
                'value' => $data['curriculum_details']['0']['crclm_owner']);

            $data['crclm_credits'] = array(
                'name' => 'crclm_credits',
                'id' => 'crclm_credits',
                'class' => 'text_align_right required onlyDigit',
                'type' => 'text',
                'value' => $data['curriculum_details'][0]['total_credits']);

            /* Term Detail */

            $data['term_name'] = array(
                'name' => 'term_name',
                'id' => 'term_name',
                'class' => 'required noSpecialChars',
                'type' => 'text',
                'placeholder' => 'Enter Name',
                'size' => '20',
                'value' => $data['curriculum_details']['0']['term_name']);

            $data['dur'] = array(
                'name' => 'dur',
                'id' => 'dur',
                'class' => 'text_align_right required onlyDigit duration rightJustified',
                'type' => 'text',
                'size' => '1');

            $data['credits'] = array(
                'name' => 'credits',
                'id' => 'credits',
                'class' => 'text_align_right required onlyDigit rightJustified',
                'type' => 'text',
                'size' => '1');

            $data['theory'] = array(
                'name' => 'theory',
                'id' => 'theory',
                'class' => 'text_align_right required onlyDigit rightJustified',
                'type' => 'text',
                'size' => '1');

            $data['practical'] = array(
                'name' => 'practical',
                'id' => 'practical',
                'class' => 'text_align_right required onlyDigit rightJustified',
                'type' => 'text',
                'size' => '1');

            $data['programlist'] = $this->curriculum_model->dropdown_program_title();
            $data['departmentlist'] = $this->curriculum_model->dropdown_department();
            $data['userlist'] = $this->curriculum_model->dropdown_userlist_edit();
            $data['bosuserlist'] = $this->curriculum_model->dropdown_bosuserlist($crclm_id);
            //$data['clo_po_bosuserlist'] = $this->curriculum_model->clo_po_approver_dropdown_bosuserlist($crclm_id);
            $data['approver_details'] = $this->curriculum_model->approver_details($crclm_id);

            $data['course_type'] = $this->curriculum_model->course_list($crclm_id);
            $data['imp_count'] = $imploded_count;


            /* $data['po_clo_aid'] = array(
              'name' 	=> 'po_clo_aid',
              'id' 	=> 'po_clo_aid',
              'type' 	=> 'hidden',
              'size' 	=> '20',
              'value' => $data['approver_details']['po_clo_details']['0']['aid'] ); */

            $data['po_peo_aid'] = array(
                'name' => 'po_peo_aid',
                'id' => 'po_peo_aid',
                'type' => 'hidden',
                'size' => '20',
                'value' => $data['approver_details']['po_peo_details']['0']['aid']);

            $data['title'] = "Curriculum Edit Page";
            $this->load->view('curriculum/curriculum/edit_curriculum_vw', $data);
        }
    }

    /*
     * Function is to create curriculum static edit page view.
     * @param - curriculum id is used fetch the details of the curriculum.
     * returns the curriculum details based on the curriculum id.
     */

    public function static_edit($crclm_id) {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman'))) {
            redirect('configuration/users/blank');
        } else {
            $data['curriculum_details'] = $this->curriculum_model->curriculum_details_in_edit($crclm_id);
            $data['term_details'] = $this->curriculum_model->term_details($crclm_id);
            $data['crclm_id'] = $crclm_id;

            $data['crclm_description'] = array(
                'name' => 'crclm_description',
                'id' => 'crclm_description',
                'class' => '',
                'type' => 'text',
                'placeholder' => 'Enter Description',
                'value' => $data['curriculum_details']['0']['crclm_description'],
                'readonly' => '',
                'size' => '20');

            $data['start_year'] = array(
                'name' => 'start_year',
                'id' => 'start_year',
                'class' => 'text_align_right required onlyDigit rightJustified',
                'type' => 'text',
                'value' => $data['curriculum_details']['0']['start_year'],
                'size' => '1');

            $data['end_year'] = array(
                'name' => 'end_year',
                'id' => 'end_year',
                'class' => 'text_align_right required onlyDigit duration rightJustified',
                'type' => 'text',
                'value' => $data['curriculum_details']['0']['end_year'],
                'size' => '4');

            $data['crclm_owner'] = array(
                'name' => 'crclm_owner',
                'id' => 'crclm_owner',
                'class' => 'required noSpecialChars',
                'type' => 'text',
                'placeholder' => 'Enter Name',
                'size' => '20',
                'value' => $data['curriculum_details']['0']['crclm_owner']);

            /* Term Detail */

            $data['term_name'] = array(
                'name' => 'term_name',
                'id' => 'term_name',
                'class' => 'required noSpecialChars',
                'type' => 'text',
                'placeholder' => 'Enter Name',
                'size' => '20',
                'value' => $data['curriculum_details']['0']['term_name']);

            $data['dur'] = array(
                'name' => 'dur',
                'id' => 'dur',
                'class' => 'text_align_right required onlyDigit duration rightJustified',
                'type' => 'text',
                'size' => '1');

            $data['credits'] = array(
                'name' => 'credits',
                'id' => 'credits',
                'class' => 'text_align_right required onlyDigit rightJustified',
                'type' => 'text',
                'size' => '1');

            $data['theory'] = array(
                'name' => 'theory',
                'id' => 'theory',
                'class' => 'text_align_right required onlyDigit rightJustified',
                'type' => 'text',
                'size' => '1');

            $data['practical'] = array(
                'name' => 'practical',
                'id' => 'practical',
                'class' => 'text_align_right required onlyDigit rightJustified',
                'type' => 'text',
                'size' => '1');

            $data['programlist'] = $this->curriculum_model->dropdown_program_title();
            $data['departmentlist'] = $this->curriculum_model->dropdown_department();
            $data['userlist'] = $this->curriculum_model->dropdown_userlist();
            $data['approver_details'] = $this->curriculum_model->approver_details($crclm_id);

            $data['peo_aid'] = array(
                'name' => 'peo_aid',
                'id' => 'peo_aid',
                'type' => 'hidden',
                'size' => '20',
                'value' => $data['approver_details']['peo_details']['0']['aid']);

            $data['po_clo_aid'] = array(
                'name' => 'po_clo_aid',
                'id' => 'po_clo_aid',
                'type' => 'hidden',
                'size' => '20',
                'value' => $data['approver_details']['po_clo_details']['0']['aid']);

            $data['po_peo_aid'] = array(
                'name' => 'po_peo_aid',
                'id' => 'po_peo_aid',
                'type' => 'hidden',
                'size' => '20',
                'value' => $data['approver_details']['po_peo_details']['0']['aid']);

            $data['title'] = "Curriculum Details Page";
            $this->load->view('curriculum/curriculum/static_edit_curriculum_vw', $data);
        }
    }

    /*
     * Function is to update curriculum details.
     * @param -----.
     * returns the curriculum update success message.
     */

    public function curriculum_update() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman'))) {
            redirect('configuration/users/blank');
        } else {
            // Course Type POST data starts //
            $cloneCntr = $this->input->post('stack_counter');
            $a = explode(',', $cloneCntr);
            $size = sizeof($a);
            for ($k = 0; $k < $size; $k++) {
                $course_type_value[] = $this->input->post('course_type_value' . $a[$k]);
            }
            // Course Type POST data ends //
            $crclm_id = $this->input->post('crclm_id');
            $crclm_name = $this->input->post('crclm_name');
            $crclm_description = $this->input->post('crclm_description');
            $start_year = $this->input->post('start_year');
            $end_year = $this->input->post('end_year');
            $total_credits = $this->input->post('total_credits');
            $crclm_total_credits = $this->input->post('crclm_credits');
            $total_terms = $this->input->post('total_terms');
            $crclm_owner = $this->input->post('crclm_owner');
            $pgm_title = $this->input->post('pgm_title');

            //term table
            $counter = $this->input->post('counter');
            for ($i = 1; $i <= $counter; $i++) {
                $crclm_term_id[] = $this->input->post('crclm_term_id_' . $i);
                $term_name[] = $this->input->post('term_name_' . $i);
                $term_duration[] = $this->input->post('term_duration_' . $i);
                $term_credits[] = $this->input->post('term_credits_' . $i);
                $total_theory_courses[] = $this->input->post('total_theory_courses_' . $i);
                $total_practical_courses[] = $this->input->post('total_practical_courses_' . $i);
                $academic_year[] = $this->input->post('term_year_' . $i);
            }

            //peo table
            $entity_id_peo = 5;

            //curriculum id to be passed after insertion
            $peo_aid = $this->input->post('peo_aid');
            $dept_name_peo = $this->input->post('dept_name_peo');
            $username_peo = $this->input->post('username_peo');
            $last_date_peo = $this->input->post('last_date_peo');

            //po_clo owner table
            $entity_id_po_clo = 14;

            //curriculum id to be passed after insertion
            $po_clo_aid = $this->input->post('po_clo_aid');
            $dept_name_po_clo = $this->input->post('dept_name_po_clo');
            $username_po_clo = $this->input->post('username_po_clo');
            $last_date_po_clo = $this->input->post('last_date_po_clo');

            //po_clo owner table
            $entity_id_po_peo_mapping = 13;

            //curriculum id to be passed after insertion
            $po_peo_aid = $this->input->post('po_peo_aid');
            $dept_name_po_peo_mapping = $this->input->post('dept_name_po_peo_mapping');
            $username_po_peo_mapping = $this->input->post('username_po_peo_mapping');
            $last_date_po_peo_mapping = $this->input->post('last_date_po_peo_mapping');

            $curriculum_added = $this->curriculum_model
                    ->update_curriculum(
                    //curriculum table
                    $crclm_id, $crclm_name, $crclm_description, $start_year, $end_year, $crclm_total_credits, $total_credits, $total_terms, $crclm_owner, $pgm_title,
                    //term table
                    $crclm_term_id, $term_name, $term_duration, $term_credits, $total_theory_courses, $total_practical_courses,
                    //to read curriculum id before insertion read after insertion
                    //peo table
                    $entity_id_peo,
                    //curriculum id to be passed after insertion
                    $peo_aid, $dept_name_peo, $username_peo, $last_date_peo,
                    //po_clo owner table
                    $entity_id_po_clo,
                    //curriculum id to be passed after insertion
                    $po_clo_aid, $dept_name_po_clo, $username_po_clo, $last_date_po_clo,
                    //po_clo owner table
                    $entity_id_po_peo_mapping,
                    //curriculum id to be passed after insertion
                    $po_peo_aid, $dept_name_po_peo_mapping, $username_po_peo_mapping, $last_date_po_peo_mapping,
                    // Course Type POST data //
                    $course_type_value, $academic_year);

            redirect('curriculum/curriculum');
        }
    }

    /*
     * Function is to get the user details filtered by dept.
     * @param dept id is used to fetch the user of that dept.
     * returns the list of user of that particular department.
     */

    public function users_by_department($dept_id = NULL) {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman'))) {
            redirect('configuration/users/blank');
        } else {
            $bos_users = $this->curriculum_model->bos_users_data($dept_id);
            $this->data['users_by_department'] = $bos_users;
            // $this->ion_auth
            // ->order_by('username')
            // ->where('user_dept_id', $dept_id)
            // ->users()
            // ->result_array();

            header('Content-Type: application/x-json; charset=utf-8');
            echo(json_encode($this->data['users_by_department']));
        }
    }

    /*
     * Function is to get the user details filtered by dept.
     * @param dept id is used to fetch the user of that dept.
     * returns the list of user of that particular department.
     */

    public function crclm_owner_data($dept_id = NULL) {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman'))) {
            redirect('configuration/users/blank');
        } else {
            $crclm_owner = $this->curriculum_model->crclm_owner_data($dept_id);
            // $this->data['crclm_owner_data'] = $crclm_owner;

            header('Content-Type: application/x-json; charset=utf-8');
            //echo(json_encode($this->data['crclm_owner_data']));
            echo (json_encode($crclm_owner));
        }
    }

    /*
     * Function is to get the details of different entities and shows the progress of the curriculum.
     * @param -------.
     * returns the list of different entities and shows the progress of the curriculum.
     */

    public function curriculum_progress() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman'))) {
            redirect('configuration/users/blank');
        } else {
            $crclm_id = $this->input->post('crclm_id');
            $progress_data = $this->curriculum_model->progress_db($crclm_id);
            $work = $progress_data['work_history'];
            $state = $progress_data['work_state'];

            $count = 0;
            $progress_value = 0;
            $i = 0;
            $final_progress = 0;

            $table[$i] = '<label><b>Total Curriculum Progress</b></label>';
            $table[$i++] = '<table class="table table-bordered table-hover"><th>Entity</th><th>Status</th><th>Progress</th>';
            foreach ($work as $progress) {
                foreach ($state as $state_prog) {
                    if ($progress['MAX( h.state)'] == $state_prog['state_id']) {
                        $count += 100;
                        $progress_value += $state_prog['percent'];
                        $progress['entity_name'] = strtoupper(str_replace('_', ' ', $progress['entity_name']));
                        $table[$i++] = '<tr><td>' . $progress['entity_name'] . '</td><td>' . $state_prog['status'] . '</td><td><div class="progress progress-striped active"><div class="bar" style="width:' . $state_prog['percent'] . '%' . '"></div></div>' . $state_prog['percent'] . '%' . '</td></tr>';
                    }
                }
                $i++;

                $final_progress = round((($progress_value / $count) * 100), 2);
            }

            $table[$i++] = '<tr><label><b>Total Progress of curriculum:' . $final_progress . '%' . '</b></label><div class="progress progress-striped active" colspan="8"><div class="bar" style="width:' . $final_progress . '%' . '"></div></div></tr><table>';

            $table = implode(' ', $table);
            echo $table;
        }
    }

    /**
     * Function to fetch help details related to curriculum
     * @return: an object
     */
    function curriculum_help() {
        $help_list = $this->curriculum_model->curriculum_help();

        if (!empty($help_list['help_data'])) {
            foreach ($help_list['help_data'] as $help) {
                $peo_id = '<i class="icon-black icon-file"> </i><a target="_blank" href="' . base_url('curriculum/curriculum/help_content') . '/' . $help['serial_no'] . '">' . $help['entity_data'] . '</a></br>';
                echo $peo_id;
            }
        }

        if (!empty($help_list['file'])) {
            foreach ($help_list['file'] as $file_data) {
                $file = '<i class="icon-black icon-book"> </i><a target="_blank" href="' . base_url('uploads') . '/' . $file_data['file_path'] . '">' . $file_data['file_path'] . '</a></br>';
                echo $file;
            }
        }
    }

    /**
     * Function to display help related to curriculum in a new page
     * @parameters: help id
     * @return: load help view page
     */
    public function help_content($help_id) {
        $help_content = $this->curriculum_model->help_content($help_id);
        $help['help_content'] = $help_content;
        $data['title'] = "Curriculum List Page";
        $this->load->view('curriculum/curriculum/curriculum_help_vw', $help);
    }

    /* Function is used to fetch Chairman of the Curriculum from department table.
     * @param- curriculum id
     * @returns - an object.
     */

    public function fetch_chairman_user() {
        $curriculum_id = $this->input->post('crclm_id');
        $chairman_user = $this->curriculum_model->fetch_chairman_user($curriculum_id);
        $chairman_user_name = $chairman_user[0]['title'] . ' ' . ucfirst($chairman_user[0]['first_name']) . ' ' . ucfirst($chairman_user[0]['last_name']);
        echo $chairman_user_name;
    }

// End of function fetch_chairman_user. 
    // Function to Add table row with dropdown for course type and text box for cia,tee,occasions//
    public function add_more_tr() {
        $counter = $this->input->post('counter');
        $stack_counter = $this->input->post('stack_counter');
        $pgm_id = $this->input->post('pgm_id');
        ++$counter;
        $add_more = '';

        $course_type = $this->curriculum_model->course_type_list($pgm_id);
        $count = count($course_type);
        $add_more.= "<tr class='course_type_del'><td name='crclm_comp" . $counter . "' id='crclm_comp" . $counter . "' style='text-align:center'> </td><td><select class='crs_type required' id='course_type_value" . $counter . "' name='course_type_value" . $counter . "' onchange='select_details(this.value,$counter);'><option value=''>Select Course Type</option>";

        for ($i = 0; $i < $count; $i++) {
            $add_more.= "<option value=" . $course_type[$i]['crs_type_id'] . ">" . ucfirst($course_type[$i]['crs_type_name']) . "</option>";
        }

        $add_more.= "</select><span style='position: relative;left: 5px; color:red;' id='error_msg" . $counter . "'></span>";
        $add_more.= "<td name=crs_type_desc" . $counter . "' id='crs_type_desc" . $counter . "'></td><td>";

        if ($counter == 1) {
            $add_more.="</td>";
        } else {
            $add_more.="<a id='remove_field" . $counter . "' class='Delete' ><i class='icon-remove' id='icon-remove'></i></a>";
        }
        $add_more.="</td></tr>";
        echo $add_more;
    }

    //Function to delete course type selected by using crclm_id & course id//
    public function delete_course_type() {
        $crclm_id = $this->input->post('crclm_id');
        $course_id = $this->input->post('course_id');
        $deleted_course = $this->curriculum_model->delete_course_type($crclm_id, $course_id);
    }

    /*
     * Function is to fetch course type details in add curriculum page view.
     * @param - pgm_id is used fetch the details 
     * returns the course type details based on the pgm_id.
     */

    public function crclm_course_type_table() {
        $pgm_id = $this->input->post('pgm_id');

        $data['course_type_weightage'] = $this->curriculum_model->crclm_course_type_table($pgm_id);
        $counter = array();
        $size = sizeof($data['course_type_weightage']);
        for ($k = 1; $k <= $size; $k++) {
            $counter[] = $k;
        }
        $imploded_count = implode(',', $counter);
        for ($i = 0; $i < count($data['course_type_weightage']); $i++) {
            $crs_type_id[$i] = $data['course_type_weightage'][$i]['course_type_id'];
            $data['course_type_name'][$i] = $this->curriculum_model->course_type_by_id($crs_type_id[$i]);
            $details = $this->curriculum_model->fetch_course_type_details($crs_type_id[$i]);
            if ($details) {
                $data['crclm_comp_name'][$i] = $this->curriculum_model->crclm_comp_name($details[0]['crclm_component_id']);
                $data['crs_type_desc'][$i] = $details[0]['crs_type_description'];
            }
        }

        $data['course_type'] = $this->curriculum_model->add_course_list($pgm_id);
        $data['imp_count'] = $imploded_count;

        $this->load->view('curriculum/curriculum/crclm_course_type_table_vw', $data);
    }

    /*
     * Function is to display state of oe and pi.
     * @param - ------.
     * returns the state of the oe and pi.
     */

    public function oe_pi_state() {
        $crclm_id = $this->input->post('crclm_id');
        $status = $this->input->post('status');
        $result = $this->curriculum_model->oe_pi_state($crclm_id, $status);
        return true;
    }

    public function clo_bl_state() {
        $crclm_id = $this->input->post('crclm_id');
        $status = $this->input->post('status');
        $result = $this->curriculum_model->clo_bl_state($crclm_id, $status);
        return true;
    }

    public function is_curriculum_exists() {
        $pgm_id = $this->input->post('pgm_id');
        $crclm_id = $this->input->post('crclm_id');
        $start_year = $this->input->post('start_year');
        $type = $this->input->post('type');

        if ($type == "add") {
            $result = $this->curriculum_model->check_curriculum_exits($pgm_id, $start_year, 0);
        } else if ($type == "edit") {
            $result = $this->curriculum_model->check_curriculum_exits($pgm_id, $start_year, 1, $crclm_id);
        }
        //var_dump($result);
        echo json_encode($result);
    }

    public function fetch_course_type_details() {
        $value = $this->input->post('value');
        $details = $this->curriculum_model->fetch_course_type_details($value);
        if ($details) {
            $crclm_comp_name = $this->curriculum_model->crclm_comp_name($details[0]['crclm_component_id']);
            $crs_type_desc = $details[0]['crs_type_description'];
            $result = $crclm_comp_name . ',' . $crs_type_desc;
            echo $result;
        }
    }

}

/* End of file Curriculum.php */
	/* Location: ./application/controllers/Curriculum.php */
