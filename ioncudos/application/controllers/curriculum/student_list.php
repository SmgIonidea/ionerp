<?php
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: 
 * Modification History:
 * Created		:	21-10-2016. 
 * Date				Modified By				Description
 * 21-10-2016                      RK      
 * 02-06-2017 		Jyoti 				Added comments , Modified to update the email while add and edit of user depending upon 
 *                                                      the uniqness in a crclm Provide Multiple deletion of students, uploaded in the student list.
  ---------------------------------------------------------------------------------------------------------------------------------
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Student_list extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        //$this->load->model('curriculum/setting/curriculum_delivery_method_model/curriculum_delivery_method_model');
        $this->load->model('curriculum/setting/student_list/student_list_model');
        $this->load->model('survey/import_student_data_model', '', TRUE);
        $this->load->model('survey/other/master_type_detail', '', TRUE);
    }
    
    /* Function is used to authenticate and display the index page
     * @param- 
     * @return: 
     */
    public function index() {
        if (!$this->ion_auth->logged_in()) {
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') )) {
            redirect('configuration/users/blank');
        } else {
            $email = $this->session->userdata('email');
            $data['title'] = 'Student List';
            $data['categories'] = $this->master_type_detail->getMasters('caste', 'Category');
            $data['gender'] = $this->master_type_detail->getMasters('gender', 'Gender');
            $data['nationality'] = $this->master_type_detail->getMasters('Nationality', 'Nationality');
            $data['entrance'] = $this->master_type_detail->getMasters('ug_entrance_exams', 'Entrance Exam');
            $data['results'] = $this->student_list_model->crclm_dm_index();            
            $this->load->view('curriculum/setting/student_list/list_student_vw', $data);
        }
    }
    
    /* Function is used to get section list for the selected curriculum
     * @param- 
     * @return: 
     */
    public function loadSectionList() {
        $crclm_id = $this->input->post('crclm_id');
        $section_list = $this->student_list_model->loadSectionList($crclm_id);

        if (empty($section_list) || empty($crclm_id)) {
            echo "<option value=''>No Section data</option>";
        } else {
            $list = "";
            $list .= "<option value=''>Select Section</option>";
            foreach ($section_list as $section) {
                $list .= "<option value='" . $section['mt_details_id'] . "'>" . $section['mt_details_name'] . "</option>";
            }
            echo $list;
        }
    }
    
    /* Function is used to fetch department list
     * @param- 
     * @return: 
     */
    public function fetch_dept_acronym(){

       $crclm_id = $this->input->post('crclm_id');
       $dept_acronym  = $this->student_list_model->fetch_dept_acronym();
       $dept_select  = $this->student_list_model->fetch_selected_deapt($crclm_id);	  	   
       $dept_selected_data = $dept_select[0]['dept_acronym'];
       $list = "";
       if (empty($dept_acronym) || empty($crclm_id)) {
          $list =    "<option value=''>No data</option>";
        } else {           
            foreach ($dept_acronym as $dept) {
                $list .= "<option value='" . $dept['dept_acronym'] . "' title='".$dept['dept_name']."'>" . $dept['dept_acronym'] . "</option>";
            }           
        }
        $data['list'] = $list;
        $data['select_dept'] = $dept_selected_data;
         echo json_encode($data);
    }
    
    /* Function is used to fetch the curriculum list
     * @param- 
     * @return: 
     */
    public function getDetailsByCrclm(){
        $crclm_id = $this->input->post('crclm_id');
        $details = $this->student_list_model->getDetailsByCrclm($crclm_id);
        $detailsByCrclm = $details[0];
        $formData = '<input id="dept_name" title="'.$detailsByCrclm['dept_name'].'" name="dept_name" type="hidden" value="'.$detailsByCrclm['dept_id'].'" />
                    <input id="program_name" title="'.$detailsByCrclm['pgm_acronym'].'" name="program_name" type="hidden" value="'.$detailsByCrclm['pgm_id'].'" />
                    <input id="curriculum_name" title="'.$detailsByCrclm['crclm_name'].'" name="curriculum_name" type="hidden" value="'.$detailsByCrclm['crclm_id'].'" />';
        echo $formData;
    }
            
    /* Function is used to get the list of all students 
     * @param- 
     * @return: 
     */
    function getStudentsList() {	
        $crclm_id = $this->input->post('crclm_id');    
        $section_id = $this->input->post('section_id');
        $student_list  = $this->student_list_model->get_student_list($crclm_id ,$section_id);
        echo json_encode($student_list);
    }
    
    /* Function is used to add new student stakeholder details
     * @param- 
     * @return: 
     */
    function add_student_stakeholder() {
        $validationRule = array(
            array(
                'field' => 'student_usn',
                'label' => 'Student USN',
                'rules' => 'required|is_unique_one[su_student_stakeholder_details.student_usn]'
            ),
            array(
                'field' => 'first_name',
                'label' => 'First name',
                'rules' => 'required|alpha_space'
            ),
            array(
                'field' => 'last_name',
                'label' => 'Last name',
                'rules' => 'alpha_space'
            ), array(
                'field' => 'email',
                'label' => 'Email',
                //'rules' => 'required|valid_email|is_unique_one[su_student_stakeholder_details.email]'
                'rules' => 'required|valid_email'
            ), array(
                'field' => 'contact',
                'label' => 'Contact',
                'rules' => 'numeric|phone_num'
            ), array(
                'field' => 'title',
                'label' => 'title',
                'rules' => 'required'
            )
        );
		
        $this->load->library('form_validation');
        $this->form_validation->set_rules($validationRule); 
        if ($this->form_validation->run() == TRUE) {

            //create user login and activate user
            //code starts here
            $username = strtolower($this->input->post('first_name') . ' ' . $this->input->post('last_name'));
            $email = $this->input->post('email');
            $password = 'passwords';

            $additional_data = array(
                'title' => $this->input->post('title'),
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'user_qualification' => '',
                'user_experience' => '',
                'email' => $email,
                'user_dept_id' => $this->input->post('add_dept_name'),
                'base_dept_id' => $this->input->post('add_dept_name'),
                'created_by' => $this->ion_auth->user()->row()->id,
                'designation_id' => 11,
				'is_student' => 1,
            );
            $group = array('3');
            $last_id = $this->ion_auth->register($username, $password, $email, $additional_data, $group);
            $this->ion_auth->activate($last_id['id']);
            //code ends here			
			$student_group_id = $this->import_student_data_model->getStudentStakeholderGroupID();
                        
            if ($this->input->post('student_gender') != 0) {
                $gender = $this->input->post('student_gender');
            } else {
                $gender = null;
            }
            if ($this->input->post('student_nationality') != 0) {
                $nationality = $this->input->post('student_nationality');
            } else {
                $nationality = null;
            }
            if ($this->input->post('student_category') != 0) {
                $category = $this->input->post('student_category');
            } else {
                $category = null;
            }
            if ($this->input->post('entrance_exam') != 0) {
                $entrance = $this->input->post('entrance_exam');
            } else {
                $entrance = null;
            }
            $form_data = array(
                'stakeholder_group_id' => $student_group_id,
                'dept_id' => $this->input->post('add_dept_name'),
                'pgm_id' => $this->input->post('add_program_name'),
                'crclm_id' => $this->input->post('add_curriculum_name'),
                'section_id' => $this->input->post('add_section_name'),
                'student_usn' => $this->input->post('student_usn'),
                'title' => $this->input->post('title'),
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'email' => $this->input->post('email'),
                'contact_number' => $this->input->post('contact'),
                'dob' => $this->input->post('dob'),
                'user_id'=>$last_id['id'],
                'student_category' => $category,
                'student_gender' => $gender,
                'entrance_exam' => $entrance,
                'student_nationality' => $nationality,
                'any_other_entrance_exam' => $this->input->post('any_other_entrance_exam'),
                'any_other_nationality' => $this->input->post('any_other_nationality'),
                'student_state' => $this->input->post('student_state'),
                'student_rank' => $this->input->post('student_rank'),
                'department_acronym' =>$this->input->post('dept_achrony')
            );
            $status = $this->import_student_data_model->store_student_stakeholder_info($form_data);
            echo $status;
        } else {
            echo validation_errors();
        }
    }
    
    /* Function is used to update the student details
     * @param- 
     * @return: 
     */
    function update_student_details() {	
        $ssd_id = $this->input->post('ssd_id');
        if($this->input->post('edit_student_gender') != 0){
            $gender = $this->input->post('edit_student_gender');
        }else{
            $gender = null;
        }
        if($this->input->post('edit_student_nationality') != 0){
            $nationality = $this->input->post('edit_student_nationality');
        }else{
            $nationality = null;
        }
        if($this->input->post('edit_student_category') != 0){
            $category = $this->input->post('edit_student_category');
        }else{
            $category = null;
        }
        if($this->input->post('edit_entrance_exam') != 0){
            $entrance = $this->input->post('edit_entrance_exam');
        }else{
            $entrance = null;
        }
        $update_arr = array(
            'student_usn' => $this->input->post('edit_student_usn'),
            'title' => $this->input->post('edit_title'),
            'first_name' => $this->input->post('edit_first_name'),
            'last_name' => $this->input->post('edit_last_name'),
            'email' => $this->input->post('edit_email'),
            'contact_number' => $this->input->post('edit_contact'),
            'dob' => $this->input->post('edit_dob'),
	    'student_gender' => $gender,
            'student_nationality' => $nationality,
            'any_other_nationality' => $this->input->post('edit_any_other_nationality'),
            'student_state' => $this->input->post('edit_student_state'),
            'student_category' => $category, 
            'entrance_exam' => $entrance,
            'any_other_entrance_exam' => $this->input->post('edit_any_other_entrance_exam'),
            'student_rank' => $this->input->post('edit_rank'),
			'department_acronym' => $this->input->post('department_acronym'),
        );

        $update_flag = $this->import_student_data_model->update_student_data($ssd_id, $update_arr,1);
        
        $update_arr = array(
            'username' => $this->input->post('edit_first_name') . " " . $this->input->post('edit_last_name'),
            'email' => $this->input->post('edit_email'),
            'title' => $this->input->post('edit_title'),
            'first_name' => $this->input->post('edit_first_name'),
            'last_name' => $this->input->post('edit_last_name'),
        );
        $this->import_student_data_model->update_user_cred_data($ssd_id, $update_arr);
        //if($update_flag['update_flag'] == 1) {
            echo $update_flag['update_flag'];
        //} else {
          //  echo 0;
        //}
    }
    
    /* Function is used to delete the selected students 
     * @param- 
     * @return: 
     */
    public function delete_students() {
        if (!$this->ion_auth->logged_in()) {
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') )) {
            redirect('configuration/users/blank');
        } else {
            $delete_values = implode(",", $this->input->post('delete_records'));
            $delete_count = sizeof($this->input->post('delete_records'));
            $deleted = $this->student_list_model->delete_students($delete_values, $delete_count);
            if($deleted) {
                echo 1;
            } else {
                echo 0;
            }
        }
    }
    
    /* Function is used to check the to check if the email is duplicate while adding a new user the user emailid.
     * @param- 
     * @return: 
     */
    public function check_email_duplicate() {
        if (!$this->ion_auth->logged_in()) {
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') )) {
            redirect('configuration/users/blank');
        } else {
            $email = $this->input->post('email');
            $crclm_id = $this->input->post('crclm_id');
            $email_exists = $this->student_list_model->check_email_duplicate($crclm_id, $email);
            if($email_exists == 0) {
                echo 0;
            } else {
                echo json_encode($email_exists);
            }
        }
    }
    
    
    /* Function is used to check the to check if the email is duplicate while editing the user emailid.
     * @param- 
     * @return: 
     */
    public function check_email_duplicate_edit() {
        if (!$this->ion_auth->logged_in()) {
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') )) {
            redirect('configuration/users/blank');
        } else {
            $email = $this->input->post('email');
            $crclm_id = $this->input->post('crclm_id');
            $ssd_id = $this->input->post('ssd_id');
            $email_exists = $this->student_list_model->check_email_duplicate_edit($crclm_id, $email, $ssd_id);
            if($email_exists == 0) {
                echo 0;
            } else {
                echo json_encode($email_exists);
            }
        }
    }
}