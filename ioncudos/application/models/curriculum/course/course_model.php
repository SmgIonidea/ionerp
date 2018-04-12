<?php

/**
 * Description	:	Model(Database) Logic for Course Module(List, Edit & Delete).
 * Created		:	09-04-2013. 
 * Modification History:
 * Date				Modified By				Description
 * 10-09-2013		Abhinay B.Angadi        Added file headers, function headers, indentations & comments.
 * 11-09-2013		Abhinay B.Angadi		Variable naming, Function naming & Code cleaning.
 * 11-04-2014		Jevi V G     	        Added help function. 
 * 02-12-2015		Bhagyalaxmi 			Added total weightage of cia add tee
  -------------------------------------------------------------------------------------------------
 */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Course_model extends CI_Model {
    /* Function is used to fetch the course, course type, term,course designer & course reviewer details 
     * from course, crclm_terms, course_clo_owner, course_validator, users & curse_type tables.
     * @param - curriculum id & term id.
     * @returns- a array of values of all the course details.
     */
     public function __construct() {
        parent::__construct();
        $this->load->helper('array_helper');
    }
    public function course_list($crclm_id, $term_id) {
        $crs_list = 'SELECT c.crs_id, c.crs_title, c.crs_type_id, c.crs_acronym, c.crs_code, lect_credits,crs_domain_id,
							tutorial_credits, practical_credits, c.self_study_credits, c.total_credits, c.cie_marks,
							c.see_marks, c.total_marks, c.contact_hours, c.see_duration, c.crs_mode, c.co_crs_owner, c.ss_marks, 
							c.status, t.term_name,c.clo_bl_flag, c.cia_flag , c.mte_flag , c.tee_flag ,
							u.clo_owner_id, r.validator_id, s.title, s.username, s.first_name, s.last_name, usr.title as usr_title, usr.username as usr_usr_name, usr.first_name as usr_first_name, usr.last_name as usr_last_name, ct.crs_type_name
					FROM  course AS c
					LEFT JOIN crclm_terms t ON t.crclm_term_id = "' . $term_id . '"
					LEFT JOIN course_clo_owner u ON u.crs_id = c.crs_id
					LEFT JOIN course_clo_validator r ON r.crs_id = c.crs_id 
					LEFT JOIN users s ON s.id = u.clo_owner_id
					LEFT JOIN users usr ON usr.id = r.validator_id
					LEFT JOIN course_type ct ON ct.crs_type_id = c.crs_type_id
					WHERE c.crclm_id = "' . $crclm_id . '"
					AND c.crclm_term_id = "' . $term_id . '"';
        $crs_list_result = $this->db->query($crs_list);
        $crs_list_data = $crs_list_result->result_array();
        $crs_list_return['crs_list_data'] = $crs_list_data;

        return $crs_list_return;
    }

// End of function course_list.

    /* Function is used to fetch the course, course type, term & course designer details from course table.
     * @param - curriculum id.
     * @returns- a array of values of all the course details.
     */

    public function course_detailslist($crclm_id) {
        return $this->db->select('course.crs_id, course.crclm_term_id, course.crs_type_id, crs_title, crs_acronym, crs_code,
	                              crs_mode, co_crs_owner, lect_credits, tutorial_credits, practical_credits, self_study_credits, 
								  total_credits, contact_hours, cie_marks, see_marks, ss_marks, total_marks, see_duration')
                        ->select('crs_type_name')
                        ->select('clo_owner_id')
                        ->select('title, username, first_name, last_name')
                        ->select('term_name, term_duration, term_credits, total_theory_courses, total_practical_courses')
                        ->join('course_type', 'course_type.crs_type_id = course.crs_type_id')
                        ->join('course_clo_owner', 'course_clo_owner.crs_id = course.crs_id')
                        ->join('users', 'users.id = course_clo_owner.clo_owner_id')
                        ->join('crclm_terms', 'crclm_terms.crclm_term_id = course.crclm_term_id')
                        ->order_by('course.crclm_term_id', 'asc')
                        ->where('course.crclm_id', $crclm_id)
                        ->get('course')
                        ->result_array();
    }

// End of function course_list.

    /* Function is used to fetch the curriculum id & name from curriculum table.
     * @param - 
     * @returns- a array of values of the curriculum details.
     */

    public function crclm_fill() {
        $loggedin_user_id = $this->ion_auth->user()->row()->id;
        if ($this->ion_auth->is_admin()) {
            $curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name 
								FROM curriculum AS c, dashboard AS d 
								WHERE d.crclm_id = c.crclm_id 
								AND d.entity_id = 4 
								AND c.status = 1
								ORDER BY c.crclm_name ASC';
        } else {
            $curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name 
							FROM curriculum AS c, users AS u, program AS p, dashboard AS d 
							WHERE u.id = "' . $loggedin_user_id . '" 
							AND u.user_dept_id = p.dept_id 
							AND c.pgm_id = p.pgm_id 
							AND d.crclm_id = c.crclm_id 
							AND d.entity_id = 4 
							AND c.status = 1
							ORDER BY c.crclm_name ASC';
        }
        $resx = $this->db->query($curriculum_list);
        $res2 = $resx->result_array();
        $crclm_data['res2'] = $res2;
        return $crclm_data;
    }

// End of function crclm_fill.

    /* Function is used to fetch the term id & name from crclm_terms table.
     * @param - curriculum id.
     * @returns- a array of values of the term details.
     */

    public function term_fill($crclm_id) {
        $term_name = 'SELECT crclm_term_id, term_name FROM crclm_terms WHERE crclm_id = "' . $crclm_id . '" ';
        $result = $this->db->query($term_name);
        $data = $result->result_array();
        $term_data['res2'] = $data;

        return $term_data;
    }

// End of function term_fill.

    /* Function is used to fetch the curriculum id & name from curriculum table.
     * @param - 
     * @returns- a array of values of the curriculum details.
     */

    public function dropdown_curriculumlist() {
        $loggedin_user_id = $this->ion_auth->user()->row()->id;
        if ($this->ion_auth->is_admin()) {
            $curriculum_list = 'SELECT c.crclm_id, c.crclm_name 
								FROM curriculum AS c, dashboard AS d 
								WHERE d.crclm_id = c.crclm_id 
								AND d.entity_id = 4 
								AND c.status = 1 
								AND d.status = 1 
								ORDER BY crclm_name ASC';
        } else {
            $curriculum_list = 'SELECT c.crclm_id, c.crclm_name 
								FROM curriculum AS c, users AS u, program AS p, dashboard AS d 
								WHERE u.id = "' . $loggedin_user_id . '" 
								AND u.user_dept_id = p.dept_id 
								AND c.pgm_id = p.pgm_id 
								AND d.crclm_id = c.crclm_id 
								AND d.entity_id = 4 
								AND c.status = 1 
								ORDER BY c.crclm_name ASC';
        }
        $curriculum_list = $this->db->query($curriculum_list);
        $curriculum_list = $curriculum_list->result_array();

        return $curriculum_list;
    }

// End of function dropdown_curriculumlist.

    /* Function is used to fetch the department id & name from department table.
     * @param - 
     * @returns- a array of values of the department details.
     */

    public function dropdown_department() {
        $status = 1;
        return $this->db->select('dept_id, dept_name')
                        ->where('status', $status)
                        ->order_by('dept_name', 'asc')
                        ->get('department')
                        ->result_array();
    }

// End of function dropdown_department.

    /* Function is used to fetch the course type id & name from course_type table.
     * @param - 
     * @returns- a array of values of the course type details.
     */

    public function dropdown_coursetypelist($crclm_id) {
        return $this->db->select('crclm_crs_type_map_id, course_type_id AS crs_type_id, course_type.crs_type_name')
                        ->where('crclm_id', $crclm_id)
                        ->join('course_type', ' crclm_crs_type_map.course_type_id = course_type.crs_type_id')
                        ->order_by('crs_type_name', 'asc')
                        ->get('crclm_crs_type_map')
                        ->result_array();
    }

// End of function dropdown_coursetypelist.

    /* Function is used to fetch the user id, first & last name from users table.
     * @param - 
     * @returns- a array of values of the user details.
     */

    public function dropdown_userlist() {
        $loggedin_user_dept_id = $this->ion_auth->user()->row()->base_dept_id;
        $active = 1;
        return $this->db->select('users.id, users.title, users.username, users.first_name, users.last_name')
                        ->join('users_groups', 'users_groups.user_id = users.id')
                        ->where('base_dept_id', $loggedin_user_dept_id)
                        ->where('active', $active)
                        ->where('users_groups.group_id', 6)
						->where('users.active', 1)						
                        ->order_by('first_name', 'asc')
                        ->get('users')
                        ->result_array();
    }

// End of function dropdown_userlist.

    /* Function is used to fetch the user id, first & last name from users table.
     * @param - 
     * @returns- a array of values of the user details.
     */

    public function reviewer_dropdown_userlist($loggedin_user_dept_id) {
        $active = 1;
        return $this->db->select('users.id, users.title, users.active,users.username, users.first_name, users.last_name, users.email')
                        ->join('users_groups', 'users_groups.user_id = users.id')
                        ->where('base_dept_id', $loggedin_user_dept_id)
                        ->where('active', $active)
                        ->where('users_groups.group_id', 6)
                        ->order_by('first_name', 'asc')
                        ->get('users')
                        ->result_array();
    }

// End of function reviewer_dropdown_userlist.

    public function owner_dropdown_userlist($loggedin_user_id) {
        $dept_id = $this->ion_auth->user()->row()->base_dept_id;
        $active = 1;
        if (!$this->ion_auth->is_admin()) {

            $data = 'SELECT * FROM
                                (SELECT u.id, u.title,u.active, u.first_name,u.last_name, u.email
									FROM users as u, users_groups as g
									WHERE u.id=g.user_id 
									AND u.active = 1 
									AND u.base_dept_id = ' . $this->ion_auth->user()->row()->base_dept_id . '
									AND g.group_id = 6 

									UNION

									SELECT u.id,u.title,u.active,u.first_name,u.last_name, u.email
									FROM map_user_dept m,map_user_dept_role mdr,groups g,users u
									WHERE m.assigned_dept_id = ' . $this->ion_auth->user()->row()->base_dept_id . '
									AND m.user_id = mdr.user_id
									AND mdr.role_id = g.id
									AND m.user_id = u.id
									AND g.id = 6) 
								A ORDER BY A.first_name ASC ';
            $result = $this->db->query($data);
            $user_list = $result->result_array();
            return $user_list;
        } else {
            $data = 'SELECT * FROM
									(SELECT u.id, u.title, u.active ,  u.first_name,u.last_name, u.email
										FROM users as u, users_groups as g
										WHERE u.id=g.user_id 
										AND u.active = 1 
										AND g.group_id = 6 

										UNION

										SELECT u.id,u.title,u.active ,u.first_name,u.last_name, u.email
										FROM map_user_dept m,map_user_dept_role mdr,groups g,users u
										WHERE m.user_id = mdr.user_id
										AND u.user_dept_id = ' . $dept_id . '
										AND mdr.role_id = g.id
										AND m.user_id = u.id
										AND g.id = 6) 
									A ORDER BY A.first_name ASC ';
            $result = $this->db->query($data);
            $user_list = $result->result_array();
            return $user_list;
        }
    }

    /* Function is used to fetch the user id, first & last name from users table.
     * @param - department id.
     * @returns- a array of values of the user details.
     */

    public function dropdown_userlist2($dept_id) {
        $active = 1;
        return $this->db->select('users.id, users.title, users.username, users.first_name, users.last_name, users.email')
                        ->join('users_groups', 'users_groups.user_id = users.id')
                        ->where('base_dept_id', $dept_id)
                        ->where('active', $active)
                        ->where('users_groups.group_id', 6)
                        ->order_by('first_name', 'asc')
                        ->get('users')
                        ->result_array();
    }

// End of function dropdown_userlist2.

    /* Function is used to fetch the user id, first & last name from users table.
     * @param - curriculum id.
     * @returns- a array of values of the user details.
     */

    public function dropdown_userlist3($crclm_id) {
        $active = 1;

        $data = 'SELECT * FROM
                                (SELECT u.id, u.title, u.first_name,u.last_name,u.email
                                FROM users as u, users_groups as g, program as p, curriculum as c
                                WHERE u.id=g.user_id AND u.active = 1 AND u.user_dept_id = p.dept_id AND p.pgm_id = c.pgm_id AND g.group_id = 6 AND c.crclm_id = "' . $crclm_id . '"

                                UNION

                                SELECT u.id,u.title,u.first_name,u.last_name,u.email
                                FROM map_user_dept m,map_user_dept_role mdr,groups g,users u, program as p, curriculum as c,users_groups as gr
                                WHERE m.assigned_dept_id = p.dept_id AND p.pgm_id = c.pgm_id AND gr.group_id = 6 AND c.crclm_id = "' . $crclm_id . '"
                                AND m.user_id = mdr.user_id
                                AND mdr.role_id = g.id
                                AND m.user_id = u.id
                                AND g.id = 6) A ORDER BY A.first_name ASC ';
        $result = $this->db->query($data);
        $user_list = $result->result_array();
        return $user_list;
    }

// End of function dropdown_userlist3.

    /* Function is used to fetch the curriculum details from curriculum table.
     * @param - curriculum id.
     * @returns- a array of values of the curriculum details.
     */

    public function curriculum_details($crclm_id) {
        return $this->db->select('crclm_id, crclm_name, crclm_description, total_credits, total_terms, 
									start_year, end_year, crclm_owner')
                        ->select('title, username, first_name, last_name')
                        ->join('users', 'users.id = curriculum.crclm_owner')
                        ->where('crclm_id', $crclm_id)
                        ->get('curriculum')
                        ->result_array();
    }

// End of function curriculum_details.

    /* Function is used to fetch the PEO details from PEO table.
     * @param - curriculum id.
     * @returns- a array of values of the PEO details.
     */

    public function peo_list($crclm_id) {
        return $this->db->select('peo_id,peo_statement')
                        ->where('crclm_id', $crclm_id)
                        ->get('peo')
                        ->result_array();
    }

// End of function peo_list.

    /* Function is used to fetch the PO details from PO table.
     * @param - curriculum id.
     * @returns- a array of values of the PO details.
     */

    public function po_list($crclm_id) {
        return $this->db->select('po_id,po_statement')
                        ->where('crclm_id', $crclm_id)
                        ->get('po')
                        ->result_array();
    }

// End of function po_list.	

    /* Function is used to fetch the Predecessor Course details from predecessor_courses table.
     * @param - course id.
     * @returns- a array of values of the Predecessor Course details.
     */

    public function predessor_details($crs_id) {
        return $this->db->select('predecessor_id')
                        ->select('predecessor_course')
                        ->where('predecessor_courses.crs_id', $crs_id)
                        ->get('predecessor_courses')
                        ->result_array();
    }

// End of function predessor_details.	

    /* Function is used to fetch the term details from crclm_terms table.
     * @param - curriculum id.
     * @returns- a array of values of the term details.
     */

    public function term_details($crclm_id) {
        return $this->db->select('crclm_term_id')
                        ->select('term_name')
                        ->select('term_duration')
                        ->select('term_credits')
                        ->select('total_theory_courses')
                        ->select('total_practical_courses')
                        ->where('crclm_id', $crclm_id)
                        ->get('crclm_terms')
                        ->result_array();
    }

// End of function term_details.	

    /* Function is used to fetch course type names from crclm_crs_type_map table.
     * @param - curriculum id.
     * @returns- a array of values of the course type details.
     */

    public function fetch_course_type($crclm_id) {
        $query = $this->db->select('crclm_crs_type_map_id, course_type_id AS crs_type_id, course_type.crs_type_name')
                ->where('crclm_id', $crclm_id)
                ->join('course_type', ' crclm_crs_type_map.course_type_id = course_type.crs_type_id')
                ->order_by('crs_type_name', 'asc')
                ->get('crclm_crs_type_map')
                ->result_array();
        return $query;
    }

// End of function fetch_course_type.	

    /* Function is used to fetch the course details from course table.
     * @param - course id.
     * @returns- a array of values of the course details.
     */

    public function course_details($crs_id) {
        return $this->db->select('course.crs_id, cia_flag, mte_flag , tee_flag , crclm_id,mid_term_marks, crclm_term_id, crs_type_id, crs_domain_id, crs_mode, crs_code,co_crs_owner,
									crs_title, crs_acronym, lect_credits, tutorial_credits, practical_credits, 
									self_study_credits,total_credits, contact_hours, cie_marks, see_marks,ss_marks, total_marks, 
									see_duration,total_cia_weightage,total_mte_weightage,total_tee_weightage,cognitive_domain_flag, affective_domain_flag, psychomotor_domain_flag')
                        ->where('course.crs_id', $crs_id)
                        ->get('course')
                        ->result_array();
    }

// End of function course_details.	

    /* Function is used to fetch the course designer & course reviewer details from 
     * course_clo_owner & course_clo_validator  table.
     * @param - course id.
     * @returns- a array of values of the course designer & course reviewer details.
     */

    public function course_owner_details($crs_id) {
        $data['owner_details'] = $this->db->select('crclm_id, clo_owner_id')
                ->select('dept_id')
                ->select('last_date')
                ->where('crs_id', $crs_id)
                ->get('course_clo_owner')
                ->result_array();

        $data['reviewer_details'] = $this->db->select('validator_id')
                ->select('dept_id')
                ->select('last_date')
                ->where('crs_id', $crs_id)
                ->get('course_clo_validator')
                ->result_array();
        return $data;
    }

// End of function course_owner_details.	

    /* Function is used to delete a course from course table.
     * @param - course id.
     * @returns- a boolean value.
     */

    public function course_delete($crs_id) {
        $crs_del = 'DELETE FROM course WHERE crs_id = "' . $crs_id . '" ';
        $result = $this->db->query($crs_del);
    }

// End of function course_delete.	

    /* Function is used to fetch the term id & curriculum id from course table.
     * @param - course id.
     * @returns- an array of values of course.
     */

    public function publish_course_curriculum($crs_id) {
        $crclm_id = 'SELECT crclm_id, crclm_term_id FROM course WHERE crs_id = "' . $crs_id . '" ';
        $result = $this->db->query($crclm_id);
        $data = $result->result_array();
        return $data;
    }

// End of function publish_course_curriculum.	

    /* Function is used to fetch the course designer id, term id & course title 
     * from course_clo_owner, crclm_terms & course table.
     * @param - course id & curriculum id.
     * @returns- an array of values of course.
     */

    public function publish_course_receiver($crs_id, $crclm_term_id) {
        $receiver_id = 'SELECT clo_owner_id FROM course_clo_owner WHERE crs_id = "' . $crs_id . '" ';
        $result = $this->db->query($receiver_id);
        $data = $result->result_array();
        $select = 'SELECT term_name FROM crclm_terms WHERE crclm_term_id = "' . $crclm_term_id . '" ';
        $select = $this->db->query($select);
        $row = $select->result_array();
        $data['term'] = $row;
        $select = 'SELECT crs_title FROM course WHERE crs_id = "' . $crs_id . '" ';
        $select = $this->db->query($select);
        $row = $select->result_array();
        $data['course'] = $row;
        return $data;
    }

// End of function publish_course_receiver.	

    /* Function is used to fetch the course reviewer id, term id & course title 
     * from course_clo_validator, crclm_terms & course table.
     * @param - course id & curriculum id.
     * @returns- an array of values of course.
     */

    public function publish_course_reviewer($crs_id, $crclm_term_id) {
        $reviewer_id = 'SELECT validator_id FROM  course_clo_validator WHERE crs_id = "' . $crs_id . '" ';
        $result = $this->db->query($reviewer_id);
        $reviewer_data = $result->result_array();
        return $reviewer_data;
    }

// End of function publish_course_reviewer.	

    /* Function is used to update the course status after initiation of CLO creation is done.
     * @param - course id.
     * @returns- a boolean value.
     */

    public function publish_course_update_status($crs_id) {
        $receiver_id = 'UPDATE course SET status = 1, state_id = 1 WHERE crs_id = "' . $crs_id . '" ';
        $result = $this->db->query($receiver_id);
        return $result;
    }

// End of function publish_course_update_status.	

    /* Function is used to insert the entry onto dashboard after the initiation of CLO creation is done.
     * @param - curriculum id, entity id, course id & many more.
     * @returns- an array of values of the term & course details.
     */

    public function publish_course($crclm_id, $term_id , $crs_id ,$entity_id, $particular_id, $sender_id, $receiver_id, $url, $description, $state, $status, $crclm_term_id, $reviewer_description, $reviewer_id) {
        // Dashboard entry to Initiate CLO Creation for Course Owner
       
	   $crs_publish_data = array(
            'crclm_id' => $crclm_id,
            'entity_id' => $entity_id,
            'particular_id' => $particular_id,
            'sender_id' => $sender_id,
            'receiver_id' => $receiver_id,
            'url' => $url,
            'description' => $description,
            'state' => $state,
            'status' => $status
        );
        $this->db->insert('dashboard', $crs_publish_data);

        // Dashboard entry to notify Course Reviewer selected 
        $reviewer_crs_publish_data = array(
            'crclm_id' => $crclm_id,
            'entity_id' => $entity_id,
            'particular_id' => $particular_id,
            'sender_id' => $sender_id,
            'receiver_id' => $reviewer_id,
            'url' => '#',
            'description' => $reviewer_description,
            'state' => $state,
            'status' => $status
        );
        $this->db->insert('dashboard', $reviewer_crs_publish_data);
		
		$query = $this->db->query('SELECT CONCAT(COALESCE(cognitive_domain_flag,""),",",COALESCE(affective_domain_flag,""),",",COALESCE(psychomotor_domain_flag,""))  crs_id from course where crs_id= "'.$crs_id.'"');
		$re = $query->result_array();
		$count = count($re[0]['crs_id']);

		$set_data = (explode(",",$re[0]['crs_id']));
		$sk=0; $bld_id = '';
		$bloom_domain_query = $this->db->query('SELECT bld_id,bld_name,status FROM bloom_domain');
        $bloom_domain_data = $bloom_domain_query->result_array();
		foreach($bloom_domain_data as $bdd){
			
			if ($set_data[$sk] == 1 && $bdd['status'] == 1) {
				$bld_id [] = $bdd['bld_id'];
				
			}
		$sk++;
		}
	
		if($bld_id != ' ' && !empty($bld_id)){
		$Bld_id_data = implode (",", $bld_id);

		$bld_id_single = str_replace("'", "", $Bld_id_data);	
		$bloom_lvl_query = 'select * from  bloom_level where bld_id IN('.$bld_id_single.') ORDER BY LPAD(LOWER(level),5,0) ASC';
		$bloom_level_threshold_data = $this->db->query($bloom_lvl_query);
		$bloom_level_threshold = $bloom_level_threshold_data->result_array();
		foreach($bloom_level_threshold as $bloom){
						$data = array(
							'crclm_id'   => $crclm_id,
							'term_id'  	 => $crclm_term_id,		
							'crs_id'  	 => $crs_id,	
							'bld_id'	 => $bloom['bld_id'],							
							'bloom_id'     => $bloom['bloom_id'],
							'cia_bloomlevel_minthreshhold' => 50,
							'mte_bloomlevel_minthreshhold' => 50,
                            'tee_bloomlevel_minthreshhold' => 50,
							'bloomlevel_studentthreshhold' => 70,        
							'created_by'    => $this->ion_auth->user()->row()->id,
							'created_date'  => date('Y-m-d') ,
							'modified_by'	=>  $this->ion_auth->user()->row()->id,
							'modified_date' =>  date('Y-m-d') );
		$this->db->insert('map_course_bloomlevel', $data);
	}
}
        $select = 'SELECT term_name FROM crclm_terms WHERE crclm_term_id = "' . $crclm_term_id . '" ';
        $select = $this->db->query($select);
        $row = $select->result_array();
        $crs_publish_data['term'] = $row;
        $select = 'SELECT crs_title FROM course WHERE crs_id = "' . $particular_id . '" ';
        $select = $this->db->query($select);
        $row = $select->result_array();
        $crs_publish_data['course'] = $row;

		
		$query = $this->db->query(" INSERT INTO attainment_level_course (crclm_id, crclm_term_id, crs_id, assess_level_name, 
									assess_level_name_alias, assess_level_value, cia_direct_percentage, mte_direct_percentage ,
									tee_direct_percentage, indirect_percentage, conditional_opr, 
									cia_target_percentage, mte_target_percentage , tee_target_percentage, justify,
									created_by, created_date, modified_by, modified_date)
									VALUES
									('".$crclm_id."', ".$term_id.", ".$crs_id.", 'Low', 'Low', 1, 50, 50 ,50, 60, '>=', 50, 50 ,40, 1, 1, ".date('Y-m-d').", NULL, NULL),
									('".$crclm_id."', ".$term_id.", ".$crs_id.", 'Medium', 'Medium', 2, 60, 50, 60, 70, '>=', 50, 50,  40, 2, 1, ".date('Y-m-d').", NULL, NULL),
									('".$crclm_id."', ".$term_id.", ".$crs_id.", 'High', 'High', 3, 70, 50 , 70, 80, '>=', 50, 50 ,40, 3, 1, ".date('Y-m-d').", NULL, NULL)");

        return $crs_publish_data;
    }

// End of function publish_course.	

    /* Function is used to update course, course designer, course reviewer & predecessor course details 
     * onto the course, course_clo_owner, course_clo_reviewer & predecessor course tables.
     * @param - course data, course_clo_owner data, course_clo_reviewer data &  predecessor course data.
     * @returns- a boolean value.
     */

    public function update_course(//course table data
    $crs_id, $crclm_id, $crclm_term_id, $crs_type_id, $crs_code, $crs_mode, $crs_title, $crs_acronym, $co_crs_owner, $crs_domain_id, $lect_credits, $tutorial_credits, $practical_credits, $self_study_credits, $total_credits, $contact_hours, $cie_marks, $see_marks, $ss_marks, $total_marks, $see_duration,
    //course_clo_owner
            $course_designer,
    //course_clo_reviewer
            $review_dept, $course_reviewer, $last_date,
    //predecessor courses array
            $pre_courses,
    // delete predecessor courses array
            $del_pre_courses ,
			
			$cia_check , $mte_check , $tee_check
    ) {	
        //update into course table
        $course_data = array(
            'crclm_id' => $crclm_id,
            'crclm_term_id' => $crclm_term_id,
            'crs_type_id' => $crs_type_id,
            'crs_code' => $crs_code,
            'crs_mode' => $crs_mode,
            'crs_title' => $crs_title,
            'crs_acronym' => $crs_acronym,
            'co_crs_owner' => $co_crs_owner,
            'crs_domain_id' => $crs_domain_id,
            'lect_credits' => $lect_credits,
            'tutorial_credits' => $tutorial_credits,
            'practical_credits' => $practical_credits,
            'self_study_credits' => $self_study_credits,
            'total_credits' => $total_credits,
            'contact_hours' => $contact_hours,
            'cie_marks' => $cie_marks,
            'see_marks' => $see_marks,
            'ss_marks' => $ss_marks,
            'total_marks' => $total_marks,
            'see_duration' => $see_duration,
			'cia_flag' => $cia_check,
			'mte_flag' => $mte_check,
			'tee_flag' => $tee_check,
            'modified_by' => $this->ion_auth->user()->row()->id,
            'modify_date' => date('Y-m-d')
        );
        $this->db->where('crs_id', $crs_id)
                ->update('course', $course_data);
        // Dashboard active links update if Course Owner or Course Reviewer are changed
        // To fetch Course Owner & Course Reviewer query
        $query = 'SELECT co.clo_owner_id, cr.validator_id
						FROM course_clo_owner AS co, course_clo_validator cr 
						WHERE co.crs_id = "' . $crs_id . '" 
						AND cr.crs_id = "' . $crs_id . '" ';
        $query_result = $this->db->query($query);
        $data = $query_result->result_array();
        $crs_owner = $data['0']['clo_owner_id'];
        $crs_reviewer = $data['0']['validator_id'];
        // To fetch Topic Ids for a given Course query
        $topic_query = 'SELECT topic_id, t_unit_id
								FROM topic
								WHERE course_id = "' . $crs_id . '" 
								AND curriculum_id = "' . $crclm_id . '" ';
        $topic_query_result = $this->db->query($topic_query);
        $topic_id_array = $topic_query_result->result_array();

        if ($crs_owner != $course_designer) {
            //update into course_clo_owner table
            $owner_data = array(
                'clo_owner_id' => $course_designer,
                'crclm_id' => $crclm_id,
                'crclm_term_id' => $crclm_term_id,
                'dept_id' => $review_dept,
                'modified_by' => $this->ion_auth->user()->row()->id,
                'modified_date' => date('Y-m-d')
            );
            $this->db->where('crs_id', $crs_id)
                    ->update('course_clo_owner', $owner_data);

            // Dashboard active links update of Course Owner till Mapping between COs to POs
            $query = 'UPDATE dashboard SET receiver_id = "' . $course_designer . '" 
							WHERE state IN (1,3,4,6,7)
							AND receiver_id = "' . $crs_owner . '" 
							AND particular_id = "' . $crs_id . '" 
							AND crclm_id = "' . $crclm_id . '" 
							AND status = 1 ';
            $query_result = $this->db->query($query);

            // Dashboard active links update of Course Owner form Topic to Mapping between TLOs to COs

            foreach ($topic_id_array AS $topic_id) {
                $query = 'UPDATE dashboard SET receiver_id = "' . $course_designer . '" 
								WHERE state IN (1,3,4)
								AND receiver_id = "' . $crs_owner . '" 
								AND particular_id = "' . $topic_id['topic_id'] . '" 
								AND crclm_id = "' . $crclm_id . '" 
								AND entity_id = 17
								AND status = 1 ';
                $query_result = $this->db->query($query);
            }
        }
        if ($crs_reviewer != $course_reviewer) {
            //update into course_clo_reviewer table
            $reviewer_data = array(
                'crclm_id' => $crclm_id,
                'term_id' => $crclm_term_id,
                'dept_id' => $review_dept,
                'validator_id' => $course_reviewer,
                'last_date' => $last_date,
                'modified_by' => $this->ion_auth->user()->row()->id,
                'modified_date' => date('Y-m-d')
            );
            $this->db->where('crs_id', $crs_id)
                    ->update('course_clo_validator', $reviewer_data);

            // Dashboard active links update of Course Reviewer
            $query = 'UPDATE dashboard SET receiver_id = "' . $course_reviewer . '" 
							WHERE state IN (1,2)
							AND receiver_id = "' . $crs_reviewer . '" 
							AND particular_id = "' . $crs_id . '" 
							AND crclm_id = "' . $crclm_id . '" 
							AND status = 1 ';
            $query_result = $this->db->query($query);

            // Dashboard active links update of Course Reviewer form Topic to Mapping between TLOs to COs

            foreach ($topic_id_array AS $topic_id) {
                $query = 'UPDATE dashboard SET receiver_id = "' . $course_reviewer . '" 
								WHERE state = 2
								AND receiver_id = "' . $crs_reviewer . '" 
								AND particular_id = "' . $topic_id['topic_id'] . '" 
								AND crclm_id = "' . $crclm_id . '" 
								AND entity_id = 17
								AND status = 1 ';
                $query_result = $this->db->query($query);
            }
        }
        //update into course_clo_reviewer table
        $reviewer_data = array(
            'crclm_id' => $crclm_id,
            'term_id' => $crclm_term_id,
            'dept_id' => $review_dept,
            'last_date' => $last_date,
            'modified_by' => $this->ion_auth->user()->row()->id,
            'modified_date' => date('Y-m-d')
        );
        $this->db->where('crs_id', $crs_id)
                ->update('course_clo_validator', $reviewer_data);

        //insert predecessor course array into predecessor courses table
        $pre_crs_array = '';
        $pre_crs_array = explode('<>', $pre_courses);
        $lmax = sizeof($pre_crs_array);
        for ($l = 0; $l < $lmax; $l++) {
            $predecessor_data = array(
                'crs_id' => $crs_id,
                'predecessor_course' => $pre_crs_array[$l],
                'created_by' => $this->ion_auth->user()->row()->id,
                'create_date' => date('Y-m-d')
            );
            if ($pre_crs_array[$l] != '') {
                $this->db->insert('predecessor_courses', $predecessor_data);
            }
        }
        // delete predecessor courses array 
        $kmax = sizeof($del_pre_courses);
        for ($k = 0; $k < $kmax; $k++) {
            if ($del_pre_courses[$k] != '') {
                $this->db->where('predecessor_id', $del_pre_courses[$k])
                        ->delete('predecessor_courses');
            }
        }
        return TRUE;
    }

// End of function update_course.

    /* Function is used to find the rows with a same course code & course title from course table.
     * @param - curriculum id, course title, course code & course id.
     * @returns- a row count value.
     */

    public function course_title_search_edit($crclm_id, $crs_title, $crs_code, $crs_id) {
        $crs_title = $this->db->escape_str($crs_title);
        $crs_code = $this->db->escape_str($crs_code);
        $query = 'SELECT crs_title FROM course 
					WHERE  crclm_id = "' . $crclm_id . '" 
					AND crs_code LIKE "' . $crs_code . '" 
					AND crs_id != "' . $crs_id . '" ';
        $result = $this->db->query($query);
        $count = $result->num_rows();
        if ($count == 1) {
            return $count;
        } else {
            $query = 'SELECT crs_title FROM course 
						WHERE  crclm_id = "' . $crclm_id . '" 
						AND crs_title LIKE "' . $crs_title . '" 
						AND crs_id != "' . $crs_id . '" ';
            $result = $this->db->query($query);
            $count = $result->num_rows();
            return $count;
        }
    }

// End of function course_title_search_edit.

    /**
     * Function to fetch help related details for course, which is
      used to provide link to help page
     * @return: serial number (help id), entity data, help description, help entity id and file path
     */
    public function course_help() {
        $help = 'SELECT serial_no, entity_data, help_desc 
				 FROM help_content 
				 WHERE entity_id = 4';
        $result = $this->db->query($help);
        $row = $result->result_array();
        $data['help_data'] = $row;

        if (!empty($data['help_data'])) {
            $help_entity_id = $row[0]['serial_no'];

            $file_query = 'SELECT help_entity_id, file_path
						   FROM uploads 
						   WHERE help_entity_id = "' . $help_entity_id . '"';
            $file_data = $this->db->query($file_query);
            $file_name = $file_data->result_array();
            $data['file'] = $file_name;

            return $data;
        } else {
            return $data;
        }
    }

    /**
     * Function to fetch help related to course to display
      the help contents in a new window
     * @parameters: help id
     * @return: entity data and help description
     */
    public function help_content($help_id) {
        $help = 'SELECT entity_data, help_desc 
				 FROM help_content 
				 WHERE serial_no = "' . $help_id . '"';
        $result_help = $this->db->query($help);
        $row = $result_help->result_array();

        return $row;
    }

    /* Function is used to update course, course designer, course reviewer & predecessor course details 
     * onto the course, course_clo_owner, course_clo_reviewer & predecessor course tables.
     * @param - course data, course_clo_owner data, course_clo_reviewer data &  predecessor course data.
     * @returns- a boolean value.
     */

    public function update_course_details(//course table data
    $crs_id, $crclm_id, $crclm_term_id, $crs_type_id, $crs_code, $crs_mode, $crs_title, $crs_acronym, $co_crs_owner, $crs_domain_id, $lect_credits, $tutorial_credits, $practical_credits, $self_study_credits, $total_credits, $contact_hours, $cie_marks, $see_marks, $ss_marks, $mid_term_marks, $total_marks, $see_duration,
    //course_clo_owner
            $course_designer,
    //course_clo_reviewer
            $review_dept, $course_reviewer, $last_date,
    //predecessor courses array
            $pre_courses,
    // delete predecessor courses array
            $del_pre_courses,
    // total weightage of cia and tee
            $total_cia_weightage, $total_mte_weightage , $total_tee_weightage, $bld_1, $bld_2, $bld_3 , $clo_bl_flag,
			
			$cia_check , $mte_check , $tee_check
    ) {

	$query_map_exist = $this->db->query('select * from map_course_bloomlevel  where crs_id = '. $crs_id.' ');
	$result_query_map_exist = $query_map_exist->result_array();
		// get old course owner id.        
        $old_crs_owner_id_query = 'SELECT clo_owner_id FROM course_clo_owner WHERE crs_id = "'.$crs_id.'"';
        $old_crs_owner = $this->db->query($old_crs_owner_id_query);
        $old_crs_owner_id = $old_crs_owner->row_array();
		
		 //insert into map_courseto_course_instructor table
        $section_id_quesry = 'SELECT mt_details_id FROM master_type_details WHERE mt_details_name = "A" ';
        $section_id_data = $this->db->query($section_id_quesry);
        $section_id = $section_id_data->row_array();
		
		// user  exist in map_courseto_course_instructor  table 
			$existing_user_query = 'SELECT course_instructor_id, section_id FROM map_courseto_course_instructor WHERE crs_id ="'.$crs_id.'" AND section_id = "'.$section_id['mt_details_id'].'" ';
			$result_data = $this->db->query($existing_user_query);
			$owner_result = $result_data->row_array();



        //update into course table
        $course_data = array(
            'crclm_id' => $crclm_id,
            'crclm_term_id' => $crclm_term_id,
            'crs_type_id' => $crs_type_id,
            'crs_code' => $crs_code,
            'crs_mode' => $crs_mode,
            'crs_title' => $crs_title,
            'crs_acronym' => $crs_acronym,
            'co_crs_owner' => $co_crs_owner,
            'crs_domain_id' => $crs_domain_id,
            'lect_credits' => $lect_credits,
            'tutorial_credits' => $tutorial_credits,
            'practical_credits' => $practical_credits,
            'self_study_credits' => $self_study_credits,
            'total_credits' => $total_credits,
            'contact_hours' => $contact_hours,
            'cie_marks' => $cie_marks,
            'mid_term_marks' => $mid_term_marks,
            'see_marks' => $see_marks,
            'ss_marks' => $ss_marks,
            'total_marks' => $total_marks,
            'see_duration' => $see_duration,
            'total_cia_weightage' => $total_cia_weightage,
			'total_mte_weightage' => $total_mte_weightage,
            'total_tee_weightage' => $total_tee_weightage,
            'cognitive_domain_flag' => $bld_1,
            'affective_domain_flag' => $bld_2,
            'psychomotor_domain_flag' => $bld_3,
            'clo_bl_flag' => $clo_bl_flag,
			'cia_flag' => $cia_check,
			'mte_flag' => $mte_check,
			'tee_flag' => $tee_check,
            'modified_by' => $this->ion_auth->user()->row()->id,
            'modify_date' => date('Y-m-d')
        );
        $this->db->where('crs_id', $crs_id)
                ->update('course', $course_data);

		if(!empty($result_query_map_exist)){
			if(isset($bld_1)){
				if($bld_1 == 1){$bld_11 = 1; }
				if($bld_1 == ''){$bld_11 = 0;  }
			}else{ $bld_11 = 0;  }
			if(isset($bld_2)){
				if($bld_2 == 1){$bld_12 = 1; }
				if($bld_2 == ''){$bld_12 = 0; }
			}else{ $bld_11 = 0;  }
			
			if(isset($bld_3)){
				if($bld_3 == 1){$bld_13 = 1;}
				if($bld_3 == ''){$bld_13 = 0; }
			}else{ $bld_13 = 0;  }

			$bld_array[] = ($bld_11);
			$bld_array[] = ($bld_12);
			$bld_array[] = ($bld_13);

			$query = $this->db->query('SELECT CONCAT(COALESCE(cognitive_domain_flag,""),",",COALESCE(affective_domain_flag,""),",",COALESCE(psychomotor_domain_flag,""))  crs_id from course where crs_id= "'.$crs_id.'"');
				$re = $query->result_array();
				$count = count($re[0]['crs_id']);
				$set_data = (explode(",",$re[0]['crs_id']));

			//$query_map = $this->db->query('select bld_id from map_course_bloomlevel where crclm_id = "'. $crclm_id .'" and crs_id = "'. $crs_id .'" group by bld_id ');	
			
			$query_map = $this->db->query('select b.bld_id from map_course_bloomlevel c left join bloom_level as b on c.bloom_id  = b.bloom_id  where crclm_id = "'. $crclm_id .'" and crs_id = "'. $crs_id .'" group by b.bld_id ');	
			$re_query_map =  $query_map->result_array();
				
	
				$sk=0;// $bld_id = 0;
				$bloom_domain_query = $this->db->query('SELECT bld_id,bld_name,status FROM bloom_domain');
				$bloom_domain_data = $bloom_domain_query->result_array();

				foreach($bloom_domain_data as $bdd){
				if ($set_data[$sk] == 1 && $bdd['status'] == 1) {			
						$bld_id [] = $bdd['bld_id'];	
				}
				/* 	if ($set_data[$sk] != $bld_array[$sk] && $bdd['status'] == 1) {			
						$bld_id [] = $bdd['bld_id'];							
					} */
					//if()
				$sk++;
				}
			$list=array();
			foreach($re_query_map as $v) {
				array_push($list, $v['bld_id']);
			}

			foreach($bld_id as $num) {
				if (!(in_array($num,$list))) {
				 $domain_val[] = $num;
				} 
			}

			if(!empty($domain_val)){

				$Bld_id_data = implode (",", $domain_val);
				$bld_id_single = str_replace("'", "", $Bld_id_data);	
				$bloom_lvl_query = 'select * from  bloom_level where bld_id IN('.$bld_id_single.') ORDER BY LPAD(LOWER(level),5,0) ASC';
				$bloom_level_threshold_data = $this->db->query($bloom_lvl_query);
				$bloom_level_threshold = $bloom_level_threshold_data->result_array();	
					foreach($bloom_level_threshold as $bloom){
									$data = array(
										'crclm_id'   => $crclm_id,
										'term_id'  	 => $crclm_term_id,		
										'crs_id'  	 => $crs_id,				
										'bloom_id'     => $bloom['bloom_id'],
										'bld_id'	 => $bloom['bld_id'],	
										'cia_bloomlevel_minthreshhold' => 50,
										'mte_bloomlevel_minthreshhold' => 50,
										'tee_bloomlevel_minthreshhold' => 50,
										'bloomlevel_studentthreshhold' => 70,        
										'created_by'    => $this->ion_auth->user()->row()->id,
										'created_date'  => date('Y-m-d') ,
										'modified_by'	=>  $this->ion_auth->user()->row()->id,
										'modified_date' =>  date('Y-m-d') );
				$this->db->insert('map_course_bloomlevel', $data);
				}
			}
	}



			
				
	if(!empty($result_query_map_exist)){			
				
				$query = $this->db->query('SELECT CONCAT(COALESCE(cognitive_domain_flag,""),",",COALESCE(affective_domain_flag,""),",",COALESCE(psychomotor_domain_flag,""))  crs_id from course where crs_id= "'.$crs_id.'"');
				$re = $query->result_array();
				$count = count($re[0]['crs_id']);

				$set_data = (explode(",",$re[0]['crs_id']));

			$query_map = $this->db->query('select bld_id from map_crclm_bloomlevel where crclm_id = "'. $crclm_id .'"  group by bld_id');	
			$re_query_map =  $query_map->result_array();
				
				$sk=0;
				$bloom_domain_query = $this->db->query('SELECT bld_id,bld_name,status FROM bloom_domain');
				$bloom_domain_data = $bloom_domain_query->result_array();
				foreach($bloom_domain_data as $bdd){

					if ($set_data[$sk] == 0 && $bdd['status'] == 1) {
						$bld_id_data [] = $bdd['bld_id'];							
					}
				$sk++;
				}

			if(!empty($bld_id_data)){
				$Bld_id_data = implode (",", $bld_id_data);
				$bld_id_single = str_replace("'", "", $Bld_id_data);	
				$bloom_lvl_query = 'select * from  bloom_level where bld_id IN('.$bld_id_single.') ORDER BY LPAD(LOWER(level),5,0) ASC';
				$bloom_level_threshold_data = $this->db->query($bloom_lvl_query);
				$bloom_level_threshold = $bloom_level_threshold_data->result_array();
					
				foreach($bloom_level_threshold as $da){
					$map_data_delet [] = $da['bloom_id'];
				}
				$Bld_id_data_array = implode (",", $map_data_delet);
				$bld_id_single_array = str_replace("'", "", $Bld_id_data_array);
				$query = $this->db->query('DELETE FROM map_course_bloomlevel where bloom_id IN ('. $bld_id_single_array .') and crs_id = '. $crs_id .'');
			}
	}
		
        // Dashboard active links update if Course Owner or Course Reviewer are changed
        // To fetch Course Owner & Course Reviewer query
        $query = 'SELECT co.clo_owner_id, cr.validator_id
						FROM course_clo_owner AS co, course_clo_validator cr 
						WHERE co.crs_id = "' . $crs_id . '" 
						AND cr.crs_id = "' . $crs_id . '" ';
        $query_result = $this->db->query($query);
        $data = $query_result->result_array();
        $crs_owner = $data['0']['clo_owner_id'];
        $crs_reviewer = $data['0']['validator_id'];
        // To fetch Topic Ids for a given Course query
        $topic_query = 'SELECT topic_id, t_unit_id
								FROM topic
								WHERE course_id = "' . $crs_id . '" 
								AND curriculum_id = "' . $crclm_id . '" ';
        $topic_query_result = $this->db->query($topic_query);
        $topic_id_array = $topic_query_result->result_array();
      
        if ($crs_owner != $course_designer) {
            //update into course_clo_owner table
            $owner_data = array(
                'clo_owner_id' => $course_designer,
                'crclm_id' => $crclm_id,
                'crclm_term_id' => $crclm_term_id,
                'dept_id' => $review_dept,
                'modified_by' => $this->ion_auth->user()->row()->id,
                'modified_date' => date('Y-m-d')
            );
            $this->db->where('crs_id', $crs_id)
                     ->update('course_clo_owner', $owner_data);
					
			// update map_courseto_course_instructor table with the new course owner id for that particular section.
		
			if($owner_result['course_instructor_id'] == $old_crs_owner_id['clo_owner_id']){
				$update_query = 'UPDATE map_courseto_course_instructor SET course_instructor_id = "'.$course_designer.'" ,crclm_id="'.$crclm_id.'",crclm_term_id="'.$crclm_term_id.'"  WHERE  crs_id ="'.$crs_id.'" AND section_id= "'.$section_id['mt_details_id'].'"';
				$update = $this->db->query($update_query);
			}
            
            // Dashboard active links update of Course Owner till Mapping between COs to POs
            $query = 'UPDATE dashboard SET receiver_id = "' . $course_designer . '" 
							WHERE state IN (1,3,4,6,7)
							AND receiver_id = "' . $crs_owner . '" 
							AND particular_id = "' . $crs_id . '" 
							AND crclm_id = "' . $crclm_id . '" 
							AND status = 1 ';
            $query_result = $this->db->query($query);

            // Dashboard active links update of Course Owner form Topic to Mapping between TLOs to COs

            foreach ($topic_id_array AS $topic_id) {
                $query = 'UPDATE dashboard SET receiver_id = "' . $course_designer . '" 
								WHERE state IN (1,3,4)
								AND receiver_id = "' . $crs_owner . '" 
								AND particular_id = "' . $topic_id['topic_id'] . '" 
								AND crclm_id = "' . $crclm_id . '" 
								AND entity_id = 17
								AND status = 1 ';
                $query_result = $this->db->query($query);
            }
        }else{
            
             //update into course_clo_owner table
            $owner_data = array(
                'clo_owner_id' => $course_designer,
                'crclm_id' => $crclm_id,
                'crclm_term_id' => $crclm_term_id,
                'dept_id' => $review_dept,
                'modified_by' => $this->ion_auth->user()->row()->id,
                'modified_date' => date('Y-m-d')
            );
            $this->db->where('crs_id', $crs_id)
                     ->update('course_clo_owner', $owner_data);
            // update map_courseto_course_instructor table with the new course owner id for that particular section.
		
			if($owner_result['course_instructor_id'] == $old_crs_owner_id['clo_owner_id']){
				$update_query = 'UPDATE map_courseto_course_instructor SET course_instructor_id = "'.$course_designer.'",crclm_id="'.$crclm_id.'" ,crclm_term_id="'.$crclm_term_id.'"  WHERE  crs_id ="'.$crs_id.'" AND section_id= "'.$section_id['mt_details_id'].'"';
				$update = $this->db->query($update_query);
			}
            
        }
        
        if ($crs_reviewer != $course_reviewer) {
            //update into course_clo_reviewer table
            $reviewer_data = array(
                'crclm_id' => $crclm_id,
                'term_id' => $crclm_term_id,
                'dept_id' => $review_dept,
                'validator_id' => $course_reviewer,
                'last_date' => $last_date,
                'modified_by' => $this->ion_auth->user()->row()->id,
                'modified_date' => date('Y-m-d')
            );
            $this->db->where('crs_id', $crs_id)
                    ->update('course_clo_validator', $reviewer_data);

            // Dashboard active links update of Course Reviewer
            $query = 'UPDATE dashboard SET receiver_id = "' . $course_reviewer . '" 
							WHERE state IN (1,2)
							AND receiver_id = "' . $crs_reviewer . '" 
							AND particular_id = "' . $crs_id . '" 
							AND crclm_id = "' . $crclm_id . '" 
							AND status = 1 ';
            $query_result = $this->db->query($query);

            // Dashboard active links update of Course Reviewer form Topic to Mapping between TLOs to COs

            foreach ($topic_id_array AS $topic_id) {
                $query = 'UPDATE dashboard SET receiver_id = "' . $course_reviewer . '" 
								WHERE state = 2
								AND receiver_id = "' . $crs_reviewer . '" 
								AND particular_id = "' . $topic_id['topic_id'] . '" 
								AND crclm_id = "' . $crclm_id . '" 
								AND entity_id = 17
								AND status = 1 ';
                $query_result = $this->db->query($query);
            }
        }
        //update into course_clo_reviewer table
        $reviewer_data = array(
            'crclm_id' => $crclm_id,
            'term_id' => $crclm_term_id,
            'dept_id' => $review_dept,
            'last_date' => $last_date,
            'modified_by' => $this->ion_auth->user()->row()->id,
            'modified_date' => date('Y-m-d')
        );
        $this->db->where('crs_id', $crs_id)
                ->update('course_clo_validator', $reviewer_data);

        //insert predecessor course array into predecessor courses table
        $pre_crs_array = '';
        $pre_crs_array = explode('<>', $pre_courses);
        $lmax = sizeof($pre_crs_array);
        for ($l = 0; $l < $lmax; $l++) {
            $predecessor_data = array(
                'crs_id' => $crs_id,
                'predecessor_course' => $pre_crs_array[$l],
                'created_by' => $this->ion_auth->user()->row()->id,
                'create_date' => date('Y-m-d')
            );
            if ($pre_crs_array[$l] != '') {
                $this->db->insert('predecessor_courses', $predecessor_data);
            }
        }
        // delete predecessor courses array 
        $kmax = sizeof($del_pre_courses);
        for ($k = 0; $k < $kmax; $k++) {
            if ($del_pre_courses[$k] != '') {
                $this->db->where('predecessor_id', $del_pre_courses[$k])
                        ->delete('predecessor_courses');
            }
        }
        return TRUE;
    }

// End of function update_course.

    /* Function is used to check whether bloom's domain is using in the co or tlo.
     * @param - course id & blooms domain id.
     * @returns- a boolean value
     */

    public function check_disable_bloom_domain($bld_id, $crs_id) {
        $check_bld_query = 'select * from clo AS clo inner join map_clo_bloom_level AS map on clo.clo_id=map.clo_id WHERE map.bld_id="' . $bld_id . '" AND clo.crs_id="' . $crs_id . '"';
        $clo = $this->db->query($check_bld_query);
        $clo = $clo->num_rows();
        $check_bld_query = 'select * from tlo AS tlo inner join map_tlo_bloom_level AS map on tlo.tlo_id=map.tlo_id WHERE map.bld_id="' . $bld_id . '" AND tlo.course_id="' . $crs_id . '"';
        $tlo = $this->db->query($check_bld_query);
        $tlo = $tlo->num_rows();
		
		$qp_exist  = $this->db->query('select qmap.actual_mapped_id from qp_definition as q
									   join qp_unit_definition qm ON q.qpd_id = qm.qpd_id
									   join qp_mainquestion_definition qmd ON qm.qpd_unitd_id = qmd.qp_unitd_id
									   join qp_mapping_definition qmap ON qmd.qp_mq_id = qmap.qp_mq_id
									   where q.crs_id = "'.$crs_id.'" and qmap.entity_id = 23
									   group by qmap.actual_mapped_id;');
		$result = $qp_exist->result_array();

		if(!empty($result)){
		$i= 0;
		for($i=0;$i<count($result); $i++){				
			$data [] =  $result[$i]['actual_mapped_id'];
		}
		$bloom_id = implode (",", $data);
		$bld_id_single = str_replace("'", "", $bloom_id);
		
		$bloom_bld_id = $this->db->query(' SELECT bld_id from bloom_level where bloom_id IN ('.$bld_id_single.') group by bld_id;');
		$re = $bloom_bld_id->result_array();
		
		for($i=0;$i<count($re); $i++){				
			$bld_id_data [] =  $re[$i]['bld_id'];
		}

		$key = array_search($bld_id, $bld_id_data);
		
		if (in_array($bld_id, $bld_id_data)) {echo $qp = 1; } else{ echo $qp = 0;}
		} else{ $qp = 0;}

        if ($clo || $tlo || $qp ) {
            return 1;
        } else {
            return 0;
        }
    }
    
    /*
 * Function to asign the course instructor.
 */
public function assign_course_instructor($crs_id,$trm_id,$crclm_id){
    // course instructors to display
    $select_course_instructor = 'SELECT ci.mcci_id, ci.crclm_id, ci.crclm_term_id, ci.crs_id, ci.course_instructor_id, ci.section_id, CONCAT(usr.title, usr.first_name," ", usr.last_name) as user_name, sec.mt_details_name as section   FROM map_courseto_course_instructor as ci'
            . ' JOIN users as usr ON id = ci.course_instructor_id'
            . ' JOIN master_type_details as sec ON sec.mt_details_id = ci.section_id and master_type_id = 34'
            . ' WHERE ci.crs_id="'.$crs_id.'" and ci.crclm_id="'.$crclm_id.'" and ci.crclm_term_id="'.$trm_id.'" ORDER BY sec.mt_details_id';
    $instructor_data = $this->db->query($select_course_instructor);
    $instructor_details = $instructor_data->result_array();
    
    // fetch course owners for the curriculum
        $active = 1;
        $course_instructor_list_query = 'SELECT * FROM
                                (SELECT u.id, u.title, u.first_name,u.last_name,u.email
                                FROM users as u, users_groups as g, program as p, curriculum as c
                                WHERE u.id=g.user_id AND u.active = 1 AND u.user_dept_id = p.dept_id AND p.pgm_id = c.pgm_id AND g.group_id = 6 AND c.crclm_id = "' . $crclm_id . '"

                                UNION

                                SELECT u.id,u.title,u.first_name,u.last_name,u.email
                                FROM map_user_dept m,map_user_dept_role mdr,groups g,users u, program as p, curriculum as c,users_groups as gr
                                WHERE m.assigned_dept_id = p.dept_id AND p.pgm_id = c.pgm_id AND gr.group_id = 6 AND c.crclm_id = "' . $crclm_id . '"
                                AND m.user_id = mdr.user_id
                                AND mdr.role_id = g.id
                                AND m.user_id = u.id
                                AND g.id = 6) A ORDER BY A.first_name ASC ';
        $result = $this->db->query($course_instructor_list_query);
        $user_list = $result->result_array();
        
        $already_assigned_section_list_query = 'SELECT section_id FROM map_courseto_course_instructor WHERE crs_id = "'.$crs_id.'" ';
        $section_list_data = $this->db->query($already_assigned_section_list_query);
        $assigned_sections = $section_list_data->result_array();
        $sections_list = flattenArray($assigned_sections);
//        $section_list_array = implode(',', $sections_list);
        
        // fetch section list from master type details table
//        $section_query = 'SELECT * FROM master_type_details WHERE NOT IN('.$section_list_array.') master_type_id = 34';
//        $section_result = $this->db->query($section_query);
//        $section_list = $section_result->result_array();
        
        $section_query = $this->db->SELECT('*')
                                    ->FROM('master_type_details')
                                    ->WHERE_NOT_IN('mt_details_id',$sections_list)
                                    ->WHERE('master_type_id',34)
                                    ->get()->result_array();
        
        $data['course_instructor_display'] = $instructor_details;
        $data['course_instructor_list'] = $user_list;
        $data['section_list'] = $section_query;
    return $data;
    
    
}

/*
 * Function to add new course instructor to the system
 */
public function add_course_instructor($section_id,$instructor_id,$ci_crclm_id,$ci_term_id,$ci_crs_id){
    // check course instructor is assigned for the section or not.
    $check_query = 'SELECT COUNT(mcci_id) as counter FROM map_courseto_course_instructor WHERE crclm_id = "'.$ci_crclm_id.'" AND crclm_term_id = "'.$ci_term_id.'" AND crs_id = "'.$ci_crs_id.'" AND section_id = "'.$section_id.'" ';
    $count_data = $this->db->query($check_query);
    $count =  $count_data->row_array();
    if($count['counter'] >= 1){
        return '-1';
    }else{
       $insert_record = array(
           'crclm_id' => $ci_crclm_id,
           'crclm_term_id' => $ci_term_id,
           'crs_id' => $ci_crs_id,
           'course_instructor_id' => $instructor_id,
           'section_id' => $section_id,
           'created_by' => $this->ion_auth->user()->row()->id,
           'modified_by' => $this->ion_auth->user()->row()->id,
           'created_date' => date('y-m-d'),
           'modified_date' => date('y-m-d'),   ); 
           
       $this->db->insert('map_courseto_course_instructor',$insert_record);
       
       $meta_data_query = ' SELECT crs.crs_title, crclm.crclm_name, term.term_name, mt.mt_details_name as section_name FROM course as crs '
               . ' JOIN curriculum as crclm ON crclm.crclm_id = "'.$ci_crclm_id.'" '
               . ' JOIN crclm_terms as term ON term.crclm_term_id = "'.$ci_term_id.'" '
               . ' JOIN master_type_details as mt ON mt.mt_details_id = "'.$section_id.'" '
               . ' WHERE crs.crs_id = "'.$ci_crs_id.'" ';
       $meta_data_data = $this->db->query($meta_data_query);
       $meta_data = $meta_data_data->row_array();
       
       $description = 'Term(Semester):- ' . $meta_data['term_name'];
       $reviewer_description = $description . ', Course:- ' . $meta_data['crs_title'] . ' is created, you have been chosen as a Course Instructor for Section/Devision :- '.$meta_data['section_name'].'.';
       
       $dashboard_insert = array(
            'crclm_id' =>$ci_crclm_id,
            'entity_id' => 4 ,
            'particular_id' =>$ci_crs_id,
            'sender_id' =>$this->ion_auth->user()->row()->id,
            'receiver_id' =>$instructor_id,
            'url' =>'#',
            'description' => $reviewer_description ,
            'state' => 1,
            'status' => 1 ,
       );
       
       $this->db->insert('dashboard',$dashboard_insert);
       
       $already_assigned_section_list_query = 'SELECT section_id FROM map_courseto_course_instructor WHERE crs_id = "'.$ci_crs_id.'" ';
        $section_list_data = $this->db->query($already_assigned_section_list_query);
        $assigned_sections = $section_list_data->result_array();
        
        $sections_list = flattenArray($assigned_sections);
//        $section_list_array = implode(',', $sections_list);
        
        // fetch section list from master type details table
//        $section_query = 'SELECT * FROM master_type_details WHERE NOT IN('.$section_list_array.') master_type_id = 34';
//        $section_result = $this->db->query($section_query);
//        $section_list = $section_result->result_array();
        
        $section_query = $this->db->SELECT('*')
                                    ->FROM('master_type_details')
                                    ->WHERE_NOT_IN('mt_details_id',$sections_list)
                                    ->WHERE('master_type_id',34)
                                    ->get()->result_array();
       
       return $section_query;
    }
    
}

/*
 * Function to load table.
 */
public function generate_table($ci_crs_id,$ci_crclm_id,$ci_term_id){
    
     // course instructors to display
    $select_course_instructor = 'SELECT ci.mcci_id, ci.crclm_id, ci.crclm_term_id, ci.crs_id, ci.course_instructor_id, ci.section_id, CONCAT(usr.title, usr.first_name," ", usr.last_name) as user_name, sec.mt_details_name as section   FROM map_courseto_course_instructor as ci'
            . ' JOIN users as usr ON id = ci.course_instructor_id'
            . ' JOIN master_type_details as sec ON sec.mt_details_id = ci.section_id and master_type_id = 34'
            . ' WHERE ci.crs_id="'.$ci_crs_id.'" and ci.crclm_id="'.$ci_crclm_id.'" and ci.crclm_term_id="'.$ci_term_id.'" ORDER BY sec.mt_details_id';
    $instructor_data = $this->db->query($select_course_instructor);
    $instructor_details = $instructor_data->result_array();
        $active = 1;
        $course_instructor_list_query = 'SELECT * FROM
                                (SELECT u.id, u.title, u.first_name,u.last_name,u.email
                                FROM users as u, users_groups as g, program as p, curriculum as c
                                WHERE u.id=g.user_id AND u.active = 1 AND u.user_dept_id = p.dept_id AND p.pgm_id = c.pgm_id AND g.group_id = 6 AND c.crclm_id = "' . $ci_crclm_id . '"

                                UNION

                                SELECT u.id,u.title,u.first_name,u.last_name,u.email
                                FROM map_user_dept m,map_user_dept_role mdr,groups g,users u, program as p, curriculum as c,users_groups as gr
                                WHERE m.assigned_dept_id = p.dept_id AND p.pgm_id = c.pgm_id AND gr.group_id = 6 AND c.crclm_id = "' . $ci_crclm_id . '"
                                AND m.user_id = mdr.user_id
                                AND mdr.role_id = g.id
                                AND m.user_id = u.id
                                AND g.id = 6) A ORDER BY A.first_name ASC ';
        $result = $this->db->query($course_instructor_list_query);
        $user_list = $result->result_array();
       
        $instructor_result['instructor_data'] = $instructor_details;
        $instructor_result['ins_list'] = $user_list;
    return $instructor_result;
    
}

/*
 * Function to Edit Save of instructor.
 */

public function edit_save_instructor($instructor_id,$mcci_id){
    $update_query = 'UPDATE map_courseto_course_instructor SET course_instructor_id = "'.$instructor_id.'" WHERE mcci_id = "'.$mcci_id.'" ';
    $result = $this->db->query($update_query);
    return $result;
}

/*
 * Function to delete course instructor
 */
public function delete_instructor($mcci_id,$crclm_id,$term_id,$course_id,$sec_id){
    
    
        $data_check = 'SELECT qpd_id FROM assessment_occasions WHERE crs_id = "'.$course_id.'" AND section_id = "'.$sec_id.'" ';
    $data_data  =   $this->db->query($data_check);
    $data_res  =  $data_data->result_array();
    
    $occ_check =  'SELECT  ao_id  FROM assessment_occasions  WHERE crs_id = "'.$course_id.'" AND section_id = "'.$sec_id.'" ';
    $occ_data = $this->db->query($occ_check);
    $occ_res   =  $occ_data->result_array();
   
    if(!empty($occ_res)){
        foreach($occ_res   as $res){
            $delete_occ = 'DELETE  FROM assessment_occasions  WHERE  ao_id = "'.$res['ao_id'].'"';
            $delete_suc =   $this->db->query($delete_occ);
        }
    }
    
    if(!empty($data_res)){
        foreach($data_res as $qp){
            $delete_qp = 'DELETE FROM  qp_definition where qpd_id = "'.$qp['qpd_id'].'" ';
            $delete_qp_suc = $this->db->query($delete_qp);
        }
    }
    
    $delete_query = 'DELETE FROM map_courseto_course_instructor WHERE mcci_id = "'.$mcci_id.'" ';
    $delete = $this->db->query($delete_query);
    
        $already_assigned_section_list_query = 'SELECT section_id FROM map_courseto_course_instructor WHERE crs_id = "'.$course_id.'" ';
        $section_list_data = $this->db->query($already_assigned_section_list_query);
        $assigned_sections = $section_list_data->result_array();
        $sections_list = flattenArray($assigned_sections);
//        $section_list_array = implode(',', $sections_list);
        
        // fetch section list from master type details table
//        $section_query = 'SELECT * FROM master_type_details WHERE NOT IN('.$section_list_array.') master_type_id = 34';
//        $section_result = $this->db->query($section_query);
//        $section_list = $section_result->result_array();
        
        $section_query = $this->db->SELECT('*')
                                    ->FROM('master_type_details')
                                    ->WHERE_NOT_IN('mt_details_id',$sections_list)
                                    ->WHERE('master_type_id',34)
                                    ->get()->result_array();
    
    return $section_query;
   
  
}

public function section_co_finalize($course_id,$sec_id){
    
    
    $check_data_fialize='SELECT cia_finalise_flag FROM map_courseto_course_instructor WHERE crs_id = "'.$course_id.'" AND section_id ="'.$sec_id.'" ';
    $check_finalize = $this->db->query($check_data_fialize);
    $check_finalize_res = $check_finalize->row_array();
   
    if($check_finalize_res['cia_finalise_flag']==0){
        $final_data = 'true';
    }else{
        $final_data = 'false';
    }
    return $final_data;
}

public function get_section_name($section_id){
    $section_name_query = 'SELECT mt_details_name FROM master_type_details WHERE mt_details_id = "'.$section_id.'" ';
    $section_name_data = $this->db->query($section_name_query);
    $section_name_res = $section_name_data->row_array();
    $section_name = $section_name_res['mt_details_name'];
    return $section_name;
}

	public function bloom_option_mandatory($crs_id , $clo_bl_flag_data){
		if($clo_bl_flag_data == 0){ $clo_bl_flag = 1;}else{ $clo_bl_flag = 0; }
		$query = $this->db->query('update course set clo_bl_flag = "'. $clo_bl_flag .'" where crs_id = "'. $crs_id .'" ');
	
	}
	
	public function check_mandatory_data_set_or_not($crs_id){
	
		$query = $this->db->query("SELECT case when (cognitive_domain_flag || affective_domain_flag || psychomotor_domain_flag ) = 1 then  1 else 0
								   end as flag
								   from course where crs_id= '". $crs_id ."'");
		return $query->result_array();
	}


}



// End of Class Course_model.


?>