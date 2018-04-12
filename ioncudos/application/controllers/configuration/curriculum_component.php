<?php

/**
 * Description		:	Controller Logic for curriculum component (List, Add, Edit,Delete).
 * Created		:	22-10-2015 
 * Author		:	Shayista Mulla
 * Modification History:
 * Date				Modified By				Description
  ------------------------------------------------------------------------------------------------
 */
?>
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Curriculum_component extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('configuration/curriculum_component/curriculum_component_model');
    }

// End of function __construct.	

    /* Function is used to check weather the user logged_in & his user group.
     * @param- 
     * @retuns - the list view of all curriculum component details.
     */

    public function index() {

        //$this->load->view('configuration/cource_component/cource_component_list_vw');
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
            //} else if (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            //redirect('configuration/programtype/static_list_programtype', 'refresh');
        }
        //permission_end
        else {
            // $result stores an array values of curriculum component details from table curriculum component.
            $result = $this->curriculum_component_model->curriculum_component_list();
            $data['records'] = $result['rows'];
            $data['title'] = 'Curriculum Component List Page';
            $this->load->view('configuration/curriculum_component/curriculum_component_list_vw', $data);
        }
    }

//End of function index.

    /* Function is used to load edit view a selected curriculum component.
     * @param - curriculum component id needed to fetch the details to be edited
     * @returns- an edit view of curriculum component.
     */

    public function curriculum_component_add_record() {
        //permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        }
        //permission_end
        else {
            //display the form & set the flash data error message if there is any
            $data['curriculum_component_name'] = array(
                'name' => 'curriculum_component_name',
                'id' => 'curriculum_component_name',
                'class' => 'required',
                'type' => 'text',
                'placeholder' => "Enter Curriculum Component Name"
            );
            $data['curriculum_component_description'] = array(
                'name' => 'curriculum_component_description',
                'id' => 'curriculum_component_description',
                'class' => 'program_type_textarea_size char-counter',
                'rows' => '3',
                'cols' => '50',
                'type' => 'textarea',
                'maxlength' => "2000",
                'placeholder' => "Enter Curriculum Component Description",
                'style' => "margin: 0px;"
            );
            $data['title'] = 'Curriculum Component Add Page';
            $this->load->view('configuration/curriculum_component/curriculum_component_add_vw', $data);
        }
    }

//End of function curriculum_component_add_record

    /* Function is used to search a curriculum component from curriculum component table.
     * @param - curriculum component name.
     * @returns- a string value.
     */

    public function add_search_curriculum_component_by_name() {
        //permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        }
        //permission_end
        else {
            $curriculum_component_name = $this->input->post('curriculum_component_name');
            // $result stores the boolean value returned after a search operation onto the curriculum component table.
            $result = $this->curriculum_component_model->add_search_curriculum_component_by_name($curriculum_component_name);

            if ($result == 0) {
                echo 1;
            } else {
                echo 0;
            }
        }
    }

// End of function add_search_curriculum_component_by_name.

    /* Function is used to add a new curriculum component details onto curriculum component table.
     * @param-curriculum component name,curriculum component description
     * @returns - the updated list view of curriculum component.
     */

    public function curriculum_component_insert_record() {
        //permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        }
        //permission_end
        else {
            $curriculum_component_name = $this->input->post('curriculum_component_name');
            $curriculum_component_description = $this->input->post('curriculum_component_description');
            // $result stores the boolean value returned after a insert operation onto the curriculum component table.
            $result = $this->curriculum_component_model->curriculum_component_insert($curriculum_component_name, $curriculum_component_description);
        }
    }

// End of function curriculum_component_insert_record.	

    /* Function is used to load edit view a selected curriculum component.
     * @param - curriculum component id needed to fetch the details to be edited
     * @returns- an edit view of curriculum component.
     */

    public function curriculum_component_edit_record($pgmtype_id) {
        //permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        }
        //permission_end
        else {
            // $data stores an result array containing the values of curriculum component details.
            $data['result'] = $this->curriculum_component_model->curriculum_component_search_by_id($pgmtype_id);

            $data['crclm_component_desc'] = array(
                'name' => 'crclm_component_desc',
                'id' => 'crclm_component_desc',
                'class' => 'program_type_textarea_size char-counter',
                'rows' => '3',
                'cols' => '50',
                'type' => 'textarea',
                'style' => "margin: 0px;",
                'maxlength' => "2000",
                'placeholder' => "Enter Curriculum Component Description",
                'value' => $data['result'][0]['crclm_component_desc']
            );
            $data['crclm_component_name'] = array(
                'name' => 'crclm_component_name',
                'id' => 'crclm_component_name',
                'class' => 'required',
                'type' => 'text',
                'rows' => '2',
                'placeholder' => "Enter Curriculum Component Name",
                'value' => $data['result'][0]['crclm_component_name']
            );
            $data['cc_id'] = array(
                'name' => 'cc_id',
                'id' => 'cc_id',
                'type' => 'hidden',
                'value' => $data['result'][0]['cc_id']
            );

            $data['title'] = 'Curriculum Component Edit Page';
            $this->load->view('configuration/curriculum_component/curriculum_component_edit_vw', $data);
        }
    }

// End of function curriculum_component_edit_record.

    /* Function is used to search a curriculum component from curriculum component table.
     * @param - curriculum component name,curriculum component id.
     * @returns- a string value.
     */

    public function search_curriculum_component_by_name() {
        //permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        }
        //permission_end
        else {
            $curriculum_component_name = $this->input->post('crclm_component_name');
            $curriculum_component_id = $this->input->post('cc_id');
            // $result stores the boolean value returned after a search operation onto the curriculum component table.
            $result = $this->curriculum_component_model->curriculum_component_search_by_name($curriculum_component_id, $curriculum_component_name);

            if ($result == 0) {
                echo 1;
            } else {
                echo 0;
            }
        }
    }

// End of function search_curriculum_component_by_name.


    /* Function is used to edit all the details of a selected curriculum component.
     * @param -curriculum component id,curriculum component name,curriculum component description
     * @returns- an updated list view of curriculum component.
     */

    public function curriculum_component_update_record() {
        //permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        }
        //permission_end
        else {
            $curriculum_id = $this->input->post('cc_id');
            $curriculum_name = $this->input->post('crclm_component_name');
            $curriculum_description = $this->input->post('crclm_component_desc');

            // $result stores the boolean value returned after a update operation onto the curriculum component table.
            $result = $this->curriculum_component_model->curriculum_component_update($curriculum_id, $curriculum_name, $curriculum_description);
        }
    }

// End of function curriculum_component_update_record.	

    /* Function is used to delete a selected curriculum component.
     * @param -curriculum component id.
     * @returns- an deleted list view of curriculum component.
     */

    public function curriculum_component_delete_record() {
        //permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        }
        //permission_end
        else {
            $curriculum_component_id = $this->input->post('cc_id');
            $this->curriculum_component_model->curriculum_component_delete_record($curriculum_component_id);
        }
    }

// End of function curriculum_component_delete_record.
}

?>
