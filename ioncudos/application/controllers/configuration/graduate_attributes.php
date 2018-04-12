<?php

/**
 * Description          :	Controller Logic for Graduate Attributes Module(List, Add, Edit & Delete).
 * Created		:	23-03-2015. 
 * Modification History:
 * Date				Author			Description
 * 23-03-2015                  Jevi V. G.        Added file headers, function headers, indentations & comments.

  -------------------------------------------------------------------------------------------------
 */
?>
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Graduate_attributes extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('configuration/standards/ga/ga_model');
    }

// End of function __construct.

    /* Function is used to check the user logged_in & his user group & to load list view.
     * @param-
     * @returns - the list view of all graduate attributes details.
     */

    public function index() {
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
            $program_types = $this->ga_model->program_type_list();
            $data['program_types'] = $program_types;
            $result = $this->ga_model->ga_list();
            $data['records'] = $result['rows'];
            $data['title'] = 'Graduate Attributes List Page';
            $this->load->view('configuration/standards/ga/ga_list_vw', $data);
        }
    }

// End of function index.

    /* Function is used to load static list view of graduate attributes.
     * @param-
     * @retuns - the static (read only) list view of all graduate attributes.
     */

    public function static_ga_list() {
        //permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        }
        //permission_end
        else {

            $result = $this->ga_model->ga_list();
            $data['records'] = $result['rows'];
            $data['title'] = 'Graduate Attributes List Page';
            $this->load->view('configuration/standards/ga/static_ga_list_vw', $data);
        }
    }

// End of function static_ga_list.

    /* Function is used to load the add view of graduate attributes.
     * @param-
     * @returns - add view of graduate attributes.
     */

    public function ga_add_record() {
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

            $data['ga_reference'] = array(
                'name' => 'ga_reference',
                'id' => 'ga_reference',
                'class' => 'required onlyDigit',
                'type' => 'text',
                'placeholder' => 'Enter Sl No.',
                'autofocus' => 'autofocus'
            );
            $data['ga_statement'] = array(
                'name' => 'ga_statement',
                'id' => 'ga_statement',
                'class' => 'required char-counter_1',
                'rows' => '2',
                'cols' => '50',
                'maxlength' => '2000',
                'type' => 'textarea',
                'placeholder' => 'Enter Graduate Attribute Statement',
                'style' => "margin: 0px;"
            );
            $data['ga_description'] = array(
                'name' => 'ga_description',
                'id' => 'ga_description',
                'class' => 'po_type_textarea_size char-counter',
                'rows' => '3',
                'cols' => '50',
                'maxlength' => '2000',
                'type' => 'textarea',
                'placeholder' => 'Enter Graduate Attribute Description',
                'style' => "margin: 0px;"
            );
            $data['title'] = 'Graduate Attributes Add Page';
            $this->load->view('configuration/standards/ga/ga_add_vw', $data);
        }
    }

// End of function ga_add_record.

    /* Function is used to add a new ga details.
     * @param-
     * @returns - updated list view of ga 
     */

    public function ga_insert_record() {
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
            $ga_reference = $this->input->post('ga_reference');
            $ga_statement = $this->input->post('ga_statement');
            $ga_description = $this->input->post('ga_description');
            $insert_result = $this->ga_model->ga_insert($ga_reference, $ga_statement, $ga_description);

            redirect('configuration/graduate_attributes');
        }
    }

// End of function ga_insert_record.



    /* Function is used to load edit view of ga.
     * @param - ga id
     * @returns- edit view of ga.
     */

    public function ga_edit_record($ga_id) {
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
            $program_types = $this->ga_model->program_type_list();
            $data['program_types'] = $program_types;
            $data['result'] = $this->ga_model->ga_edit_record($ga_id);
            $data['pgmtype_selected'] = $data['result']['pgmtype_id'];
            $data['pgm_name'] = $this->ga_model->ga_edit_record_selected($data['pgmtype_selected']);
            $data['selected_name'] = $data['pgm_name'][0]['pgmtype_name'];
            $data['ga_id'] = array(
                'name' => 'ga_id',
                'id' => 'ga_id',
                'type' => 'hidden',
                'value' => $ga_id
            );

            $data['ga_reference'] = array(
                'name' => 'ga_reference',
                'id' => 'ga_reference',
                'class' => 'required onlyDigit',
                'type' => 'text',
                'value' => $data['result']['ga_reference'],
                'placeholder' => 'Enter Sl No.',
                'autofocus' => 'autofocus'
            );
            $data['ga_statement'] = array(
                'name' => 'ga_statement',
                'id' => 'ga_statement',
                'class' => 'required char-counter_1',
                'rows' => '2',
                'cols' => '50',
                'maxlength' => '2000',
                'type' => 'textarea',
                'value' => $data['result']['ga_statement'],
                'placeholder' => 'Enter Graduate Attribute Statement',
                'style' => "margin: 0px;"
            );
            $data['ga_description'] = array(
                'name' => 'ga_description',
                'id' => 'ga_description',
                'class' => 'po_type_textarea_size char-counter',
                'rows' => '3',
                'cols' => '50',
                'maxlength' => '2000',
                'type' => 'textarea',
                'value' => $data['result']['ga_description'],
                'placeholder' => 'Enter Graduate Attribute Description',
                'style' => "margin: 0px;"
            );

            $data['title'] = 'Graduate Attributes Edit Page';
            $this->load->view('configuration/standards/ga/ga_edit_vw', $data);
        }
    }

