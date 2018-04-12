<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Controller Logic for Program Adding, Editing and Disabling/Enabling operations performed through this file.	  
 * Modification History:
 * Date				Modified By				Description
 * 20-08-2013        Mritunjay B S           Added file headers, function headers & comments. 
  24-09-2014        Waseemraj Mulla			Added course type weightage create and update function and comments
 * 02-01-2015	    Shayista Mulla		Added duration validation functions and field data length.  
  ---------------------------------------------------------------------------------------------------------------------------------
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Program extends CI_Controller {

    public function __construct() {
	parent::__construct();
	$this->load->library('session');
	$this->load->helper('url');
	$this->load->model('configuration/program/pgm_model');
    }

    /**
     *  Function is to check the user logged in user and to load the program list view.
     *  @param - ------.
     *  returns program list view.
     */
    public function index() {
	if (!$this->ion_auth->logged_in()) {
	    //redirect them to the login page
	    redirect('login', 'refresh');
	} elseif (!$this->ion_auth->is_admin() && !($this->ion_auth->has_permission('Read Program'))) {
	    //redirect them to the home page because they must be an administrator to view this
	    redirect('configuration/users/blank', 'refresh');
	} else {
	    $get_sanp = $this->pgm_model->get_sanp_details();
	    $num_progs = $this->pgm_model->get_num_progs();
	    $program_list_result = $this->pgm_model->program_list();

	    //decrypt number of programs
	    $this->load->library('Encryption');
	    $get_snaps = $get_sanp[0]['sanp'];
	    $get_sanp = $this->encryption->decode($get_snaps);
	    if ($get_sanp > $num_progs) {
		$data['get_sanp'] = 1;
	    } else {
		$data['get_sanp'] = 0;
	    }

	    $data['pgm_list_data_result'] = $program_list_result['pgm_list_data'];
	    $data['num_rows'] = $program_list_result['num_rows'];
	    $data['search'] = '0';
	    $data['title'] = "Program List Page";

	    $this->load->view('configuration/program/list_program_vw', $data);
	}
    }

    /**
     * Function to fetch number of programs allowed to be added for an organization
     * @parameters: 
     * @return: number of programs
     */
    public function num_of_progs() {
	$get_sanp = $this->pgm_model->get_sanp_details();
	$num_progs = $this->pgm_model->get_num_progs();

	if ($get_sanp >= $num_progs) {
	    echo 1;
	} else {
	    echo 0;
	}
    }

    /**
     *  Function is to check the user logged in user and load the program add view page through controller.
     *  @param - ------.
     * Loads the program add view page.
     */
    public function prgram_add_view() {
	if (!$this->ion_auth->logged_in()) {
	    //redirect them to the login page
	    redirect('login', 'refresh');
	} elseif (!$this->ion_auth->is_admin() && !($this->ion_auth->has_permission('Create Program'))) {
	    //redirect them to the home page because they must be an administrator to view this
	    redirect('configuration/users/blank', 'refresh');
	} else {
	    $result = $this->pgm_model->program_fetch_data();
	    $data['course_type'] = $this->pgm_model->course_list(); //Fetches course type from db
	    $data['pgmtype_name_data'] = $result['pgmtype_name_data'];
	    $data['mode_name_data'] = $result['mode_name_data'];
	    $data['pgm_acronym_data'] = $result['pgm_acronym_data'];
	    $data['total_terms_data'] = $result['total_terms_data'];
	    $data['total_topic_unit_data'] = $result['total_topic_unit_data'];
	    $data['dept_name_data'] = $result['dept_name_data'];
	    $data['username_data'] = $result['username_data'];
	    $data['unit_name_data'] = $result['unit_name_data'];

	    $data['specialization'] = array(
		'name' => 'specialization',
		'id' => 'specialization',
		'class' => 'required noSpecialChars span9',
		'type' => 'text',
		'onblur' => 'pgm_title()',
		'placeholder' => 'Enter Specialization',
		'size' => '20');

	    $data['specialization_acronym'] = array(
		'name' => 'specialization_acronym',
		'id' => 'specialization_acronym',
		'class' => 'required noSpecialChars span9',
		'type' => 'text',
		'onblur' => 'program_acronym_fun()',
		'placeholder' => 'Enter Acronym',
		'size' => '20');

	    $data['pgm_title_first'] = array(
		'name' => 'pgm_title_first',
		'id' => 'pgm_title_first',
		'class' => 'required noSpecialChars span6',
		'type' => 'text',
		'size' => '1',
		'placeholder' => 'Enter Title',
		'onblur' => 'pgm_title()');

	    $data['pgm_acronym_first'] = array(
		'name' => 'pgm_acronym_first',
		'id' => 'pgm_acronym_first',
		'class' => 'required noSpecialChars span6',
		'type' => 'text',
		'size' => '1',
		'placeholder' => 'Enter Shortname');

	    $data['pgm_max_duration'] = array(
		'name' => 'pgm_max_duration',
		'id' => 'pgm_max_duration',
		'class' => 'required onlyDigit rightJustified span7 duration_validation',
		'type' => 'text',
		'placeholder' => 'Enter Duration',
		'size' => '1');

	    $data['pgm_min_duration'] = array(
		'name' => 'pgm_min_duration',
		'id' => 'pgm_min_duration',
		'class' => 'required onlyDigit duration rightJustified span7 duration_validation',
		'type' => 'text',
		'placeholder' => 'Enter Duration',
		'size' => '1');

	    $data['total_no_terms'] = array(
		'name' => 'total_no_terms',
		'id' => 'total_no_terms',
		'class' => 'required onlyDigit leftJustified span9',
		'type' => 'text',
		'placeholder' => 'Enter Total Terms',
		'size' => '1');

	    $data['terms_min_credits'] = array(
		'name' => 'terms_min_credits',
		'id' => 'terms_min_credits',
		'class' => 'required leftJustified span9 onlyDigit maxlengthCheck credits_validation_min',
		'type' => 'text',
		'placeholder' => 'Enter Credits',
		'size' => '1');

	    $data['term_max_credits'] = array(
		'name' => 'term_max_credits',
		'id' => 'term_max_credits',
		'class' => 'required onlyDigit maxlengthCheck leftJustified span9 credits_validation maxlength credits_validation_max',
		'type' => 'text',
		'placeholder' => 'Enter Credits',
		'size' => '1');

	    $data['term_min_duration'] = array(
		'name' => 'term_min_duration',
		'id' => 'term_min_duration',
		'class' => 'required  onlyDigit duration rightJustified span7 duration_validation1 ',
		'type' => 'text',
		'placeholder' => 'Enter Duration',
		'size' => '1');

	    $data['term_max_duration'] = array(
		'name' => 'term_max_duration',
		'id' => 'term_max_duration',
		'class' => 'required onlyDigit duration rightJustified span7 duration_validation1',
		'type' => 'text',
		'placeholder' => 'Enter Duration',
		'size' => '1');

	    $data['total_no_credits'] = array(
		'name' => 'total_no_credits',
		'id' => 'total_no_credits',
		'class' => 'required onlyDigit leftJustified span9 maxlengthCheck',
		'type' => 'text',
		'placeholder' => 'Enter Total Credits',
		'size' => '1');

	    $data['title'] = "Program Add Page";
	    $this->load->view('configuration/program/add_program_vw', $data);
	}
    }

    /**
     *  Function is to check the user logged in user and adds the program details into the database.
     *  @param - ------.
     *  returns the updated program list view.
     */
    public function add_program() {
	if (!$this->ion_auth->logged_in()) {
	    //redirect them to the login page
	    redirect('login', 'refresh');
	} elseif (!$this->ion_auth->is_admin()) {
	    //redirect them to the home page because they must be an administrator to view this
	    redirect('configuration/users/blank', 'refresh');
	} else {
	    $pgm_title = preg_replace('/\s+/', ' ', $this->input->post('pgm_title_first') . ' ' . $this->input->post('pgm_title_last'));
	    $pgm_acronym = preg_replace('/\s+/', ' ', $this->input->post('pgm_acronym_first') . ' ' . $this->input->post('pgm_acronym_last'));
	    $program_details = array(
		'pgm_id' => $this->input->post('pgm_id'),
		'pgm_specialization' => $this->input->post('specialization'),
		'pgm_title' => $pgm_title,
		'pgm_spec_acronym' => $this->input->post('specialization_acronym'),
		'pgm_acronym' => $pgm_acronym,
		'pgm_min_duration' => $this->input->post('pgm_min_duration'),
		'pgm_max_duration' => $this->input->post('pgm_max_duration'),
		'total_terms' => $this->input->post('total_no_terms'),
		'total_topic_units' => $this->input->post('total_topic_units'),
		'total_credits' => $this->input->post('total_no_credits'),
		'term_min_credits' => $this->input->post('terms_min_credits'),
		'term_max_credits' => $this->input->post('term_max_credits'),
		'term_min_duration' => $this->input->post('term_min_duration'),
		'term_max_duration' => $this->input->post('term_max_duration'),
		'created_by' => $this->ion_auth->user()->row()->id,
		//'modified_by' 		=> $this->ion_auth->user()->row()->id,
		'create_date' => date('Y-m-d'),
		//'modify_date' 		=> date('Y-m-d'),
		'dept_id' => $this->input->post('department'),
		'pgmtype_id' => $this->input->post('pgm_type'),
		'mode_id' => $this->input->post('pgm_mode'),
		'max_unit_id' => $this->input->post('pgm_max_duration_unit'),
		'min_unit_id' => $this->input->post('pgm_min_duration_unit'),
		'term_min_unit_id' => $this->input->post('term_min_duration_unit'),
		'term_max_unit_id' => $this->input->post('term_max_duration_unit'),
		'status' => '1'
	    );
	    //------------------Course type weightage details in add program view-----------//
	    $cloneCntr = $this->input->post('stack_counter');
	    $counter = explode(',', $cloneCntr);
	    $size = sizeof($counter);
	    for ($k = 0; $k < $size; $k++) {
		$course_type_value[] = $this->input->post('course_type_value' . $counter[$k]);
	    }


	    $results = $this->pgm_model->prgram_data_insert($program_details, $course_type_value);

	    //email
	    $receiver_id = $results[0]['dept_hod_id'];
	    $cc = '';
	    $url = base_url('curriculum/curriculum/');
	    $curriculum_id = '';
	    $entity_id = 3;
	    $state = 2;

	    $additional_data['pgm_title'] = $program_details['pgm_title'];
	    $this->ion_auth->send_email($receiver_id, $cc, $url, $entity_id, $state, $curriculum_id, $additional_data);
	    redirect('configuration/program');
	}
    }

    /**
     *  Function is to check the user logged in user and to check program specialization existance in database.
     *  @param - ------.
     *  returns the 0 if program specialization exist else returns 1.
     */
    public function search_specialization() {
	if (!$this->ion_auth->logged_in()) {
	    //redirect them to the login page
	    redirect('login', 'refresh');
	} elseif (!$this->ion_auth->is_admin()) {
	    //redirect them to the home page because they must be an administrator to view this
	    redirect('configuration/users/blank', 'refresh');
	} else {
	    $pgm = $this->input->post('spec');
	    $result = $this->pgm_model->pgm_spec($pgm);

	    if ($result == 0) {
		echo "valid";
	    } else {
		echo "invalid";
	    }
	}
    }

    /**
     *  Function is to check the user logged in user and to update the program details.
     *  @param - program id used to fetch program details to update.
     *  returns the updated program list view.
     */
    public function program_edit($prgram_id, $flag = 0) {
	if (!$this->ion_auth->logged_in()) {
	    //redirect them to the login page
	    redirect('login', 'refresh');
	} elseif (!$this->ion_auth->is_admin() && !($this->ion_auth->has_permission('Update Program'))) {
	    //redirect them to the home page because they must be an administrator to view this
	    redirect('configuration/users/blank', 'refresh');
	} else {
	    if ($flag == 0) {
		//-----Course type weightage details------//
		$data['course_type_weightage'] = $this->pgm_model->course_type_weightage($prgram_id);
		$data['course_type'] = $this->pgm_model->course_list();
		$counter = array();
		$size = sizeof($data['course_type_weightage']);
		for ($k = 1; $k <= $size; $k++) {
		    $counter[] = $k;
		}
		$imploded_count = implode(',', $counter);
		$data['imp_count'] = $imploded_count;
		for ($i = 0; $i < count($data['course_type_weightage']); $i++) {
		    $course_id[$i] = $data['course_type_weightage'][$i]['course_type_id'];
		    $data['course_type_name'][$i] = $this->pgm_model->course_type_by_id($course_id[$i]);
		    $details = $this->pgm_model->fetch_course_type_details($course_id[$i]);
		    if ($details) {
			$data['crclm_comp_name'][$i] = $this->pgm_model->crclm_comp_name($details[0]['crclm_component_id']);
			$data['crs_type_desc'][$i] = $details[0]['crs_type_description'];
		    }
		}
		//--------------------//
		$data['notupdated'] = 0;
		$id = $prgram_id;
		$result = $this->pgm_model->edit_pgms($prgram_id);
		$data['program_data'] = $result['program_data'];
		$data['pgm_type_data'] = $result['pgm_type_data'];
		$data['pgm_mode_data'] = $result['pgm_mode_data'];
		$data['pgm_dept_data'] = $result['pgm_dept_data'];
		$data['total_topic_unit_data'] = $result['total_topic_unit_data'];
		$data['pgm_unit_min_data'] = $result['pgm_unit_min_data'];
		$data['pgm_unit_max_data'] = $result['pgm_unit_max_data'];
		$data['pgm_id'] = $prgram_id;
		$data['selected_pgmtype'] = $result['program_data']['0']['pgmtype_id'];
		$data['selected_mode'] = $result['program_data']['0']['mode_id'];
		$data['selected_dept'] = $result['program_data']['0']['dept_id'];
		$data['selected_pgm_min_duration'] = $result['program_data']['0']['min_unit_id'];
		$data['selected_pgm_max_duration'] = $result['program_data']['0']['max_unit_id'];
		$data['selected_term_min'] = $result['program_data']['0']['term_min_unit_id'];
		$data['selected_term_max'] = $result['program_data']['0']['term_max_unit_id'];
		$data['selected_topic_unit'] = $result['program_data']['0']['total_topic_units'];
		$data['pgm_current_id'] = $prgram_id;

		$data['specialization'] = array(
		    'name' => 'specialization',
		    'id' => 'specialization',
		    'class' => 'required noSpecialChars span9',
		    'type' => 'text',
		    'onblur' => 'pgm_title()',
		    'placeholder' => 'Enter Specialization',
		    'size' => '20',
		    'value' => $data['program_data'][0]['pgm_specialization']);

		$data['specialization_acronym'] = array(
		    'name' => 'specialization_acronym',
		    'id' => 'specialization_acronym',
		    'class' => 'required noSpecialChars span9',
		    'type' => 'text',
		    'onblur' => 'program_acronym_fun()',
		    'placeholder' => 'Enter Acronym',
		    'size' => '20',
		    'value' => $data['program_data'][0]['pgm_spec_acronym']);
		$pgm_title_data = (explode(" in ", $data['program_data'][0]['pgm_title']));
		$data['pgm_title_first'] = array(
		    'name' => 'pgm_title_first',
		    'id' => 'pgm_title_first',
		    'class' => 'required noSpecialChars span6',
		    'type' => 'text',
		    'size' => '1',
		    'placeholder' => 'Enter Title',
		    'onblur' => 'pgm_title()',
		    'value' => $pgm_title_data[0]);

		$pgm_acronym_data = (explode(" in ", $data['program_data'][0]['pgm_acronym']));
		$data['pgm_acronym_first'] = array(
		    'name' => 'pgm_acronym_first',
		    'id' => 'pgm_acronym_first',
		    'class' => 'required noSpecialChars span6',
		    'type' => 'text',
		    'size' => '1',
		    'placeholder' => 'Enter Shortname',
		    'value' => $pgm_acronym_data[0]);

		$data['pgm_max_duration'] = array(
		    'name' => 'pgm_max_duration',
		    'id' => 'pgm_max_duration',
		    'class' => 'required onlyDigit rightJustified span6 duration_validation',
		    'type' => 'text',
		    'placeholder' => 'Enter Duration',
		    'size' => '1',
		    'value' => $data['program_data'][0]['pgm_max_duration']);

		$data['pgm_min_duration'] = array(
		    'name' => 'pgm_min_duration',
		    'id' => 'pgm_min_duration',
		    'class' => 'required onlyDigit duration rightJustified span6 duration_validation',
		    'type' => 'text',
		    'placeholder' => 'Enter Duration',
		    'size' => '1',
		    'value' => $data['program_data'][0]['pgm_min_duration']);

		$data['total_no_terms'] = array(
		    'name' => 'total_no_terms',
		    'id' => 'total_no_terms',
		    'class' => 'required onlyDigit leftJustified span9',
		    'type' => 'text',
		    'placeholder' => 'Enter Total Terms',
		    'size' => '1',
		    'value' => $data['program_data'][0]['total_terms']);

		$data['term_max_credits'] = array(
		    'name' => 'term_max_credits',
		    'id' => 'term_max_credits',
		    'class' => 'required onlyDigit leftJustified span9 credits_validation',
		    'type' => 'text',
		    'placeholder' => 'Enter Credits',
		    'size' => '1',
		    'value' => $data['program_data'][0]['term_max_credits']);

		$data['term_min_credits'] = array(
		    'name' => 'term_min_credits',
		    'id' => 'term_min_credits',
		    'class' => 'required onlyDigit leftJustified span9',
		    'type' => 'text',
		    'placeholder' => 'Enter Credits',
		    'size' => '1',
		    'value' => $data['program_data'][0]['term_min_credits']);

		$data['term_min_duration'] = array(
		    'name' => 'term_min_duration',
		    'id' => 'term_min_duration',
		    'class' => 'required onlyDigit duration rightJustified span6 duration_validation1',
		    'type' => 'text',
		    'placeholder' => 'Enter Duration',
		    'size' => '1',
		    'value' => $data['program_data'][0]['term_min_duration']);

		$data['term_max_duration'] = array(
		    'name' => 'term_max_duration',
		    'id' => 'term_max_duration',
		    'class' => 'required onlyDigit duration rightJustified span6 duration_validation1',
		    'type' => 'text',
		    'placeholder' => 'Enter Duration',
		    'size' => '1',
		    'value' => $data['program_data'][0]['term_max_duration']);

		$data['total_no_credits'] = array(
		    'name' => 'total_no_credits',
		    'id' => 'total_no_credits',
		    'class' => 'required onlyDigit leftJustified span9',
		    'type' => 'text',
		    'placeholder' => 'Enter Total Credits',
		    'value' => $data['program_data'][0]['total_credits'],
		    'size' => '1');


		$data['pgm_title_last'] = array(
		    'name' => 'pgm_title_last',
		    'id' => 'pgm_title_last',
		    'class' => 'required noSpecialChars span5',
		    'type' => 'text',
		    'value' => $pgm_title_data[1],
		    'size' => '20');

		$data['title'] = "Program Edit Page";
		$this->load->view('configuration/program/edit_program_vw', $data);
	    } else {
		//update database when update button is clicked
		$program_id = $this->input->post('pgm_id');  //returns total number of table rows
		$cloneCntr = $this->input->post('stack_counter');  //returns index values of table row
		$cnt = explode(',', $cloneCntr); //explodes stack_counter				

		$pgm_title = preg_replace('/\s+/', ' ', $this->input->post('pgm_title_first') . ' ' . $this->input->post('pgm_title_last'));
		$pgm_acronym = preg_replace('/\s+/', ' ', $this->input->post('pgm_acronym_first') . ' ' . $this->input->post('pgm_acronym_last'));

		$program_update_details = array(
		    'pgm_id' => $program_id,
		    'pgm_specialization' => $this->input->post('specialization'),
		    'pgm_title' => $pgm_title,
		    'pgm_spec_acronym' => $this->input->post('specialization_acronym'),
		    'pgm_acronym' => $pgm_acronym,
		    'pgm_min_duration' => $this->input->post('pgm_min_duration'),
		    'pgm_max_duration' => $this->input->post('pgm_max_duration'),
		    'total_terms' => $this->input->post('total_no_terms'),
		    'total_topic_units' => $this->input->post('total_topic_units'),
		    'total_credits' => $this->input->post('total_no_credits'),
		    'term_min_credits' => $this->input->post('term_min_credits'),
		    'term_max_credits' => $this->input->post('term_max_credits'),
		    'term_min_duration' => $this->input->post('term_min_duration'),
		    'term_max_duration' => $this->input->post('term_max_duration'),
		    //'created_by' 			=> $this->ion_auth->user()->row()->id,
		    'modified_by' => $this->ion_auth->user()->row()->id,
		    //'create_date' 			=> date('Y-m-d'),
		    'modify_date' => date('Y-m-d'),
		    'dept_id' => $this->input->post('dept_id'),
		    'pgmtype_id' => $this->input->post('type_id'),
		    'mode_id' => $this->input->post('pgm_mode'),
		    'max_unit_id' => $this->input->post('pgm_max_duration_unit'),
		    'min_unit_id' => $this->input->post('pgm_min_duration_unit'),
		    'term_min_unit_id' => $this->input->post('term_min_duration_unit'),
		    'term_max_unit_id' => $this->input->post('term_max_duration_unit'),
		    'status' => '1'
		);

		$pgm_title_fr_mail = $program_update_details['pgm_title'];

		$size = sizeof($cnt);
		for ($k = 0; $k < $size; $k++) {
		    $course_type_value[] = $this->input->post('course_type_value' . $cnt[$k]);
		}

		$results = $this->pgm_model->program_update_insert($program_update_details, $program_id, $course_type_value);

		//email
		$receiver_id = $results[0]['dept_hod_id'];
		$cc = '';
		$url = base_url('configuration/program/');
		$entity_id = 3;
		$state = 3;
		$curriculum = '';
		$addtional_data['pgm_title'] = $pgm_title_fr_mail;

		$email_result = $this->ion_auth->send_email($receiver_id, $cc, $url, $entity_id, $state, $curriculum, $addtional_data);
		redirect('configuration/program');
	    }
	}
    }

    public function static_program_edit($prgram_id) {
	if (!$this->ion_auth->logged_in()) {
	    //redirect them to the login page
	    redirect('login', 'refresh');
	} else {
	    $data['notupdated'] = 0;
	    $id = $prgram_id;
	    $result = $this->pgm_model->edit_pgms($prgram_id);
	    $data['program_data'] = $result['program_data'];
	    $data['pgm_type_data'] = $result['pgm_type_data'];
	    $data['pgm_mode_data'] = $result['pgm_mode_data'];
	    $data['pgm_dept_data'] = $result['pgm_dept_data'];
	    $data['pgm_unit_min_data'] = $result['pgm_unit_min_data'];
	    $data['pgm_unit_max_data'] = $result['pgm_unit_max_data'];
	    $data['pgm_id'] = $prgram_id;
	    $data['selected_pgmtype'] = $result['program_data']['0']['pgmtype_id'];
	    $data['selected_mode'] = $result['program_data']['0']['mode_id'];
	    $data['selected_dept'] = $result['program_data']['0']['dept_id'];
	    $data['selected_pgm_min_duration'] = $result['program_data']['0']['min_unit_id'];
	    $data['selected_pgm_max_duration'] = $result['program_data']['0']['max_unit_id'];
	    $data['selected_term_min'] = $result['program_data']['0']['term_min_unit_id'];
	    $data['selected_term_max'] = $result['program_data']['0']['term_max_unit_id'];
	    $data['pgm_current_id'] = $prgram_id;

	    $data['specialization'] = array(
		'name' => 'specialization',
		'id' => 'specialization',
		'class' => 'required noSpecialChars span9',
		'type' => 'text',
		'readonly' => 'readonly',
		'onblur' => 'pgm_title()',
		'placeholder' => 'Enter Specialization',
		'size' => '20',
		'value' => $data['program_data'][0]['pgm_specialization']);

	    $data['specialization_acronym'] = array(
		'name' => 'specialization_acronym',
		'id' => 'specialization_acronym',
		'class' => 'required noSpecialChars span9',
		'type' => 'text',
		'readonly' => 'readonly',
		'onblur' => 'program_acronym_fun()',
		'placeholder' => 'Enter Acronym',
		'size' => '20',
		'value' => $data['program_data'][0]['pgm_spec_acronym']);
	    $pgm_title_data = (explode(" in ", $data['program_data'][0]['pgm_title']));
	    $data['pgm_title_first'] = array(
		'name' => 'pgm_title_first',
		'id' => 'pgm_title_first',
		'class' => 'required noSpecialChars span9',
		'type' => 'text',
		'readonly' => 'readonly',
		'size' => '1',
		'placeholder' => 'Enter Title',
		'onblur' => 'pgm_title()',
		'value' => $pgm_title_data[0]);
	    $pgm_acronym_data = (explode(" in ", $data['program_data'][0]['pgm_acronym']));
	    $data['pgm_acronym_first'] = array(
		'name' => 'pgm_acronym_first',
		'id' => 'pgm_acronym_first',
		'class' => 'required noSpecialChars span9',
		'type' => 'text',
		'readonly' => 'readonly',
		'size' => '1',
		'placeholder' => 'Enter Shortname',
		'value' => $pgm_acronym_data[0]);

	    $data['pgm_max_duration'] = array(
		'name' => 'pgm_max_duration',
		'id' => 'pgm_max_duration',
		'class' => 'required onlyDigit rightJustified span5',
		'type' => 'text',
		'readonly' => 'readonly',
		'placeholder' => 'Enter Duration',
		'size' => '1',
		'value' => $data['program_data'][0]['pgm_max_duration']);

	    $data['pgm_min_duration'] = array(
		'name' => 'pgm_min_duration',
		'id' => 'pgm_min_duration',
		'class' => 'required onlyDigit duration rightJustified span5',
		'type' => 'text',
		'readonly' => 'readonly',
		'placeholder' => 'Enter Duration',
		'size' => '1',
		'value' => $data['program_data'][0]['pgm_min_duration']);

	    $data['total_no_terms'] = array(
		'name' => 'total_no_terms',
		'id' => 'total_no_terms',
		'class' => 'required onlyDigit rightJustified span6',
		'type' => 'text',
		'readonly' => 'readonly',
		'placeholder' => 'Enter Total Terms',
		'size' => '1',
		'value' => $data['program_data'][0]['total_terms']);

	    $data['term_max_credits'] = array(
		'name' => 'term_max_credits',
		'id' => 'term_max_credits',
		'class' => 'required onlyDigit rightJustified span5',
		'type' => 'text',
		'readonly' => 'readonly',
		'placeholder' => 'Enter Credits',
		'size' => '1',
		'value' => $data['program_data'][0]['term_min_credits']);

	    $data['terms_min_credits'] = array(
		'name' => 'terms_min_credits',
		'id' => 'terms_min_credits',
		'class' => 'required onlyDigit rightJustified span5',
		'type' => 'text',
		'readonly' => 'readonly',
		'placeholder' => 'Enter Credits',
		'size' => '1',
		'value' => $data['program_data'][0]['term_max_credits']);

	    $data['term_min_duration'] = array(
		'name' => 'term_min_duration',
		'id' => 'term_min_duration',
		'class' => 'required onlyDigit duration rightJustified span5',
		'type' => 'text',
		'readonly' => 'readonly',
		'placeholder' => 'Enter Duration',
		'size' => '1',
		'value' => $data['program_data'][0]['term_min_duration']);

	    $data['term_max_duration'] = array(
		'name' => 'term_max_duration',
		'id' => 'term_max_duration',
		'class' => 'required onlyDigit duration rightJustified span5',
		'type' => 'text',
		'readonly' => 'readonly',
		'placeholder' => 'Enter Duration',
		'size' => '1',
		'value' => $data['program_data'][0]['term_max_duration']);

	    $data['total_no_credits'] = array(
		'name' => 'total_no_credits',
		'id' => 'total_no_credits',
		'class' => 'required onlyDigit rightJustified span6',
		'type' => 'text',
		'readonly' => 'readonly',
		'placeholder' => 'Enter Total Credits',
		'value' => $data['program_data'][0]['total_credits'],
		'size' => '1');


	    $data['pgm_title_last'] = array(
		'name' => 'pgm_title_last',
		'id' => 'pgm_title_last',
		'class' => 'required noSpecialChars span5',
		'type' => 'text',
		'readonly' => 'readonly',
		'value' => $data['program_data'][0]['pgm_specialization'],
		'size' => '20');


	    $data['title'] = "Program Edit Page";
	    $this->load->view('configuration/program/static_edit_program_vw', $data);
	}
    }

    /**

     *  Function is to check the user logged in user and to load the program list view.
     *  @param - ------.
     *  returns program list view.
     */
    public function static_index() {
	if (!$this->ion_auth->logged_in()) {
	    //redirect them to the login page
	    redirect('login', 'refresh');
	} else {
	    $limit = 3;
	    $program_list_result = $this->pgm_model->program_list();
	    $data['pgm_list_data_result'] = $program_list_result['pgm_list_data'];
	    $data['num_rows'] = $program_list_result['num_rows'];
	    $data['search'] = '0';

	    $data['title'] = "Program List Page";
	    $this->load->view('configuration/program/static_list_program_vw', $data);
	}
    }

    /**
     *  Function is to check the user logged in user and to set the program status to active and inactive.
     *  @param - ------.
     *  returns program status active or inactive.
     */
    public function pgm_status() {
	if (!$this->ion_auth->logged_in()) {
	    //redirect them to the login page
	    redirect('login', 'refresh');
	} elseif (!$this->ion_auth->is_admin() && !($this->ion_auth->has_permission('Enable/Disable Program'))) {
	    //redirect them to the home page because they must be an administrator to view this
	    redirect('configuration/users/blank', 'refresh');
	} else {
	    $pgm_id = $this->input->post('pgm_id');
	    $status = $this->input->post('status');
	    $result = $this->pgm_model->pgm_status($pgm_id, $status);
	    return true;
	}
    }

    /**
     *  Function is to check the user logged in user and to check the current curriculums running under the particular program.
     *  @param - program id used to check the related curriculums.
     *  returns running curriculums count under the program.
     */
    public function check_for_curriculum() {
	if (!$this->ion_auth->logged_in()) {
	    //redirect them to the login page
	    redirect('login', 'refresh');
	} elseif (!$this->ion_auth->is_admin()) {
	    //redirect them to the home page because they must be an administrator to view this
	    redirect('configuration/users/blank', 'refresh');
	} else {
	    $pgm_id = $this->input->post('pgm_id');
	    $results = $this->pgm_model->curriculum_search($pgm_id);

	    if ($results == 0) {
		echo 'valid';
		exit;
	    } else {
		echo 'invalid';
	    }
	}
    }

    /*
     *  Function is to Add table row with dropdown for course type and text boxes for cia,tee,occasions
      @param - counter and stack_counter to check number of table rows .
     */

    public function add_more_tr() {
	if (!$this->ion_auth->logged_in()) {
	    //redirect them to the login page
	    redirect('login', 'refresh');
	} elseif (!$this->ion_auth->is_admin()) {
	    //redirect them to the home page because they must be an administrator to view this
	    redirect('configuration/users/blank', 'refresh');
	} else {
	    $counter = $this->input->post('counter');
	    $stack_counter = $this->input->post('stack_counter');
	    ++$counter;
	    $add_more = '';
	    $course_type = $this->pgm_model->course_list();
	    $count = count($course_type);
	    $add_more.= "<tr class='course_type_del'><td name='crclm_comp" . $counter . "' id='crclm_comp" . $counter . "' style='text-align:center'> </td><td><select class='required crs_type progRegex ' id='course_type_value" . $counter . "' name='course_type_value" . $counter . "' onchange='select_details(this.value,$counter);'><option value=''>Select Course Type</option>";
	    for ($i = 0; $i < $count; $i++) {
		$add_more.="<option value=" . $course_type[$i]['crs_type_id'] . ">" . ucfirst($course_type[$i]['crs_type_name']) . "</option>";
	    }

	    $add_more.="</select><br/><span style='position: relative;left: 5px; color:red;' id='error_msg" . $counter . "'></span>";
	    $add_more.="<td name=crs_type_desc" . $counter . "' id='crs_type_desc" . $counter . "'></td><td>";
	    if ($counter == 1) {
		$add_more.="</td>";
	    } else {
		$add_more.="<a id='remove_field" . $counter . "' class=Delete ><i class='icon-remove' id='icon-remove'></i></a>";
	    }
	    $add_more.="</td></tr>";
	    echo $add_more;
	}
    }

    /*
     *  Function is to delete course type from database.
     *  @param - prog_id and course id used to check the related course type.
     */

    public function delete_course_type() {
	if (!$this->ion_auth->logged_in()) {
	    //redirect them to the login page
	    redirect('login', 'refresh');
	} elseif (!$this->ion_auth->is_admin()) {
	    //redirect them to the home page because they must be an administrator to view this
	    redirect('configuration/users/blank', 'refresh');
	} else {
	    $prog_id = $this->input->post('prog_id');
	    $course_id = $this->input->post('course_id');
	    if ($course_id != 0) {
		$deleted_course = $this->pgm_model->delete_course_type($prog_id, $course_id);
	    }
	}
    }

    /*
     *  Function is to fetch details for selected course type from database.
     *  @param -course type id used to get details.
     */

    public function fetch_course_type_details() {
	$value = $this->input->post('value');
	$details = $this->pgm_model->fetch_course_type_details($value);
	if ($details) {
	    $crclm_comp_name = $this->pgm_model->crclm_comp_name($details[0]['crclm_component_id']);
	    $crs_type_desc = $details[0]['crs_type_description'];
	    $result = $crclm_comp_name . ',' . $crs_type_desc;
	    echo $result;
	}
    }

}

/* End of file form.php */
/* Location: ./application/controllers/form.php */
?>
