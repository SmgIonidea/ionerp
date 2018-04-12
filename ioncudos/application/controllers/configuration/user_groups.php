<?php
/**
 * Description	:	To display the existing group and provisions to edit and delete the groups.
					Permission(s) allocated for each group can also be viewed
 * 					
 * Created		:	25-03-2013
 *
 * Author		:	
 * 		  
 * Modification History:
 *   Date                Modified By                			Description
 * 26-08-2013		   Arihant Prasad			File header, function headers, indentation 
												and comments.
 *
  ---------------------------------------------------------------------------------------------- */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User_groups extends CI_Controller {
    function __construct() {
        // Call the Model constructor
        parent::__construct();
		$this->load->model('configuration/user_groups/user_groups_model');
    }

	/**
     * Function checks for the authentication and then loads the user group page through view after 
	   fetching the user groups, its corresponding description and permission associated with it.
	 * @return: returns all the rows containing user groups and their description
     */
    public function index() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/user_groups/static_user_groups_list', 'refresh');
        } else {
            $result = $this->user_groups_model->user_groups_search();
            $data['result_grid_details'] = $result['rows'];

            $this->load->view('configuration/user_groups/user_groups_list_vw', $data);
        }
    }

    /**
     * Function checks for authentication and fetches the permissions 
	   for a particular user group
	 * @return: will return all the permissions to be displayed
	 */
    public function get_permission() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            $user_group_id = $this->input->post('user_group_id');
            $result = $this->user_groups_model->get_permission($user_group_id);
			$output = '<table class="table table-bordered">';
            $serial = 1;
			
            $output.="<th><b>Sl. No</b></td></th>";
            $output.="<th><b>Permission</b></td></th>";
            
			foreach ($result as $each_row) {
			    $output.="<tr><td>" . $serial . "</td>";
                $output.="<td>" . $each_row['permission_function'] . "</td>";
                $serial++; 
            }
			$output.='</table>';
            echo $output;
        }
    }

    /**
     * Function checks for the user authentication, fetches all the permissions that are available 
	   in the permission table (while adding new user group)
	 * @return: will return all the permissions to be displayed or will return boolean expression
	 */
    public function add_new_user_group() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/user_groups/blank', 'refresh');
        } else {
            $group_name = $this->input->post('name');
            $group_description = $this->input->post('description');

            $result = $this->user_groups_model->get_permission_list();
            $this->data['permission_list'] = $result;
            
			$this->data['name'] = array(
				'name' => 'name',
				'id' => 'name',
				'class' => 'required',
				'type' => 'text'
			);
			$this->data['description'] = array(
				'name' => 'description',
				'id' => 'description',
				'style' => 'vertical-align:top',
				'type' => 'textarea',
				'rows' => 4,
				'cols' => 30
			);

			$data['title'] = "User Groups Add Page";
			$this->load->view("configuration/user_groups/user_groups_add_vw", $this->data);
        }
    }

	/**
     * Function checks for the user authentication and to insert new user group
	 * @return: load user group list page
	 */
    public function insert_new_user_group() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/user_groups/blank', 'refresh');
        } else {
                $group_name = $this->input->post('name');
                $group_description = $this->input->post('description');
                $list = $this->input->post('list');

                $results = $this->user_groups_model->user_groups_add($group_name, $group_description, $list);
                redirect('configuration/user_groups/user_groups');
        }
    }
	
	/**
     * Function checks for the user authentication and also is used to delete an existing user group from the list
	 * @return: boolean
	 */
    function user_groups_delete() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/user_groups/blank', 'refresh');
        } else {
            $user_group_id = $this->input->post('user_group_id');
            $this->ion_auth->delete_group($user_group_id);
			
			return true;            
        }
    }

    /**
     * The function checks for authentication, make changes (edit) to the existing group, fetch selected & stored permissions
	   and display the permissions.
     * @parameters: user group id 
	 * @return: returns all the details related to that user group or will return boolean expression
	 */
    function user_groups_edit($user_group_id) {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/user_groups/blank', 'refresh');
        } else {
            $result_model = $this->user_groups_model->get_permission_list();
            $this->data['permission_list'] = $result_model;
            $result_permission_list = $this->user_groups_model->selected_permission_list($user_group_id);
            $this->data['selected_permission_list'] = $result_permission_list;

            $result = $this->user_groups_model->user_groups_edit($user_group_id);            
			$this->data['name'] = array(
				'name' => 'name',
				'id' => 'name',
				'class' => 'required noSpecialChars',
				'type' => 'text',
				'value' => $result[0]['name'],
                                'readonly' => 'readonly'
			);
			$this->data['user_group_id'] = array(
				'name' => 'user_group_id',
				'id' => 'user_group_id',
				'type' => 'hidden',
				'value' => $user_group_id                               
			);
			$this->data['description'] = array(
				'name' => 'description',
				'id' => 'description',
				'style' => 'vertical-align:top',
				'type' => 'textarea',
				'rows' => 4,
				'cols' => 30,
				'value' => $result[0]['description'],
                                'readonly' => 'readonly'
			);

			$this->load->view('configuration/user_groups/user_groups_edit_vw', $this->data);
        }
    }

    /**
	 * The function is used to check for the authentication and updates the existing group 
	 * @return: boolean
	 */
    public function update_user_groups() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/user_groups/blank', 'refresh');
        } else {
            $group_name = $this->input->post('name');
            $group_description = $this->input->post('description');
            $user_group_id = $this->input->post('user_group_id');
            $list = $this->input->post('list');
			
            $results = $this->user_groups_model->user_groups_update($user_group_id, $group_name, $group_description, $list);
            redirect('configuration/user_groups');
        }
    }

    /* The function is used to check for the authentication and loads the static user group page in read only mode 
	 * @return: returns all the rows containing user groups and their description
	 */
    public function static_list_usergroups() {
        if (!$this->ion_auth->logged_in()) {
            //redirect user to the login page
            redirect('login', 'refresh');
        }
		
        $result = $this->user_groups_model->user_groups_search();
        $data['result_grid_details'] = $result['rows'];
        $data['deleted'] = 0;

        $data['title'] = "User Groups List Page";
        $this->load->view('configuration/user_groups/static_user_groups_list_vw', $data);
    }
}

/*
 * End of file user_groups.php
 * Location: .configuration/user_groups.php 
 */
?>