// End of function ga_edit_record.

    /* Function is used to update the details of a ga.
     * @param - 
     * @returns- updated list view of ga.
     */

    public function ga_update_record() {
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
            $ga_id = $this->input->post('ga_id');
            $ga_reference = $this->input->post('ga_reference');
            $ga_statement = $this->input->post('ga_statement');
            $ga_description = $this->input->post('ga_description');

            $result = $this->ga_model->edit_search_ga_name($ga_statement, $ga_statement);
            if ($result == 0) {
                $updated = $this->ga_model->ga_update($ga_id, $ga_reference, $ga_statement, $ga_description);
				echo 1;
            } else {
                echo 0;
            }
        }
    }

// End of function ga_update_record.

    /* Function is used to delete a ga.
     * @param- 
     * @retuns - a boolean value.
     */

    public function ga_delete() {
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
            $ga_id = $this->input->post('ga_id');
            $delete_result = $this->ga_model->ga_delete($ga_id);
            return TRUE;
        }
    }

// End of function ga_delete.


    /*     * ****************************************************************************************************************************************************** */

    /* Function is used to load the add view of graduate attributes.
     * @param-
     * @returns - add view of graduate attributes.
     */

    public function ga_add_record_new() {
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
            $program_types = $this->ga_model->program_type_list();
            $data['program_types'] = $program_types;
            $data['ga_reference'] = array(
                'name' => 'ga_reference',
                'id' => 'ga_reference',
                'class' => 'required onlyDigit',
                'type' => 'text',
                'placeholder' => 'Enter Sl No.',
                'autofocus' => 'autofocus'
            );
            $data['ga_statement'] = array(
                'name' => 'ga_statement',
                'id' => 'ga_statement',
                'class' => 'required char-counter_1',
                'rows' => '2',
                'cols' => '50',
                'maxlength' => '2000',
                'type' => 'textarea',
                'placeholder' => 'Enter Graduate Attribute Statement',
                'style' => "margin: 0px;"
            );
            $data['ga_description'] = array(
                'name' => 'ga_description',
                'id' => 'ga_description',
                'class' => 'po_type_textarea_size char-counter',
                'rows' => '3',
                'cols' => '50',
                'maxlength' => '2000',
                'type' => 'textarea',
                'placeholder' => 'Enter Graduate Attribute Description',
                'style' => "margin: 0px;"
            );
            $data['title'] = 'Graduate Attributes Add Page';
            //$data['val']=1;
            $this->load->view('configuration/standards/ga/ga_add_vw', $data);
        }
    }

// End of function ga_add_record.	

    public function fetch_data() {
        $pro_type_id = $this->input->post('pro_type_id');
        $result = $this->ga_model->ga_list_new($pro_type_id);
        $this->fetch_training($result);
    }

    /*
     * Function to display  my_training details
     * @param:
     * @return:
     */

    public function fetch_training($result) {
        $i = 1;
        //var_dump($result);
        if (!empty($result)) {
            foreach ($result as $data1) {
                $list[] = array(
                    'ga_reference' => $data1['ga_reference'],
                    'ga_statement' => $data1['ga_statement'],
                    'ga_description' => $data1['ga_description'],
                    'edit' => '<center><a role = "button" class="edit_pro"  href="' . base_url('configuration/graduate_attributes/ga_edit_record/' . $data1["ga_id"] . '') . '"><i class="icon-pencil icon-black"> </i></a></center>',
                    'Delete' => '<center><a role = "button"   class="delete_pro cursor_pointer" id="' . $data1['is_ga'] . '" data-id="' . $data1['ga_id'] . '"><i class="icon-remove icon-black"> </i></a></center>'
                );
                $i++;
            }
        } else {
            $list[] = array(
                'ga_reference' => "",
                'ga_statement' => "",
                'ga_description' => "",
                'edit' => '<a role = "button" class="edit_training "></a>',
                'Delete' => '<a role = "button"   class="delete_training " ></i></a>'
            );
        }
        //var_dump($list);exit;
        echo json_encode($list);
    }

    /* Function is used to add a new ga details.
     * @param-
     * @returns - updated list view of ga 
     */

    public function ga_insert_record_new() {
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
            $ga_reference = $this->input->post('ga_reference');
            $ga_statement = $this->input->post('ga_statement');
            $ga_description = $this->input->post('ga_description');
            $program_type = $this->input->post('program_type');

            $result = $this->ga_model->add_search_ga_name($ga_statement);
            if ($result == 0) {
                echo 1;
                $insert_result = $this->ga_model->ga_insert_new($ga_reference, $ga_statement, $ga_description, $program_type);
                //echo json_encode("true");
                redirect('configuration/graduate_attributes/ga_add_record_new');
            } else {
                echo 0;
            }
        }
    }

// End of function ga_insert_record.
}

// End of Class Graduate_attributes.
?>