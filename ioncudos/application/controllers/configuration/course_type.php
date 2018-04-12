<?php

/**
 * Description	:	Displays the course type list along with add, edit and delete options.
 * 					
 * Created		:	25-03-2013
 *
 * Author		:	
 * 		  
 * Modification History:
 *   Date                Modified By                			Description
 * 26-08-2013		   Arihant Prasad			File header, function headers, indentation 
  and comments.
 * 27-03-2014		   Jevi V. G				Added description field for course_type												
 * 29-09-2014			Jyoti					Added Weightage section for course_type
 * 29-10-2014		   Shayista Mulla			Added curriculum component name dropdown list 
  -------------------------------------------------------------------------------------------- */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Course_type extends CI_Controller {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->model('configuration/standards/course_type_model/course_type_model');
    }

    /**
     * This function checks for authentication and is used fetch and display the course type in the grid
     * @parameters: 
     * @return: returns all the course type id and course type name from the course type table
     */
    public function index() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        } else {
            $result = $this->course_type_model->course_type_list();
            $data['records'] = $result['rows'];
            $crclm_component_data = $this->course_type_model->curriculum_component_name_list();
            $data['crclm_component_name_data'] = $crclm_component_data['crclm_component_name_result'];

            $data['title'] = "Course Type List Page";
            $this->load->view('configuration/standards/course_type/course_list_vw', $data);
        }
    }

    /**
     * This function is used to load add view of course type.
     * @parameters: 
     * @return: an add view of course type.
     */
    function course_add_record() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        }

        $data['crs_type_name'] = array(
            'name' => 'course_type_name',
            'id' => 'course_type_name',
            'class' => 'required loginRegex input-medium',
            'type' => 'text',
            'autofocus' => 'autofocus'
        );
        $data['crs_type_description'] = array(
            'name' => 'crs_type_description',
            'id' => 'crs_type_description',
            'class' => 'char-counter',
            'rows' => '3',
            'cols' => '50',
            'type' => 'textarea',
            'maxlength' => "2000",
            'style' => "margin: 0px; width: 80%;"
        );
        $data['import'] = array(
            'name' => 'import',
            'id' => 'import',
            'class' => 'required ',
            'type' => 'hidden',
            'value' => '1',
            'size' => '20'
        );
        $data['title'] = "Course Type Add Page";
        $crclm_component_data = $this->course_type_model->curriculum_component_name_list();
        $data['crclm_component_name_data'] = $crclm_component_data['crclm_component_name_result'];
        $this->load->view('configuration/standards/course_type/course_add_vw', $data);
    }

    /**
     * This function is used to insert the course type onto the course type table 
     * @parameters: 
     * @return: updated list view of course type
     */
    function course_insert_record() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        }
        $crclm_component_name = $this->input->post('crclm_component_name');
        $course_type_name = $this->input->post('course_type_name');
        $course_type_description = $this->input->post('crs_type_description');
        $import = $this->input->post('import');
        $is_added = $this->course_type_model->course_insert($course_type_name, $course_type_description, $import, $crclm_component_name);
        redirect('configuration/course_type');
    }

    /**
     * This function checks for authentication and is used to delete the existing course type
     * @return: returns boolean 
     */
    function course_delete() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        } else {
            $course_type_id = $this->input->post('course_type_id');
            $is_delete = $this->course_type_model->course_delete($course_type_id);

            if ($is_delete) {
                echo "Course Type Deleted Successfully!";
            }
        }
    }

    /**
     * This function will perform authentication and also fetch the existing course type to be edited
     * @parameters: course type id is passed
     * @return: returns the course type pertaining to that id 
     */
    function course_edit($course_type_id) {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        } else {
            $data['result'] = $this->course_type_model->course_edit($course_type_id);

            $data['crs_type_id'] = array(
                'name' => 'course_type_id',
                'id' => 'course_type_id',
                'type' => 'hidden',
                'value' => $this->form_validation->set_value('course_type_id', $course_type_id),
            );
            $data['crs_type_name'] = array(
                'name' => 'course_type_name',
                'id' => 'course_type_name',
                'class' => 'required input-medium',
                'type' => 'text',
                'rows' => '2',
                'value' => $this->form_validation->set_value('course_type_name', $data['result']['crs_type_name']),
                'autofocus' => 'autofocus'
            );
            $data['crs_type_description'] = array(
                'name' => 'course_type_description',
                'id' => 'course_type_description',
                'class' => 'char-counter',
                'rows' => '3',
                'cols' => '50',
                'type' => 'textarea',
                'maxlength' => "2000",
                'style' => "margin: 0px; width: 80%;",
                'value' => $this->form_validation->set_value('course_type_description', $data['result']['crs_type_description']),
            );
            $data['import'] = $data['result']['crs_import_flag'];
            $data['crclm_component_id'] = $data['result']['crclm_component_id'];
            $crclm_component_data = $this->course_type_model->curriculum_component_name_list();
            $data['crclm_component_name_data'] = $crclm_component_data['crclm_component_name_result'];
            $this->load->view('configuration/standards/course_type/course_edit_vw', $data);
        }
    }

    /**
     * This function is used to check for authentication and to update the existing course type
     * @return: boolean value 
     */
    function course_update_record() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        } else {
            $crclm_component_name = $this->input->post('crclm_component_name');
            $course_type_id = $this->input->post('course_type_id');
            $course_type_name = $this->input->post('course_type_name');
            $course_type_description = $this->input->post('course_type_description');
            $import = $this->input->post('import');
            $updated = $this->course_type_model->course_update($course_type_id, $course_type_name, $course_type_description, $import, $crclm_component_name);
            redirect('configuration/course_type');
        }
    }

    /**
     * This function checks for authentication and for the uniqueness of the course type while adding the course type
     * @parameters: 
     * @return: returns all the details from the course type table for the existing course type
     */
    function add_unique_course_type() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        } else {
            $course_type_name = $this->input->post('course_type_name');
            $result = $this->course_type_model->add_unique_course_type($course_type_name);

            if ($result == 0) {
                echo 1;
            } else {
                echo 0;
            }
        }
    }

    /**
     * This function checks for authentication and for the uniqueness of the course type while editing the course type
     * @parameters: 
     * @return: returns all the details from the course type table for the existing course type
     */
    function unique_course_type() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        } else {
            $course_type_name = $this->input->post('course_type_name');
            $crs_type_id = $this->input->post('crs_type_id');
            $result = $this->course_type_model->unique_course_type($crs_type_id, $course_type_name);

            if ($result == 0) {
                echo 1;
            } else {
                echo 0;
            }
        }
    }

    /**
     * This function checks for authentication and to fetch and display the course type in the grid
     * @parameters: 
     * @return: returns all the course type id and course type name from the course type table
     */
    function static_course_type_list() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            $result = $this->course_type_model->course_type_list();
            $data['records'] = $result['rows'];

            $data['title'] = "Course Type List Page";
            $this->load->view('configuration/standards/course_type/static_course_list_vw', $data);
        }
    }

}

/**
 * End of file course_type.php 
 * Location: .configuration/course_type.php 
 */
?>
