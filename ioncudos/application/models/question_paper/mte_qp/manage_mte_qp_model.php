<?php

/* --------------------------------------------------------------------------------------------------------------------------------
 * Description	:	  
 * Author : Bhagyalaxmi.shivapuji	
 * Date : 02-03-2017
 * Modification History:
 * Date							Modified By							Description
  ---------------------------------------------------------------------------------------------------------------------------------
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Manage_mte_qp_model extends CI_Model {
    /* Function is used to fetch the dept id & name from dept table.
     * @param - 
     * @returns- a array of values of the dept details.
	 
	    /* Function is used to fetch the dept id & name from dept table.
     * @param - 
     * @returns- a array of values of the dept details.
     */

    public function dept_fill() {
        $loggedin_user_id = $this->ion_auth->user()->row()->id;

        if ($this->ion_auth->is_admin()) {
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
	
	    /* Function is used to fetch the pgm id & name from program table.
     * @param - 
     * @returns- a array of values of the program details.
     */

    public function pgm_fill($dept_id) {
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
	
	    /* Function is used to fetch the curriculum id & name from curriculum table.
     * @param - 
     * @returns- a array of values of the curriculum details.
     */

    public function crclm_fill($pgm_id) {
/*         $loggedin_user_id = $this->ion_auth->user()->row()->id;
        $crclm_name = 'SELECT DISTINCT c.crclm_id, c.crclm_name
					   FROM curriculum AS c
					   WHERE c.pgm_id = "' . $pgm_id . '" 
						  AND c.status = 1 
						  ORDER BY c.crclm_name ASC';
        $resx = $this->db->query($crclm_name);
        $result = $resx->result_array();
        $crclm_data['crclm_result'] = $result;
        return $crclm_data; */
		
			
		$loggedin_user_id = $this->ion_auth->user()->row()->id;
	if ($this->ion_auth->is_admin()) {
	    $curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name, d.state
				FROM curriculum AS c JOIN dashboard AS d on d.entity_id=2
				AND d.status=1
				WHERE c.status = 1
				AND c.pgm_id = "'.$pgm_id.'"
				GROUP BY c.crclm_id
				ORDER BY c.crclm_name ASC';
	} else if($this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')) {

	    $dept_id = $this->ion_auth->user()->row()->user_dept_id;
	    $curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name
				FROM curriculum AS c, users AS u, program AS p
				WHERE u.id = "' . $loggedin_user_id . '" 
				AND u.user_dept_id = p.dept_id
				AND c.pgm_id = p.pgm_id
				AND c.status = 1
				AND c.pgm_id = "'.$pgm_id.'"
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
				AND c.pgm_id = "'.$pgm_id.'"
				ORDER BY c.crclm_name ASC';
	}

	$curriculum_list_data = $this->db->query($curriculum_list);
	$crclm_data['crclm_result'] =  $curriculum_list_data->result_array();
	return $crclm_data; 
    }
	
	
	    /* Function is used to fetch the term id & name from crclm_terms table.
     * @param - curriculum id.
     * @returns- a array of values of the term details.
     */

    public function term_fill($curriculum_id) {
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
        $data = $result->result_array();
        $term_data['res2'] = $data;

        return $term_data;
    }
	
	
	    /* Function is used to fetch the course, course type, term,course designer & course reviewer details 
     * from course, crclm_terms, course_clo_owner, course_validator, users & curse_type tables.
     * @param - curriculum id & term id.
     * @returns- a array of values of all the course details.
     */

    public function course_list($crclm_id, $term_id) {

        $user = $this->ion_auth->user()->row()->id;

        if ($this->ion_auth->in_group('admin') || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')) {
            $crs_list = 'SELECT c.crs_id, c.crs_title, c.crs_type_id, c.crs_acronym, c.crs_code, lect_credits, 
							tutorial_credits, practical_credits, c.self_study_credits, c.total_credits, c.cie_marks, 
							c.see_marks, c.total_marks, c.contact_hours, c.see_duration, c.crs_mode, c.status,c.state_id, t.term_name, 
							u.clo_owner_id, r.validator_id, s.title, s.username, s.first_name, s.last_name, ct.crs_type_name
					FROM  course AS c, crclm_terms AS t, course_clo_owner AS u, course_clo_validator AS r, users AS s ,
						course_type ct
					WHERE c.crclm_id = "' . $crclm_id . '" 
					AND c.crclm_term_id = "' . $term_id . '" 
					AND c.state_id >= 4
					AND t.crclm_term_id = "' . $term_id . '"   
					AND u.crs_id = c.crs_id 
					AND r.crs_id = c.crs_id 
					AND s.id = u.clo_owner_id  						
					AND ct.crs_type_id = c.crs_type_id
								GROUP BY c.crs_id';
            $crs_list_result = $this->db->query($crs_list);
            $crs_list_data = $crs_list_result->result_array();

            $crs_list_return['crs_list_data'] = $crs_list_data;
        } else {
            $crs_list = 'SELECT c.crs_id, c.crs_title, c.crs_type_id, c.crs_acronym, c.crs_code, lect_credits, 
							tutorial_credits, practical_credits, c.self_study_credits, c.total_credits, c.cie_marks, 
							c.see_marks, c.total_marks, c.contact_hours, c.see_duration, c.crs_mode, c.status,c.state_id, t.term_name, 
							u.clo_owner_id, r.validator_id, s.title, s.username, s.first_name, s.last_name, ct.crs_type_name
						FROM  course AS c, crclm_terms AS t, course_clo_owner AS u, course_clo_validator AS r, users AS s,  
							course_type ct
						WHERE c.crclm_id = "' . $crclm_id . '" 
						AND c.crclm_term_id = "' . $term_id . '" 
						AND t.crclm_term_id = "' . $term_id . '"  
						AND c.state_id >= 4						
						AND u.crs_id = c.crs_id 
						AND u.clo_owner_id = "' . $user . '" 
						AND r.crs_id = c.crs_id 
						AND s.id = u.clo_owner_id 
						AND ct.crs_type_id = c.crs_type_id 						
						group by crs_id';
            $crs_list_result = $this->db->query($crs_list);
            $crs_list_data = $crs_list_result->result_array();
            $crs_list_return['crs_list_data'] = $crs_list_data;
        }

        return $crs_list_return;
    }
    /** Function to check if question papaer is defined for TEE
     * *@param - crs_id
     * *@returns - TRUE if defined
     * */
    public function oneQPDefined($crs_id_val) {
        $isQPDefined = $this->db->query('SELECT count(*) as "defined"
						FROM qp_definition q
						WHERE q.qpd_type = 5
						AND q.crs_id = ' . $crs_id_val . '');
        $isQPDefined_res = $isQPDefined->result();
        return $isQPDefined_res;
    }

    //End of function oneQPDefined
	 
		/*     * Function to check whether framework for a course has been set or not* */

    public function check_framework_tee($pgm_id ,$crs_id) {
        $pgmtype_fetch_query = 'SELECT pgmtype_id
								FROM qp_framework
								WHERE pgmtype_id = "' . $pgm_id . '"';
        $pgmtype_fetch_result = $this->db->query($pgmtype_fetch_query);
        $pgmtype_data = $pgmtype_fetch_result->result_array();

		$query = $this->db->query('SELECT * FROM qp_definition q join assessment_occasions as a on a.crs_id = q.crs_id where q.crs_id = "'. $crs_id .'" and a.mte_flag = 1');
		$qp_def = $query->result_array();	
		
		$query1 = $this->db->query('SELECT * FROM qp_definition q where crs_id = "'.$crs_id.'" and qpd_type = 4');
		$qp_def_4 = $query->result_array();

        if (!empty($pgmtype_data)) {
            return '1';
        } else if(!empty($qp_def)){ 

			return '2';
		} else  {
            return 0;
        }
/* 		
		else if(!empty($qp_def_4)){ 
			return '3';
		} */
    } 
	 
	 
    public function fetch_mte_qp_data($crclm_id, $term_id, $crs_id, $qp_type) {

        $fetch_qp_data_query = 'SELECT c.crs_title ,a.ao_name , a.crs_id , a.section_id ,  a.ao_description , a.ao_id , qp.qpd_id, qp.qpf_id,qp.qp_rollout, qp.qpd_type, qp.qpd_title, qp.rubrics_flag, qp.ao_method_id, qp.created_by, qp.created_date, qp.modified_by, qp.modified_date, u.title as utitle, u.first_name as uname, u.last_name as ulastname , mu.title as mtitle, mu.first_name as mfirst, mu.last_name as mlast FROM qp_definition as qp
		JOIN users as u on u.id = qp.created_by 
		JOIN users as mu on mu.id = qp.modified_by
		JOIN assessment_occasions as a on a.qpd_id = qp.qpd_id
		JOIN course as c on c.crs_id = qp.crs_id
		WHERE qp.crclm_id = "' . $crclm_id . '"
		AND qp.crclm_term_id = "' . $term_id . '"
		AND qp.crs_id = "' . $crs_id . '"
		AND qp.qpd_type = "' . $qp_type . '" 
		AND a.mte_flag = 1
		group by qp.qpd_id ';
        $fetch_qp_data = $this->db->query($fetch_qp_data_query);
        $fetch_qp_data_res = $fetch_qp_data->result_array();
        return $fetch_qp_data_res;
    }

	
    public function check_data_imported($qpd_id, $crclm_id, $term_id, $crs_id) {
        $query = $this->db->query('SELECT qp_rollout FROM qp_definition as q join assessment_occasions as ao on ao.crs_id = q.crs_id where q.qpd_type="3" and q.crclm_id = "' . $crclm_id . '" and q.crclm_term_id ="' . $term_id . '" and q.crs_id ="' . $crs_id . '" and q.qpd_id="' . $qpd_id . '"  and ao.mte_flag = 1');
        $result = $query->result_array();
        return $result;
    }
	public function fetch_rubrics_qp_data($crclm_id, $term_id, $crs_id, $qp_type){
	
		$query = $this->db->query('SELECT c.crs_title ,a.ao_name , a.crs_id , a.section_id ,  a.ao_description , a.ao_id , qp.qpd_id, qp.qpf_id,qp.qp_rollout, qp.qpd_type, qp.qpd_title, qp.rubrics_flag, qp.ao_method_id, qp.created_by, qp.created_date, qp.modified_by, qp.modified_date, u.title as utitle, u.first_name as uname, u.last_name as ulastname , mu.title as mtitle, mu.first_name as mfirst, mu.last_name as mlast FROM qp_definition as qp
		JOIN users as u on u.id = qp.created_by 
		JOIN users as mu on mu.id = qp.modified_by
		JOIN assessment_occasions as a on a.qpd_id = qp.qpd_id
		JOIN course as c on c.crs_id = qp.crs_id
		WHERE qp.crclm_id = "' . $crclm_id . '"
		AND qp.crclm_term_id = "' . $term_id . '"
		AND qp.crs_id = "' . $crs_id . '"
		AND qp.qpd_type = "' . $qp_type . '" 
		AND a.mte_flag = 1
		AND qp.rubrics_flag = 1
		group by qp.qpd_id');
        $fetch_qp_data_res = $query->result_array();
        return $fetch_qp_data_res;
	}
	
    public function mte_model_qp_details($crclm_id, $term_id, $crs_id, $ao_id) {
        $qpd_id_query = 'SELECT qpd_id FROM assessment_occasions WHERE ao_id = "' . $ao_id . '" and crclm_id= "' . $crclm_id . '" and term_id= "' . $term_id . '" and crs_id = "' . $crs_id . '"';
        $qpd_id_data = $this->db->query($qpd_id_query);
        $qpd_id = $qpd_id_data->result_array();

        return $qpd_id;
    }
	
	/*   Function to Fetch the QP mapping entities */
    public function mapping_entity() {

        $qp_entity_config_query = 'SELECT entity_id, entity_name, alias_entity_name, qpf_config_orderby FROM entity WHERE qpf_config = 1 
		ORDER BY qpf_config_orderby';
        $qp_entity_config_data = $this->db->query($qp_entity_config_query);
        $qp_entity_config_result = $qp_entity_config_data->result_array();
        return $qp_entity_config_result;
    }

	
	public function generate_model_unit($qpd_id) {

        $unitid_wfm_query = 'SELECT * FROM qp_unit_definition WHERE qpd_id="' . $qpd_id . '"';
        $unitid_wfm_data = $this->db->query($unitid_wfm_query);
        $unitid_wfm_result = $unitid_wfm_data->result_array();
        return ($unitid_wfm_result);
    }
	
	    public function generate_model_qp($pgm_id) {
        $qp_query = 'SELECT  qpf.qpf_id, qpf.qpf_notes, qpf.qpf_gt_marks, qpf.qpf_max_marks, unit.qpf_unit_id, unit.qpf_unit_code, 
					unit.qpf_num_mquestions, unit.qpf_utotal_marks , qpf_mq.qpf_mq_id, qpf_mq.qpf_mq_code, qpf_mq.qpf_mtotal_marks			
					FROM qp_framework as qpf
					JOIN qpf_unit as unit ON  unit.qpf_id = qpf.qpf_id
					JOIN qpf_mquestion as qpf_mq ON  unit.qpf_unit_id = qpf_mq.qpf_unit_id
					WHERE qpf.pgmtype_id = "' . $pgm_id . '" ';
        $qpf_mq_data = $this->db->query($qp_query);
        $qpf_mq_result = $qpf_mq_data->result_array();

        $qp_unit_query = 'SELECT  qpf.qpf_id,qpf.qpf_notes, unit.qpf_unit_id, unit.qpf_unit_code, 
					unit.qpf_num_mquestions, unit.qpf_utotal_marks 
					FROM qp_framework as qpf
					JOIN qpf_unit as unit ON  unit.qpf_id = qpf.qpf_id
					WHERE qpf.pgmtype_id = "' . $pgm_id . '" ';
        $qp_unit_data = $this->db->query($qp_unit_query);
        $qp_unit_result = $qp_unit_data->result_array();

        $qp_entity_config_query = 'SELECT entity_id FROM entity WHERE qpf_config = 1 ORDER BY qpf_config_orderby';
        $qp_entity_config_data = $this->db->query($qp_entity_config_query);
        $qp_entity_config_result = $qp_entity_config_data->result_array();


		if (!empty($qp_unit_result)) {
			$qp_data['qpf_unit_details'] = $qp_unit_result;
		}else{ $qp_data['qpf_unit_details']  = false; }
        if (!empty($qpf_mq_result)) {
            $qp_data['qpf_mq_details'] = $qpf_mq_result;
			$qp_data['qpf_mq_details']  = false;
        }
        $qp_data['qp_entity_config'] = $qp_entity_config_result;
        return $qp_data;
    }
	
	    public function generate_total_marks($qpd_id) {

        $query = 'select qpd_unitd_id, qp_unit_code from qp_unit_definition where qpd_id="' . $qpd_id . '"';
        $query_data = $this->db->query($query);
        $v = $query_data->result_array();
        $unit_size = count($v);
        $m = '';
        $v1 = array();
        for ($i = 0; $i < $unit_size; $i++) {
            $v1[$i] = $this->db->select('SUM(qp_subq_marks)')
                    ->where('qp_unitd_id', $v[$i]['qpd_unitd_id'])
                    ->get('qp_mainquestion_definition')
                    ->result_array();
        }
        $size = count($v1);

        for ($k = 0; $k < $size; $k++) {
            (int) $m += $v1[$k][0]['SUM(qp_subq_marks)'];
        }

        $data['marks'] = $m;
        $data['units'] = $v;
        return $data;
    }
	
	    public function generate_model_unit_sum($qpd_id) {
        $unitid_wfm_query = 'SELECT SUM(qp_utotal_marks) FROM qp_unit_definition WHERE qpd_id="' . $qpd_id . '"';
        $unitid_wfm_data = $this->db->query($unitid_wfm_query);
        $unitid_wfm_result = $unitid_wfm_data->result_array();
        return ($unitid_wfm_result[0]['SUM(qp_utotal_marks)']);
    }
    public function model_qp_course_data($crclm_id, $term_id, $crs_id) {
        $course_co_data_query = 'SELECT clo_id, clo_code, clo_statement
						FROM clo 
						WHERE crclm_id = "' . $crclm_id . '" 
						AND term_id = "' . $term_id . '" 
						AND crs_id = "' . $crs_id . '"
						ORDER BY LPAD(LOWER(clo_code),5,0) ASC';
        //ORDER BY clo_code ASC';
        $course_co_data = $this->db->query($course_co_data_query);
        $course_co_data_result = $course_co_data->result_array();
					
		$course_question_type = $this->db->query('select * from master_type_details as m1 join master_type as m2 on m1.master_type_id = m2.master_type_id where m2.master_type_name = "qn_type"');
		$course_question_type_result = $course_question_type->result_array();
					
					
        $topic_query = 'SELECT topic_id, topic_title 
						FROM topic 
						WHERE curriculum_id = "' . $crclm_id . '" 
						AND term_id = "' . $term_id . '" 
						AND course_id = "' . $crs_id . '"
						ORDER BY LPAD(LOWER(topic_title),5,0) ASC';
        //ORDER BY topic_title ASC';
        $topic_data = $this->db->query($topic_query);
        $topic_result = $topic_data->result_array();
/* 
        $bloom_lvl_query = 'SELECT bloom_id, level, bloom_actionverbs,description,learning
								FROM bloom_level
								topic_title
								ORDER BY LPAD(LOWER(level),5,0) ASC';

        $bloom_lvl_data = $this->db->query($bloom_lvl_query);
        $bloom_lvl_result = $bloom_lvl_data->result_array(); */

		$query = $this->db->query('SELECT CONCAT(COALESCE(cognitive_domain_flag,""),",",COALESCE(affective_domain_flag,""),",",COALESCE(psychomotor_domain_flag,""))  crs_id from course where crs_id= "'.$crs_id.'"');
		$re = $query->result_array();
		$count = count($re[0]['crs_id']);
		
		$set_data = (explode(",",$re[0]['crs_id']));

		$sk=0; $bld_id ='';
		$bloom_domain_query = $this->db->query('SELECT bld_id,bld_name,status FROM bloom_domain');
                  $bloom_domain_data = $bloom_domain_query->result_array();
		foreach($bloom_domain_data as $bdd){
			
			if ($set_data[$sk] == 1 && $bdd['status'] == 1) {
				$bld_id [] = $bdd['bld_id'];
				
			}
		$sk++;
		}
		if($bld_id != '' && !empty($bld_id)){
		$Bld_id_data = implode (",", $bld_id);

		$bld_id_single = str_replace("'", "", $Bld_id_data);	

		$bloom_lvl_query = 'select * from  bloom_level where bld_id IN('.$bld_id_single.') ORDER BY LPAD(LOWER(level),5,0) ASC';
		}else{
			$bloom_lvl_query = 'select * from  bloom_level  ORDER BY LPAD(LOWER(level),5,0) ASC';
		}
		$bloom_lvl_data = $this->db->query($bloom_lvl_query);
        $bloom_lvl_result = $bloom_lvl_data->result_array();

        $crs_title_query = 'SELECT crs_title, crs_code FROM course WHERE crs_id = "' . $crs_id . '"';
        $crs_title_data = $this->db->query($crs_title_query);
        $crs_title_result = $crs_title_data->result_array();

        $pi_code_query = 'SELECT DISTINCT co_po.msr_id, crs_id, msr.msr_statement, msr.pi_codes FROM clo_po_map as co_po
							JOIN measures as msr ON msr.msr_id = co_po.msr_id 
							WHERE co_po.crs_id = "' . $crs_id . '" 
							ORDER BY LPAD(LOWER(msr.pi_codes),5,0) ASC';
        //	ORDER BY msr.pi_codes ASC';
        $pi_code_data = $this->db->query($pi_code_query);
        $pi_code_result = $pi_code_data->result_array();

        $crclm_title = 'SELECT * FROM curriculum c where c.crclm_id="' . $crclm_id . '"';
        $crclm_title = $this->db->query($crclm_title);
        $crclm_title = $crclm_title->result_array();

        $term_title = 'SELECT * FROM crclm_terms c where c.crclm_term_id="' . $term_id . '"';
        $term_title = $this->db->query($term_title);
        $term_title = $term_title->result_array();
        $course_data['crclm_title'] = $crclm_title[0]['crclm_name'];
        $course_data['term_title'] = $term_title[0]['term_name'];
        $course_data['co_data'] = $course_co_data_result;
		$course_data['question_type_data'] = $course_question_type_result;
        $course_data['topic_list'] = $topic_result;
        $course_data['level_list'] = $bloom_lvl_result;
        $course_data['pi_code_list'] = $pi_code_result;
        $course_data['crs_title'] = $crs_title_result;

        return $course_data;
    }
	    public function model_qp_edit_details($crclm_id, $term_id, $crs_id, $qp_type, $qpd_id) {
        if (!empty($qpd_id)) {

            $occasions_query = 'select * from assessment_occasions where qpd_id="' . $qpd_id . '"';
            $occasions_re = $this->db->query($occasions_query);
            $occasions_res = $occasions_re->result_array();

            $model_qp_meta_data_query = 'SELECT qpd.cia_model_qp,qpd.qpd_id, qpd.qpf_id, qpd.qpd_type, qpd.qpd_title, qpd.qpd_timing, 	qpd.qpd_max_marks, qpd.qpd_notes, qpd.qpd_num_units, qpd.qpd_gt_marks, crs.crs_title, crs.crs_code
		FROM qp_definition AS qpd
	JOIN course AS crs ON qpd.crs_id = crs.crs_id
	WHERE qpd.crclm_id = "' . $crclm_id . '"
	AND qpd.crclm_term_id = "' . $term_id . '"
	AND qpd.crs_id = "' . $crs_id . '" 
	AND qpd.qpd_type = "' . $qp_type . '"
	AND qpd.qpd_id = "' . $qpd_id . '"';
        } else {
            $model_qp_meta_data_query = 'SELECT qpd.cia_model_qp,qpd.qpd_id, qpd.qpf_id, qpd.qpd_type, qpd.qpd_title, qpd.qpd_timing, 	qpd.qpd_max_marks, qpd.qpd_notes, qpd.qpd_num_units, qpd.qpd_gt_marks, crs.crs_title, crs.crs_code
		FROM qp_definition AS qpd
	JOIN course AS crs ON qpd.crs_id = crs.crs_id
	WHERE qpd.crclm_id = "' . $crclm_id . '"
	AND qpd.crclm_term_id = "' . $term_id . '"
	AND qpd.crs_id = "' . $crs_id . '" 
	AND qpd.qpd_type = "' . $qp_type . '"';
        }

        $model_qp_meta_data = $this->db->query($model_qp_meta_data_query);
        $model_qp_meta_data_res = $model_qp_meta_data->result_array();
        $val['qp_meta_data'] = $model_qp_meta_data_res;

        $query = 'SELECT qpd_unitd_id from qp_unit_definition where qpd_id="' . $model_qp_meta_data_res[0]['qpd_id'] . '"';
        $query_re = $this->db->query($query);
        $count = $query_re->num_rows();
        $qpd_unitd_id = $query_re->result_array();
        $data = array();
        for ($i = 0; $i < $count; $i++) {
            $data[$i] = $this->db->select('*')
                    ->from('qp_unit_definition')
                    ->join('qp_mainquestion_definition', 'qp_unit_definition.qpd_unitd_id=qp_mainquestion_definition.qp_unitd_id')
                    ->where('qp_unitd_id', $qpd_unitd_id[$i]['qpd_unitd_id'])
                    //$this->db->join('category', 'category.id = articles.id'); 
                    ->get()
                    ->result_array();
            $size = count($data[$i]);
            for ($j = 0; $j < $size; $j++) {
                $co_query = 'SELECT qp_map.actual_mapped_id, co.clo_code, co.clo_statement FROM qp_mapping_definition as qp_map 
								JOIN clo as co ON co.clo_id = qp_map.actual_mapped_id
								WHERE qp_map.qp_mq_id = "' . $data[$i][$j]['qp_mq_id'] . '" AND qp_map.entity_id =11';
                $co_data = $this->db->query($co_query);
                $co_result[] = $co_data->result_array();
            }

            for ($p = 0; $p < $size; $p++) {
                $po_query = 'SELECT qp_map.actual_mapped_id, po.po_reference, po.po_statement FROM qp_mapping_definition as qp_map 
								JOIN po as po ON po.po_id = qp_map.actual_mapped_id
								WHERE qp_map.qp_mq_id = "' . $data[$i][$p]['qp_mq_id'] . '" AND qp_map.entity_id =6';
                $po_data = $this->db->query($po_query);
                $po_result[] = $po_data->result_array();
            }

            for ($t = 0; $t < $size; $t++) {
                $topic_query = 'SELECT qp_map.actual_mapped_id, top.topic_title FROM qp_mapping_definition as qp_map 
								JOIN topic as top ON top.topic_id = qp_map.actual_mapped_id
								WHERE qp_map.qp_mq_id = "' . $data[$i][$t]['qp_mq_id'] . '" AND qp_map.entity_id =10';
                $topic_data = $this->db->query($topic_query);
                $topic_result[] = $topic_data->result_array();
            }

            for ($l = 0; $l < $size; $l++) {
                $level_query = 'SELECT qp_map.actual_mapped_id, bl.level, bl.bloom_actionverbs FROM qp_mapping_definition as qp_map 
								JOIN bloom_level as bl ON bl.bloom_id = qp_map.actual_mapped_id
								WHERE qp_map.qp_mq_id = "' . $data[$i][$l]['qp_mq_id'] . '" AND qp_map.entity_id = 23';
                $level_data = $this->db->query($level_query);
                $level_result[] = $level_data->result_array();
            }

            for ($pi = 0; $pi < $size; $pi++) {
                $pi_query = 'SELECT qp_map.actual_mapped_id, pi.pi_codes FROM qp_mapping_definition as qp_map 
								JOIN measures as pi ON pi.msr_id = qp_map.actual_mapped_id
								WHERE qp_map.qp_mq_id = "' . $data[$i][$pi]['qp_mq_id'] . '" AND qp_map.entity_id =22';
                $pi_data = $this->db->query($pi_query);
                $pi_result[] = $pi_data->result_array();
            }
        }

        if (!empty($co_result)) {
            $co_data_array = Array();
            $co_siz = count($co_result);
            for ($j = 0; $j < $co_siz; $j++) {
                $count = count($co_result[$j]);
                for ($k = 0; $k < $count; $k++) {
                    $co_data_array[$j][] = $co_result[$j][$k]['clo_code'];
                }
                @$co_data_string[] = implode(",", $co_data_array[$j]);
            }
            $val['co_data'] = $co_data_string;
        }
        if (!empty($po_result)) {
            $po_data_array = Array();
            $po_siz = count($po_result);
            for ($po = 0; $po < $po_siz; $po++) {
                $po_count = count($po_result[$po]);
                for ($p = 0; $p < $po_count; $p++) {
                    $po_data_array[$po][] = $po_result[$po][$p]['po_reference'];
                }
                @$po_data_string[] = implode(",", $po_data_array[$po]);
            }
            $val['po_data'] = $po_data_string;
        }

        if (!empty($topic_result)) {
            $topic_data_array = Array();
            $top_siz = count($topic_result);
            for ($t = 0; $t < $top_siz; $t++) {
                if (!empty($topic_result[$t])) {
                    $top_count = count($topic_result[$t]);
                    for ($top = 0; $top < $top_count; $top++) {
                        $topic_data_array[$t][] = $topic_result[$t][$top]['topic_title'];
                    }
                    $topic_data_string[] = implode(",", $topic_data_array[$t]);
                } else {
                    $topic_data_string[] = NULL;
                }
            }
            $val['topic_data'] = $topic_data_string;
        }


        if (!empty($level_result)) {
            $level_data_array = Array();
            $lev_siz = count($level_result);
            for ($lv = 0; $lv < $lev_siz; $lv++) {
                $lev_count = count($level_result[$lv]);
                for ($lev = 0; $lev < $lev_count; $lev++) {
                    $level_data_array[$lv][] = $level_result[$lv][$lev]['level'];
                }
                @$level_data_string[] = implode(",", $level_data_array[$lv]);
            }
            $val['level_data'] = $level_data_string;
        }


        if (!empty($pi_result)) {
            $pi_data_array = Array();
            $pi_siz = count($pi_result);
            for ($pi = 0; $pi < $pi_siz; $pi++) {
                if (!empty($pi_result[$pi])) {
                    $pi_count = count($pi_result[$pi]);
                    for ($pc = 0; $pc < $pi_count; $pc++) {
                        $pi_data_array[$pi][] = $pi_result[$pi][$pc]['pi_codes'];
                    }
                    $pi_data_string[] = implode(",", $pi_data_array[$pi]);
                } else {
                    $pi_data_string[] = NULL;
                }
            }
            $val['pi_data'] = $pi_data_string;
        }

        $val['question_paper_data'] = $data;
        $val['dat1'] = $qpd_unitd_id;
        if (!empty($occasions_res)) {
            $val['occassion_name'] = $occasions_res;
        } else {
            $val['occassion_name'] = "";
        }

        return $val;
    }
	/** Function to store Question PAper Definition */
    public function mte_model_qp_def($result) {

        $unit_counter = 'SELECT qpf_unit_code FROM qpf_unit WHERE qpf_id = "' . $result['qpf_id'] . '"';
        $unit_data = $this->db->query($unit_counter);
        $unit_result = $unit_data->num_rows();
        $unit = $unit_data->result_array();
        $logged_in_uid =  $this->ion_auth->user()->row()->id;
        if (empty($result['qp_title']) == true || empty($result['time']) == true || empty($result['Grand_total']) == true || ($result['Grand_total']) < $result['max_marks']) {
            $val = "false";
        } else { // Insertion of data into QP_Defination table
            $qp_definition = array( 
                'qpf_id' => $result['qpf_id'],
                'qpd_type' => $result['qpd_type'],
                'qp_rollout' => 1,
                'crclm_id' => $result['crclm_id'],
                'crclm_term_id' => $result['term_id'],
                'crs_id' => $result['crs_id'],
                'qpd_title' => $result['qp_title'],
                'qpd_timing' => $result['time'],
                'qpd_gt_marks' => $result['Grand_total'],
                'qpd_max_marks' => $result['max_marks'],
                'qpd_notes' => $result['qp_notes'],
                'qpd_num_units' => $unit_result,
                'cia_model_qp' => $result['qp_model'],
                'created_by' => $logged_in_uid,
                'created_date' => date('y-m-d'),
                'modified_by' => $logged_in_uid,
                'modified_date' => date('y-m-d')
            );
            $this->db->insert('qp_definition', $qp_definition);
            $last_inserted_qpd_id = $this->db->insert_id();
         
			
			
			$ao_id_fetch = $this->db->query('SELECT ao_name FROM assessment_occasions where crs_id = "'. $result['crs_id'] .'" and mte_flag = 1 ORDER BY ao_id DESC LIMIT 1;');
			$ao_id_old = $ao_id_fetch->result_array(); 
			$ao_name = 1;
			if( !empty($ao_id_old)){  $ao_name = $ao_id_old[0]['ao_name'] + (int) 1 ;} else  { $ao_name = 1;}
			$mte_occasion  = 'mte_occasion_'. ($ao_name);
			$assessment_occasion = array(
					'mte_flag' => 1,
					'qpd_id'   => $last_inserted_qpd_id, 
					'ao_name'  => $ao_name,
					'ao_description' => $mte_occasion,
					'ao_method_id'   => '',
					'ao_type_id'	=> 1,
					'max_marks'		=> $result['max_marks'] , 
					'crclm_id' => $result['crclm_id'],
					'term_id' => $result['term_id'],
					'crs_id' => $result['crs_id'],
					'section_id' => '',
					'created_by' => $logged_in_uid,
					'created_date' => date('y-m-d'),
					'modified_by' => $logged_in_uid,
					'modified_date' => date('y-m-d'),
					'status' => 1,
					'rubrics_qp_status' => ''										
			);
			$this->db->insert('assessment_occasions', $assessment_occasion);
        
			$re = 'select q.qpd_id , a.ao_id   FROM qp_definition q
					join assessment_occasions as a  on a.qpd_id = q.qpd_id and a.mte_flag = 1 and q.qpd_id = "'. $last_inserted_qpd_id .'" ORDER BY q.qpd_id DESC LIMIT 1';
            $pi_code_data = $this->db->query($re);
            $val = $pi_code_data->result_array();

            $array_size = count($result['val']);
            for ($i = 1; $i <= $array_size; $i++) {
                $qp_unit_definition = array(
                    'qpd_id' => $last_inserted_qpd_id,
                    'qp_unit_code' => $result['val'][$i]['val1'],
                    'qp_total_unitquestion' => $result['val'][$i]['val2'],
                    'qp_attempt_unitquestion' => 1,
                    'qp_utotal_marks' => $result['val'][$i]['val3'],
                    'created_by' => $logged_in_uid,
                    'created_date' => date('y-m-d'),
                    'modified_by' => $logged_in_uid,
                    'modified_date' => date('y-m-d'),
                    'FM' => 1
                );
				

               $this->db->insert('qp_unit_definition', $qp_unit_definition);
				$this->db->insert_id();
            }
        }
			
        return $val;
    }
	

 /*
      Function to Fetch  Number of question .
      @param : question_unit_id.
      @return : Success Message.
     */

    public function unit_list($qpf_id, $qpd_id) {
        $m = '';
        $this->db->select('qp_total_unitquestion')
                ->from('qp_unit_definition')
                ->where(('qpd_unitd_id'), $qpf_id);
        $q = $this->db->get();
        $result['per'] = $q->result_array();

        $query = 'select qpd_unitd_id from qp_unit_definition where qpd_id="' . $qpd_id . '"';
        $query_re = $this->db->query($query);
        $v = $query_re->result_array();
        $unit_size = count($v);
        for ($i = 0; $i < $unit_size; $i++) {
            $v1[$i] = $this->db->select('SUM(qp_subq_marks)')
                    ->where('qp_unitd_id', $v[$i]['qpd_unitd_id'])
                    ->get('qp_mainquestion_definition')
                    ->result_array();
        }
        $size = count($v1);
        for ($k = 0; $k < $size; $k++) {
            (int) $m += $v1[$k][0]['SUM(qp_subq_marks)'];
        }



        $query = 'select qp_total_unitquestion from qp_unit_definition where qpd_id="' . $qpd_id . '"';
        $query_re = $this->db->query($query);
        $result['data'] = $query_re->result_array();

        $query1 = 'select qp_subq_code, qp_subq_marks from qp_mainquestion_definition where qp_unitd_id="' . $qpf_id . '"';
        $query_re1 = $this->db->query($query1);
        $res = $this->db->select('qp_utotal_marks')
                ->from('qp_unit_definition')
                ->where('qpd_unitd_id', $qpf_id)
                ->get()
                ->result_array();
        $res1 = $this->db->select('SUM(qp_subq_marks)')
                ->where('qp_unitd_id', $qpf_id)
                ->get('qp_mainquestion_definition')
                ->result_array();
        $result['c'] = $query_re1->num_rows();
        $result['qcode'] = $query_re1->result_array();
        $result['total_unit'] = $res;
        $result['attemted'] = $res1;
        $result['total_attempted'] = $m;
        return $result;
    }
	
	public function check_question($qpd_unitd_id, $qp_subq_code) {
        $query = 'SELECT * from qp_mainquestion_definition where qp_unitd_id="' . $qpd_unitd_id . '" AND qp_subq_code="' . $qp_subq_code . '"';
        $query_re = $this->db->query($query);
        $count = $query_re->num_rows();
        $qpd_unitd_id = $query_re->result_array();
        return $count;
    }
	
	/** Function to store and retrive  question paper data from and to db
     * *
     * *
     * */
    public function fetch_groups($qpd_id) {
        //$i=0;
		$query = $this->db->query('SELECT * from entity where entity_id = 6 ');
		$result =  $query->result_array();
		
        $query = 'SELECT qpd_unitd_id from qp_unit_definition where qpd_id="' . $qpd_id . '"';
        $query_re = $this->db->query($query);
        $count = $query_re->num_rows();
        $qpd_unitd_id = $query_re->result_array();

	
        for ($i = 0; $i < $count; $i++) {

			$data1 = 'select * , IF(qp_subq_marks > FLOOR(qp_subq_marks),qp_subq_marks,CONVERT(qp_subq_marks,UNSIGNED INTEGER))
					AS qp_subq_marks
					  from qp_unit_definition as qm
					join qp_mainquestion_definition as q ON qm.qpd_unitd_id=q.qp_unitd_id
					where q.qp_unitd_id = "' . $qpd_unitd_id[$i]['qpd_unitd_id'] . '"
					and  qm.qpd_id =  "' . $qpd_id . '"
					ORDER BY CAST(q.qp_subq_code AS UNSIGNED), q.qp_subq_code ASC;';
                                                                  
            $data1 = $this->db->query($data1);
            $data[$i] = $data1->result_array();

            $size = count($data[$i]);
            for ($j = 0; $j < $size; $j++) {
                $co_query = 'SELECT qp_map.actual_mapped_id, co.clo_code, co.clo_statement FROM qp_mapping_definition as qp_map 
								JOIN clo as co ON co.clo_id = qp_map.actual_mapped_id
								WHERE qp_map.qp_mq_id = "' . $data[$i][$j]['qp_mq_id'] . '" AND qp_map.entity_id =11
								ORDER BY CAST(co.clo_code AS UNSIGNED), co.clo_code DESC';
                $co_data = $this->db->query($co_query);
                $co_result[] = $co_data->result_array();
            }

            for ($p = 0; $p < $size; $p++) {
                $po_query = 'SELECT qp_map.actual_mapped_id, po.po_reference, po.po_statement FROM qp_mapping_definition as qp_map 
								JOIN po as po ON po.po_id = qp_map.actual_mapped_id
								WHERE qp_map.qp_mq_id = "' . $data[$i][$p]['qp_mq_id'] . '" AND qp_map.entity_id =6';
                $po_data = $this->db->query($po_query);
                $po_result[] = $po_data->result_array();
            }

            for ($t = 0; $t < $size; $t++) {
                $topic_query = 'SELECT qp_map.actual_mapped_id, top.topic_title FROM qp_mapping_definition as qp_map 
								JOIN topic as top ON top.topic_id = qp_map.actual_mapped_id
								WHERE qp_map.qp_mq_id = "' . $data[$i][$t]['qp_mq_id'] . '" AND qp_map.entity_id =10';
                $topic_data = $this->db->query($topic_query);
                $topic_result[] = $topic_data->result_array();
            }

            for ($l = 0; $l < $size; $l++) {
                $level_query = 'SELECT qp_map.actual_mapped_id, bl.level, bl.bloom_actionverbs FROM qp_mapping_definition as qp_map 
								JOIN bloom_level as bl ON bl.bloom_id = qp_map.actual_mapped_id
								WHERE qp_map.qp_mq_id = "' . $data[$i][$l]['qp_mq_id'] . '" AND qp_map.entity_id = 23';
                $level_data = $this->db->query($level_query);
                $level_result[] = $level_data->result_array();
            }

            for ($pi = 0; $pi < $size; $pi++) {
                $pi_query = 'SELECT qp_map.actual_mapped_id, pi.pi_codes FROM qp_mapping_definition as qp_map 
								JOIN measures as pi ON pi.msr_id = qp_map.actual_mapped_id
								WHERE qp_map.qp_mq_id = "' . $data[$i][$pi]['qp_mq_id'] . '" AND qp_map.entity_id =22';
                $pi_data = $this->db->query($pi_query);
                $pi_result[] = $pi_data->result_array();
            }    
			for ($qt = 0; $qt < $size; $qt++) {
                $qt_query = 'SELECT q.actual_mapped_id , m.mt_details_name from qp_mapping_definition as q
							JOIN master_type_details as m on m.mt_details_id = q.actual_mapped_id 
								WHERE q.qp_mq_id = "' . $data[$i][$qt]['qp_mq_id'] . '" AND q.entity_id =29';
                $qt_data = $this->db->query($qt_query);
                $qt_result[] = $qt_data->result_array();
            }
        }


        $co_data_array = Array();
		if(!empty($co_result)){
			$co_siz = count($co_result);
			for ($j = 0; $j < $co_siz; $j++) {
				$count = count($co_result[$j]);
				for ($k = 0; $k < $count; $k++) {
					$co_data_array[$j][] = $co_result[$j][$k]['clo_code'];
				}
				$co_data_string[] = implode(",", $co_data_array[$j]);
			}
		}else{
			$co_data_string[] = NULL;
		}	
        $po_data_array = Array();
		if(!empty($po_result[0])){
			$po_siz = count($po_result);			
			for ($po = 0; $po < $po_siz; $po++) {
				$po_count = count($po_result[$po]);
				for ($p = 0; $p < $po_count; $p++) {
					$po_data_array[$po][] = $po_result[$po][$p]['po_reference'];
				}
				$po_data_string[] = implode(",", $po_data_array[$po]);
			}
		}else{
			$po_data_string[] = NULL;
		}

        $topic_data_array = Array();
		if(!empty($topic_result)){
			$top_siz = count($topic_result);
			for ($t = 0; $t < $top_siz; $t++) {
				if (!empty($topic_result[$t])) {
					$top_count = count($topic_result[$t]);
					for ($top = 0; $top < $top_count; $top++) {
						$topic_data_array[$t][] = $topic_result[$t][$top]['topic_title'];
					}
					$topic_data_string[] = implode(",", $topic_data_array[$t]);
				} else {
					$topic_data_string[] = NULL;
				}
			}
		}else{
			$topic_data_string[] = NULL;
		}

        $level_data_array = Array();
		if(!empty($level_result)){
			$lev_siz = count($level_result);
			for ($lv = 0; $lv < $lev_siz; $lv++) {
				$lev_count = count($level_result[$lv]);
				for ($lev = 0; $lev < $lev_count; $lev++) {
					$level_data_array[$lv][] = $level_result[$lv][$lev]['level'];
				}
				$level_data_string[] = implode(",", $level_data_array[$lv]);
			}
		}else{
			$level_data_string[] = NULL;
		}


        $pi_data_array = Array();
		if(!empty($pi_result)){
			$pi_siz = count($pi_result);
			for ($pi = 0; $pi < $pi_siz; $pi++) {
				if (!empty($pi_result[$pi])) {
					$pi_count = count($pi_result[$pi]);
					for ($pc = 0; $pc < $pi_count; $pc++) {
						$pi_data_array[$pi][] = $pi_result[$pi][$pc]['pi_codes'];
					}
					$pi_data_string[] = implode(",", $pi_data_array[$pi]);
				} else {
					$pi_data_string[] = NULL;
				}
			}
		}else{
			$pi_data_string[] = NULL;
		}      
		
		
		$qt_data_array = Array();
		if(!empty($qt_result)){
			$pi_siz = count($qt_result);
			for ($qt1 = 0; $qt1 < $pi_siz; $qt1++) {
				if (!empty($qt_result[$qt1])) {
					$qt_count = count($qt_result[$qt1]);
					for ($qt2 = 0; $qt2 < $qt_count; $qt2++) {
						$qt_data_array[$qt1][] = $qt_result[$qt1][$qt2]['mt_details_name'];
					}
					$qt_data_string[] = implode(",", $qt_data_array[$qt1]);
				} else {
					$qt_data_string[] = NULL;
				}
			}
		}else{
			$qt_data_string[] = NULL;
		}

        $val['co_data'] = $co_data_string;
        $val['po_data'] = $po_data_string;
        $val['topic_data'] = $topic_data_string;
        $val['level_data'] = $level_data_string;
        $val['pi_data'] = $pi_data_string; 
		$val['qt_data'] = $qt_data_string;


        $val['question_paper_data'] = $data;

        $val['dat1'] = $qpd_unitd_id;
        return $val;
    }
	
    public function po_list($co_id, $crclm_id, $term_id, $crs_id) {        
        $this->db->select('copo.po_id, p.po_id, p.po_reference,p.po_statement')
                ->FROM('clo_po_map as copo')
                ->JOIN('po as p', 'copo.po_id = p.po_id')
                ->WHERE_IN('copo.clo_id', $co_id)
                ->WHERE('copo.crclm_id', $crclm_id)
                ->WHERE('copo.crs_id', $crs_id)
                ->group_by('copo.po_id');
        $mapped_po_res = $this->db->get()->result_array();

        $qp_entity_query = 'SELECT qpf_config FROM entity WHERE entity_id = 6';
        $qp_entity_data = $this->db->query($qp_entity_query);
        $qp_entity_result = $qp_entity_data->result_array();

        $po_related_data['qp_po_entity_data'] = $qp_entity_result;
        $po_related_data['mapped_po_res'] = $mapped_po_res;


        return $po_related_data;
    }
	
	public function bl_list( $co_id, $crclm_id, $term_id, $crs_id ){
		
            $query = $this->db->query("select clo_bl_flag from course where crs_id = '".$crs_id."' ");
            $result = $query->result_array();
			
			$query_count = $this->db->query('select * from map_clo_bloom_level as m join clo as cl on cl.clo_id = m.clo_id  where crs_id = "'. $crs_id .'"');
			$count_map_data = $query_count->result_array();

			if($result[0]['clo_bl_flag'] == '1' ||  (!empty($count_map_data))) {

            $this->db->SELECT(' DISTINCT(bl.bloom_id) , bl.level , bl.learning , bl.description , bl.bloom_actionverbs')
                    ->FROM('map_clo_bloom_level as cl')
                    ->JOIN('clo AS c', 'cl.clo_id = c.clo_id')
                    ->JOIN('bloom_level as bl' , 'bl.bloom_id = cl.bloom_id')
                    ->WHERE_IN('c.clo_id', $co_id)
                    ->WHERE('c.crclm_id', $crclm_id)
                    ->WHERE('c.crs_id', $crs_id)
                    ->GROUP_BY('c.clo_id  , bl.bloom_id');
                            $bl_list_result = $this->db->get()->result_array();
              $po_related_data['mapped_bl_res'] =  $bl_list_result;
            }else{
					
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
					}else{
						$bloom_lvl_query = 'select * from  bloom_level  ORDER BY LPAD(LOWER(level),5,0) ASC';
					}
					$bloom_lvl_data = $this->db->query($bloom_lvl_query);
					$bl_list_result = $bloom_lvl_data->result_array();
					$po_related_data['mapped_bl_res'] =  $bl_list_result;
            }
			return $po_related_data;
	}
	
    public function pi_list($co_id, $crclm_id, $term_id, $crs_id) {

        $this->db->select('copo.msr_id, m.msr_id, m.msr_statement, m.pi_codes')
                ->FROM('clo_po_map as copo')
                ->JOIN('measures as m', 'copo.msr_id = m.msr_id')
                ->WHERE_IN('copo.clo_id', $co_id)
                ->WHERE('copo.crclm_id', $crclm_id)
                ->WHERE('copo.crs_id', $crs_id)
                ->group_by('copo.msr_id');
        $mapped_pi_res = $this->db->get()->result_array();

        $qp_entity_query = 'SELECT qpf_config FROM entity WHERE entity_id = 22';
        $qp_entity_data = $this->db->query($qp_entity_query);
        $qp_entity_result = $qp_entity_data->result_array();

        $pi_related_data['qp_pi_entity_data'] = $qp_entity_result;
        $pi_related_data['mapped_pi_res'] = $mapped_pi_res;

        return $pi_related_data;
    }
	
	
	public function get_qpd_unit_id($qpd_id, $sec_name2) {
        $query = 'SELECT qpd_unitd_id FROM qp_unit_definition WHERE qpd_id="' . $qpd_id . '" AND qp_unit_code="' . $sec_name2 . '"';
        $query_exe = $this->db->query($query);
        $qpd_unit_id = $query_exe->result_array();
        return($qpd_unit_id);
    }
		
	
	public function insert_data_new($results) {

        $logged_in_uid = $this->ion_auth->logged_in();
        $res_one = $this->db->select('qp_utotal_marks')
                ->from('qp_unit_definition')
                ->where('qpd_unitd_id', $results['qpd_unitd_id'])
                //$this->db->join('category', 'category.id = articles.id'); 
                ->get()
                ->result_array();
        $res = $this->db->select('SUM(qp_subq_marks) as total, qp_utotal_marks')
                ->from('qp_unit_definition')
                ->join('qp_mainquestion_definition', 'qp_unit_definition.qpd_unitd_id=qp_mainquestion_definition.qp_unitd_id')
                ->where('qp_unitd_id', $results['sec_name'])
                //$this->db->join('category', 'category.id = articles.id'); 
                ->get()
                ->result_array();

        if (($res[0]['total'] + $results['mark']) <= ($res_one[0]['qp_utotal_marks'])) {
            if (strpos($results['question'], 'img') != false)
                $question = str_replace('"', "", $results['question']);
            else
                $question = str_replace('"', "&quot;", $results['question']);

            $result_qe = array('qp_unitd_id' => $results['qpd_unitd_id'],
                'qp_mq_flag' => $results['mandatory'],
                'qp_mq_code' => $results['qn'],
                'qp_subq_code' => $results['qes_num'],
                'qp_content' => $this->db->escape_str($question),
                'qp_mq_code' => $results['q_num'],
                'qp_subq_marks' => $results['mark'],
                'created_by' => $logged_in_uid,
                'created_date' => date('y-m-d'),
                'modified_by' => $logged_in_uid,
                'modified_date' => date('y-m-d')
            );
            $r = $this->db->insert('qp_mainquestion_definition', $result_qe);
            $question_id = $this->db->insert_id();
            $co_size = count($results['co']);
            $topic_size = count($results['topic']);
            $level_size = count($results['level']);
			$question_type_size = count($results['question_type']);

            $insert_query = 'INSERT INTO qp_mapping_definition(qp_mq_id,entity_id,actual_mapped_id,created_by,created_date,modified_by,modified_date) VALUES';
            for ($c = 0; $c < $co_size; $c++) {
                $insert_query.='("' . $question_id . '",11,"' . $results['co'][$c] . '","' . $logged_in_uid . '","' . date('y-m-d') . '","' . $logged_in_uid . '","' . date('y-m-d') . '"),';
            }


            $po_size = count($results['po']);
            for ($p = 0; $p < $po_size; $p++) {
                $insert_query.='("' . $question_id . '",6,"' . $results['po'][$p] . '","' . $logged_in_uid . '","' . date('y-m-d') . '","' . $logged_in_uid . '","' . date('y-m-d') . '"),';
            }

            $pi_size = count(@$results['pi']);
            if (!empty($results['pi'])) {
                for ($j = 0; $j < $pi_size; $j++) {
                    $insert_query.='("' . $question_id . '",22,"' . $results['pi'][$j] . '","' . $logged_in_uid . '","' . date('y-m-d') . '","' . $logged_in_uid . '","' . date('y-m-d') . '"),';
                }
            }

// Added by bhagya
            for ($t = 0; $t < $topic_size; $t++) {
                $insert_query.='("' . $question_id . '",10,"' . $results['topic'][$t] . '","' . $logged_in_uid . '","' . date('y-m-d') . '","' . $logged_in_uid . '","' . date('y-m-d') . '"),';
            }

            for ($bl = 0; $bl < $level_size; $bl++) {
                $insert_query.='("' . $question_id . '",23,"' . $results['level'][$bl] . '","' . $logged_in_uid . '","' . date('y-m-d') . '","' . $logged_in_uid . '","' . date('y-m-d') . '"),';
            }         
			
			for ($qt = 0; $qt < $question_type_size; $qt++) {
                $insert_query.='("' . $question_id . '",29,"' . $results['question_type'][$qt] . '","' . $logged_in_uid . '","' . date('y-m-d') . '","' . $logged_in_uid . '","' . date('y-m-d') . '"),';
            } 
			
            $insert_query = substr($insert_query, 0, -1);

            $this->db->query($insert_query);

            $proc_query = 'call updateQPWeightageDistribution(' . $question_id . ')';
            $proc_data = $this->db->query($proc_query);


            if ($r) {
                $query = 'select qpd_unitd_id from qp_unit_definition where qpd_id="' . $results['qpd_id'] . '"';
                $query_data = $this->db->query($query);
                $v = $query_data->result_array();
                $unit_size = count($v);
                $m = '';
                for ($i = 0; $i < $unit_size; $i++) {
                    $v1[$i] = $this->db->select('SUM(qp_subq_marks)')
                            ->where('qp_unitd_id', $v[$i]['qpd_unitd_id'])
                            ->get('qp_mainquestion_definition')
                            ->result_array();
                }
                $size = count($v1);
                for ($k = 0; $k < $size; $k++) {
                    (int) $m += $v1[$k][0]['SUM(qp_subq_marks)'];
                }

                $entity_query = 'SELECT qpf_config FROM entity WHERE entity_id = 6';
                $entity_data = $this->db->query($entity_query);
                $entity_result = $entity_data->result_array();
                $data['entity'] = $entity_result[0]['qpf_config'];
                $data['marks'] = $m;

                return $data;
            } else {

                return '-1'; //
            }
        } else {
            $data['marks'] = '-1'; //to return unit if full
            return $data;
        }
    }

    public function fetch_Mapping_data($main_que_id, $crclm_id, $term_id, $crs_id, $qpd_id, $unit_id) {
        $mandetory_query = 'SELECT * FROM qp_mainquestion_definition WHERE qp_mq_id="' . $main_que_id . '"';
        $mandetory_data = $this->db->query($mandetory_query);
        $mandatory_res = $mandetory_data->result_array();
        @$co_mapping_data = $this->db->select('actual_mapped_id')
                ->where('qp_mq_id', $main_que_id)
                ->where('entity_id', 11)
                ->get('qp_mapping_definition')
                ->result_array();

        $co_count = count($co_mapping_data);
        $co_array = Array();
        for ($i = 0; $i < $co_count; $i++) {
            $co_array[] = $co_mapping_data[$i]['actual_mapped_id'];
        }
        if (!empty($co_array)) {
            $this->db->select('copo.po_id, p.po_id, p.po_reference,p.po_statement')
                    ->FROM('clo_po_map as copo')
                    ->JOIN('po as p', 'copo.po_id = p.po_id')
                    ->WHERE_IN('copo.clo_id', @$co_array)
                    ->WHERE('copo.crclm_id', $crclm_id)
                    ->WHERE('copo.crs_id', $crs_id)
                    ->group_by('copo.po_id');
            @$mapped_po_res = $this->db->get()->result_array();
        } else {
            $mapped_po_res = 0;
        }


        $topic_mapping_data = $this->db->select('actual_mapped_id')
                ->where('qp_mq_id', $main_que_id)
                ->where('entity_id', 10)
                ->get('qp_mapping_definition')
                ->result_array();

        $level_mapping_data = $this->db->select('actual_mapped_id')
                ->where('qp_mq_id', $main_que_id)
                ->where('entity_id', 23)
                ->get('qp_mapping_definition')
                ->result_array();

        $pi_mapping_data = $this->db->select('actual_mapped_id')
                ->where('qp_mq_id', $main_que_id)
                ->where('entity_id', 22)
                ->get('qp_mapping_definition')
                ->result_array();

        $po_list = $this->db->select('actual_mapped_id')
                ->where('qp_mq_id', $main_que_id)
                ->where('entity_id', 6)
                ->get('qp_mapping_definition')
                ->result_array();
				
		$qt_list = $this->db->select('actual_mapped_id')
					->where('qp_mq_id' , $main_que_id)
					->where('entity_id' , 29)
					->get('qp_mapping_definition')
					->result_array();


        $po_array = Array();
        $po_array_size = count($po_list);
        for ($p = 0; $p < $po_array_size; $p++) {
            $po_array[] = $po_list[$p]['actual_mapped_id'];
        }
        if (!empty($po_array)) {
            $po_result = $this->db->select('po_id,po_reference,po_statement')
                    ->WHERE_IN('po_id', $po_array)
                    ->where('crclm_id', $crclm_id)
                    ->get('po')
                    ->result_array();
        } else {
            $po_result = NULL;
        }
		
		
		$qt_array = Array();
		$qt_array_size = count($qt_list);
		
		for($q =0 ; $q< $qt_array_size ; $q++){
			$qt_array[] = $qt_list[$q]['actual_mapped_id'];
		}
		if(!empty($qt_array)){
			$qt_result = $this->db->select('mt_details_id , mt_details_name')
						->WHERE_IN('mt_details_id' , @$qt_array)
						->get('master_type_details')
						->result_array();
			//@$mapped_po_res = $this->db->get()->result_array();
			
		}else{
			$qt_result = NULL;
		}

        $query = 'select qpd_unitd_id from qp_unit_definition where qpd_id="' . $qpd_id . '"';

        $query_data = $this->db->query($query);
        $v = $query_data->result_array();
        $unit_size = count($v);
        $m = '';

        for ($i = 0; $i < $unit_size; $i++) {
            $v1[$i] = $this->db->select('SUM(qp_subq_marks)')
                    ->where('qp_unitd_id', $v[$i]['qpd_unitd_id'])
                    ->get('qp_mainquestion_definition')
                    ->result_array();
        }
        $size = count($v1);
        for ($k = 0; $k < $size; $k++) {
            (int) $m += $v1[$k][0]['SUM(qp_subq_marks)'];
        }

        $entity_query = 'SELECT qpf_config FROM entity WHERE entity_id = 6';
        $entity_data = $this->db->query($entity_query);
        $entity_result = $entity_data->result_array();

        $res = $this->db->select('qp_utotal_marks')
                ->from('qp_unit_definition')
                ->where('qpd_unitd_id', $unit_id)
                ->get()
                ->result_array();
        $res1 = $this->db->select('SUM(qp_subq_marks)')
                ->where('qp_unitd_id', $unit_id)
                ->get('qp_mainquestion_definition')
                ->result_array();

        $que_max_marks = $this->db->query("SELECT MAX(secured_marks) as max_marks FROM student_assessment s where qp_mq_id='" . $main_que_id . "';");
        $que_max_marks_res = $que_max_marks->row();

        $qp_rollout = $this->db->query("SELECT qp_rollout FROM qp_definition where qpd_id='" . $qpd_id . "';");
        $qp_rollout_val = $qp_rollout->row();
        
        if($mandatory_res[0]['qp_mq_flag']){
            $qp_mq_flag = $mandatory_res[0]['qp_mq_flag'];
        }else{
            $qp_mq_flag = 0;
        }
        
        $qp_map_data = array(
            'co_map_data' => $co_mapping_data,
            'topic_mapping_data' => $topic_mapping_data,
            'level_mapping_data' => $level_mapping_data,
            'pi_mapping_data' => $pi_mapping_data,
            'po_list' => $po_list,
            'po_result' => @$po_result,
			'qt_result' => @$qt_result,
            'mapped_po_data' => @$mapped_po_res,
            'marks_total' => $m,
            'total_unit_marks' => $res,
            'total_attemted_marks' => $res1,
            'mandatory' => $qp_mq_flag,
            'qp_mq_code' => $mandatory_res[0]['qp_mq_code'],
            'entity_config' => $entity_result,
            'que_max_marks' => $que_max_marks_res->max_marks,
            'qp_rollout' => $qp_rollout_val->qp_rollout);								
        return $qp_map_data;
    }

    public function check_question_update($qpd_unitd_id, $qp_subq_code, $qp_mq_id) {

        $query = 'SELECT * from qp_mainquestion_definition where qp_unitd_id="' . $qpd_unitd_id . '" and qp_subq_code ="' . $qp_subq_code . '" and  qp_mq_id !="' . $qp_mq_id . '"';
        $query_re = $this->db->query($query);
        $count = $query_re->num_rows();
        $qpd_unitd_id = $query_re->result_array();
        return $count;
    }
	
	    /**
      Function to update the mainquestion
     * */
    public function update_qp($qp_unit_id, $qp_subq_code, $qp_subq_marks, $qp_mq_id, $qp_content, $co, $po, $topic, $bl, $pi, $mandatory, $qp_mq_code , $question_type) {
        $logged_in_uid = $this->ion_auth->logged_in();

        $unit_id_query = 'SELECT qp_unitd_id FROM qp_mainquestion_definition WHERE qp_mq_id ="' . $qp_mq_id . '"';
        $unit_id_data = $this->db->query($unit_id_query);
        $unit_id = $unit_id_data->row_array();

        $sum_unit_submarks_query = 'SELECT SUM(qp_subq_marks) as total FROM qp_mainquestion_definition WHERE qp_unitd_id = "' . $unit_id['qp_unitd_id'] . '" AND qp_mq_id != "' . $qp_mq_id . '"';
        $sum_unit_submarks = $this->db->query($sum_unit_submarks_query);
        $sum_unit_data = $sum_unit_submarks->result_array();

        $unit_max_marks_query = 'SELECT qp_utotal_marks,qpd_id FROM qp_unit_definition WHERE qpd_unitd_id = "' . $unit_id['qp_unitd_id'] . '"';
        $unit_marks = $this->db->query($unit_max_marks_query);
        $unit_total_marks = $unit_marks->row_array();

        if (($sum_unit_data[0]['total'] + $qp_subq_marks) <= $unit_total_marks['qp_utotal_marks']) {
            $update_qp = 'UPDATE qp_mainquestion_definition SET qp_mq_code="' . $qp_mq_code . '",qp_unitd_id="' . $qp_unit_id . '", qp_subq_code="' . $qp_subq_code . '",qp_subq_marks="' . $qp_subq_marks . '",qp_content="' . $this->db->escape_str($qp_content) . '", qp_mq_flag = "' . $mandatory . '"  WHERE qp_mq_id=' . $qp_mq_id . '';
            $re = $this->db->query($update_qp);

            $co_count = count($co);
            $delete_map_data = 'DELETE FROM qp_mapping_definition WHERE qp_mq_id= "' . $qp_mq_id . '"';
            $delete_data = $this->db->query($delete_map_data);

            $co_size = count($co);
            $insert_query = 'INSERT INTO qp_mapping_definition(qp_mq_id,entity_id,actual_mapped_id,created_by,created_date,modified_by,modified_date) VALUES';
            for ($c = 0; $c < $co_size; $c++) {
                $insert_query.='("' . $qp_mq_id . '",11,"' . $co[$c] . '","' . $logged_in_uid . '","' . date('y-m-d') . '","' . $logged_in_uid . '","' . date('y-m-d') . '"),';
            }

            $po_size = count($po);
            if (!empty($po)) {
                for ($p = 0; $p < $po_size; $p++) {
                    $insert_query.='("' . $qp_mq_id . '",6,"' . $po[$p] . '","' . $logged_in_uid . '","' . date('y-m-d') . '","' . $logged_in_uid . '","' . date('y-m-d') . '"),';
                }
            }

            $pi_size = count($pi);
            if (!empty($pi)) {
                for ($k = 0; $k < $pi_size; $k++) {
                    $insert_query.='("' . $qp_mq_id . '",22,"' . $pi[$k] . '","' . $logged_in_uid . '","' . date('y-m-d') . '","' . $logged_in_uid . '","' . date('y-m-d') . '"),';
                }
            }
            /* $insert_query.='("'.$qp_mq_id.'",23,"'.$bl.'","'.$logged_in_uid.'","'.date('y-m-d').'","'.$logged_in_uid.'","'.date('y-m-d').'"),';
              $insert_query.='("'.$qp_mq_id.'",10,"'.$topic.'","'.$logged_in_uid.'","'.date('y-m-d').'","'.$logged_in_uid.'","'.date('y-m-d').'")'; */

            $topic_size = count($topic);
            $level_size = count($bl);

            for ($t = 0; $t < $topic_size; $t++) {
                $insert_query.='("' . $qp_mq_id . '",10,"' . $topic[$t] . '","' . $logged_in_uid . '","' . date('y-m-d') . '","' . $logged_in_uid . '","' . date('y-m-d') . '"),';
            }

            for ($b = 0; $b < $level_size; $b++) {
                $insert_query.='("' . $qp_mq_id . '",23,"' . $bl[$b] . '","' . $logged_in_uid . '","' . date('y-m-d') . '","' . $logged_in_uid . '","' . date('y-m-d') . '"),';
            }
			
			$qt_size = count($question_type);
			
			for($qt = 0 ; $qt < $qt_size ; $qt++){
			
				$insert_query.='("' . $qp_mq_id . '",29,"' . $question_type[$qt] . '","' . $logged_in_uid . '","' . date('y-m-d') . '","' . $logged_in_uid . '","' . date('y-m-d') . '"),';
			}

            $insert_query = substr($insert_query, 0, -1);
            $this->db->query($insert_query);

            $proc_query = 'call updateQPWeightageDistribution(' . $qp_mq_id . ')';
            $proc_data = $this->db->query($proc_query);
            $data['value'] = 0;
            return $data;
        } else {
            $data['value'] = -1;
            return $data;
        }
    }
	
	
    public function deleteUser_Roles($i) {

        $this->db->trans_start();
        $this->db->query('DELETE FROM qp_mainquestion_definition WHERE qp_mq_id = ' . $i . ' ');
        $this->db->query('DELETE FROM qp_mapping_definition WHERE qp_mq_id = ' . $i . ' ');
        //$this->db->query('DELETE FROM map_user_dept_role WHERE user_id = '.$user_id.' and dept_id = '.$base_dept_id);		
        $this->db->trans_complete();
        return TRUE;
    }
	
	    /*
     * Function to get the review and aissingment questions
     */
    public function get_rev_assin_questions($crs_id,$que_type){
	
        $this->db->SELECT('que.question_id, que.review_question, que.assignment_question, que.curriculum_id, que.term_id, '
                . 'que.course_id, que.topic_id, que.tlo_id, que.bloom_id, que.pi_codes, que.created_by, que.modified_by, '
                . 'que.created_date, que.modified_date, que.que_id,top.topic_id,top.topic_title,crs.crs_id,crs.crs_title')
                ->FROM('topic_question as que')
                ->JOIN('topic as top','que.topic_id=top.topic_id')
                ->JOIN('course as crs','crs.crs_id=que.course_id')
                ->WHERE('que.course_id',$crs_id)->WHERE('que_id',$que_type);
        
        $question_query = $this->db->get()->result_array();
       
       return $question_query;
       
    }
    /*
      Function to delete individual TEE qp data.
      @param : curriculum id, term id, course id and QP definition id to delete particular QP.
      @return : Success Message.
     */

    public function mte_qp_delete($crclm_id, $term_id, $crs_id, $qp_type, $qpd_id) {
        $qp_def_delete_query = 'DELETE FROM qp_definition WHERE qpd_id = "' . $qpd_id . '" AND qpd_type = "' . $qp_type . '" AND crclm_id = "' . $crclm_id . '" AND crclm_term_id ="' . $term_id . '" AND crs_id = "' . $crs_id . '"';
        $qpd_id_res = $this->db->query($qp_def_delete_query);
		
		$query = $this->db->query('DELETE FROM assessment_occasions where qpd_id = "' . $qpd_id . '"  AND crclm_id = "' . $crclm_id . '" AND term_id ="' . $term_id . '" AND crs_id = "' . $crs_id . '" ');
		
        return true;
    }
	
	
	  public function model_mte_qp_details($crclm_id, $term_id, $crs_id, $qp_type, $qpd_id) {


        $model_qp_meta_data_query = 'SELECT qpd.qpf_id, qpd.qpd_title, qpd.qpd_timing, qpd.qpd_gt_marks,qpd.qpd_max_marks, qpd.qpd_notes, qpd.qpd_num_units, crs.crs_title,crs.crs_code
			FROM qp_definition AS qpd
		JOIN course AS crs ON qpd.crs_id = crs.crs_id
		WHERE qpd.crclm_id = "' . $crclm_id . '"
		AND qpd.crclm_term_id = "' . $term_id . '"
		AND qpd.crs_id = "' . $crs_id . '" 
		AND qpd.qpd_type = "' . $qp_type . '" 
		AND qpd.qpd_id = "' . $qpd_id . '"';

        $model_qp_meta_data = $this->db->query($model_qp_meta_data_query);
        $model_qp_meta_data_res = $model_qp_meta_data->result_array();
        $val['qp_meta_data'] = $model_qp_meta_data_res;

        $query = 'SELECT qpd_unitd_id from qp_unit_definition where qpd_id="' . $qpd_id . '"';
        $query_re = $this->db->query($query);
        $count = $query_re->num_rows();
        $qpd_unitd_id = $query_re->result_array();

        for ($i = 0; $i < $count; $i++) {


            $data1 = 'select * from qp_unit_definition as qm
					join qp_mainquestion_definition as q ON qm.qpd_unitd_id=q.qp_unitd_id
					where q.qp_unitd_id="' . $qpd_unitd_id[$i]['qpd_unitd_id'] . '"
					and  qm.qpd_id = "' . $qpd_id . '"
					ORDER BY CAST(q.qp_subq_code AS UNSIGNED), q.qp_subq_code ASC';
            //ORDER BY CAST(q.qp_subq_code AS UNSIGNED), q.qp_subq_code ASC'

            $data1 = $this->db->query($data1);
            $data[$i] = $data1->result_array();
            $size = count($data[$i]);
            for ($j = 0; $j < $size; $j++) {
                $co_query = 'SELECT qp_map.actual_mapped_id, co.clo_code, co.clo_statement FROM qp_mapping_definition as qp_map 
								JOIN clo as co ON co.clo_id = qp_map.actual_mapped_id
								WHERE qp_map.qp_mq_id = "' . $data[$i][$j]['qp_mq_id'] . '" AND qp_map.entity_id =11';
                $co_data = $this->db->query($co_query);
                $co_result[] = $co_data->result_array();
            }

            for ($p = 0; $p < $size; $p++) {
                $po_query = 'SELECT qp_map.actual_mapped_id, po.po_reference, po.po_statement FROM qp_mapping_definition as qp_map 
								JOIN po as po ON po.po_id = qp_map.actual_mapped_id
								WHERE qp_map.qp_mq_id = "' . $data[$i][$p]['qp_mq_id'] . '" AND qp_map.entity_id =6';
                $po_data = $this->db->query($po_query);
                $po_result[] = $po_data->result_array();
            }

            for ($t = 0; $t < $size; $t++) {
                $topic_query = 'SELECT qp_map.actual_mapped_id, top.topic_title FROM qp_mapping_definition as qp_map 
								JOIN topic as top ON top.topic_id = qp_map.actual_mapped_id
								WHERE qp_map.qp_mq_id = "' . $data[$i][$t]['qp_mq_id'] . '" AND qp_map.entity_id =10';
                $topic_data = $this->db->query($topic_query);
                $topic_result[] = $topic_data->result_array();
            }

            for ($l = 0; $l < $size; $l++) {
                $level_query = 'SELECT qp_map.actual_mapped_id, bl.level, bl.bloom_actionverbs FROM qp_mapping_definition as qp_map 
								JOIN bloom_level as bl ON bl.bloom_id = qp_map.actual_mapped_id
								WHERE qp_map.qp_mq_id = "' . $data[$i][$l]['qp_mq_id'] . '" AND qp_map.entity_id = 23';
                $level_data = $this->db->query($level_query);
                $level_result[] = $level_data->result_array();
            }

            for ($pi = 0; $pi < $size; $pi++) {
                $pi_query = 'SELECT qp_map.actual_mapped_id, pi.pi_codes FROM qp_mapping_definition as qp_map 
								JOIN measures as pi ON pi.msr_id = qp_map.actual_mapped_id
								WHERE qp_map.qp_mq_id = "' . $data[$i][$pi]['qp_mq_id'] . '" AND qp_map.entity_id =22';
                $pi_data = $this->db->query($pi_query);
                $pi_result[] = $pi_data->result_array();
            }
			for ($qt = 0; $qt < $size; $qt++) {
                $qt_query = 'SELECT q.actual_mapped_id , m.mt_details_name from qp_mapping_definition as q
							JOIN master_type_details as m on m.mt_details_id = q.actual_mapped_id 
								WHERE q.qp_mq_id = "' . $data[$i][$qt]['qp_mq_id'] . '" AND q.entity_id =29';
                $qt_data = $this->db->query($qt_query);
                $qt_result[] = $qt_data->result_array();
            }
        }


        $co_data_array = Array();
        $co_siz = 0;
        if (!empty($co_result)) {
            $co_siz = count($co_result);
        }
        for ($j = 0; $j < $co_siz; $j++) {
            $count = count($co_result[$j]);
            if (!empty($count)) {
                for ($k = 0; $k < $count; $k++) {
                    $co_data_array[$j][] = $co_result[$j][$k]['clo_code'];
                }
                $co_data_string[] = implode(",", $co_data_array[$j]);
            } else {
                $co_data_string[] = " ";
            }
        }

        $po_data_array = Array();
        $po_siz = 0;
        if (!empty($po_result)) {
            $po_siz = count($po_result);
        }
        for ($po = 0; $po < $po_siz; $po++) {
            $po_count = count($po_result[$po]);
            for ($p = 0; $p < $po_count; $p++) {
                $po_data_array[$po][] = $po_result[$po][$p]['po_reference'];
            }
            if (!empty($po_data_array) && (!empty($po_data_array[$po]))) {
				if($po_data_array[$po] != ""){
					$po_data_string[] = implode(",", $po_data_array[$po]);
				}else{ $po_data_string[] ="";}
            }
        }

        $topic_data_array = Array();
        $top_siz = 0;
        if (!empty($topic_result)) {
            $top_siz = count($topic_result);
        }

        for ($t = 0; $t < $top_siz; $t++) {
            if (!empty($topic_result[$t])) {
                $top_count = count($topic_result[$t]);
                for ($top = 0; $top < $top_count; $top++) {
                    $topic_data_array[$t][] = $topic_result[$t][$top]['topic_title'];
                }
                $topic_data_string[] = implode(",", $topic_data_array[$t]);
            } else {
                $topic_data_string[] = NULL;
            }
        }

        $level_data_array = Array();
        $lev_siz = 0;
        if (!empty($level_result)) {
            $lev_siz = count($level_result);
        }
        for ($lv = 0; $lv < $lev_siz; $lv++) {
            $lev_count = count($level_result[$lv]);
            if (!empty($lev_count)) {
                for ($lev = 0; $lev < $lev_count; $lev++) {
                    $level_data_array[$lv][] = $level_result[$lv][$lev]['level'];
                }
                $level_data_string[] = implode(",", $level_data_array[$lv]);
            } else {
                $level_data_string[] = " ";
            }
        }


        $pi_data_array = Array();
        $pi_siz = 0;
        if (!empty($pi_result)) {
            $pi_siz = count($pi_result);
        }
        for ($pi = 0; $pi < $pi_siz; $pi++) {
            if (!empty($pi_result[$pi])) {
                $pi_count = count($pi_result[$pi]);
                for ($pc = 0; $pc < $pi_count; $pc++) {
                    $pi_data_array[$pi][] = $pi_result[$pi][$pc]['pi_codes'];
                }
                $pi_data_string[] = implode(",", $pi_data_array[$pi]);
            } else {
                $pi_data_string[] = NULL;
            }
        }
		
		$qt_data_array = Array();
		if(!empty($qt_result)){
			$pi_siz = count($qt_result);
			for ($qt1 = 0; $qt1 < $pi_siz; $qt1++) {
				if (!empty($qt_result[$qt1])) {
					$qt_count = count($qt_result[$qt1]);
					for ($qt2 = 0; $qt2 < $qt_count; $qt2++) {
						$qt_data_array[$qt1][] = $qt_result[$qt1][$qt2]['mt_details_name'];
					}
					$qt_data_string[] = implode(",", $qt_data_array[$qt1]);
				} else {
					$qt_data_string[] = NULL;
				}
			}
		}else{
			$qt_data_string[] = NULL;
		}
		
		
        if (!empty($co_data_string)) {
            $val['co_data'] = $co_data_string;
        }
        if (!empty($po_data_string)) {
            $val['po_data'] = $po_data_string;
        }
        if (!empty($topic_data_string)) {
            $val['topic_data'] = $topic_data_string;
        }
        if (!empty($level_data_string)) {
            $val['level_data'] = $level_data_string;
        }
        if (!empty($pi_data_string)) {
            $val['pi_data'] = $pi_data_string;
        }  
		
		if (!empty($qt_data_string)) {
			$val['qt_data'] = $qt_data_string;
        }


        $val['question_paper_data'] = $data;

        $val['dat1'] = $qpd_unitd_id;
        return $val;
    }
	
	 /* Function - To get data from procedure call for BloomsLevelPlannedCoverageDistribution
     * @param - course id and question paper id
     * returns - 
     */

    public function getBloomsLevelPlannedCoverageDistribution($crs, $qid, $qp_type) {
	if ($qp_type == 5) {
            $r = $this->db->query("call getBloomsLevelPlannedCoverageDistribution(" . $crs . "," . $qid . ",'TEE')");
        } else if ($qp_type == 4) {
            $r = $this->db->query("call getBloomsLevelPlannedCoverageDistribution(" . $crs . "," . $qid . ",'MDL')");
        } else {
            $r = $this->db->query("call getBloomsLevelPlannedCoverageDistribution(" . $crs . "," . $qid . ",'CIA')");
        }
        return $r->result_array();
    }

//End of Function getBloomsLevelPlannedCoverageDistribution

    /* Function - To get data from procedure call for BloomsLevelMarksDistribution
     * @param - course id and question paper id
     * returns - 
     */

    public function getBloomsLevelMarksDistribution($crs, $qid, $qp_type) {
	
        if ($qp_type == 5) {
            $r = $this->db->query("call getBloomsLevelMarksDistribution(" . $crs . "," . $qid . ",'TEE')");
            return $r->result_array();
        } else if ($qp_type == 4) {
            $r = $this->db->query("call getBloomsLevelMarksDistribution(" . $crs . "," . $qid . ",'MDL')");
            return $r->result_array();
        } else {

            $r = $this->db->query("call getBloomsLevelMarksDistribution(" . $crs . "," . $qid . ",'CIA')");
            return $r->result_array();
        }
    }

//End of function getBloomsLevelMarksDistribution

    /* Function - To get data from procedure call for BloomsLevelMarksDistribution_custom
     * @param - course id and question paper id
     * returns - 
     */

    public function getBloomsLevelMarksDistribution_custom($crs, $qid) {
        $r = $this->db->query("call getBloomsLevelMarksDistribution_custom(" . $crs . "," . $qid . ")");
        return $r->result_array();
    }

//End of function getBloomsLevelMarksDistribution

    /* Function - To get data from procedure call for COLevelMarksDistribution
     * @param - course id and question paper id
     * returns - 
     */

    public function getCOLevelMarksDistribution($crs, $qid) {
        $r = $this->db->query("call getCOLevelMarksDistribution(" . $crs . "," . $qid . ")");
        return $r->result_array();
    }//End of function getCOLevelMarksDistribution

    /* Function - To get data from procedure call for getTopicCoverageDistribution
     * @param - course id and question paper id
     * returns - 
     */

    public function getTopicCoverageDistribution($crs, $qid) {
        $r = $this->db->query("call getTopicCoverageDistribution(" . $crs . "," . $qid . ")");
        return $r->result_array();
    }
	
	public function list_dept() {
        $dept_name = 'SELECT DISTINCT dept_id, dept_name
						  FROM department 
						  WHERE status = 1
						  ORDER BY dept_name ASC';
        $department_result = $this->db->query($dept_name);
        $department_data = $department_result->result_array();
        $dept_data['dept_result'] = $department_data;
        return $dept_data;
    }

	    /*
     * Function is to fetch the term details.
     * @param - -----.
     * returns list of term names.
     */

    public function term_drop_down_fill($crclm_id) {
        return $this->db->select('crclm_term_id, term_name')
                        ->where('crclm_id', $crclm_id)
                        ->get('crclm_terms')
                        ->result_array();
    }
	
	    public function course_fill($crclm_id, $term, $to_crs_id) {
        $course_list = 'SELECT * FROM course WHERE crclm_id = "' . $crclm_id . '" AND crclm_term_id = "' . $term . '" AND state_id >= 4 ';
        $course_list_data = $this->db->query($course_list);
        $course_list_res = $course_list_data->result_array();
        return $course_list_res;
    }
	
	   /*
     * Function to get the list of question paper.
     */

    public function fetch_qp_list($dept_id, $pgm_id, $crclm_id, $term_id, $crs_id, $ao_id) {

        $this->db->SELECT('ao.ao_id as aoid, ao.ao_name as aoname, ao.ao_description as ao_desc,qpd.qpd_id, qpd.qpf_id, qpd.qpd_type, '
                        . ' qpd.cia_model_qp, qpd.qp_rollout, qpd.crclm_id, qpd.crclm_term_id, qpd.crs_id, qpd.qpd_title, '
                        . ' qpd.qpd_timing, qpd.qpd_gt_marks, qpd.qpd_max_marks, qpd.qpd_notes, qpd.qpd_num_units, qpd.created_by, '
                        . ' qpd.created_date, qpd.modified_by, qpd.modified_date, mtd.mt_details_id, mtd.mt_details_name as section_name ')
                ->FROM('qp_definition as qpd')
                ->JOIN('assessment_occasions as ao', 'ao.qpd_id=qpd.qpd_id')
                ->JOIN('master_type_details as mtd', 'mtd.mt_details_id = ao.section_id')
                ->WHERE('qpd.crclm_id', $crclm_id)
                ->WHERE('qpd.crclm_term_id', $term_id)
                ->WHERE('qpd.crs_id', $crs_id)
                ->WHERE('qpd.qpd_type', 3)
                ->WHERE('qpd.qpd_title != "CIA Individual Assessment"')
                ->WHERE('qpd.qpd_title != "Rubrics Based Minor Question Paper"')
                ->ORDER_BY('ao.ao_name');
        $qp_result_val = $this->db->get()->result_array();
        return $qp_result_val;
    }
	
	public function existance_of_qp($qpd_id, $ao_id, $crclm_id, $term_id, $crs_id) {
        $check_count = 'SELECT COUNT(qpd_id) as size FROM assessment_occasions '
                . 'WHERE ao_id ="' . $ao_id . '" ';
        $check_data = $this->db->query($check_count);
        $check_res = $check_data->row_array();
        return $check_res;
    }
	
	
    public function get_qp_data_import($qpd_id, $ao_id, $crclm_id, $term_id, $crs_id) {

	/* 
	$check_empty_occasion = $this->db->query('select * from assessment_occasions where crclm_id = "'. $crclm_id .'" and term_id = "'. $term_id.'"  and crs_id = "'. $crs_id .'" ');
	$result_empty_occasion = $check_empty_occasion->result_array();
	
	if(!empty($result_empty_occasion))  */
	{
/*         $get_the_qp_id_query = 'SELECT qpd_id FROM assessment_occasions WHERE ao_id = "' . $ao_id . '" ';
        $qp_id_data = $this->db->query($get_the_qp_id_query);
        $ao_qpd_id = $qp_id_data->row_array();
        
        if ($ao_qpd_id['qpd_id'] == $qpd_id) {
          
            $data_val = 'false';
            return $data_val;
        } else */

		{
            
           /*  $delete_query = 'DELETE FROM qp_definition WHERE '
                    . 'crclm_id="' . $crclm_id . '" '
                    . 'AND crclm_term_id = "' . $term_id . '" '
                    . 'AND crs_id = "' . $crs_id . '" '
                    . 'AND qpd_type=3 '
                    . 'AND qpd_id = "' . $ao_qpd_id['qpd_id'] . '" ';
            $delete_data = $this->db->query($delete_query); */
            
            $date = date('y-m-d');
            $loggedin_user_id = $this->ion_auth->user()->row()->id;
            $new_qp_id = $this->db->query('SELECT import_createQP(' . $qpd_id . ',3,' . $crclm_id . ',' . $term_id . ',' . $crs_id . ',' . $loggedin_user_id . ',' . $date . ') as new_qp_id')->row_array();

            $qp_unit_def_query = 'SELECT qpd_unitd_id, qpd_id, qp_unit_code, qp_total_unitquestion, qp_attempt_unitquestion, qp_utotal_marks, created_by, created_date, modified_by, modified_date, FM FROM qp_unit_definition WHERE qpd_id = "' . $new_qp_id['new_qp_id'] . '" ';
            $qp_unit_def_data = $this->db->query($qp_unit_def_query);
            $qp_unit_def_result = $qp_unit_def_data->result_array();
            $qp_unit_def_size = count($qp_unit_def_result);


            for ($k = 0; $k < $qp_unit_def_size; $k++) {
                $qp_unit_id[] = $qp_unit_def_result[$k]['qpd_unitd_id'];
            }

            $this->db->SELECT('DISTINCT(map.clo_id),cl.clo_code')
                    ->FROM('clo_po_map as map')
                    ->JOIN('clo as cl', 'cl.clo_id = map.clo_id')
                    ->WHERE('map.crs_id', $crs_id)
                    ->ORDER_BY('map.clo_id');
            $crs_co_data = $this->db->get()->result_array();
            $to_co_size = count($crs_co_data);

            $this->db->SELECT('DISTINCT(map.po_id),p.po_reference')
                    ->FROM('clo_po_map as map')
                    ->JOIN('po as p', 'p.po_id = map.po_id')
                    ->WHERE('map.crs_id', $crs_id)
                    ->ORDER_BY('map.po_id');
            $po_data = $this->db->get()->result_array();

            $to_po_size = count($po_data);

            $this->db->SELECT('qp_main.qp_mq_id, qp_main.qp_subq_marks, qp_main.created_by, qp_main.created_date, qp_map.qp_map_id, qp_map.actual_mapped_id, qp_map.mapped_percentage, cl.clo_code,cl.clo_id')
                    ->FROM('qp_mainquestion_definition as qp_main')
                    ->JOIN('qp_mapping_definition as qp_map', 'qp_map.qp_mq_id = qp_main.qp_mq_id')
                    ->JOIN('clo as cl', 'qp_map.actual_mapped_id = cl.clo_id')
                    ->WHERE_IN('qp_main.qp_unitd_id', $qp_unit_id)
                    ->WHERE('qp_map.entity_id', '11')
                    ->ORDER_BY('qp_map.qp_map_id');
            $qp_map_co_data = $this->db->get()->result_array();

            $this->db->SELECT('qp_main.qp_mq_id,qp_main.qp_subq_marks, qp_main.created_by, qp_main.created_date, qp_map.qp_map_id, qp_map.actual_mapped_id,qp_map.mapped_percentage, p.po_reference,p.po_id')
                    ->FROM('qp_mainquestion_definition as qp_main')
                    ->JOIN('qp_mapping_definition as qp_map', 'qp_map.qp_mq_id = qp_main.qp_mq_id')
                    ->JOIN('po as p', 'qp_map.actual_mapped_id = p.po_id')
                    ->WHERE_IN('qp_main.qp_unitd_id', $qp_unit_id)
                    ->WHERE('qp_map.entity_id', '6')->ORDER_BY('qp_map.qp_map_id');
            $qp_map_po_data = $this->db->get()->result_array();
            $co_size = count($qp_map_co_data);

            for ($co_i = 0; $co_i < $co_size; $co_i++) {
                for ($co_j = 0; $co_j < $to_co_size; $co_j++) {
                    if ($qp_map_co_data[$co_i]['clo_code'] == $crs_co_data[$co_j]['clo_code']) {
                        $update_query = 'UPDATE qp_mapping_definition SET actual_mapped_id = "' . $crs_co_data[$co_j]['clo_id'] . '" '
                                . 'WHERE qp_map_id = "' . $qp_map_co_data[$co_i]['qp_map_id'] . '" AND entity_id = 11';
                        $update_data = $this->db->query($update_query);

                        $po_select_query = 'SELECT DISTINCT(po_id) FROM clo_po_map WHERE clo_id = ' . $crs_co_data[$co_j]['clo_id'] . '  AND crs_id=' . $crs_id . ' ';
                        $po_id_data = $this->db->query($po_select_query);
                        $po_id_res = $po_id_data->result_array();
                        foreach ($po_id_res as $po_id) {
//                                    $po_update_query = 'UPDATE qp_mapping_definition SET actual_mapped_id = '.$po_id['po_id'].' '
//                                   . 'WHERE qp_map_id = '.$qp_map_po_data[$co_i]['qp_map_id'].' AND entity_id = 6';
//                                    $po_update = $this->db->query($po_update_query);

                            $po_insert_array = array(
                                'qp_mq_id' => $qp_map_co_data[$co_i]['qp_mq_id'],
                                'entity_id' => 6,
                                'actual_mapped_id' => $po_id['po_id'],
                                'created_by' => $qp_map_co_data[$co_i]['created_by'],
                                'created_date' => $qp_map_co_data[$co_i]['created_date'],
                                'modified_by' => $loggedin_user_id,
                                'modified_date' => date('y-m-d'),
                                'mapped_marks' => $qp_map_co_data[$co_i]['qp_subq_marks'],
                                'mapped_percentage' => $qp_map_co_data[$co_i]['mapped_percentage'],
                            );
                            $this->db->insert('qp_mapping_definition', $po_insert_array);
                        }
                    }
                }
            }

			$get_qp_max_marks =  $this->db->query('SELECT * from qp_definition WHERE qpd_id = "'.$new_qp_id['new_qp_id'].'" ');
            $qp_max_mark =$get_qp_max_marks->result_array();
			
			
			    $logged_in_uid = $this->ion_auth->logged_in();
				$ao_id_fetch = $this->db->query('SELECT ao_name FROM assessment_occasions where crs_id = "'. $crs_id .'" ORDER BY ao_id DESC LIMIT 1;');
				$ao_id_old = $ao_id_fetch->result_array(); 
				$ao_name = 1;
				if( !empty($ao_id_old)){  $ao_name = $ao_id_old[0]['ao_name'] + (int) 1 ;} else  { $ao_name = 1;}
				$mte_occasion  = 'mte_occasion_'. ($ao_name);
				$assessment_occasion = array(
					'mte_flag' => 1,
					'qpd_id'   => $new_qp_id['new_qp_id'], 
					'ao_name'  => $ao_name,
					'ao_description' => $mte_occasion,
					'ao_method_id'   => '',
					'ao_type_id'	=> 1,
					'max_marks'		=> $qp_max_mark[0]['qpd_max_marks'] , 
					'crclm_id' => $crclm_id,
					'term_id' => $term_id,
					'crs_id' => $crs_id,
					'section_id' => '',
					'created_by' => $logged_in_uid,
					'created_date' => date('y-m-d'),
					'modified_by' => $logged_in_uid,
					'modified_date' => date('y-m-d'),
					'status' => 1,
					'rubrics_qp_status' => ''										
			);
			$this->db->insert('assessment_occasions', $assessment_occasion);
            
            $update_qp_def_query = 'UPDATE qp_definition SET qp_rollout = 1 WHERE qpd_id = "'.$new_qp_id['new_qp_id'].'" ';
            $update_qp_def = $this->db->query($update_qp_def_query);
            
            $result_data = 'true';
        }
	}/* else{
	
	
	
	} */
        return $result_data;
    }
	
	public function check_topic_defined_or_not($course_id){
	
		$query = $this->db->query('SELECT * FROM topic t where course_id = "'. $course_id.'"');
		return $query->result_array();
	}
	
	public function check_topic_status(){
	
		$query = $this->db->query('select qpf_config from entity where entity_id = 10');
		$re = $query->result_array();
		return $re[0]['qpf_config'];
	}
	
	public function course_bloom_status($crclm_id , $term_id , $crs_id){
	
		$query = $this->db->query('SELECT count(map_clo_bloom_level_id) as count_val , cr.clo_bl_flag  FROM map_clo_bloom_level m
									join clo as c on m.clo_id = c.clo_id
									join course as cr on cr.crs_id = c.crs_id
									where  c.crclm_id = "'. $crclm_id.'" AND  c.term_id = "'. $term_id .'" AND c.crs_id = "'. $crs_id .'"');
		return $query->result_array();
	}
	
	    public function update_FM($result, $unit_ids, $no_q, $sub_marks, $qpd_id, $unit_marks) {


        $query = 'select * from qp_unit_definition where qpd_id="' . $qpd_id . '"';
        $query_re = $this->db->query($query);

        $data = $query_re->result_array();
        $count = $query_re->num_rows();
        $marks_U = 0;
        $marks_B = 0;

        for ($j = 0; $j < $count; $j++) {
            ($marks_U+=$sub_marks[$j]);
            $marks_B+=$data[$j]['qp_utotal_marks'];
        }
        $count = $query_re->num_rows();
        $re_data = '';

        for ($i = 0; $i < $count; $i++) {

            $select_query = 'SELECT SUM(mqd.qp_subq_marks) as total_marks, uc.qp_utotal_marks, count(mqd.qp_mq_id) as count_val, uc.qp_unit_code FROM qp_mainquestion_definition as mqd join qp_unit_definition as uc ON uc.qpd_unitd_id = mqd.qp_unitd_id where qp_unitd_id = "' . $unit_ids[$i] . '"';

            $query_data = $this->db->query($select_query);
            $query_result = $query_data->row_array();

            if ($query_result['total_marks'] <= $sub_marks[$i]) {
                $update_FM = 'UPDATE qp_unit_definition SET qp_unit_code="' . $result[$i] . '" ,qp_total_unitquestion="' . $no_q[$i] . '", qp_utotal_marks="' . $sub_marks[$i] . '" where qpd_id="' . $qpd_id . '" AND qpd_unitd_id = "' . $unit_ids[$i] . '" ';
                $update_data = $this->db->query($update_FM);
                $re_data['qp_unit_result'] = '0';
            } else {
                $re_data['qp_unit_result'] = '-1';
                break;
                $re_data['section_name'] = $query_result['qp_unit_code'];
            }
        }
        return $re_data;
    }
	

	
	    /**
      Function to Update The header section of model question paper
      qpd_title	qpd_timing	qpd_gt_marks	qpd_max_marks	qpd_notes
     * */
    public function update_header($results) {

        if (empty($results['qp_title']) == true || empty($results['time']) == true || empty($results['Grand_total']) == true) {
            $re = "false";
        } else {
            $update_h = 'UPDATE qp_definition SET qpd_title="' . $results["qp_title"] . '", cia_model_qp="' . $results["qp_model"] . '",qpd_timing="' . $results["time"] . '",qpd_gt_marks="' . $results["Grand_total"] . '",qpd_max_marks="' . $results["max_marks"] . '",qpd_notes="' . $results["qp_notes"] . '"  where qpd_id="' . $results["qpd_id"] . '"';
            $re = $this->db->query($update_h);
        }
        return $re;
    }
	
	/**
	 * Function is to fetch file_name from qp_tee_upload
	 * @parameters: qpd_id
	 * @return: file_name
	 */
	public function check_file_uploaded($qpd_id){
		$query = $this->db->query('select file_name from qp_upload where qpd_id =  "'.$qpd_id .'"');
		return $query->result_array();		
	}
}