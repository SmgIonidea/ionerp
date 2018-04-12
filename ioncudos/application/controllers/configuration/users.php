<?php

/**
 *
 * Description          :	Displaying the list of users, adding new users, editing existing users
 * 					
 * Created		:	27-03-2013
 *
 * Author		:	
 * 		  
 * Modification History:
 *   Date                   Modified By                		Description
 * 02-09-2013		   Arihant Prasad		File header, function headers, indentation and comments.
 * 29-05-2017               Jyoti                       Modified for allowing special characters and label text value; Multiselect dropdown 
                                                        with checkboxes for user roles in User module
  ---------------------------------------------------------------------------------------------- */
?>

<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->helper('url');

        // Load MongoDB library instead of native db driver if required
        $this->config->item('use_mongodb', 'ion_auth') ?
                        $this->load->library('mongo_db') :
                        $this->load->database();

        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
    }

    //redirect to login page if login credentials are not met, else redirect to dashboard page
    function index() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            redirect("dashboard/dashboard", 'refresh');
        }
    }

    //Function will help admin to change users password
    function change_password() {
        //##permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        }

        //##permission_end
        else {
            $this->form_validation->set_rules('old', 'Old password', 'required');
            //while entering new password length of the password id also verified
            $this->form_validation->set_rules('new', 'New Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
            $this->form_validation->set_rules('new_confirm', 'Confirm New Password', 'required');

            if (!$this->ion_auth->logged_in()) {
                redirect('configuration/users/login', 'refresh');
            }

            $user = $this->ion_auth->user()->row();

            if ($this->form_validation->run() == false) {
                //display the form
                //set the flash data error message if there is one
                $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

                $this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
                $this->data['old_password'] = array(
                    'name' => 'old',
                    'id' => 'old',
                    'type' => 'password',
                );
                $this->data['new_password'] = array(
                    'name' => 'new',
                    'id' => 'new',
                    'type' => 'password',
                    'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
                );
                $this->data['new_password_confirm'] = array(
                    'name' => 'new_confirm',
                    'id' => 'new_confirm',
                    'type' => 'password',
                    'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
                );
                $this->data['user_id'] = array(
                    'name' => 'user_id',
                    'id' => 'user_id',
                    'type' => 'hidden',
                    'value' => $user->id,
                );

                //render
                $this->load->view('configuration/users/change_password', $this->data);
            } else {
                $identity = $this->session->userdata($this->config->item('identity', 'ion_auth'));
                $change = $this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));

                if ($change) {
                    //if the password was successfully changed
                    $this->session->set_flashdata('message', $this->ion_auth->messages());
                    $this->logout();
                } else {
                    $this->session->set_flashdata('message', $this->ion_auth->errors());
                    redirect('configuration/users/change_password', 'refresh');
                }
            }
        }
    }

    //forgot password (this function is meant for future use)
    function forgot_password() {
        //##permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        }

        //##permission_end
        else {
            $this->form_validation->set_rules('email', 'Email Address', 'required');
            if ($this->form_validation->run() == false) {
                //setup the input
                $this->data['email'] = array('name' => 'email',
                    'id' => 'email',
                );

                //set any errors and display the form
                $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
                $this->load->view('configuration/users/forgot_password', $this->data);
            } else {
                // get identity for that email
                $config_tables = $this->config->item('tables', 'ion_auth');
                $identity = $this->db->where('email', $this->input->post('email'))->limit('1')->get($config_tables['users'])->row();

                //run the forgotten password method to email an activation code to the user
                $forgotten = $this->ion_auth->forgotten_password($identity->{$this->config->item('identity', 'ion_auth')});

                if ($forgotten) {
                    //if there were no errors
                    $this->session->set_flashdata('message', $this->ion_auth->messages());
                    redirect("configuration/users/login", 'refresh');
                } else {
                    $this->session->set_flashdata('message', $this->ion_auth->errors());
                    redirect("configuration/users/forgot_password", 'refresh');
                }
            }
        }
    }

    /* reset password - final step for forgotten password (this function is meant for future use) 
     * @parameters: code	
     */

    public function reset_password($code = NULL) {
        //##permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        }

        //##permission_end
        else {
            if (!$code) {
                show_404();
            }

            $user = $this->ion_auth->forgotten_password_check($code);

            if ($user) {
                //if the code is valid then display the password reset form
                $this->form_validation->set_rules('new', 'New Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
                $this->form_validation->set_rules('new_confirm', 'Confirm New Password', 'required');

                if ($this->form_validation->run() == false) {
                    //display the form
                    //set the flash data error message if there is one
                    $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

                    $this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
                    $this->data['new_password'] = array(
                        'name' => 'new',
                        'id' => 'new',
                        'type' => 'password',
                        'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
                    );

                    $this->data['new_password_confirm'] = array(
                        'name' => 'new_confirm',
                        'id' => 'new_confirm',
                        'type' => 'password',
                        'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
                    );

                    $this->data['user_id'] = array(
                        'name' => 'user_id',
                        'id' => 'user_id',
                        'type' => 'hidden',
                        'value' => $user->id,
                    );
                    $this->data['csrf'] = $this->_get_csrf_nonce();
                    $this->data['code'] = $code;

                    //render
                    $this->load->view('configuration/users/reset_password', $this->data);
                } else {
                    // do we have a valid request?
                    if ($this->_valid_csrf_nonce() === FALSE || $user->id != $this->input->post('user_id')) {
                        //something fishy might be up
                        $this->ion_auth->clear_forgotten_password_code($code);
                        show_error('This form post did not pass our security checks.');
                    } else {
                        // finally change the password
                        $identity = $user->{$this->config->item('identity', 'ion_auth')};

                        $change = $this->ion_auth->reset_password($identity, $this->input->post('new'));

                        if ($change) {
                            //if the password was successfully changed
                            $this->session->set_flashdata('message', $this->ion_auth->messages());
                            $this->logout();
                        } else {
                            $this->session->set_flashdata('message', $this->ion_auth->errors());
                            redirect('configuration/users/reset_password/' . $code, 'refresh');
                        }
                    }
                }
            } else {
                //if the code is invalid then send them back to the forgot password page
                $this->session->set_flashdata('message', $this->ion_auth->errors());
                redirect("configuration/users/forgot_password", 'refresh');
            }
        }
    }

    /* activate the user present in the user list
     * @parenthesis: user id and code */

    function activate($user_id, $code = false) {
        //##permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        }

        //##permission_end
        else {
            $redirect_url = "configuration/users/list_users";

            if ($code != false) {
                $activation = $this->ion_auth->activate($user_id, $code);
            } else if ($this->ion_auth->is_admin()) {
                $activation = $this->ion_auth->activate($user_id);
            }

            if ($activation) {
                //redirect them to the auth page
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect($redirect_url);
            } else {
                //redirect them to the forgot password page
                $this->session->set_flashdata('message', $this->ion_auth->errors());
                redirect("configuration/users/forgot_password", 'refresh');
            }
        }
    }

    //Function to deactivate the user present in the user list
    function deactivate($user_id = NULL) {
        //##permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        }

        //##permission_end
        else {
            $redirect_url = "configuration/users/list_users";
            $user_id = $this->config->item('use_mongodb', 'ion_auth') ? (string) $user_id : (int) $user_id;
            $this->load->library('form_validation');
            $this->form_validation->set_rules('confirm', 'confirmation', 'required');
            $this->form_validation->set_rules('id', 'user ID', 'required|alpha_numeric');

            // added this function for js popup validatio and commenting the code below
            if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
                $this->ion_auth->deactivate($user_id);
            }

            //redirect them back to the auth page
            redirect($redirect_url);
        }
    }

    //Function to create a new user
    function create_user() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        } else {

            //#####################add additional fields here########################//
            $grouplist = $this->users_model->group_list();
            $designationlist = $this->users_model->designation_list();
            $departmentlist = $this->users_model->department_list();

            //validate form input
            $this->form_validation->set_rules('first_name', 'First Name', 'required|xss_clean|max_length[20]|regex_match[/^[A-Za-z\'][a-z0-9\\-\\s\\\'\,\.]+$/i]');
            $this->form_validation->set_rules('last_name', 'Last Name', 'xss_clean|max_length[20]');
            $this->form_validation->set_rules('email', 'Email Address', 'required|valid_email|max_length[50]');
            //$this->form_validation->set_rules('department', 'Department', 'required|valid_email|max_length[8]');

            $this->form_validation->set_rules('qualification', 'Highest Qualification Name', 'xss_clean|max_length[50]');
            $this->form_validation->set_rules('experience', 'Experience', 'xss_clean|max_length[5]');
            $this->form_validation->set_rules('designation_id', 'Designation Id', 'required|xss_clean|max_length[8]');


            $this->form_validation->set_rules('password', 'Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']');

            if ($this->form_validation->run() == true) {
                $username = strtolower($this->input->post('first_name')) . ' ' . strtolower($this->input->post('last_name'));
                $email = $this->input->post('email');
                $password = $this->input->post('password');

                $additional_data = array(
                    'title' => $this->input->post('user_title'),
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'user_qualification' => $this->input->post('qualification'),
                    'user_experience' => $this->input->post('experience'),
                    'email' => $this->input->post('email'),
					'contact' => $this->input->post('mobile-number'),
                    'user_dept_id' => $this->input->post('user_dept_id'),
                    'base_dept_id' => $this->input->post('user_dept_id'),
                    'created_by' => $this->ion_auth->user()->row()->id,
                    'create_date' => date('Y-m-d'),
                    'designation_id' => $this->input->post('designation_id')
                );
                $group = $this->input->post('usergroup_id');
            }

            if ($this->form_validation->run() == true && $uid = $this->ion_auth->register($username, $password, $email, $additional_data, $group)) {
                //check to see if we are creating the user
                //redirect them back to the admin page
                $this->ion_auth->activate($uid['id']);
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect("configuration/users/list_users", 'refresh');
            } else {
                //display the create user form
                //set the flash data error message if there is one
                $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

                $this->data['first_name'] = array(
                    'name' => 'first_name',
                    'id' => 'first_name',
                    'class' => 'required',
                    'type' => 'text',
                    'placeholder' => 'Enter First Name',
                    'value' => $this->form_validation->set_value('first_name'),
                );
                $this->data['last_name'] = array(
                    'name' => 'last_name',
                    'id' => 'last_name',
                    //'class' => 'required',
                    'type' => 'text',
                    'placeholder' => 'Enter Last Name',
                    'value' => $this->form_validation->set_value('last_name'),
                );
                $this->data['email'] = array(
                    'name' => 'email',
                    'id' => 'email',
                    'class' => 'required email',
                    'type' => 'text',
                    'placeholder' => 'Enter User Email',
                    'value' => $this->form_validation->set_value('email'),
                );
                $this->data['designation'] = $designationlist;
                $this->data['department'] = $departmentlist;


                $this->data['qualification'] = array(
                    'name' => 'qualification',
                    'id' => 'qualification',
                    //'class' => 'required',
                    'type' => 'text',
                    'placeholder' => 'Enter User Qualification',
                    'value' => $this->form_validation->set_value('qualification'),
                );
                $this->data['experience'] = array(
                    'name' => 'experience',
                    'id' => 'experience',
                    //'class' => 'required',
                    'type' => 'text',
                    'placeholder' => 'Enter User Experience',
                    'value' => $this->form_validation->set_value('experience'),
                );
                $this->data['password'] = array(
                    'name' => 'password',
                    'id' => 'password',
                    'class' => 'password',
                    //'readonly' => 'readonly',
                    //'class'=> 'required',
                    'minlength' => '8',
                    'type' => 'text',
                );
                $this->data['password_confirm'] = array(
                    'name' => 'password_confirm',
                    'id' => 'password_confirm',
                    //	'class'=> 'required', 
                    'type' => 'password',
                );
                $this->data['designation_id'] = array(
                    'name' => 'designation_id',
                    'id' => 'designation_id',
                    'type' => 'text',
                    'value' => $this->form_validation->set_value('designation_id'),
                );
                $this->data['group'] = $grouplist;

                $this->data['title'] = "User Add Page";
                $this->load->view('configuration/users/create_user', $this->data);
            }
        }
    }

    // Function to edit a existing user in the users list
    function edit_user($user_id) {
        //##permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        }

        //##permission_end
        else {
            //#####################add additional fields here########################//
            $this->data['title'] = "Edit User";
            $grouplist = $this->users_model->group_list();
            $designationlist = $this->users_model->designation_list();
            $departmentlist = $this->users_model->department_list();

            $user = $this->ion_auth->user($user_id)->row();

            //validate form input
            $this->form_validation->set_rules('first_name', 'First Name', 'required|xss_clean|max_length[20]');
            //####change#####

            $this->form_validation->set_rules('last_name', 'Last Name', 'xss_clean|max_length[20]');
            $this->form_validation->set_rules('department', 'Department', 'xss_clean|max_length[8]');
            $this->form_validation->set_rules('qualification', 'Highesr Qualification Name', 'xss_clean|max_length[50]');
            $this->form_validation->set_rules('experience', 'Experience', 'xss_clean|max_length[5]');
            //$this->form_validation->set_rules('email', 'Email Address', 'required|valid_email|max_length[50]|is_unique[users.email]');			
            $this->form_validation->set_rules('designation_id', 'Designation Id', 'required|xss_clean|max_length[8]');
            if ($this->input->post('email') != $user->email) {
                $this->form_validation->set_rules('email', 'Email Address', 'required|valid_email|max_length[50]|is_unique[users.email]');
            }

            if (isset($_POST) && !empty($_POST)) {
                // do we have a valid request?
                if ($user_id != $this->input->post('id')) {
                    show_error('This form post did not pass our security checks.');
                }

                $data = array(
                    'username' => strtolower($this->input->post('first_name')) . ' ' . strtolower($this->input->post('last_name')),
                    'title' => $this->input->post('user_title'),
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'email' => $this->input->post('email'),
					'contact' => $this->input->post('mobile-number'),
                    'user_dept_id' => $this->input->post('user_dept_id'),
                    'base_dept_id' => $this->input->post('user_dept_id'),
                    'user_qualification' => $this->input->post('qualification'),
                    'user_experience' => $this->input->post('experience'),
                    'modified_by' => $this->ion_auth->user()->row()->id,
                    'modify_date' => date('Y-m-d'),
                    'designation_id' => $this->input->post('designation_id')
                );

                $group = $this->input->post('usergroup_id');

                // DELETE user_id, dept_id, role_id FROM `map_user_dept_role` table - multi-user dept model.
                $this->users_model->delete_user_dept_role($user->id, $user->base_dept_id);

                //update the password if it was posted
                if ($this->input->post('reset_password')) {
                    $this->form_validation->set_rules('reset_password', 'Reset Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']');

                    $data['password'] = $this->input->post('reset_password');
                }

                if ($this->form_validation->run() === TRUE) {
                    // update user details
                    $this->ion_auth->update($user->id, $data);

                    $previous_groups = $this->ion_auth->get_users_groups($user->id)->result();
                    $kmax = sizeof($previous_groups);
                    $item = array();
                    for ($k = 0; $k < $kmax; $k++)
                        $item[] = $previous_groups[$k]->id;
                    $this->ion_auth->remove_from_group(NULL, $user->id);

                    $group = $this->input->post('usergroup_id');

                    foreach ($group as $value) {
                        $this->ion_auth->add_to_group($value, $user->id);
                        // INSERT user_id, dept_id, role_id onto `map_user_dept_role` table - multi-user dept model.
                        $user_dept_role_data = array(
                            'user_id' => $user->id,
                            'dept_id' => $data['user_dept_id'],
                            'role_id' => $value,
                            'modified_by' => $this->ion_auth->user()->row()->id,
                            'modified_date' => date('Y-m-d')
                        );
                        $this->users_model->insert_user_dept_role($user_dept_role_data);
                    }
                    //check to see if we are creating the user
                    //redirect them back to the admin page
                    $this->session->set_flashdata('message', "User Saved");
                    redirect("configuration/users/list_users", 'refresh');
                }
            }

            //display the edit user form
            $this->data['csrf'] = $this->_get_csrf_nonce();

            //set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            //pass the user to the view
            $this->data['user'] = $user;

            $this->data['first_name'] = array(
                'name' => 'first_name',
                'id' => 'first_name',
                'class' => 'required',
                'type' => 'text',
                'placeholder' => 'Enter First Name',
                'value' => $this->form_validation->set_value('first_name', $user->first_name),
            );
            $this->data['last_name'] = array(
                'name' => 'last_name',
                'id' => 'last_name',
                //'class' => 'required',
                'type' => 'text',
                'placeholder' => 'Enter Last Name',
                'value' => $this->form_validation->set_value('last_name', $user->last_name)
            );
            $this->data['qualification'] = array(
                'name' => 'qualification',
                'id' => 'qualification',
                //'class' => 'required',
                'type' => 'text',
                'placeholder' => 'Enter User Qualification',
                'value' => $this->form_validation->set_value('qualification', $user->user_qualification)
            );
            $this->data['experience'] = array(
                'name' => 'experience',
                'id' => 'experience',
                //'class' => 'required',
                'type' => 'text',
                'placeholder' => 'Enter User Experience',
                'value' => $this->form_validation->set_value('experience', $user->user_experience)
            );

            $this->data['selected_title'] = $user->title;
            $this->data['selected_designation'] = $user->designation_id;
            $this->data['selected_department'] = $user->base_dept_id;

            $this->data['designation'] = $designationlist;
            $this->data['department'] = $departmentlist;
            $this->data['password'] = array(
                'name' => 'reset_password',
                'id' => 'reset_password',
                //'readonly' => 'readonly',
                'class' => 'reset_password',
                'type' => 'text'
            );
            $this->data['password_confirm'] = array(
                'name' => 'password_confirm',
                'id' => 'password_confirm',
                'type' => 'password'
            );
            $this->data['designation_id'] = array(
                'name' => 'designation_id',
                'id' => 'designation_id',
                'type' => 'text',
                'value' => $this->form_validation->set_value('designation_id'),
            );
            $this->data['email'] = array(
                'name' => 'email',
                'id' => 'email',
                'type' => 'text',
                'placeholder' => 'Enter User Email',
                'value' => $this->form_validation->set_value('email', $user->email),
            );
            $this->data['update'] = array(
                'name' => 'submit',
                'id' => 'submit',
                'value' => 'true',
                'type' => 'submit',
                'class' => 'btn-icon btn-grey floatright',
                'content' => 'Update'
            );
			$this->data['contact'] = $user->contact;
            $this->data['group'] = $grouplist;
            $this->data['selected_group'] = $this->ion_auth->get_users_groups($user->id)->result();
            $kmax = sizeof($this->data['selected_group']);
            $item = array();
            for ($k = 0; $k < $kmax; $k++)
                $item[] = $this->data['selected_group'][$k]->id;
            $this->data['selected_group'] = $item;

            $this->data['title'] = "User Edit Page";
            $this->load->view('configuration/users/edit_user', $this->data);
        }
    }

    // Function to view user details and to create a new group
    function create_group() {
        //##permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        }

        //##permission_end
        else {
            $this->data['title'] = "Create Group";

            if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {

                redirect('configuration/users', 'refresh');
            }

            //validate form input
            $this->form_validation->set_rules('group_name', 'Group name', 'required|alpha_dash|xss_clean');
            $this->form_validation->set_rules('description', 'Description', 'xss_clean');

            if ($this->form_validation->run() == TRUE) {
                $new_group_id = $this->ion_auth->create_group($this->input->post('group_name'), $this->input->post('description'));
                if ($new_group_id) {
                    // check to see if we are creating the group
                    // redirect them back to the admin page
                    $this->session->set_flashdata('message', $this->ion_auth->messages());
                    redirect("configuration/users", 'refresh');
                }
            } else {
                //display the create group form
                //set the flash data error message if there is one
                $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

                $this->data['group_name'] = array(
                    'name' => 'group_name',
                    'id' => 'group_name',
                    'type' => 'text',
                    'value' => $this->form_validation->set_value('group_name'),
                );
                $this->data['description'] = array(
                    'name' => 'description',
                    'id' => 'description',
                    'type' => 'text',
                    'value' => $this->form_validation->set_value('description'),
                );

                $this->load->view('configuration/users/create_group', $this->data);
            }
        }
    }

    /* Function to edit a group 
     * @parameters: user id
     */

    function edit_group($user_id) {
        //##permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        }

        //##permission_end
        else {
            // bail if no group id given
            if (!$user_id || empty($user_id)) {
                redirect('configuration/users', 'refresh');
            }

            $this->data['title'] = "Edit Group";

            if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
                redirect('configuration/users', 'refresh');
            }

            $group = $this->ion_auth->group($user_id)->row();

            //validate form input
            $this->form_validation->set_rules('group_name', 'Group name', 'required|alpha_dash|xss_clean|max_length[20]');
            $this->form_validation->set_rules('group_description', 'Group Description', 'xss_clean|max_length[100]');

            if (isset($_POST) && !empty($_POST)) {
                if ($this->form_validation->run() === TRUE) {
                    $group_update = $this->ion_auth->update_group($user_id, $_POST['group_name'], $_POST['group_description']);

                    if ($group_update) {
                        $this->session->set_flashdata('message', "Group Saved");
                    } else {
                        $this->session->set_flashdata('message', $this->ion_auth->errors());
                    }
                    redirect("configuration/users", 'refresh');
                }
            }

            //set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            //pass the user to the view
            $this->data['group'] = $group;

            $this->data['group_name'] = array(
                'name' => 'group_name',
                'id' => 'group_name',
                'type' => 'text',
                'value' => $this->form_validation->set_value('group_name', $group->name),
            );

            $this->data['group_description'] = array(
                'name' => 'group_description',
                'id' => 'group_description',
                'type' => 'text',
                'value' => $this->form_validation->set_value('group_description', $group->description),
            );

            $this->load->view('configuration/users/edit_group', $this->data);
        }
    }

    /**/

    function _get_csrf_nonce() {
        //##permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        }

        //##permission_end
        else {
            $this->load->helper('string');
            $key = random_string('alnum', 8);
            $value = random_string('alnum', 20);
            $this->session->set_flashdata('csrfkey', $key);
            $this->session->set_flashdata('csrfvalue', $value);

            return array($key => $value);
        }
    }

    /**/

    function _valid_csrf_nonce() {
        //##permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        }

        //##permission_end
        else {
            if ($this->input->post($this->session->flashdata('csrfkey')) !== FALSE &&
                    $this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue')) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }

    //Function is used to fetch the permission id for the usergroup
    public function haspermission($permission_name) {
        //##permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        }

        //##permission_end
        else {
            $this->data = $this->ion_auth->get_users_groups($this->ion_auth->user()->row()->id)->result();
            $group_list = NULL;
            foreach ($this->data as $group)
                $group_list.= $group->id . ",";
            $group_list = rtrim($group_list, ',');
            $this->load->model('permission/permission_model');
            return $this->permission_model->has_permission($group_list, 'permission_name');
        }
    }

    // Function checks for the authentication and redirects the user to the dashboard.
    public function blank() {
        //##permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if ($this->ion_auth->in_group('Member') || $this->ion_auth->in_group('BOS')) {
            $this->load->view('welcome_template_vw', 'refresh');
        } else {
            redirect("dashboard/dashboard", 'refresh');
        }
        //##permission_end
    }

    // Function is to fetch the list of user from the users table
    public function list_users() {
        //##permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        }

        //##permission_end
        else {
            if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
                redirect('configuration/users/login', 'refresh');
            } else {
                $logged_in_base_dept_id = $this->ion_auth->user()->row()->base_dept_id;
                $this->data['users'] = $this->ion_auth
                        ->where('organization_name != 1')
                        ->where('base_dept_id', $logged_in_base_dept_id)
                        ->users()
                        ->result();
            }

            foreach ($this->data['users'] as $k => $user) {
                $this->data['users'][$k]->dept_name = $this->users_model
                        ->get_department_name_by_id($this->ion_auth->user($user->id)->row()->base_dept_id);
            }
            $data_result = $this->users_model->get_all_dept();
            $this->data['results'] = $data_result['dept'];
            $this->data['users'] = $data_result['user'];
            $this->data['title'] = "User List Page";
            $this->load->view('configuration/users/list_users_vw', $this->data); 
        }
    }

    //Function is used to add more than one user at the same time by uploading .csv file
    function bulk_users_upload() {
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'csv';
        $config['max_size'] = '100';

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload()) {
            $error = array('error' => $this->upload->display_errors());

            redirect("configuration/users/list_users", 'refresh');
        } else {
            $data = array('upload_data' => $this->upload->data());
            $data = array('upload_data' => $this->upload->data());
            $file_name = $data['upload_data']['file_name'];
            $result = $this->users_model->bulk_upload($file_name);
            $this->load->view('configuration/users/upload_success_vw', $data);
        }
    }

    // Function is to load static pages to the guest users
    function static_users_list() {
        //##permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        }

        //##permission_end
        else
            $this->data['users'] = $this->ion_auth
                    ->users()
                    ->result();

        foreach ($this->data['users'] as $k => $user) {
            $this->data['users'][$k]->dept_name = $this->users_model
                    ->get_department_name_by_id($this->ion_auth
                    ->user($user->id)
                    ->row()
                    ->user_dept_id);
        }

        $this->data['title'] = "User List Page";
        $this->load->view('configuration/users/static_list_users_vw', $this->data);
    }

    // Function to auto generate password string.
    function randomPassword() {
        $pass = '';
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
        for ($i = 0; $i < 8; $i++) {
            $n = rand(1, strlen($alphabet) - 1);
            $pass .= $alphabet[$n];
        }
        echo $pass;
    }

    /**
     * Function to fetch assessment method of a department to display in the grid
     * @return: an object
     */
    public function user_list() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        } else {
            $dept_id = $this->input->post('dept_id');
            $user_list_result = $this->users_model->user_list($dept_id);
            $data = $user_list_result['user'];
            $counter = 1;
            //$ao_method_list = array();
            $users_list = array();

            if ($data) {
                foreach ($data as $user_data) {
                    $title = $user_data['title'];
                    $first_name = $user_data['first_name'];
                    $last_name = $user_data['last_name'];
                    $dept_name = $user_data['dept_acronym'];
                    $designation = $user_data['designation_name'];
					$group_name = $user_data['group_name'];
                    $email = $user_data['email'];
                    if ($user_data['id'] != 1) {
                        $user_edit = '<center><a href="' . base_url('configuration/users/edit_user') . '/' . $user_data['id'] . '" readonly><i class="icon-pencil" rel="tooltip " title="Edit"></i></a></center>';
                    } else {
                        $user_edit = '<center><a data-toggle="modal" href="#cant_edit" ><i class="icon-pencil "></i></a><center>';
                    }

                    if ($user_data['id'] != 1) {

                        $user_status = ($user_data['active']) ? '<center><a class="status" href="#disable" id="' . $user_data['id'] . '" role="button" data-toggle="modal"><i class="icon-ban-circle"></i></a></center>' : '<center><a class="status" href="#enable" id="' . $user_data['id'] . '" role="button" id="' . $user_data['id'] . '" data-toggle="modal"><i class="icon-ok-circle"></i></a></center>';
                    } else {

                        $user_status = '<center><a data-toggle="modal" href="#cant_disable"><i class="icon-ban-circle"></i></a></center>';
                    }

                    $users_list['user_list'][] = array(
                        'first_name' => $title . ' ' . $first_name,
                        'last_name' => $last_name,
                        'dept_name' => $dept_name,
						'group_name' => $group_name,
                        'designation' => $designation,
                        'email' => $email,
                        'user_edit' => $user_edit,
                        'user_status' => $user_status
                    );
                    $counter++;
                }
                echo json_encode($users_list);
            } else {
                $users_list_no_data['user_list'][] = array(
                    'first_name' => 'No data available',
                    'last_name' => '',
                    'dept_name' => '',
					'group_name' => '',
                    'designation' => '',
                    'email' => '',
                    'user_edit' => '',
                    'user_status' => ''
                );
                echo json_encode($users_list_no_data);
            }
        }
    }

    function check_user() {
        $user_id = $this->input->post('user_id');
        $list_data = $this->users_model->enable_user_list($user_id);

        echo $list_data;
    }

    /* End of file users.php
     * Location: .configuration/users.php */
}
