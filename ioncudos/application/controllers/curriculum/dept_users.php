<?php

/**
 * Description          :	Department users list, add, edit - Controller
 * Created		:	27-03-2013
 * Author		:	Arihant Prasad
 * Modification History:
 *   Date                    Modified By                	Description
 *  4/12/2016               Bhagyalaxmi S S                 Addedd faculty  Contribution Column
 * 29-05-2017               Jyoti                       Modified for allowing special characters and label text value; Multiselect dropdown 
                                                        with checkboxes for user roles in User module
  --------------------------------------------------------------------------------------------------- */
?>

<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dept_users extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->library('Crypter');
        $this->load->library('form_validation');
        $this->load->model('curriculum/dept_users/dept_users_model');
    }

    /**
     * Function is to fetch the list of user from the users table
     * @parameters:
     * @return: load list page
     */
    public function index() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner'))) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        } else {
            $data = $this->dept_users_model->dept_user_list();

            $data['title'] = "Department Users List Page";
            $this->load->view('curriculum/dept_users/list_dept_users_vw', $data);
        }
    }

    /**
     * Function to add new user to users list
     * @parameters:
     * @return: load add user page
     */
    public function create_dept_user() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner'))) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        } else {
            $grouplist = $this->dept_users_model->group_list();
            $designationlist = $this->dept_users_model->designation_list();
            $departmentlist = $this->dept_users_model->department_list();

            $user_dept_id = $this->ion_auth->user()->row()->user_dept_id;
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
                'user_dept_id' => $user_dept_id,
                'designation_id' => $this->input->post('designation_id'),
                'base_dept_id' => $user_dept_id,
                'created_by' => $this->ion_auth->user()->row()->id,
                'create_date' => date("Y-m-d")
            );
            $group = $this->input->post('usergroup_id');

            if (!empty($additional_data) && !empty($group) && $uid = $this->ion_auth->register($username, $password, $email, $additional_data, $group)) {
                $this->ion_auth->activate($uid['id']);
                redirect("curriculum/dept_users/index", 'refresh');
            } else {
                //set the flash data error message if there is one
                $data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

                $data['first_name'] = array(
                    'name' => 'first_name',
                    'id' => 'first_name',
                    'class' => 'required',
                    'type' => 'text',
                    'placeholder' => 'Enter First Name',
                    'value' => $this->input->post('first_name')
                );

                $data['last_name'] = array(
                    'name' => 'last_name',
                    'id' => 'last_name',
                    //'class' => 'required',
                    'type' => 'text',
                    'placeholder' => 'Enter Last Name',
                    'value' => $this->input->post('last_name')
                );

                $data['email'] = array(
                    'name' => 'email',
                    'id' => 'email',
                    'class' => 'required email',
                    'type' => 'text',
                    'placeholder' => 'Enter User Email',
                    'value' => $this->input->post('email')
                );

                $data['designation'] = $designationlist;
                $data['department'] = $departmentlist;

                $data['qualification'] = array(
                    'name' => 'qualification',
                    'id' => 'qualification',
                    //'class' => 'required',
                    'type' => 'text',
                    'placeholder' => 'Enter User Qualification',
                    'value' => $this->input->post('qualification')
                );

                $data['experience'] = array(
                    'name' => 'experience',
                    'id' => 'experience',
                    //'class' => 'required',
                    'type' => 'text',
                    'placeholder' => 'Enter User Experience',
                    'value' => $this->input->post('experience')
                );

                $data['password'] = array(
                    'name' => 'password',
                    'id' => 'password',
                    'class' => 'required password',
                    'minlength' => '8',
                    'type' => 'text',
                );

                $data['designation_id'] = array(
                    'name' => 'designation_id',
                    'id' => 'designation_id',
                    'class' => 'required',
                    'type' => 'text',
                    'value' => $this->input->post('designation_id')
                );

                $data['group'] = $grouplist;

                $data['title'] = "Department User Add Page";
                $this->load->view('curriculum/dept_users/create_dept_user_vw', $data);
            }
        }
    }

    /**
     * Function to edit a existing user in the users list
     * @parameters:
     * @return: load edit user page
     */
    function edit_dept_user($e_dept_user_id = NULL) {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner'))) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        } else {
            $grouplist = $this->dept_users_model->group_list();
            $designationlist = $this->dept_users_model->designation_list();
            $departmentlist = $this->dept_users_model->department_list();

            //decrypt
            //$dept_user_id = $this->crypter->c_decode($e_dept_user_id);
            $dept_user_id = $this->crypter->c_decode($e_dept_user_id);
            $user = $this->ion_auth->user($dept_user_id)->row();

            //validate email - check if email is repeated and whether it is in right format
            if ($this->input->post('email') != $user->email) {
                $this->form_validation->set_rules('email', 'Email Address', 'required|valid_email|max_length[50]|is_unique[users.email]');
            }

            //if changes are made in edit page
            if (isset($_POST) && !empty($_POST)) {
                //fetch dept id
                $user_dept_id = $this->ion_auth->user()->row()->user_dept_id;

                $data = array(
                    'username' => strtolower($this->input->post('first_name')) . ' ' . strtolower($this->input->post('last_name')),
                    'title' => $this->input->post('user_title'),
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'email' => $this->input->post('email'),
					'contact' => $this->input->post('mobile-number'),
                    'user_dept_id' => $user_dept_id,
                    'base_dept_id' => $user_dept_id,
                    'user_qualification' => $this->input->post('qualification'),
                    'user_experience' => $this->input->post('experience'),
                    'designation_id' => $this->input->post('designation_id')
                );
                $group = $this->input->post('usergroup_id');

                // DELETE user_id, dept_id, role_id FROM `map_user_dept_role` table - multi-user dept model.
                $this->dept_users_model->delete_user_dept_role($user->id, $user->base_dept_id);

                //update the password if it was posted - reset password is not manadatory
                if ($this->input->post('reset_password')) {
                    $data['password'] = $this->input->post('reset_password');
                }

                //	if ($this->form_validation->run($data) === TRUE) 
                {
                    // update user details
                    $this->ion_auth->update($user->id, $data);

                    $previous_groups = $this->ion_auth->get_users_groups($user->id)->result();
                    $kmax = sizeof($previous_groups);
                    $item = array();

                    for ($k = 0; $k < $kmax; $k++) {
                        $item[] = $previous_groups[$k]->id;
                    }

                    $this->ion_auth->remove_from_group(NULL, $user->id);
                    $group = $this->input->post('usergroup_id');

                    foreach ($group as $value) {
                        $this->ion_auth->add_to_group($value, $user->id);

                        $user_dept_role_data = array(
                            'user_id' => $user->id,
                            'dept_id' => $user_dept_id,
                            'role_id' => $value,
                            'modified_by' => $this->ion_auth->user()->row()->id,
                            'modified_date' => date('Y-m-d')
                        );
                        $this->dept_users_model->insert_user_dept_role($user_dept_role_data);
                    }

                    //redirect user back to department user list page
                    redirect("curriculum/dept_users/index", 'refresh');
                }
            }

            //load edit page
            //display the edit user form
            $data['csrf'] = $this->_get_csrf_nonce();

            //set the flash data error message if there is one
            $data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            //pass the user to the view
            $data['user'] = $user;

            //set_value is to re-populate the form on reload 
            $data['first_name'] = array(
                'name' => 'first_name',
                'id' => 'first_name',
                'class' => 'required',
                'type' => 'text',
                'placeholder' => 'Enter First Name',
                'value' => $this->form_validation->set_value('first_name', $user->first_name)
            );

            $data['last_name'] = array(
                'name' => 'last_name',
                'id' => 'last_name',
                //'class' => 'required',
                'type' => 'text',
                'placeholder' => 'Enter Last Name',
                'value' => $this->form_validation->set_value('last_name', $user->last_name)
            );

            $data['qualification'] = array(
                'name' => 'qualification',
                'id' => 'qualification',
                //'class' => 'required',
                'type' => 'text',
                'placeholder' => 'Enter User Qualification',
                'value' => $this->form_validation->set_value('qualification', $user->user_qualification)
            );

            $data['experience'] = array(
                'name' => 'experience',
                'id' => 'experience',
                //'class' => 'required',
                'type' => 'text',
                'placeholder' => 'Enter User Experience',
                'value' => $this->form_validation->set_value('experience', $user->user_experience)
            );

            $data['selected_title'] = $user->title;
            $data['selected_designation'] = $user->designation_id;
            $data['designation'] = $designationlist;

            $data['password'] = array(
                'name' => 'reset_password',
                'id' => 'reset_password',
                'class' => 'reset_password',
                'type' => 'text'
            );

            $data['designation_id'] = array(
                'name' => 'designation_id',
                'id' => 'designation_id',
                'type' => 'text',
                'value' => $this->form_validation->set_value('designation_id')
            );

            $data['email'] = array(
                'name' => 'email',
                'id' => 'email',
                'type' => 'text',
                'placeholder' => 'Enter User Email',
                'value' => $this->form_validation->set_value('email', $user->email)
            );

            $data['update'] = array(
                'name' => 'submit',
                'id' => 'submit',
                'value' => 'true',
                'type' => 'submit',
                'class' => 'btn-icon btn-grey floatright',
                'content' => 'Update'
            );

            $data['group'] = $grouplist;
            $data['selected_group'] = $this->ion_auth->get_users_groups($user->id)->result();
            $kmax = sizeof($data['selected_group']);
            $item = array();

            for ($k = 0; $k < $kmax; $k++)
                $item[] = $data['selected_group'][$k]->id;
            $data['selected_group'] = $item;

            $data['title'] = "Department User Edit Page";
            $data['user_id'] = $dept_user_id;
			$data['contact'] = $user->contact;
            $this->load->view('curriculum/dept_users/edit_dept_user_vw', $data);
        }
    }

    /* Function to activate the user present in the user list
     * @parameters: user id
     * @return: 
     */

    function activate($user_id) {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner'))) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        } else {
            $activation = $this->dept_users_model->activate($user_id);
        }
    }

    /* Function to deactivate the user present in the user list
     * @parameters: user id
     * @return: 
     */

    function deactivate($user_id) {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner'))) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        } else {
            $deactivation = $this->dept_users_model->deactivate($user_id);
        }
    }

    /* not clear */

    function _get_csrf_nonce() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner'))) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        } else {
            $this->load->helper('string');
            $key = random_string('alnum', 8);
            $value = random_string('alnum', 20);
            $this->session->set_flashdata('csrfkey', $key);
            $this->session->set_flashdata('csrfvalue', $value);

            return array($key => $value);
        }
    }

    /**
     * Function to auto generate password string.
     * @parameters:
     * @return:
     */
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
     * Function to check authentication and to load help related to Department User List 
     * @parameters  :
     * @return      : an object
     */
    function dept_user_help() {
        if (!$this->ion_auth->logged_in()) {
            //redirect user to the login page
            redirect('login', 'refresh');
        } else if (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman'))) {
            redirect('configuration/users/blank');
        } else {
            $help_list = $this->dept_users_model->dept_user_help();

            if (!empty($help_list['help_data'])) {
                foreach ($help_list['help_data'] as $help) {
                    $po_id = '<i class="icon-black icon-file"> </i><a target="_blank" href="' . base_url('curriculum/dept_users/dept_user_content') . '/' . $help['serial_no'] . '">' . $help['entity_data'] . '</a></br>';

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

    /*
     * Function is to load Department user list help content in view page.
     * @parameters  :
     * returns      : view page.
     */

    public function dept_user_content($id) {
        $help_content = $this->dept_users_model->help_content($id);
        $help['help_content'] = $help_content;
        $this->load->view('curriculum/dept_users/dept_user_help_content_vw', $help);
    }

    public function check_user() {
        $user_id = $this->input->post('user_id');
        $list_data = $this->dept_users_model->enable_user_list($user_id);

        echo $list_data;
    }

}
?>