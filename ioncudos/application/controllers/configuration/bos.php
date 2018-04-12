<?php

/**
 * Description	:	Controller Logic for BOS Module (List, Edit, Enable/Disable).
 * Created		:	25-03-2013. 
 * Modification History:
 * Date				Modified By				Description
 * 23-08-2013		Abhinay B.Angadi        Added file headers, function headers, indentations & comments.
 * 30-08-2013		Abhinay B.Angadi		Variable naming, Function naming & Code cleaning.
 * 13-03-2015		Jyoti					Tooltip for BoS add existing.
  -------------------------------------------------------------------------------------------------
 */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Bos extends CI_Controller {
    /* Constructor Function is to load session, form validation, 
     * url, libraries & database utilities.
     */

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
    }

// End of function __construct.

    /* Function is used to check the user logged_in & his user group.
     * @param-
     * @retuns - the BOS list view with all BOS users.
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
            $this->load->model('configuration/bos/bos_model');
            $data['users'] = $this->bos_model->list_bos_users();

            $data['title'] = 'BOS List Page';
            $this->load->view('configuration/bos/list_bos_vw', $data);
        }
    }

// End of function index.

    /* Function is used to check the user logged_in & his user group 
     * and to fetches BOS users details from the bos & user table.
     * @param- 
     * @retuns - static (read only) list view of BOS users.
     */

    public function static_list_bos() {
        //permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        }

        //permission_end
        else {
            $this->load->model('configuration/bos/bos_model');
            // data['users'] stores an array values of BOS users details.
            $this->data['users'] = $this->bos_model->list_bos_users();

            $data['title'] = 'BOS List Page';
            $this->load->view('configuration/bos/static_list_bos_vw', $this->data);
        }
    }

// End of function static_list_bos.

    /* Function is to check the user logged_in & his user group.
     * @param----
     * @retuns - the BOS list view of all BOS users.
     */

    public function list_bos() {
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
            $this->load->model('configuration/bos/bos_model');
            // data['users'] stores an array values of BOS users details.
            $this->data['users'] = $this->bos_model->list_bos_users();

            $data['title'] = 'BOS List Page';
            $this->load->view('configuration/bos/list_bos_vw', $this->data);
        }
    }

// End of function list_bos.

    /* Function is used to load add existing BOS user view.
     * @param- 
     * @retuns - an add existing BOS user view.
     */

    public function bos_add_existing() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        }
        //permission_end
        else {
            $data['department'] = $this->bos_model->department_list();
            $data['title'] = 'BOS AddExisting Page';
            $this->load->view('configuration/bos/add_existing_user_bos_vw', $data);
        }
    }

// End of function bos_add_existing.

    /* Function is used to add new BOS user onto the BOS table.
     * @param- 
     * @retuns - a string value.
     */

    public function insert_existing_bos_user() {
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
            $this->load->library('form_validation');
            $this->form_validation->set_rules('dept_id', 'Department Id', 'required|xss_clean|max_length[8]');
            $this->form_validation->set_rules('user_dept_id', 'User Id', 'required|xss_clean|max_length[8]');
            $this->form_validation->set_rules('bos_dept_id', 'BOS Id', 'required|xss_clean|max_length[8]');

            $user_id = $this->input->post('id');
            $bos_dept_id = $this->input->post('bos_dept_id');
            //email trigger code is written inside bos_model.php code.
            $this->data['is_updated'] = $this->bos_model->insert_existing_bos_user($user_id, $bos_dept_id);
            echo $this->data['is_updated'];
        }
    }

