<?php

/**
 * Description          :	Controller Logic for PO(Program Outcomes) to PEO(Program Educational Objectives) Mapping Module.
 * Created		:	25-03-2013. 
 * Modification History:
 * Date			Modified By				Description
 * 24-09-2013		Abhinay B.Angadi                Added file headers, public function headers, indentations & comments.
 * 25-09-2013		Abhinay B.Angadi		Variable naming, Function naming & Code cleaning.
 * 06-15-2015		Bhagyalaxmi S S			Added justification for each mapping	

  -------------------------------------------------------------------------------------------------
 */
?>
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Map_po_peo extends CI_Controller {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->model('curriculum/map_po_to_peo/map_po_peo_model');
    }

    /* Function is used to check the user logged_in, his user group, permissions & 
     * to load PO to PEO mapping static list / list view.
     * @param- curriculum id.
     * @return: static(read only) list / list view of PO to PEO mapping based on the state id.
     */

    public function index($curriculum_id = '0') {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if ($this->ion_auth->is_admin()) {
            $data['crclm_id'] = $curriculum_id;
            $results = $this->map_po_peo_model->list_curriculum();
            $skip_approval_flag_fetch = $this->map_po_peo_model->skip_approval_flag_fetch();
            $peo_po_comment_results = $this->map_po_peo_model->peo_po_comment_data($curriculum_id);
            $data['peo_po_comment'] = $peo_po_comment_results['peo_po_comment'];
            $data['approval_flag'] = $skip_approval_flag_fetch;
            $data['curriculum_list'] = $results;
            $data['select_id'] = $curriculum_id;
            $data['title'] = $this->lang->line('so') . ' to PEO Mapping Page';
            $this->load->view('curriculum/map_po_to_peo/po_peo_vw', $data);
        } else if ($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman')) {
            $data['crclm_id'] = $curriculum_id;
            $user_id = $this->ion_auth->user()->row()->id;
            $results = $this->map_po_peo_model->list_department_curriculum($user_id);
            $skip_approval_flag_fetch = $this->map_po_peo_model->skip_approval_flag_fetch();
            $peo_po_comment_results = $this->map_po_peo_model->peo_po_comment_data($curriculum_id);
            $data['peo_po_comment'] = $peo_po_comment_results['peo_po_comment'];
            $data['approval_flag'] = $skip_approval_flag_fetch;
            $data['curriculum_list'] = $results;
            $data['select_id'] = $curriculum_id;
            $data['title'] = $this->lang->line('so') . ' to PEO Mapping Page';
            $this->load->view('curriculum/map_po_to_peo/po_peo_vw', $data);
        } else {
            /* 			$results = $this->map_po_peo_model->list_curriculum();

              $data['curriculum_list'] = $results;
              $data['title'] = $this->lang->line('so') . ' to PEO Mapping Page';
              $this->load->view('curriculum/map_po_to_peo/static_po_peo_vw', $data); */
            redirect('dashboard/dashboard');
        }
    }

    /* Function is used to check the user logged_in & to load PO to PEO mapping static list / list view.
     * @param-
     * @return: static(read only) list view of PO to PEO mapping.
     */

    public function static_page_index() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            $results = $this->map_po_peo_model->list_curriculum();
            $data['curriculum_list'] = $results;
            $data['title'] = $this->lang->line('so') . ' to PEO Mapping Page';
            $this->load->view('curriculum/map_po_to_peo/static_po_peo_vw', $data);
        }
    }

    /* Function is used to loads static data table gird.
     * @parameters: 
     * @return: Static data table grid of PO to PEO mapping.
     */

    public function static_map_table() {
        $curriculum_id = $this->input->post('crclm_id');
        $results = $this->map_po_peo_model->map_po_peo($curriculum_id);

        $data['po_list'] = $results['po_list'];
        $data['peo_list'] = $results['peo_list'];
        $data['crclm_id'] = $curriculum_id;
        $data['mapped_po_peo'] = $results['mapped_po_peo'];
        $data['title'] = $this->lang->line('so') . ' to PEO Mapping Page';

        //$this->load->view('curriculum/map_po_to_peo/static_po_peo_table_vw', $data);
    }

    /* Function is used to check the state id of the PEO & loads its corresponding data table gird.
     * @parameters: 
     * @return: Data table grid of PO to PEO mapping based on the PEO state id. 
     */

    public function map_table() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            $curriculum_id = $this->input->post('crclm_id');
            $results = $this->map_po_peo_model->get_state($curriculum_id);

            if ($results['state_id'][0]['count(state)'] != 0) {
                if ($results['state_id'][0]['state'] == 1 || $results['state_id'][0]['state'] == 6) {
                    $user_id = $this->ion_auth->user()->row()->id;
                    $results = $this->map_po_peo_model->map_po_peo($curriculum_id);
                    $data['po_list'] = $results['po_list'];
                    $data['peo_list'] = $results['peo_list'];
                    $data['mapped_po_peo'] = $results['mapped_po_peo'];
                    $this->load->view('curriculum/map_po_to_peo/po_peo_table_vw', $data);
                } else if ($results['state_id'][0]['state'] == 5 || $results['state_id'][0]['state'] >= 7) {
                    $results = $this->map_po_peo_model->map_po_peo($curriculum_id);
                    $data['po_list'] = $results['po_list'];
                    $data['peo_list'] = $results['peo_list'];
                    $data['crclm_id'] = $curriculum_id;
                    $data['mapped_po_peo'] = $results['mapped_po_peo'];
                    $data['title'] = $this->lang->line('so') . ' to PEO Mapping Page';
                    $this->load->view('curriculum/map_po_to_peo/po_peo_send_for_approval_table_vw', $data);
                }
            } else {
                echo "<font color='color'><h3> Mapping of " . $this->lang->line('sos') . " to PEOs yet to be initiated. </h3></font>";
            }
        }
    }

    /* Function is used to check the state id of the PEO & loads its corresponding data table gird.
     * @parameters: 
     * @return: Data table grid of PO to PEO mapping based on the PEO state id. 
     */

    public function map_table_new() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            $curriculum_id = $this->input->post('crclm_id');
            $results = $this->map_po_peo_model->get_state($curriculum_id);
            $peo_po_comment_results = $this->map_po_peo_model->peo_po_comment_data($curriculum_id);
            $data['peo_po_comment'] = $peo_po_comment_results['peo_po_comment'];

            if ($results['state_id'][0]['count(state)'] != 0) {
                if ($results['state_id'][0]['state'] == 1 || $results['state_id'][0]['state'] == 6) {
                    $user_id = $this->ion_auth->user()->row()->id;
                    $results = $this->map_po_peo_model->map_po_peo_new($curriculum_id);

                    $data['po_list'] = $results['po_list'];
                    $data['peo_list'] = $results['peo_list'];
                    $data['mapped_po_peo'] = $results['mapped_po_peo'];
                    $data['weightage'] = $results['weightage_data'];
                    $data['indv_mappig_just'] = $results['indv_mappig_just'];
                    $data['title'] = $this->lang->line('so') . ' to PEO Mapping Page';
                    $this->load->view('curriculum/map_po_to_peo/po_peo_table_vw', $data);
                } else if ($results['state_id'][0]['state'] == 5 || $results['state_id'][0]['state'] >= 7) {
                    $results = $this->map_po_peo_model->map_po_peo_new($curriculum_id);
                    $data['po_list'] = $results['po_list'];
                    $data['peo_list'] = $results['peo_list'];
                    $data['crclm_id'] = $curriculum_id;
                    $data['mapped_po_peo'] = $results['mapped_po_peo'];
                    $data['indv_mappig_just'] = $results['indv_mappig_just'];
                    $data['title'] = $this->lang->line('so') . ' to PEO Mapping Page';
                    $this->load->view('curriculum/map_po_to_peo/po_peo_send_for_approval_table_vw', $data);
                }
            } else {
                echo "<font color='color'><h3> Mapping of " . $this->lang->line('sos') . " to PEOs yet to be initiated. </h3></font>";
            }
        }
    }

    public function fetch_comments() {
        $curriculum_id = $this->input->post('crclm_id');
        $peo_po_comment_results = $this->map_po_peo_model->peo_po_comment_data($curriculum_id);
        $data['peo_po_comment'] = $peo_po_comment_results['peo_po_comment'];
        if (!empty($data['peo_po_comment']))
            echo $data['peo_po_comment'][0]['cmt_statement'];
    }

    /* Function is used to check the state id of PEO state.
     * @parameters: 
     * @return: an integer value
     */

    public function disable() {
        $curriculum_id = $this->input->post('crclm_id');
        $results = $this->map_po_peo_model->get_state($curriculum_id);
        if ($results['state_id'][0]['count(state)'] != 0) {
            if ($results['state_id'][0]['state'] == 5 || $results['state_id'][0]['state'] >= 7) {
                echo 1; //'hide send for approve button'
            } else {
                echo 0; //'show send for approve button''
            }
        } else {
            echo 1; //'hide send for approve button'
        }
    }

    public function disable_remap() {
        $curriculum_id = $this->input->post('crclm_id');
        $results = $this->map_po_peo_model->get_state($curriculum_id);
        if ($results['state_id'][0]['count(state)'] != 0) {
            if ($results['state_id'][0]['state'] >= 7) {
                echo 1; //'hide send for approve button'
            } else {
                echo 0; //'show send for approve button''
            }
        } else {
            echo 1; //'hide send for approve button'
        }
    }

    /* Function is used to insert the mapping of PO to PEO onto the po_peo_map table.
     * @parameters: 
     * @return: a boolean value.
     */

    public function add_mapping() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            $po_peo_mapping = $this->input->post('po');
            $curriculum_id = $this->input->post('crclm_id');
            $results = $this->map_po_peo_model->add_po_peo_mapping($po_peo_mapping, $curriculum_id);
            return true;
        }
    }

    /* Function is used to insert the comments of mapping of PO to PEO onto the notes table.
     * @parameters: 
     * @return: a boolean value.
     */

    public function save_txt() {
        $curriculum_id = $this->input->post('crclm_id');
        $text = $this->input->post('text');
        $results = $this->map_po_peo_model->save_notes_in_database($curriculum_id, $text);
        echo $results;
    }

    /* Function is used to fetch the comments of mapping of PO to PEO onto the notes table.
     * @parameters: 
     * @return: a JSON_ENCODE object.
     */

    public function fetch_txt() {
        $curriculum_id = $this->input->post('crclm_id');
        $results = $this->map_po_peo_model->text_details($curriculum_id);
        echo $results;
    }

    /* Function is used to send an email notification for Approval of mapping of PO to PEO.
     * @parameters: 
     * @return: Redirects to Dashboard view..
     */

    public function send_for_Approve() {
        $user_id = $this->ion_auth->user()->row()->id;
        $curriculum_id = $this->input->post('crclm_id');
        $state = '5';
        $results = $this->map_po_peo_model->send_for_Approve($curriculum_id, $state, $user_id);

        //this code is used to send an email notification to the user
        $receiver_id = $results['receiver_id'];
        $cc = '';
        $links = $results['url'];
        $entity_id = '13';
        $state = $results['state'];

        $this->ion_auth->send_email($receiver_id, $cc, $links, $entity_id, $state, $curriculum_id);
        redirect('curriculum/map_po_peo');
    }

    /* Function is used to send an email notification for Rework of mapping of PO to PEO.
     * @parameters: 
     * @return: Redirects to Dashboard view..
     */

    public function approver_rework_accept() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            $user_id = $this->ion_auth->user()->row()->id;
            $state = $this->input->post('state');
            $curriculum_id = $this->input->post('crclm_id');
            $results = $this->map_po_peo_model->send_for_Approve($curriculum_id, $state, $user_id);

            //this code is used to send an email notification to the user
            $receiver_id = $results['receiver_id'];
            $cc = '';
            $links = $results['url'];
            $entity_id = '13';
            $state = $results['state'];

            $this->ion_auth->send_email($receiver_id, $cc, $links, $entity_id, $state, $curriculum_id);
            redirect('dashboard/dashboard');
        }
    }

    /* Function is used to send an email notification for Skip Approval of mapping of PO to PEO.
     * @parameters: 
     * @return: Redirects to mapping view..
     */

    public function bos_skip_approval() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {

            $user_id = $this->ion_auth->user()->row()->id;
            $state = $this->input->post('state');
            $curriculum_id = $this->input->post('crclm_id');
            /* 			if(!$this->ion_auth->in_group('Program Owner') || !$this->ion_auth->is_admin() || !$this->ion_auth->in_group('Chairman')){echo "false";}
              else
              {
             */ $results['data'] = $this->map_po_peo_model->send_for_Approve($curriculum_id, $state, $user_id);
            $results['po_exist'] = $this->ion_auth->in_group('Program Owner');
            $results['chairman'] = $this->ion_auth->in_group('Chairman');
            $results['admin'] = $this->ion_auth->is_admin();
            //this code is used to send an email notification to the user
            $receiver_id = $results['data']['receiver_id'];
            $cc = '';
            $links = $results['data']['url'];
            $entity_id = '13';
            $state = $results['data']['state'];
            $this->ion_auth->send_email($receiver_id, $cc, $links, $entity_id, $state, $curriculum_id);
            echo json_encode($results);
        }
    }

    /* Function is used to delete the mapping of PO to PEO from the po_peo_map table.
     * @parameters: 
     * @return: a boolean value.
     */

    public function unmap() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            $po = $this->input->post('po');
            $curriculum_id = $this->input->post('crclm_id');
            $results = $this->map_po_peo_model->unmap($po, $curriculum_id);
            return true;
        }
    }

    /* Function is used to check the state id, user permissions & loads the Approval of PO to PEO mapping view.
     * @param- curriculum id.
     * @return: Approval of PO to PEO mapping view.
     */

    public function approval_page($curriculum_id = NULL) {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if ($this->ion_auth->is_admin()) {
            $results = $this->map_po_peo_model->list_curriculum();
            $data['curriculum_list'] = $results;
            $results = $this->map_po_peo_model->map_po_peo($curriculum_id);
            $data['po_list'] = $results['po_list'];
            $data['peo_list'] = $results['peo_list'];
            $data['peo_po_comment_result'] = $results['peo_po_comment'];
            $data['peo_po_notes'] = $results['peo_po_notes_data'];
            $data['weightage'] = $results['weightage_data'];
            $data['crclm_id'] = $curriculum_id;
            $data['mapped_po_peo'] = $results['mapped_po_peo'];
            $results = $this->map_po_peo_model->get_state($curriculum_id);
            if ($results['state_id'][0]['state'] == 5) {
                $data['button_state'] = 1;
            } else {
                $data['button_state'] = 0;
            }
            $data['title'] = $this->lang->line('so') . ' to PEO Mapping Page';
            $this->load->view('curriculum/map_po_to_peo/po_peo_for_approval_vw', $data);
        } else {
            $check_permission = $this->map_po_peo_model->check_for_approver($curriculum_id);
            if ($this->ion_auth->user()->row()->id == $check_permission) {
                $results = $this->map_po_peo_model->list_curriculum();
                $data['curriculum_list'] = $results;
                $results = $this->map_po_peo_model->map_po_peo($curriculum_id);
                $data['po_list'] = $results['po_list'];
                $data['peo_list'] = $results['peo_list'];
                $data['peo_po_comment_result'] = $results['peo_po_comment'];
                $data['peo_po_notes'] = $results['peo_po_notes_data'];
                $data['crclm_id'] = $curriculum_id;
                $data['weightage'] = $results['weightage_data'];
                $data['mapped_po_peo'] = $results['mapped_po_peo'];
                $justify = count($data['mapped_po_peo']);
                $data['indv_mappig_just'] = $results['indv_mappig_just'];
                $results = $this->map_po_peo_model->get_state($curriculum_id);
                if ($results['state_id'][0]['state'] == 5) {
                    $data['button_state'] = '1';
                } else {
                    $data['button_state'] = '0';
                }
                $data['title'] = $this->lang->line('so') . ' to PEO Mapping Page';
                $this->load->view('curriculum/map_po_to_peo/po_peo_for_approval_vw', $data);
            } else {
                redirect('dashboard/dashboard');
            }
        }
    }

    /* Function is used to insert the comments of mapping of PO to PEO onto the comment table.
     * @parameters: 
     * @return: a boolean value.
     */

    public function po_peo_comment_insert() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        } else {
            echo '1st';
            $po_id = $this->input->post('po_id');
            echo $po_id;
            echo '</br>';
            $peo_id = $this->input->post('peo_id');
            echo $peo_id;
            $curriculum_id = $this->input->post('crclm_id');
            echo $curriculum_id;
            $po_peo_cmt = $this->input->post('po_peo_cmt');
            $results = $this->map_po_peo_model->po_peo_comment_insert($curriculum_id, $po_id, $peo_id, $po_peo_cmt);
            return true;
        }
    }

    /* Function is used to fetch the help data from help_content table.
     * @param - 
     * @returns- an object of values of PO to PEO Mapping details.
     */

    public function peo_po_help() {
        $help_list = $this->map_po_peo_model->peo_po_help();

        if (!empty($help_list['help_data'])) {
            foreach ($help_list['help_data'] as $help) {
                $clo_po_id = '<i class="icon-black icon-file"> </i><a target="_blank" href="' . base_url('curriculum/map_po_peo/po_peo_content') . '/' . $help['serial_no'] . '">' . $help['entity_data'] . '</a></br>';
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

    /**
     * Function to display help related to course learning outcomes to program outcomes mapping in a new page
     * @parameters: help id
     * @return: load help view page
     */
    public function po_peo_content($help_id) {
        $help_content = $this->map_po_peo_model->help_content($help_id);
        $help['help_content'] = $help_content;
        $data['title'] = $this->lang->line('so') . " PEO Help";

        $this->load->view('curriculum/map_po_to_peo/po_peo_help_vw', $help);
    }

    /* Function is used to fetch current state of POs to PEOs Mapping from dashboard table.
     * @param- curriculum id
     * @returns - an object.
     */

    public function fetch_po_peo_map_current_state() {
        $curriculum_id = $this->input->post('crclm_id');
        $po_peo_map_data = $this->map_po_peo_model->fetch_po_peo_map_current_state($curriculum_id);
        if ($po_peo_map_data) {
            $list = $this->lang->line('sos') . " to PEOs Mapping Current Status : " . $po_peo_map_data[0]['state_name'];
            echo $list;
        } else {
            $list = $this->lang->line('sos') . " to PEOs Mapping Current Status : POs to PEOs Mapping Creation Not Initiated.";
            echo $list;
        }
    }

// End of function fetch_po_peo_map_current_state.

    /* Function is used to insert the comments of mapping of PO to PEO onto the notes table.
     * @parameters: 
     * @return: a boolean value.
     */

    public function save_peo_po_comment() {
        $curriculum_id = $this->input->post('crclm_id');
        $peo_po_comment = $this->input->post('peo_po_cmt');

        $results = $this->map_po_peo_model->peo_po_comment_save($curriculum_id, $peo_po_comment);
        echo $results;
    }

    /* Function is used to fetch BoS Approval Authority of POs to PEOs Mapping from peo_po_approver table.
     * @param- curriculum id
     * @returns - an object.
     */

    public function fetch_bos_user() {
        $curriculum_id = $this->input->post('crclm_id');
        $bos_user = $this->map_po_peo_model->fetch_bos_user($curriculum_id);
        $bos_user_name = $bos_user[0]['title'] . ' ' . ucfirst($bos_user[0]['first_name']) . ' ' . ucfirst($bos_user[0]['last_name']);
        $bos_user_name = array(
            'bos_user_name' => $bos_user_name,
            'crclm_name' => $bos_user[0]['crclm_name']
        );
        echo json_encode($bos_user_name);
    }

// End of function fetch_bos_user.

    /* Function is used to fetch Chairman details of POs to PEOs Mapping.
     * @param- curriculum id
     * @returns - an object.
     */

    public function fetch_chairman_user() {
        $curriculum_id = $this->input->post('crclm_id');
        $chairman_user = $this->map_po_peo_model->fetch_chairman_user($curriculum_id);
        $chairman_user_name = $chairman_user[0]['title'] . ' ' . ucfirst($chairman_user[0]['first_name']) . ' ' . ucfirst($chairman_user[0]['last_name']);
        $chairman_user_name = array(
            'chairman_user_name' => $chairman_user_name,
            'crclm_name' => $chairman_user[0]['crclm_name']
        );
        echo json_encode($chairman_user_name);
    }

// End of function fetch_chairman_user.

    /* Function is used to fetch Program Owner details of OEs & PIs creation.
     * @param- curriculum id
     * @returns - an object.
     */

    public function fetch_programowner_user() {
        $curriculum_id = $this->input->post('crclm_id');
        $programowner_user = $this->map_po_peo_model->fetch_programowner_user($curriculum_id);
        $programowner_user_name = $programowner_user[0]['title'] . ' ' . ucfirst($programowner_user[0]['first_name']) . ' ' . ucfirst($programowner_user[0]['last_name']);
        $chairman_user_name = array(
            'programowner_user_name' => $programowner_user_name,
            'crclm_name' => $programowner_user[0]['crclm_name']
        );
        echo json_encode($chairman_user_name);
    }

// End of function fetch_chairman_user.

    /* Function is used to send an email notification for Rework of mapping of PO to PEO.
     * @parameters: 
     * @return: Redirects to Dashboard view..
     */

    public function remap() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            $user_id = $this->ion_auth->user()->row()->id;
            $state = $this->input->post('state');
            $curriculum_id = $this->input->post('crclm_id');
            $results = $this->map_po_peo_model->send_for_remap($curriculum_id, $state, $user_id);
            redirect('dashboard/dashboard');
        }
    }

    /*     * ****************************************************************************************************************************** */

    /* Function is used to insert the mapping of PO to PEO onto the po_peo_map table.
     * @parameters: 
     * @return: a boolean value.
     */

    public function add_mapping_new() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            $po_peo_mapping = $this->input->post('po');
            $curriculum_id = $this->input->post('crclm_id');
            $map_level = $this->input->post('map_level');
            $results = $this->map_po_peo_model->add_po_peo_mapping_new($po_peo_mapping, $curriculum_id, $map_level);
            return true;
        }
    }

    /* Function is used to delete the mapping of PO to PEO from the po_peo_map table.
     * @parameters: 
     * @return: a boolean value.
     */

    public function unmap_new() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            $po = $this->input->post('po');
            $curriculum_id = $this->input->post('crclm_id');
            $results = $this->map_po_peo_model->unmap_new($po, $curriculum_id);
            return true;
        }
    }

    /*
     * Function to fetch mapped data
     * @param:
     * @return:
     */

    public function fetch_map_data() {

        $globalid = $this->input->post('globalid');
        $results = $this->map_po_peo_model->fetch_map_data($globalid);
        echo json_encode($results);
    }

    /* Author: Bhagyalaxmi S S
     * Function is used to check the user logged_in, his user group, permissions & 
     * to load PO to PEO mapping static list / list view.
     * @param- curriculum id.
     * @return: static(read only) list / list view of PO to PEO mapping based on the state id.
     * author : Bhagya S S
     */

    public function map_po_peo_index($curriculum_id) {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if ($this->ion_auth->is_admin()) {
            $data['crclm_id'] = $curriculum_id;
            $results = $this->map_po_peo_model->list_curriculum();
            $skip_approval_flag_fetch = $this->map_po_peo_model->skip_approval_flag_fetch();
            $peo_po_comment_results = $this->map_po_peo_model->peo_po_comment_data($curriculum_id);
            $data['peo_po_comment'] = $peo_po_comment_results['peo_po_comment'];
            $data['approval_flag'] = $skip_approval_flag_fetch;
            $data['curriculum_list'] = $results;
            $data['select_id'] = $curriculum_id;
            $data['title'] = $this->lang->line('so') . ' to PEO Mapping Page';
            $this->load->view('curriculum/map_po_to_peo/po_peo_vw', $data);
        } else if ($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman')) {
            $data['crclm_id'] = $curriculum_id;
            $user_id = $this->ion_auth->user()->row()->id;
            $results = $this->map_po_peo_model->list_department_curriculum($user_id);
            $skip_approval_flag_fetch = $this->map_po_peo_model->skip_approval_flag_fetch();
            $peo_po_comment_results = $this->map_po_peo_model->peo_po_comment_data($curriculum_id);
            $data['peo_po_comment'] = $peo_po_comment_results['peo_po_comment'];
            $data['approval_flag'] = $skip_approval_flag_fetch;
            $data['curriculum_list'] = $results;
            $data['select_id'] = $curriculum_id;
            $data['title'] = $this->lang->line('so') . ' to PEO Mapping Page';
            $this->load->view('curriculum/map_po_to_peo/po_peo_vw', $data);
        } else {
            $results = $this->map_po_peo_model->list_curriculum();

            $data['curriculum_list'] = $results;
            $data['title'] = $this->lang->line('so') . ' to PEO Mapping Page';
            $this->load->view('curriculum/map_po_to_peo/static_po_peo_vw', $data);
        }
    }

    public function save_justification() {
        $data['crclm_id'] = $this->input->post('crclm_id');
        $data['peo_id'] = $this->input->post('peo_id');
        $data['po_id'] = $this->input->post('po_id');
        $data['pp_id'] = $this->input->post('pp_id');
        $data['justification'] = $this->input->post('justification');

        return $this->map_po_peo_model->save_justification($data);
    }

    public function fetch_justification() {
        $peo_id = $this->input->post('peo_id');
        $crclm_id = $this->input->post('crclm_id');
        $po_id = $this->input->post('po_id');
        $pp_id = $this->input->post('pp_id');
        $comment_results = $this->map_po_peo_model->fetch_justification($peo_id, $crclm_id, $po_id, $pp_id);
        echo json_encode($comment_results);
    }

}

/* End of file map_po_peo.php */
/* Location: ./application/curriculum/map_po_peo.php */
?>
