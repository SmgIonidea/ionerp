<?php

/**
 * Description          :	Controller logic for bloom's level for display, add, edit and delete.
 * 					
 * Created		:	25-03-2013
 *
 * Author		:	
 * 		  
 * Modification History:
 *   Date                   Modified By                		Description
 * 27-08-2013		   Arihant Prasad		File header, function headers, indentation and comments.
 *
  --------------------------------------------------------------------------------------------- */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Bloom_level extends CI_Controller {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->model('configuration/standards/bloom_level_model/bloom_level_model');
    }

    /* Function is to display the bloom's level, its description, its characteristics of learning 
      and its action verbs
     * @param-
     * @return: updated list view of bloom's level
     */

    public function index() {
        //permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        } else {
            $data['bloom_domain_list'] = $this->bloom_level_model->fetch_bloom_domain();

            $data['title'] = "Bloom's Level List Page";
            $this->load->view('configuration/standards/bloom_level/bloom_list_vw', $data);
        }
    }

    //function is to add new bloom's level to the list
    function bloom_add_record() {
        //permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        } else {
            $data['bloom_domain_list'] = $this->bloom_level_model->fetch_bloom_domain();
            $data['level'] = array(
                'name' => 'level',
                'id' => 'level',
                'class' => 'required noSpecialChars',
                'type' => 'text',
                'autofocus' => 'autofocus'
            );
            $data['assess_method'] = array(
                'name' => 'assess_method1',
                'id' => 'assess_method',
                'type' => 'text',
                'class' => 'required noSpecialChars'
            );

            $data['learning'] = array(
                'name' => 'learning',
                'id' => 'learning',
                'class' => 'required noSpecialChars',
                'type' => 'text'
            );

            $data['description'] = array(
                'name' => 'description',
                'id' => 'description',
                'class' => 'required noSpecialChars blooms_level_textarea_size',
                'style' => 'height: 60px;',
                'maxlength' => "2000",
                'type' => 'textarea'
            );
            $data['bloom_actionverbs'] = array(
                'name' => 'bloom_actionverbs',
                'id' => 'bloom_actionverbs',
                'rows' => '3',
                'cols' => '50',
                'maxlength' => "2000",
                'class' => 'required noSpecialChars blooms_level_textarea_size',
                'type' => 'textarea',
                'style' => "margin: 0px;"
            );

            $data['title'] = "Bloom's Level Add Page";
            $this->load->view('configuration/standards/bloom_level/bloom_add_vw', $data);
        }
    }

    /* Function is used to add a new bloom level details.
     * @param-
     * @returns - updated list view of bloom's level.
     */

    public function bloom_insert_record() {
        //permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        }
        //permission_end
        else {
            $bloom_domain = $this->input->post('add_bloom_domain');
            $bloom_level = $this->input->post('level');
            $bloom_learning = $this->input->post('learning');
            $bloom_description = $this->input->post('description');
            $bloom_actionverbs = $this->input->post('bloom_actionverbs');
            $bloom_counter = $this->input->post('array_counter');
            $assement_method_array = explode(",", $bloom_counter);
            $array_size = sizeof($assement_method_array);
            for ($i = 0; $i < $array_size; $i++) {
                $assessment_method[] = $this->input->post('assess_method' . $assement_method_array[$i]);
            }

            $is_added = $this->bloom_level_model->bloom_insert($bloom_level, $bloom_learning, $bloom_description, $bloom_actionverbs, $assessment_method, $bloom_domain);
            redirect('configuration/bloom_level');
        }
    }

    //function is to delete the existing bloom's level from the list
    function bloom_delete() {
        //permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        } else {
            $bloom_id = $this->input->post('bloom_id');
            $is_delete = $this->bloom_level_model->bloom_delete($bloom_id);

            if ($is_delete) {
                echo "Bloom's Level Deleted Successfully";
            }
        }
    }

    /**
     * Function is to fetch and display the existing bloom's level  
     * @parameters: bloom's id 
     * @return: bloom's level, bloom's description and bloom's action verb
     */
    function bloom_edit($bloom_id) {
        //permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        } else {
            $data = $this->bloom_level_model->bloom_fetch_details_to_edit($bloom_id);
            $data['bloom_domain_list'] = $this->bloom_level_model->fetch_bloom_domain();
            $bloom_level_data = $data['bloom_data'];
            $assessment_data = $data['assess_data'];
            $data['bloom_domain_id'] = $bloom_level_data[0]['bld_id'];

            $data['description'] = array(
                'name' => 'description',
                'id' => 'description',
                'class' => 'required noSpecialChars',
                'type' => 'text',
                'value' => $bloom_level_data[0]['description']
            );
            $data['learning'] = array(
                'name' => 'learning',
                'id' => 'learning',
                'class' => 'required noSpecialChars blooms_level_textarea_size',
                'style' => 'height: 60px;',
                'type' => 'textarea',
                'maxlength' => "2000",
                'value' => $bloom_level_data[0]['learning']
            );
            $data['bloom_id'] = array(
                'name' => 'bloom_id',
                'id' => 'bloom_id',
                'type' => 'hidden',
                'value' => $bloom_id
            );
            $data['level'] = array(
                'name' => 'level',
                'id' => 'level',
                'class' => 'required noSpecialChars',
                'type' => 'text',
                'rows' => '2',
                'value' => $bloom_level_data[0]['level'],
                'autofocus' => 'autofocus'
            );
            $data['bloom_actionverbs'] = array(
                'name' => 'bloom_actionverbs',
                'id' => 'bloom_actionverbs',
                'rows' => '3',
                'cols' => '50',
                'class' => 'required blooms_level_textarea_size',
                'maxlength' => "2000",
                'type' => 'textarea',
                'style' => "margin: 0px;",
                'value' => $bloom_level_data[0]['bloom_actionverbs']
            );
            if (!empty($assessment_data)) {
                $data['assess_method'] = array(
                    'name' => 'assess_method1',
                    'id' => 'assess_method1',
                    'class' => 'required ',
                    'type' => 'text',
                    'value' => $assessment_data[0]['assess_name']
                );
                $data['assess_method_id'] = array(
                    'name' => 'assess_method_id[]',
                    'id' => 'assess_method_id',
                    'class' => 'required ',
                    'type' => 'hidden',
                    'value' => $assessment_data[0]['assess_id']
                );
            } else {
                $data['assess_method'] = array(
                    'name' => 'assess_method1',
                    'id' => 'assess_method1',
                    'class' => 'required ',
                    'type' => 'text',
                );
                $data['assess_method_id'] = array(
                    'name' => 'assess_method_id[]',
                    'id' => 'assess_method_id',
                    'class' => 'required ',
                    'type' => 'hidden',
                );
            }
            $data['title'] = "Bloom's Level Edit Page";
            $this->load->view('configuration/standards/bloom_level/bloom_edit_vw', $data);
        }
    }

    //function is to update existing bloom's record
    function bloom_update_record() {
        //permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        } else {
            $bloom_domain = $this->input->post('bloom_domain');
            $bloom_id = $this->input->post('bloom_id');
            $bloom_level = $this->input->post('level');
            $bloom_learning = $this->input->post('learning');
            $bloom_description = $this->input->post('description');
            $bloom_action_verb = $this->input->post('bloom_actionverbs');

            // assessment values
            $assess_values = $this->input->post('assessment_val');
            $assess_counter_array = explode(",", $assess_values);
            $array_size = sizeof($assess_counter_array);
            $assess_id_array = $this->input->post('assess_method_id');

            for ($i = 0; $i < $array_size; $i++) {
                $assess_name[] = $this->input->post('assess_method' . $assess_counter_array[$i]);
            }

            $updated = $this->bloom_level_model->bloom_update($bloom_id, $bloom_domain, $bloom_level, $bloom_learning, $bloom_description, $bloom_action_verb, $assess_id_array, $assess_name, $assess_counter_array);

            redirect('configuration/bloom_level');
        }
    }

    /**
     * Function is used to count the number of bloom's level in the bloom level table
      and also checks for uniqueness. If count = 0, it means bloom's level does not exist
      and if count = 1, bloom's level already exist
     */
    function unique_bloom_level() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        } else {
            $bloom_levels = $this->input->post('bloom_level');
            $result = $this->bloom_level_model->unique_bloom_level($bloom_levels);

            if ($result == 0) {
                echo "valid";
            } else {
                echo "invalid";
            }
        }
    }

    /**
     * Function is used to count the number of bloom's level in the bloom level table
      and also checks for uniqueness. If count = 0, it means bloom's level does not exist
      and if count = 1, bloom's level already exist
     */
    function edit_unique_bloom_level() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        } else {
            $bloom_id = $this->input->post('bloom_id');
            $bloom_levels = $this->input->post('bloom_level');
            $result = $this->bloom_level_model->edit_unique_bloom_level($bloom_id, $bloom_levels);

            if ($result == 0) {
                echo 1;
            } else {
                echo 0;
            }
        }
    }

    // Function to display the static grid page to the guest user
    function static_bloom_list() {
        //permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            $result = $this->bloom_level_model->bloom_level_list();
            $data['records'] = $result['rows'];

            $data['title'] = "Bloom's Level List Page";
            $this->load->view('configuration/standards/bloom_level/static_bloom_list_vw', $data);
        }
    }

    /* Function is to list Bloom's Level details.
     * @parameters  :
     * returns      : an object.
     */

    public function bloom_level_list() {
        $bloom_domain_id = $this->input->post('bloom_domain_id');
        $result = $this->bloom_level_model->bloom_level_list($bloom_domain_id);
        $result = $result['rows'];

        if ($result) {

            foreach ($result as $records) {

                if ($records['is_bloom'] == 0 && $records['used_bloom'] == 0) {

                    $bloom_level = array(
                        'level' => $records['level'],
                        'description' => $records['description'],
                        'learning' => $records['learning'],
                        'bloom_actionverbs' => $records['bloom_actionverbs'],
                        'edit' => '<center><a title="Edit" class="" href="' . base_url('configuration/bloom_level/bloom_edit') . '/' . $records['bloom_id'] . '">
                                                <i class="icon-pencil icon-black"> </i></a></center>',
                        'delete' => '<center><a href = "#myModaldelete" id="' . $records['bloom_id'] . '" class=" get_id icon-remove" data-toggle="modal" data-original-title="Delete" rel="tooltip" title="Delete" value="' . $records['bloom_id'] . '" ></a></center>'
                    );
                } else {

                    $bloom_level = array(
                        'level' => $records['level'],
                        'description' => $records['description'],
                        'learning' => $records['learning'],
                        'bloom_actionverbs' => $records['bloom_actionverbs'],
                        'edit' => '<center><a class="" title="Edit" href="' . base_url('configuration/bloom_level/bloom_edit') . '/' . $records['bloom_id'] . '">
                                                <i class="icon-pencil icon-black"> </i></a></center>',
                        'delete' => '<center><a href = "#cant_delete" class=" get_id icon-remove" data-toggle="modal" data-original-title="Delete" rel="tooltip" title="Delete"></center>'
                    );
                }
                $list[] = $bloom_level;
            }
            echo json_encode($list);
        } else {
            echo json_encode($result);
        }
    }

}

/*
 * End of file bloom_level.php
 * Location: .configuration/bloom_level.php 
 */
?>