<?php
/**
* Description	:	Controller Logic for User login management through credentials(username & password) 
					are being authenticated & presented with the respective view.
*					
*Created		:	25-03-2013. 
*		  
* Modification History:
* Date				Modified By				Description
* 19-08-2013		Abhinay B.Angadi        Added file headers, function headers, indentations & comments.
* 26-08-2013		Abhinay B.Angadi		Variable naming, Function naming & Code cleaning.
* 21-10-2014		Arihant Prasad			Check License period
* 02-05-2016		Bhagyalaxmi S S			Added functions    to show log history 
---------------------------------------------------------------------------------------------------
*/
?>

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {
	
	public function __construct()	{
		parent::__construct();
		$this->load->model('login/Login_model');
		$this->load->library('Session');
		// $this->load->library('ionauth/ion_auth');
		// $this->load->library('form_validation');
		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), 
														$this->config->item('error_end_delimiter', 'ion_auth'));
		header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: X-Requested-With');
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
	}// End of function __construct.
		
	// Function is used to load the login page & to make validations as well authentication checks.
		/*
		Global Function to read the file contents from Angular Http Request.
		@param:
		@return:
		@result: Get Http Request COntent
		Created: 16/8/2017

	*/
	public function readHttpRequest(){
			$incomingFormData = file_get_contents('php://input');
			
			return $incomingFormData;
			}

	public function index(){
		$incomingFormData =  $this->readHttpRequest();
		$formData = json_decode($incomingFormData);
//		$userName = base64_decode($formData->identity);
//		$password = base64_decode($formData->password);
        
        $getData = $this->Login_model->getUser($formData);
       
         echo json_encode($getData);
//		if($this->ion_auth->logged_in()){
//
//		}else{
//			if ($this->ion_auth->login($userName,$password)) {
//				$this->session->set_userdata('user_input', $userName);
//				$data['admin'] = $this->ion_auth->is_admin();
//				$data['course_owner'] = $this->ion_auth->in_group('Course Owner');
//				$data['program_owner'] = $this->ion_auth->in_group('Program Owner');
//				$data['chairman'] = $this->ion_auth->in_group('Chairman');
//				$data['student'] = $this->ion_auth->in_group('Student');
//				$data['isLoggedIn'] = TRUE;
//				$data['id'] = $this->ion_auth->user()->row()->id;
//				$data['username'] = $this->ion_auth->user()->row()->username;
//				$data['email'] = $this->ion_auth->user()->row()->email;
//                $data['password'] = $password;
//				$data['title'] = $this->ion_auth->user()->row()->title;
//				$data['first_name'] = $this->ion_auth->user()->row()->first_name;
//				$data['last_name'] = $this->ion_auth->user()->row()->last_name;
//				$data['user_dept_id'] = $this->ion_auth->user()->row()->user_dept_id;
//				$data['user_dept_name'] = $this->Login_model->fetch_dept_name($this->ion_auth->user()->row()->user_dept_id)->dept_name;
//				$data['base_dept_id'] = $this->ion_auth->user()->row()->base_dept_id;
//				$data['base_dept_name'] = $this->Login_model->fetch_dept_name($this->ion_auth->user()->row()->base_dept_id)->dept_name;
//				if($this->Login_model->fetch_assigned_dept($this->ion_auth->user()->row()->id)){
//					$data['assigned_dept_id'] = $this->Login_model->fetch_assigned_dept($this->ion_auth->user()->row()->id)->assigned_dept_id;
//					$data['assigned_dept_name'] = $this->Login_model->fetch_assigned_dept($this->ion_auth->user()->row()->id)->dept_name;
//				}else{
//					$data['assigned_dept_id'] = null;
//					$data['assigned_dept_name'] = null;
//				}
//				$data['user_qualification'] = $this->ion_auth->user()->row()->user_qualification;
//
//                $session_data = $this->Login_model->fetch_session_data($this->ion_auth->user()->row()->id);
//                if(!empty($session_data)){
//                $data['dept_id'] = $session_data['0']['dept_id'];
//                $data['dept_name'] = $session_data['0']['dept_name'];
//                $data['pgm_id'] = $session_data['0']['pgm_id'];
//                $data['pgm_name'] = $session_data['0']['pgm_specialization'];
//                $data['crclm_id'] = $session_data['0']['crclm_id'];
//                $data['crclm_name'] = $session_data['0']['crclm_name'];
//                $data['crclm_term_id'] = $session_data['0']['crclm_term_id'];
//                $data['term_name'] = $session_data['0']['term_name'];
//                $data['crs_id'] = $session_data['0']['crs_id'];
//                $data['crs_name'] = $session_data['0']['crs_title'];
//                $data['section_id'] = $session_data['0']['section_id'];
//                $data['section_name'] = $session_data['0']['mt_details_name'];
//                }
//				echo json_encode($data);
//			}else{
//				$data['isLoggedIn'] = FALSE;
//				echo json_encode($data);
//			}
//		}
	}

    public function getBaseUrl(){
        $baseUrl = $this->Login_model->getBaseUrl();
        echo json_encode($baseUrl);
    }

    public function updateSessionData(){
        $incomingFormData =  $this->readHttpRequest();
		$formData = json_decode($incomingFormData);
        $this->Login_model->updateSessionData($formData);
    }
	
	public function genrate_forgot_password_link()
	{	
		$email_id = $this->input->post('email_id');
		$result = $this->login_model->get_user_details($email_id);
		
		if (!empty($result)) {
			$id = $result['0']['id'];
			$links = base_url();
			$links = $links."login/reset_password/".$result['0']['password'];
			$entity_id = '7';
			$addtional_data = "\n Username: ".$result['0']['email'];
			//$addtional_data = $addtional_data."\n Password: ".$result['0']['password'];
			
			$this->ion_auth->send_email($id,$cc=NULL,$links,$entity_id,$state=4,$crclm_id=NULL,$addtional_data);	
			echo 1;
		} else {
			echo 0;
		}
	}

	
	/*
		Function to Change the default password and set to new password.
		@param new password, user id.
	*/
	
	public function update_change_password() {
		$user_id = $this->input->post('user_id');
		$new_psw = $this->input->post('new_psw');
		$re_psw = $this->input->post('re_psw');
		//$updated_data = $this->login_model->update_change_password($user_id, $new_psw, $re_psw);
		//$identity = $this->session->userdata('identity');
			$data['password'] = $new_psw;
			$change = $this->ion_auth->update($user_id, $data);

	}
	
	public function forgot_password() {
		//setup the input
		$this->data['organisation_detail']=$this->login_model->get_organisation_details(1);
		$this->data['email'] = array('name'    => 'email',
									 'id'      => 'email',
									 'class'   => 'required'
									);
		//set any errors and display the form
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		
		$this->data['title']='Forgot Password Page';
		$this->load->view('login/forgot_password_vw', $this->data);
	}
	
	public function reset_password($encrypt_code) {
		$result = $this->login_model->get_user_id($encrypt_code);
		$this->data['organisation_detail']=$this->login_model->get_organisation_details(1);
		if (!empty($result)) {
			$user_id = $result['0']['id'];
			$email_id = $result['0']['email'];
		} else {
			redirect('login', 'refresh'); 
		}
	//setup the input
		$this->data['user_id'] = array(
			'name'    => 'user_id',
			'id'      => 'user_id',
			'type'      => 'hidden',
			'class'   => 'required',
			'value'   => $user_id
		);
		$this->data['email_id'] = array(
			'name'    => 'email_id',
			'id'      => 'email_id',
			'type'      => 'hidden',
			'class'   => 'required',
			'value'   => $email_id
		);
		$this->data['first_name'] =  $this->ion_auth->user($user_id)->row()->first_name;
		$this->data['last_name'] = $this->ion_auth->user($user_id)->row()->last_name;
		$this->data['email'] = $this->ion_auth->user($user_id)->row()->email;
		
		$this->data['password'] = array(
			'name' => 'password',
			'id'   => 'password',
			'type' => 'password'
		);
		$this->data['confirm_password'] = array(
			'name' => 'confirm_password',
			'id'   => 'confirm_password',
			'type' => 'password'
		);
	//set any errors and display the form
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		$this->data['title']='Reset Password Page';
		$this->load->view('login/reset_forgot_password_vw', $this->data);
	}

	public function update_reset_password() {
	
	$user_id = $this->input->post('user_id');
	$email = $this->input->post('email');
	$password = $this->input->post('password');
	
	$data['password'] = $password;
	$this->ion_auth->update($user_id, $data);
	
	$email_id = $this->input->post('email_id');
	$result = $this->login_model->get_user_details($email_id);
	$id = $result['0']['id'];
	$links = base_url();
	$entity_id = '7';
	//$addtional_data = "\n Your password has been successfully updated & your new password is as below";
	$addtional_data['username'] = "\n Username: ".$result['0']['email'];
	$addtional_data['password'] = "\n Password: ".$password;
	
	$this->ion_auth->send_email($id,$cc=NULL,$links,$entity_id,$state=5,$crclm_id=NULL,$addtional_data);

	redirect('login', 'refresh'); 
	}
	
	//change password
		public function change_pass()
	{		
		$this->data['organisation_detail']=$this->login_model->get_organisation_details(1);
		$this->load->view('login/change_password_vw', $this->data);
	}
			
	function change_password()
	{
		$this->form_validation->set_rules('old', 'password', 'required');
		$this->form_validation->set_rules('new', 'newpassword', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]'); 
		$this->form_validation->set_rules('new_confirm', 'new password', 'required');

		if (!$this->ion_auth->logged_in())
		{
			redirect('login/login', 'refresh');
		}

		$user = $this->ion_auth->user()->row();

		if ($this->form_validation->run() == false)
		{
			echo 'false';
			//display the form
			//set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
			$this->data['old_password'] = array(
				'name' => 'old',
				'id'   => 'old',
				'type' => 'password',
			);
			$this->data['new_password'] = array(
				'name' => 'new',
				'id'   => 'new',
				'type' => 'password',
				'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
			);
			$this->data['new_password_confirm'] = array(
				'name' => 'new_confirm',
				'id'   => 'new_confirm',
				'type' => 'password',
				'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
			);
			$this->data['user_id'] = array(
				'name'  => 'user_id',
				'id'    => 'user_id',
				'type'  => 'hidden',
				'value' => $user->id,
			);

			//render
			$this->_render_page('login/change_pass', $this->data);
		}
		else
		{
			echo 'true';
			$identity = $this->session->userdata('identity');

			$change = $this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));

			if ($change)
			{
				//if the password was successfully changed
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				$this->logout();
			}
			else
			{
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect('login/change_pass', 'refresh');
			}
		}
	}		
	
	// Function to edit a existing user in the users list
    function user_edit_profile($user_id) {
	
        //##permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } 
        //##permission_end
        else {
		
            $this->data['title'] = "Edit User";
            $grouplist = $this->users_model->group_list();
            $designationlist = $this->users_model->designation_list();
            $departmentlist = $this->users_model->department_list();

            $user = $this->ion_auth->user($user_id)->row();

            //validate form input
            $this->form_validation->set_rules('first_name', 'First Name', 'required|xss_clean|max_length[20]');
            //####change#####

            $this->form_validation->set_rules('last_name', 'Last Name', 'required|xss_clean|max_length[20]');
            $this->form_validation->set_rules('qualification', 'Qualification Name', 'required|xss_clean|max_length[50]');
            $this->form_validation->set_rules('experience', 'Experience', 'required|xss_clean|max_length[5]');

            if ($this->input->post('email') != $user->email) {
                $this->form_validation->set_rules('email', 'Email Address', 'required|valid_email|max_length[50]|is_unique[users.email]');
            }

            if (isset($_POST) && !empty($_POST)) {
                // do we have a valid request?
                if ($user_id != $this->input->post('id')) {
                    show_error('This form post did not pass our security checks.');
                }
	            
				//#####################add additional fields here########################//
				
                $data = array(
					'username' => strtolower($this->input->post('first_name')) . ' ' . strtolower($this->input->post('last_name')),
                    'title' => $this->input->post('user_title'),
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    //'email' => $this->input->post('email'),
                    //'user_dept_id' => $this->input->post('user_dept_id'),
                    'user_qualification' => $this->input->post('qualification'),
                    'user_experience' => $this->input->post('experience'),
                    //'designation_id' => $this->input->post('designation_id')
                );

                //$group = $this->input->post('usergroup_id');

                //update the password if it was posted
                if ($this->input->post('reset_password')) {
                    $this->form_validation->set_rules('reset_password', 'Reset Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']');

                    $data['password'] = $this->input->post('reset_password');
                }

                if ($this->form_validation->run() === TRUE) {
                    $this->ion_auth->update($user->id, $data);

                    $previous_groups = $this->ion_auth->get_users_groups($user->id)->result();
                    $kmax = sizeof($previous_groups);
                    $item = array();
                    for ($k = 0; $k < $kmax; $k++)
                        $item[] = $previous_groups[$k]->id;
                    $this->ion_auth->remove_from_group(NULL, $user->id);
                    //$group = $this->input->post('usergroup_id');

                    foreach ($item as $value) {
                        $this->ion_auth->add_to_group($value, $user->id);
                    }
					//email code
					$receiver_id = $this->ion_auth->user()->row()->id;
					$cc = '';
					$links = base_url();
					$entity_id = 7;
					$state = 3;
					$additional_data = '';
					$curriculum_id = '';
					$this->ion_auth->send_email($receiver_id, $cc, $links, $entity_id, $state, $curriculum_id, $additional_data);
					
                    //check to see if we are creating the user
                    //redirect them back to the admin page
                    $this->session->set_flashdata('message', "User Saved");
                    redirect("home", 'refresh');
                }
            }

            //display the edit user form
            //$this->data['csrf'] = $this->_get_csrf_nonce();

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
				'autofocus' => 'autofocus'
            );
            $this->data['last_name'] = array(
                'name' => 'last_name',
                'id' => 'last_name',
                'class' => 'required',
                'type' => 'text',
                'value' => $this->form_validation->set_value('last_name', $user->last_name)
            );
            $this->data['qualification'] = array(
                'name' => 'qualification',
                'id' => 'qualification',
                'class' => 'required',
                'type' => 'text',
                'value' => $this->form_validation->set_value('qualification', $user->user_qualification)
            );
            $this->data['experience'] = array(
                'name' => 'experience',
                'id' => 'experience',
                'class' => 'required',
                'type' => 'text',
                'value' => $this->form_validation->set_value('experience', $user->user_experience)
            );

			$this->data['selected_title'] = $user->title;
            $this->data['selected_designation'] = $user->designation_id;
            $this->data['selected_department'] = $user->user_dept_id;

            $this->data['designation'] = $designationlist;
            $this->data['department'] = $departmentlist;
            $this->data['password'] = array(
                'name' => 'reset_password',
                'id' => 'reset_password',
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
                'readonly' => 'readonly',
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

            $this->data['group'] = $grouplist;
            $this->data['selected_group'] = $this->ion_auth->get_users_groups($user->id)->result();
            $kmax = sizeof($this->data['selected_group']);
            $item = array();
            for ($k = 0; $k < $kmax; $k++)
                $item[] = $this->data['selected_group'][$k]->id;
            $this->data['selected_group'] = $item;
			$this->data['organisation_detail']=$this->login_model->get_organisation_details(1);
            $this->data['title'] = "User Edit Page";
            $this->load->view('configuration/users/user_edit_profile_vw', $this->data);
        }
    }
	
	//function to edit student user
    public function student_user_edit() {
        //##permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } 
        //##permission_end
        else {
            $user_id = $this->ion_auth->user()->row()->id;
            $this->data['title'] = "Edit User";
            $grouplist = $this->users_model->group_list();
            $designationlist = $this->users_model->designation_list();
            $departmentlist = $this->users_model->department_list();

            $user = $this->ion_auth->user($user_id)->row();

            //validate form input
            $this->form_validation->set_rules('first_name', 'First Name', 'required|xss_clean|max_length[20]');
            //####change#####

            $this->form_validation->set_rules('last_name', 'Last Name', 'required|xss_clean|max_length[20]');
//            $this->form_validation->set_rules('qualification', 'Qualification Name', 'required|xss_clean|max_length[50]');
//            $this->form_validation->set_rules('experience', 'Experience', 'required|xss_clean|max_length[5]');

            if ($this->input->post('email') != $user->email) {
                $this->form_validation->set_rules('email', 'Email Address', 'required|valid_email|max_length[50]|is_unique[users.email]');
            }

            if (isset($_POST) && !empty($_POST)) {
                $this->load->model('survey/import_student_data_model', '', TRUE);
                // do we have a valid request?
                if ($user_id != $this->input->post('id')) {
                    show_error('This form post did not pass our security checks.');
                }

                //#####################add additional fields here########################//

                $data = array(
                    'username' => strtolower($this->input->post('first_name')) . ' ' . strtolower($this->input->post('last_name')),
                    'title' => $this->input->post('user_title'),
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'contact' => $this->input->post('contact'),
                    //'email' => $this->input->post('email'),
                    //'user_dept_id' => $this->input->post('user_dept_id'),
                    'user_qualification' => $this->input->post('qualification'),
                    'user_experience' => $this->input->post('experience'),
                        //'designation_id' => $this->input->post('designation_id')
                );

                //$group = $this->input->post('usergroup_id');
                //update the password if it was posted
                if ($this->input->post('reset_password')) {
                    $this->form_validation->set_rules('reset_password', 'Reset Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']');

                    $data['password'] = $this->input->post('reset_password');
                }

                if ($this->form_validation->run() === TRUE) {
                    $this->ion_auth->update($user->id, $data);

                    $previous_groups = $this->ion_auth->get_users_groups($user->id)->result();
                    $kmax = sizeof($previous_groups);
                    $item = array();
                    for ($k = 0; $k < $kmax; $k++)
                        $item[] = $previous_groups[$k]->id;
                    $this->ion_auth->remove_from_group(NULL, $user->id);
                    //$group = $this->input->post('usergroup_id');

                    foreach ($item as $value) {
                        $this->ion_auth->add_to_group($value, $user->id);
                    }
                    //email code
                    $receiver_id = $this->ion_auth->user()->row()->id;
                    $cc = '';
                    $links = base_url();
                    $entity_id = 7;
                    $state = 3;
                    $additional_data = '';
                    $curriculum_id = '';
                    $this->ion_auth->send_email($receiver_id, $cc, $links, $entity_id, $state, $curriculum_id, $additional_data);

                    $update_arr = array(
                        'title' => $this->input->post('user_title'),
                        'first_name' => $this->input->post('first_name'),
                        'last_name' => $this->input->post('last_name'),
                        'email' => $this->input->post('email'),
                    );
                    $this->import_student_data_model->update_student_data($user->id, $update_arr, 0);
                    //check to see if we are creating the user
                    //redirect them back to the admin page
                    $this->session->set_flashdata('message', "User Saved");
                    redirect("dashboard", 'refresh');
                }
            }

            //display the edit user form
            //$this->data['csrf'] = $this->_get_csrf_nonce();
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
                'autofocus' => 'autofocus'
            );
            $this->data['last_name'] = array(
                'name' => 'last_name',
                'id' => 'last_name',
                'class' => 'required',
                'type' => 'text',
                'value' => $this->form_validation->set_value('last_name', $user->last_name)
            );
            $this->data['contact'] = array(
                'name' => 'contact',
                'id' => 'contact',
                'class' => 'onlyDigit',
                'type' => 'text',
                'value' => $this->form_validation->set_value('contact', $user->contact)
            );
            $this->data['qualification'] = array(
                'name' => 'qualification',
                'id' => 'qualification',
                'class' => '',
                'type' => 'hidden',
                'value' => $this->form_validation->set_value('qualification', $user->user_qualification)
            );
            $this->data['experience'] = array(
                'name' => 'experience',
                'id' => 'experience',
                'class' => '',
                'type' => 'hidden',
                'value' => $this->form_validation->set_value('experience', $user->user_experience)
            );

            $this->data['selected_title'] = $user->title;
            $this->data['selected_designation'] = $user->designation_id;
            $this->data['selected_department'] = $user->user_dept_id;

            $this->data['designation'] = $designationlist;
            $this->data['department'] = $departmentlist;
            $this->data['password'] = array(
                'name' => 'reset_password',
                'id' => 'reset_password',
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
                'readonly' => 'readonly',
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

            $this->data['group'] = $grouplist;
            $this->data['selected_group'] = $this->ion_auth->get_users_groups($user->id)->result();
            $kmax = sizeof($this->data['selected_group']);
            $item = array();
            for ($k = 0; $k < $kmax; $k++)
                $item[] = $this->data['selected_group'][$k]->id;
            $this->data['selected_group'] = $item;
            $this->data['organisation_detail'] = $this->login_model->get_organisation_details(1);
            $this->data['title'] = "Student User Edit Page";
            $this->load->view('configuration/users/student_profile_edit_vw', $this->data);
        }
    }
    
	public function contact_support(){
		//echo $this->input->post('body');exit;
        $this->load->helper('support');
		$to = "iioncudos@gmail.com";
		$from = "iioncudos@gmail.com";
		$subject = $this->input->post('subject');
		$body = $this->input->post('body');
		$number = $this->input->post('number');
		$mail = $this->input->post('mail');
		$support_data = array('to'=>$to,'from'=>$from,'subject'=>$subject,'body'=>$body,'number'=>$number,'mail'=>$mail);
		$this->login_model->contact_support($support_data);
		$body.= "\nMail Id: ".$mail;
		contact_support($to, $from, $subject,$body,$number);
		//redirect('/index');
		return true;
   }
   
   public function multi_dept() {
       $dept_id = $this->input->post('dept_id');
       $user_id = $this->ion_auth->user()->row()->id;
       $result = $this->login_model->user_table_update($dept_id,$user_id);    
	   $val =  $this->ion_auth->user()->row();	   
       echo $val->login_attempt;
       
   }
   
   public function load_dept_modal(){
            $user_id = $this->ion_auth->user()->row()->id;
            $data = $this->login_model->get_multiple_dept($user_id);
            //generate dropdown
            $drop_down = '<label class="control-label" for="email"> Department: <font color="red"> * </font></label>';
            $drop_down .= '<select name="department_list" id="department_list" class="required">';
            $drop_down .= '<option value="">Select Department</option>';
            foreach($data as $dept){
                $drop_down .= '<option value="'.$dept['assigned_dept_id'].'">'.$dept['dept_name'].'</option>';
            }
            $drop_down .= '</select><span id="err_msg"></span>';
               echo  $drop_down;                                                        
   }
   
   public function swich_dept_validate() {
            $user_id = $this->ion_auth->user()->row()->id;
            $data = $this->login_model->swich_dept_validate($user_id);
            
            echo $data;
       
   }
   
	/**
	 * function to redirect user to terms and condition view page
	 * @parameters:
	 * @return: load terms and condition view page
	 */
	public function terms_condition() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner') || $this->ion_auth->in_group('BOS') )) {
            redirect('configuration/users/blank');
        } else {
			$data['title'] = 'Terms & Condition';
			$this->load->view('login/login_terms_condition_vw', $data);
		}
	}
	
	/*
		Function to get the logged-in user due action count
	*/
	
	public function get_myaction_count(){
		$user_id = $this->ion_auth->user()->row()->id;
		$dept_id = $this->ion_auth->user()->row()->user_dept_id;
		$data = $this->login_model->get_myaction_count($user_id,$dept_id);
		echo $data;
	}
	/*
	* Function to  update login failed attempt
	* @parameter :
	* @return : 
	**/
	public function update_failed_attempt(){
		$user_id = $this->ion_auth->user()->row()->id;
		$this->login_model->login_failed_attempts_success($user_id); 
	
	}
	/*
	* Function to prevent to show  log history
	* @parameter :
	* @return : 
	**/
	public function prevent_log_history(){
		$user_id = $this->ion_auth->user()->row()->id;
		$val = $this->input->post('val');
		$this->login_model->prevent_log_history($user_id,$val); 
	
	}

	public function call_nectar(){
		$user_id = $this->ion_auth->user()->row()->id;
		$status = $this->login_model->sys_fetch($user_id);
		
		if($status == false){
		//$fetch_date = $this->login_model->fetch_date_diff();		
			$status_msg = $this->login_model->call_nectar();
			//if($fetch_date[0]['date_difference'] <= 10){
				if(!empty($status_msg)){
					if($status_msg[0]['msg'] != 1){
						$msg = $status_msg[0]['msg'];
					}else{ $msg = '';}
				
				}else{$msg = '';}
			//}else{ $msg = '';}
		}else{$msg = '';}
		echo $msg;
	}
	
	   
}// End of Class Login.

/* End of file login.php 
*Location: ./application/controllers/login.php
*/
?>
