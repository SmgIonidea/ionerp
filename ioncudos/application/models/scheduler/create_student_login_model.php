<?php

class Create_student_login_model extends CI_Model{
	
	//function to create login for previously uploaded students
	function create_login(){
		//get student stakholders list where user id is null 
		$stud_list_res = $this->db->query("SELECT * FROM su_student_stakeholder_details where user_id is null;");
		//$this->db->where('user_id',null)->get('su_student_stakeholder_details');
		$stud_list = $stud_list_res->result_array();
		$design_id_res = $this->db->select('designation_id')->get_where('user_designation', array('designation_name' => 'Student'), 1);
        $design_id = $design_id_res->row()->designation_id;//designation 
		$count = 0;
		foreach($stud_list as $stud_data){
			$username = strtolower($stud_data['first_name'] . ' ' . $stud_data['last_name']);
            $email = $stud_data['email'];
            $password = 'password'; //default password for students
			
            $additional_data = array(
                'title' => $stud_data['title'],
                'first_name' => $stud_data['first_name'],
                'last_name' => $stud_data['last_name'],
                'contact' => $stud_data['contact_number'],
                'user_qualification' => '',
                'user_experience' => '',
                'email' => $email,
                'user_dept_id' => $stud_data['dept_id'],
                'base_dept_id' => $stud_data['dept_id'],
                'created_by' => $this->ion_auth->user()->row()->id,
                'designation_id' => $design_id,
                'is_student' => 1,
            );
            $group = array('3'); //user group id
			
			$last_id = $this->ion_auth->register($username, $password, $email, $additional_data, $group);
            $this->ion_auth->activate($last_id['id']);
			
			$this->db->where('email',$email);
			$this->db->where('ssd_id',$stud_data['ssd_id']);
			$is_updated = $this->db->update('su_student_stakeholder_details',array('user_id'=>$last_id['id']));
			if($is_updated){
				$count++;
			}
		}//end of foreach
		echo "Login created for ".$count." students";
	}//end of create_login()
	
}//end of create_student_login_model