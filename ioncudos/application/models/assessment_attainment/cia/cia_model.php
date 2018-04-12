<?php

/* ----------------------------------------------------------------------------------------------
 * Description	: Manage CIA Occasions list, add, edit - model
 * Created By   : Arihant Prasad
 * Created Date : 10-09-2015
 * Date							Modified By						Description
   23-3-2016				   Mritunjay B. S.				New functions has been included
   12-9-2016				   Arihant Prasad				Code cleanup
  ---------------------------------------------------------------------------------------------------------
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cia_model extends CI_Model {

    /**
     * Function is to fetch department details
     * @parameters: 
     * @returns: department details
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
	
	public function get_dept_list(){
		$dept_name = 'SELECT DISTINCT dept_id, dept_name
						  FROM department 
						  WHERE status = 1
						  ORDER BY dept_name ASC';
			$department_result = $this->db->query($dept_name);
			$department_data = $department_result->result_array();
			$dept_data['dept_result'] = $department_data;

        return $dept_data;
	}

    /**
     * Function is to fetch program details
     * @parameters:
     * @returns: program details
     */
    public function pgm_fill($dept_id) {
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

    /**
     * Function is to fetch curriculum details
     * @parameters: program id
     * returns: curriculum details
     */
    public function crlcm_drop_down_fill($pgm_id) {
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
		} else {
			$curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name
				FROM curriculum AS c, users AS u, program AS p , map_courseto_course_instructor AS map
				WHERE u.id = "' . $loggedin_user_id . '" 
				AND u.user_dept_id = p.dept_id
				AND c.pgm_id = p.pgm_id
				AND u.id = map.course_instructor_id
				AND c.crclm_id = map.crclm_id
				AND c.status = 1
				AND c.pgm_id = "'.$pgm_id.'"
				ORDER BY c.crclm_name ASC';
		}

		$curriculum_list_data = $this->db->query($curriculum_list);
		return $curriculum_list_data->result_array();
    }

	/**
	 * Function to
	 *
	 *
	 */
	public function get_crclm_list($pgm_id){
		return $this->db->select('crclm_id, crclm_name')
                        ->where('pgm_id', $pgm_id)
                        ->order_by('crclm_name', 'ASC')
                        ->get('curriculum')
                        ->result_array();
	}

    /**
     * Function is to fetch term details.
     * @parameters: curriculum id
     * @returns list of term names.
     */
    public function term_drop_down_fill($curriculum_id) {
		$loggedin_user_id = $this->ion_auth->user()->row()->id;
		
		if($this->ion_auth->in_group('Course Owner') && !$this->ion_auth->in_group('admin') && !$this->ion_auth->in_group('Chairman') && !$this->ion_auth->in_group('Program Owner')) {
			$term_list_query = 'SELECT DISTINCT ct.crclm_id, ct.term_name, ct.crclm_term_id ,map.course_instructor_id 
								FROM map_courseto_course_instructor AS map, crclm_terms AS ct
								WHERE map.crclm_term_id = ct.crclm_term_id
								  AND map.course_instructor_id ="'.$loggedin_user_id.'"
								  AND map.crclm_id = "'.$curriculum_id.'"';
		} else {
			 $term_list_query = 'SELECT term_name, crclm_term_id 
								 FROM crclm_terms 
								 WHERE crclm_id = "' . $curriculum_id . '"';
		}
		
		$term_list_data = $this->db->query($term_list_query);
		return $term_list_data->result_array();
    }
	
	/**
	 * Function to
	 *
	 *
	 */
	public function get_term_list($crclm_id){
			$term_list_query = 'SELECT term_name, crclm_term_id 
					  FROM crclm_terms 
					  WHERE crclm_id = "' . $crclm_id . '"';
			$term_list_data = $this->db->query($term_list_query);
			return $term_list_data->result_array();
	}

    /**
     * Function is to fetch course details.
     * @parameters: curriculum id, term id
     * returns: course details
     */
    public function course_list($crclm_id, $term_id) {
        $user = $this->ion_auth->user()->row()->id;

        if ($this->ion_auth->in_group('admin') || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')) {
            $crs_fetch_query = 'SELECT map_ci.course_instructor_id, map_ci.crs_id, map_ci.section_id,
                                crs.crs_title, crs_code, crs.crs_id, crs.crs_mode,
                                crs_type.crs_type_name,
                                mtd.mt_details_name as section_name,
                                usr.username,usr.title,usr.first_name,usr.last_name,usr.id,
                                ao.status as ao_status, CONCAT_ws("",map_ci.crs_id,map_ci.section_id) as ao_crs_section_id
                                FROM map_courseto_course_instructor as map_ci
                                LEFT JOIN course as crs ON crs.crs_id = map_ci.crs_id
                                LEFT JOIN users as usr ON usr.id = map_ci.course_instructor_id
                                LEFT JOIN master_type_details as mtd ON mtd.mt_details_id = map_ci.section_id
                                LEFT JOIN course_type as crs_type ON crs_type.crs_type_id = crs.crs_type_id
                                LEFT JOIN assessment_occasions as ao ON ao.crs_id = map_ci.crs_id and ao.section_id = map_ci.section_id and ao.mte_flag = 0
                                WHERE crs.crclm_id = "'.$crclm_id.'"
                                AND crs.crclm_term_id = "'.$term_id.'"
                                AND crs.status=1
                                AND crs.state_id >= 4 
								GROUP BY ao_crs_section_id';
            $query_data = $this->db->query($crs_fetch_query);
            $query_result = $query_data->result_array();
            $data['course_details'] = $query_result;
        } else {
            $crs_fetch_query = 'SELECT map_ci.course_instructor_id, map_ci.crs_id, map_ci.section_id,
									crs.crs_title, crs_code, crs.crs_id, crs.crs_mode,
									crs_type.crs_type_name,
									mtd.mt_details_name as section_name,
									usr.username,usr.title,usr.first_name,usr.last_name,usr.id,
									ao.status as ao_status, CONCAT_ws("",map_ci.crs_id,map_ci.section_id) as ao_crs_section_id
                                FROM map_courseto_course_instructor as map_ci
                                LEFT JOIN course as crs ON crs.crs_id = map_ci.crs_id
                                LEFT JOIN users as usr ON usr.id = map_ci.course_instructor_id
                                LEFT JOIN master_type_details as mtd ON mtd.mt_details_id = map_ci.section_id
                                LEFT JOIN course_type as crs_type ON crs_type.crs_type_id = crs.crs_type_id
                                LEFT JOIN assessment_occasions as ao ON ao.crs_id = map_ci.crs_id and ao.section_id = map_ci.section_id and ao.mte_flag = 0
                                WHERE crs.crclm_id = "'.$crclm_id.'"
									AND crs.crclm_term_id = "'.$term_id.'"
									AND map_ci.course_instructor_id = "'.$user.'"
									AND crs.status = 1
									AND crs.state_id >= 4 
									GROUP BY ao_crs_section_id';
            $query_data = $this->db->query($crs_fetch_query);
            $query_result = $query_data->result_array();
            $data['course_details'] = $query_result;
        }

        $data['fetched_details'] = $this->db->query('SELECT DISTINCT crs_id,status 
													 FROM assessment_occasions 
													 WHERE crclm_id = "' . $crclm_id . '" 
														AND term_id = "' . $term_id . '" 
														AND status >= 0 ')
													->result_array();
        return $data;
    }
	
	/**
	 * Function to
	 *
	 *
	 */
	public function get_course_list($crclm_id, $term_id, $from_crs_id){
				$query = 'SELECT crs_id, crs_title FROM course WHERE crclm_id = "'.$crclm_id.'" AND crclm_term_id = "'.$term_id.'" AND  state_id >= 4 ';
				$res_data = $this->db->query($query);
				$result = $res_data->result_array();
				return $result;
	
	}

    /**
     * Function is to fetch assessment occasion details
     * @parameters: curriculum id, term id, course id
     * returns: course details
     */
    public function ao_data($pgm_id = NULL, $crclm_id = NULL, $crs_id = NULL, $section_id = NULL) {
        //fetch curriculum, term, course, type details from curriculum, term, course and course_type tables
        $crclm_term_crs_type_query = 'SELECT crclm.crclm_name, crs.clo_bl_flag , crclm.pgm_id, crs.crs_id, mtd.mt_details_name as section_name, mtd.mt_details_id as section_id,
										term.term_name, crs.crclm_id, crs.crclm_term_id, crs.crs_code,
										ct.crs_type_id, crs_title, ct.crs_type_name , crs.cia_flag , crs.mte_flag, crs.tee_flag 
									  FROM curriculum AS crclm, crclm_terms AS term, course AS crs, course_type AS ct, master_type_details as mtd
									  WHERE crs.crclm_id = crclm.crclm_id
										AND crs.crclm_term_id = term.crclm_term_id
										AND ct.crs_type_id = crs.crs_type_id
										AND crs.crs_id = "' . $crs_id . '"
										AND mtd.mt_details_id = "'.$section_id.'"';
        $crclm_term_crs_type_result = $this->db->query($crclm_term_crs_type_query);
        $crclm_term_crs_type_data = $crclm_term_crs_type_result->result_array();
        $data['crclm_term_crs_type'] = $crclm_term_crs_type_data;

        //get course type id to fetch weightage and cia occasion
        if (!empty($data['crclm_term_crs_type'])) {
            $crs_type_id = $crclm_term_crs_type_data[0]['crs_type_id'];
        } else {
            $crs_type_id = 0;
        }

        //fetch weightage and occasion from crclm_crs_type_map table
        $weightage_occasion_query = 'SELECT cia_weightage, tee_weightage, cia_occasion 
									 FROM crclm_crs_type_map
									 WHERE course_type_id = "' . $crs_type_id . '"
										AND crclm_id = "' . $crclm_id . '"';
        $weightage_occasion_result = $this->db->query($weightage_occasion_query);
        $weightage_occasion_data = $weightage_occasion_result->result_array();
        $data['weightage_occasion'] = $weightage_occasion_data;

        //fetch total_cia_weightage, total_tee_weightage from course table
        $query = 'SELECT total_cia_weightage, total_mte_weightage , total_tee_weightage , cia_flag  , mte_flag , tee_flag FROM course where crclm_id = "' . $crclm_id . '" and crs_id = "' . $crs_id . '"';
        $value = $this->db->query($query);
        $result = $value->result_array();
        $data['data'] = $result;

        //fetch ao methods from ao_method table
        $ao_method_query = 'SELECT ao_method_id, ao_method_name 
							FROM ao_method
							WHERE ao_method_pgm_id = "' . $pgm_id . '" and crs_id is NULL';
        $ao_method_result = $this->db->query($ao_method_query);
        $ao_method_data = $ao_method_result->result_array();
        $data['ao_method_data'] = $ao_method_data;

        //fetch ao methods from ao_method table
        $mt_details_query = 'SELECT mt_details_id, mt_details_name 
							 FROM master_type_details
							 WHERE master_type_id = 1';
        $mt_details_result = $this->db->query($mt_details_query);
        $mt_details_data = $mt_details_result->result_array();
        $data['mt_details_data'] = $mt_details_data;

        //fetch course outcome details of a course from co table
        $co_details_query = 'SELECT clo_id, clo_code, clo_statement
							 FROM clo
							 WHERE crs_id = "' . $crs_id . '"';
        $co_details_result = $this->db->query($co_details_query);
        $co_details_data = $co_details_result->result_array();
        $data['co_details_data'] = $co_details_data;

		$mte_query = $this->db->query('select mte_flag from organisation');
		$mte_result = $mte_query->result_array();
		$data['mte_flag_org'] = $mte_result[0]['mte_flag'];
		
        $query = $this->db->query('SELECT CONCAT(COALESCE(cognitive_domain_flag,""),",",COALESCE(affective_domain_flag,""),",",COALESCE(psychomotor_domain_flag,""))  crs_id from course where crs_id= "'.$crs_id.'"');
		$re = $query->result_array();
		$count = count($re[0]['crs_id']);
		
		$set_data = (explode(",",$re[0]['crs_id']));

		$sk = 0; $bld_id='';
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
		$data['bl_details_data'] = $bloom_lvl_data->result_array();
		
		$query = $this->db->query('SELECT count(ao_id) as count
									FROM assessment_occasions
									WHERE crclm_id = "'. $crclm_id .'" and                                             
									crs_id = "'. $crs_id .'" and 
									mte_flag = 0 and 
									section_id =  "'. $section_id .'" ');
		$re = $query->result_array();
  
		if($re[0]['count'] <= 0) {
			$data['ao_count']  = 1;
		} else {
			$data['ao_count']  = $re[0]['count'] + 1  ;
		}
		
        return $data;
    }
    
    public function fetch_ao_count( $crclm_id, $crs_id, $section_id ){
        $query = $this->db->query('SELECT count(ao_id) as count
									FROM assessment_occasions
									WHERE crclm_id = "'. $crclm_id .'" and  
									mte_flag = 0 AND
									crs_id = "'. $crs_id .'" and 
									section_id =  "'. $section_id .'" ');
                $re = $query->result_array();
          
                if($re[0]['count'] <= 0){
                   $ao_count  = 1;
                }else{
                    $ao_count  = $re[0]['count'] + 1  ;
                }
                return $ao_count;
    }

	public function fetch_bl_list($crclm_id, $crs_id, $get_selected_co_id){            
            $co_ids = sizeof($get_selected_co_id);
            $query = $this->db->query("select clo_bl_flag from course where crs_id = '".$crs_id."' ");
            $result = $query->result_array();
			
			$query_count = $this->db->query('select * from map_clo_bloom_level as m join clo as cl on cl.clo_id = m.clo_id  where crs_id = "'. $crs_id .'"');
			$count_map_data = $query_count->result_array();

			if($result[0]['clo_bl_flag'] == '1' ||  (!empty($count_map_data))) {
				$this->db->SELECT(' DISTINCT(bl.bloom_id) , bl.level , bl.learning , bl.description , bl.bloom_actionverbs')
					->FROM('map_clo_bloom_level as cl')
					->JOIN('clo AS c', 'cl.clo_id = c.clo_id')
					->JOIN('bloom_level as bl' , 'bl.bloom_id = cl.bloom_id')
					->WHERE_IN('c.clo_id', $get_selected_co_id)
					->WHERE('c.crclm_id', $crclm_id)
					->WHERE('c.crs_id', $crs_id)
					->GROUP_BY('c.clo_id  , bl.bloom_id');
				$bl_list_result = $this->db->get()->result_array();
		 
			return $bl_list_result;
        } else {
				
            $query = $this->db->query('SELECT CONCAT(COALESCE(cognitive_domain_flag,""),",",COALESCE(affective_domain_flag,""),",",COALESCE(psychomotor_domain_flag,""))  crs_id from course where crs_id= "'.$crs_id.'"');
			$re = $query->result_array();
			$count = count($re[0]['crs_id']);
			
			$set_data = (explode(",",$re[0]['crs_id']));			
			$bloom_domain_query = $this->db->query('SELECT bld_id,bld_name,status FROM bloom_domain');
			$bloom_domain_data = $bloom_domain_query->result_array();
		if($set_data != '0,0,0'){	
			$sk=0;
			
			foreach($bloom_domain_data as $bdd) {			
				if ($set_data[$sk] == 1 && $bdd['status'] == 1) {
					$bld_id [] = $bdd['bld_id'];
				}
				
				$sk++;
			}	
		}else{
		
			foreach($bloom_domain_data as $bdd) { $bld_id [] = $bdd['bld_id']; }
		}
		
		if(!empty($bld_id)){
			$Bld_id_data = implode (",", $bld_id);
			$bld_id_single = str_replace("'", "", $Bld_id_data);	

			$bloom_lvl_query = 'select * from  bloom_level where bld_id IN('.$bld_id_single.') ORDER BY LPAD(LOWER(level),5,0) ASC';
			$bloom_lvl_data = $this->db->query($bloom_lvl_query);
            
			return $bloom_lvl_data->result_array();
		}else{ return '';}
        
		}
    }
	
	
    /**
     * Function to AO details to display in the table
     * @parameters: crs id
     * @return: 
     */
    public function ao_data_table($crs_id,$section_id) {
        //fetch AO data to display in the table
        $assessment_query = 'SELECT ao.ao_id, ao.qpd_id, ao.crs_id, ao.ao_type_id, ao.ao_name, ao.ao_description,
								ao.ao_method_id, ao.ao_type_id, ao.weightage, ao.max_marks,
								am.ao_method_id, am.ao_method_name, mtd.mt_details_id, mtd.mt_details_name
								FROM assessment_occasions AS ao, ao_method AS am, master_type_details AS mtd
								WHERE ao.crs_id = "' . $crs_id . '"
								AND ao.ao_method_id = am.ao_method_id
								AND ao.ao_type_id = mtd.mt_details_id
								AND ao.section_id = "'.$section_id.'"
								AND ao.mte_flag = 0
								GROUP BY ao.ao_id ORDER BY ao.ao_id';
        $assessment_data = $this->db->query($assessment_query);
        $assessment_result = $assessment_data->result_array();

        return $assessment_result;
    }
    
    /**
     * Function to fetch mapped COs, BL and POs
     * @parameters: curriculum id, program id, course id
     * @return: 
     */
    public function mapped_co_bl_po_data($ao_id) {
        //CO
        $mapped_co_query = 'SELECT qpmap.qp_mq_id, qpmap.entity_id, qpmap.actual_mapped_id
							FROM assessment_occasions AS ao, qp_definition AS qpd, qp_unit_definition AS qpud,
								 qp_mainquestion_definition AS qpmd, qp_mapping_definition AS qpmap
							WHERE ao.ao_id = "' . $ao_id . '"
								AND ao.qpd_id = qpd.qpd_id
								AND qpd.qpd_id = qpud.qpd_id
								AND qpud.qpd_unitd_id = qpmd.qp_unitd_id
								AND qpmd.qp_mq_id = qpmap.qp_mq_id
								AND qpmap.entity_id = 11';
        $mapped_co_data = $this->db->query($mapped_co_query);
        $mapped_co_result = $mapped_co_data->result_array();
        $data['mapped_co'] = $mapped_co_result;

        //string to array conversion to avoid active record error
        $co_ids = array();
        $i = 0;
        foreach ($mapped_co_result AS $co_list) {
            $co_ids[$i++] = $co_list['actual_mapped_id'];
        }
        $data['co_ids'] = $co_ids;

        //BL
        $mapped_bl_query = 'SELECT qpmap.qp_mq_id, qpmap.entity_id, qpmap.actual_mapped_id
							FROM assessment_occasions AS ao, qp_definition AS qpd, qp_unit_definition AS qpud,
								 qp_mainquestion_definition AS qpmd, qp_mapping_definition AS qpmap
							WHERE ao.ao_id = "' . $ao_id . '"
								AND ao.qpd_id = qpd.qpd_id
								AND qpd.qpd_id = qpud.qpd_id
								AND qpud.qpd_unitd_id = qpmd.qp_unitd_id
								AND qpmd.qp_mq_id = qpmap.qp_mq_id
								AND qpmap.entity_id = 23';
        $mapped_bl_data = $this->db->query($mapped_bl_query);
        $data['mapped_bl'] = $mapped_bl_data->result_array();

        return $data;
    }

    /**
     * Function to fetch AO method, assessment type, CO, BL and PO drop-downs
     * @parameters: 
     * @return: 
     */
    public function modal_edit_co_bl_po_data($pgm_id, $crs_id, $co_ids,$section_id) {
        //fetch ao methods from ao_method table
        $ao_method_query = 'SELECT ao_method_id, ao_method_name 
                            FROM(
                            SELECT a.ao_method_id , a.ao_method_name  
                                FROM ao_method a
                                WHERE a.ao_method_pgm_id = "' . $pgm_id . '" AND a.crs_id is NULL
                            UNION 
                            SELECT b.ao_method_id , b.ao_method_name  
                                FROM ao_method b
                                WHERE b.ao_method_pgm_id = "' . $pgm_id . '" AND b.crs_id = "'.$crs_id.'" AND b.section_id = "'.$section_id.'"
                                 )A ORDER BY ao_method_name ASC';
        $ao_method_result = $this->db->query($ao_method_query);
        $ao_method_data = $ao_method_result->result_array();
        $data['ao_method_data'] = $ao_method_data;

        //fetch ao methods from ao_method table
        $mt_details_query = 'SELECT mt_details_id, mt_details_name 
							 FROM master_type_details
							 WHERE master_type_id = 1';
        $mt_details_result = $this->db->query($mt_details_query);
        $mt_details_data = $mt_details_result->result_array();
        $data['mt_details_data'] = $mt_details_data;

        //fetch course outcome details of a course from co table
        $co_details_query = 'SELECT clo_id, clo_code, clo_statement
							 FROM clo
							 WHERE crs_id = "' . $crs_id . '"';
        $co_details_result = $this->db->query($co_details_query);
        $co_details_data = $co_details_result->result_array();
        $data['co_details_data'] = $co_details_data;

        $query = $this->db->query('SELECT CONCAT(COALESCE(cognitive_domain_flag,""),",",COALESCE(affective_domain_flag,""),",",COALESCE(psychomotor_domain_flag,""))  crs_id from course where crs_id= "'.$crs_id.'"');
		$re = $query->result_array();
		$count = count($re[0]['crs_id']);
		
		$set_data = (explode(",",$re[0]['crs_id']));

		$sk=0;$bld_id = '';
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
                $data['bl_details_data'] = $bloom_lvl_data->result_array();
        
        return $data;
    }

    /**
     * Function to insert AO details into the database
     * @parameters: 
     * @return: PO details
     */
    public function ao_data_insert($crclm_id, $term_id, $crs_id, $section_id, $ao_name, $ao_description, $ao_list, $mt_list, $cia_total_weightage, $weightage, $max_marks, $co_list, $bl_list) {
       $rubrics_id = $this->get_rubrics_id();
       $crs_code_query = 'SELECT crs_code FROM course WHERE crs_id = "'.$crs_id.'" ';
       $crs_code_data = $this->db->query($crs_code_query);
       $crs_code = $crs_code_data->row_array();
        if ($mt_list == 1) {
            //QP
            $ao_data_query = 'INSERT INTO assessment_occasions(ao_name, ao_description, ao_method_id, ao_type_id, 
								weightage, max_marks, crclm_id, term_id, crs_id, section_id, status , mte_flag) 
							  VALUES ("' . $ao_name . '", "' . $ao_description . '", "' . $ao_list . '", "' . $mt_list . '", "' . $weightage . '", "' . $max_marks . '", "' . $crclm_id . '", "' . $term_id . '", "' . $crs_id . '","'.$section_id.'", 1 , 0)';
            $ao_data_result = $this->db->query($ao_data_query);

            //newly inserted assessment occasion id
            $last_insert_ao_id = $this->db->insert_id();
            $data['pk_ao_id'] = $last_insert_ao_id;

            //fetch assessment occasion details to populate in the table
            $assessment_query = 'SELECT am.ao_method_name, mt.mt_details_name
								 FROM assessment_occasions AS ao, ao_method AS am, master_type_details AS mt
								 WHERE ao.ao_id = "' . $last_insert_ao_id . '"
									AND ao.ao_method_id = am.ao_method_id
									AND ao_type_id  = mt.mt_details_id
									AND ao.mte_flag = 0
                                    AND ao.section_id = "'.$section_id.'"
								 GROUP BY ao.ao_id';
            $assessment_data = $this->db->query($assessment_query);
            $assessment_result = $assessment_data->result_array();
            $data['assessment_result'] = $assessment_result;

            return 1;
        } else if($mt_list == $rubrics_id) {
             //Rubrics
            $assess_method_name = 'SELECT * FROM ao_method WHERE ao_method_id = "'.$ao_list.'" ';
            $assess_method_name_data = $this->db->query($assess_method_name);
            $assess_method_res = $assess_method_name_data->row_array();
            
            $program_id = 'SELECT pgm_id FROM curriculum WHERE crclm_id = "'.$crclm_id.'" ';
            $pgm_id_data = $this->db->query($program_id);
            $pgm_id = $pgm_id_data->row_array();
            
            $assess_method_insert = array(
                'ao_method_pgm_id' => $pgm_id['pgm_id'],
                'crclm_id' => $crclm_id,
                'term_id' =>$term_id ,
                'crs_id' => $crs_id,
                'section_id' => $section_id,
                'ao_method_name' => $ao_description .' - '.$assess_method_res['ao_method_name'].' - ['.$crs_code['crs_code'].']',
                'ao_method_description' => NULL,
                'created_by' => $this->ion_auth->user()->row()->id,
                'modified_by' => $this->ion_auth->user()->row()->id,
                'created_date' => date('y-m-d'),
                'modified_date' => date('y-m-d'),
            );
            $this->db->insert('ao_method',$assess_method_insert);
            $last_inserted_ao_method_id = $this->db->insert_id();
            
            // Fetch the last ao method criteria, range and criteria descriptions and save with the new ao method id.
            $select_criteria_query = 'SELECT rubrics_criteria_id, ao_method_id, criteria FROM ao_rubrics_criteria WHERE ao_method_id = "'.$ao_list.'" ';
            $criteria_data = $this->db->query($select_criteria_query);
            $criteria_data_res = $criteria_data->result_array();
            
            $criteria_count = count($criteria_data_res);
            for($i=0; $i<$criteria_count;$i++){
                
                $criteria_insert_array = array(
                    'ao_method_id' => $last_inserted_ao_method_id,
                    'criteria' => $criteria_data_res[$i]['criteria'],
                    'created_by' => $this->ion_auth->user()->row()->id,
                    'modified_by' => $this->ion_auth->user()->row()->id,
                    'created_date' => date('y-m-d'),
                    'modified_date' => date('y-m-d'),
                );
                $this->db->insert('ao_rubrics_criteria',$criteria_insert_array);
            }
            
            $select_criteria_query_one = 'SELECT rubrics_criteria_id, ao_method_id, criteria FROM ao_rubrics_criteria WHERE ao_method_id = "'.$last_inserted_ao_method_id.'" ';
            $criteria_data_one = $this->db->query($select_criteria_query_one);
            $criteria_data_res_one = $criteria_data_one->result_array();
            
            $select_ao_range_query = 'SELECT rubrics_range_id, ao_method_id, criteria_range_name, criteria_range FROM ao_rubrics_range WHERE ao_method_id = "'.$ao_list.'" ';
            $ao_range_data = $this->db->query($select_ao_range_query);
            $ao_range_res = $ao_range_data->result_array();
            
            $ao_range_count = count($ao_range_res);
            
            for($j = 0; $j < $ao_range_count; $j++){
                $ao_range_insert_array = array(
                    'ao_method_id' => $last_inserted_ao_method_id,
                    'criteria_range_name' => @$ao_range_res[$j]['criteria_range_name'],
                    'criteria_range' => $ao_range_res[$j]['criteria_range'],
                    'created_by' => $this->ion_auth->user()->row()->id,
                    'modified_by' => $this->ion_auth->user()->row()->id,
                    'created_date' => date('y-m-d'),
                    'modified_date' => date('y-m-d'),
                );
				
                $this->db->insert('ao_rubrics_range',$ao_range_insert_array);
                $last_insert_range_id = $this->db->insert_id();
                
				$select_old_criteria_desc_query = ' SELECT rubrics_range_id, rubrics_criteria_id, criteria_description 
													FROM ao_rubrics_criteria_desc 
													WHERE rubrics_range_id = "'.$ao_range_res[$j]['rubrics_range_id'].'" ';
                $select_old_criteria = $this->db->query($select_old_criteria_desc_query);
                $select_old_criteria_data = $select_old_criteria->result_array();
                
                $criteria_desc_count = count($select_old_criteria_data);
                for($c = 0; $c < $criteria_desc_count; $c++){
                    $criteria_desc_array = array(
                        'rubrics_range_id' => $last_insert_range_id,
                        'rubrics_criteria_id' => @$criteria_data_res_one[$c]['rubrics_criteria_id'],
                        'criteria_description' => $select_old_criteria_data[$c]['criteria_description'],
                        'created_by' => $this->ion_auth->user()->row()->id,
                        'modified_by' => $this->ion_auth->user()->row()->id,
                        'created_date' => date('y-m-d'),
                        'modified_date' => date('y-m-d'),
                        );
                 $this->db->insert('ao_rubrics_criteria_desc',$criteria_desc_array);   
                }
            }
           
            $ao_data_query = 'INSERT INTO assessment_occasions(ao_name, ao_description, ao_method_id, ao_type_id, 
								weightage, max_marks, crclm_id, term_id, crs_id, section_id, status) 
							  VALUES ("' .$ao_name . '", "' .$ao_description . '", "' . $last_inserted_ao_method_id . '", "' . $mt_list . '", "' . $weightage . '", "' . $max_marks . '", "' . $crclm_id . '", "' . $term_id . '", "' . $crs_id . '","'.$section_id.'", 1)';
            $ao_data_result = $this->db->query($ao_data_query);

            //newly inserted assessment occasion id
            $last_insert_ao_id = $this->db->insert_id();
            $data['pk_ao_id'] = $last_insert_ao_id;

            //fetch assessment occasion details to populate in the table
            $assessment_query = 'SELECT am.ao_method_name, mt.mt_details_name
								 FROM assessment_occasions AS ao, ao_method AS am, master_type_details AS mt
								 WHERE ao.ao_id = "' . $last_insert_ao_id . '"
									AND ao.ao_method_id = am.ao_method_id
									AND ao_type_id  = mt.mt_details_id
                                                                        AND ao.section_id = "'.$section_id.'"
								 GROUP BY ao.ao_id';
            $assessment_data = $this->db->query($assessment_query);
            $assessment_result = $assessment_data->result_array();
            $data['assessment_result'] = $assessment_result;

            return 1;
            
        }
            else {
            //individual
            //qp definition table
            $qp_defn_data_query = 'INSERT INTO qp_definition(qpd_type, qp_rollout, crclm_id, crclm_term_id, crs_id, qpd_title, 
										qpd_num_units, qpd_gt_marks, qpd_max_marks) 
								   VALUES (3, 1, "' . $crclm_id . '", "' . $term_id . '", "' . $crs_id . '", "CIA Individual Assessment", 1, "' . $max_marks . '", "' . $max_marks . '")';
            $qp_defn_result = $this->db->query($qp_defn_data_query);

            //newly inserted qp id
            $last_insert_qp_defn_id = $this->db->insert_id();
            $data['pk_qpd_id'] = $last_insert_qp_defn_id;

            //qp unit definition table
            $qp_unit_defn_data_query = 'INSERT INTO qp_unit_definition(qpd_id, qp_unit_code, qp_total_unitquestion, 
											qp_attempt_unitquestion, qp_utotal_marks, FM) 
										VALUES ("' . $last_insert_qp_defn_id . '", "Unit - I", 1, 1, "' . $max_marks . '", 0)';
            $qp_unit_defn_result = $this->db->query($qp_unit_defn_data_query);

            //newly inserted qp unit id
            $last_insert_qp_unit_defn_id = $this->db->insert_id();

            //qp main question definition table
            $qp_mainquest_defn_data_query = 'INSERT INTO qp_mainquestion_definition(qp_unitd_id, qp_mq_code, qp_subq_code, 
												qp_content, qp_subq_marks) 
											 VALUES ("' . $last_insert_qp_unit_defn_id . '", 1, "1a", "CIA Individual Assessment", "' . $max_marks . '")';
            $qp_mainquest_defn_result = $this->db->query($qp_mainquest_defn_data_query);

            //newly inserted qp main question id
            $last_insert_qp_mainquest_defn_id = $this->db->insert_id();
            $data['main_qp_id'] = $last_insert_qp_mainquest_defn_id;

            //get size of CO and PO list
            $co_size = sizeof($co_list);
            $bl_size = sizeof($bl_list);
			
            //insert CO into qp mapping definition table
            $insert_query = 'INSERT INTO qp_mapping_definition(qp_mq_id, entity_id, actual_mapped_id, mapped_marks, mapped_percentage) VALUES';
            for ($i = 0; $i < $co_size; $i++) {
                $insert_query.='("' . $last_insert_qp_mainquest_defn_id . '", 11, "' . $co_list[$i] . '", "' . $max_marks . '", 100),';
            }

            //insert BL into qp mapping definition table
            for ($i = 0; $i < $bl_size; $i++) {
                $insert_query.= '("' . $last_insert_qp_mainquest_defn_id . '", 23, "' . $bl_list[$i] . '", "' . $max_marks . '", 100),';
            }

			//remove the comma in the end before inserting the data			
            $insert_query = substr($insert_query, 0, -1);
            $this->db->query($insert_query);

            //assessment occasions table
            $ao_data_query = 'INSERT INTO assessment_occasions(qpd_id, ao_name, ao_description, ao_method_id, ao_type_id, weightage, max_marks, crclm_id, term_id, crs_id, section_id, status) VALUES ("' . $last_insert_qp_defn_id . '", "' . $ao_name . '", "' . $ao_description . '", "' . $ao_list . '", "' . $mt_list . '", "' . $weightage . '", "' . $max_marks . '", "' . $crclm_id . '", "' . $term_id . '", "' . $crs_id . '","'.$section_id.'", 1)';
            $ao_result = $this->db->query($ao_data_query);

            //update main question marks distribution based on the organization config settings
            $proc_query = 'call updateQPWeightageDistribution(' . $last_insert_qp_mainquest_defn_id . ')';
            $proc_data = $this->db->query($proc_query);

            //newly inserted assessment occasion id
            $last_insert_ao_id = $this->db->insert_id();
            $data['pk_ao_id'] = $last_insert_ao_id;

            //fetch assessment occasion details to populate in the table
            $assessment_query = 'SELECT am.ao_method_name, mt.mt_details_name
								 FROM assessment_occasions AS ao, qp_definition AS q, ao_method AS am, master_type_details AS mt
								 WHERE q.qpd_id = "' . $last_insert_qp_defn_id . '"
									AND ao.qpd_id = q.qpd_id
									AND ao.ao_method_id = am.ao_method_id
                                                                        AND ao.section_id = "'.$section_id.'"
									AND ao_type_id  = mt.mt_details_id';
            $assessment_data = $this->db->query($assessment_query);
            $assessment_result = $assessment_data->result_array();
            $data['assessment_result'] = $assessment_result;

            return 1;
        }
    }

    /**
     * Function to insert AO details into the database
     * @parameters: curriculum id, term id, course id, qpd id, ao id, ao name, ao description, ao list, mt list, cia total weightage, 
      weightage, max marks, co list, po list, bl list
     * @return: return integer value
     */
     public function ao_data_update($crclm_id, $term_id, $crs_id, $qpd_id = NULL, $ao_id, $ao_name, 
                                    $ao_description, $ao_list, $mt_list, $cia_total_weightage, $weightage, 
                                    $max_marks, $co_list = NULL, $bl_list = NULL,$section_id,$old_ao_method_id) {
         // get course code
         $crs_code_query = 'SELECT crs_code FROM course WHERE crs_id = "'.$crs_id.'" ';
            $crs_code_data = $this->db->query($crs_code_query);
            $crs_code = $crs_code_data->row_array();
        //check if qpd_id exists and also to check whether to delete insert or to update
        $qp_to_individual_query = 'SELECT qpd_id, ao_type_id
                                   FROM assessment_occasions
                                   WHERE ao_id = "' . $ao_id . '"';
        $qp_to_individual_data = $this->db->query($qp_to_individual_query);
        $qp_to_individual = $qp_to_individual_data->result_array();
        
        $rubrics_id = $this->get_rubrics_id();
        
        $ao_type_id_query = $qp_to_individual[0]['ao_type_id'];

        //check if qp id exists or not (i.e. question paper has been created or not)
        if (!empty($qp_to_individual[0]['qpd_id'])) {
            $qpd_id_query = $qp_to_individual[0]['qpd_id'];
            $qpd_id_flag = 1;
        } else {
            //set the flag
            $qpd_id_flag = 0;
        }
        if($ao_type_id_query == $rubrics_id ){
            $dumy_rubrics_id = 0;
        }else{
            $dumy_rubrics_id = $rubrics_id;
        }

        //check when AO method type has not changed
        if ($mt_list == $ao_type_id_query) {
            //normal update
            //update AO for QP or individual
            $ao_data_query = 'UPDATE assessment_occasions
                              SET ao_name = "' . $ao_name . '", ao_description = "' . $ao_description . '", ao_method_id = "' . $ao_list . '",
                                ao_type_id = "' . $mt_list . '", weightage = "' . $weightage . '", max_marks = "' . $max_marks . '"
                              WHERE ao_id = "' . $ao_id . '"';
            $ao_result = $this->db->query($ao_data_query);

            if ($qpd_id_flag == 1) {
                //condition - if qpd id exists and assessment is individual (only 1 question exists) else no change
                if($ao_type_id_query == 2) {
                    //update qp definition table
                    $qp_def_query = 'UPDATE qp_definition
                                     SET qpd_gt_marks = "' . $max_marks . '", qpd_max_marks = "' . $max_marks . '"
                                     WHERE qpd_id = "' . $qpd_id_query . '"';
                    $qp_def_result = $this->db->query($qp_def_query);

                    //update qp unit definition table
                    $qp_unit_def_query = 'UPDATE qp_unit_definition
                                          SET qp_utotal_marks = "' . $max_marks . '"
                                          WHERE qpd_id = "' . $qpd_id_query . '"';
                    $qp_unit_def_result = $this->db->query($qp_unit_def_query);

                    //update qp main question definition table
                    $main_qpid_query = 'SELECT qp_mq_id
                                        FROM qp_mainquestion_definition AS qpm
                                        JOIN qp_unit_definition AS qpu ON qpu.qpd_unitd_id = qpm.qp_unitd_id
                                            AND qpu.qpd_id = "' . $qpd_id_query . '"';
                    $main_qpid_data = $this->db->query($main_qpid_query);
                    $main_qpid_result = $main_qpid_data->result_array();
                    $temp = $main_qpid_result[0]['qp_mq_id'];

                    $qp_mq_def_query = 'UPDATE qp_mainquestion_definition
                                        SET qp_subq_marks = "' . $max_marks . '"
                                        WHERE qp_mq_id IN ("' . $temp . '")';
                    $qp_mq_def_result = $this->db->query($qp_mq_def_query);

                    //if individual, check whether CO, PO and BL has been selected
                    if (!empty($co_list) && !empty($bl_list)) {
                        //individual
                        //fetch main question id related to CO, BL and PO using AO id
                        $get_mq_ids_query = 'SELECT qpmd.qp_mq_id
                                             FROM assessment_occasions AS ao, qp_definition AS qpd, qp_unit_definition AS qpud,
                                                qp_mainquestion_definition AS qpmd, qp_mapping_definition AS qpmap
                                             WHERE ao.ao_id = "' . $ao_id . '"
                                                AND ao.qpd_id = qpd.qpd_id
                                                AND qpd.qpd_id = qpud.qpd_id
                                                AND qpud.qpd_unitd_id = qpmd.qp_unitd_id
                                                AND qpmd.qp_mq_id = qpmap.qp_mq_id
                                             GROUP BY qpmd.qp_mq_id';
                        $get_mq_ids_data = $this->db->query($get_mq_ids_query);
                        $get_mq_ids_result = $get_mq_ids_data->result_array();

                        //using qpd_id fetch main question id
                        $get_main_qp_id_query = 'SELECT qmd.qp_mq_id
                                                 FROM qp_mainquestion_definition AS qmd, qp_unit_definition AS qud
                                                 WHERE qud.qpd_id = "' . $qpd_id . '"
                                                    AND qud.qpd_unitd_id = qmd.qp_unitd_id';
                        $get_main_qp_id_data = $this->db->query($get_main_qp_id_query);
                        $get_main_qp_id_result = $get_main_qp_id_data->result_array();

                        if (!empty($get_mq_ids_result)) {
                            //delete all CO, BL and PO entities related to that main question id
                            $i = 0;
                            foreach ($get_mq_ids_result AS $mq_id_result) {
                                $qp_mq_delete = 'DELETE FROM qp_mapping_definition
                                                 WHERE qp_mq_id = "' . $mq_id_result['qp_mq_id'] . '"';
                                $qp_mq_delete_result = $this->db->query($qp_mq_delete);
                            }

                            //get size of CO and PO list
                            $co_size = sizeof($co_list);
                            $bl_size = sizeof($bl_list);
                           
                            //insert CO into qp mapping definition table
                            $insert_query = 'INSERT INTO qp_mapping_definition(qp_mq_id, entity_id, actual_mapped_id, mapped_marks, mapped_percentage) VALUES';
                            for ($j = 0; $j < $co_size; $j++) {
                                $insert_query.='("' . $get_mq_ids_result[0]['qp_mq_id'] . '", 11, "' . $co_list[$j] . '", "' . $max_marks . '", 100),';
                            }

                            //insert BL into qp mapping definition table
                            for ($k = 0; $k < $bl_size; $k++) {
                                $insert_query.= '("' . $get_mq_ids_result[0]['qp_mq_id'] . '", 23, "' . $bl_list[$k] . '", "' . $max_marks . '", 100),';
                            }
                           
                            //remove the comma in the end before inserting the data
                            $insert_query = substr($insert_query, 0, -1);
                            $this->db->query($insert_query);

                            //update main question marks distribution based on the organization config settings
                            $proc_query = 'call updateQPWeightageDistribution(' . $get_main_qp_id_result[0]['qp_mq_id'] . ')';
                            $proc_data = $this->db->query($proc_query);

                            //return 1 - on successful individual update
                            return 1;
                        }
                    }
                }else if($ao_type_id_query == 106){
                    $delete_qp = 'DELETE FROM qp_definition WHERE qpd_id = "'.$qpd_id_query.'" ';
                    $delete_qp_data = $this->db->query($delete_qp);
            if($ao_list != $old_ao_method_id){
              $assess_method_name = 'SELECT * FROM ao_method WHERE ao_method_id = "'.$ao_list.'" ';
               $assess_method_name_data = $this->db->query($assess_method_name);
               $assess_method_res = $assess_method_name_data->row_array(); 
               
            $program_id = 'SELECT pgm_id FROM curriculum WHERE crclm_id = "'.$crclm_id.'" ';
            $pgm_id_data = $this->db->query($program_id);
            $pgm_id = $pgm_id_data->row_array();
            
            $assess_method_insert = array(
                'ao_method_pgm_id' => $pgm_id['pgm_id'],
                'crclm_id' => $crclm_id,
                'term_id' =>$term_id ,
                'crs_id' => $crs_id,
                'section_id' => $section_id,
                'ao_method_name' => $assess_method_res['ao_method_name'],
                'ao_method_description' => NULL,
                'created_by' => $this->ion_auth->user()->row()->id,
                'modified_by' => $this->ion_auth->user()->row()->id,
                'created_date' => date('y-m-d'),
                'modified_date' => date('y-m-d'),
            );
            $this->db->insert('ao_method',$assess_method_insert);
            $last_inserted_ao_method_id = $this->db->insert_id();
            
            // Fetch the last ao method criteria, range and criteria descriptions and save with the new ao method id.
            $select_criteria_query = 'SELECT rubrics_criteria_id, ao_method_id, criteria FROM ao_rubrics_criteria WHERE ao_method_id = "'.$ao_list.'" ';
            $criteria_data = $this->db->query($select_criteria_query);
            $criteria_data_res = $criteria_data->result_array();
           
            $criteria_count = count($criteria_data_res);
            for($i=0; $i<$criteria_count;$i++){
                
                $criteria_insert_array = array(
                    'ao_method_id' => $last_inserted_ao_method_id,
                    'criteria' => $criteria_data_res[$i]['criteria'],
                    'created_by' => $this->ion_auth->user()->row()->id,
                    'modified_by' => $this->ion_auth->user()->row()->id,
                    'created_date' => date('y-m-d'),
                    'modified_date' => date('y-m-d'),
                );
                $this->db->insert('ao_rubrics_criteria',$criteria_insert_array);
            }
            
            $select_criteria_query_one = 'SELECT rubrics_criteria_id, ao_method_id, criteria FROM ao_rubrics_criteria WHERE ao_method_id = "'.$last_inserted_ao_method_id.'" ';
            $criteria_data_one = $this->db->query($select_criteria_query_one);
            $criteria_data_res_one = $criteria_data_one->result_array();
            
            $select_ao_range_query = 'SELECT rubrics_range_id, ao_method_id, criteria_range_name, criteria_range FROM ao_rubrics_range WHERE ao_method_id = "'.$ao_list.'" ';
            $ao_range_data = $this->db->query($select_ao_range_query);
            $ao_range_res = $ao_range_data->result_array();
            
            $ao_range_count = count($ao_range_res);
            
            for($j = 0; $j < $ao_range_count; $j++){
                $ao_range_insert_array = array(
                    'ao_method_id' => $last_inserted_ao_method_id,
                    'criteria_range_name' => @$ao_range_res[$j]['criteria_range_name'],
                    'criteria_range' => $ao_range_res[$j]['criteria_range'],
                    'created_by' => $this->ion_auth->user()->row()->id,
                    'modified_by' => $this->ion_auth->user()->row()->id,
                    'created_date' => date('y-m-d'),
                    'modified_date' => date('y-m-d'),
                );
				
                $this->db->insert('ao_rubrics_range',$ao_range_insert_array);
                $last_insert_range_id = $this->db->insert_id();
                
				$select_old_criteria_desc_query = ' SELECT rubrics_range_id, rubrics_criteria_id, criteria_description 
													FROM ao_rubrics_criteria_desc 
													WHERE rubrics_range_id = "'.$ao_range_res[$j]['rubrics_range_id'].'" ';
                $select_old_criteria = $this->db->query($select_old_criteria_desc_query);
                $select_old_criteria_data = $select_old_criteria->result_array();
                
                $criteria_desc_count = count($select_old_criteria_data);
                for($c = 0; $c < $criteria_desc_count; $c++){
                    $criteria_desc_array = array(
                        'rubrics_range_id' => $last_insert_range_id,
                        'rubrics_criteria_id' => @$criteria_data_res_one[$c]['rubrics_criteria_id'],
                        'criteria_description' => $select_old_criteria_data[$c]['criteria_description'],
                        'created_by' => $this->ion_auth->user()->row()->id,
                        'modified_by' => $this->ion_auth->user()->row()->id,
                        'created_date' => date('y-m-d'),
                        'modified_date' => date('y-m-d'),
                        );
                 $this->db->insert('ao_rubrics_criteria_desc',$criteria_desc_array);   
                }
            }
            //normal update
            //update AO for QP or individual
            $ao_data_query = 'UPDATE assessment_occasions 
                                SET qpd_id = 0, ao_name = "' . $ao_name . '", ao_description = "' . $ao_description . '", ao_method_id = "' . $last_inserted_ao_method_id . '",
                                      ao_type_id = "' . $mt_list . '", weightage = "' . $weightage . '", max_marks = "' . $max_marks . '"
                                WHERE ao_id = "' . $ao_id . '"';
            $ao_result = $this->db->query($ao_data_query);  
            if($assess_method_res){
                // Delete old AO method 
                $delete_ao_method_query = 'DELETE FROM ao_method WHERE ao_method_id = "'.$old_ao_method_id.'" ';
                $delete_ao_method = $this->db->query($delete_ao_method_query);
                }
	}
                    
    }else{

    }
    //return 1 - on successful QP or individual update
     return 1;
   }else{
      
   }
            
        }else if($mt_list == $dumy_rubrics_id){
            
            if($ao_list != $old_ao_method_id){
               
              $assess_method_name = 'SELECT * FROM ao_method WHERE ao_method_id = "'.$ao_list.'" ';
               $assess_method_name_data = $this->db->query($assess_method_name);
               $assess_method_res = $assess_method_name_data->row_array(); 
               
            $program_id = 'SELECT pgm_id FROM curriculum WHERE crclm_id = "'.$crclm_id.'" ';
            $pgm_id_data = $this->db->query($program_id);
            $pgm_id = $pgm_id_data->row_array();
            
            $assess_method_insert = array(
                'ao_method_pgm_id' => $pgm_id['pgm_id'],
                'crclm_id' => $crclm_id,
                'term_id' =>$term_id ,
                'crs_id' => $crs_id,
                'section_id' => $section_id,
                'ao_method_name' => $ao_description.' - '. $assess_method_res['ao_method_name'].'- ['.$crs_code['crs_code'].']',
                'ao_method_description' => NULL,
                'created_by' => $this->ion_auth->user()->row()->id,
                'modified_by' => $this->ion_auth->user()->row()->id,
                'created_date' => date('y-m-d'),
                'modified_date' => date('y-m-d'),
            );
            $this->db->insert('ao_method',$assess_method_insert);
            $last_inserted_ao_method_id = $this->db->insert_id();
            
            //normal update
            //update AO for QP or individual
            $ao_data_query = 'UPDATE assessment_occasions 
                                SET qpd_id = 0, ao_name = "' . $ao_name . '", ao_description = "' . $ao_description . '", ao_method_id = "' . $last_inserted_ao_method_id . '",
                                      ao_type_id = "' . $mt_list . '", weightage = "' . $weightage . '", max_marks = "' . $max_marks . '"
                                WHERE ao_id = "' . $ao_id . '"';
            $ao_result = $this->db->query($ao_data_query);
            
            // Fetch the last ao method criteria, range and criteria descriptions and save with the new ao method id.
            $select_criteria_query = 'SELECT rubrics_criteria_id, ao_method_id, criteria FROM ao_rubrics_criteria WHERE ao_method_id = "'.$ao_list.'" ';
            $criteria_data = $this->db->query($select_criteria_query);
            $criteria_data_res = $criteria_data->result_array();
           
            $criteria_count = count($criteria_data_res);
            for($i=0; $i<$criteria_count;$i++){
                
                $criteria_insert_array = array(
                    'ao_method_id' => $last_inserted_ao_method_id,
                    'criteria' => $criteria_data_res[$i]['criteria'],
                    'created_by' => $this->ion_auth->user()->row()->id,
                    'modified_by' => $this->ion_auth->user()->row()->id,
                    'created_date' => date('y-m-d'),
                    'modified_date' => date('y-m-d'),
                );
                $this->db->insert('ao_rubrics_criteria',$criteria_insert_array);
            }
            
            $select_criteria_query_one = 'SELECT rubrics_criteria_id, ao_method_id, criteria FROM ao_rubrics_criteria WHERE ao_method_id = "'.$last_inserted_ao_method_id.'" ';
            $criteria_data_one = $this->db->query($select_criteria_query_one);
            $criteria_data_res_one = $criteria_data_one->result_array();
            
            $select_ao_range_query = 'SELECT rubrics_range_id, ao_method_id, criteria_range_name, criteria_range FROM ao_rubrics_range WHERE ao_method_id = "'.$ao_list.'" ';
            $ao_range_data = $this->db->query($select_ao_range_query);
            $ao_range_res = $ao_range_data->result_array();
            
            $ao_range_count = count($ao_range_res);
            
            for($j = 0; $j < $ao_range_count; $j++){
                $ao_range_insert_array = array(
                    'ao_method_id' => $last_inserted_ao_method_id,
                    'criteria_range_name' => @$ao_range_res[$j]['criteria_range_name'],
                    'criteria_range' => $ao_range_res[$j]['criteria_range'],
                    'created_by' => $this->ion_auth->user()->row()->id,
                    'modified_by' => $this->ion_auth->user()->row()->id,
                    'created_date' => date('y-m-d'),
                    'modified_date' => date('y-m-d'),
                );
				
                $this->db->insert('ao_rubrics_range',$ao_range_insert_array);
                $last_insert_range_id = $this->db->insert_id();
                
				$select_old_criteria_desc_query = ' SELECT rubrics_range_id, rubrics_criteria_id, criteria_description 
													FROM ao_rubrics_criteria_desc 
													WHERE rubrics_range_id = "'.$ao_range_res[$j]['rubrics_range_id'].'" ';
                $select_old_criteria = $this->db->query($select_old_criteria_desc_query);
                $select_old_criteria_data = $select_old_criteria->result_array();
                
                $criteria_desc_count = count($select_old_criteria_data);
                for($c = 0; $c < $criteria_desc_count; $c++){
                    $criteria_desc_array = array(
                        'rubrics_range_id' => $last_insert_range_id,
                        'rubrics_criteria_id' => @$criteria_data_res_one[$c]['rubrics_criteria_id'],
                        'criteria_description' => $select_old_criteria_data[$c]['criteria_description'],
                        'created_by' => $this->ion_auth->user()->row()->id,
                        'modified_by' => $this->ion_auth->user()->row()->id,
                        'created_date' => date('y-m-d'),
                        'modified_date' => date('y-m-d'),
                        );
                 $this->db->insert('ao_rubrics_criteria_desc',$criteria_desc_array);   
                }
            }
              
            if($assess_method_res){
                // Delete old AO method 
                $delete_ao_method_query = 'DELETE FROM ao_method WHERE ao_method_id = "'.$old_ao_method_id.'" ';
                $delete_ao_method = $this->db->query($delete_ao_method_query);
                }
                
                return 1;
	}else{
            $assess_method_name = 'SELECT * FROM ao_method WHERE ao_method_id = "'.$ao_list.'" ';
               $assess_method_name_data = $this->db->query($assess_method_name);
               $assess_method_res = $assess_method_name_data->row_array(); 
               
            $program_id = 'SELECT pgm_id FROM curriculum WHERE crclm_id = "'.$crclm_id.'" ';
            $pgm_id_data = $this->db->query($program_id);
            $pgm_id = $pgm_id_data->row_array();
            
            $assess_method_insert = array(
                'ao_method_pgm_id' => $pgm_id['pgm_id'],
                'crclm_id' => $crclm_id,
                'term_id' =>$term_id ,
                'crs_id' => $crs_id,
                'section_id' => $section_id,
                'ao_method_name' => $ao_description.' - '. $assess_method_res['ao_method_name'].'- ['.$crs_code['crs_code'].']',
                'ao_method_description' => NULL,
                'created_by' => $this->ion_auth->user()->row()->id,
                'modified_by' => $this->ion_auth->user()->row()->id,
                'created_date' => date('y-m-d'),
                'modified_date' => date('y-m-d'),
            );
            $this->db->insert('ao_method',$assess_method_insert);
            $last_inserted_ao_method_id = $this->db->insert_id();
            
            //normal update
            //update AO for QP or individual
            $ao_data_query = 'UPDATE assessment_occasions 
                                SET qpd_id = 0, ao_name = "' . $ao_name . '", ao_description = "' . $ao_description . '", ao_method_id = "' . $last_inserted_ao_method_id . '",
                                      ao_type_id = "' . $mt_list . '", weightage = "' . $weightage . '", max_marks = "' . $max_marks . '"
                                WHERE ao_id = "' . $ao_id . '"';
            $ao_result = $this->db->query($ao_data_query);
            
            // Fetch the last ao method criteria, range and criteria descriptions and save with the new ao method id.
            $select_criteria_query = 'SELECT rubrics_criteria_id, ao_method_id, criteria FROM ao_rubrics_criteria WHERE ao_method_id = "'.$ao_list.'" ';
            $criteria_data = $this->db->query($select_criteria_query);
            $criteria_data_res = $criteria_data->result_array();
           
            $criteria_count = count($criteria_data_res);
            for($i=0; $i<$criteria_count;$i++){
                
                $criteria_insert_array = array(
                    'ao_method_id' => $last_inserted_ao_method_id,
                    'criteria' => $criteria_data_res[$i]['criteria'],
                    'created_by' => $this->ion_auth->user()->row()->id,
                    'modified_by' => $this->ion_auth->user()->row()->id,
                    'created_date' => date('y-m-d'),
                    'modified_date' => date('y-m-d'),
                );
                $this->db->insert('ao_rubrics_criteria',$criteria_insert_array);
            }
            
            $select_criteria_query_one = 'SELECT rubrics_criteria_id, ao_method_id, criteria FROM ao_rubrics_criteria WHERE ao_method_id = "'.$last_inserted_ao_method_id.'" ';
            $criteria_data_one = $this->db->query($select_criteria_query_one);
            $criteria_data_res_one = $criteria_data_one->result_array();
            
            $select_ao_range_query = 'SELECT rubrics_range_id, ao_method_id, criteria_range_name, criteria_range FROM ao_rubrics_range WHERE ao_method_id = "'.$ao_list.'" ';
            $ao_range_data = $this->db->query($select_ao_range_query);
            $ao_range_res = $ao_range_data->result_array();
            
            $ao_range_count = count($ao_range_res);
            
            for($j = 0; $j < $ao_range_count; $j++){
                $ao_range_insert_array = array(
                    'ao_method_id' => $last_inserted_ao_method_id,
                    'criteria_range_name' => @$ao_range_res[$j]['criteria_range_name'],
                    'criteria_range' => $ao_range_res[$j]['criteria_range'],
                    'created_by' => $this->ion_auth->user()->row()->id,
                    'modified_by' => $this->ion_auth->user()->row()->id,
                    'created_date' => date('y-m-d'),
                    'modified_date' => date('y-m-d'),
                );
				
                $this->db->insert('ao_rubrics_range',$ao_range_insert_array);
                $last_insert_range_id = $this->db->insert_id();
                
				$select_old_criteria_desc_query = ' SELECT rubrics_range_id, rubrics_criteria_id, criteria_description 
													FROM ao_rubrics_criteria_desc 
													WHERE rubrics_range_id = "'.$ao_range_res[$j]['rubrics_range_id'].'" ';
                $select_old_criteria = $this->db->query($select_old_criteria_desc_query);
                $select_old_criteria_data = $select_old_criteria->result_array();
                
                $criteria_desc_count = count($select_old_criteria_data);
                for($c = 0; $c < $criteria_desc_count; $c++){
                    $criteria_desc_array = array(
                        'rubrics_range_id' => $last_insert_range_id,
                        'rubrics_criteria_id' => @$criteria_data_res_one[$c]['rubrics_criteria_id'],
                        'criteria_description' => $select_old_criteria_data[$c]['criteria_description'],
                        'created_by' => $this->ion_auth->user()->row()->id,
                        'modified_by' => $this->ion_auth->user()->row()->id,
                        'created_date' => date('y-m-d'),
                        'modified_date' => date('y-m-d'),
                        );
                 $this->db->insert('ao_rubrics_criteria_desc',$criteria_desc_array);   
                }
            }
            return 1;
        }
        }else if ($mt_list == 1) {
            //QP = 1
            if ($qpd_id_flag != 2) {
                //on change from individual to QP
                //delete from qp definition table
                $qp_delete_query = 'DELETE FROM qp_definition
                                    WHERE qpd_id ="' . $qpd_id_query . '"';
                $qp_delete_result = $this->db->query($qp_delete_query);

                $qpd_id_update = NULL;
            } else {
                $qpd_id_update = NULL;
            }
           
            //update AO table - remaining fields
            $ao_data_query = 'UPDATE assessment_occasions SET ao_name = "' . $ao_name . '", qpd_id = "' . $qpd_id_update . '", '
                             . 'ao_description = "' . $ao_description . '", ao_method_id = "' . $ao_list . '",'
                             . ' ao_type_id = "' . $mt_list . '", weightage = "' . $weightage . '", '
                             . 'max_marks = "' . $max_marks . '"'
                             . 'WHERE ao_id = "' . $ao_id . '"';
            $ao_result = $this->db->query($ao_data_query);

            //return 1 - on successful update
            return 1;
        } else {
            //individual = 2
            //on change from QP to individual
            if ($qpd_id_flag == 1) {
                //delete from qp definition table
                $qp_delete_query = 'DELETE FROM qp_definition WHERE qpd_id ="' . $qpd_id_query . '"';
                $qp_delete_result = $this->db->query($qp_delete_query);
            }

            //new individual occasion insert
            //qp definition table
            $qp_defn_data_query = 'INSERT INTO qp_definition(qpd_type, qp_rollout, crclm_id, crclm_term_id, crs_id, qpd_title, qpd_num_units)
                                   VALUES (3, 1, "' . $crclm_id . '", "' . $term_id . '", "' . $crs_id . '", "CIA Individual Assessment", 1)';
            $qp_defn_result = $this->db->query($qp_defn_data_query);

            //newly inserted qp id
            $last_insert_qp_defn_id = $this->db->insert_id();
            $data['pk_qpd_id'] = $last_insert_qp_defn_id;

            //qp unit definition table
            $qp_unit_defn_data_query = 'INSERT INTO qp_unit_definition(qpd_id, qp_unit_code, qp_total_unitquestion,
                                            qp_attempt_unitquestion, qp_utotal_marks, FM)
                                        VALUES ("' . $last_insert_qp_defn_id . '", "Unit - I", 1, 1, "' . $max_marks . '", 0)';
            $qp_unit_defn_result = $this->db->query($qp_unit_defn_data_query);

            //newly inserted qp unit id
            $last_insert_qp_unit_defn_id = $this->db->insert_id();

            //qp main question definition table
            $qp_mainquest_defn_data_query = 'INSERT INTO qp_mainquestion_definition(qp_unitd_id, qp_mq_code, qp_subq_code, qp_content, qp_subq_marks)
                                             VALUES ("' . $last_insert_qp_unit_defn_id . '", 1, "1a", "Individual Assessment", "' . $max_marks . '")';
            $qp_mainquest_defn_result = $this->db->query($qp_mainquest_defn_data_query);

            //newly inserted qp main question id
            $last_insert_qp_mainquest_defn_id = $this->db->insert_id();
            $data['main_qp_id'] = $last_insert_qp_mainquest_defn_id;

            //get size of CO and BL list
            $co_size = sizeof($co_list);
            $bl_size = sizeof($bl_list);
           
            $insert_query = 'INSERT INTO qp_mapping_definition(qp_mq_id, entity_id, actual_mapped_id, mapped_marks, mapped_percentage) VALUES';
            for ($i = 0; $i < $co_size; $i++) {
                $insert_query.='("' . $last_insert_qp_mainquest_defn_id . '", 11, "' . $co_list[$i] . '", "' . $max_marks . '", 100),';
            }

            //insert BL into qp mapping definition table
            for ($i = 0; $i < $bl_size; $i++) {
                $insert_query.= '("' . $last_insert_qp_mainquest_defn_id . '", 23, "' . $bl_list[$i] . '", "' . $max_marks . '", 100),';
            }
           
            //insert query
            $insert_query = substr($insert_query, 0, -1);
            $this->db->query($insert_query);
           
            //assessment occasions table
            $ao_data_query = 'UPDATE assessment_occasions
                              SET qpd_id = "' . $last_insert_qp_defn_id . '", ao_name = "' . $ao_name . '", ao_description = "' . $ao_description . '",
                                ao_method_id = "' . $ao_list . '", ao_type_id = "' . $mt_list . '", weightage = "' . $weightage . '", max_marks = "' . $max_marks . '"
                              WHERE ao_id = "' . $ao_id . '"';
            $ao_result = $this->db->query($ao_data_query);

            //update main question marks distribution based on the organization config settings
            $proc_query = 'call updateQPWeightageDistribution(' . $last_insert_qp_mainquest_defn_id . ')';
            $proc_data = $this->db->query($proc_query);

            return 1;
        }
    }

    /**
     * Function to delete AO details
     * @parameters: ao id, qpd id
     * @return: boolean
     */
    public function delete_ao_confirm($crs_id, $modal_delete_ao_id, $modal_delete_qpd_id = NULL) {
        $sec_query = 'SELECT section_id 
					 FROM assessment_occasions
					 WHERE crs_id = "' . $crs_id . '"
						AND ao_id ="' . $modal_delete_ao_id . '"';
        $sec_result = $this->db->query($sec_query);
        $result = $sec_result->result_array();
		
		$section_id = $result[0]['section_id'];
		
		if ($modal_delete_qpd_id != NULL) {
            //if QP
            $qp_delete_confirm_query = 'DELETE FROM qp_definition 
										WHERE qpd_id ="' . $modal_delete_qpd_id . '"';
            $qp_delete_confirm_result = $this->db->query($qp_delete_confirm_query);
			
			$query = $this->db->query('SELECT org_type  FROM organisation');
			$re = $query->result_array();
			//delete AO occasion attainment values from tier1 clo ao attainment table
			if($re[0]['org_type'] == 'TIER-I'){
			$sec_ao_attnmt_query = 'DELETE FROM tier1_section_clo_ao_attainment 
									WHERE crs_id ="' . $crs_id . '"
										AND ao_id ="' . $modal_delete_ao_id . '"';
            $sec_ao_attnmt_result = $this->db->query($sec_ao_attnmt_query);
			
			//delete section-wise all AO occasion attainment values from tier1 clo attainment table
			$sec_co_ovrll_attnmt_query = 'DELETE FROM tier1_section_clo_overall_cia_attainment 
										  WHERE crs_id ="' . $crs_id . '"
											AND section_id ="' . $section_id . '"';
            $sec_co_ovrll_attnmt_result = $this->db->query($sec_co_ovrll_attnmt_query);
			} else if($re[0]['org_type'] == 'TIER-II'){
				$sec_ao_attnmt_query = 'DELETE FROM tier_ii_section_clo_ao_attainment 
										WHERE crs_id ="' . $crs_id . '"
											AND ao_id ="' . $modal_delete_ao_id . '"';
				$sec_ao_attnmt_result = $this->db->query($sec_ao_attnmt_query);
				
				//delete section-wise all AO occasion attainment values from tier2 clo attainment table
				$sec_co_ovrll_attnmt_query = 'DELETE FROM tier_ii_section_clo_overall_cia_attainment 
											  WHERE crs_id ="' . $crs_id . '"
												AND section_id ="' . $section_id . '"';
				$sec_co_ovrll_attnmt_result = $this->db->query($sec_co_ovrll_attnmt_query);
			
			}
			//update course attainment finalize status flag to zero
			$ao_data_query = 'UPDATE map_courseto_course_instructor 
							  SET cia_finalise_flag = 0
							  WHERE crs_id ="' . $crs_id . '"
								AND section_id ="' . $section_id . '"';
            $ao_result = $this->db->query($ao_data_query);
        }

        //individual
        $ao_delete_confirm_query = 'DELETE FROM assessment_occasions 
									WHERE ao_id ="' . $modal_delete_ao_id . '"';
        $ao_delete_confirm_result = $this->db->query($ao_delete_confirm_query);

        return true;
    }

    /**
     * function to fetch rubrics details
     * @parameters: ao id
     * @return: 
     */
    public function define_rubrics($ao_method_id) {
        $rubrics_criteria_range_query = $this->db->query('SELECT rubrics_range_id, criteria_range
														  FROM ao_rubrics_range
														  WHERE ao_method_id = ' . $ao_method_id);
        $data['rubrics_criteria_range'] = $rubrics_criteria_range_query->result_array();
        $data['range_count'] = sizeof($data['rubrics_criteria_range']);

        $criteria_query = $this->db->query('SELECT rubrics_criteria_id, criteria
											FROM ao_rubrics_criteria
											WHERE ao_method_id = ' . $ao_method_id);
        $data['criteria_query'] = $criteria_query->result_array();
        $data['criteria_count'] = sizeof($data['criteria_query']);

        $rubrics_criteria_desc_query = $this->db->query('SELECT cd.criteria_description_id, cd.rubrics_range_id, r.criteria_range,
															cd.rubrics_criteria_id, c.criteria, cd.criteria_description
                                                         FROM ao_rubrics_criteria_desc cd
														 JOIN ao_rubrics_range r ON r.rubrics_range_id = cd.rubrics_range_id
														 JOIN ao_rubrics_criteria c ON c.rubrics_criteria_id = cd.rubrics_criteria_id
														 WHERE (cd.rubrics_range_id, cd.rubrics_criteria_id)
														 IN (SELECT r.rubrics_range_id, c.rubrics_criteria_id
														 FROM ao_rubrics_range r, ao_rubrics_criteria c
														 WHERE c.ao_method_id =' . $ao_method_id . ' AND r.ao_method_id =' . $ao_method_id . ')
														 ORDER BY c.rubrics_criteria_id ASC 
														');
        $data['rubrics_criteria_desc'] = $rubrics_criteria_desc_query->result_array();
        $data['rubrics_count'] = sizeof($data['rubrics_criteria_desc']);

        return $data;
    }

	/**
	 * Function to
	 *
	 *
	 */
    public function save_weightage($crclm_id, $course, $cia_total_weightage , $mte_total_weightage , $tee_total_weightage) {
        $cia_data = 'Update course set total_cia_weightage = "' . $cia_total_weightage . '", total_tee_weightage="' . $tee_total_weightage . '"  , total_mte_weightage = "'.  $mte_total_weightage.'" 
					 WHERE crclm_id = "' . $crclm_id . '" and crs_title = "' . $course . '"';
        $cia_value = $this->db->query($cia_data);
        return $cia_value;
    }

	/**
	 * Function to
	 *
	 *
	 */
    public function save_tee_db($text, $crclm_id, $course) {
        $cia_data = 'Update course set total_tee_weightage = "' . $text . '" 
					 WHERE crclm_id = "' . $crclm_id . '" and crs_title = "' . $course . '"';
        $cia_value = $this->db->query($cia_data);
        return $cia_value;
    }
    
    
    /*
     * Function to get the section list
	 * @parameters: curriculum id, term id and course id
	 * @return: 
     */
    public function section_list($crclm_id, $term_id, $course_id){
        $section_list_query = 'SELECT map_ci.section_id, mtd.mt_details_id as section_id, mtd.mt_details_name as section_name'
								. ' FROM map_courseto_course_instructor as map_ci'
								. ' LEFT JOIN master_type_details as mtd ON mtd.mt_details_id = map_ci.section_id'
								. ' WHERE map_ci.crs_id = "'.$course_id.'" '
								. ' AND map_ci.crclm_term_id = "'.$term_id.'" '
								. ' AND map_ci.crclm_id = "'.$crclm_id.'" ORDER BY mtd.mt_details_id';
        $section_list_data = $this->db->query($section_list_query);
        $section_list_result = $section_list_data->result_array();
        
		return $section_list_result;
    }
    
    /*
     * Function to get the list of occasions
     * @parameters: crclm_id, term_id, course_id
     * @return: List of Occasions belong to the partical course
     */
    public function occasion_list($crclm_id, $term_id, $course_id) {
        $course_query =  ' SELECT DISTINCT a.ao_id, qpd_id, ao_name, ao_description, a.ao_method_id,
								am.ao_method_name, ao_type_id, m.mt_details_name,username, title, first_name, last_name,
								c.crs_id, c.crs_title, c.crs_code, c.crs_mode,a.weightage,a.max_marks,a.status AS ao_status,
                                mtd.mt_details_id as section_id, mtd.mt_details_name as section_name, map_ci.course_instructor_id
							FROM assessment_occasions AS a, course AS c, course_clo_owner AS cr,
								ao_method AS am, master_type_details AS m, users AS u,  master_type_details as mtd, map_courseto_course_instructor as map_ci
							WHERE a.crclm_id = "'.$crclm_id.'"
								AND a.term_id = "'.$term_id.'"
								AND a.crs_id = "'.$course_id.'"
								AND a.crs_id = c.crs_id AND a.ao_method_id = am.ao_method_id
								AND a.section_id = mtd.mt_details_id
								AND a.crs_id = map_ci.crs_id
								AND a.section_id = map_ci.section_id
								AND m.mt_details_id = a.ao_type_id
								AND cr.crs_id = a.crs_id
								AND u.id = map_ci.course_instructor_id ' ;
        $occasion_data = $this->db->query($course_query);
        $occasion_list = $occasion_data->result_array();
		
        return $occasion_list;
    }
    
    /*
     * Function to import to CIA occasions 
	 *
	 *
     */
    public function import_occasion($crclm_id, $term_id, $course_id, $ao_id,$ao_name, $to_section_id) {
         $this->db->SELECT('ao_id, qpd_id, ao_name, ao_description, ao_method_id, ao_type_id, co_id, pi_code, bloom_level_id, weightage, max_marks,
							avg_cia_marks, crclm_id, term_id, crs_id, section_id, created_by, modified_by, created_date, modified_date, status')
                  ->FROM('assessment_occasions')
                  ->WHERE_IN('ao_id', $ao_id);
                $data = $this->db->get()->result_array();
                
                $size = count($data);
				
                $occa_query = 'SELECT COUNT(ao_name) as size,GROUP_CONCAT(ao_id,",",ao_description ORDER BY ao_name ASC) as name FROM assessment_occasions '
								. 'WHERE crclm_id = "'.$crclm_id.'" 
										AND term_id = "'.$term_id.'" '
								. 'AND crs_id = "'.$course_id.'" '
								. ' AND section_id = "'.$to_section_id.'" ';
                    $occa_count_data = $this->db->query($occa_query);
                    $occa_count_val = $occa_count_data->row_array();
					
                   // if($occa_count_val['size'] == 0) {
                        for($i = 0; $i < $size; $i++) {
							if($data[$i]['ao_type_id'] != 2) {
								$occasion_data = array(
								'qpd_id' => null,
								'ao_name' => $data[$i]['ao_name'],
								'ao_description' => $data[$i]['ao_description'],
								'ao_method_id' => $data[$i]['ao_method_id'],
								'ao_type_id' => $data[$i]['ao_type_id'],
								'co_id' => $data[$i]['co_id'],
								'bloom_level_id' => $data[$i]['bloom_level_id'],
								'weightage' => $data[$i]['weightage'],
								'max_marks' => $data[$i]['max_marks'],
								'avg_cia_marks' => $data[$i]['avg_cia_marks'],
								'crclm_id' => $crclm_id,
								'term_id' => $term_id,
								'crs_id' => $course_id,
								'section_id' => $to_section_id,
								'created_by' => $data[$i]['created_by'],
								'modified_by' => $this->ion_auth->user()->row()->id,
								'created_date' => $data[$i]['created_date'],
								'modified_date' => date('y-m-d'),
								'status' => 0  );
								$this->db->insert('assessment_occasions', $occasion_data);
                       } else {
                           $occasion_data = array(
							'qpd_id' => null,
							'ao_name' => $data[$i]['ao_name'],
							'ao_description' => $data[$i]['ao_description'],
							'ao_method_id' => $data[$i]['ao_method_id'],
							'ao_type_id' => $data[$i]['ao_type_id'],
							'co_id' => $data[$i]['co_id'],
							'bloom_level_id' => $data[$i]['bloom_level_id'],
							'weightage' => $data[$i]['weightage'],
							'max_marks' => $data[$i]['max_marks'],
							'avg_cia_marks' => $data[$i]['avg_cia_marks'],
							'crclm_id' => $crclm_id,
							'term_id' => $term_id,
							'crs_id' => $course_id,
							'section_id' => $to_section_id,
							'created_by' => $data[$i]['created_by'],
							'modified_by' => $this->ion_auth->user()->row()->id,
							'created_date' => $data[$i]['created_date'],
							'modified_date' => date('y-m-d'),
							'status' => 0
						  );
						  
                        $this->db->insert('assessment_occasions',$occasion_data);
                        $new_ao_id = $this->db->insert_id();
						
                        // creating question paper in backend for ocaation type 2 i.e. CIA Individual assessment.
                        $date = date('y-m-d');
                        $loggedin_user_id = $this->ion_auth->user()->row()->id;
                        $new_qp_id = $this->db->query('SELECT import_createQP('.$data[$i]['qpd_id'].',3,'.$crclm_id.','.$term_id.','.$course_id.','.$loggedin_user_id.','.$date.') as new_qp_id')->row_array();
                        
                        $qp_unit_def_query = 'SELECT qpd_unitd_id, qpd_id, qp_unit_code, qp_total_unitquestion, qp_attempt_unitquestion, qp_utotal_marks,
												created_by, created_date, modified_by, modified_date, FM 
											  FROM qp_unit_definition 
											  WHERE qpd_id = "'.$new_qp_id['new_qp_id'].'" ';
                        $qp_unit_def_data = $this->db->query($qp_unit_def_query);
                        $qp_unit_def_result = $qp_unit_def_data->result_array();
                        $qp_unit_def_size = count($qp_unit_def_result);
                        
                        for($k = 0; $k<$qp_unit_def_size; $k++) {
                           $qp_unit_id[]=  $qp_unit_def_result[$k]['qpd_unitd_id'];
						}

						$this->db->SELECT('DISTINCT(map.clo_id),cl.clo_code')
								->FROM('clo_po_map as map')
								->JOIN('clo as cl','cl.clo_id = map.clo_id')
								->WHERE('map.crs_id',$course_id)
								->ORDER_BY('map.clo_id');
						$crs_co_data = $this->db->get()->result_array();
						$to_co_size = count($crs_co_data);

						$this->db->SELECT('DISTINCT(map.po_id),p.po_reference')
								->FROM('clo_po_map as map')
								->JOIN('po as p','p.po_id = map.po_id')
								->WHERE('map.crs_id',$course_id)
								->ORDER_BY('map.po_id');
						$po_data = $this->db->get()->result_array();

						$to_po_size = count($po_data);

						$this->db->SELECT('qp_main.qp_mq_id, qp_main.qp_subq_marks, qp_main.created_by, qp_main.created_date, qp_map.qp_map_id, qp_map.actual_mapped_id, qp_map.mapped_percentage, cl.clo_code,cl.clo_id')
								->FROM('qp_mainquestion_definition as qp_main')
								->JOIN('qp_mapping_definition as qp_map','qp_map.qp_mq_id = qp_main.qp_mq_id')
								->JOIN('clo as cl','qp_map.actual_mapped_id = cl.clo_id')
								->WHERE_IN('qp_main.qp_unitd_id',$qp_unit_id)
								->WHERE('qp_map.entity_id','11')
								->ORDER_BY('qp_map.qp_map_id');
						$qp_map_co_data = $this->db->get()->result_array();

						$this->db->SELECT('qp_main.qp_mq_id,qp_main.qp_subq_marks, qp_main.created_by, qp_main.created_date, qp_map.qp_map_id,
                                                                    qp_map.actual_mapped_id,qp_map.mapped_percentage, p.po_reference,p.po_id')
								->FROM('qp_mainquestion_definition as qp_main')
								->JOIN('qp_mapping_definition as qp_map','qp_map.qp_mq_id = qp_main.qp_mq_id')
								->JOIN('po as p','qp_map.actual_mapped_id = p.po_id')
								->WHERE_IN('qp_main.qp_unitd_id',$qp_unit_id)
								->WHERE('qp_map.entity_id','6')->ORDER_BY('qp_map.qp_map_id');
						$qp_map_po_data = $this->db->get()->result_array();
						$co_size = count($qp_map_co_data);

						for($co_i = 0; $co_i < $co_size; $co_i++) {
							for($co_j = 0; $co_j < $to_co_size; $co_j++){
								if($qp_map_co_data[$co_i]['clo_code'] == $crs_co_data[$co_j]['clo_code']) {
								  $update_query = 'UPDATE qp_mapping_definition SET actual_mapped_id = "'.$crs_co_data[$co_j]['clo_id'].'" '
													. 'WHERE qp_map_id = "'.$qp_map_co_data[$co_i]['qp_map_id'].'" AND entity_id = 11';
								   $update_data = $this->db->query($update_query);

								   $po_select_query = 'SELECT DISTINCT(po_id) 
													   FROM clo_po_map 
													   WHERE clo_id = '.$crs_co_data[$co_j]['clo_id'].'  
															AND crs_id='.$course_id.' ';
								   $po_id_data = $this->db->query($po_select_query);
								   $po_id_res = $po_id_data->result_array(); 
								   
								   foreach($po_id_res as $po_id) {
										$po_insert_array = array(
										  'qp_mq_id' => $qp_map_co_data[$co_i]['qp_mq_id'],  
										  'entity_id' => 6,  
										  'actual_mapped_id' => $po_id['po_id'],  
										  'created_by' =>$qp_map_co_data[$co_i]['created_by'],  
										  'created_date' =>$qp_map_co_data[$co_i]['created_date'],
										  'modified_by' => $loggedin_user_id,  
										  'modified_date' => date('y-m-d'),  
										 'mapped_marks' =>$qp_map_co_data[$co_i]['qp_subq_marks'],  
										 'mapped_percentage' =>$qp_map_co_data[$co_i]['mapped_percentage'], 
										);
										$this->db->insert('qp_mapping_definition',$po_insert_array);
								   }
								}
							}
						}
						
						$update_query = 'UPDATE assessment_occasions SET qpd_id = '.$new_qp_id['new_qp_id'].' WHERE ao_id = '.$new_ao_id.' ';
						$update_data = $this->db->query($update_query);
					}
                        
				}
				
				$data = 'true';
        return $data; 
    }
    
    /*
     * Function to overwrite the occasions
     */
    public function occasion_import_overwrite($crclm_id, $term_id, $course_id, $from_ao_id, $ao_id,$to_section_id){
        
        $ao_id_val = explode(',', $ao_id);
        $delete_ao_id = explode(',',$from_ao_id);
        $select_query = $this->db->SELECT('ao_name')
                        ->FROM('assessment_occasions')
                        ->WHERE_IN('ao_id',$delete_ao_id)->get()->result_array();
        //$ao_name_array = array();
        foreach($select_query as $aoname){
            $delete_query = 'DELETE FROM assessment_occasions WHERE  crs_id ="'.$course_id.'" AND ao_name ="'.$aoname['ao_name'].'" AND section_id = "'.$to_section_id.'" ';
            $delete_record = $this->db->query($delete_query);
        }
       // $ao_names = implode(',',$ao_name_array);
    $from_ao_id_val = explode(',', $from_ao_id);
        
        $this->db->SELECT('ao_id, qpd_id, ao_name, ao_description, ao_method_id, ao_type_id, co_id, pi_code, bloom_level_id, weightage, max_marks, avg_cia_marks, crclm_id, term_id, crs_id, section_id, created_by, modified_by, created_date, modified_date, status')
                 ->FROM('assessment_occasions')
                 ->WHERE_IN('ao_id',$from_ao_id_val);
                $data = $this->db->get()->result_array();
               
                $size_vlue = count($data);
                
              for($i=0; $i<$size_vlue; $i++){
                    if($data[$i]['ao_type_id'] != 2){
                    $occasion_data = array(
                        'qpd_id' => null,
                        'ao_name' =>$data[$i]['ao_name'],
                        'ao_description' =>$data[$i]['ao_description'],
                        'ao_method_id' =>$data[$i]['ao_method_id'],
                        'ao_type_id' =>$data[$i]['ao_type_id'],
                        'co_id' =>$data[$i]['co_id'],
                        'bloom_level_id' =>$data[$i]['bloom_level_id'],
                        'weightage' =>$data[$i]['weightage'],
                        'max_marks' =>$data[$i]['max_marks'],
                        'avg_cia_marks' =>$data[$i]['avg_cia_marks'],
                        'crclm_id' =>$crclm_id,
                        'term_id' =>$term_id,
                        'crs_id' =>$course_id,
                        'section_id' =>$to_section_id,
                        'created_by' =>$data[$i]['created_by'],
                        'modified_by' => $this->ion_auth->user()->row()->id,
                        'created_date' =>$data[$i]['created_date'],
                        'modified_date' =>  date('y-m-d'),
                        'status' =>0  );
                        $this->db->insert('assessment_occasions',$occasion_data);
                       }else{
                           $occasion_data = array(
                        'qpd_id' => null,
                        'ao_name' =>$data[$i]['ao_name'],
                        'ao_description' =>$data[$i]['ao_description'],
                        'ao_method_id' =>$data[$i]['ao_method_id'],
                        'ao_type_id' =>$data[$i]['ao_type_id'],
                        'co_id' =>$data[$i]['co_id'],
                        'bloom_level_id' =>$data[$i]['bloom_level_id'],
                        'weightage' =>$data[$i]['weightage'],
                        'max_marks' =>$data[$i]['max_marks'],
                        'avg_cia_marks' =>$data[$i]['avg_cia_marks'],
                        'crclm_id' =>$crclm_id,
                        'term_id' =>$term_id,
                        'crs_id' =>$course_id,
                        'section_id' =>$to_section_id,
                        'created_by' =>$data[$i]['created_by'],
                        'modified_by' => $this->ion_auth->user()->row()->id,
                        'created_date' =>$data[$i]['created_date'],
                        'modified_date' =>  date('y-m-d'),
                        'status' =>0  );
                        $this->db->insert('assessment_occasions',$occasion_data);
                        $new_ao_id = $this->db->insert_id();
                         // creating question paper in backend for ocaation type 2 i.e. CIA Individual assessment.
                        $date = date('y-m-d');
                        $loggedin_user_id = $this->ion_auth->user()->row()->id;
                        $new_qp_id = $this->db->query('SELECT import_createQP('.$data[$i]['qpd_id'].',3,'.$crclm_id.','.$term_id.','.$course_id.','.$loggedin_user_id.','.$date.') as new_qp_id')->row_array();
                        
                        $qp_unit_def_query = 'SELECT qpd_unitd_id, qpd_id, qp_unit_code, qp_total_unitquestion, qp_attempt_unitquestion, qp_utotal_marks, created_by, created_date, modified_by, modified_date, FM FROM qp_unit_definition WHERE qpd_id = "'.$new_qp_id['new_qp_id'].'" ';
                        $qp_unit_def_data = $this->db->query($qp_unit_def_query);
                        $qp_unit_def_result = $qp_unit_def_data->result_array();
                        $qp_unit_def_size = count($qp_unit_def_result);
                        
                        for($k=0;$k<$qp_unit_def_size;$k++){
                           $qp_unit_id[]=  $qp_unit_def_result[$k]['qpd_unitd_id'];
                          }

                            $this->db->SELECT('DISTINCT(map.clo_id),cl.clo_code')
                                    ->FROM('clo_po_map as map')
                                    ->JOIN('clo as cl','cl.clo_id = map.clo_id')
                                    ->WHERE('map.crs_id',$course_id)
                                    ->ORDER_BY('map.clo_id');
                            $crs_co_data = $this->db->get()->result_array();
                            $to_co_size = count($crs_co_data);

                            $this->db->SELECT('DISTINCT(map.po_id),p.po_reference')
                                    ->FROM('clo_po_map as map')
                                    ->JOIN('po as p','p.po_id = map.po_id')
                                    ->WHERE('map.crs_id',$course_id)
                                    ->ORDER_BY('map.po_id');
                            $po_data = $this->db->get()->result_array();

                            $to_po_size = count($po_data);

                            $this->db->SELECT('qp_main.qp_mq_id, qp_main.qp_subq_marks, qp_main.created_by, qp_main.created_date, qp_map.qp_map_id, qp_map.actual_mapped_id, qp_map.mapped_percentage, cl.clo_code,cl.clo_id')
                                    ->FROM('qp_mainquestion_definition as qp_main')
                                     ->JOIN('qp_mapping_definition as qp_map','qp_map.qp_mq_id = qp_main.qp_mq_id')
                                    ->JOIN('clo as cl','qp_map.actual_mapped_id = cl.clo_id')
                                    ->WHERE_IN('qp_main.qp_unitd_id',$qp_unit_id)
                                    ->WHERE('qp_map.entity_id','11')
                                    ->ORDER_BY('qp_map.qp_map_id');
                            $qp_map_co_data = $this->db->get()->result_array();

                            $this->db->SELECT('qp_main.qp_mq_id,qp_main.qp_subq_marks, qp_main.created_by, qp_main.created_date, qp_map.qp_map_id, qp_map.actual_mapped_id,qp_map.mapped_percentage, p.po_reference,p.po_id')
                                    ->FROM('qp_mainquestion_definition as qp_main')
                                     ->JOIN('qp_mapping_definition as qp_map','qp_map.qp_mq_id = qp_main.qp_mq_id')
                                    ->JOIN('po as p','qp_map.actual_mapped_id = p.po_id')
                                    ->WHERE_IN('qp_main.qp_unitd_id',$qp_unit_id)
                                    ->WHERE('qp_map.entity_id','6')->ORDER_BY('qp_map.qp_map_id');
                            $qp_map_po_data = $this->db->get()->result_array();
                            $co_size = count($qp_map_co_data);

                            for($co_i = 0; $co_i<$co_size;$co_i++){
                                for($co_j=0;$co_j<$to_co_size;$co_j++){
                                    if($qp_map_co_data[$co_i]['clo_code'] == $crs_co_data[$co_j]['clo_code']){
                                      $update_query = 'UPDATE qp_mapping_definition SET actual_mapped_id = "'.$crs_co_data[$co_j]['clo_id'].'" '
                                            . 'WHERE qp_map_id = "'.$qp_map_co_data[$co_i]['qp_map_id'].'" AND entity_id = 11';
                                       $update_data = $this->db->query($update_query);

                                       $po_select_query = 'SELECT DISTINCT(po_id) FROM clo_po_map WHERE clo_id = '.$crs_co_data[$co_j]['clo_id'].'  AND crs_id='.$course_id.' ';
                                       $po_id_data = $this->db->query($po_select_query);
                                       $po_id_res = $po_id_data->result_array();  
                                       foreach($po_id_res as $po_id){

                                            $po_insert_array = array(
                                              'qp_mq_id' => $qp_map_co_data[$co_i]['qp_mq_id'],  
                                              'entity_id' => 6,  
                                              'actual_mapped_id' => $po_id['po_id'],  
                                              'created_by' =>$qp_map_co_data[$co_i]['created_by'],  
                                              'created_date' =>$qp_map_co_data[$co_i]['created_date'],
                                              'modified_by' => $loggedin_user_id,  
                                              'modified_date' => date('y-m-d'),  
                                             'mapped_marks' =>$qp_map_co_data[$co_i]['qp_subq_marks'],  
                                             'mapped_percentage' =>$qp_map_co_data[$co_i]['mapped_percentage'], 
                                            );
                                            $this->db->insert('qp_mapping_definition',$po_insert_array);
                                       }

                                    }
                                }
                            }
                            $update_query = 'UPDATE assessment_occasions SET qpd_id = '.$new_qp_id['new_qp_id'].' WHERE ao_id = '.$new_ao_id.' ';
                            $update_data = $this->db->query($update_query);
                            }
                        }
                        
                        $result = 'true';
                        return $result; 
    }
    
    /*
     * Function to import the occasions without overwriting it.
     */
    public function occasion_import_continue($crclm_id, $term_id, $course_id, $from_ao_id, $ao_id,$ao_name){
        $ao_id_val = explode(',', $ao_id);
        $from_ao_id_val = explode(',', $from_ao_id);
        $ao_name_val = explode(',',$ao_name);
        $this->db->SELECT('ao_id, qpd_id, ao_name, ao_description, ao_method_id, ao_type_id, co_id, pi_code, bloom_level_id, weightage, max_marks, avg_cia_marks, crclm_id, term_id, crs_id, created_by, modified_by, created_date, modified_date, status')
                 ->FROM('assessment_occasions')
                 ->WHERE_IN('ao_id',$from_ao_id_val)
                 ->WHERE_NOT_IN('ao_name',$ao_name_val)
                 ->ORDER_BY('ao_name');
                $data = $this->db->get()->result_array();
                $size = count($data);
               
            if(!empty($data)){
               
                for($i=0; $i<$size; $i++){
                        $occasion_data = array(
                        'qpd_id' => null,
                        'ao_name' =>$data[$i]['ao_name'],
                        'ao_description' =>$data[$i]['ao_description'],
                        'ao_method_id' =>$data[$i]['ao_method_id'],
                        'ao_type_id' =>$data[$i]['ao_type_id'],
                        'co_id' =>$data[$i]['co_id'],
                        'bloom_level_id' =>$data[$i]['bloom_level_id'],
                        'weightage' =>$data[$i]['weightage'],
                        'max_marks' =>$data[$i]['max_marks'],
                        'avg_cia_marks' =>$data[$i]['avg_cia_marks'],
                        'crclm_id' =>$crclm_id,
                        'term_id' =>$term_id,
                        'crs_id' =>$course_id,
                        'created_by' =>$data[$i]['created_by'],
                        'modified_by' => $this->ion_auth->user()->row()->id,
                        'created_date' =>$data[$i]['created_date'],
                        'modified_date' =>  date('y-m-d'),
                        'status' =>0  );
                        $this->db->insert('assessment_occasions',$occasion_data);
                }
                
                $result = 'true';
            }else{
                $result = 'false';
            }
                
                
                return $result;
        }
        
        /*
         * Function to delete the rubrics and qp data
         * @param:
         * @return:
         */
        public function delete_rubrics_and_qp_data($ao_id, $qpd_id, $crs_id,$section_id){
            
            $ao_method_id_query = 'SELECT ao_method_id FROM assessment_occasions WHERE ao_id = "'.$ao_id.'" ';
            $ao_method_id_data = $this->db->query($ao_method_id_query); 
            $ao_method_id = $ao_method_id_data->row_array();
            
            if($qpd_id != 0){
                
                $delete_rubrics_data = 'DELETE FROM ao_rubrics_criteria WHERE ao_method_id ="'.$ao_method_id['ao_method_id'].'" ';
                $delete_criteria = $this->db->query($delete_rubrics_data);
                
                $delete_rubrics_range_data = 'DELETE FROM ao_rubrics_range WHERE ao_method_id ="'.$ao_method_id['ao_method_id'].'" ';
                $delete_range = $this->db->query($delete_rubrics_range_data);
                
                $delete_qp_data = 'DELETE FROM qp_definition WHERE qpd_id = "'.$qpd_id.'" ';
                $delete_qp = $this->db->query($delete_qp_data);
                return true;
            }else{
                
                $delete_rubrics_data = 'DELETE FROM ao_rubrics_criteria WHERE ao_method_id ="'.$ao_method_id['ao_method_id'].'" ';
                $delete_criteria = $this->db->query($delete_rubrics_data);
                
                $delete_rubrics_range_data = 'DELETE FROM ao_rubrics_range WHERE ao_method_id ="'.$ao_method_id['ao_method_id'].'" ';
                $delete_range = $this->db->query($delete_rubrics_range_data);
                return true;
            }
            
        }
        
        /*
         * Function to get the rubrics id
         * @param:
         * @return:
         */
        public function get_rubrics_id(){
            $query = 'SELECT mt_details_id FROM master_type_details WHERE mt_details_name = "Rubrics" ';
            $query_data = $this->db->query($query);
            $query_res = $query_data->row_array();
            return $query_res['mt_details_id'];
        }
		
		public function  fetch_bl_flag( $crclm_id , $crs_id){
			
            $query = $this->db->query("select clo_bl_flag from course where crs_id = '".$crs_id."' ");
            return  $query->result_array();
		}

}