// End of function insert_existing_bos_user.

    /* Function is used to edit & update BOS user details onto the user table.
     * @param- user id.
     * @retuns - a boolean value.
     */

    public function edit_bos_user($user_id, $bos_id) {
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
            $this->data['title'] = 'Edit BOS User';

            $this->load->model('configuration/users/users_model');
            $designationlist = $this->bos_model->designation_list();
            $departmentlist = $this->bos_model->department_list();
            $default_department_result = $this->bos_model->edit_default_department($user_id, $bos_id);
            $default_department = $default_department_result['bos_dept_id'];

            $user = $this->ion_auth->user($user_id)->row();
            $bos_user = $this->bos_model->bos_user_list();

            //validate form input
            $this->form_validation->set_rules('first_name', 'First Name', 'required|xss_clean|max_length[20]');
            $this->form_validation->set_rules('last_name', 'Last Name', 'xss_clean|max_length[20]');
            $this->form_validation->set_rules('department', 'Department', 'xss_clean|max_length[8]');
            $this->form_validation->set_rules('qualification', 'Highest Qualification Name', 'xss_clean|max_length[50]');
            $this->form_validation->set_rules('experience', 'Experience', 'xss_clean|max_length[3]');
            $this->form_validation->set_rules('organization', 'Organization Name', 'required|xss_clean|max_length[50]');
            $this->form_validation->set_rules('designation_id', 'Designation Id', 'required|xss_clean|max_length[8]');
            if ($this->input->post('email') != $user->email) {
                $this->form_validation->set_rules('email', 'Email Address', 'required|valid_email|max_length[50]|is_unique[users.email]');
                $this->form_validation->set_rules('password', 'Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']');
                //$this->form_validation->set_rules('status', 'Status', 'required');
            }
            if (isset($_POST) && !empty($_POST)) {
                // do we have a valid request?
                if ($user_id != $this->input->post('id')) {
                    show_error('This form post did not pass our security checks.');
                }
                // creating an array of POST data received from view
                $data = array(
                    'title' => $this->input->post('user_title'),
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'organization' => $this->input->post('organization'),
                    'user_qualification' => $this->input->post('qualification'),
                    'user_experience' => $this->input->post('experience'),
                    'email' => $this->input->post('email'),
					'contact' => $this->input->post('mobile-number'),
                    'bos_dept_id' => $this->input->post('bos_dept_id'),
                    'user_dept_id' => $this->input->post('user_dept_id'),
                    'created_by' => $this->ion_auth->user()->row()->id,
                    'create_date' => date('Y-m-d'),
                    'modified_by' => $this->ion_auth->user()->row()->id,
                    'modify_date' => date('Y-m-d'),
                    'designation_id' => $this->input->post('designation_id'),
                    'password' => $this->input->post('password'),
                );

                //update the password if it was posted
                if ($this->input->post('reset_password')) {
                    $this->form_validation->set_rules('reset_password', 'Reset Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']');
                    $data['password'] = $this->input->post('reset_password');
                }
                if ($this->form_validation->run() === TRUE) {
                    //Function to update BOS user details onto the user table 
                    $this->ion_auth->update($user->id, $data);

                    //  Function to update department id of user onto BOS table
                    $this->bos_model->update_bos($this->input->post('bos_dept_id'), $user_id, $bos_id);

                    //check to see if we are creating the user
                    //redirect them back to the admin page
                    $this->session->set_flashdata('message', 'User Saved');
                    redirect('configuration/bos/list_bos', 'refresh');
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
                'value' => $this->form_validation->set_value('first_name', $user->first_name),
                'placeholder' => 'Enter First Name',
                'autofocus' => 'autofocus'
            );
            $this->data['last_name'] = array(
                'name' => 'last_name',
                'id' => 'last_name',
                //class' => 'required',
                'type' => 'text',
                'placeholder' => 'Enter Last Name',
                'value' => $this->form_validation->set_value('last_name', $user->last_name)
            );
            $this->data['organization'] = array(
                'name' => 'organization',
                'id' => 'organization',
                'class' => 'required',
                'type' => 'text',
                'placeholder' => 'Enter Organization Name',
                'value' => $this->form_validation->set_value('organization', $user->organization_name)
            );
            $this->data['email'] = array(
                'name' => 'email',
                'id' => 'email',
                'class' => 'required email',
                'type' => 'text',
                'placeholder' => 'Enter User Email',
                'value' => $this->form_validation->set_value('email', $user->email)
            );

            $this->data['selected_title'] = $user->title;
            $this->data['designation'] = $designationlist;
            $this->data['department'] = $departmentlist;

            $this->data['user_dept_id'] = array(
                'name' => 'user_dept_id',
                'id' => 'user_dept_id',
                'class' => 'required',
                'type' => 'text',
                'value' => $this->form_validation->set_value('user_dept_id', $user->user_dept_id)
            );
            $this->data['bos_dept_id'] = array(
                'name' => 'bos_dept_id',
                'id' => 'bos_dept_id',
                'class' => 'required',
                'type' => 'text',
                'value' => $this->form_validation->set_value('bos_dept_id', $default_department)
            );
            //Function to fetch all the departments from department table.
            $this->data['default_department'] = $default_department;

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
                'value' => (int) $this->form_validation->set_value('experience', $user->user_experience)
            );
            $this->data['password'] = array(
                'name' => 'reset_password',
                'id' => 'reset_password',
                'class' => 'reset_password',
                'type' => 'text'
            );
            $this->data['designation_id'] = array(
                'name' => 'designation_id',
                'id' => 'designation_id',
                'type' => 'text',
                'value' => $this->form_validation->set_value('designation_id', $user->designation_id)
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
            $data['title'] = 'BOS Edit Page';
            $this->load->view('configuration/bos/edit_bosuser_vw', $this->data);
        }
    }

