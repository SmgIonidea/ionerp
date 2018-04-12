<?php
/**
 * Description	:	Publications and Awards in inter-institute events by students of the 
					programme of study model
 * Created		:	07 June, 2016
 * Author		:	Arihant Prasad
 * Modification History:-
 *   Date                    Modified By                         	Description
---------------------------------------------------------------------------------------------- */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Publications_awards_model extends CI_Model {

    /**
     * Function to fetch course and course learning objectives details
     * @return: course title, course learning objective details, course learning objectives id
      and course learning objective count
     */
    public function dept_details() {
		$loggedin_user_id = $this->ion_auth->user()->row()->id;
		
		if($this->ion_auth->is_admin()) {
			$dept_name = 'SELECT DISTINCT dept_id, dept_name
						  FROM department 
						  WHERE status = 1
						  ORDER BY dept_name ASC';
		} else {
			$dept_name = 'SELECT DISTINCT d.dept_id, d.dept_name
						  FROM department AS d, users AS u
						  WHERE u.id = "' . $loggedin_user_id . '" 
							AND u.user_dept_id = d.dept_id 
							AND d.status = 1
						  ORDER BY d.dept_name ASC';		
		}
		
		$department_result = $this->db->query($dept_name);
		$department_data = $department_result->result_array();
		$dept_data['dept_result'] = $department_data;
		
		return $dept_data;
    }
	
	/* Function used to fetch the pgm id & name from program table.
	 * @parameters: department id 
	 * @returns: a array of values of the program details.
	 */
	public function pgm_details($dept_id) {
		$loggedin_user_id = $this->ion_auth->user()->row()->id;
		$pgm_name = 'SELECT DISTINCT pgm_id, pgm_acronym 	
					 FROM program
					 WHERE dept_id = "' . $dept_id . '"
						AND status = 1
					 ORDER BY pgm_acronym ASC';
		$resx = $this->db->query($pgm_name);
		$result = $resx->result_array();
		$pgm_data['pgm_result'] = $result;
		
		return $pgm_data;
	}
	
	/*
     * Function to fetch the curriculum details.
     * @parameters: program id
     * returns list of curriculum details.
	*/
    public function crlcm_drop_down_fill($pgm_id) {
		$crclm_query = 'SELECT crclm_id, crclm_name 	
						FROM curriculum
						WHERE pgm_id = "'.$pgm_id.'"
							AND status = 1
						ORDER BY crclm_name ASC';
		$crclm_result = $this->db->query($crclm_query);
		$crclm_data = $crclm_result->result_array();
		
		return $crclm_data;
    }
	
	/*
     * Function is to fetch the term details.
     * @param - -----.
     * returns list of term names.
	 */
    public function term_drop_down_fill($crclm_id) {
		return $this->db->select('crclm_term_id, term_name')
					 ->where('crclm_id',$crclm_id)        		
					 ->get('crclm_terms')
					 ->result_array();
    } 
	
	/*
     * Function is to fetch the term details.
     * @param - -----.
     * returns list of term names.
	 */
    public function course_drop_down_fill($crclm_id, $term_id) {
		$user = $this->ion_auth->user()->row()->id;
		
		if ($this->ion_auth->in_group('admin') || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')) {
			$course_result =  $this->db->select('course.crs_id, course.crs_title, course.crs_code, course.crs_mode,
											course_type.crs_type_name,assessment_occasions.status AS ao_status')
											->select('clo_owner_id')
											->select('username, title, first_name, last_name','left')
											->join('course_type','course_type.crs_type_id = course.crs_type_id','left')
											->join('course_clo_owner', 'course_clo_owner.crs_id = course.crs_id','left')
											->join('assessment_occasions', 'assessment_occasions.crs_id = course.crs_id','left')
											->join('users', 'users.id = course_clo_owner.clo_owner_id','left')
											->where('course.crclm_id', $crclm_id)
											->where('course.crclm_term_id', $term_id)
											->where('course.status',1)
											->group_by('course.crs_id')
											->get('course')
											->result_array();
		} else {
			$course_result =  $this->db->select('course.crs_id, course.crs_title, course.crs_code, course.crs_mode,
									course_type.crs_type_name,assessment_occasions.status AS ao_status')
									->select('clo_owner_id')
									->select('username, title, first_name, last_name','left')
									->join('course_type','course_type.crs_type_id = course.crs_type_id','left')
									->join('course_clo_owner', 'course_clo_owner.crs_id = course.crs_id','left')
									->join('assessment_occasions', 'assessment_occasions.crs_id = course.crs_id','left')
									->join('users', 'users.id = course_clo_owner.clo_owner_id','left')
									->where('course_clo_owner.clo_owner_id', $user)											
									->where('course.crclm_id', $crclm_id)
									->where('course.crclm_term_id', $term_id)
									->where('course.status',1)
									->group_by('course.crs_id')
									->get('course')
									->result_array();
		}
		
		return  $course_result;
    }
	
	/*
     * Function to fetch publications and award details
     * @parameters: deptarment id, program id and curriculum id
     * returns: list of publications and awards
	 */
    public function publication_awards_details($crclm_id, $term_id){	
		$loggedin_user_id = $this->ion_auth->user()->row()->id;
		
		if ($this->ion_auth->in_group('admin') || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')) {
			$course_query =  ' SELECT *
								FROM publications_awards
								WHERE crclm_id="'.$crclm_id.'" and crclm_term_id="'.$term_id.'" ' ;
			$course_result = $this->db->query($course_query);
			$course_data = $course_result->result_array();
		} else {
			$course_query =  ' SELECT 	*
								FROM publications_awards
								WHERE crclm_id="'.$crclm_id.'" and crclm_term_id="'.$term_id.'"  and user_id= "' . $loggedin_user_id . '"  
								';
			$course_result = $this->db->query($course_query);
			$course_data = $course_result->result_array();
		}
		
		return $course_data;
	}
	
	/**
	* Function to save publications and awards
	* @parameters:
	* @return: 
	*/
	public function save_award_publc($data){
		$result = ($this->db->insert('publications_awards', $data));	
		if($result){return 1;}else{return 0;}
	}
	/**
	* Function to update publications and awards
	* @parameters:
	* @return: 
	*/
	public function update_award_publc($data){
	
			$this->db->where('publ_award_id', $data['publ_award_id']);
			$this->db->where('dept_id', $data['dept_id']);
			$this->db->where('pgm_id', $data['pgm_id']);	
			$this->db->where('crclm_id', $data['crclm_id']);
			$this->db->where('crclm_term_id', $data['crclm_term_id']);
			$result = $this->db->update('publications_awards', $data); 		
			if($result){return 1;}else{return 0;}
	}
	/**
	* Function to Delete publications and awards
	* @parameters:
	* @return: 
	*/
	public function delete_publc_award($publication_awards){
		$result = $this->db->query('delete from publications_awards where publ_award_id="'.$publication_awards.'"');
		if($result){return '1';}else{return '0';}
	}
	
	/*
	*Function to fetch research papers / Guid of user
	*@parameter: 
	*@return: 
	*/
	public function  fetch_records($tab_id ,$per_table_id){
		$query = $this->db->query('select * from upload_user_data_files where table_ref_id="'.$per_table_id.'" and tab_ref_id="'.$tab_id.'" ');
		return $query->result_array();
	
	}
	
		/*
	*Function to insert  research papers / Guid  of user
	*@parameter: 
	*@return: 
	*/
	public function save_uploaded_files($result){
		($this->db->insert('upload_user_data_files', $result));
	}
	
	
	public function delete_uploaded_files($tab_id ,$per_table_id){
	//var_dump($tab_id); var_dump($per_table_id);
	$result = $this->db->query('delete from  upload_user_data_files where uload_id="'.$per_table_id.'" and tab_ref_id="'.$tab_id.'"');
	if($result){return '1';}else{return '0';}
	}
	
}
?>