<?php

/**
 * Description	:	Controller Logic for Program Type Module (List, Add, Edit, Enable/Disable).
 * Created		:	25-03-2013. 
 * Modification History:
 * Date				Modified By				Description
 * 20-08-2013		Abhinay B.Angadi        Added file headers, function headers, indentations & comments.
 * 27-08-2013		Abhinay B.Angadi		Variable naming, Function naming & Code cleaning.
  -------------------------------------------------------------------------------------------------
 */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Programtype extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('configuration/programtype/programtype_model');
    }

// End of function __construct.	

    /* Function is used to check weather the user logged_in & his user group.
     * @param- 
     * @retuns - the list view of all program type details.
     */

    public function index() {
        //permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/programtype/static_list_programtype', 'refresh');
        }
        //permission_end
        else {
            // $result stores an array values of program type details from table program type.			
            $result = $this->programtype_model->program_type_list();
            $data['records'] = $result['rows'];
            $data['title'] = 'Program Type List Page';
            $this->load->view('configuration/programtype/programtype_list_vw', $data);
        }
    }

// End of function index.

    /* Function is used to display the static list view.
     * @param-
     * @retuns - the static (read only) list view of all program type details.
     */

    public function static_list_programtype() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            // $result stores an array values of program type details from table program type.			
            $result = $this->programtype_model->program_type_list();
            $data['records'] = $result['rows'];
            $data['title'] = 'Program Type List Page';
            $this->load->view('configuration/programtype/static_programtype_list_vw', $data);
        }
    }

// End of function static_list_programtype.

    /* Function is used to load add program type view.
     * @param-
     * @returns - the add view of program type.
     */

    public function program_type_add_record() {
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
            $data['pgm_type_list'] = $this->programtype_model->fetch_pgm_type_list();
            //display the form & set the flash data error message if there is any
            $data['pgmtype_name'] = array(
                'name' => 'pgmtype_name',
                'id' => 'pgmtype_name',
                'class' => 'required',
                'type' => 'text',
                'placeholder' => "Enter Program Type Name"
            );
            $data['pgm_description'] = array(
                'name' => 'pgm_description',
                'id' => 'pgm_description',
                'class' => 'program_type_textarea_size char-counter',
                'rows' => '3',
                'cols' => '50',
                'type' => 'textarea',
                'maxlength' => "2000",
                'placeholder' => "Enter Program Type Description",
                'style' => "margin: 0px;"
            );
            $data['title'] = 'Program Type Add Page';
            $this->load->view('configuration/programtype/programtype_add_vw', $data);
        }
    }

// End of function program_type_add_record.

    /* Function is used to add a new program type details onto program type table.
     * @param-
     * @returns - the updated list view of program type.
     */

    public function program_type_insert_record() {
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
            $pgmtype_name = $this->input->post('pgmtype_name');
            $pgm_type = $this->input->post('pgm_type');
            $pgmtype_description = $this->input->post('pgm_description');
            // $result stores the boolean value returned after a insert operation onto the program type table.
            $result = $this->programtype_model->program_type_insert($pgmtype_name, $pgmtype_description, $pgm_type);
            redirect('configuration/programtype');
        }
    }

// End of function program_type_insert_record.	


    /* Function is used to load edit view a selected program type.
     * @param - program type id needed to fetch the details to be edited
     * @returns- an edit view of program type.
     */

    public function program_type_edit_record($pgmtype_id) {
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
            // $data stores an result array containing the values of program type details.
            $data['result'] = $this->programtype_model->program_type_search_by_id($pgmtype_id);
            $data['pgm_type_list'] = $this->programtype_model->fetch_pgm_type_list();
            $data['pgm_type_id'] = $data['result'][0]['pgm_type_id'];
            $data['pgm_description'] = array(
                'name' => 'pgm_description',
                'id' => 'pgm_description',
                'class' => 'program_type_textarea_size char-counter',
                'rows' => '3',
                'cols' => '50',
                'type' => 'textarea',
                'style' => "margin: 0px;",
                'maxlength' => "2000",
                'placeholder' => "Enter Program Type Description",
                'value' => $data['result'][0]['pgmtype_description']
            );
            $data['pgmtype_name'] = array(
                'name' => 'pgmtype_name',
                'id' => 'pgmtype_name',
                'class' => 'required',
                'type' => 'text',
                'rows' => '2',
                'placeholder' => "Enter Program Type Name",
                'value' => $data['result'][0]['pgmtype_name']
            );
            $data['pgmtype_id'] = array(
                'name' => 'pgmtype_id',
                'id' => 'pgmtype_id',
                'type' => 'hidden',
                'value' => $data['result'][0]['pgmtype_id']
            );

            $data['title'] = 'Program Type Edit Page';
            $this->load->view('configuration/programtype/programtype_edit_vw', $data);
        }
    }

// End of function program_type_edit_record.	

    /* Function is used to edit all the details of a selected program type.
     * @param - 
     * @returns- an updated list view of program type.
     */

    public function program_type_update_record() {
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
            $pgmtype_id = $this->input->post('pgmtype_id');
            $pgm_type = $this->input->post('pgm_type');
            $pgmtype_name = $this->input->post('pgmtype_name');
            $pgmtype_description = $this->input->post('pgm_description');

            // $result stores the boolean value returned after a update operation onto the program type table.
            $result = $this->programtype_model->program_type_update($pgmtype_id, $pgmtype_name, $pgmtype_description,$pgm_type);
            redirect('configuration/programtype');
        }
    }

// End of function program_type_update_record.		

    /* Function is used to update status of a program type onto program type table.
     * @param - 
     * @returns- a boolean value.
     */

    public function update_program_type_status() {
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
            $pgmtype_id = $this->input->post('pgmtype_id');
            $status = $this->input->post('status');

            // $status_data stores the boolean value returned after a set status update operation onto the program type table.
            $status_data['status_result'] = $this->programtype_model->program_type_update_status($pgmtype_id, $status);
            return TRUE;
        }
    }

// End of function update_program_type_status.	


    /* Function is used to search a program type from program type table.
     * @param - program type name.
     * @returns- a string value.
     */

    public function add_search_program_type_by_name() {
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
            $pgmtype_name = $this->input->post('pgmtype_name');
            // $result stores the boolean value returned after a search operation onto the program type table.
            $result = $this->programtype_model->add_search_program_type_by_name($pgmtype_name);

            if ($result == 0) {
                echo 1;
            } else {
                echo 0;
            }
        }
    }

// End of function add_search_program_type_by_name.

    /* Function is used to search a program type from program type table.
     * @param - program type name.
     * @returns- a string value.
     */

    public function search_program_type_by_name() {
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
            $pgmtype_name = $this->input->post('pgmtype_name');
            $pgmtype_id = $this->input->post('pgmtype_id');
            // $result stores the boolean value returned after a search operation onto the program type table.
            $result = $this->programtype_model->program_type_search_by_name($pgmtype_id, $pgmtype_name);

            if ($result == 0) {
                echo 1;
            } else {
                echo 0;
            }
        }
    }

// End of function search_program_type_by_name.	

    /* Function is used to check weather a program type exists onto program table for disabling.
     * @param - program type id.
     * @returns- a string.
     */

    public function program_type_name_is_used($pgmtype_id) {
        // $result stores the boolean value returned after a search operation onto the program table.
        $result = $this->programtype_model->program_type_name_is_used($pgmtype_id);
        if ($result == 0) {
            echo 'disable';
        } else {
            echo 'cannot disable';
        }
    }

// End of function program_type_name_is_used.	
}

// End of Class Programtype.	
?>