// End of function edit_bos_user.

    /* Function is used to update the BOS user status field to 1 (active) onto the BOS table.
     * @param-
     * @returns - a boolean value.
     */

    public function bos_status() {
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
            $bos_id = $this->input->post('id');
            $active = $this->input->post('active');

            $this->load->model('configuration/bos/bos_model');
            //$result stores boolean value of the result of update BOS user status field to 1 (active) onto the BOS table
            $result = $this->bos_model->bos_status($bos_id, $active);
            return true;
        }
    }

// End of function bos_status.

    /* Function is used to fetch BOS user details from the user table.
     * @param- department id.
     * @retuns - a json object of list of users.
     */

    public function users_by_department($dept_id = NULL) {
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
            // data['users_by_department'] stores the users list from users table.
            $this->data['users_by_department'] = $this->ion_auth
                    ->select('id, title, first_name, last_name, email')
                    ->order_by('username', 'asc')
                    ->where('base_dept_id', $dept_id)
                    ->where('active', 1)
                    ->where('is_student', 0)
                    ->users()
                    ->result_array();
            header('Content-Type: application/x-json; charset=utf-8');
            echo(json_encode($this->data['users_by_department']));
        }
    }

// End of function users_by_department.

    public function _get_csrf_nonce() {
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
            $this->load->helper('string');
            $key = random_string('alnum', 8);
            $value = random_string('alnum', 20);
            $this->session->set_flashdata('csrfkey', $key);
            $this->session->set_flashdata('csrfvalue', $value);

            return array($key => $value);
        }
    }

// End of function _get_csrf_nonce.

    /* Function is to check the user logged_in & his user group.
     * @param-
     * @retuns - the Welcome view.
     */

    public function blank() {
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
            $data['title'] = 'Blank Page';
            $this->load->view('welcome_template_vw', $data);
        }
    }

    public function check_delete_bos() {
        $id = $this->input->post('id');
        $bos_dept_id = $this->input->post('bos_dept_id');
        $data = $this->bos_model->check_delete_bos($id);
        echo $data;
    }

    public function delete_bos() {
        $id = $this->input->post('id');
        $bos_dept_id = $this->input->post('bos_dept_id');
        $re = $this->bos_model->delete_bos($id, $bos_dept_id);
        echo ($re);
    }

// End of function blank.
}

// End of Controller Class Bos.
?>
