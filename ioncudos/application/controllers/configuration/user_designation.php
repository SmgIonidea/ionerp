<?php
/**
* Description	:	Controller Logic for User Designation Module (List, Add, Edit & Delete).
* Created		:	03-04-2013. 
* Modification History:
* Date				Modified By				Description
* 22-08-2013		Abhinay B.Angadi        Added file headers, function headers, indentations & comments.
* 29-08-2013		Abhinay B.Angadi		Variable naming, Function naming & Code cleaning.
* 26-03-2014		Jevi V. G				Added description field for designation
-------------------------------------------------------------------------------------------------
*/
?>
<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_designation extends CI_Controller {

	public function __construct() {
        parent::__construct();
			$this->load->model('configuration/standards/user_designation/designation_model');	    
	}
	
	/* Function is used to check the user logged_in & his user group & to load list view.
	* @param-
	* @retuns - the list view of all user designation details.
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
			$result = $this->designation_model->designation_list();
            $data['records'] = $result['rows'];
            $data['title'] = 'User Designation List Page';
            $this->load->view('configuration/standards/user_designation/designation_list_vw', $data);
        }
    }// End of function index.

	/* Function is used to load static list view of user designation.
	* @param-
	* @retuns - the static (read only) list view of all user designation details.
	*/    
	public function static_designation_list() {
        //permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        }
        //permission_end
        else {
            $result = $this->designation_model->designation_list();
            $data['records'] = $result['rows'];
            $data['title'] = 'User Designation List Page';
            $this->load->view('configuration/standards/user_designation/static_designation_list_vw', $data);
        }
    }// End of function static_designation_list.
	
	/* Function is used to load the add view of user designation.
	* @param-
	* @returns - add view of user designation.
	*/     
	public function designation_add_record() {
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
			$data['designation_name'] = array(
							'name' 	=> 'designation_name',
							'id' 	=> 'designation_name',
							'class' => 'required',
							'type'  => 'text',
							'autofocus' => 'autofocus',
							'placeholder' => 'Enter Designation Name'
			);
			$data['designation_description'] = array(
						'name'	=> 'designation_description',
						'id' 	=> 'designation_description',
						'class' => 'user_designation_textarea_size char-counter',
						'rows'  => '3',
						'cols'  => '50',
						'maxlength' => '2000',
						'type'  => 'textarea',
						'style' => "margin: 0px;",
						'placeholder' => 'Enter Designation Description'
                );
			$data['title'] = 'User Designation Add Page';
			$this->load->view('configuration/standards/user_designation/designation_add_vw', $data);
		} 
     }// End of function designation_add_record.
	 
	/* Function is used to add a new user designation details.
	* @param-
	* @returns - updated list view of user designation.
	*/     
	public function designation_insert_record() {
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
			$designation_name = $this->input->post('designation_name');
			$designation_description = $this->input->post('designation_description');
			$add_result = $this->designation_model->designation_insert($designation_name,$designation_description);
			redirect('configuration/user_designation');
		}
	}// End of function designation_insert_record.	 

	/* Function is used to load edit view of user designation.
	* @param - user designation id
	* @returns- edit view of user designation.
	*/    
	public function designation_edit_record($designation_id) {
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
			$data['result'] = $this->designation_model->search_designation($designation_id);
			
            $data['designation_id'] = array(
						'name'  => 'designation_id',
						'id' 	=> 'designation_id',
						'type' 	=> 'hidden',
						'value' => $data['result'][0]['designation_id']
			);
			$data['designation_name'] = array(
							'name'  => 'designation_name',
							'id' 	=> 'designation_name',
							'class' => 'required',
							'type'  => 'text',
							'rows'  => '2',
							'value' => $data['result'][0]['designation_name'],
							'autofocus' => 'autofocus',
							'placeholder' => 'Enter Designation Name'
			);
			$data['designation_description'] = array(
						'name'	=> 'designation_description',
						'id' 	=> 'designation_description',
						'class' => 'user_designation_textarea_size char-counter',
						'rows'  => '3',
						'cols'  => '50',
						'type'  => 'textarea',
						'style' => "margin: 0px;",
						'maxlength' => '2000',
						'placeholder' => 'Enter Designation Description',
						'value' => $data['result'][0]['designation_description']
                );
			$data['title'] = 'User Designation Edit Page';
			$this->load->view('configuration/standards/user_designation/designation_edit_vw', $data);
		}
	}// End of function designation_edit_record.
	
	/* Function is used to update the details of a user designation onto user designation table.
	* @param - 
	* @returns- updated list view of user designation.
	*/    
	public function designation_update_record() {
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
			$designation_id = $this->input->post('designation_id');
			$designation_name = $this->input->post('designation_name');
			$designation_description = $this->input->post('designation_description');
			$update_result = $this->designation_model->designation_update($designation_id, $designation_name, $designation_description);
			redirect('configuration/user_designation');
		}
	}// End of function designation_update_record.	
	
	/* Function is used to delete a user designation.
	* @param- 
	* @retuns - a boolean value.
	*/    
	public function designation_delete() {
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
            $designation_id = $this->input->post('designation_id');
            $delete_result = $this->designation_model->designation_delete($designation_id);
            return TRUE;
        }
    }// End of function designation_delete.

	
	/* Function is used to search a user designation from user designation table.
	* @param - 
	* @returns- a string value.
	*/    
	public function check_user_designation_name() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        }
        //permission_end
        else {
			$designation_name = $this->input->post('designation_name');
            $result = $this->designation_model->check_user_designation_name($designation_name);
            if ($result == 0) {
                echo 1;
            } else {
                echo 0;
            }
        }
    }// End of function search_user_designation_name.

	
	/* Function is used to search a user designation from user designation table.
	* @param - 
	* @returns- a string value.
	*/    
	public function search_user_designation_name() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        }
        //permission_end
        else {
            $designation_id = $this->input->post('designation_id');
			$designation_name = $this->input->post('designation_name');
            $result = $this->designation_model->search_user_designation_name($designation_id,$designation_name);
            if ($result == 0) {
                echo 1;
            } else {
                echo 0;
            }
        }
    }// End of function search_user_designation_name.

}// End of Class User_designation.

?>