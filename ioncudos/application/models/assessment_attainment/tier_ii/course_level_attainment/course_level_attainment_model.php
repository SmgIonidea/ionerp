<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Model Logic for CIA Adding, Editing operations performed through this file.	  
 * Modification History:
 * Date				Modified By				Description
 * 20-08-2014		Jevi V G     	         
  26-11-2014		Jyoti					PDF creation,Added TEE and CIA for graph display
  ---------------------------------------------------------------------------------------------------------------------------------
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

//class Course_attainment_model extends CI_Model {
class course_level_attainment_model extends CI_Model {
    /*
     * Function is to get the program titles.
     * @param - ------.
     * returns -------.
     */

    public function dropdown_dept_title() {
        if ($this->ion_auth->is_admin()) {
            return $this->db->select('dept_id, dept_name')
                            ->order_by('dept_name', 'asc')
                            ->where('status', 1)
                            ->get('department')->result_array();
        } else {
            $logged_in_user_id = $this->ion_auth->user()->row()->user_dept_id;
            return $this->db->select('dept_id, dept_name')
                            ->order_by('dept_name', 'asc')
                            ->where('dept_id', $logged_in_user_id)
                            ->where('status', 1)
                            ->get('department')->result_array();
        }
    }

    /*
     * Function is to get the program titles.
     * @param - ------.
     * returns -------.
     */

    public function dropdown_program_title($dept_id) {
        return $this->db->select('pgm_id, pgm_acronym')
                        ->order_by('pgm_acronym', 'asc')
                        ->where('dept_id', $dept_id)
                        // ->where('status',1)        		
                        ->get('program')
                        ->result_array();
    }

    /*
     * Function is to fetch the curriculum details.
     * @param - -----.
     * returns list of curriculum names.
     */

