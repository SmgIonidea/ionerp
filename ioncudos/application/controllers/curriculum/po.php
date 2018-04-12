<?php

/**
 * Description          :	To display, add and edit Program Outcomes

 * Created		:	March 30th, 2013

 * Author		:	 

 * Modification History:
 *   Date                   Modified By                         Description
 * 21-10-2013		   Arihant Prasad		File header, function headers, indentation, serial number and comments.
 * 04-01-2015		   Shayista Mulla 		Added loading image and cookie.
  ---------------------------------------------------------------------------------------------- */
?>

<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Po extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model('curriculum/po/po_model');
    }

    /**
     * Function to check for authentication and to load list program outcome view page
     * @parameters: curriculum id
     * @return: load program outcome list page
     */
    public function index($curriculum_id = NULL) {
        if (!$this->ion_auth->logged_in()) {
            //redirect user to the login page
            redirect('login', 'refresh');
        } else if (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman'))) {
            redirect('configuration/users/blank');
        } else {
            $data['crclm_id'] = $curriculum_id;
            $results = $this->po_model->list_curriculum();
            $skip_approval_flag_fetch = $this->po_model->skip_approval_flag_fetch();
            $data['curriculum_list'] = $results;
            $data['approval_flag'] = $skip_approval_flag_fetch;
            $data['title'] = $this->lang->line('so') . " List Page";
            $this->load->view('curriculum/po/list_po_vw', $data);
        }
    }

    /**
     * Function to check for authentication and to load static list program outcome view page
     * @return: load static list program outcome view page
     */
    public function static_index() {
        if (!$this->ion_auth->logged_in()) {
            //redirect user to the login page
            redirect('login', 'refresh');
        } else {
            $results = $this->po_model->list_curriculum();
            $data['curriculum_list'] = $results;
            $data['title'] = $this->lang->line('so') . " List Page";
            $this->load->view('curriculum/po/static_list_po_vw', $data);
        }
    }

    /**
     * Function to check authentication and to load help related to program outcome
     * @return: an object
     */
    function po_help() {
        if (!$this->ion_auth->logged_in()) {
            //redirect user to the login page
            redirect('login', 'refresh');
        } else if (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman'))) {
            redirect('configuration/users/blank');
        } else {
            $help_list = $this->po_model->po_help();

            if (!empty($help_list['help_data'])) {
                foreach ($help_list['help_data'] as $help) {
                    $po_id = '<i class="icon-black icon-file"> </i><a target="_blank" href="' . base_url('curriculum/po/po_content') . '/' . $help['serial_no'] . '">' . $help['entity_data'] . '</a></br>';

                    echo $po_id;
                }
            }

            if (!empty($help_list['file'])) {
                foreach ($help_list['file'] as $file_data) {
                    $file = '<i class="icon-black icon-book"> </i><a target="_blank" href="' . base_url('uploads') . '/' . $file_data['file_path'] . '">' . $file_data['file_path'] . '</a></br>';
                    echo $file;
                }
            }
        }
    }

    public function po_content($id) {
        $this->load->model('curriculum/po/po_model');
        $help_content = $this->po_model->help_content($id);
        $help['help_content'] = $help_content;
        $this->load->view('curriculum/po/po_help_content_vw', $help);
    }

    /**
     * Function to check authentication and to fetch curriculum details
     * @return: an object
     */
    public function select_po_curriculum() {
        if (!$this->ion_auth->logged_in()) {
            //redirect user to the login page
            redirect('login', 'refresh');
        } else if (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman'))) {
            redirect('configuration/users/blank');
        } else {
            $curriculum_id = $this->input->post('crclm_id');
            $results = $this->po_model->list_po($curriculum_id);

            $po_data = $results['po_list'];
            $po_cmt = $results['po_comment'];
            $po_state = $results['po_state'];
            $po_row_data = array();

            if (!empty($po_data)) {
                foreach ($po_data as $po_row) {
                    $comment = "";
                    $po_cmt_statement = "";

                    foreach ($po_cmt as $po_comment) {
                        if ($po_comment['po_id'] == $po_row['po_id'] && $po_comment['crclm_id'] == $po_row['crclm_id']) {
                            $po_cmt_statement = $po_comment['cmt_statement'];
                            $comment = $comment . "\n" . $po_cmt_statement . "\n";
                        }
                    }

                    if ($po_state[0]['state'] == 1 || $po_state[0]['state'] == 2 || $po_state[0]['state'] == 6) {
                          if($po_row['pso_flag'] == 0){
                              $po_reference = $po_row['po_reference']; 
                              $po_statement = $po_row['po_statement'];
                              $po_type_val = $po_row['po_type_name'];
                          }else{
                              $po_reference = '<font color="blue">'.$po_row['po_reference'].'</font>'; 
                              $po_statement = '<font color="blue">'.$po_row['po_statement'].'</font>';
                              $po_type_val = '<font color="blue">'.$po_row['po_type_name'].'</font>';
                          }
                        $po_row_data['po_list'][] = array(
                            'sl_no' => '<center>' . $po_reference . '</center>',
                            'po_statement' => $po_statement,
                            'po_type' => $po_type_val,
                            'po_cmt' => $comment,
                            'po_id' => '<center><a href="' . base_url('curriculum/po/edit_po') . '/' . $po_row['po_id'] . '"><i class="icon-pencil"></i></a></center>',
                            'po_id1' => '<center><a role="button"  data-toggle="modal" href="#myModal_delete" class="icon-remove get_id" id="' . $po_row['po_id'] . '" ></a></center>'
                        );
                    } else {
                        /* commented by shivaraj B */
                        if ($po_row['state_id'] == 7) {
                            if($po_row['pso_flag'] == 0){
                              $po_reference = $po_row['po_reference']; 
                              $po_statement = $po_row['po_statement'];
                              $po_type_val = $po_row['po_type_name'];
                          }else{
                              $po_reference = '<font color="blue">'.$po_row['po_reference'].'</font>'; 
                              $po_statement = '<font color="blue">'.$po_row['po_statement'].'</font>';
                              $po_type_val = '<font color="blue">'.$po_row['po_type_name'].'</font>';
                          }
                            $po_row_data['po_list'][] = array(
                                'sl_no' => '<center>' . $po_reference . '</center>',
                                'po_statement' => $po_statement,
                                'po_type' => $po_type_val,
                                'po_cmt' => $comment,
                                'po_id' => '<center><a href="' . base_url('curriculum/po/edit_po') . '/' . $po_row['po_id'] . '"><i class="icon-pencil"></i></a></center>',
                                'po_id1' => '<center><a role="button"  data-toggle="modal" href="#modal_cant_delete" class="icon-remove" id="' . $po_row['po_id'] . '" ></a></center>'
                            );
                        } else if ($po_row['state_id'] == 5) {
                            if($po_row['pso_flag'] == 0){
                              $po_reference = $po_row['po_reference']; 
                              $po_statement = $po_row['po_statement'];
                              $po_type_val = $po_row['po_type_name'];
                          }else{
                              $po_reference = '<font color="blue">'.$po_row['po_reference'].'</font>'; 
                              $po_statement = '<font color="blue">'.$po_row['po_statement'].'</font>';
                              $po_type_val = '<font color="blue">'.$po_row['po_type_name'].'</font>';
                          }
                            $po_row_data['po_list'][] = array(
                                'sl_no' => '<center>' . $po_reference . '</center>',
                                'po_statement' => $po_statement,
                                'po_type' => $po_type_val,
                                'po_cmt' => $comment,
                                'po_id' => '<a role="button"  data-toggle="modal" href="#modal_cant_edit"><i class="icon-pencil"></i></a>',
                                'po_id1' => '<a role="button"  data-toggle="modal" href="#modal_cant_delete_pending" class="icon-remove" id="' . $po_row['po_id'] . '" ></a>'
                            );
                        }else{ 
                             if($po_row['pso_flag'] == 0){
                              $po_reference = $po_row['po_reference']; 
                              $po_statement = $po_row['po_statement'];
                              $po_type_val = $po_row['po_type_name'];
                          }else{
                              $po_reference = '<font color="blue">'.$po_row['po_reference'].'</font>'; 
                              $po_statement = '<font color="blue">'.$po_row['po_statement'].'</font>';
                              $po_type_val = '<font color="blue">'.$po_row['po_type_name'].'</font>';
                          }
							$po_row_data['po_list'][] = array(
                            'sl_no' => '<center>' . $po_reference . '</center>',
                            'po_statement' => $po_statement,
                            'po_type' => $po_type_val,
                            'po_cmt' => $comment,
                            'po_id' => '<center><a href="' . base_url('curriculum/po/edit_po') . '/' . $po_row['po_id'] . '"><i class="icon-pencil"></i></a></center>',
                            'po_id1' => '<center><a role="button"  data-toggle="modal" href="#myModal_delete" class="icon-remove get_id" id="' . $po_row['po_id'] . '" ></a></center>'
                        );}
                    }
                }

                $po_row_data['po_state_data'] = array(
                    'state_name' => $po_state[0]['state_name'],
                    'state' => $po_state[0]['state']
                );

                echo json_encode($po_row_data);
            } else {
                $po_row_data['po_list'][] = array(
                    'sl_no' => '',
                    'po_statement' => 'No data available in table',
                    'po_type' => '',
                    'po_cmt' => '',
                    'po_id' => '',
                    'po_id1' => ''
                );

                $po_row_data['po_state_data'] = array(
                    'state_name' => '',
                    'state' => 0
                );

                echo json_encode($po_row_data);
            }
        }
    }

    /**
     * Function to check log-in and to fetch Program Outcomes details for static screen
     * @return: an object
     */
    public function static_select_po_curriculum() {
        if (!$this->ion_auth->logged_in()) {
            //redirect user to the login page
            redirect('login', 'refresh');
        } else {
            $curriculum_id = $this->input->post('crclm_id');
            $results = $this->po_model->list_po($curriculum_id);

            $po_data = $results['po_list'];
            $po_cmt = $results['po_comment'];
            $po_state = $results['po_state'];
            $po_row_data = array();
            if (!empty($po_data)) {
                foreach ($po_data as $po_row) {
                    $comment = "";
                    $po_cmt_statement = "";

                    foreach ($po_cmt as $po_comment) {
                        if ($po_comment['po_id'] == $po_row['po_id'] && $po_comment['crclm_id'] == $po_row['crclm_id']) {
                            $po_cmt_statement = $po_comment['cmt_statement'];
                            $comment = $comment . "\n" . $po_cmt_statement . "\n";
                        }
                    }

                    $po_row_data['po_list'][] = array(
                        'po_statement' => $po_row['po_statement'],
                        'po_type' => $po_row['po_type_name'],
                        'po_cmt' => $comment,
                        'po_id' => '<center><a href="' . base_url('curriculum/po/edit_po') . '/' . $po_row['po_id'] . '"><i class="icon-pencil"></i></a></center>',
                        'po_id1' => '<center><a role="button"  data-toggle="modal" href="#myModal_delete" class="icon-remove get_id" id="' . $po_row['po_id'] . '" ></a></center>'
                    );
                }
                $po_row_data['po_state_data'] = array(
                    'state_name' => $po_state[0]['state_name'],
                    'state' => $po_state[0]['state']);

                echo json_encode($po_row_data);
            } else {
                $po_row_data['po_list'][] = array(
                    'po_statement' => 'No data available in table',
                    'po_type' => '',
                    'po_cmt' => '',
                    'po_id' => '',
                    'po_id1' => ''
                );

                $po_row_data['po_state_data'] = array(
                    'state_name' => '',
                    'state' => 0);

                echo json_encode($po_row_data);
            }
        }
    }

    /**
     * Function to check authentication and to delete program outcome
     * @return: an object
     */
    public function po_delete() {
        if (!$this->ion_auth->logged_in()) {
            //redirect user to the login page
            redirect('login', 'refresh');
        } else if (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman'))) {
            redirect('configuration/users/blank');
        } else {
            $po_id = $this->input->post('po_id');
            $results = $this->po_model->delete_po($po_id);
            if ($results) {
                echo 'valid';
            } else {
                echo 'invalid';
            }
        }
    }

    /**
     * Function to check authentication and to load add program outcome page
     * @parameters: curriculum id
     * @return: loads add program outcome page
     */
    function add_po($curriculum_id = NULL) {
        if (!$this->ion_auth->logged_in()) {
            redirect('login', 'refresh');
        } else if (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman'))) {
            redirect('configuration/users/blank');
        } else {
            //var_dump($curriculum_id);
            $results = $this->po_model->list_po($curriculum_id);
            //	var_dump($results);
            $this->data['po_name'] = array(
                'name' => 'po_name_1',
                'id' => 'po_name_1',
                'class' => 'span3 required po_name',
                'type' => 'text',
                'autofocus' => 'autofocus'
            );

            $this->data['po_statement'] = array(
                'name' => 'po_statement_1',
                'id' => 'po_statement_1',
                'rows' => '2',
                'class' => 'input-xxlarge required po_stmt po_textarea_size',
                'type' => 'text',
                'style' => 'margin: 0px; height: 50px; width: 750px;',
                "maxlength" => "2000",
                'autofocus' => 'autofocus'
            );
            $this->data['crclm_id'] = array(
                'name' => 'crclm_id',
                'id' => 'crclm_id',
                'type' => 'hidden',
                'value' => $curriculum_id,
            );
            //Edited by Shivaraj B
            $this->data['pso_custom'] = array(
                'name' => 'pso_custom_1',
                'id' => 'pso_custom',
				'title' => $this->lang->line('entity_pso_singular') . " (" . $this->lang->line('entity_pso') . ")",
                'type' => 'checkbox',
                'value' => '1',
                'style' => 'margin-left:5%',
                'class' => 'form-control pso_custom'
            );

            $this->data['title'] = "Add " . $this->lang->line('so') . " Page";
            $this->data['po_types'] = $this->po_model->list_po_types($curriculum_id);
            $re = $this->po_model->list_po_types_new($curriculum_id);
            $this->data['crclm_name'] = $this->data['po_types']['curriculum_name'][0]['crclm_name'];
            $this->data['accredit'] = $this->data['po_types']['accredit_type'];
            $this->data['ga_list'] = $this->data['po_types']['ga_data'];
            //var_dump($this->data['ga_list']);
            $this->data['ga_list_new'] = $re['ga_data'];
            $this->data['po_state_id'] = $results['po_state'][0]['state_id'];
            $this->data['curriculum_id'] = $curriculum_id;
            $this->load->view('curriculum/po/add_po_vw', $this->data);
        }
    }

    /*     * fetch_accredit_countfetch_accredit_count
     * Function to check authentication and to insert new program outcome
     * @parameters: curriculum id
     * @return: load program outcome list page
     */

    function insert_po($curriculum_id = NULL) {
        
        if (!$this->ion_auth->logged_in()) {
            redirect('login', 'refresh');
        } else if (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman'))) {
            redirect('configuration/users/blank');
        } else {
            $counter = $this->input->post('counter');

            $counter_val = explode(",", $counter);

            for ($i = 0; $i < sizeof($counter_val); $i++) {
                $po_name[] = $this->input->post('po_name_' . $counter_val[$i]);
                $po_statement[] = $this->input->post('po_statement_' . $counter_val[$i]);
                $po_types[] = $this->input->post('po_types' . $counter_val[$i]);
                $ga_data[$i][] = $this->input->post('ga_data' . $counter_val[$i]);
                $pso_cb[] = $this->input->post('pso_custom_' . $counter_val[$i]);
            }

            $curriculum_id = $this->input->post('crclm_id');

            if ($this->input->post('crclm_id') == NULL) {
                redirect('/curriculum/po?error=no_crclm');
            }
            
			$po_state = $this->input->post('po_state');
            
            $is_added = ($this->po_model->add_po($curriculum_id, $po_name, $po_statement, $po_types, $ga_data, $pso_cb, $po_state));
            redirect('curriculum/po');
        }
    }

    /**
     * Function to check authentication and loads edit program outcome page
     * @parameters: program outcome id
     * @return: load program outcome edit page
     */
    function edit_po($po_id = NULL) {
        if (!$this->ion_auth->logged_in()) {
            //redirect user to the login page
            redirect('login', 'refresh');
        } else if (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman'))) {
            redirect('configuration/users/blank');
        } else {
            if (!$this->ion_auth->logged_in()) {
                redirect('configuration/users/login', 'refresh');
            }

            $data = $this->po_model->edit_po($po_id);

            $po_related_data = $data['po_data'];
            $ga_map_data = $data['ga_map_data'];
            $crclm_data = $data['curriculum_id'];

            $temp_crclm = $crclm_data[0]['crclm_id'];
            $po_data = $this->po_model->list_po_types($temp_crclm);
            $data['crclm_name'] = $po_data['curriculum_name'][0]['crclm_name'];

            $po_data_new = $this->po_model->list_po_types_new($temp_crclm);


            if (count($po_data_new['ga_data']) != 0) {
                foreach ($po_data_new['ga_data'] as $ga_list) {
                    $ga_list_options[$ga_list['ga_id']] = 'GA - ' . $ga_list['ga_reference'];
                }
                $data['ga_list'] = $ga_list_options;
            } else {
                $data['ga_list'] = " ";
            }

            if (count($data['ga_map_data']) != 0) {
                foreach ($data['ga_map_data'] as $ga_map_list) {
                    $ga_map_list_data[] = $ga_map_list['ga_id'];
                }$data['ga_map_list'] = $ga_map_list_data;
            } else {
                $data['ga_map_list'] = "";
            }

            foreach ($po_data['po_types_id_names'] as $listitem) {
                $select_options[$listitem['po_type_id']] = $listitem['po_type_name'];
            }

            $data['po_types'] = $select_options;
            $data['po_type_selected'] = $data['po_data']['0']['po_type_id'];

            $data['po_id'] = array(
                'name' => 'po_id',
                'id' => 'po_id',
                'type' => 'hidden',
                'value' => $po_id
            );

            $data['po_name'] = array(
                'name' => 'po_name',
                'id' => 'po_name',
                'class' => 'span3 required loginRegex1',
                'type' => 'text',
                'value' => $po_related_data[0]['po_reference']
            );
            //Edited by Shivaraj B 
            $checked = $po_related_data[0]['pso_flag'];
            if ($checked == 1) {
                $status = TRUE;
            } else {
                $status = FALSE;
            }
            $data['pso_cb'] = array(
                'name' => 'pso_custom',
                'id' => 'pso_custom',
                'class' => 'form-control pso_custom',
                'type' => 'checkbox',
                'value' => '1',
                'style' => 'margin-left:5%',
                'checked' => $status,
            );

            $data['title'] = "Edit " . $this->lang->line('so') . " Page";
            $this->load->view('curriculum/po/edit_po_vw', $data);
        }
    }

    /**
     * Function to check authentication and to save edited program outcomes details
     * @parameters: program outcome id
     * @return: load program outcome list page
     */
    function update_po($po_id = NULL) {
        if (!$this->ion_auth->logged_in()) {
            //redirect user to the login page
            redirect('login', 'refresh');
        } else if (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman'))) {
            redirect('configuration/users/blank');
        } else {
            $po_id = $this->input->post('po_id');
            $po_name = $this->input->post('po_name');
            $po_statement = $this->input->post('po_statement');
            $po_type_id = $this->input->post('po_type_id');
            $ga_data = $this->input->post('ga_data');
            $pso_data = $this->input->post('pso_custom');

            $is_added = ($this->po_model->update_po_individual($po_id, $po_name, $po_statement, $po_type_id, $ga_data, $pso_data));
            redirect('curriculum/po');
        }
    }

    /**
     * Function to check authentication and to send program outcome(s) for approval
     * @return: boolean
     */
    public function po_creator_publish_details() {
        if (!$this->ion_auth->logged_in()) {
            //redirect user to the login page
            redirect('login', 'refresh');
        } else if (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman'))) {
            redirect('configuration/users/blank');
        } else {
            $curriculum_id = $this->input->post('crclm_id');
            $results = $this->po_model->po_creator_approve_publish_db($curriculum_id);
            $update_po_state = $this->po_model->po_state_approval_update($curriculum_id);
            $receiver_id = $results['receiver_id'];
            $cc = '';
            $links = $results['url'];
            $entity_id = $results['entity_id'];
            $state = $results['state'];

            $this->ion_auth->send_email($receiver_id, $cc, $links, $entity_id, $state, $curriculum_id);
            return true;
        }
    }

    /**
     * Function to check authentication and to accept program outcome(s)
     * @return: boolean
     */
    public function bos_publish_details() {
        if (!$this->ion_auth->logged_in()) {
            //redirect user to the login page
            redirect('login', 'refresh');
        } else if (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('BOS'))) {
            redirect('configuration/users/blank');
        } else {
            $curriculum_id = $this->input->post('crclm_id');
            $results = $this->po_model->bos_approve_publish_db($curriculum_id);
            $update_po_state = $this->po_model->po_state_update($curriculum_id);
/*             $receiver_id = $results['receiver_id'];
            $cc = '';
            $links = $results['dashboard_data_po_peo_mapping']['url'];
            $entity_id = $results['dashboard_data_po_peo_mapping']['entity_id'];
            $state = $results['dashboard_data_po_peo_mapping']['state'];

            $this->ion_auth->send_email($receiver_id, $cc, $links, $entity_id, $state, $curriculum_id); */
            return true;
        }
    }

    /**
     * Function to check authentication and to load the program outcome approval grid
     * @parameters: curriculum id
     * @return: load program outcome approval grid
     */
    public function po_approval_grid($curriculum_id = NULL) {
        if (!$this->ion_auth->logged_in()) {
            //redirect user to the login page
            redirect('login', 'refresh');
        } else if (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('BOS'))) {
            redirect('configuration/users/blank');
        } else {
            $po_results = $this->po_model->po_approve_grid($curriculum_id);
            $po_results['crclm_id'] = $curriculum_id;
            $po_results['title'] = $this->lang->line('so') . " List Page";
            $this->load->view('curriculum/po/approval_po_vw', $po_results);
        }
    }

    /**
     * Function to check authentication and to insert comments
     * @return: boolean
     */
    public function po_comment_insert() {
        if (!$this->ion_auth->logged_in()) {
            //redirect user to the login page
            redirect('login', 'refresh');
        } else if (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('BOS'))) {
            redirect('configuration/users/blank');
        } else {
            $po_id = $this->input->post('po_id');
            $curriculum_id = $this->input->post('crclm_id');
            $po_comment = $this->input->post('po_cmt');
            $comment_status = 1;

            $results = $this->po_model->comment_insert($po_id, $curriculum_id, $po_comment, $comment_status);
            return true;
        }
    }

    /**
     * Function to check authentication and update dashboard with rework details
     * @return: boolean
     */
    public function po_rework_dashboard_insert() {
        if (!$this->ion_auth->logged_in()) {
            //redirect user to the login page
            redirect('login', 'refresh');
        } else if (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('BOS'))) {
            redirect('configuration/users/blank');
        } else {
            $curriculum_id = $this->input->post('crclm_id');
            $results = $this->po_model->po_rework_dashboard_entry($curriculum_id);

            $receiver_id = $results['receiver_id'];
            $cc = '';
            $links = $results['url'];
            $entity_id = $results['entity_id'];
            $state = $results['state'];

            $this->ion_auth->send_email($receiver_id, $cc, $links, $entity_id, $state, $curriculum_id);
            return true;
        }
    }

    /**
     * Function to check authentication and to load program outcome list page with comments from BOS (rework)
     * @parameters: curriculum id
     * @return: load program outcome list page
     */
    public function po_rework_grid($curriculum_id) {
        if (!$this->ion_auth->logged_in()) {
            //redirect user to the login page
            redirect('login', 'refresh');
        } else if (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman'))) {
            redirect('configuration/users/blank');
        } else {
            $po_result = $this->po_model->po_rework_page($curriculum_id);

            $po_data['po_result'] = $po_result['po_list'];
            $po_data['po_cmnt'] = $po_result['po_comment'];
            $po_data['crclm_name'] = $po_result['curriculum_name'];
            $po_data['title'] = $this->lang->line('so') . " List Page";
            $this->load->view('curriculum/po/list_po_vw', $po_data);
        }
    }

    /* Function is used to fetch current state of POs from dashboard table.
     * @param- curriculum id
     * @returns - an object.
     */

    public function fetch_po_current_state() {
        $curriculum_id = $this->input->post('crclm_id');
        $po_data = $this->po_model->fetch_po_current_state($curriculum_id);
        if ($po_data) {
            $list = $this->lang->line('student_outcomes_full') . ' ' . $this->lang->line('student_outcomes') . ' Current Status : ' . $po_data[0]['state_name'];

            echo $list;
        } else {
            $list = $this->lang->line('student_outcomes_full') . ' ' . $this->lang->line('student_outcomes') . ' Current Status : ' . $this->lang->line('sos') . ' Creation Not Initiated.';
            echo $list;
        }
    }
	// End of function fetch_po_current_state.
	
    //Function to create the Accreditation Grid
    public function accredit_po_grid() {
        $atype_id = $this->input->post('atype_id');
        $crclm_id = $this->input->post('crclm_id');
        $accredit_data = $this->po_model->fetch_accredit_count($atype_id, $crclm_id);
        
        $j = 1;
        $grid = "";

        foreach ($accredit_data['accreditation_data'] as $po_grid) {
            $grid .= "<div class='bs-docs-example'>";
            $grid .= "<div class='control-group'>";
            $grid .= "<div class='control-group span12'>";
            $grid .= "<div class='controls span4'>";
            $grid .= "<label class='control-label' for='po_name_" . $j . "'> " . $this->lang->line('so') . " Reference: <font color='red'> * </font></label>";
            $grid .= "<input name='po_name_" . $j . "' id='po_name_" . $j . "'  class='span3 po_name required'  type='text' autofocus='autofocus' value='" . $po_grid['atype_details_name'] . "' readonly>";
            $grid .= str_repeat('&nbsp;', 20) . $this->lang->line('entity_pso'). " Flag: <input type='checkbox' title='" . $this->lang->line('entity_pso_singular') . " (" . $this->lang->line('entity_pso') . ")' name='pso_custom_" . $j . "' id='pso_c' value='1' class='form-control pso_custom' style='margin-left:5%;'>";
            $grid .= "</div>";
            $grid .= "<div class='controls span3'>";
            $grid .= "<select id='po_types" . $j . "' name='po_types" . $j . "' class='input-medium po_types required'>";
            $grid .= "<option value=''>" . $this->lang->line('so') . " Types</option>";

            foreach ($accredit_data['po_types_id_names'] as $po_types) {
                if ($po_grid['po_type_id'] == $po_types['po_type_id']) {
                    $grid .= "<option value=" . $po_types['po_type_id'] . " selected='selected'>" . $po_types['po_type_name'] . "</option>";
                } else {
                    $grid .= "<option value=" . $po_types['po_type_id'] . ">" . $po_types['po_type_name'] . "</option>";
                }
            }

            $grid .= "</select>";
            $grid .= "</div>";

            $grid .= "<div class='controls span4 pull-right'>";
            $grid .= '<select id="ga_data' . $j . '" name="ga_data' . $j . '[]" class="ga_data input-medium" multiple="multiple">';
            foreach ($accredit_data['ga_data'] as $ga_list) {
                $grid .= '<option value="' . $ga_list['ga_id'] . '" title="' . $ga_list['ga_statement'] . '">GA - ' . $ga_list['ga_reference'] . '</option>';
            }
            $grid .= '</select>';
            $grid .= "</div>";

            $grid .= "</div>";
            $grid .= "<div class='controls'>";
            $grid .= "<label class='control-label' for='po_statement_" . $j . "'>" . $this->lang->line('so') . " Statement:<font color='red'>* </font></label>";
            $grid .= "<textarea name='po_statement_" . $j . "' style='margin: 0px; height: 50px; width: 750px;' id='po_statement_" . $j . "' rows='2'  class='input-large required po_stmt po_textarea_size' type='text' autofocus='autofocus'>" . $po_grid['atype_details_description'] . "</textarea>";
            $grid .= "</div></div></div>";
            $j++;
        }

        $grid.="<input type='hidden' value='" . ($j - 1) . "' class='append_class'>";
        echo $grid;
    }

