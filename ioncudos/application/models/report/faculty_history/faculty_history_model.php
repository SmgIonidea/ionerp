<?php
/**
 * Description	:	Generate or edit map_level_weightage of Faculty

 * Created		:		29th june  2016

 * Author		:	Bhagyalaxmi Shivapuji

 * Modification History:
 *   Date                Modified By                         Description
 * 
  ------------------------------------------------------------------------------------------ 
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Faculty_history_model extends CI_Model 
{
	 /*
        * Function is to fetch the department details.
        * @param - -----.
        * returns list of department names.
	*/   
     public function fetch_department() {
		$query = $this->db->query('select * from department where status = 1 ');
		$dept = $query->result_array();
	    return $dept;
    }    

	
	public function fetch_users($dept_id){
		$query = $this->db->query('select * from users where user_dept_id = "'.$dept_id.'" and is_student = "0" ');
		$users = $query->result_array();
	    return $users;
	}
	
	public function fetch_faculty_contribution($dept_id , $user_id){		
		$query = $this->db->query('select * from user_training_workshop_conference as u
				   join user_training_type_role as ut on ut.twc_id = u.twc_id
				   join master_type_details as m ON m.mt_details_id = u.level where u.user_id = "'.$user_id.'" ');
		$users = $query->result_array();
	    return $users;
	}
	public function fetch_role($role){
			$query = $this->db->query('select mt_details_name from master_type_details where mt_details_id ="'.$role.'" ');
			return $data = $query->result_array();	
	}		
	
	public function fetch_level($level){
			$query = $this->db->query('select mt_details_name from master_type_details where mt_details_id ="'.$level.'" ');
			return $data = $query->result_array();	
	}	
		
	public function fetch_training_type($training_type){
			$query = $this->db->query('select mt_details_name from master_type_details where mt_details_id ="'.$training_type.'" ');
			return $data = $query->result_array();	
	}	
/*
	*Function to fetch user details
	*@parameter: 
	*@return:
	*/
	public function fetch_my_qualification($data){
	$query = $this->db->query('SELECT my_qua_id,user_id,degree,mt.mt_details_name,m.university,m.specialization,m.specialization_detl,yog FROM my_qualification m join users as u ON 
	m.user_id = u.id join master_type_details as mt on mt.mt_details_id= m.degree  where m.user_id = "'.$data['user_id'].'"
	order by mt_details_name desc');
	return $re = $query->result_array();	
	}
	/*
	*Function to Fetch Work_load
	*@param:
	*@return:
	*/
	public function fetch_work_load($id){
		$query = 'select mt.mt_details_name ,d.dept_name,p.pgm_title,pt.pgmtype_name,m.accademic_year,
				 m.user_id,m.my_wid, m.program_type, m.program, m.dept_id, m.program_category , m.year , m.workload_percent from my_workload m
				join master_type_details as mt  On mt.mt_details_id = m.program_category
				join department as d ON d.dept_id = m.dept_id
				join program as p On p.pgm_id =m.program
				join program_type as pt ON pt.pgmtype_id = m.program_type
				where m.user_id="'.$id.'"';
		$result=$this->db->query($query);
		return $data = $result->result_array();	
	}

	/**
	*Function to fetch my_achievement details
	*@Param :
	*@Return : 
	**/
	public function fetch_my_Achievements($user_id ,$research_journal){	

			$query ='Select * from user_research_journal_publications m join master_type_details mm ON mm.mt_details_id= m.status where m.user_id="'.$user_id.'" and research_journal ="'.$research_journal.'" ';
			$result = $this->db->query($query);$data = $result->result_array();
		return ($data);
	}
		/**
	* Function to
	* @parameters:
	* @return: 
	*/
	public function fetch_consultant_project_data($data){
			$query='select * from consultancy_projects u join master_type_details as m on m.mt_details_id = u.status where user_id = "'.$data['user_id'].'"';
			$result = $this->db->query($query);
			return $data = $result->result_array();			
	}	
	/**
	* Function to
	* @parameters:
	* @return: 
	*/
	public function fetch_sponsored_project_data($data){
			$query='select * from sponsored_projects u join master_type_details as m on m.mt_details_id = u.status where u.user_id = "'.$data['user_id'].'"';
			$result = $this->db->query($query);
			return $data = $result->result_array();			
	}	
		/**
	* Function to
	* @parameters:
	* @return: 
	*/
	public function fetch_award_honour_data($data){
			$query='select * from user_awards_honours where user_id = "'.$data['user_id'].'"';
			$result = $this->db->query($query);
			return $data = $result->result_array();			
	}	
	
	/**
	* Function to
	* @parameters:
	* @return: 
	*/
	public function fetch_patent($data){
			$query='select * from user_patent as u join master_type_details as m on m.mt_details_id = u.status where u.user_id = "'.$data['user_id'].'"';
			$result = $this->db->query($query);
			return $data = $result->result_array();			
	}	
	
	/**
	* Function to
	* @parameters:
	* @return: 
	*/
	public function fetch_scholar($data){
			$query='select * from user_fellowship_scholar as u join master_type_details as m on m.mt_details_id = u.type where u.user_id = "'.$data['user_id'].'"';
			$result = $this->db->query($query);
		    return $data = $result->result_array();			
	}

	/**
	* Function to fetch paper presentation
	* @parameters:
	* @return: 
	*/
	public function fetch_paper_presentation($data){
	//if($data['select_level_present'] == '-1'){
 			$query='select * from user_paper_presentation as u , master_type_details as m where m.mt_details_id = u.presentation_type and u.user_id = "'.$data['user_id'].'"'; 		
		/* }else{
			$query='select * from user_paper_presentation as u , master_type_details as m where m.mt_details_id = u.presentation_type and u.user_id = "'.$data['user_id'].'" and presentation_level="'.$data['select_level_present'].'"'; 	
		} */	
			$result = $this->db->query($query);
			return $data = $result->result_array();			
	}
	
	/**
	* Function to
	* @parameters:
	* @return: 
	*/
	public function fetch_text_reference_book($data){
 			$query = ('select * from user_text_reference_book as u , master_type_details as m where m.mt_details_id = u.book_type and  u.user_id = "'.$data['user_id'].'"');
			$result = $this->db->query($query);
			return $data = $result->result_array();	
					
	}	
	
	public function fetch_files($data){
		$query = $this->db->query('select * from  upload_user_data_files where user_id = "'.$data['user_id'].'" and table_ref_id = "'.$data['table_ref_id'].'" and tab_ref_id="'.$data['tab_ref_id'].'" ');		
		return $query->result_array();	
	}
	
	public function fetch_faculty_type($user_id){
		$query = $this->db->query('select mt_details_name from users u join master_type_details as m ON u.faculty_type = m.mt_details_id where id="'.$user_id.'"');		
		$re = $query->result_array();	
		if(!empty($re)){
		return $re[0]['mt_details_name'];
		}else { return str_repeat("&nbsp;", 9)."--";}
	}

	public function fetch_faculty_serving($user_id){
		$query = $this->db->query('select mt_details_name from users u join master_type_details as m ON u.faculty_serving = m.mt_details_id where id="'.$user_id.'"');		
		$re = $query->result_array();
		if(!empty($re)){
		return $re[0]['mt_details_name'];
		}else { return str_repeat("&nbsp;", 9)."--";}
	}	
	
	public function fetch_designation($user_id){
		$query = $this->db->query('select designation_name from users u join user_designation as m ON u.designation_id = m.designation_id where  id="'.$user_id.'"');		
		$re = $query->result_array();
		if(!empty($re)){
		return $re[0]['designation_name'];
		}else { return str_repeat("&nbsp;", 9)."--";}
	}
	public function fetch_designation_data($user_id) {
		$query = $this->db->query('SELECT d.dept_name , u.year ,ud.designation_name FROM user_designation_list u join department as d ON d.dept_id = u.dept_id join user_designation as ud ON ud.designation_id = u.designation where u.user_id ="'.$user_id.'" ');
		$data = $query->result_array();
	    return $data;
    }
		
}