    public function crlcm_drop_down_fill_old($pgm_id) {
        return $this->db->select('crclm_id, crclm_name')
                        ->where('pgm_id', $pgm_id)
                        ->order_by('crclm_name', 'asc')
                        ->get('curriculum')
                        ->result_array();
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

	public function student_drop_down_fill( $crclm_id ,$ao_id,$crs_id){		
	/* 	$query = $this->db->query('select * from  su_student_stakeholder_details where crclm_id = "'. $crclm_id.'" ');
		return $query->result_array(); */
		if($ao_id == 'all_occasion') {
			$fetch_course_query = $this->db->query('SELECT  DISTINCT student_usn,student_name  FROM student_assessment s
														WHERE qp_mq_id IN (SELECT qp_mq_id FROM qp_mainquestion_definition
														WHERE qp_unitd_id IN(SELECT qpd_unitd_id FROM qp_unit_definition
														WHERE qpd_id IN(SELECT qpd_id FROM qp_definition WHERE qpd_type = 3 AND crs_id = "'.$crs_id.'") ) )');
			return  $fetch_course_query->result_array();
		} else {
		
			$query = $this->db->query('SELECT * FROM assessment_occasions a where ao_id = "'.$ao_id .'"');
			$qpd_id = $query->result_array();
			if(!empty($qpd_id)) {$qpd_id = $qpd_id[0]['qpd_id'];} else{ $qpd_id = '';} 
			$fetch_course_query = $this->db->query('SELECT  distinct student_usn,student_name  FROM student_assessment s
													where qp_mq_id in (select qp_mq_id from qp_mainquestion_definition
													where qp_unitd_id in(select qpd_unitd_id from qp_unit_definition where qpd_id = "'. $qpd_id .'" ) )');
			return  $fetch_course_query->result_array();
		}
	}
	
    /*
     * Function is to fetch the curriculum details.
     * @param - -----.
     * returns list of curriculum names.
     */

    public function crlcm_drop_down_fill() {
        $loggedin_user_id = $this->ion_auth->user()->row()->id;
        if ($this->ion_auth->is_admin()) {
            $curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name 
								FROM curriculum AS c
								WHERE c.status = 1
								ORDER BY c.crclm_name ASC';
        } elseif($this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')){
            $curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name 
							FROM curriculum AS c, users AS u, program AS p
							WHERE u.id = "' . $loggedin_user_id . '" 
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

    public function course_drop_down_fill($term) {
        // return $this->db->select('crs_id, crs_title')
        // ->where('crclm_term_id',$term)
        // ->order_by('crs_title','ASC')
        // ->get('course')
        // ->result_array();
        $fetch_course_query = $this->db->query('SELECT DISTINCT(c.crs_id),c.crs_title
												FROM course c,qp_definition qpd
												WHERE qpd.qpd_type IN (3,5) 
												AND c.crs_id = qpd.crs_id
												AND qpd.qp_rollout = 2 
												AND c.crclm_term_id = ' . $term);
        return $fetch_course_query->result_array();
    }
	
	public function student_data( $data){
		$query = $this->db->query('select * from  su_student_stakeholder_details where student_usn = "'. $data['student_usn'] .'" ');
		return $query->result_array();
	}
	public function StudentAttainmentAnalysis($data) {
		if($data['type_val']== 3){
			$query = $this->db->query('SELECT * FROM assessment_occasions a where crs_id = "'. $data['course_id'] .'"');
			$re = $query->result_array();
			$r = $this->db->query("call getStudentAttainmentAnalysis(".$re[0]['qpd_id'] .", '".$data['student_usn']."')");
			return $r->result_array();
		}else if($data['type_val'] == 5){
			$query = $this->db->query('select * from qp_definition where crs_id  = "'. $data['course_id'] .'" and qp_rollout = 2');
			$re = $query->result_array();
			$r = $this->db->query("call getStudentAttainmentAnalysis(".$re[0]['qpd_id'] .", '".$data['student_usn']."')");
			return $r->result_array();
		}
	}
	
    /*
     * Function to fetch the course list.
     */
    public function section_dropdown_list($crclm_id, $term_id, $course_id){
        
		$res_wgt = $this->db->query("SELECT total_cia_weightage,total_tee_weightage FROM course c where crs_id='" . $course_id . "'");
        $wgt_data = $res_wgt->row();
        $cia_wgt = $wgt_data->total_cia_weightage;
        $tee_wgt = $wgt_data->total_tee_weightage;
		
		
        $select_section_query = 'SELECT map.section_id, mtd.mt_details_name as section_name FROM map_courseto_course_instructor as map '
                . ' LEFT JOIN master_type_details as mtd ON mtd.mt_details_id = map.section_id WHERE map.crs_id = "'.$course_id.'" ';
        $section_data = $this->db->query($select_section_query);
        $section_result = $section_data->result_array();
        
        
        if(!empty($section_result)){
                    foreach($section_result as $section_val){
            $section_status_query = 'SELECT COUNT(section_id) as status FROM tier_ii_section_clo_overall_cia_attainment WHERE section_id = "'.$section_val['section_id'].'" AND crs_id = "'.$course_id.'" ';
            $status_data = $this->db->query($section_status_query);
            $status_res = $status_data->row_array();

            
              $select_query = 'SELECT tier2.clo_id, tier2.individual_weightage as individual_wt, tier2.individual_da_percentage as individual_per, 
			  ROUND(AVG(tier2.individual_da_percentage)*' . $cia_wgt . '/100,2) as threshold_clo_da_attainment_wgt ,
			  tier2.attainment_level as attain_level , tier2.average_clo_da_attainment ,tier2.section_id, tier2.crs_id, '
                            . ' mtd.mt_details_name as section_name, mtd.mt_details_id, cl.clo_code, cl.clo_statement, cl.cia_clo_minthreshhold '
                            . ' FROM tier_ii_section_clo_overall_cia_attainment as tier2 '
                            . ' LEFT JOIN master_type_details as mtd ON mtd.mt_details_id = tier2.section_id'
                            . ' LEFT JOIN clo as cl ON cl.clo_id = tier2.clo_id '
                            . ' WHERE tier2.crclm_id = "'.$crclm_id.'"'
                            . ' AND tier2.crclm_term_id = "'.$term_id.'"'
                            .'  AND tier2.crs_id = "'.$course_id.'"'
                            .'  AND tier2.section_id = "'.$section_val['section_id'].'" group by cl.clo_id';
            $overall_data = $this->db->query($select_query);
            $overall_attain_res = $overall_data->result_array();
            $section_over_all_attain[] = $overall_attain_res ;
            $status[] = $status_res;
        }
       
        $data['section_name_array'] =  $section_result;
        $data['secction_attainment_array'] = $section_over_all_attain;
        $data['status'] = $status;
        return $data;
        }else{
            exit;
        }
    }
       public function get_crs_level_attainment($crs_id) {
        return $this->db->get_where('attainment_level_course', array('crs_id' => $crs_id))->result_array();
    }
	
	
	public function mte_dropdown_list($crclm_id, $term_id, $course_id){
		$res_wgt = $this->db->query("SELECT total_cia_weightage,total_mte_weightage , total_tee_weightage FROM course c where crs_id='" . $course_id . "'");
        $wgt_data = $res_wgt->row();
        $cia_wgt = $wgt_data->total_cia_weightage;
		$mte_wgt = $wgt_data->total_mte_weightage;
        $tee_wgt = $wgt_data->total_tee_weightage;
				$section_status_query = 'SELECT COUNT(clo_oa_id) as status FROM tier_ii_section_clo_overall_cia_attainment WHERE  section_id = 0 AND crs_id = "'.$course_id.'" ';
				$status_data = $this->db->query($section_status_query);
				$status_res = $status_data->row_array();

								
 				$select_query = 'SELECT tier2.clo_id, tier2.individual_weightage as individual_wt, tier2.individual_da_percentage as individual_per,
								ROUND(AVG(tier2.individual_da_percentage)*' . $mte_wgt . '/100,2) as threshold_clo_da_attainment_wgt ,tier2.attainment_level as attain_level , tier2.average_clo_da_attainment ,tier2.section_id, tier2.crs_id, '
                            . ' mtd.mt_details_name as section_name, mtd.mt_details_id, cl.clo_code, cl.clo_statement, cl.cia_clo_minthreshhold '
                            . ' FROM tier_ii_section_clo_overall_cia_attainment as tier2 '
                            . ' LEFT JOIN master_type_details as mtd ON mtd.mt_details_id = tier2.section_id'
                            . ' LEFT JOIN clo as cl ON cl.clo_id = tier2.clo_id '
                            . ' WHERE tier2.crclm_id = "'.$crclm_id.'"'
                            . ' AND tier2.crclm_term_id = "'.$term_id.'"'
                            .'  AND tier2.crs_id = "'.$course_id.'"'
                            .'  AND tier2.section_id = 0 group by cl.clo_id'; 
				$overall_data = $this->db->query($select_query);
				$overall_attain_res = $overall_data->result_array();
		   
			$data['mte_attainment_array'] = $overall_attain_res;
			$data['status'] = $status_res;
			return $data;      
	}
    /*
     * Function to show section wise attainment drilldown
     */
    public function section_attainment_drill_down($sec_id, $clo_id){
        $clo_data_query = 'SELECT clo_code, clo_statement, crs_id FROM clo WHERE clo_id = "'.$clo_id.'" ';
        $clo_data = $this->db->query($clo_data_query);
        $clo_result = $clo_data->row_array();
        
        $section_name_query = 'SELECT mt_details_name as section_name FROM master_type_details WHERE mt_details_id = "'.$sec_id.'"';
        $section_name_data = $this->db->query($section_name_query);
        $section_name = $section_name_data->row_array();
        
        $attainment_table_query = 'SELECT att.sclo_ao_id, att.assess_type, att.ao_id, att.crclm_id, att.crclm_term_id, att.crs_id, att.section_id, att.clo_id, '
                                . ' att.ao_weightage, att.threshold_ao_direct_attainment, att.average_ao_direct_attainment, att.created_by, '
                                . ' att.created_date, att.modified_by, att.modified_date, '
                                . ' ao.ao_description, ao.ao_name, ao.ao_id, '
                                . ' crs.total_cia_weightage, crs.total_tee_weightage '
                                . ' FROM tier1_section_clo_ao_attainment as att '
                                . ' LEFT JOIN assessment_occasions as ao ON ao.ao_id = att.ao_id '
                                . ' LEFT JOIN course as crs ON crs.crs_id = att.crs_id '
                                . ' WHERE att.clo_id = "'.$clo_id.'" '
                                . ' AND att.crs_id = "'.$clo_result['crs_id'].'" AND att.assess_type = 3 AND att.section_id = "'.$sec_id.'" ';
        $attainment_data = $this->db->query($attainment_table_query);
        $attainment_result = $attainment_data->result_array();
        
        $attainment['clo_tbl_data'] = $clo_result;
        $attainment['attainment_data'] = $attainment_result;
        $attainment['section_name'] = $section_name['section_name'];

        return $attainment;
    }
	
		public function fetch_type_data($crclm_id, $term_id, $course_id){
	
		$query  = $this->db->query('select cia_flag , mte_flag , tee_flag from course where crs_id = "'. $course_id .'" and crclm_id = "'. $crclm_id .'" and crclm_term_id = "'. $term_id.'" ');
		$type_data =  $query->result_array();
		$types = array();
		$key = array();
		$t ='';
		foreach($type_data as $type ){
			if($type['cia_flag'] == 1){$types ['3'] = 'CIA';}
			if($type['mte_flag'] == 1){$types ['6'] = 'MTE';}
			if($type['tee_flag'] == 1){$types ['5'] = 'TEE';}			
		
		}	
			return $types;
			
	}
    
    /*
     * Function to show tee & cia finalized co attainment data
     */
    public function tee_cia_finalized_co_data($crclm_id, $term_id, $course_id){
	
		// Course CIA & TEE weightage fetch query
		$res_wgt = $this->db->query("SELECT total_cia_weightage,total_tee_weightage FROM course c where crs_id='" . $course_id . "'");
        $wgt_data = $res_wgt->row();
        $cia_wgt = $wgt_data->total_cia_weightage;
        $tee_wgt = $wgt_data->total_tee_weightage;
        $wgt_arr = array(
            'cia_wgt' => $cia_wgt,
            'tee_wgt' => $tee_wgt,
        );
        // query for cia data check.
        $check_all_section_attain = 'SELECT map.section_id, mtd.mt_details_name as section_name, map.cia_finalise_flag as flag 
										FROM map_courseto_course_instructor as map 
										LEFT JOIN master_type_details as mtd ON mtd.mt_details_id = map.section_id 
										WHERE map.crs_id = "'.$course_id.'" 
										AND map.cia_finalise_flag !=1 ';
        $all_section_data = $this->db->query($check_all_section_attain);
        $all_section_res = $all_section_data->result_array();
        
        
        
        // query for tee data check.
        $check_tee_query = 'SELECT COUNT(assess_type) as type 
								FROM tier_ii_section_clo_ao_attainment 
								WHERE crs_id = "'.$course_id.'" 
								AND assess_type = 5 ';
        $check_tee_data = $this->db->query($check_tee_query);
        $check_tee_res = $check_tee_data->row_array();

             $crs_all_co_attainment_query = 'SELECT crs_id, clo_id,  cast(ROUND(SUM(co_average_da)) as decimal(10,2)) as ao_average,
											cast(ROUND(SUM(co_threshold_average)) as decimal(10,2)) as total_co_avg,                                           
                                            clo_code, clo_statement, 
                                            cast((SUM(attainment_level))as decimal(10,2)) as attainment_level,
                                            cia_clo_minthreshhold FROM(
                                            SELECT tier2.crs_id,tier2.clo_id, cast(ROUND(AVG(tier2.average_clo_da_attainment)*'.$cia_wgt.'/100)as decimal(10,2)) as co_average_da,
                                            cast(ROUND(AVG(tier2.threshold_clo_da_attainment)*'.$cia_wgt.'/100)as decimal(10,2)) co_threshold_average,
                                            AVG(tier2.attainment_level)*'.$cia_wgt.'/100 as attainment_level,
                                            cl.clo_code, cl.clo_statement, cl.cia_clo_minthreshhold
                                            FROM tier_ii_section_clo_overall_cia_attainment as tier2
                                            LEFT JOIN clo as cl ON cl.clo_id = tier2.clo_id
                                            WHERE tier2.crs_id = "'. $course_id .'"
                                            AND tier2.crclm_term_id = "'. $term_id .'"
                                            AND tier2.crclm_id = "'. $crclm_id .'"
                                            group by tier2.clo_id
                                            UNION ALL
                                            SELECT att.crs_id, att.clo_id, cast(ROUND(AVG(att.average_direct_attainment)*'.$tee_wgt.'/100)as decimal(10,2)) as co_average_da,
                                            cast(ROUND(AVG(att.threshold_direct_attainment)*'.$tee_wgt.'/100)as decimal(10,2)) as co_threshold_average,
                                            AVG(att.attainment_level)*'.$tee_wgt.'/100 as attainment_level,
                                            cl.clo_code, cl.clo_statement, cl.cia_clo_minthreshhold
                                            FROM tier_ii_section_clo_ao_attainment as att
                                            LEFT JOIN clo as cl ON cl.clo_id = att.clo_id
                                            WHERE att.crs_id = "'. $course_id .'"
                                            AND att.crclm_term_id = "'. $term_id .'"
                                            AND att.crclm_id = "'. $crclm_id .'"
										    AND att.assess_type = 5
                                            group by att.clo_id
                                            )s
                                            GROUP BY clo_id';  
			$crs_all_section_data = $this->db->query($crs_all_co_attainment_query);
            $crs_all_section_res = $crs_all_section_data->result_array();
            $data['cia_tee_co_attainment'] = $crs_all_section_res;
            $data['all_section_co_attain_check'] = 'false';
    /*     }else{
            $data['cia_tee_co_attainment'] = 'false';
            $data['all_section_co_attain_check'] = $all_section_res;
        } */
        return $data;
    }
	public function finalized_po_attainment_mapping_data( $crclm_id  , $course_id){
			$result = $this->db->query("call tier_2_POAttainment_COPOMapping(". $crclm_id .",".$course_id.")");
			return $result->result_array();	
	}
	
	public function po_data($crclm_id){
		$query = $this->db->query("SELECT * FROM po where crclm_id ='". $crclm_id ."' ");
        return $query->result_array();	
	}	
    /*
     * Function to fetch the finalized po attainment data
     */
    
    public function finalized_po_attainment($crclm_id, $term_id, $course_id){
        // query to fetch the po attainment.
        
        $po_attainment_query = 'SELECT pod.po_da_id, pod.crclm_id, pod.crclm_term_id, pod.crs_id, pod.po_id, 
								pod.hml_weighted_multiply_maplevel_da , pod.hml_wtd_avg_mul_attainment_level ,pod.hml_weighted_average_da , 
										hml_wtd_avg_attainment_level,pod.threshold_po_direct_attainment ,pod.threshold_po_attainment_level,'
                                . ' pod.average_po_direct_attainment , pod.average_po_attainment_level , pod.threshold_po_direct_attainment,'
                                . ' pod.created_by, pod.created_date, pod.modified_by, pod.modified_date, '
                                . ' p.po_reference, p.po_statement  '
                                . ' FROM tier_ii_po_direct_attainment as pod '
                                . ' LEFT JOIN po as p ON p.po_id = pod.po_id '
                                . ' WHERE pod.crclm_id ="'.$crclm_id.'" '
                                . ' AND pod.crclm_term_id = "'.$term_id.'" '
                                . ' AND pod.crs_id = "'.$course_id.'" 
								ORDER BY LENGTH(p.po_reference), p.po_reference';									
        $po_data = $this->db->query($po_attainment_query);
        $po_result = $po_data->result_array();
        return $po_result;
    }
	public function fetch_org_config_po(){
		$query = $this->db->query("SELECT * FROM org_config o where org_config_id = 6");
        return $query->result_array();	
	}	
	public function fetch_map_level_weightage(){
		$query = $this->db->query("SELECT * FROM map_level_weightage m");
        return $query->result_array();	
	}
    /*
     * Function to fetch the all section attainment
     */
    
    public function all_section_attainemnt($crclm_id, $term_id, $course_id){

        $check_all_section_attain = 'SELECT map.section_id, mtd.mt_details_name as section_name, map.cia_finalise_flag as flag FROM map_courseto_course_instructor as map '
                . ' LEFT JOIN master_type_details as mtd ON mtd.mt_details_id = map.section_id WHERE map.crs_id = "'.$course_id.'" AND map.cia_finalise_flag !=1 ';
        $all_section_data = $this->db->query($check_all_section_attain);
        $all_section_res = $all_section_data->result_array();

        if(empty($all_section_res)){

            $crs_all_co_attainment_query = 'SELECT tier2.clo_id, cast(ROUND(AVG(tier2.individual_weightage))as decimal(10,2)) as average_attainment,'
                                            . ' cast(ROUND(AVG(tier2.individual_da_percentage))as decimal(10,2)) as threshold_attainment,'
                                            . ' cl.clo_code, tier2.attainment_level , cl.clo_statement, cl.cia_clo_minthreshhold '
                                            . ' FROM tier_ii_section_clo_overall_cia_attainment as tier2 '
                                            . ' LEFT JOIN clo as cl ON cl.clo_id = tier2.clo_id '
                                            . ' WHERE tier2.crs_id = "'.$course_id.'" '
                                            . ' AND tier2.crclm_term_id = "'.$term_id.'" '
                                            . ' AND tier2.crclm_id = "'.$crclm_id.'" GROUP BY tier2.clo_id';
            $crs_all_section_data = $this->db->query($crs_all_co_attainment_query);
            $crs_all_section_res = $crs_all_section_data->result_array();

            $data['all_section_co_avg_attainment'] = $crs_all_section_res;
            $data['all_section_co_attain_check'] = 'false';
        }else{
            
            $data['all_section_co_avg_attainment'] = 'false';
            $data['all_section_co_attain_check'] = $all_section_res;
        }
        return $data;
        
    } 
    
    /*
     * Function to fetch clo all section attainment
     */
    public function clo_all_section_attainemnt($crs_id, $clo_id){
						
         $crs_all_co_attainment_query = 'SELECT c.total_cia_weightage,tier2.clo_id, tier2.threshold_clo_da_attainment as co_average, '
											. ' cast((cast(ROUND(AVG(tier2.threshold_clo_da_attainment))as decimal(10,2)) * c.total_cia_weightage)/100 as decimal(10,2)) as after_wt_cia,'
                                            . ' cl.clo_code, cl.clo_statement, CAST(cl.cia_clo_minthreshhold as decimal(10,2)) as cia_clo_minthreshhold , cl.tee_clo_minthreshhold '
                                            . ' FROM tier_ii_section_clo_overall_cia_attainment as tier2 '
                                            . ' LEFT JOIN clo as cl ON cl.clo_id = tier2.clo_id '
											. ' JOIN course as c on c.crs_id = cl.crs_id'
                                            . ' WHERE tier2.crs_id = "'.$crs_id.'" '
                                            . ' AND tier2.clo_id = "'.$clo_id.'" '; 
            $crs_all_section_data = $this->db->query($crs_all_co_attainment_query);
            $crs_all_section_res = $crs_all_section_data->result_array();

			return $crs_all_section_res;
    }
    
    /*
     * Function to get the tee all attainment
     */
    public function tee_all_attainemnt($crclm_id, $term_id, $course_id){
		
         $tee_attainment_query = ' SELECT *  '
                                . ' FROM tier_ii_section_clo_ao_attainment as tier2 '
                                . ' LEFT JOIN clo as cl ON cl.clo_id = tier2.clo_id '
                                . ' WHERE tier2.crclm_id = "'.$crclm_id.'" '
                                . ' AND tier2.crclm_term_id = "'.$term_id.'" '
                                . ' AND tier2.crs_id = "'.$course_id.'" '
                                . ' AND tier2.assess_type = 5 ';	 	

      /*   $tee_attainment_query = ' select * from tier_ii_section_clo_ao_attainment as t
								  left join CLO as c ON c.clo_id = t.clo_id
								  where t.crs_id = "'. $course_id .'" and t.assess_type = 5 '; */									
        $tee_attainment_data = $this->db->query($tee_attainment_query);
        $tee_attainment_res = $tee_attainment_data->result_array();		
        return $tee_attainment_res;
    }
    
    /*
     * Function to get the tee all attainment
     */
    public function clo_tee_attainemnt($crs_id,$clo_id){
		$tee_attainment_query = 'SELECT tier2.clo_id, c.total_tee_weightage , tier2.threshold_direct_attainment , 
		cast(ROUND(tier2.threshold_direct_attainment * c.total_tee_weightage) / 100 as decimal(10,2)) as after_wt_tee,'
                                . ' cl.clo_code, cl.clo_statement, CAST(cl.tee_clo_minthreshhold as decimal(10,2)) as tee_clo_minthreshhold    '
                                . ' FROM tier_ii_section_clo_ao_attainment as tier2 '
                                . ' LEFT JOIN clo as cl ON cl.clo_id = tier2.clo_id '
								. ' JOIN course as c on c.crs_id = cl.crs_id '
                                . ' WHERE tier2.crs_id = "'.$crs_id.'" and tier2.clo_id = "'.$clo_id.'" '
                                . ' AND tier2.assess_type = 5  ';
        $tee_attainment_data = $this->db->query($tee_attainment_query);
        $tee_attainment_res = $tee_attainment_data->result_array();
        return $tee_attainment_res;
    }
            /*
     * Function to fetch the course list.
     */
    public function fetch_section($crclm_id, $term_id, $course_id){
        $loggedin_user_id = $this->ion_auth->user()->row()->id;
         if($this->ion_auth->in_group('Course Owner') && !$this->ion_auth->in_group('admin') && !$this->ion_auth->in_group('Chairman') && !$this->ion_auth->in_group('Program Owner')){
             $section_query = 'SELECT DISTINCT map.section_id, map.crs_id, mt_details.mt_details_id, mt_details.mt_details_name as section_name '
                . ' FROM map_courseto_course_instructor as map'
                . ' LEFT JOIN master_type_details as mt_details ON mt_details.mt_details_id = map.section_id'
                . ' WHERE map.crclm_id = "'.$crclm_id.'" '
                . ' AND map.crclm_term_id = "'.$term_id.'" '
                . ' AND map.course_instructor_id="'.$loggedin_user_id.'" '
                . ' AND map.crs_id = "'.$course_id.'" GROUP BY map.section_id';
        $section_data = $this->db->query($section_query);
        $section_list = $section_data->result_array();
        return $section_list;
         }else{
             $section_query = 'SELECT DISTINCT map.section_id, map.crs_id, mt_details.mt_details_id, mt_details.mt_details_name as section_name '
                . ' FROM map_courseto_course_instructor as map'
                . ' LEFT JOIN master_type_details as mt_details ON mt_details.mt_details_id = map.section_id'
                . ' WHERE map.crclm_id = "'.$crclm_id.'" '
                . ' AND map.crclm_term_id = "'.$term_id.'" '
                . ' AND map.crs_id = "'.$course_id.'" GROUP BY map.section_id';
        $section_data = $this->db->query($section_query);
        $section_list = $section_data->result_array();
        return $section_list;
         }
        
    }
	
	public function fetch_bloom_threshold($data){
			$query = $this->db->query('select * from map_course_bloomlevel where crs_id = "'. $data['course_id'] .'" ');
			return $query->result_array();
	}
	
	public function cia_bloom_level_attainment($data){	
		if($data['type_val'] == 3){
			
				if($data['occasion'] == 'all_occasion'){
					if(empty($data['student_usn'])){
						$result_cia = $this->db->query("call get_bloomsLevel_Attainment(".$data['crclm_id'].",".$data['course_id'].",".$data['section_id'].",". $data['type_val'] .",NULL, NULL ,'CIA',NULL,NULL)");
					}else{
						$result_cia = $this->db->query("call get_bloomsLevel_Attainment(".$data['crclm_id'].",".$data['course_id'].",".$data['section_id'].",". $data['type_val'] .",NULL, NULL ,'CIA','".$data['student_usn']."',NULL)");
					}
				}else{
						$query_qpd_id = $this->db->query('SELECT qpd_id FROM assessment_occasions where crs_id = '. $data['course_id'].' and  ao_id = "'. $data['occasion'].'" ');
						$qpd_id_data = $query_qpd_id->result_array();
						$qpd_id = $qpd_id_data[0]['qpd_id'];
					if(empty($data['student_usn'])){
									
						$result_cia = $this->db->query("call get_bloomsLevel_Attainment(".$data['crclm_id'].",".$data['course_id'].",".$data['section_id'].",". $data['type_val'] .",".$data['occasion'].", ". $qpd_id." ,'CIA',NULL,NULL)");
					}else{
						$result_cia = $this->db->query("call get_bloomsLevel_Attainment(".$data['crclm_id'].",".$data['course_id'].",".$data['section_id'].",". $data['type_val'] .",".$data['occasion'].", ". $qpd_id." ,'CIA','".$data['student_usn']."',NULL)");
					}
				}
			
			
			return $result_cia->result_array();		
		}else if($data['type_val'] == 5){			
			$query_qpd_id = $this->db->query('select qpd_id from qp_definition where crs_id = '.$data['course_id'].' and qpd_type = '.$data['type_val'].'  and qp_rollout = 2');
			$qpd_id_data = $query_qpd_id->result_array();
			if(!empty($qpd_id)){
			$qpd_id = $qpd_id_data[0]['qpd_id'];
			if(empty($data['student_usn'])){
				$result_cia = $this->db->query("call get_bloomsLevel_Attainment(".$data['crclm_id'].",".$data['course_id'].",NULL,". $data['type_val'] .",NULL, ". $qpd_id." ,'TEE',NULL,NULL)");
			}else{
				$result_cia = $this->db->query("call get_bloomsLevel_Attainment(".$data['crclm_id'].",".$data['course_id'].",NULL,". $data['type_val'] .",NULL, ". $qpd_id." ,'TEE','".$data['student_usn']."',NULL)");
			}
			return $result_cia->result_array();	
			}else{ return null;}
		}
	}
    /*
     * Function to finalize tee and cia attainment data
     */
    public function finalize_cia_tee_attainment_data($crclm_id, $term_id, $course_id,$clo_ids,$tee_cia_attain,$avg_ao_attain,$threshold_ao_attain, $co_threshold , $attainment_level){
        // Delete data from the course before inserting.
        $delete_query = 'DELETE FROM tier_ii_crs_clo_overall_attainment WHERE crclm_id = "'.$crclm_id.'" AND crclm_term_id = "'.$term_id.'" AND crs_id = "'.$course_id.'" ';
        $data_delete_success = $this->db->query($delete_query);
        
        $clo_id_array = explode(',',$clo_ids);
        $tee_cia_attain_array = explode(',',$tee_cia_attain);
        $avg_ao_attain_array = explode(',',$avg_ao_attain);
        $threshold_ao_attain_array = explode(',',$threshold_ao_attain);
        $co_threshold_array = explode(',',$co_threshold);
		$attainment_level = explode(',',$attainment_level);
       
        $size = count($co_threshold_array);
		
        for($j=0;$j<$size;$j++){
            $insert_finalize_data = array(
                'crclm_id'=>$crclm_id,
                'crclm_term_id'=>$term_id,
                'crs_id'=>$course_id,
                'clo_id'=>$clo_id_array[$j],
                'da_percentage'=>$co_threshold_array[$j],
                'average_clo_da_attainment'=>$avg_ao_attain_array[$j], // provide values from controller
                'threshold_clo_da_attainment'=>$co_threshold_array[$j],
                'da_weightage'=>100,
                'ia_percentage'=>0,
                'ia_weightage'=>0,              
                'overall_attainment'=>$co_threshold_array[$j],
                'attainment_level'=>$attainment_level[$j], // provide values from controller
                'created_by'=>$this->ion_auth->user()->row()->id,
                'created_date'=>date('y-m-d'),
                'modified_by'=>$this->ion_auth->user()->row()->id,
                'modified_date'=>date('y-m-d'),
            );
            $this->db->insert('tier_ii_crs_clo_overall_attainment',$insert_finalize_data);
        }
        
        $po_delete_query = 'DELETE FROM tier_ii_po_direct_attainment WHERE crclm_id = "'.$crclm_id.'" AND crclm_term_id = "'.$term_id.'" AND crs_id = "'.$course_id.'" ';
        $po_data_delete_success = $this->db->query($po_delete_query);
        
         $procedure_call = $this->db->query('CALL Save_Tier2_POAttainment('.$crclm_id.','.$term_id.','.$course_id.' , NULL)');
        ////  'threshold_clo_overall_attainment'=>$tee_cia_attain_array[$j],
        return true;
    }
    
    /*
     * Function to fetch the finalized data
     */
    public function finalize_attainment_data($crclm_id, $term_id, $course_id){
        $finalize_query = 'SELECT att.clo_oall_id, att.crclm_id, att.crclm_term_id, att.crs_id, att.clo_id, att.da_weightage,
						   att.ia_weightage, att.threshold_clo_da_attainment,  att.average_clo_da_attainment,
						   att.overall_attainment, att.attainment_level, att.created_by, att.created_date, att.modified_by, att.modified_date,
						   cl.clo_code, cl.clo_statement, cl.cia_clo_minthreshhold
						   FROM tier_ii_crs_clo_overall_attainment as att
						   LEFT JOIN clo as cl ON cl.clo_id = att.clo_id
						   WHERE att.crs_id = "'. $course_id .'"
                           AND att.crclm_term_id = "'. $term_id .'"
                           AND att.crclm_id = "'. $crclm_id .'" ';
        $finalized_data = $this->db->query($finalize_query);
        $finalized_res = $finalized_data->result_array();
        return $finalized_res;
    }

    public function survey_drop_down_fill($course) {
        return $this->db->select('survey_id, name')
                        ->where('crs_id', $course)
                        ->where('su_for', 8)
                        //->where('status',1) //Edited by shivaraj B for getting closed surveys
                        ->get('su_survey')
                        ->result_array();
    }

    public function qp_rolled_out($crs) {

        return $this->db->select('qpd_id')
                        ->where('crs_id', $crs)
                        ->where('qp_rollout > 1')
                        ->where('qpd_type = 5')
                        ->get('qp_definition')
                        ->result_array();
    }

    public function course_po_list($crs) {

        return $this->db->distinct()
                        ->select('clo_po_map.po_id, po.po_reference')
                        ->join('po', 'po.po_id = clo_po_map.po_id')
                        ->where('crs_id', $crs)
                        ->order_by('po.po_reference', 'ASC')
                        ->get('clo_po_map')
                        ->result_array();
    }

    public function getCourseCOThreshholdAttainment($crs, $qp_rolled_out, $type, $section_id) {
        if ($qp_rolled_out > 0) { //Individual CIA or TEE CO attainment
            $r = $this->db->query("call getCourseCOThreshholdAttainment(" . $crs . ", " . $qp_rolled_out . ", NULL, '" . $type . "','" . $section_id . "')");
        } else if ($qp_rolled_out == 'all_occasion') { //All CIA occasion CO attainment
            $r = $this->db->query("call getCourseCOThreshholdAttainment(" . $crs . ", NULL, NULL, 'CIA', '" . $section_id . "')");
        } else { //Both CIA & TEE CO attainment
            $r = $this->db->query("call getCourseCOThreshholdAttainment(" . $crs . ", NULL, NULL, 'Both', '" . $section_id . "')");
        }
        return $r->result_array();
    }

    public function getCourseCOAttainment($crs, $qp_rolled_out, $type) {
        if ($qp_rolled_out > 0) { //Individual CIA or TEE CO attainment
            $r = $this->db->query("call getCourseCOAttainment(" . $crs . ", " . $qp_rolled_out . ", NULL, '" . $type . "')");
        } else if ($qp_rolled_out == 'all_occasion') { //All CIA occasion CO attainment
            $r = $this->db->query("call getCourseCOAttainment(" . $crs . ", NULL, NULL, 'CIA')");
        } else { //Both CIA & TEE CO attainment
            $r = $this->db->query("call getCourseCOAttainment(" . $crs . ", NULL, NULL, 'BOTH')");
        }
        return $r->result_array();
    }

    public function CourseBloomsLevelThresholdAttainment($crs, $qp_rolled_out, $type, $section_id) {
        if ($qp_rolled_out > 0) { //Individual CIA or TEE CO attainment
            $r = $this->db->query("call getCourseBLThreshholdAttainment(" . $crs . ", " . $qp_rolled_out . ", NULL, '" . $type . "', '" . $section_id . "')");
        } else if ($qp_rolled_out == 'all_occasion') { //All CIA occasion CO attainment
            $r = $this->db->query("call getCourseBLThreshholdAttainment(" . $crs . ", NULL, NULL, 'CIA', '" . $section_id . "')");
        } else { //Both CIA & TEE CO attainment
            $r = $this->db->query("call getCourseBLThreshholdAttainment(" . $crs . ", NULL, NULL, 'BOTH', '" . $section_id . "')");
        }
        return $r->result_array();
    }

    public function CourseBloomsLevelAttainment($crs, $qp_rolled_out, $type) {
	
        if ($qp_rolled_out > 0) { //Individual CIA or TEE CO attainment
            $r = $this->db->query("call getCourseBloomsLevelAttainment(" . $crs . ", " . $qp_rolled_out . ", NULL, '" . $type . "')");
        } else if ($qp_rolled_out == 'all_occasion') { //All CIA occasion CO attainment
            $r = $this->db->query("call getCourseBloomsLevelAttainment(" . $crs . ", NULL, NULL, 'CIA')");
        } else { //Both CIA & TEE CO attainment
            $r = $this->db->query("call getCourseBloomsLevelAttainment(" . $crs . ", NULL, NULL, 'BOTH')");
        }
        return($r->result_array());
    }

    public function BloomsLevelMarksDistribution($crs) {
        $r = $this->db->query("call getBloomsLevelMarksDistribution(" . $crs . ", " . $qp_rolled_out . ")");

        return $r->result_array();
    }

    public function BloomsLevelPlannedCoverageDistribution($crs) {
        $r = $this->db->query("call getBloomsLevelPlannedCoverageDistribution(" . $crs . ", " . $qp_rolled_out . ")");

        return $r->result_array();
    }

    public function CoursePOCOAttainment($po_list, $crs, $qpd_id, $type) {
        if ($qpd_id == 'all_occasion' || $qpd_id == NULL) {
            $param = $crs . ", NULL, NULL,'" . $type;
        } else if ($qpd_id > 0) {
            $param = $crs . ", " . $qpd_id . ", NULL,'" . $type;
        }
        $i = 0;
        foreach ($po_list as $po_id) {
            $this->load->library('database_result');
            $result = $this->db->query("call getCoursePOCOAttainment(" . $po_id['po_id'] . ", " . $param . "')");
            $graph_data = $result->result_array();
            $this->database_result->next_result();
            $data['po_reference'][] = $po_id['po_reference'];
            $data['graph_data'][] = $graph_data;
        }
        return $data;
    }

    /*
     * Function is to fetch the qpd details.
     * @param - -----.
     * returns list of qpd_id.
     */

    public function select_qpd_all_terms($crclm_id, $term_id) {
        if ($term_id == 'select_all_term') {
            $fetch_qpd_all_terms_query = $this->db->query('SELECT q.qpd_id 
														   FROM qp_definition q 
														   WHERE q.crclm_id = ' . $crclm_id . ' 
														   AND q.qpd_type = 5 
														   AND q.qp_rollout = 2');
        } else {
            $fetch_qpd_all_terms_query = $this->db->query('SELECT q.qpd_id
															FROM qp_definition q
															WHERE q.crclm_id = ' . $crclm_id . ' 
															AND q.crclm_term_id = ' . $term_id . '
															AND q.qpd_type = 5
															AND q.qp_rollout = 2');
        }
        return $fetch_qpd_all_terms_query->result_array();
    }

    /*
     * Function is to fetch the occasion details.
     * @param - -----.
     * returns list of occasion names.
     */

    public function occasion_fill($crclm_id, $term_id, $crs_id, $section_id) {
       
         $fetch_occasion_query = $this->db->query('SELECT a.qpd_id,a.ao_description,a.ao_id
                                                    FROM assessment_occasions a,qp_definition q
                                                    WHERE a.crclm_id = ' . $crclm_id . '
                                                    AND a.term_id = ' . $term_id . '
                                                    AND a.crs_id = ' . $crs_id. '
                                                    AND a.section_id = '.$section_id.'
                                                    AND q.qpd_id = a.qpd_id
                                                    AND q.qp_rollout > 1
                                                    AND q.qpd_type = 3
													AND a.ao_type_id IN(1,2)
                                                    AND a.qpd_id IS NOT NULL');
        return $fetch_occasion_query->result_array();
    }

    public function getCOSurveyReportData($survey_id) {

        $r = $this->db->query("call getCOSurveyReportData(" . $survey_id . ")");

        return $r->result_array();
    }

    public function qp_direct_indirect($crs) {

        return $this->db->select('qpd_id')
                        ->where('crs_id', $crs)
                        ->where('qp_rollout > 1')
                        ->where('qpd_type', 5)
                        ->get('qp_definition')
                        ->result_array();
    }

    public function getDirectIndirectCOAttaintmentData($course_id, $qpd_id, $usn, $qpd_type, $direct_attainment, $indirect_attainment, $survey_id, $type_data) {
        if ($type_data == 1) {
            $r = $this->db->query("call getDirectIndirectCOAttaintmentData(" . $course_id . "," . $qpd_id . ",NULL,'TEE'," . $direct_attainment . "," . $indirect_attainment . "," . $survey_id . ",0)");
        } else if ($type_data == 2) {
            if ($qpd_id != NULL) {
                $r = $this->db->query("call getDirectIndirectCOAttaintmentData(" . $course_id . "," . $qpd_id . ",NULL,'CIA'," . $direct_attainment . "," . $indirect_attainment . "," . $survey_id . ",0)");
            } else {
                $r = $this->db->query("call getDirectIndirectCOAttaintmentData(" . $course_id . ",NULL,NULL,'CIA'," . $direct_attainment . "," . $indirect_attainment . "," . $survey_id . ",0)");
            }
        } else {
            $r = $this->db->query("call getDirectIndirectCOAttaintmentData(" . $course_id . ",NULL,NULL,'BOTH'," . $direct_attainment . "," . $indirect_attainment . "," . $survey_id . ",0)");
        }
        return $r->result_array();
    }

    // Finalize CO Attainment and store onto the table direct_attainment
    public function finalize_getDirectIndirectCOAttaintmentData($course_id, $qpd_id, $usn, $qpd_type, $direct_attainment, $indirect_attainment, $survey_id, $type_data) {
        if ($type_data == 1) {
            $r = $this->db->query("call getDirectIndirectCOAttaintmentData(" . $course_id . "," . $qpd_id . ",NULL,'TEE'," . $direct_attainment . "," . $indirect_attainment . "," . $survey_id . ",1)");
        } else if ($type_data == 2) {
            if ($qpd_id != NULL) {
                $r = $this->db->query("call getDirectIndirectCOAttaintmentData(" . $course_id . "," . $qpd_id . ",NULL,'CIA'," . $direct_attainment . "," . $indirect_attainment . "," . $survey_id . ",1)");
            } else {
                $r = $this->db->query("call getDirectIndirectCOAttaintmentData(" . $course_id . ",NULL,NULL,'CIA'," . $direct_attainment . "," . $indirect_attainment . "," . $survey_id . ",1)");
            }
        } else {
            $r = $this->db->query("call getDirectIndirectCOAttaintmentData(" . $course_id . ",NULL,NULL,'BOTH'," . $direct_attainment . "," . $indirect_attainment . "," . $survey_id . ",1)");
        }
        return true;
    }

    /* Added by Shivaraj B  Date: 10-12-2015
     * Edited for displaying data depending on CLO ATTAINMENT TYPE
     */

    public function getCloAttainmentType() {
        $res = $this->db->select('value')->where('config', 'CLO_ATTAINMENT_TYPE')->get('org_config');
        return $res->result_array();
    }

    function getIndirectAttainmentData($survey_id, $crs_id) {
        $result = $this->db->query(" SELECT * FROM indirect_attainment i,clo c 
										WHERE i.survey_id='" . $survey_id . "' 
										AND i.crs_id='" . $crs_id . "' 
										AND i.actual_id=c.clo_id 
										ORDER BY c.clo_id; ");
        return $result->result_array();
    }

    function get_finalized_co_attainment($crclm_id, $term_id, $crs_id) {
        $co_result = $this->db->query("SELECT * FROM direct_attainment_clo t 
										LEFT JOIN clo c ON t.clo_id=c.clo_id 
										WHERE t.crclm_id='" . $crclm_id . "'
										AND t.crclm_term_id='" . $term_id . "' 
										AND t.crs_id='" . $crs_id . "';");
		//echo $this->db->last_query();
        $data['co_result'] = $co_result->result_array();

		// PO Attainment query after CO finalize option
		$po_result = $this->db->query("SELECT t.da_clo_id, ROUND(AVG(t.overall_attainment),2) as direct_attainment,t.crclm_id,
											t.crclm_term_id, t.crs_id,t.clo_id,cp.clo_id,cp.po_id, cp.map_level,
											t.overall_attainment,cp.clo_po_id,po.po_reference,po.po_statement,
										GROUP_CONCAT(DISTINCT c.clo_code ORDER BY c.clo_code ASC) AS co_mapping
										FROM direct_attainment_clo t
										LEFT JOIN clo_po_map cp ON t.clo_id = cp.clo_id
										LEFT JOIN po po ON po.po_id = cp.po_id
										LEFT JOIN clo c ON c.clo_id = cp.clo_id
										WHERE t.crclm_id = '" . $crclm_id . "'
										AND t.crclm_term_id = '" . $term_id . "'
										AND t.crs_id = '" . $crs_id . "'
										GROUP BY cp.po_id ;");
										
		 $data['po_result'] = $po_result->result_array();
		
		return $data;



	
		
		
    }
	
	
	public function fetch_co_details($param) {
        extract($param);
        $query = $this->db->query('SELECT c.crclm_name AS "crclm_name", t.term_name AS "term_name", crs.crs_code AS "crs_code", crs.crs_title AS "crs_title",  o.ao_description AS "occasion"
                                        FROM curriculum c
                                        LEFT JOIN crclm_terms t ON t.crclm_id = c.crclm_id
                                        LEFT JOIN course crs ON (crs.crclm_id = c.crclm_id AND t.crclm_term_id = crs.crclm_term_id)                                        
                                        LEFT JOIN assessment_occasions o ON (o.crclm_id = c.crclm_id AND o.term_id = t.crclm_term_id AND o.crs_id = crs.crs_id )
                                        WHERE c.crclm_id = '.$crclm_id.'
                                        AND t.crclm_term_id = '.$term_id.'
                                        AND crs.crs_id = '.$crs_id);
        $data = $query->row_array();
        return $data;
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
	
	
	public function fetch_selected_param_details($params = NULL) {
        extract($params);
        if(isset($occasion_type)) {
            $type_string = ($occasion_type == '3') ? $this->lang->line('entity_cie') : 
                            (($occasion_type == '5') ? $this->lang->line('entity_tee') : 
                               ( $this->lang->line('entity_cie').'_'.$this->lang->line('entity_tee') ) ) ;
            $query = ('SELECT c.crclm_name AS "crclm_name", t.term_name AS "term_name", crs.crs_code AS "crs_code", 
                        crs.crs_title AS "crs_title", "'.$type_string.'" AS "type"
                        FROM curriculum c
                        LEFT JOIN crclm_terms t ON t.crclm_id = c.crclm_id
                        LEFT JOIN course crs ON (crs.crclm_id = c.crclm_id AND t.crclm_term_id = crs.crclm_term_id )
                        WHERE c.crclm_id = '.$crclm_id.'
                        AND t.crclm_term_id = '.$term_id.'
                        AND crs.crs_id = '.$crs_id.'');
        }  else {
            $query = ('SELECT c.crclm_name AS "crclm_name", t.term_name AS "term_name", crs.crs_code AS "crs_code", 
                        crs.crs_title AS "crs_title"
                        FROM curriculum c
                        LEFT JOIN crclm_terms t ON t.crclm_id = c.crclm_id
                        LEFT JOIN course crs ON (crs.crclm_id = c.crclm_id AND t.crclm_term_id = crs.crclm_term_id )
                        WHERE c.crclm_id = '.$crclm_id.'
                        AND t.crclm_term_id = '.$term_id.'
                        AND crs.crs_id = '.$crs_id.'');
        }
        
        $param_details = $this->db->query($query);
        return $param_details->row_array();
    }
	
	public function multiple_section_attainemnt($crclm_id, $term_id, $course_id , $type_id , $type_not_selected){
	
	    $cia_attainment_query = 'SELECT tier2.clo_id, cast(ROUND(AVG(tier2.average_clo_da_attainment))as decimal(10,2)) as co_average_da,'
                                            . ' cast(ROUND(AVG(tier2.threshold_clo_da_attainment))as decimal(10,2)) as co_threshold_average,'
                                            . ' cl.clo_code, tier2.attainment_level , cl.clo_statement, cl.cia_clo_minthreshhold  ,  CAST(cl.mte_clo_minthreshhold as decimal(10,2)) as mte_clo_minthreshhold '
                                            . ' FROM tier_ii_section_clo_overall_cia_attainment as tier2 '
                                            . ' LEFT JOIN clo as cl ON cl.clo_id = tier2.clo_id '
                                            . ' WHERE tier2.crs_id = "'.$course_id.'" '
                                            . ' AND tier2.crclm_term_id = "'.$term_id.'" '
                                            . ' AND tier2.crclm_id = "'.$crclm_id.'"  AND  tier2.section_id != 0 GROUP BY tier2.clo_id';	
											
	    $mte_attainment_query = 'SELECT tier2.clo_id, cast(ROUND(AVG(tier2.average_clo_da_attainment))as decimal(10,2)) as co_average_da,'
                                            . ' cast(ROUND(AVG(tier2.threshold_clo_da_attainment))as decimal(10,2)) as co_threshold_average,  CAST(cl.mte_clo_minthreshhold as decimal(10,2)) as mte_clo_minthreshhold  ,'
                                            . ' cl.clo_code, tier2.attainment_level , cl.clo_statement, cl.cia_clo_minthreshhold '
                                            . ' FROM tier_ii_section_clo_overall_cia_attainment as tier2 '
                                            . ' LEFT JOIN clo as cl ON cl.clo_id = tier2.clo_id '
                                            . ' WHERE tier2.crs_id = "'.$course_id.'" '
                                            . ' AND tier2.crclm_term_id = "'.$term_id.'" '
                                            . ' AND tier2.crclm_id = "'.$crclm_id.'"  AND  tier2.section_id = 0 GROUP BY tier2.clo_id';
											
		$tee_attainment_query = ' SELECT  tier2.average_direct_attainment as co_average_da , tier2.threshold_direct_attainment ,  cl.clo_code, cl.clo_statement, CAST(cl.tee_clo_minthreshhold as decimal(10,2)) as tee_clo_minthreshhold , cl.mte_clo_minthreshhold,cl.cia_clo_minthreshhold  '
					. ' FROM tier_ii_section_clo_ao_attainment as tier2 '
					. ' LEFT JOIN clo as cl ON cl.clo_id = tier2.clo_id '
					. ' WHERE tier2.crclm_id = "'.$crclm_id.'" '
					. ' AND tier2.crclm_term_id = "'.$term_id.'" '
					. ' AND tier2.crs_id = "'.$course_id.'" '
					. ' AND tier2.assess_type = 5 ';	 
											
		$res_wgt = $this->db->query("SELECT total_cia_weightage, total_mte_weightage , total_tee_weightage FROM course c where crs_id='" . $course_id . "'");
        $wgt_data = $res_wgt->row();
        $cia_wgt = $wgt_data->total_cia_weightage;
        $mte_wgt = $wgt_data->total_mte_weightage;
		$tee_wgt = $wgt_data->total_tee_weightage;		

			$cia_finalize_status_query = $this->db->query('select * from map_courseto_course_instructor where crs_id = "'. $course_id .'"');
			$cia_finalize_status_re  = $cia_finalize_status_query->result_array();
			if(!empty($cia_finalize_status_re)){$cia_finalize_status =  $cia_finalize_status_re[0]['cia_finalise_flag'];}else{$cia_finalize_status = 0;}										
			$mte_finalize_status_query = $this->db->query('select * from course_clo_owner where crs_id = "'. $course_id .'"');
			$mte_finalize_status_re  = $mte_finalize_status_query->result_array();
			if(!empty($mte_finalize_status_re)){$mte_finalize_status =  $mte_finalize_status_re[0]['mte_finalize_flag'];}else{$mte_finalize_status = 0;}

			$tee_finalize_status_query = $this->db->query('SELECT * FROM tier_ii_section_clo_ao_attainment t where crs_id = "'. $course_id .'" and assess_type = 5');
			$tee_finalize_status_re  = $tee_finalize_status_query->result_array();
			if(!empty($tee_finalize_status_re)){$tee_finalize_status =  1;}else{$tee_finalize_status = 0;}		


			$cia_query = 'SELECT tier2.crs_id,tier2.clo_id, cast(ROUND(AVG(tier2.average_clo_da_attainment)*'.$cia_wgt.'/100)as decimal(10,2)) as co_average_da,
                                            cast(ROUND(AVG(tier2.threshold_clo_da_attainment)*'.$cia_wgt.'/100)as decimal(10,2)) co_threshold_average,
                                            AVG(tier2.attainment_level)*'.$cia_wgt.'/100 as attainment_level,
                                            cl.clo_code, cl.clo_statement, cl.cia_clo_minthreshhold , CAST(cl.mte_clo_minthreshhold as decimal(10,2)) as mte_clo_minthreshhold,
											cl.tee_clo_minthreshhold 
                                            FROM tier_ii_section_clo_overall_cia_attainment as tier2
                                            LEFT JOIN clo as cl ON cl.clo_id = tier2.clo_id
											LEFT JOIN assessment_occasions as ao ON ao.crs_id = tier2.crs_id
                                            WHERE tier2.crs_id = "'. $course_id .'"
                                            AND tier2.crclm_term_id = "'. $term_id .'"
                                            AND tier2.crclm_id = "'. $crclm_id .'"
											AND tier2.section_id != 0
                                            group by tier2.clo_id';	
			
			$mte_query = 'SELECT tier2.crs_id,tier2.clo_id, cast(ROUND(AVG(tier2.average_clo_da_attainment)*'.$cia_wgt.'/100)as decimal(10,2)) as co_average_da,
                                            cast(ROUND(AVG(tier2.threshold_clo_da_attainment)*'.$cia_wgt.'/100)as decimal(10,2)) co_threshold_average,
                                            AVG(tier2.attainment_level)*'.$cia_wgt.'/100 as attainment_level,
                                            cl.clo_code, cl.clo_statement, cl.cia_clo_minthreshhold , CAST(cl.mte_clo_minthreshhold as decimal(10,2)) as mte_clo_minthreshhold,
											cl.tee_clo_minthreshhold 
                                            FROM tier_ii_section_clo_overall_cia_attainment as tier2
                                            LEFT JOIN clo as cl ON cl.clo_id = tier2.clo_id
											LEFT JOIN assessment_occasions as ao ON ao.crs_id = tier2.crs_id
                                            WHERE tier2.crs_id = "'. $course_id .'"
                                            AND tier2.crclm_term_id = "'. $term_id .'"
                                            AND tier2.crclm_id = "'. $crclm_id .'"
											AND tier2.section_id = 0
                                            group by tier2.clo_id';
											
			$tee_query = 'SELECT att.crs_id, att.clo_id, cast(ROUND(AVG(att.average_direct_attainment)*'.$tee_wgt.'/100)as decimal(10,2)) as co_average_da,
                                            cast(ROUND(AVG(att.threshold_direct_attainment)*'.$tee_wgt.'/100)as decimal(10,2)) as co_threshold_average,
                                            AVG(att.attainment_level)*'.$tee_wgt.'/100 as attainment_level,
                                            cl.clo_code, cl.clo_statement, cl.cia_clo_minthreshhold , CAST(cl.mte_clo_minthreshhold as decimal(10,2)) as mte_clo_minthreshhold 
											,	cl.tee_clo_minthreshhold 
                                            FROM tier_ii_section_clo_ao_attainment as att
                                            LEFT JOIN clo as cl ON cl.clo_id = att.clo_id
                                            WHERE att.crs_id = "'. $course_id .'"
                                            AND att.crclm_term_id = "'. $term_id .'"
                                            AND att.crclm_id = "'. $crclm_id .'"
										    AND att.assess_type = 5
                                            group by att.clo_id';	

											
		if($type_not_selected == 0){
				$Finalize_status = '';
				$query = $this->db->query('select * from course where crs_id = "'. $course_id .'"');
				$course_status = $query->result_array();
				if(!empty($course_status)){
					$crs_cia_flag =  $course_status[0]['cia_flag'];
					$crs_mte_flag =  $course_status[0]['mte_flag'];
					$crs_tee_flag =  $course_status[0]['tee_flag'];
				}
				
				
				if($cia_finalize_status == 0 && $crs_cia_flag == 1 ){ $Finalize_status .= "<br/>".$this->lang->line('entity_cie')." is not Finalized  for this course."."<br/><a class='cursor_pointer' href=". base_url()."tier_ii/co_section_level_attainment>Click here to Finalize course .</a>";}
				if($mte_finalize_status == 0 && $crs_mte_flag == 1){ $Finalize_status .= '<br/>'.$this->lang->line('entity_mte')." is not Finalized  for this course."."<br/><a class='cursor_pointer' href=". base_url()."tier_ii/mte_co_section_level_attainment>Click here to Finalize course .</a>";}
				if($tee_finalize_status == 0 && $crs_tee_flag == 1){ $Finalize_status .= '<br/>'.$this->lang->line('entity_tee')." marks not uploaded  for this course."."<br/><a class='cursor_pointer' href=". base_url()."tier_ii/import_coursewise_tee>Click here to Upload Marks .</a>";}
		 			
					$crs_all_co_attainment_query = 'SELECT crs_id, clo_id,  cast(ROUND(SUM(co_average_da)) as decimal(10,2)) as co_average_da,
											cast(ROUND(SUM(co_threshold_average)) as decimal(10,2)) as co_threshold_average,                                           
                                            clo_code, clo_statement, 
                                            cast((SUM(attainment_level))as decimal(10,2)) as attainment_level,
                                            cia_clo_minthreshhold FROM('. $cia_query.'
													UNION ALL '. $mte_query.' UNION ALL '. $tee_query.'
													
													)s
                                            GROUP BY clo_id'; 
											

				$re_array= $this->db->query($crs_all_co_attainment_query);

		
				$data['Final_attainment'] = $re_array->result_array();	
				$data['error_msg'] = $Finalize_status;	
					return $data;
				}else{	
					$Finalize_status = '';
					if(count($type_id) == 1){				
						for($i=0;$i<count($type_id);$i++){						
							switch($type_id[$i]){
								case 3: $executing_query = $cia_attainment_query;
										if($cia_finalize_status == 0){ $Finalize_status .= '<br/>'.$this->lang->line('entity_cie')." is not Finalized  for this course."."<br/><a class='cursor_pointer' href=". base_url()."tier_ii/co_section_level_attainment>Click here to Finalize course.";} 
								break;
								case 6: $executing_query = $mte_attainment_query;
										if($mte_finalize_status == 0){ $Finalize_status .= '<br/>'.$this->lang->line('entity_mte')." is not Finalized  for this course."."<br/><a class='cursor_pointer' href=". base_url()."tier_ii/mte_co_section_level_attainment>Click here to Finalize course .</a>";}
								break;
								case 5: $executing_query = $tee_attainment_query;
										if($tee_finalize_status == 0){ $Finalize_status .= '<br/>'.$this->lang->line('entity_tee')." marks not uploaded  for this course."."<br/><a class='cursor_pointer' href=". base_url()."tier_ii/import_coursewise_tee>Click here to Upload Marks .</a>";}
								break;													
							}
						}
						$result = $this->db->query($executing_query);
						$data['Final_attainment'] = $result->result_array();	
						$data['error_msg'] = $Finalize_status;
						return $data;
					}else{	
						$query1 =  $query2 = '';
						$selected_data = '';
						$selected_data = implode (",", $type_id);
							
			
						
						$Finalize_status = '';
						
						if($selected_data == '3,6'){
								$query1 = $cia_query; $query2 = $mte_query;  
								if($cia_finalize_status == 0){ $Finalize_status .= '<br/>'.$this->lang->line('entity_cie')." is not Finalized  for this course."."<br/><a class='cursor_pointer' href=". base_url()."tier_ii/co_section_level_attainment>Click here to Finalize course.</a>";}
								if($mte_finalize_status == 0){ $Finalize_status .= '<br/>'.$this->lang->line('entity_mte')." is not Finalized  for this course."."<br/><a class='cursor_pointer' href=". base_url()."tier_ii/mte_co_section_level_attainment>Click here to Finalize course.</a>";}
						}
						if($selected_data == '3,5'){
								$query1 = $cia_query; $query2 = $tee_query;
								if($cia_finalize_status == 0){ $Finalize_status .= '<br/>'.$this->lang->line('entity_cie')." is not Finalized  for this course."."<br/><a class='cursor_pointer' href=". base_url()."tier_ii/co_section_level_attainment>Click here to Finalize course.</a>";}
								if($tee_finalize_status == 0){ $Finalize_status .= '<br/>'.$this->lang->line('entity_tee')." marks not uploaded  for this course."."<br/><a class='cursor_pointer' href=". base_url()."tier_ii/co_section_level_attainment>Click here to Upload marks.</a>";}
						}
						if($selected_data == '6,5'){
								$query1 = $mte_query; $query2 = $tee_query; 
								if($mte_finalize_status == 0){ $Finalize_status .= '<br/>'.$this->lang->line('entity_mte')." is not Finalized  for this course."."<br/><a class='cursor_pointer' href=". base_url()."tier_ii/mte_co_section_level_attainment>Click here to Finalize course </a>";}
								if($tee_finalize_status == 0){ $Finalize_status .= '<br/>'.$this->lang->line('entity_tee')." marks not uploaded  for this course."."<br/><a class='cursor_pointer' href=". base_url()."tier_ii/co_section_level_attainment>Click here to Upload marks .</a>";}
						}
						$multipe_type_attainment = 'SELECT crs_id, clo_id,  cast(ROUND(SUM(co_average_da)) as decimal(10,2)) as co_average_da,
											cast(ROUND(SUM(co_threshold_average)) as decimal(10,2)) as co_threshold_average,                                           
                                            clo_code, clo_statement, 
                                            cast((SUM(attainment_level))as decimal(10,2)) as attainment_level,
                                            cia_clo_minthreshhold FROM('. $query1.' UNION ALL '. $query2.')s
									GROUP BY clo_id';
						$multiple_data = $this->db->query($multipe_type_attainment);						
						//return $multiple_data->result_array();
						$data['Final_attainment'] =  $multiple_data->result_array();	
						$data['error_msg'] = $Finalize_status;
						return $data;
					}																	
				}
											
	}
	
	public function fetch_organisation_mte_flag(){
		$query = $this->db->query('select mte_flag from organisation ');
		$result = $query->result_array();
		return $result[0]['mte_flag'];
	}

}