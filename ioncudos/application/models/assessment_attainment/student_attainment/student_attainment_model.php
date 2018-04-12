<?php 
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Model Logic for Student Attainment.	  
 * Modification History:
 * Date				Author				Description
 * 25-02-2015		Jevi V G     	         
  ---------------------------------------------------------------------------------------------------------------------------------
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Student_attainment_model extends CI_Model 
{
	/*
     * Function is to get the program titles.
     * @param - ------.
     * returns -------.
	*/
	public function dropdown_dept_title() {
		if($this->ion_auth->is_admin()) {
			return $this->db->select('dept_id, dept_name')
							->order_by('dept_name','asc') 
							->where('status',1)        		
							->get('department')->result_array();
		} else {
			$logged_in_user_id = $this->ion_auth->user()->row()->user_dept_id;
			return $this->db->select('dept_id, dept_name')
						    ->order_by('dept_name','asc')
						    ->where('dept_id',$logged_in_user_id)        		
						    ->where('status',1)        		
						    ->get('department')->result_array();
		}
	}
	
	
	/*
     * Function is to get the program titles.
     * @param - ------.
     * returns -------.
	*/
	public function dropdown_program_title($dept_id) 
	{
	 return $this->db->select('pgm_id, pgm_acronym')
					 ->order_by('pgm_acronym','asc')
					 ->where('dept_id',$dept_id)        		
					// ->where('status',1)        		
					 ->get('program')
					 ->result_array();

	}
	
	 
	 /*
        * Function is to fetch the curriculum details.
        * @param - -----.
        * returns list of curriculum names.
	*/   
     public function crlcm_drop_down_fill() {
		$loggedin_user_id = $this->ion_auth->user()->row()->id;
		if($this->ion_auth->is_admin()) {
			$curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name 
								FROM curriculum AS c
								WHERE c.status = 1
								ORDER BY c.crclm_name ASC';
		} elseif($this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')){
			$curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name 
							FROM curriculum AS c, users AS u, program AS p
							WHERE u.id = "'.$loggedin_user_id.'" 
							AND u.user_dept_id = p.dept_id 
							AND c.pgm_id = p.pgm_id 
							AND c.status = 1
							ORDER BY c.crclm_name ASC';
		}else{
		$curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name
				FROM curriculum AS c, users AS u, program AS p , course_clo_owner AS clo
				WHERE u.id = "' . $loggedin_user_id . '" 
				AND u.user_dept_id = p.dept_id
				AND c.pgm_id = p.pgm_id
				AND u.id = clo.clo_owner_id
				AND c.crclm_id = clo.crclm_id
				AND c.status = 1				
				ORDER BY c.crclm_name ASC';
		}
		
		
		$resx = $this->db->query($curriculum_list);
		$crclm_data = $resx->result_array();
		
       return $crclm_data;
    } 
	
	 /*
        * Function is to fetch the term details.
        * @param - -----.
        * returns list of term names.
	*/   
     public function term_drop_down_fill($curriculum_id) {
	$loggedin_user_id = $this->ion_auth->user()->row()->id;
	if($this->ion_auth->in_group('Course Owner') && !$this->ion_auth->in_group('admin') && !$this->ion_auth->in_group('Chairman') && !$this->ion_auth->in_group('Program Owner')){
       $term_list_query = 'select DISTINCT ct.crclm_id, ct.term_name, ct.crclm_term_id ,c.clo_owner_id from course_clo_owner AS c, crclm_terms AS ct
					  where c.crclm_term_id = ct.crclm_term_id
					  AND c.clo_owner_id="'.$loggedin_user_id.'"
					  AND c.crclm_id = "'.$curriculum_id.'"';
	}else{
		 $term_list_query = 'SELECT term_name, crclm_term_id 
					  FROM crclm_terms 
					  WHERE crclm_id = "' . $curriculum_id . '"';
				
	}
				
        $result = $this->db->query($term_list_query);
        return $result->result_array();					 
    } 
	
	/*
        * Function is to fetch the term details.
        * @param - -----.
        * returns list of term names.
	*/   
    public function course_drop_down_fill($term) {
		// return $this->db->select('crs_id, crs_title')
					 // ->where('crclm_term_id',$term)
					 // ->order_by('crs_title','ASC')
					 // ->get('course')
					 // ->result_array();
		$fetch_course_query = $this->db->query('SELECT DISTINCT c.crs_id, c.crs_title
												FROM course c,qp_definition qpd
												WHERE qpd.qpd_type IN (3,5)
												AND c.crs_id = qpd.crs_id
												AND qpd.qp_rollout > 1
												AND c.crclm_term_id = '.$term.'
												ORDER BY c.crs_title ASC ');
		return  $fetch_course_query->result_array();
    }
	
	public function qp_rolled_out($crs){
	
	return $this->db->select('qpd_id')
					->where('crs_id',$crs)
					->where('qp_rollout = 2')
					->where('qpd_type = 5')
					->get('qp_definition')
					->result_array();
			
	}
	
	public function course_po_list($crs){
	
	return $this->db->distinct()
					->select('clo_po_map.po_id, po.po_reference')
					->join('po','po.po_id = clo_po_map.po_id')
					->where('crs_id',$crs)
					 ->order_by('po.po_reference','ASC')
					->get('clo_po_map')
					->result_array();
	}
	
	public function getCourseCOAttainment($crs, $qp_rolled_out,$student_usn,$qpd_type) {
/* 	$query = $this->db->query('  * , (q.mapped_marks) as cloMaxMarks , (AVG((secured_marks * q.mapped_percentage)/100)) AS cloSecuredMarks,
								ROUND((secured_marks *100)/q.mapped_marks,2) AS Attainment
							   join assessment_occasions as a on a.ao_id = t.ao_id
							   join clo as c on c.clo_id = t.clo_id 
							   LEFT JOIN qp_mapping_definition q on c.clo_id=q.actual_mapped_id
							   LEFT JOIN qp_mainquestion_definition qpm on q.qp_mq_id =qpm.qp_mq_id
							   LEFT JOIN qp_unit_definition qpu  on qpm.qp_unitd_id=qpu.qpd_unitd_id
							   LEFT JOIN qp_definition qd on qpu.qpd_id=qd.qpd_id
							   LEFT JOIN student_assessment as s on  s.qp_mq_id=qpm.qp_mq_id
							   where t.crs_id = '. $crs .'  and t.student_usn = "'. $student_usn .'"							   
							   AND a.qpd_id = '. $qp_rolled_out  .'
								group by c.clo_id	
							  ');
	 
		return  $query->result_array(); */
	 $qpd_type = strtoupper($qpd_type);
		$r = $this->db->query("call getCourseCOThreshholdAttainment(".$crs.", ".$qp_rolled_out.",'".$student_usn."','".$qpd_type."')");
		
			return $r->result_array(); 
	}
	
	public function CourseCOAttainment_Contribution($crs, $qp_rolled_out,$student_usn,$qpd_type) {
		$qpd_type = strtoupper($qpd_type);
		$r = $this->db->query("call getCourseCOAttainment(".$crs.", ".$qp_rolled_out.",'".$student_usn."','".$qpd_type."')");
		
			return $r->result_array();
	}
	
	public function CourseBloomsLevelAttainment($crs, $qp_rolled_out,$student_usn,$qpd_type) {
		$qpd_type = strtoupper($qpd_type);
		$r = $this->db->query("call getCourseBLThreshholdAttainment(".$crs.", ".$qp_rolled_out.", '".$student_usn."','".$qpd_type."')");
		
			return $r->result_array();
	}
	
	public function CourseBloomsLevelCumulativeData($crs, $qp_rolled_out,$student_usn,$qpd_type) {
		$qpd_type = strtoupper($qpd_type);
		$r = $this->db->query("call getCourseBloomsLevelAttainment(".$crs.", ".$qp_rolled_out.", '".$student_usn."','".$qpd_type."')");
		
			return $r->result_array();
	}
	
	public function BloomsLevelMarksDistribution($crs,$student_usn,$qpd_type) {
		$qpd_type = strtoupper($qpd_type);
		$r = $this->db->query("call getBloomsLevelMarksDistribution(".$crs.", ".$qp_rolled_out.",".$student_usn.",'".$qpd_type."')");
		
			return $r->result_array();
	}
	
	public function BloomsLevelPlannedCoverageDistribution($crs,$student_usn,$qpd_type) {
		$qpd_type = strtoupper($qpd_type);
		$r = $this->db->query("call getBloomsLevelPlannedCoverageDistribution(".$crs.", ".$qp_rolled_out.",".$student_usn.",'".$qpd_type."')");
		
			return $r->result_array();
	}
	
	public function CoursePOCOAttainment($po_list, $crs, $qpd_id,$student_usn,$qpd_type) {
		$qpd_type = strtoupper($qpd_type);
		if($qpd_id == 'NULL') {
			$param = $crs.", NULL, NULL,'".$qpd_type;
		} else if($qpd_id > 0) {
			$param = $crs.", ".$qpd_id.", '".$student_usn."','".$qpd_type;
		}
		$i=0;
		foreach($po_list as $po_id){
		//echo $po_id['po_id']."<br>";
		$this->load->library('database_result');
		//print_r($po_id['po_id']."<br>");
			$result = $this->db->query("call getCoursePOCOAttainment(".$po_id['po_id'].", ".$param."')");
			$graph_data = $result->result_array();
			$this->database_result->next_result();
			
			$data['po_reference'][] = $po_id['po_reference']; 
			$data['graph_data'][] = $graph_data;
			//$data[$i++] = $po_id['po_reference'];
	}
	
	return $data;
	
	}
	
	/*
        * Function is to fetch the occasion details.
        * @param - -----.
        * returns list of occasion names.
	*/   
    public function cia_usn_fill($qpd_id,$crs_id) {
		if($qpd_id == 'all_occasion') {
			$fetch_course_query = $this->db->query('SELECT  DISTINCT student_usn,student_name  FROM student_assessment s
														WHERE qp_mq_id IN (SELECT qp_mq_id FROM qp_mainquestion_definition
														WHERE qp_unitd_id IN(SELECT qpd_unitd_id FROM qp_unit_definition
														WHERE qpd_id IN(SELECT qpd_id FROM qp_definition WHERE qpd_type = 3 AND crs_id = "'.$crs_id.'") ) )');
			return  $fetch_course_query->result_array();
		} else {
			$fetch_course_query = $this->db->query('SELECT  distinct student_usn,student_name  FROM student_assessment s
													where qp_mq_id in (select qp_mq_id from qp_mainquestion_definition
													where qp_unitd_id in(select qpd_unitd_id from qp_unit_definition where qpd_id = "'.$qpd_id.'" ) )');
			return  $fetch_course_query->result_array();
		}
    }
	
	/*
        * Function is to fetch the student usn details.
        * @param - -----.
        * returns list of student usn details.
	*/   
    public function usn_fill($course_id,$type) {
		if ($type == 'tee') {
			$qpd_id_result = $this->db->select('qpd_id')
							->where('crs_id',$course_id)
							->where('qp_rollout = 2')
							->where('qpd_type = 5')
							->get('qp_definition')
							->result_array();
			
			if(!empty($qpd_id_result)){
			$qpd_id = $qpd_id_result[0]['qpd_id'];
			$fetch_course_query = $this->db->query('SELECT  distinct student_usn,student_name  FROM student_assessment s
													where qp_mq_id in (select qp_mq_id from qp_mainquestion_definition
													where qp_unitd_id in(select qpd_unitd_id from qp_unit_definition where qpd_id='.$qpd_id.') )');
			$data['student_usn'] = $fetch_course_query->result_array();
			$data['qpd_id'] = $qpd_id;
			}else{
				$data['student_usn'] = '';
				$data['qpd_id'] = '';
			}
			return $data;
		} else {
			$fetch_course_query = $this->db->query('SELECT  DISTINCT student_usn,student_name  FROM student_assessment s
														WHERE qp_mq_id IN (SELECT qp_mq_id FROM qp_mainquestion_definition
															WHERE qp_unitd_id IN(SELECT qpd_unitd_id FROM qp_unit_definition
																WHERE qpd_id IN (SELECT qpd_id FROM qp_definition
																	WHERE qpd_type IN (3,5) AND qp_rollout = 2 AND crs_id = '.$course_id.')))');
			$data['student_usn'] = $fetch_course_query->result_array();
			$data['qpd_id'] = 'BOTH';
			return $data;
		}
    }
	
	public function StudentAttainmentAnalysis($qpd_id, $usn) {
		$r = $this->db->query("call getStudentAttainmentAnalysis('".$qpd_id."', '".$usn."')");

		return $r->result_array();
	}
	
		public function dept_name_by_crclm_id($crclm_id) {
        $dept_name_qry = 'SELECT dept_name 
						  FROM department 
						  WHERE dept_id = (SELECT dept_id 
										   FROM program 
										   WHERE pgm_id = (SELECT pgm_id 
														   FROM curriculum 
														   WHERE crclm_id= "' . $crclm_id . '"))';
        $dept_name_object = $this->db->query($dept_name_qry);
        $dept_name_array = $dept_name_object->result_array();

        return $dept_name_array[0]['dept_name'];
    }
		public function fetch_type_data($crclm_id, $term_id, $course_id){
	
		$query  = $this->db->query('select cia_flag , mte_flag , tee_flag from course where crs_id = "'. $course_id .'" and crclm_id = "'. $crclm_id .'" and crclm_term_id = "'. $term_id.'" ');
		$type_data =  $query->result_array();
		$types = array();
		$key = array();
		$t ='';
			$types [''] = 'Select Type';
		foreach($type_data as $type ){
		
			if($type['cia_flag'] == 1){$types ['3'] = 'CIA';}
			if($type['mte_flag'] == 1){$types ['6'] = 'MTE';}
			if($type['tee_flag'] == 1){$types ['5'] = 'TEE';}			
		
		}	
		$types ['ALL'] = 'ALL';
			return $types;
			
	}
}
