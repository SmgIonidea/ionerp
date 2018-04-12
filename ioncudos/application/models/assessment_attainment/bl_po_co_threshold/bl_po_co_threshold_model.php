<?php
/**
* Description	:	Bloom's Level, Program Outcome & Course Threshold model
* Created		:	28-04-2015
* Author 		:   Arihant Prasad
* Modification History:
* Date				Modified By				Description
-------------------------------------------------------------------------------
*/
?>

<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Bl_po_co_threshold_model extends CI_Model {

    /*
     * Function to fetch curriculum details
	 * @parameters: user id
     * @return: curriculum id and name
     */
    public function list_curriculum($user_dept_id) {
	$loggedin_user_id = $this->ion_auth->user()->row()->id;
		if($this->ion_auth->is_admin()) {
			$curriculum_list_query = 'SELECT DISTINCT crclm_id, crclm_name 
									  FROM curriculum AS c
									  WHERE status = 1
									  ORDER BY c.crclm_name ASC';
			$curriculum_list_data = $this->db->query($curriculum_list_query);
			$curriculum_list = $curriculum_list_data->result_array();
		} else if($this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')){
			$curriculum_list_query = 'SELECT DISTINCT c.crclm_id, c.crclm_name
									  FROM curriculum AS c, department AS d, program AS p
									  WHERE d.dept_id = "' . $user_dept_id . '"
										AND p.dept_id = "' . $user_dept_id . '"
										AND c.pgm_id = p.pgm_id
										AND c.status = 1
									  ORDER BY c.crclm_name ASC';
			$curriculum_list_data = $this->db->query($curriculum_list_query);
			$curriculum_list = $curriculum_list_data->result_array();
		}else{
		$curriculum_list_query = 'SELECT DISTINCT c.crclm_id, c.crclm_name
				FROM curriculum AS c, users AS u, program AS p , course_clo_owner AS clo
				WHERE u.id = "' . $loggedin_user_id . '" 
				AND u.user_dept_id = p.dept_id
				AND c.pgm_id = p.pgm_id
				AND u.id = clo.clo_owner_id
				AND c.crclm_id = clo.crclm_id
				AND c.status = 1
				ORDER BY c.crclm_name ASC';
			$curriculum_list_data = $this->db->query($curriculum_list_query);
			$curriculum_list = $curriculum_list_data->result_array();
		}
		
		return $curriculum_list;
	}
	
	/**
	 * Function to fetch term details from curriculum term table
	 * @parameters: curriculum id and term id
	 * @return: term name and curriculum term id
	 */
    public function term_details($curriculum_id) {
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
		$term_result = $this->db->query($term_list_query);
		$term_result_data = $term_result->result_array();
		$term_data['term_result_data'] = $term_result_data;
			
		return $term_data;
    }
	
	/**
	 * Function to fetch bloom level threshold details
	 * @parameters: curriculum id and term id
	 * @return: term name and curriculum term id
	 */
	public function bloom_level_model($crclm_id, $term_id) {
		$bloom_level_threshold_query = 'SELECT mcb.bloom_id, description, cia_bloomlevel_minthreshhold, mte_bloomlevel_minthreshhold, tee_bloomlevel_minthreshhold , bloomlevel_studentthreshhold, 
											justify, level, bloom_actionverbs
										FROM map_crclm_bloomlevel AS mcb, bloom_level as bl
										WHERE mcb.crclm_id = "' . $crclm_id . '"
											AND bl.bloom_id = mcb.bloom_id';
		$bloom_level_threshold_data = $this->db->query($bloom_level_threshold_query);
		$bloom_level_threshold = $bloom_level_threshold_data->result_array();
		$data['bloom_level_threshold'] = $bloom_level_threshold;
		
		return $data;
	}	
	
	/**
	 * Function to fetch bloom level threshold details
	 * @parameters: curriculum id and term id
	 * @return: term name and curriculum term id
	 */
	public function bloom_level_model_data($crclm_id, $term_id ,$crs_id) {
				$query = $this->db->query('SELECT CONCAT(COALESCE(cognitive_domain_flag,""),",",COALESCE(affective_domain_flag,""),",",COALESCE(psychomotor_domain_flag,""))  crs_id from course where crs_id= "'.$crs_id.'"');
			$re = $query->result_array();
			$count = count($re[0]['crs_id']);
			
			$set_data = (explode(",",$re[0]['crs_id']));

		$sk=0;
		$bloom_domain_query = $this->db->query('SELECT bld_id,bld_name,status FROM bloom_domain');
        $bloom_domain_data = $bloom_domain_query->result_array();
		foreach($bloom_domain_data as $bdd){
			
			if ($set_data[$sk] == 1 && $bdd['status'] == 1) {
				$bld_id [] = $bdd['bld_id'];
				
			}
		$sk++;
		}
		$Bld_id_data = implode (",", $bld_id);

		$bld_id_single = str_replace("'", "", $Bld_id_data);	
                
		$bloom_lvl_query = 'select * from  bloom_level where bld_id IN('.$bld_id_single.') ORDER BY LPAD(LOWER(level),5,0) ASC';
	
	
		$bloom_level_threshold_query = 'SELECT mcb.bloom_id, description, cia_bloomlevel_minthreshhold , mte_bloomlevel_minthreshhold , tee_bloomlevel_minthreshhold ,bloomlevel_studentthreshhold, justify, level, bloom_actionverbs FROM map_crclm_bloomlevel AS mcb, bloom_level as bl
										WHERE mcb.crclm_id = "' . $crclm_id . '"
										AND bl.bld_id IN('.$bld_id_single.')
										AND bl.bloom_id = mcb.bloom_id';
		$bloom_level_threshold_data = $this->db->query($bloom_level_threshold_query);
		$bloom_level_threshold = $bloom_level_threshold_data->result_array();
		$data['bloom_level_threshold'] = $bloom_level_threshold;		
		return $data;
	}
	
	/**
	 * Function to fetch po threshold details
	 * @parameters: curriculum id and term id
	 * @return: term name and curriculum term id
	 */
	public function program_outcome_model($crclm_id, $term_id) {
		$program_outcome_threshold_query = 'SELECT po_id, po_reference, po_statement, po_minthreshhold, po_studentthreshhold, justify
											FROM po
											WHERE crclm_id = "' . $crclm_id . '"';
		$program_outcome_threshold_data = $this->db->query($program_outcome_threshold_query);
		$program_outcome_threshold = $program_outcome_threshold_data->result_array();
		$data['program_outcome_threshold'] = $program_outcome_threshold;
		
		return $data;
	}
	
	/**
	 * Function to fetch course threshold details
	 * @parameters: curriculum id and term id
	 * @return: term name and curriculum term id
	 */
	public function course_model($crclm_id, $term_id) {
	
		$query = $this->db->query('select mte_flag  from organisation');
		$mte_flag  = $query->result_array();
		
		$course_threshold_query = 'SELECT crs_id, crs_title, crs_code, cia_course_minthreshhold, mte_course_minthreshhold , tee_course_minthreshhold, course_studentthreshhold, justify
								   FROM course
								   WHERE crclm_id = "' . $crclm_id . '"
									  AND crclm_term_id = "' . $term_id . '"   
									  AND state_id >= 4';
		$course_threshold_data = $this->db->query($course_threshold_query);
		$course_threshold = $course_threshold_data->result_array();
		$data['course_threshold'] = $course_threshold;
		$data['mte_flag'] = $mte_flag;
		
		return $data;
	}
		/**
	 * Function to fetch course threshold details for perticular course owner 
	 * @parameters: curriculum id and term id
	 * @return: term name and curriculum term id
	 */
	public function course_model_for_course_owner($crclm_id, $term_id,$loggedin_user_id) {
	$query = $this->db->query('select mte_flag  from organisation');
		$mte_flag  = $query->result_array();
		
		$course_threshold_query = ' SELECT c1.crs_id, c1.crs_title, c1.crs_code, c1.crclm_term_id, c1.cia_course_minthreshhold,c1.mte_course_minthreshhold,c1.tee_course_minthreshhold, c1.course_studentthreshhold, c1.justify FROM course c1
        join course_clo_owner c on c.crs_id=c1.crs_id
								   WHERE c1.crclm_id ="'.$crclm_id.'"
									  AND c1.crclm_term_id = "'.$term_id.'"
										AND  c.clo_owner_id="'.$loggedin_user_id.'"';
		$course_threshold_data = $this->db->query($course_threshold_query);
		$course_threshold = $course_threshold_data->result_array();
		$data['course_threshold'] = $course_threshold;
		$data['mte_flag'] = $mte_flag;
		return $data;
	}
	

	
	/**
	 * Function to insert bloom level threshold, po threshold and course threshold
	 * @parameters: array
	 * @return: boolean
	 */
	public function bl_values($value_array, $crclm_id) {
		$count_val = sizeof($value_array);
	
		//insert minimum, student and justify data into map_crclm_bloomlevel table
		for($i = 0; $i < $count_val; $i++) {		
			$update_query = 'UPDATE map_crclm_bloomlevel
							 SET cia_bloomlevel_minthreshhold = "' . $value_array[$i++] . '",
                                                             tee_bloomlevel_minthreshhold = "' . $value_array[$i++] . '",
								bloomlevel_studentthreshhold = "' . $value_array[$i++] . '",
								justify = "' . $value_array[$i++] . '"
							 WHERE crclm_id = "' . $crclm_id . '"
								AND bloom_id = "' . $value_array[$i] . '"';
			$update_query_result = $this->db->query($update_query);
		}
		
		return true;
	}
	
	/**
	 * Function to insert po threshold
	 * @parameters: array
	 * @return: boolean
	 */
	public function po_values($value_array, $crclm_id) {
		$count_val = sizeof($value_array);
	
		//insert minimum, student and justify data into po table
		for($i = 0; $i < $count_val; $i++) {		
			$update_query = 'UPDATE po
							 SET po_minthreshhold = "' . $value_array[$i++] . '",
								po_studentthreshhold = "' . $value_array[$i++] . '",
								justify = "' . $value_array[$i++] . '"
							 WHERE crclm_id = "' . $crclm_id . '"
								AND po_id = "' . $value_array[$i] . '"';
			$update_query_result = $this->db->query($update_query);
		}
		
		return true;
	}
	
	/**
	 * Function to insert course
	 * @parameters: array
	 * @return: boolean
	 */
	public function crs_values($value_array, $crclm_id, $term_id , $mte_flag) {
		$count_val = sizeof($value_array);
		if($mte_flag == 0){
			//insert minimum, student and justify data into course table
			for($i = 0, $j = 0; $i < $count_val; $i++, $j++) {

				$update_course_query = 'UPDATE course
										SET cia_course_minthreshhold = "' . $value_array[$i++] . '",
											tee_course_minthreshhold = "' . $value_array[$i++] . '",
											course_studentthreshhold = "' . $value_array[$i++] . '",
											justify = "' . $value_array[$i++] . '"
										WHERE crclm_id = "' . $crclm_id . '"
											AND crs_id = "' . $value_array[$i] . '"';
				$update_course_result = $this->db->query($update_course_query);
				$crs_id[$j] = $value_array[$i];
			}
			
			//number of courses
			$crs_count_val = sizeof($crs_id);

			//insert minimum, student and justify data into clo table
			for($i = 0, $j = 0; $j < $crs_count_val; $i++, $j++) {
				$update_co_query = 'UPDATE clo
									SET cia_clo_minthreshhold = "' . $value_array[$i++] . '",
										tee_clo_minthreshhold = "' . $value_array[$i++] . '",
										clo_studentthreshhold = "' . $value_array[$i++] . '",
										justify = "' . $value_array[$i++] . '"
									WHERE crclm_id = "' . $crclm_id . '"
										AND term_id = "' . $term_id . '"
										AND crs_id = "' . $value_array[$i] . '"';
				$update_co_result = $this->db->query($update_co_query);
				
				// Need to put this code for individual Course Threshold definitions update - for all Course in a loop
				/* // Recalculate CIA & TEE Attainment w.r.t new threshold values for the QPs for which marks have been uploaded
					$procedure_call_cia = $this->db->query('CALL tier1_editThreshold_recalculateCIA_Attainment('.$value_array[$i].')');
					$procedure_call_tee = $this->db->query('CALL tier1_editThreshold_recalculateTEE_Attainment('.$value_array[$i].')');
				  
				//update CIA - course attainment finalize status flag to zero
					$ao_data_query = 'UPDATE map_courseto_course_instructor 
									  SET cia_finalise_flag = 0
									  WHERE crs_id ="' . $value_array[$i] . '" ';
					$ao_result = $this->db->query($ao_data_query); */
					
			}
		}else{
		
						//insert minimum, student and justify data into course table
			for($i = 0, $j = 0; $i < $count_val; $i++, $j++) {

				$update_course_query = 'UPDATE course
										SET cia_course_minthreshhold = "' . $value_array[$i++] . '",
											mte_course_minthreshhold = "' . $value_array[$i++] . '",
											tee_course_minthreshhold = "' . $value_array[$i++] . '",
											course_studentthreshhold = "' . $value_array[$i++] . '",
											justify = "' . $value_array[$i++] . '"
										WHERE crclm_id = "' . $crclm_id . '"
											AND crs_id = "' . $value_array[$i] . '"';
				$update_course_result = $this->db->query($update_course_query);
				$crs_id[$j] = $value_array[$i];
			}
			
			//number of courses
			$crs_count_val = sizeof($crs_id);

			//insert minimum, student and justify data into clo table
			for($i = 0, $j = 0; $j < $crs_count_val; $i++, $j++) {
				$update_co_query = 'UPDATE clo
									SET cia_clo_minthreshhold = "' . $value_array[$i++] . '",
										mte_clo_minthreshhold = "' . $value_array[$i++] . '",
										tee_clo_minthreshhold = "' . $value_array[$i++] . '",
										clo_studentthreshhold = "' . $value_array[$i++] . '",
										justify = "' . $value_array[$i++] . '"
									WHERE crclm_id = "' . $crclm_id . '"
										AND term_id = "' . $term_id . '"
										AND crs_id = "' . $value_array[$i] . '"';
				$update_co_result = $this->db->query($update_co_query);
				
				// Need to put this code for individual Course Threshold definitions update - for all Course in a loop
				/* // Recalculate CIA & TEE Attainment w.r.t new threshold values for the QPs for which marks have been uploaded
					$procedure_call_cia = $this->db->query('CALL tier1_editThreshold_recalculateCIA_Attainment('.$value_array[$i].')');
					$procedure_call_tee = $this->db->query('CALL tier1_editThreshold_recalculateTEE_Attainment('.$value_array[$i].')');
				  
				//update CIA - course attainment finalize status flag to zero
					$ao_data_query = 'UPDATE map_courseto_course_instructor 
									  SET cia_finalise_flag = 0
									  WHERE crs_id ="' . $value_array[$i] . '" ';
					$ao_result = $this->db->query($ao_data_query); */
					
			}

		}
		return true;
	}
	
	public function save_bloom_course_wise($crclm_id, $term_id , $bl_min , $bl_stud , $bl_justify , $crs_id_data ,$bloom_id , $tee_min , $mte_flag , $mte_min){
			$query = $this->db->query('SELECT count(map_course_blm_id) FROM map_course_bloomlevel m where crs_id = "'.$crs_id_data.'" and crclm_id = "'.$crclm_id.'" and term_id="'.$term_id.'"');
			$re = $query->result_array();
			$kmax = sizeof($bloom_id);
				if($mte_flag == 0){
				for ($k = 0; $k < $kmax; $k++) {
				$threshold_data = array(
							'crclm_id'   => $crclm_id,
							'crs_id'  	 => $crs_id_data,				
							'bloom_id'     => $bloom_id[$k],
							'cia_bloomlevel_minthreshhold' => $bl_min[$k],
                                                        'tee_bloomlevel_minthreshhold' => $tee_min[$k],
							'bloomlevel_studentthreshhold' => $bl_stud[$k],
							'justify' => $bl_justify[$k],                
							'term_id'       => $term_id,
							'created_by'    => $this->ion_auth->user()->row()->id,
							'created_date'  => date('Y-m-d') ,
							'modified_by'	=>  $this->ion_auth->user()->row()->id,
							'modified_date' =>  date('Y-m-d') );
						if($re[0]['count(map_course_blm_id)'] == 0){	
								$result = $this->db->insert('map_course_bloomlevel' , $threshold_data);
						} else { 

						$result = $this->db->query('update map_course_bloomlevel set cia_bloomlevel_minthreshhold="'.$bl_min[$k].'" , tee_bloomlevel_minthreshhold = "'. $tee_min[$k].'" , bloomlevel_studentthreshhold="'.$bl_stud[$k].'" ,  justify="'.$bl_justify[$k].'" where crclm_id="'.$crclm_id.'" and term_id = "'.$term_id.'" and bloom_id = "'.$bloom_id[$k].'" and crs_id="'.$crs_id_data.'"');
						}
					}	
				}else if($mte_flag == 1){
				for ($k = 0; $k < $kmax; $k++) {
				$threshold_data = array(
							'crclm_id'   => $crclm_id,
							'crs_id'  	 => $crs_id_data,				
							'bloom_id'     => $bloom_id[$k],
							'cia_bloomlevel_minthreshhold' => $bl_min[$k],
							'mte_bloomlevel_minthreshhold' => $mte_min[$k],
                            'tee_bloomlevel_minthreshhold' => $tee_min[$k],
							'bloomlevel_studentthreshhold' => $bl_stud[$k],
							'justify' => $bl_justify[$k],                
							'term_id'       => $term_id,
							'created_by'    => $this->ion_auth->user()->row()->id,
							'created_date'  => date('Y-m-d') ,
							'modified_by'	=>  $this->ion_auth->user()->row()->id,
							'modified_date' =>  date('Y-m-d') );
						if($re[0]['count(map_course_blm_id)'] == 0){	
								$result = $this->db->insert('map_course_bloomlevel' , $threshold_data);
						} else { 

						$result = $this->db->query('update map_course_bloomlevel set cia_bloomlevel_minthreshhold="'.$bl_min[$k].'" , mte_bloomlevel_minthreshhold="'. $mte_min[$k].'",tee_bloomlevel_minthreshhold = "'. $tee_min[$k].'" , bloomlevel_studentthreshhold="'.$bl_stud[$k].'" ,  justify="'.$bl_justify[$k].'" where crclm_id="'.$crclm_id.'" and term_id = "'.$term_id.'" and bloom_id = "'.$bloom_id[$k].'" and crs_id="'.$crs_id_data.'"');
						}
					}	
				}
			if($result){return '1';} else {return '0';}
	}
	
	public function check_course_bloom_level($crclm_id, $term_id,$crs_id_data){	
			$query = $this->db->query('SELECT * FROM map_course_bloomlevel m ,bloom_level bl where crs_id = "'.$crs_id_data.'" and crclm_id = "'.$crclm_id.'" and term_id="'.$term_id.'" and m.bloom_id = bl.bloom_id');
			 $bloom_level_threshold = $query->result_array();	
			 $data['bloom_level_threshold'] = $bloom_level_threshold;
			 return $data;
	}
	
	public function fetch_bloom_detail($crclm_id, $term_id,$crs_id){
		$query_crclm = $this->db->query('SELECT c.crs_title,ct.term_name ,crclm.crclm_name FROM course c , crclm_terms ct ,curriculum crclm  where crs_id="'.$crs_id.'" and ct.crclm_term_id="'.$term_id.'" and crclm.crclm_id="'.$crclm_id.'"');
		return $query_crclm->result_array();
	}
        
 /*
    * Function to fetch the course clo threshold details
    * @param:
    * @return:
 */
  public function fetch_course_clo_thresold_details($crclm_id, $term_id , $crs_id){
    $clo_threshold_data_query = 'SELECT * FROM clo where crs_id = "'.$crs_id.'" ';
    $clo_threshold_data = $this->db->query($clo_threshold_data_query);
    $clo_threshold_res = $clo_threshold_data->result_array();
    return $clo_threshold_res;
  }
  
  /*
   * Function to Update the individual clo threshold values
   * @param:
   * @return:
   */
  public function save_course_clo_wise_threshold_details($crclm_id, $term_id , $clo_min , $clo_stud , $clo_justify , $crs_id_data ,$clo_ids , $clo_tee_min , $clo_mte_flag , $mte_flag){
     
      $clo_id_count = count($clo_ids);
	    if($mte_flag == 0){
			for($i=0;$i<$clo_id_count;$i++){
				$clo_data_query = 'UPDATE clo 
									SET cia_clo_minthreshhold = "'.$clo_min[$i].'", tee_clo_minthreshhold = "'.$clo_tee_min[$i].'",
									clo_studentthreshhold = "'.$clo_stud[$i].'", justify = "'.$clo_justify[$i].'" '
									. ' WHERE clo_id = "'.$clo_ids[$i].'" 
									AND crs_id = "'.$crs_id_data.'" ';
				 $clo_threshold = $this->db->query($clo_data_query);
			}
	    }else if($mte_flag == 1){
				for($i=0;$i<$clo_id_count;$i++){
				$clo_data_query = 'UPDATE clo 
									SET cia_clo_minthreshhold = "'.$clo_min[$i].'",
									mte_clo_minthreshhold = "'.$clo_mte_flag[$i].'",
									tee_clo_minthreshhold = "'.$clo_tee_min[$i].'",
									clo_studentthreshhold = "'.$clo_stud[$i].'", justify = "'.$clo_justify[$i].'" '
									. ' WHERE clo_id = "'.$clo_ids[$i].'" 
									AND crs_id = "'.$crs_id_data.'" ';
				 $clo_threshold = $this->db->query($clo_data_query);
			}
		}
	  // Recalculate CIA & TEE Attainment w.r.t new threshold values for the QPs for which marks have been uploaded
	  $procedure_call_cia = $this->db->query('CALL tier1_editThreshold_recalculateCIA_Attainment('.$crs_id_data.')');
	  $procedure_call_tee = $this->db->query('CALL tier1_editThreshold_recalculateTEE_Attainment('.$crs_id_data.')');
	  
	  if($mte_flag ==1){	  
		$procedure_call_mte = $this->db->query('CALL tier1_editThreshold_recalculateMTE_Attainment('.$crs_id_data.')');
	  }
      //update CIA - course attainment finalize status flag to zero
		$ao_data_query = 'UPDATE map_courseto_course_instructor 
						  SET cia_finalise_flag = 0
						  WHERE crs_id ="' . $crs_id_data . '" ';
		$ao_result = $this->db->query($ao_data_query);
		if($mte_flag == 1){
			$this->db->query('UPDATE course_clo_owner  SET mte_finalize_flag = 0 WHERE crs_id = "'. $crs_id_data .'" ');
		}
	//delete section-wise all AO occasion attainment values from tier1 clo attainment table
		$sec_co_ovrll_attnmt_query = ' DELETE FROM tier1_section_clo_overall_cia_attainment 
									  WHERE crs_id ="' . $crs_id_data . '" ';
		$sec_co_ovrll_attnmt_result = $this->db->query($sec_co_ovrll_attnmt_query);
	
	//delete overall Course attainment values from tier1_crs_clo_overall_attainment table
		$crs_ovrll_attnmt_query = ' DELETE FROM tier1_crs_clo_overall_attainment 
									  WHERE crs_id ="' . $crs_id_data . '" ';
		$crs_ovrll_attnmt_result = $this->db->query($crs_ovrll_attnmt_query);
	
      return true;
  }
  
  public function fetch_organisation_data(){	
		$query = $this->db->query('select mte_flag  from organisation');
		$mte_flag  = $query->result_array();
		return $mte_flag ;
  }
	
} ?>
