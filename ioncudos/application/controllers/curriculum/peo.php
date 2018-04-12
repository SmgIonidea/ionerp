<?php

/**
 * Description          :	PEO grid displays the PEO statements and PEO type. PEO statements can be deleted individually and can be edited in bulk.
  Notes can be added, edited or viewed.
  PEO statements will be published for final approval.
 * 					
 * Created		:	01-03-2013
 *
 * Author		:	
 * 		  
 * Modification History :
 *   Date                Modified By                			Description
 *  05-09-2013		Arihant Prasad                      File header, function headers, indentation and comments.
 *  18/01/2016		Bhagyalaxmi S S                     Modified Peo_list function and added update_peo function
 *  21-04-2016		Bhagyalaxmi S S 		    Added map_level weightage to the peo_me mapping 
 *  06-15-2015		Bhagyalaxmi S S                     Added justification for each mapping	
  ---------------------------------------------------------------------------------------------- */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Peo extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('curriculum/peo/peo_model');
        $this->load->model('curriculum/peo/add_peo_model');
    }

    /**
     * Function to check authentication of the logged in user and load program educational objective list page
     * @return: Program educational objective list page
     */
    public function index() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if ((!$this->ion_auth->is_admin()) && !($this->ion_auth->in_group('Chairman'))) {
            //redirect them to the home page because they must be an administrator or program owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            $curriculum_name = $this->peo_model->peo_curriculum_index();
            $data['results'] = $curriculum_name['result_curriculum_list'];

            $data['curriculum_id'] = array(
                'name' => 'crclm_id',
                'id' => 'crclm_id',
                'class' => 'required',
                'type' => 'hidden',
            );

            $data['title'] = "PEO List Page";
            $this->load->view('curriculum/peo/list_peo_vw', $data);
        }
    }

    /**
     * Function to fetch curriculum details for the guest user
     * @return: load static program educational objective list page
     */
    public function static_index() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            $curriculum_name = $this->peo_model->peo_curriculum_index();
            $data['results'] = $curriculum_name['result_curriculum_list'];

            $data['curriculum_id'] = array(
                'name' => 'crclm_id',
                'id' => 'crclm_id',
                'class' => 'required',
                'type' => 'hidden',
            );

            $data['title'] = "PEO List Page";
            $this->load->view('curriculum/peo/static_list_peo_vw', $data);
        }
    }

    /**
     * Function to fetch program educational objective details to display in the grid
     * @return: an object
     */
    public function peo_list() {
        $curriculum_id = $this->input->post('curriculum_id');
        $peo_list_result = $this->peo_model->peo_list($curriculum_id);
        $data = $peo_list_result['peo_list_data'];

        $peo_state_data = $peo_list_result['peo_state'];

        $peo_list = array();
        $counter = 1;
        //if program educational objective statements are associated with any curriculum then they cannot be deleted
        if ($data != 0) {
            foreach ($data as $peo_data) {
                if ($peo_state_data[0]['state_id'] != 7) {
                    $peo_id = '<center><a href = "#myModal_delete" 
						   class = "icon-remove peo_del" 
						   data-toggle = "modal"  
						   data-original-title = "Delete" 
						   rel = "tooltip"
						   title = "Delete" 
						   value = "' . $peo_data['peo_id'] . '" 
						   abbr = "' . $peo_data['peo_id'] . '" 
						  </a></center>';
                } else {
                    $peo_id = '<center><a href = "#myModal_notification" class = "icon-remove" 
							data-toggle = "modal"  
							data-original-title = "Delete" 
							rel = "tooltip" 
							title = "PEOs Approved" 
							value = "' . $peo_data['peo_id'] . '">
						   </a></center>';
                }

                $peo_list['peo_list'][] = array(
                    'peo_reference' => $peo_data['peo_reference'],
                    'peo_statement' => $peo_data['peo_statement'],
                    'peo_id' => $peo_id,
                    'Edit' => '<center><a rel = "tooltip" title = "Edit PEO" class="icon-pencil cursor_pointer peo_edit" id="peo_edit" data-id="' . $peo_data['peo_id'] . '" data-state="' . htmlspecialchars($peo_data['peo_statement']) . '" data-state_name="' . $peo_data['peo_reference'] . '" data-attendees_name="' . $peo_data['attendees_name'] . '" data-attendees_notes="' . $peo_data['attendees_notes'] . '"></a></center>'
                );
                $counter++;
            }
            $peo_list['peo_state'] = array(
                'state_name' => $peo_state_data[0]['state_name'],
                'state' => $peo_state_data[0]['state']
            );
            echo json_encode($peo_list);
        } else {
            $peo_list['peo_list'][] = array(
                'peo_reference' => '',
                'peo_statement' => 'No data available',
                'peo_id' => '',
                'Edit' => ''
            );
            $peo_list['peo_state'] = array(
                'state_name' => '',
                'state' => 0
            );
            echo json_encode($peo_list);
        }
    }

    /**
     * Function to delete program educational objective details 
     * @return: boolean
     */
    function peo_delete() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if ((!$this->ion_auth->is_admin()) && !($this->ion_auth->in_group('Chairman'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            $peo_id = $this->input->post('peo_id');
            $this->peo_model->peo_delete($peo_id);
            redirect('/', 'refresh');
            return true;
        }
    }

    /**
     * Function to fetch help details related to program educational objectives
     * @return: an object
     */
    function peo_help() {

        $help_list = $this->peo_model->peo_help();

        if (!empty($help_list['help_data'])) {
            foreach ($help_list['help_data'] as $help) {
                $peo_id = '<i class="icon-black icon-file"> </i><a target="_blank" href="' . base_url('curriculum/peo/peo_content') . '/' . $help['serial_no'] . '">' . $help['entity_data'] . '</a></br>';
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
     * Function to display help related to program educational objective in a new page
     * @parameters: help id
     * @return: load help view page
     */
    public function peo_content($help_id) {
        $help_content = $this->peo_model->help_content($help_id);
        $help['help_content'] = $help_content;
        $data['title'] = "PEO List Page";
        $this->load->view('curriculum/peo/peo_help_vw', $help);
    }

    /**
     * Function to load add page
     * @parameter: curriculum id
     * @return: load add program educational objective view page
     */
    public function peo_add($curriculum_id) {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if ((!$this->ion_auth->is_admin()) && !($this->ion_auth->in_group('Chairman'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            $curriculum_name = $this->peo_model->peo_curriculum_index();
            $data['results'] = $curriculum_name['result_curriculum_list'];

            $data['attendees_data'] = $this->add_peo_model->attendees_data($curriculum_id);
            $data['peo_reference'] = array(
                'name' => 'peo_reference1',
                'id' => 'peo_reference1',
                'class' => 'input-mini required',
                'type' => 'text',
                'autofocus' => 'autofocus'
            );
            $data['peo_statement'] = array(
                'name' => 'peo_statement1',
                'id' => 'peo_statement1',
                'rows' => '3',
                'cols' => '20',
                'class' => 'required peo char-counter',
                'type' => 'textarea',
                'maxlength' => "2000",
                'autofocus' => 'autofocus'
            );

            if (!isset($data['attendees_data']['0']['attendees_name'])) {
                $data['attendees_data']['0']['attendees_name'] = '';
            }

            $data['attendees'] = array(
                'name' => 'attendees',
                'id' => 'attendees',
                'rows' => '3',
                'class' => 'required',
                'type' => 'textarea',
                'cols' => '15',
                'value' => $data['attendees_data']['0']['attendees_name']
            );

            if (!isset($data['attendees_data']['0']['attendees_notes'])) {
                $data['attendees_data']['0']['attendees_notes'] = '';
            }


            $data['notes'] = array(
                'name' => 'notes',
                'id' => 'notes',
                'rows' => '3',
                'class' => '',
                'type' => 'textarea',
                'value' => $data['attendees_data']['0']['attendees_notes']
            );
            $data['curriculum_id'] = array(
                'name' => 'crclm_id',
                'id' => 'crclm_id',
                'class' => 'required',
                'type' => 'hidden',
                'value' => $curriculum_id
            );

            $data['crclm'] = array(
                'name' => 'crclm',
                'id' => 'crclm',
                'class' => 'required',
                'type' => 'hidden',
                'value' => $curriculum_id
            );

            $data['entity_id'] = array(
                'name' => 'entity_id',
                'id' => 'entity_id',
                'class' => 'required',
                'type' => 'hidden',
                'value' => '5'
            );

            $curriculum_name_data = $this->add_peo_model->curriculum_name_data();

            $data['curriculum_name_data'] = $curriculum_name_data;
            $data['title'] = "PEO Add Page";
            $this->load->view('curriculum/peo/add_peo_vw', $data);
        }
    }

    /**
     * Function to add new program educational objective 
     * @parameters: curriculum id
     * @return: redirect to program educational objective list page
     */
    public function peo_insert() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if ((!$this->ion_auth->is_admin()) && !($this->ion_auth->in_group('Chairman'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            $counter = $this->input->post('counter');
            $counter_val = explode(",", $counter);
            for ($i = 0; $i < sizeof($counter_val); $i++) {
                $peo_statement[] = $this->input->post('peo_statement' . $counter_val[$i]);
                $peo_reference[] = $this->input->post('peo_reference' . $counter_val[$i]);
            }
            $entity_id = 5;
            $attendees = $this->input->post('attendees');
            $notes = $this->input->post('notes');
            $curriculum_id = $this->input->post('crclm');
            $results = $this->add_peo_model->insert($peo_statement, $peo_reference, $entity_id, $attendees, $notes, $curriculum_id);
        }
    }

    /**
     * Function to check authentication of the logged in user and load program educational objective edit page
     * @return: loads program educational objective page
     */
    public function peo_edit($curriculum_id) {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if ((!$this->ion_auth->is_admin()) && !($this->ion_auth->in_group('Chairman'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            $data['peo_data'] = $this->add_peo_model->peo_edit($curriculum_id);
            $data['attendees_name'] = array(
                'name' => 'attendees_name',
                'id' => 'attendees_name',
                'rows' => '3',
                'type' => 'textarea',
                'class' => 'required peo',
                'value' => $data['peo_data']['0']['attendees_name']
            );
            $data['attendees_notes'] = array(
                'name' => 'attendees_notes',
                'id' => 'attendees_notes',
                'rows' => '3',
                'type' => 'textarea',
                'class' => 'peo',
                'value' => $data['peo_data']['0']['attendees_notes']
            );
            $data['peo_reference'] = array(
                'name' => 'peo_reference1',
                'id' => 'peo_reference1',
                'class' => 'input-mini required edit_peo',
                'type' => 'text',
                'autofocus' => 'autofocus',
                'value' => ''
            );
            $data['peo_statement'] = array(
                'name' => 'peo_statement',
                'id' => 'peo_statement',
                'rows' => '3',
                'cols' => '40',
                'type' => 'textarea',
                'style' => 'margin: 0px; width: 564px; height: 72px;',
                'maxlength' => "2000",
                'class' => 'required edit_peo loginRegex_one',
                'autofocus' => 'autofocus'
            );
            $this->data['peo_id'] = array(
                'name' => 'peo_id[]',
                'id' => 'peo_id',
                'type' => 'hidden',
            );
            $this->data['curriculum_id'] = array(
                'name' => 'crclm_id',
                'id' => 'crclm_id',
                'type' => 'hidden',
                'value' => $curriculum_id
            );

            $curriculum_name_data = $this->add_peo_model->curriculum_name_data();

            foreach ($curriculum_name_data as $list_item) {
                $select_options[$list_item['crclm_id']] = $list_item['crclm_name'];
            }

            $data['curriculum_name_data'] = $select_options;
            $data['title'] = "PEO Edit Page";

            $this->load->view('curriculum/peo/edit_peo_vw', $data);
        }
    }

    /**
     * Function is used to update the details of the program educational objective statements, attendees and attendee notes
     * @return: load program educational objective list page
     */
    public function peo_update() {
        $counter = $this->input->post('add_more_peo_counter');
        $counter_val = explode(",", $counter);
        for ($i = 0; $i < sizeof($counter_val); $i++) {
            $peo_statement[] = $this->input->post('peo_statement' . $counter_val[$i]);
            $peo_reference[] = $this->input->post('peo_reference' . $counter_val[$i]);
        }
        $curriculum_id = $this->input->post('crclm_id');
        $peo_id = $this->input->post('peo_id');
        $attendees_name = $this->input->post('attendees_name');
        $attendees_notes = $this->input->post('attendees_notes');
        $peo_statement_new = $this->input->post('peo_statement_new');


        $results = $this->add_peo_model->edit_peo($curriculum_id, $peo_id, $peo_statement, $attendees_name, $attendees_notes, $peo_reference);

        redirect('curriculum/peo');
    }

    /**
     * Function is used to redirect the user to the welcome screen if the user does not have permission
     * @return: load welcome page
     */
    public function blank() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        }

        $data['title'] = "Blank Page";
        $this->load->view('welcome_template_vw');
    }

    /**
     * Function is used to publish the program educational objective statements and a mail will be sent
      to the program owner
     * @return: boolean
     */
    public function publish_details() {
        $curriculum_id_details = $this->input->post('curriculum_id');
        $results = $this->peo_model->approve_publish_db($curriculum_id_details);

        $receiver_id = $results['receiver_id'];
        $cc = '';
        $links = $results['url'];
        $entity_id = 5;
        $state = 7;
        $additional_data = '';

        $this->ion_auth->send_email($receiver_id, $cc, $links, $entity_id, $state, $curriculum_id_details, $additional_data);

        return true;
    }

    /* Function is used to fetch current Status of PEOs from dashboard table.
     * @param- curriculum id
     * @returns - an object.
     */

    public function fetch_peo_current_state() {
        $curriculum_id = $this->input->post('curriculum_id');
        $peo_data = $this->peo_model->fetch_peo_current_state($curriculum_id);
        if ($peo_data) {
            $list = "PEOs Current Status :- " . $peo_data[0]['state_name'];
            $state_id = $peo_data[0]['state'];
            echo $list . ',' . $state_id;
        } else {
            $list = "PEOs Current Status :- PEO Creation Not Initiated.";
            echo $list;
        }
    }

// End of function fetch_peo_current_state.

    /**
     * Function to fetch bos user name and curriculum
     * @parameter: 
     * @return: an object
     */
    public function chairman_user_details() {
        $curriculum_id = $this->input->post('curriculum_id');
        $chairman_user = $this->peo_model->chairman_user($curriculum_id);
        $curriculum_data = $this->peo_model->fetch_curriculum($curriculum_id);


        $chairman_user_name = array(
            'chairman_user_name' => $chairman_user[0]['title'] . ' ' . $chairman_user[0]['first_name'] . ' ' . $chairman_user[0]['last_name'],
            'crclm_name' => $curriculum_data[0]['crclm_name']
        );

        echo json_encode($chairman_user_name);
    }

    //Mapping between PEO to ME 
    /* Function is used to load corresponding data table gird.
     * @parameters: 
     * @return: Data table grid of PEO to ME mapping. 
     */
    public function map_table() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if ($this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('admin') | $this->ion_auth->in_group('Program Owner')) {
            $curriculum_id = $this->input->post('curriculum_id');

            $logged_in_user_dept_id = $this->ion_auth->user()->row()->user_dept_id;

            $dept_id = $this->peo_model->fetch_dept($curriculum_id);
            $results = $this->peo_model->map_peo_me($curriculum_id, $dept_id[0]['dept_id']);
            $data['me_list'] = $results['me_list'];
            $data['peo_list'] = $results['peo_list'];
            $data['mapped_peo_me'] = $results['mapped_peo_me'];
            $data['title'] = 'PEO to ME Mapping Page';
            $data['indv_mappig_just'] = $results['indv_mappig_just'];
            $data['weightage_data'] = $results['weightage_data'];
            $data = $this->load->view('curriculum/peo/peo_me_table_vw', $data);
        }
    }

    /* Function is used to insert the PEO to ME mapping.
     * @parameters: 
     * @return: a boolean value.
     */

    public function add_mapping() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            $peo_me_mapping = $this->input->post('me');
            $curriculum_id = $this->input->post('crclm_id');
            $logged_in_user_dept_id = $this->ion_auth->user()->row()->user_dept_id;
            $results = $this->peo_model->add_peo_me_mapping($peo_me_mapping, $curriculum_id, $logged_in_user_dept_id);
            return true;
        }
    }

    /* Function is used to delete the PEO to ME mapping.
     * @parameters: 
     * @return: a boolean value.
     */

    public function unmap() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            $me = $this->input->post('me');
            $logged_in_user_dept_id = $this->ion_auth->user()->row()->user_dept_id;
            $curriculum_id = $this->input->post('crclm_id');
            $results = $this->peo_model->unmap($me, $curriculum_id, $logged_in_user_dept_id);
            return true;
        }
    }

    /* Function to update  PEO */

    public function update_peo() {
        $id = $this->input->post('peo_id');
        $crclm = $this->input->post('crclm');
        $peo_ref = $this->input->post('peo_ref');
        $peo_stmt = $this->input->post('peo_stmt');
        $attendees_name = $this->input->post('attendees_name');
        $attendees_notes = $this->input->post('attendees_notes');
        echo $result = $this->peo_model->update_peo($id, $crclm, $peo_ref, $peo_stmt, $attendees_notes, $attendees_name);
    }

    public function add_mapping_new() {
        $crclm_id = $this->input->post('crclm_id');
        $data = $this->input->post('peo_id');
        return $this->peo_model->add_peo_me_mapping_new($data, $crclm_id);
    }

    public function save_justification() {
        $data['crclm_id'] = $this->input->post('crclm_id');
        $data['peo_id'] = $this->input->post('peo_id');
        $data['me_id'] = $this->input->post('me_id');
        $data['pm_id'] = $this->input->post('pm_id');
        $data['justification'] = $this->db->escape_str($this->input->post('justification'));
        return $this->peo_model->save_justification($data);
    }

    /* Function is used to delete the mapping of PEO to ME  from the po_peo_map table.
     * @parameters: 
     * @return: a boolean value.
     */

    public function unmap_new() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            $peo = $this->input->post('peo');
            $curriculum_id = $this->input->post('crclm_id');
            $results = $this->peo_model->unmap_new($peo, $curriculum_id);
            return true;
        }
    }

    /* Function is used to insert the comments of mapping of PEO to ME onto the notes table.
     * @parameters: 
     * @return: a boolean value.
     */

    public function save_txt() {
        $curriculum_id = $this->input->post('crclm_id');
        $text = $this->input->post('text');
        $results = $this->peo_model->save_notes_in_database($curriculum_id, $text);
        echo $results;
    }

    /* Function is used to fetch the comments of mapping of PO to PEO onto the notes table.
     * @parameters: 
     * @return: a JSON_ENCODE object.
     */

    public function fetch_txt() {
        $curriculum_id = $this->input->post('crclm_id');
        $results = $this->peo_model->text_details($curriculum_id);
        echo $results;
    }

    public function save_comment_peo_me() {
        $data['peo_id'] = $this->input->post('peo_id');
        $data['crclm_id'] = $this->input->post('crclm_id');
        $data['me_id'] = $this->input->post('me_id');
        $data['justification'] = $this->input->post('justification');
        $data['pm_id'] = $this->input->post('pm_id');
        $re = $this->peo_model->save_comment_peo_me($data);
    }

    public function fetch_justification() {
        $peo_id = $this->input->post('peo_id');
        $crclm_id = $this->input->post('crclm_id');
        $me_id = $this->input->post('me_id');
        $pm_id = $this->input->post('pm_id');
        $comment_results = $this->peo_model->fetch_justification($peo_id, $crclm_id, $me_id, $pm_id);
        echo json_encode($comment_results);
    }

    public function peo_statement_search() {
        $peo_id = $this->input->post('peo_id');
        $peo_ref = $this->input->post('peo_ref');
        $peo_data = $this->input->post('peo_stmt');
        $crclm_id = $this->input->post('crclm');
        $search = $this->add_peo_model->search_statement($peo_id, $crclm_id, $peo_ref, $peo_data);

        if ($search == 0) {
            echo 'valid';
        } else {
            echo 'invalid';
        }
    }

    public function peo_search() {
        //$peo_id = $this->input->post('peo_id');
        $peo_data = $this->input->post('peo_stmt');
        $crclm_id = $this->input->post('crclm');
        $search = $this->add_peo_model->statement_search($crclm_id, $peo_data);

        if ($search == 0) {
            echo 'valid';
        } else {
            echo 'invalid';
        }
    }

}

/* End of file peo.php */
/* Location: ./controllers/peo.php */
?>
