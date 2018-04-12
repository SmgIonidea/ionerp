<?php
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: 
 * Modification History:
 * Created		:	02-02-2015. 
 * Date				Modified By				Description
 * 02-02-2015                   Jyoti                   Added file headers, function headers & comments. 
 * 21-10-2016                   Neha Kulkarni           Sending the mail to HoD and the user when the user is imported.
  ---------------------------------------------------------------------------------------------------------------------------------
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Import_user extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('curriculum/setting/import_user/import_user_model');
    }

	/*
        * Function is to check for user login. and to display the import_user list.
        * @param - ------.
        * returns the list of users.
	*/
    public function index() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') )) {
            redirect('configuration/users/blank');
        } else {
			$email = $this->session->userdata('email');
			$data['title'] = 'Import User';
			$data['dept_id'] = $this->import_user_model->getDepartment($email);
			$data['list_data'] = $this->import_user_model->getListData($email);
			$this->load->view('curriculum/setting/import_user/list_import_vw',$data); 
        }
    }
	
	/*Function to get the active users of a department.
	*@params:
	*@returns:list of active users.
	*/
	public function getActiveUsersOfDepartment(){
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner'))) {
            redirect('configuration/users/blank');
        } else {
			$dept_id = $this->input->post('dept_id');
			$user['user_info'] = $this->import_user_model->getActiveUsersOfDepartment($dept_id);
			$list = Array();
			$i = 0;
			$list[$i++] = '<option value="0">Select User</option>';
			foreach($user['user_info'] as $user_data){
				$list[$i] = '<option value='.$user_data['id'].' title="'.$user_data['email'].'">'.ucfirst($user_data['title']).' '.ucfirst($user_data['first_name']).' '.ucfirst($user_data['last_name']).'</option>';
				$i++;
			}
			$list = implode(' ', $list);
			echo $list;
        }
	}//End of function getActiveUsersOfDepartment	
	
	/*Function to get User Info.
	*@params:
	*@returns:user email.
	*/
	public function getUserInfo(){
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner'))) {
            redirect('configuration/users/blank');
        } else {
			$user_id = $this->input->post('user_id');
			$user['user_info'] = $this->import_user_model->getUserInfo($user_id);
			echo $user['user_info'][0]['email'];
        }
	}//End of function getActiveUsersOfDepartment	
	
	/*Function to get the save the imported user data 
	*@params:
	*returns:
	*/
	public function save_imported_user_data(){
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
		} elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner'))) {
            redirect('configuration/users/blank');
        } else {
			$email = $this->session->userdata('email');
			
			// refer setting/import_user_model.php
			
			$base_dept_id = $this->import_user_model->getBaseDepartment($email);	
			$base_dept_id = $this->ion_auth->user()->row()->base_dept_id; 
			$from_dept_id = $this->input->post('department');	
			
			$user_id = $this->input->post('user');
			$roles = 6;
			// $user_id = $this->ion_auth->user()->row()->id; 
			$user_dept = $this->import_user_model->fetch_user_dept($user_id);

			$check_send_mail = $this->input->post('send_mail');		

			if($check_send_mail == 1) { 
				
				$title = $this->ion_auth->user()->row()->title;
				$first_name = $this->ion_auth->user()->row()->first_name;
				$last_name = $this->ion_auth->user()->row()->last_name;
				$lgged_in_user_id = $this->ion_auth->user()->row()->id;
				$lgged_in_user_dept = $this->import_user_model->fetch_user_dept($lgged_in_user_id);
				$lang_po = $this->lang->line('program_owner_full');	
						    
				$imported_user_name = $this->import_user_model->getUserInfo($user_id);
				$user_name = $imported_user_name[0]['username'];
				$user_mail = $imported_user_name[0]['email'];
				$to_data = $this->import_user_model->department_hod($from_dept_id);
					
				$sub = "Import Details";
				$signature = "<br/>Warm Regards,<br/>IonCUDOS Team";
				$body1 = "Hello <b>". ucwords($user_name) ."</b>,<br/><br/>" . $lang_po . " <b>". ucwords($title.' '.$first_name.' '.$last_name) ."</b> of the <b>". $lgged_in_user_dept[0]['dept_name'] ."</b> has assigned you as a Course Owner for <b>".$lgged_in_user_dept[0]['dept_name']."</b> Department.<br/>".$signature."";
				
				$receiver_id = $to_data[0]['email'];	
				$subject = "Imported User Details";
				
				$body = "Dear Sir / Madam, <br/><br/><br/> This is an automated email from IonCUDOS Software.<br/><br/> <b>User Imported</b> : <b></b> The user <b>".ucwords($imported_user_name[0]['username'])."</b> from <b>" . ucwords($to_data[0]['dept_name']) . "</b> department has been imported as a Course Owner for <b>" .$lgged_in_user_dept[0]['dept_name']. "</b> Department. <br/>" .$signature. "";
				$send_mail = $this->ion_auth->import_user_email($receiver_id, $subject, $body);
				$send_user_mail = $this->ion_auth->import_user_info($user_mail, $sub, $body1);

				$data = $this->import_user_model->save_imported_user_data($user_id,$from_dept_id,$base_dept_id,$roles);
				redirect('curriculum/import_user');			
			} else {
				$this->import_user_model->save_imported_user_data($user_id,$from_dept_id,$base_dept_id,$roles);
				redirect('curriculum/import_user');			

			}

		}
	}
	//End of function save_imported_user_data
	
	/*Function to delete the user and their roles from the department
	*@params:
	*@returns:
	*/
	public function deleteUser_Roles(){
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
		} elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner'))) {
            redirect('configuration/users/blank');
        } else {
			$user_id = $this->input->post('user_id');
			$email = $this->session->userdata('email');
			$base_dept_id = $this->import_user_model->getBaseDepartment($email);
			$base_dept_id = $base_dept_id[0]->base_dept_id;
			$deleted = $this->import_user_model->deleteUser_Roles($user_id,$base_dept_id);
		}
	}//End of function deleteUser_Roles
        
        /*
         * Function to check the user allotted with the course
         * @param:user_id
         * @return:
         */
        public function check_user_alloted_to_course(){
            $user_id = $this->input->post('user_id');
            $check_user_alloted_with_course = $this->import_user_model->check_user_alloted_to_course($user_id);
            echo $check_user_alloted_with_course;
        }
}

/* End of file import_user.php */
/* Location: ./application/controllers/curriculum/import_user.php */
