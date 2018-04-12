<?php
/**
* Description	:	Controller Logic for BOS Module (Add).
* Created		:	25-03-2013. 
* Modification History:
* Date				Modified By				Description
* 23-08-2013		Abhinay B.Angadi        Added file headers, function headers, indentations & comments.
* 30-08-2013		Abhinay B.Angadi		Variable naming, Function naming & Code cleaning.
* 29-05-2017               Jyoti               Modified for allowing special characters and label text value
-------------------------------------------------------------------------------------------------
*/
?>

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Addbos extends CI_Controller {
	
	public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->helper('url');
        // Load MongoDB library instead of native db driver if required
        $this->config->item('use_mongodb', 'ion_auth') ?
                        $this->load->library('mongo_db') :
                        $this->load->database();
        $this->load->model('configuration/bos/bos_model');
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
    }// End of function __construct.

    /* Function is used to check weather the user logged_in & his user group 
	* and to add new BOS user details onto the user table.
	* @param- 
	* @retuns - add view of BOS user.
	*/
	public function create_user() {
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
			$this->data['title'] = 'Add BOS Member';
            //$this->load->model('configuration/users/users_model');
            //$grouplist = $this->users_model->group_list();
			
			//Function to fetch the designations list form designation table.
            $designationlist = $this->bos_model->designation_list();
            
			//Function to fetch the departments list form department table.
			$departmentlist = $this->bos_model->department_list();
			
			//Function to fetch the general department id (default_department) from department table.
            $default_department_result = $this->bos_model->default_department();
            $default_department = $default_department_result['dept_id'];

            //validate form input
            $this->form_validation->set_rules('first_name', 'First Name', 'required|xss_clean|max_length[20]|regex_match[/^[A-Za-z\'][a-z\\-\\s\\\'\,\.]+$/i]');
            $this->form_validation->set_rules('last_name', 'Last Name', 'xss_clean|max_length[20]');
            //$this->form_validation->set_rules('organization', 'Organization Name', 'required|xss_clean|max_length[50]]');
            $this->form_validation->set_rules('email', 'Email Address', 'required|valid_email|max_length[50]');
            //$this->form_validation->set_rules('department', 'Department', 'required|valid_email|max_length[8]');
            $this->form_validation->set_rules('qualification', 'Highest Qualification Name', 'xss_clean|max_length[50]');
            $this->form_validation->set_rules('experience', 'Experience', 'xss_clean|max_length[2]');
            $this->form_validation->set_rules('designation_id', 'Designation Id', 'required|xss_clean|max_length[8]');
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']');
            //$this->form_validation->set_rules('password_confirm', 'Password Confirmation', 'required');
            $this->form_validation->set_rules('status', 'Status', '');

            if ($this->form_validation->run() == true) {
				//Built-in functions to convert first name & last name string to lower case letters & concatenate those as username. 
				$username = strtolower($this->input->post('first_name')) . ' ' . strtolower($this->input->post('last_name'));
                $email = $this->input->post('email');
                $password = $this->input->post('password');

                $additional_data = array(
							'title' 		 => $this->input->post('user_title'),
							'first_name' 		 => $this->input->post('first_name'),
							'last_name' 		 => $this->input->post('last_name'),
							'user_qualification' 	 => $this->input->post('qualification'),
							'user_experience' 	 => $this->input->post('experience'),
							'email' 		 => $this->input->post('email'),
							'contact' => $this->input->post('mobile-number'),
							'base_dept_id' 		 => $this->input->post('bos_dept_id'),
							'user_dept_id'		 => $this->input->post('default_department'),
							'created_by' 		 => $this->ion_auth->user()->row()->id,
							'create_date'		 => date('Y-m-d'),
							'designation_id'	 => $this->input->post('designation_id')
                );
				//$group = $this->input->post('usergroup_id');
				$group = array('15');
            }
            if ($this->form_validation->run() == true && $uid = $this->ion_auth->register($username, $password, $email, $additional_data, $group)) {
                //check to see if we are creating the user
                //redirect them back to the admin page
				$this->ion_auth->activate($uid['id']);
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect('configuration/bos/list_bos', 'refresh');
            } else {
                //display the create user form
                //set the flash data error message if there is one
                $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

                $this->data['first_name'] = array(
								'name'  => 'first_name',
								'id'    => 'first_name',
								'class' => 'required',
								'type'  => 'text',
								'placeholder' => 'Enter First Name',
								'autofocus' => 'autofocus'
								//'value' => $this->form_validation->set_value('first_name')
                );
                $this->data['last_name'] = array(
								'name'  => 'last_name',
								'id'    => 'last_name',
								//'class' => 'required',
								'type'  => 'text',
								'placeholder' => 'Enter Last Name',
								'value' => $this->form_validation->set_value('last_name')
                );
                $this->data['organization'] = array(
								'name'  => 'organization',
								'id'    => 'organization',
								'class' => 'required',
								'type'  => 'text',
								'placeholder' => 'Enter Organization Name',
								'value' => $this->form_validation->set_value('organization')
                );
                $this->data['email'] = array(
							'name'  => 'email',
							'id'    => 'email',
							'class' => 'required email',
							'type'  => 'text',
							'placeholder' => 'Enter User Email',
							'value' => $this->form_validation->set_value('email')
                );

                $this->data['designation'] = $designationlist;
                $this->data['department'] = $departmentlist;

                $this->data['default_department'] = array(
										'name'  => 'default_department',
										'id'    => 'default_department',
										'class' => 'required',
										'type'  => 'hidden',
										'value' => $this->form_validation->set_value('default_department', $default_department)
                );
				$this->data['qualification'] = array(
								'name'  => 'qualification',
								'id' 	=> 'qualification',
								//'class' => 'required',
								'type'  => 'text',
								'placeholder' => 'Enter User Qualification',
								'value' => $this->form_validation->set_value('qualification')
                );
                $this->data['experience'] = array(
								'name' 	=> 'experience',
								'id'	=> 'experience',
								//'class' => 'required',
								'type'  => 'text',
								'placeholder' => 'Enter User Experience',
								'value' => $this->form_validation->set_value('experience')
                );
                $this->data['password'] = array(
							'name' 		=> 'password',
							'id' 		=> 'password',
							'minlength' 	=> '8',
							'type'		=> 'text'
                );
                $this->data['designation_id'] = array(
									'name'  => 'designation_id',
									'id' 	=> 'designation_id',
									'type'  => 'text',
									'value' => $this->form_validation->set_value('designation_id')
                );
                $data['title'] = 'BOS Add Page';
                $this->load->view('configuration/bos/create_newbos_user', $this->data);
            }
        }
    }// End of function create_user.

}// End of Controller Class Addbos.

?>