// Function to Add more po statement fields

    public function po_types() {
        $po_count = $this->input->post('po_count');
        $crclm_id = $this->input->post('crclm_id');
        $po_type_data = $this->po_model->po_type_list($crclm_id);
        $po_type_list_data = $po_type_data['po_type_id'];
        $ga_data = $po_type_data['ga_data'];
        ++$po_count;
        $add_more = '';

        $add_more .= '<div class="bs-docs-example">';
        $add_more .= '<div class="control-group">';
        $add_more .= '<div class="control-group span12">';
        $add_more .= '<div class="controls span3">';
        $add_more .= '<label class="control-label" for="po_name_' . $po_count . '"> ' . $this->lang->line('so') . ' Reference: <font color="red"> * </font></label>';
        $add_more .= '<input name="po_name_' . $po_count . '" id="po_name_' . $po_count . '"  class="span3 po_name required" type="text" autofocus="autofocus" value="">';
        $add_more .= '</div>';
        $add_more .= '<div class="controls span3">';
        $add_more .= $this->lang->line('entity_pso')." Flag: <input type='checkbox' title='".$this->lang->line('entity_pso_singular'). ' ('. $this->lang->line('entity_pso').")' name='pso_custom_" . $po_count . "' id='pso_c' value='1' class='form-control pso_custom'>";
        $add_more .= '</div>';
        $add_more .= '<div class="controls span3">';
        $add_more .= '<select id="po_types' . $po_count . '" name="po_types' . $po_count . '" class="po_types input-medium required">';
        $add_more .= '<option value="">' . $this->lang->line('so') . ' Types</options>';
        foreach ($po_type_list_data as $po_types) {
            $add_more .= '<option value="' . $po_types['po_type_id'] . '">' . $po_types['po_type_name'] . '</option>';
        }
        $add_more .= '</select>';
        $add_more .= '</div>';
        $add_more .= '<div class="controls span3 pull-right">';
        $add_more .= '<select id="ga_data' . $po_count . '" name="ga_data' . $po_count . '[]" class="ga_data input-medium" multiple="multiple">';
        foreach ($ga_data as $ga_list) {
            $add_more .= '<option value="' . $ga_list['ga_id'] . '" title="' . $ga_list['ga_statement'] . '">GA - ' . $ga_list['ga_reference'] . '</option>';
        }
        $add_more .= '</select>';
        $add_more .= '</div>';
        $add_more .= '</div>';
        $add_more .= '<div class="controls">';
        $add_more .= '<label class="control-label" for="po_statement_' . $po_count . '">  ' . $this->lang->line('so') . '  Statement : <font color="red"> * </font></label>';
        $add_more .= '<textarea name="po_statement_' . $po_count . '" id="po_statement_' . $po_count . '" rows="2"  style="margin: 0px; height: 50px; width: 750px;" class="required po_stmt po_textarea_size" type="text" autofocus="autofocus">PO Statement</textarea>&nbsp;&nbsp;&nbsp;<a id="remove_field' . $po_count . '" class="Delete" href="#"><i class="icon-remove"></i> </a>';
        $add_more .= '</div></div>';
        $add_more .= '</div>';

        echo $add_more;
    }

    /**
     * Function to fetch bos user name and curriculum
     * @parameter: 
     * @return: an object
     */
    public function bos_user_name() {
        $curriculum_id = $this->input->post('curriculum_id');
        $bos_user = $this->po_model->bos_user($curriculum_id);
        $curriculum_data = $this->po_model->fetch_curriculum($curriculum_id);

        $bos_user_name = array(
            'bos_user_name' => $bos_user[0]['title'] . ' ' . $bos_user[0]['first_name'] . ' ' . $bos_user[0]['last_name'],
            'crclm_name' => $curriculum_data[0]['crclm_name']
        );

        echo json_encode($bos_user_name);
    }

    /**
     * Function to fetch chairman user name and curriculum
     * @parameter: 
     * @return: an object
     */
    public function chairman_user_name() {
        $curriculum_id = $this->input->post('curriculum_id');
        $chairman_user = $this->po_model->chairman_user($curriculum_id);
        $curriculum_data = $this->po_model->fetch_curriculum($curriculum_id);

        $chairman_user_name = array(
            'chairman_user_name' => $chairman_user[0]['title'] . ' ' . $chairman_user[0]['first_name'] . ' ' . $chairman_user[0]['last_name'],
            'crclm_name' => $curriculum_data[0]['crclm_name']
        );

        echo json_encode($chairman_user_name);
    }

    /* Function is used to insert the comments of mapping of PO to PEO onto the notes table.
     * @parameters: 
     * @return: a boolean value.
     */

    public function save_po_comment() {
        $curriculum_id = $this->input->post('crclm_id');
        $po_id = $this->input->post('po_id');
        $po_comment = $this->input->post('po_comment');

        $results = $this->po_model->po_comment_save($curriculum_id, $po_id, $po_comment);
        echo $results;
    }

    /**
     * Function to check for authentication and to load list program outcome view page
     * @parameters: curriculum id
     * @return: load program outcome list page
     * author: Bhagya S S
     */
    public function po_index($curriculum_id) {
        if (!$this->ion_auth->logged_in()) {
            //redirect user to the login page
            redirect('login', 'refresh');
        } else if (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman'))) {
            redirect('configuration/users/blank');
        } else {
            $data['crclm_id'] = $curriculum_id;
            $results = $this->po_model->list_curriculum();
            $skip_approval_flag_fetch = $this->po_model->skip_approval_flag_fetch();
            $data['curriculum_list'] = $results;
            $data['approval_flag'] = $skip_approval_flag_fetch;

            $data['title'] = $this->lang->line('so') . " List Page";
            $this->load->view('curriculum/po/list_po_vw', $data);
        }
    }

}

/*
 * End of file po.php
 * Location: .curriculum/po.php 
 */
?>
