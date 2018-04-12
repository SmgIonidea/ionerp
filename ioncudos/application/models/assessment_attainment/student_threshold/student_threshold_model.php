<?php
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description				: Student Threshold 
 * Modification History		:
 * Date				Author				Description
 * 04-08-2015	 Arihant Prasad		Set student threshold, list student USN
									display improvement plan table, facility to
									insert improvement plan details, view improvement plan
  ---------------------------------------------------------------------------------------------------------------------------------
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Student_threshold_model extends CI_Model 
{
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
		}  elseif($this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')){
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
     public function term_drop_down_fill($crclm_id) {
       return $this->db->select('crclm_term_id, term_name')
					 ->where('crclm_id',$crclm_id)        		
					 ->get('crclm_terms')
					 ->result_array();
    } 
	
	/*
        * Function is to fetch the course details.
        * @param - -----.
        * returns list of course names.
	*/   
    public function course_drop_down_fill($term) {
		$fetch_course_query = $this->db->query('SELECT DISTINCT c.crs_id, c.crs_title
												FROM course c,qp_definition qpd
												WHERE qpd.qpd_type IN (3,5)
													AND c.crs_id = qpd.crs_id
													AND qpd.qp_rollout > 1
													AND c.crclm_term_id = '.$term.'
												ORDER BY c.crs_title ASC ');
		return  $fetch_course_query->result_array();
    }
	
	/*
        * Function is to fetch the occasion details.
        * @param :
        * returns : list of occasion names.
	*/   
    public function occasion_fill($crclm_id, $term_id, $crs_id) {
		$fetch_course_query = $this->db->query('SELECT a.qpd_id,a.ao_description
												FROM assessment_occasions a,qp_definition q , master_type_details  m
												WHERE a.crclm_id = '.$crclm_id.'
												AND a.term_id = '.$term_id.'
												AND a.crs_id = '.$crs_id.'											
												AND q.qpd_id = a.qpd_id
												AND q.qp_rollout > 1
												AND q.qpd_type = 3
                                                AND cia_model_qp = 0
												AND a.section_id  = m.mt_details_id
												AND a.qpd_id IS NOT NULL');
		return  $fetch_course_query->result_array();
    }
	
	/*
        * Function is to fetch the CLO details.
        * @param - -----.
        * returns list of CLO details.
	*/   
    public function fetch_course_clo($crs_id, $qpd_id, $type_data_id) {
		if ($type_data_id == 5) {
			//TEE
			$fetch_clo_query = $this->db->query('SELECT DISTINCT qpmap.actual_mapped_id, clo.clo_id, clo.clo_code, 
													clo.clo_statement, c.crs_id, c.crs_title, c.cia_course_minthreshhold, c.course_studentthreshhold, 
													qpu.qpd_unitd_id, qpmap.qp_mq_id
												FROM course c
												JOIN qp_unit_definition AS qpu ON qpu.qpd_id = "'.$qpd_id.'"
												JOIN qp_mainquestion_definition AS qpm ON qpm.qp_unitd_id = qpu.qpd_unitd_id
												JOIN qp_mapping_definition AS qpmap ON qpmap.qp_mq_id = qpm.qp_mq_id 
														AND qpmap.entity_id = 11
												JOIN clo AS clo ON clo.clo_id = qpmap.actual_mapped_id
												WHERE c.crs_id = clo.crs_id
												AND clo.crs_id = "'.$crs_id.'"
												GROUP BY qpmap.actual_mapped_id
												ORDER BY clo.clo_code ASC');
			$fetch_clo_result = $fetch_clo_query->result_array();							 
			return  $fetch_clo_result;
		} elseif($type_data_id == 3) {
			if($qpd_id == 'all_occasion'){
				//All CIA
				$fetch_clo_query = $this->db->query('
SELECT DISTINCT qpmap.actual_mapped_id, clo.clo_id, clo.clo_code,
													clo.clo_statement, c.crs_id, c.crs_title, c.cia_course_minthreshhold, c.course_studentthreshhold,
													qpu.qpd_unitd_id, qpmap.qp_mq_id
												FROM course c
												JOIN qp_unit_definition AS qpu ON qpu.qpd_id IN (SELECT DISTINCT q.qpd_id
																									FROM qp_definition as q
																									join assessment_occasions as a on q.qpd_id = a.qpd_id
																									WHERE q.crs_id = "'. $crs_id .'"
																									AND qpd_type IN (3)
																									AND qp_rollout > 1 and a.mte_flag = 0 )
												JOIN qp_mainquestion_definition AS qpm ON qpm.qp_unitd_id = qpu.qpd_unitd_id
												JOIN qp_mapping_definition AS qpmap ON qpmap.qp_mq_id = qpm.qp_mq_id 
														AND qpmap.entity_id = 11
												JOIN clo AS clo ON clo.clo_id = qpmap.actual_mapped_id
												WHERE c.crs_id = clo.crs_id
												AND clo.crs_id = "'. $crs_id .'"
												GROUP BY qpmap.actual_mapped_id
												ORDER BY clo.clo_code ASC');
				$fetch_clo_result = $fetch_clo_query->result_array();							 
				return  $fetch_clo_result;
			} else {
				//CIA Minor 1, 2...
				$fetch_clo_query = $this->db->query('SELECT DISTINCT qpmap.actual_mapped_id, clo.clo_id, clo.clo_code, 
													clo.clo_statement, c.crs_id, c.crs_title, c.cia_course_minthreshhold, c.course_studentthreshhold, 
													qpu.qpd_unitd_id, qpmap.qp_mq_id
												FROM course c
												JOIN qp_unit_definition AS qpu ON qpu.qpd_id = "'.$qpd_id.'"
												JOIN qp_mainquestion_definition AS qpm ON qpm.qp_unitd_id = qpu.qpd_unitd_id
												JOIN qp_mapping_definition AS qpmap ON qpmap.qp_mq_id = qpm.qp_mq_id 
														AND qpmap.entity_id = 11
												JOIN clo AS clo ON clo.clo_id = qpmap.actual_mapped_id
												WHERE c.crs_id = clo.crs_id
												AND clo.crs_id = "'.$crs_id.'"
												GROUP BY qpmap.actual_mapped_id
												ORDER BY clo.clo_code ASC');
				$fetch_clo_result = $fetch_clo_query->result_array();
				
				return  $fetch_clo_result;
			}
		}elseif($type_data_id == 6) {
			if($qpd_id == 'all_occasion'){
				//All CIA
				$fetch_clo_query = $this->db->query('
SELECT DISTINCT qpmap.actual_mapped_id, clo.clo_id, clo.clo_code,
													clo.clo_statement, c.crs_id, c.crs_title, c.cia_course_minthreshhold, c.course_studentthreshhold,
													qpu.qpd_unitd_id, qpmap.qp_mq_id
												FROM course c
												JOIN qp_unit_definition AS qpu ON qpu.qpd_id IN (SELECT DISTINCT q.qpd_id
																									FROM qp_definition as q
																									join assessment_occasions as a on q.qpd_id = a.qpd_id
																									WHERE q.crs_id = "'. $crs_id .'"
																									AND qpd_type IN (3)
																									AND qp_rollout > 1 and a.mte_flag = 1 )
												JOIN qp_mainquestion_definition AS qpm ON qpm.qp_unitd_id = qpu.qpd_unitd_id
												JOIN qp_mapping_definition AS qpmap ON qpmap.qp_mq_id = qpm.qp_mq_id 
														AND qpmap.entity_id = 11
												JOIN clo AS clo ON clo.clo_id = qpmap.actual_mapped_id
												WHERE c.crs_id = clo.crs_id
												AND clo.crs_id = "'. $crs_id .'"
												GROUP BY qpmap.actual_mapped_id
												ORDER BY clo.clo_code ASC');
				$fetch_clo_result = $fetch_clo_query->result_array();							 
				return  $fetch_clo_result;
			} else {
				//CIA Minor 1, 2...
				$fetch_clo_query = $this->db->query('SELECT DISTINCT qpmap.actual_mapped_id, clo.clo_id, clo.clo_code, 
													clo.clo_statement, c.crs_id, c.crs_title, c.cia_course_minthreshhold, c.course_studentthreshhold, 
													qpu.qpd_unitd_id, qpmap.qp_mq_id
												FROM course c
												JOIN qp_unit_definition AS qpu ON qpu.qpd_id = "'.$qpd_id.'"
												JOIN qp_mainquestion_definition AS qpm ON qpm.qp_unitd_id = qpu.qpd_unitd_id
												JOIN qp_mapping_definition AS qpmap ON qpmap.qp_mq_id = qpm.qp_mq_id 
														AND qpmap.entity_id = 11
												JOIN clo AS clo ON clo.clo_id = qpmap.actual_mapped_id
												WHERE c.crs_id = clo.crs_id
												AND clo.crs_id = "'.$crs_id.'"
												GROUP BY qpmap.actual_mapped_id
												ORDER BY clo.clo_code ASC');
				$fetch_clo_result = $fetch_clo_query->result_array();
				
				return  $fetch_clo_result;
			}
		} else {
			//CIA and TEE (Both)
			$fetch_clo_query = $this->db->query('SELECT DISTINCT qpmap.actual_mapped_id, clo.clo_id, clo.clo_code, 
													clo.clo_statement, c.crs_id, c.crs_title, c.cia_course_minthreshhold, c.course_studentthreshhold, 
													qpu.qpd_unitd_id, qpmap.qp_mq_id
												FROM course c
												JOIN qp_unit_definition AS qpu ON qpu.qpd_id IN (SELECT DISTINCT qpd_id 
																									FROM qp_definition
																									WHERE crs_id = "'.$crs_id.'" 
																									AND qpd_type IN (3,5)
																									AND qp_rollout > 1 )
												JOIN qp_mainquestion_definition AS qpm ON qpm.qp_unitd_id = qpu.qpd_unitd_id
												JOIN qp_mapping_definition AS qpmap ON qpmap.qp_mq_id = qpm.qp_mq_id 
														AND qpmap.entity_id = 11
												JOIN clo AS clo ON clo.clo_id = qpmap.actual_mapped_id
												WHERE c.crs_id = clo.crs_id
												AND clo.crs_id = "'.$crs_id.'"
												GROUP BY qpmap.actual_mapped_id
												ORDER BY clo.clo_code ASC');
			$fetch_clo_result = $fetch_clo_query->result_array();							 
			return  $fetch_clo_result;
		}
    }
	
	/*
        * Function is to fetch the TEE details on select of Type
        * @param : course id, type id
        * returns : list of TEE details.
	*/   
    public function fetch_tee_qpd_id($course_id, $type) {
		if ($type == 'tee') {
			$qpd_id_result = $this->db->select('qpd_id')
							->where('crs_id',$course_id)
							->where('qp_rollout = 2')
							->where('qpd_type = 5')
							->get('qp_definition')
							->result_array();
			if(!empty($qpd_id_result)){
			$qpd_id = $qpd_id_result[0]['qpd_id'];}else{$qpd_id = '';}
			$data['qpd_id'] = $qpd_id;
			return $data;
		} else {
			return false;
		}
    }
	
	/*
     * Function is to fetch the TEE details.
     * @parameters - course id, qpd id, clo ids, threshold bit and custom threshold id
     * returns list of student above or below the threshold limit
	*/
	public function StudentResultAnalysis($crs_id, $qpd_id, $qpType_id, $clo_ids, $threshold_bit, $clo_thold) {
		$this->load->library('database_result');

		$data['student_details'] = $this->db->query("call getStudentCOAnalysis(".$crs_id.", ".$qpd_id.", '".$qpType_id."', '".$clo_ids."', ".$threshold_bit.", '".$clo_thold."')");
		$data_val = $data['student_details']->result_array();
		$data['count'] = (count($data_val));
		
		return $data;

	}
	
	/*
     * Function is to fetch improvement plan details & display in the table
     * @parameters - entity id, curriculum id, term id, course id, qpd id, clo ids, threshold bit and custom threshold id
     * returns : improvement plan details
	*/
	public function student_improvement_plan($entity_id, $crclm_id, $term_id, $crs_id , $mte_flag , $qpType_id , $qpd_id ) {
		if($qpType_id == 'CIA' || $qpType_id == 'MTE'){
			if($qpd_id != 'NULL'){$improvement_plan_query = 'SELECT sip.sip_id, sip.qpd_type,  sip.qpd_id  ,sip.ao_name, ac.ao_description, sip.qpd_id , ac.section_id 
								   FROM stud_improvement_action_item AS sia
								   JOIN stud_improvement_plan AS sip ON sip.entity_id = "'.$entity_id.'"
									  AND sip.crclm_id = "'.$crclm_id.'"
									  AND sip.crclm_term_id = "'.$term_id.'"
									  AND sip.crs_id = "'.$crs_id.'"
								   LEFT JOIN assessment_occasions AS ac ON sip.qpd_id = ac.qpd_id
								   WHERE sip.sip_id = sia.sip_id and ac.mte_flag = "'. $mte_flag .'"';
			}else{
				$improvement_plan_query = 'SELECT sip.sip_id, sip.qpd_type, sip.ao_name, ac.ao_description, sip.qpd_id , ac.section_id  , sip.qpd_id 
								   FROM stud_improvement_action_item AS sia
								   JOIN stud_improvement_plan AS sip ON sip.entity_id = "'.$entity_id.'"
									  AND sip.crclm_id = "'.$crclm_id.'"
									  AND sip.crclm_term_id = "'.$term_id.'"
									  AND sip.crs_id = "'.$crs_id.'"
								    LEFT JOIN assessment_occasions AS ac ON sip.crs_id = ac.crs_id
								   WHERE sip.sip_id = sia.sip_id and ac.mte_flag = "'. $mte_flag .'" and qpd_type =3
                   group by sip_id';
			}
		}else if($qpType_id == 'TEE'){
				$improvement_plan_query = 'SELECT sip.sip_id, sip.qpd_type,  "" as ao_description,sip.ao_name , sip.qpd_id 
								   FROM stud_improvement_action_item AS sia
								   JOIN stud_improvement_plan AS sip ON sip.entity_id = "'.$entity_id.'"
									  AND sip.crclm_id = "'.$crclm_id.'"
									  AND sip.crclm_term_id = "'.$term_id.'"
									  AND sip.crs_id = "'.$crs_id.'"																		 
								   WHERE sip.sip_id = sia.sip_id and qpd_type = 5 ';
		}else{
		$improvement_plan_query = 'SELECT sip.sip_id, sip.qpd_type,  ac.ao_description, sip.ao_name , sip.qpd_id 
								   FROM stud_improvement_action_item AS sia
								   JOIN stud_improvement_plan AS sip ON sip.entity_id = "'.$entity_id.'"
									  AND sip.crclm_id = "'.$crclm_id.'"
									  AND sip.crclm_term_id = "'.$term_id.'"
									  AND sip.crs_id = "'.$crs_id.'"
									  LEFT JOIN assessment_occasions AS ac ON sip.crs_id = ac.crs_id	
								      WHERE sip.sip_id = sia.sip_id  ';
		
		}
		$improvement_plan_details = $this->db->query($improvement_plan_query);
		$improvement_plan_data = $improvement_plan_details->result_array();
		
		return $improvement_plan_data;
	}
	
	/*
     * Function to delete improvement plan from the improvement table
     * @parameters - improvement plan id
     * returns : boolean
	*/
	public function improvement_plan_delete($imp_plan_sip_id) {
		$delete_query = 'DELETE FROM stud_improvement_plan 
						 WHERE sip_id ="'.$imp_plan_sip_id.'"';
		$delete_result = $this->db->query($delete_query);
		
		return true;
	}
	
	/*
     * Function to view improvement plan in the modal
     * @parameters : improvement plan id
     * returns : improvement plan details
	*/
	public function view_improvement_plan($imp_plan_sip_id) {
		$view_ip_query = 'SELECT sip.problem_statement,sip.student_usn, sip.root_cause, sip.corrective_action, sia.action_item
						  FROM stud_improvement_action_item AS sia, stud_improvement_plan AS sip
						  WHERE sip.sip_id = sia.sip_id
							AND sip.sip_id = "'.$imp_plan_sip_id.'"';
		$view_ip_details = $this->db->query($view_ip_query);
		$view_ip_data = $view_ip_details->result_array();
		
		return $view_ip_data;
	}
		/*
	*Function to insert uploded files
	*@parameter: 
	*@return: 
	*/
	public function save_uploaded_files($result){
		($this->db->insert('stud_improvement_plan_uploads', $result));
	}
	
		/*
	*Function to fetch research papers / Guid of user
	*@parameter: 
	*@return: 
	*/
	public function  fetch_records($sip_id){
		$query = $this->db->query('select * from stud_improvement_plan_uploads where sip_id="'.$sip_id.'"');
		return $query->result_array();
	
	}
	
	public function delete_file($uload_id){
		return $this->db->query('delete from stud_improvement_plan_uploads where uload_id="'.$uload_id.'"');
	}
	
		/*
	*Function is insert description and date
	*@parameter: array
	*@return: boolean
	*/
	public function save_res_guid_desc($save_form_details) {

		$count = sizeof($save_form_details['save_form_data']);		
		
		for($i = 0; $i < $count; $i++) {
			$date =  date("Y-m-d",strtotime($save_form_details['actual_date'][$i]));
			$save_form_data_array = array(
                'description' => $save_form_details['res_guid_description'][$i],
                'actual_date' => $date
            );
			
			$af_where_clause = array(
				'uload_id' => $save_form_details['save_form_data'][$i]
			);
		 $result = $this->db->update('stud_improvement_plan_uploads', $save_form_data_array, $af_where_clause);
			
		}  
		return $result;
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
			return $types;
			
	}
	
		/*
        * Function is to fetch the occasion details.
        * @param - -----.
        * returns list of occasion names.
	*/   
    public function occasion_fill_mte($crclm_id,$term_id,$crs_id,$section_id) {
		$fetch_course_query = $this->db->query('SELECT a.qpd_id,a.ao_description
												FROM assessment_occasions a,qp_definition q 
												WHERE a.crclm_id = '.$crclm_id.'
												AND a.term_id = '.$term_id.'
												AND a.crs_id = '.$crs_id.'											
												AND q.qpd_id = a.qpd_id
												AND q.qp_rollout > 1
												AND q.qpd_type = 3
                                                AND cia_model_qp = 0
												AND a.section_id = 0 
												AND a.qpd_id IS NOT NULL');
		return  $fetch_course_query->result_array();
    } 
}
