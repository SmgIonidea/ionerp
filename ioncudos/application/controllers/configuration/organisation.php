<?php

/**
 * Description	:	Organization allows the admin to add or edit the content of the
  login page. Organization makes use of tinymce for editing the content
  of the login page.
 * 					
 * Created		:	25-03-2013
 *
 * Author		:	
 * 		  
 * Modification History:
 *   Date                Modified By                			Description
 * 20-08-2013		   Arihant Prasad			File header, function headers, indentation 
  and comments.
 *
  ------------------------------------------------------------------------------------------ */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Organisation extends CI_Controller {

    function __construct() {
	parent::__construct();
	$this->load->model('configuration/organisation/organisation_model');
	$this->load->model('login/login_model');
    }

    /* Function is to check for the authentication and pass the control to the update_organisation() function
     * for fetching organization details */

    public function index($organization_id = 1) {
	//##permission_start
	if (!$this->ion_auth->logged_in()) {
	    //redirect them to the login page
	    redirect('login', 'refresh');
	} else if (!$this->ion_auth->is_admin()) {
	    //redirect them to the home page because they must be an administrator to view this
	    redirect('configuration/users/blank', 'refresh');
	}    //##permission_end
	else {
	    $user_id = $this->ion_auth->user()->row()->id;
	    $org_name_data = $this->organisation_model->data_disable($user_id);
	    $organisation_detail = $this->organisation_model->get_organisation_by_id($organization_id);
	    $mission_elements = $this->organisation_model->get_mission_elements($organization_id);
	    //$data['missions'] = $mission_elements;
	    //var_dump($mission_elements);exit;
	    if ($organisation_detail == null && $mission_elements == null) {
		$this->data['org_id'] = 1;
		$this->data['org_society'] = "Enter Society Name";
		$this->data['org_name'] = "Enter Organization Name";
		$this->data['org_desc'] = "Enter Organization Description";
		$this->data['vision'] = "Enter Vision";
		$this->data['mandate'] = "Enter Mandate";
		$this->data['mission'] = "Enter Mission";
		$this->data['mission_element'] = "Enter Mission Element";
		$this->data['title'] = "Organization Edit Page";

		$this->load->view('configuration/organisation/edit_organisation_vw', $this->data);
	    } else {
		$this->data['user_data'] = $org_name_data;
		$org_id = 1;
		$this->data['org_id'] = array(
		    'name' => 'org_id',
		    'id' => 'org_id',
		    'class' => 'required',
		    'type' => 'hidden',
		    'value' => $organization_id
		);
		$me = $this->organisation_model->get_mission_elements($org_id);
		$size = sizeof($me);
		$cnt = array();
		for ($i = 0; $i < $size; $i++) {
		    $cnt[$i] = $i + 1;
		}
		$counter = sizeof($cnt);
		$this->data['list'] = array(
		    'name' => 'mission_element_counter',
		    'id' => 'mission_element_counter',
		    'class' => '',
		    'type' => 'hidden',
		    'value' => implode(",", $cnt));

		$this->data['mission_counter_val'] = array(
		    'name' => 'mission_counter',
		    'id' => 'mission_counter',
		    'class' => '',
		    'type' => 'hidden',
		    'value' => $counter
		);
		$this->data['org_society'] = array(
		    'name' => 'org_society',
		    'id' => 'org_society',
		    'class' => 'span7',
		    'placeholder' => 'Enter Society Name',
		    'type' => 'text',
		    'value' => $organisation_detail[0]['org_society'],
		    'autofocus' => 'autofocus'
		);
		$this->data['org_name'] = array(
		    'name' => 'org_name',
		    'id' => 'org_name',
		    'class' => 'span7',
		    'placeholder' => 'Enter Organization Name',
		    'type' => 'text',
		    'value' => $organisation_detail[0]['org_name']
		);
		$this->data['org_desc'] = array(
		    'name' => 'org_desc',
		    'id' => 'org_desc',
		    'class' => 'myTextEditor input-xxlarge required',
		    'type' => 'text',
		    'rows' => '15',
		    'cols' => '600',
		    'value' => $organisation_detail[0]['org_desc']
		);

		$this->data['vision'] = array(
		    'name' => 'vision',
		    'id' => 'vision',
		    'class' => 'org_text_size',
		    'cols' => 50,
		    'rows' => 2,
		    'type' => 'text',
		    'value' => $organisation_detail[0]['vision']
		);
		$this->data['mandate'] = array(
		    'name' => 'mandate',
		    'id' => 'mandate',
		    'class' => 'org_text_size',
		    'cols' => 50,
		    'rows' => 2,
		    'type' => 'text',
		    'value' => $organisation_detail[0]['mandate']
		);
		$this->data['mission'] = array(
		    'name' => 'mission',
		    'id' => 'mission',
		    'class' => 'org_text_size',
		    'cols' => 50,
		    'rows' => 2,
		    'type' => 'text',
		    'value' => $organisation_detail[0]['mission']
		);

		$this->data['mission_element'] = array(
		    'name' => 'mission_element_1',
		    'id' => 'mission_element_1',
		    'placeholder' => 'Enter mission element',
		    'class' => 'noSpecialChars input-xxlarge org_me_text_size',
		    'cols' => 80,
		    'rows' => 2,
		    'type' => 'text',
		    'style' => 'height: 60px;'
		);
		$this->data['org_type_options'] = array(
		    'TIER-I' => 'TIER-I',
		    'TIER-II' => 'TIER-II',
		);

		$this->data['org_selected_type'] = $organisation_detail[0]['org_type'];

		$this->data['comp_pi_options'] = array(
		    '0' => 'Optional',
		    '1' => 'Mandatory',
		);	
		$this->data['mte_options'] = array(
		    '0' => 'Optional',
		    '1' => 'Mandatory',
		);

		$this->data['comp_pi_selected'] = $organisation_detail[0]['oe_pi_flag'];
		$this->data['mte_flag_selected'] = $organisation_detail[0]['mte_flag'];
		$this->data['ind_map_just_selected'] = $organisation_detail[0]['indv_mapping_justify_flag'];


		$this->data['missions'] = $mission_elements;
		$this->data['title'] = "Organization Edit Page";
		$this->load->view('configuration/organisation/edit_organisation_vw', $this->data);
	    }
	}
    }

    /**
     * All guest users will be redirected to this
     * function when they login.
     */
    public function org_static_index($organization_id = 1) {
	//##permission_start
	if (!$this->ion_auth->logged_in()) {
	    //redirect them to the login page
	    redirect('login', 'refresh');
	}

	//##permission_end
	else {
	    $organisation_detail = $this->organisation_model->get_organisation_by_id($organization_id);

	    $this->data['org_id'] = array(
		'name' => 'org_id',
		'id' => 'org_id',
		'class' => 'required',
		'type' => 'hidden',
		'value' => $organization_id,
	    );
	    $this->data['org_society'] = array(
		'name' => 'org_society',
		'id' => 'org_society',
		'class' => 'required',
		'readonly' => '',
		'type' => 'text',
		'value' => $organisation_detail[0]['org_society'],
	    );
	    $this->data['org_name'] = array(
		'name' => 'org_name',
		'id' => 'org_name',
		'class' => 'required',
		'readonly' => '',
		'type' => 'text',
		'value' => $organisation_detail[0]['org_name'],
	    );
	    $this->data['org_desc'] = array(
		'name' => 'org_desc',
		'id' => 'org_desc',
		'class' => 'input-xxlarge required',
		'type' => 'text',
		'rows' => '15',
		'cols' => '700',
		'readonly' => '',
		'value' => $organisation_detail[0]['org_desc'],
	    );

	    $this->data['title'] = "Organization List Page";
	    $this->load->view('configuration/organisation/static_organisation_vw', $this->data);
	}
    }

    // Function is to check for the authentication and pass the control to the update_organisation() function
    public function update_organisation_details() {
	if (!$this->ion_auth->logged_in()) {
	    //redirect them to the login page
	    redirect('login', 'refresh');
	} else if (!$this->ion_auth->is_admin()) {
	    //redirect them to the home page because they must be an administrator to view this
	    redirect('configuration/users/blank', 'refresh');
	} else {
	    $mission_counter = $this->input->post('mission_element_counter');
	    $mission_ele_counter = explode(",", $mission_counter);
	    $mission_ele_size = sizeof($mission_ele_counter);

	    $org_id = $this->input->post('org_id');
	    $org_name = $this->input->post('org_name');
	    $org_society = $this->input->post('org_society');
	    $org_desc = $this->input->post('org_desc');
	    $vision = $this->input->post('vision');
	    $mandate = $this->input->post('mandate');
	    $mission = $this->input->post('mission');
	    $org_type = $this->input->post('org_type');
	    $oe_pi = $this->input->post('oe_pi'); 
		$mte_flag = $this->input->post('mte_flag');
	    $indv_map_just = $this->input->post('indv_map_just');
	    for ($k = 0; $k < $mission_ele_size; $k++) {
		$mission_element[] = $this->input->post('mission_element_' . $mission_ele_counter[$k]);
	    }
	    //var_dump($mission_element);exit;
	    //organization id is always 1
	    $org_id = 1;

	    $is_added = $this->organisation_model->update_organisation($org_id, $org_society, $org_name, $org_desc, $vision, $mandate, $mission, $mission_element, $mission_ele_size, $org_type, $oe_pi, $indv_map_just , $mte_flag);
	    echo $is_added;
	}
    }

    // Function is to load the login_vw page
    public function preview_login_page() {
	$data['identity'] = array('name' => 'identity',
	    'id' => 'identity',
	    'type' => 'text',
	    'class' => 'input-block-level required',
	    'disabled' => 'disabled',
	    'value' => $this->form_validation->set_value('identity'),
	);
	$data['password'] = array('name' => 'password',
	    'id' => 'password',
	    'type' => 'password',
	    'class' => 'input-block-level required',
	    'disabled' => 'disabled',
	);

	//$this->load->model('login/login_model');
	$data['organisation_detail'] = $this->login_model->get_organisation_details(1);

	$data['title'] = "Login Page";
	$this->load->view('configuration/organisation/login_vw', $data);
    }

}

/*
 * End of file organisation.php
 * Location: .configuration/organisation.php 
 */
?>
