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

class Tier1_section_clo_attainment_model extends CI_Model {
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
				FROM curriculum AS c, users AS u, program AS p , map_courseto_course_instructor AS map
				WHERE u.id = "' . $loggedin_user_id . '" 
				AND u.user_dept_id = p.dept_id
				AND c.pgm_id = p.pgm_id
				AND u.id = map.course_instructor_id
				AND c.crclm_id = map.crclm_id
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
       $term_list_query = 'select DISTINCT ct.crclm_id, ct.term_name, ct.crclm_term_id ,c.course_instructor_id from map_courseto_course_instructor AS c, crclm_terms AS ct
					  where c.crclm_term_id = ct.crclm_term_id
					  AND c.course_instructor_id="'.$loggedin_user_id.'"
					  AND c.crclm_id = "'.$curriculum_id.'" ORDER BY ct.crclm_term_id';
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
        $loggedin_user_id = $this->ion_auth->user()->row()->id;
       if($this->ion_auth->in_group('Course Owner') && !$this->ion_auth->in_group('admin') && !$this->ion_auth->in_group('Chairman') && !$this->ion_auth->in_group('Program Owner')){
             $fetch_course_query = $this->db->query('SELECT DISTINCT(c.crs_id),c.crs_title
                                                FROM course c,qp_definition qpd, map_courseto_course_instructor as map  , assessment_occasions as ao
                                                WHERE qpd.qpd_type IN (3) 
                                                AND c.crs_id = qpd.crs_id
                                                AND map.crs_id = c.crs_id
                                                AND qpd.qp_rollout = 2 												
                                                AND ao.mte_flag = 0
                                                AND map.course_instructor_id="'.$loggedin_user_id.'"
                                                AND c.crclm_term_id = map.crclm_term_id												
                                                AND map.crclm_term_id = ' . $term);
        return $fetch_course_query->result_array();
        }else{
            $fetch_course_query = $this->db->query('SELECT DISTINCT(c.crs_id),c.crs_title
                                                FROM course c,qp_definition qpd , assessment_occasions as ao
                                                WHERE qpd.qpd_type IN (3)
                                                AND c.crs_id = qpd.crs_id
                                                AND qpd.qp_rollout = 2												
                                                AND ao.mte_flag = 0
                                                AND c.crclm_term_id = ' . $term);
        return $fetch_course_query->result_array();
        }
        
    }
    
    /*
     * Function to fetch the course list.
     */
    public function section_dropdown_list($crclm_id, $term_id, $course_id){
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
    public function section_dropdown_list_finalize($crclm_id, $term_id, $course_id ,$section_id){
        
        $select_section_query = 'SELECT map.section_id, mtd.mt_details_name as section_name FROM map_courseto_course_instructor as map '
                . ' LEFT JOIN master_type_details as mtd ON mtd.mt_details_id = map.section_id WHERE map.crs_id = "'.$course_id.'" and map.section_id = "'. $section_id.'" ';
        $section_data = $this->db->query($select_section_query);
        $section_result = $section_data->result_array();
        
		$res_wgt = $this->db->query("SELECT total_cia_weightage,total_tee_weightage FROM course c where crs_id='" . $course_id . "'");
        $wgt_data = $res_wgt->row();
        $cia_wgt = $wgt_data->total_cia_weightage;
        $tee_wgt = $wgt_data->total_tee_weightage;
        
        if(!empty($section_result)){
                    foreach($section_result as $section_val){
            $section_status_query = 'SELECT COUNT(section_id) as status FROM tier1_section_clo_overall_cia_attainment WHERE section_id = "'.$section_val['section_id'].'" AND crs_id = "'.$course_id.'" ';
            $status_data = $this->db->query($section_status_query);
            $status_res = $status_data->row_array();
              
            $select_query = 'SELECT tier1.clo_id, tier1.threshold_section_direct_attainment as attainment,   ROUND((tier1.threshold_section_direct_attainment)*' . $cia_wgt . '/100,2) as threshold_clo_da_attainment_wgt , tier1.average_section_direct_attainment as avg_attainment, tier1.section_id, tier1.crs_id, '
                            . ' mtd.mt_details_name as section_name, mtd.mt_details_id, cl.clo_code, cl.clo_statement, CAST(cl.cia_clo_minthreshhold as decimal(10,2)) as cia_clo_minthreshhold , cl.tee_clo_minthreshhold  '
                            . ' FROM tier1_section_clo_overall_cia_attainment as tier1 '
                            . ' LEFT JOIN master_type_details as mtd ON mtd.mt_details_id = tier1.section_id'
                            . ' LEFT JOIN clo as cl ON cl.clo_id = tier1.clo_id '
                            . ' WHERE tier1.crclm_id = "'.$crclm_id.'"'
                            . ' AND tier1.crclm_term_id = "'.$term_id.'"'
                            .'  AND tier1.crs_id = "'.$course_id.'"'
                            .'  AND tier1.section_id = "'.$section_val['section_id'].'"';
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
    /*
     * Function is to fetch the occasion details.
     * @param - -----.
     * returns list of occasion names.
     */

    public function occasion_fill($crclm_id, $term_id, $crs_id, $section_id ) {
       
      
          $fetch_occasion_query = $this->db->query('SELECT a.ao_id, a.qpd_id,a.ao_description
                                                    FROM assessment_occasions a,qp_definition q
                                                    WHERE a.crclm_id = ' . $crclm_id . '
                                                    AND a.term_id = ' . $term_id . '
                                                    AND a.crs_id = ' . $crs_id. '                                                    
                                                     AND q.qp_rollout > 0
                                                    AND q.qpd_type = 3
													AND a.mte_flag = 0 AND 
													a.section_id = '. $section_id .'
                                                    group by a.ao_id');
        return $fetch_occasion_query->result_array();
    }
    
    /*
     * Function to fetch the course clo attainment
     */
    public function fetch_clo_attainment_data($crclm_id,  $term_id, $course_id, $section_id, $type_id, $occasion_id , $occasion_not_selected){
        if($type_id == 2){
            $cia = 3;
        }	

	    $res_wgt = $this->db->query("SELECT total_cia_weightage,total_tee_weightage FROM course c where crs_id='" . $course_id . "'");
        $wgt_data = $res_wgt->row();
        $cia_wgt = $wgt_data->total_cia_weightage;
        $tee_wgt = $wgt_data->total_tee_weightage;
		
        $count_occasions = 'SELECT count(ao_id) as count_ao_id FROM assessment_occasions WHERE crs_id = "'.$course_id.'" AND section_id = "'.$section_id.'" AND mte_flag = 0 ';
        $count_occasion_data = $this->db->query($count_occasions);
        $count_result = $count_occasion_data->row_array();			
		
		$occasion = implode("," , $occasion_id);
		$occasion_selected = str_replace("'", "", $occasion);
		$error = ''; $occasion = ''; $msg =''; $error_status = '';
		for($i = 0; $i < count($occasion_id); $i++){

			$query = $this->db->query('select * from tier1_section_clo_ao_attainment as t join assessment_occasions as a on a.ao_id = t.ao_id  where a.ao_id = "'. $occasion_id[$i].'"');
			$result = $query->result_array();
			$fecth_ao_name = $this->db->query('select * from assessment_occasions where ao_id = '. $occasion_id[$i].' ');
			$fetch_result = $fecth_ao_name->result_array();
			
			if(empty($result)){ $occasion .=  $fetch_result[0]['ao_description'] .","; 	$msg =   " Marks are not  uploaded for occasion ";}
		
		}
		$error .= $msg . "<b>" . $occasion  ." .</b>";
		$error_status .= $occasion;
        if($occasion_not_selected != 0){
            $attainment_query = 'SELECT att.sclo_ao_id, att.assess_type, att.ao_id, att.crclm_id, att.crclm_term_id, att.crs_id, att.section_id, att.clo_id, '
                            . ' att.ao_weightage,  ROUND(AVG(att.threshold_ao_direct_attainment),2) as  threshold_ao_direct_attainment,    ROUND((att.threshold_ao_direct_attainment)*' . $cia_wgt . '/100,2) as threshold_clo_da_attainment_wgt , ROUND(AVG(att.average_ao_direct_attainment),2) as average_ao_direct_attainment, '
                            . ' att.created_by, att.created_date, att.modified_by, att.modified_date, mtd.mt_details_name as section_name, '
                            . ' cl.clo_statement, CAST(cl.cia_clo_minthreshhold as decimal(10,2)) as cia_clo_minthreshhold, cl.clo_code'
                            . ' FROM tier1_section_clo_ao_attainment as att'
                            . ' LEFT JOIN master_type_details  as mtd  ON mtd.mt_details_id  = att.section_id'
                            . ' LEFT JOIN clo as cl ON cl.clo_id = att.clo_id'
                            . ' WHERE att.assess_type = "'.$cia.'" AND att.ao_id IN('.$occasion_selected.') AND att.crclm_id = "'.$crclm_id.'" '
                            . ' AND att.crclm_term_id = "'.$term_id.'" AND att.crs_id = "'.$course_id.'" AND att.section_id = "'.$section_id.'"  group by cl.clo_id ';
            $clo_all_attainment_res = array();
        }else{
            $clo_overall_attainment_query = 'SELECT oatt.sclo_oall_id, oatt.crclm_id, oatt.crclm_term_id, oatt.crs_id, oatt.section_id, oatt.clo_id, '
                                            . ' oatt.threshold_section_direct_attainment, ROUND((oatt.threshold_section_direct_attainment)*' . $cia_wgt . '/100,2) as threshold_clo_da_attainment_wgt , oatt.average_section_direct_attainment, '
                                            . ' oatt.created_by, oatt.created_date, oatt.modified_by, oatt.modified_date, CAST(cl.cia_clo_minthreshhold as decimal(10,2)) as cia_clo_minthreshhold, cl.clo_code, cl.clo_statement '
                                            . ' FROM tier1_section_clo_overall_cia_attainment as oatt '
                                            . ' LEFT JOIN clo as cl ON cl.clo_id = oatt.clo_id '
                                            . ' WHERE oatt.crclm_id = "'.$crclm_id.'" '
                                            . ' AND oatt.crclm_term_id = "'.$term_id.'" '
                                            . ' AND  oatt.crs_id = "'.$course_id.'" '
                                            . ' AND oatt.section_id = "'.$section_id.'"';
            $clo_all_attainment_data = $this->db->query($clo_overall_attainment_query);
            $clo_all_attainment_res = $clo_all_attainment_data->result_array();
            
            $attainment_query = 'SELECT att.sclo_ao_id, att.assess_type, att.ao_id, att.crclm_id, att.crclm_term_id, att.crs_id, att.section_id, att.clo_id, '
                            . ' att.ao_weightage, att.threshold_ao_direct_attainment, att.average_ao_direct_attainment,cast(ROUND(AVG(att.threshold_ao_direct_attainment)) as decimal(10,2)) as threshold_ao_direct_attainment , ROUND((att.threshold_ao_direct_attainment)*' . $cia_wgt . '/100,2) as threshold_clo_da_attainment_wgt,cast(ROUND(AVG(att.average_ao_direct_attainment)) as decimal(10,2)) as average_ao_direct_attainment,'
                            . ' att.created_by, att.created_date, att.modified_by, att.modified_date, mtd.mt_details_name as section_name, '
                            . ' cl.clo_statement, CAST(cl.cia_clo_minthreshhold as decimal(10,2)) as cia_clo_minthreshhold, cl.clo_code'
                            . ' FROM tier1_section_clo_ao_attainment as att'
                            . ' LEFT JOIN master_type_details  as mtd  ON mtd.mt_details_id  = att.section_id'
                            . ' LEFT JOIN clo as cl ON cl.clo_id = att.clo_id'
                            . ' WHERE att.assess_type = "'.$cia.'"  AND att.crclm_id = "'.$crclm_id.'" '
                            . ' AND att.crclm_term_id = "'.$term_id.'" AND att.crs_id = "'.$course_id.'" AND att.section_id = "'.$section_id.'" GROUP BY att.clo_id ';
        }
        
       $clo_attainment_data = $this->db->query($attainment_query);
       $clo_attainment_res = $clo_attainment_data->result_array();
       
       // PO attainmnet for All Occasions
       $po_attain_query = 'SELECT t.sclo_oall_id, ROUND(AVG(t.threshold_section_direct_attainment),2) as direct_attainment, 
                            ROUND(AVG(t.average_section_direct_attainment),2) as average_attainment ,t.crclm_id, t.crclm_term_id, 		
                            t.crs_id,t.clo_id,cp.clo_id,cp.po_id, cp.map_level,
                            t.threshold_section_direct_attainment, t.average_section_direct_attainment,cp.clo_po_id,po.po_reference,po.po_statement,
                            GROUP_CONCAT(DISTINCT c.clo_code ORDER BY c.clo_code ASC) AS co_mapping
                            FROM tier1_section_clo_overall_cia_attainment t
                            LEFT JOIN clo_po_map cp ON t.clo_id = cp.clo_id
                            LEFT JOIN po po ON po.po_id = cp.po_id
                            LEFT JOIN clo c ON c.clo_id = cp.clo_id
                            WHERE t.crclm_id = "'.$crclm_id.'" 
                            AND t.crclm_term_id = "'.$term_id.'"
                            AND t.crs_id = "'.$course_id.'"
                            AND t.section_id = "'.$section_id.'"
                            GROUP BY cp.po_id ORDER BY po.po_id';
       $po_attain_data = $this->db->query($po_attain_query);
       $po_attain_res = $po_attain_data->result_array();
       
       $clo_data['clo_ovarall_attain'] =  $clo_all_attainment_res;
       $clo_data['all_occ_attain'] =  $clo_attainment_res;
       $clo_data['po_attainment'] =  $po_attain_res;
	   $clo_data['error_marks'] = $error;
	   $clo_data['error_status'] = $error_status;
      
       return $clo_data;
       
    }
    
    /*
     * Function to fetch the drilldown data for individual clo
     */
    public function fetch_drilldown_attainment_data($clo_id,$sec_id){
        $clo_data_query = 'SELECT clo_code, clo_statement, crs_id FROM clo WHERE clo_id = "'.$clo_id.'" ';
        $clo_data = $this->db->query($clo_data_query);
        $clo_result = $clo_data->row_array();
        
        $attainment_table_query = 'SELECT att.sclo_ao_id, att.assess_type, att.ao_id, att.crclm_id, att.crclm_term_id, att.crs_id, att.section_id, att.clo_id, '
                                . ' att.ao_weightage, ROUND(AVG(att.threshold_ao_direct_attainment),2) as  threshold_ao_direct_attainment,  AVG(att.average_ao_direct_attainment) as average_ao_direct_attainment, att.created_by, '
                                . ' att.created_date, att.modified_by, att.modified_date, '
                                . ' ao.ao_description, ao.ao_name, ao.ao_id, '
                                . ' crs.total_cia_weightage, crs.total_mte_weightage ,crs.total_tee_weightage '
                                . ' FROM tier1_section_clo_ao_attainment as att '
                                . ' LEFT JOIN assessment_occasions as ao ON ao.ao_id = att.ao_id '
                                . ' LEFT JOIN course as crs ON crs.crs_id = att.crs_id '
                                . ' WHERE att.clo_id = "'.$clo_id.'" '
                                . ' AND att.crs_id = "'.$clo_result['crs_id'].'" AND att.assess_type = 3 AND att.section_id = "'.$sec_id.'" group by att.clo_id ';
        $attainment_data = $this->db->query($attainment_table_query);
        $attainment_result = $attainment_data->result_array();
        
        $attainment['clo_tbl_data'] = $clo_result;
        $attainment['attainment_data'] = $attainment_result;
        return $attainment;
        
    }
    
    public function get_co_mapped_questions($clo_id,$type,$ao_id,$section_id ,$occasion_not_selected) {
           
        if ($occasion_not_selected == 0) {
                $result = $this->db->query('SELECT qpm.actual_mapped_id, qpm.entity_id, mnq.qp_subq_code, qpm.qp_mq_id, 
                                            qpm.qp_map_id, mnq.qp_unitd_id, mnq.qp_content, un.qpd_id,  
                                            qpd.qpd_type,mm.mt_details_name,mnq.qp_subq_marks, aocc.ao_description,
                                            c.clo_code,c.clo_statement   
                                            FROM qp_mapping_definition as qpm
                                            LEFT JOIN qp_mainquestion_definition as mnq ON mnq.qp_mq_id = qpm.qp_mq_id
                                            LEFT JOIN qp_unit_definition as un ON un.qpd_unitd_id = mnq.qp_unitd_id
                                            LEFT JOIN qp_definition as qpd ON qpd.qpd_id = un.qpd_id
                                            LEFT JOIN master_type_details as mm ON mm.mt_details_id = qpd.qpd_type 
                                            LEFT JOIN assessment_occasions aocc ON  aocc.section_id = "'.$section_id.'"
                                            LEFT JOIN clo c ON c.clo_id = "' . $clo_id . '"
                                            WHERE qpm.actual_mapped_id = "' . $clo_id . '" AND qpm.entity_id = 11 
                                            AND qpd.qpd_type = 3 
                                            AND qpd.qp_rollout = 2 AND aocc.qpd_id = un.qpd_id
                                            ORDER BY aocc.ao_description, mnq.qp_subq_code ASC');
            } else {
				$ao_data = implode("," , $ao_id);
				$ao_ids = str_replace("'", "", $ao_data);
                
				$ao_query = 'SELECT qpd_id  FROM assessment_occasions WHERE ao_id IN('.$ao_ids.') and mte_flag = 0 ';
                $ao_data = $this->db->query($ao_query);
                $ao_res = $ao_data->row_array(); 

				$qpd_id_val = implode("," , $ao_res);
				$qpd_data = str_replace("'", "", $qpd_id_val); 

                
                 
                $result = $this->db->query('SELECT qpm.actual_mapped_id, qpm.entity_id, mnq.qp_subq_code, qpm.qp_mq_id, qpm.qp_map_id, mnq.qp_unitd_id, mnq.qp_content, un.qpd_id,  qpd.qpd_type,mm.mt_details_name,mnq.qp_subq_marks,aocc.ao_description,c.clo_code,c.clo_statement   
										FROM qp_mapping_definition as qpm
										LEFT JOIN qp_mainquestion_definition as mnq ON mnq.qp_mq_id = qpm.qp_mq_id
										LEFT JOIN qp_unit_definition as un ON un.qpd_unitd_id = mnq.qp_unitd_id
										LEFT JOIN qp_definition as qpd ON qpd.qpd_id = un.qpd_id
										LEFT JOIN master_type_details as mm ON mm.mt_details_id = qpd.qpd_type 
										LEFT JOIN assessment_occasions aocc ON aocc.ao_id IN ("'. $ao_ids .'") AND aocc.section_id = "'.$section_id.'"
										LEFT JOIN clo c ON c.clo_id = "' . $clo_id . '"
										WHERE qpm.actual_mapped_id = "' . $clo_id . '" AND qpm.entity_id = 11 
										AND qpd.qpd_id IN("' . $qpd_data . '") AND qpd.qpd_type = 3 
										AND qpd.qp_rollout = 2 
										ORDER BY aocc.ao_description, mnq.qp_subq_code ASC');
            }
            
          
        return $result->result_array();
    }
    
    /*
     * Function to finalize the co attainment
     */
    
    public function co_finalize($crclm_id,  $term_id, $course_id, $section_id, $type_id, $occasion_id){
        if($type_id == 2){
            $cia = 3;
        }
        
        $count_occasions = 'SELECT count(ao_id) as count_ao_id FROM assessment_occasions WHERE crs_id = "'.$course_id.'" AND section_id = "'.$section_id.'" ';
        $count_occasion_data = $this->db->query($count_occasions);
        $count_result = $count_occasion_data->row_array();
        $attainment_query = 'SELECT att.sclo_ao_id, att.assess_type, att.ao_id, att.crclm_id, att.crclm_term_id, att.crs_id, att.section_id, att.clo_id, '
                            . ' att.ao_weightage, att.threshold_ao_direct_attainment, cast(ROUND(AVG(att.average_ao_direct_attainment))as decimal(10,2)) as average_ao_direct_attainment,cast(ROUND(AVG(att.threshold_ao_direct_attainment)) as decimal(10,2)) as threshold_ao_direct_attainment, '
                            . ' att.created_by, att.created_date, att.modified_by, att.modified_date, mtd.mt_details_name as section_name, '
                            . ' cl.clo_statement, cl.cia_clo_minthreshhold, cl.clo_code'
                            . ' FROM tier1_section_clo_ao_attainment as att'
                            . ' LEFT JOIN master_type_details  as mtd  ON mtd.mt_details_id  = att.section_id'
                            . ' LEFT JOIN clo as cl ON cl.clo_id = att.clo_id'
                            . ' WHERE att.assess_type = "'.$cia.'"  AND att.crclm_id = "'.$crclm_id.'" '
                            . ' AND att.crclm_term_id = "'.$term_id.'" AND att.crs_id = "'.$course_id.'" AND att.section_id = "'.$section_id.'"   AND att.section_id IS NOT NULL  GROUP BY att.clo_id ';
       $clo_attainment_data = $this->db->query($attainment_query);
       $clo_attainment_res = $clo_attainment_data->result_array();
        
        $data_check = 'SELECT COUNT(sclo_oall_id) as data_size FROM tier1_section_clo_overall_cia_attainment WHERE crs_id = "'.$course_id.'"  AND section_id = "'.$section_id.'" ';
        $data_data = $this->db->query($data_check);
        $data_res = $data_data->row_array();
        if($data_res['data_size'] == 0){
                foreach($clo_attainment_res as $clo_final){
                $insert_array = array(
                    'crclm_id'=>$crclm_id,
                    'crclm_term_id'=>$term_id,
                    'crs_id'=>$course_id,
                    'section_id'=>$section_id,
                    'clo_id'=>$clo_final['clo_id'],
                    'threshold_section_direct_attainment'=>$clo_final['threshold_ao_direct_attainment'],
                    'average_section_direct_attainment'=>$clo_final['average_ao_direct_attainment'],
                    'created_by'=>$this->ion_auth->user()->row()->id,
                    'created_date'=>date('y-m-d'),
                    'modified_by'=>$this->ion_auth->user()->row()->id,
                    'modified_date'=>date('y-m-d'),
                );
                $this->db->insert('tier1_section_clo_overall_cia_attainment',$insert_array);

            } 
        }else{
            $delete_query  = 'DELETE FROM tier1_section_clo_overall_cia_attainment WHERE crs_id = "'.$course_id.'" AND section_id = "'.$section_id.'" ';
            $delete_query_res  = $this->db->query($delete_query);
            
            foreach($clo_attainment_res as $clo_final){
                $insert_array = array(
                    'crclm_id'=>$crclm_id,
                    'crclm_term_id'=>$term_id,
                    'crs_id'=>$course_id,
                    'section_id'=>$section_id,
                    'clo_id'=>$clo_final['clo_id'],
                    'threshold_section_direct_attainment'=>$clo_final['threshold_ao_direct_attainment'],
                    'average_section_direct_attainment'=>$clo_final['average_ao_direct_attainment'],
                    'created_by'=>$this->ion_auth->user()->row()->id,
                    'created_date'=>date('y-m-d'),
                    'modified_by'=>$this->ion_auth->user()->row()->id,
                    'modified_date'=>date('y-m-d'),
                );
                $this->db->insert('tier1_section_clo_overall_cia_attainment',$insert_array);

            }
            
            
        }
            $get_course_instructor_id = 'SELECT course_instructor_id FROM map_courseto_course_instructor WHERE section_id = "'.$section_id.'" AND crs_id = "'.$course_id.'" ';
            $this_course_instructor_id_data = $this->db->query($get_course_instructor_id);
            $instructor_id = $this_course_instructor_id_data->row_array();
            
            $update_dashboard = 'UPDATE dashboard SET status = 0 WHERE receiver_id = "'.$instructor_id['course_instructor_id'].'" AND particular_id = "'.$course_id.'" AND crclm_id = "'.$crclm_id.'" AND entity_id = 4 ';
            $update_dash = $this->db->query($update_dashboard);
            
            $update_query = 'UPDATE map_courseto_course_instructor SET cia_finalise_flag = 1 WHERE section_id = "'.$section_id.'" AND crs_id = "'.$course_id.'" ';
            $update_suc = $this->db->query($update_query);
       
       return true;
    }
    
         
    public function fetch_co_details($param) {
        extract($param);
        if($type_all_selected == 0 || $type_all_selected == '' ) {
            $query = $this->db->query('SELECT c.crclm_name AS "crclm_name", t.term_name AS "term_name", crs.crs_code AS "crs_code", crs.crs_title AS "crs_title", mtd.mt_details_name AS "section_name", mtd.mt_details_name_desc AS "section_desc", GROUP_CONCAT(o.ao_description) AS "occasion"
                                        FROM curriculum c
                                        LEFT JOIN crclm_terms t ON t.crclm_id = c.crclm_id
                                        LEFT JOIN course crs ON (crs.crclm_id = c.crclm_id AND t.crclm_term_id = crs.crclm_term_id)
                                        LEFT JOIN master_type_details mtd ON mtd.mt_details_id = '.$section_id.'
                                        LEFT JOIN assessment_occasions o ON (o.crclm_id = c.crclm_id AND o.term_id = t.crclm_term_id AND o.crs_id = crs.crs_id AND o.section_id = '.$section_id.')
                                        WHERE c.crclm_id = '.$crclm_id.'
                                        AND t.crclm_term_id = '.$term_id.'
                                        AND crs.crs_id = '.$crs_id.'
                                        AND o.ao_id IN (SELECT ao_id FROM assessment_occasions a WHERE a.crclm_id = '.$crclm_id.'
                                        AND a.term_id = '.$term_id.' AND a.crs_id = '.$crs_id.' AND a.section_id = '.$section_id.')');
        } else {
		 $ao_data = implode("," , $occasion_id);
				$ao_ids = str_replace("'", "", $ao_data);
        $query = $this->db->query('SELECT c.crclm_name AS "crclm_name", t.term_name AS "term_name", crs.crs_code AS "crs_code", crs.crs_title AS "crs_title", mtd.mt_details_name AS "section_name", mtd.mt_details_name_desc AS "section_desc", o.ao_description AS "occasion"
                                        FROM curriculum c
                                        LEFT JOIN crclm_terms t ON t.crclm_id = c.crclm_id
                                        LEFT JOIN course crs ON (crs.crclm_id = c.crclm_id AND t.crclm_term_id = crs.crclm_term_id)
                                        LEFT JOIN master_type_details mtd ON mtd.mt_details_id = '.$section_id.'
                                        LEFT JOIN assessment_occasions o ON (o.crclm_id = c.crclm_id AND o.term_id = t.crclm_term_id AND o.crs_id = crs.crs_id AND o.section_id = '.$section_id.')
                                        WHERE c.crclm_id = '.$crclm_id.'
                                        AND t.crclm_term_id = '.$term_id.'
                                        AND crs.crs_id = '.$crs_id.'
                                        AND o.ao_id IN ("'. $ao_ids.'")');
        }
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
	public function error_status_data($course_id, $section_id){
		
		$query = $this->db->query("SELECT a.ao_id , a.qpd_id , a.ao_description,q.qp_rollout ,
												CASE WHEN  (a.qpd_id IS NULL || a.qpd_id = 0) THEN concat(' >> Question Paper is not defined for these Occasions : <b>',  group_concat(a.ao_description SEPARATOR  '  ,  ') ,'</b> ', '  ','')
												WHEN  (q.qp_rollout = 1) THEN concat('','  <br/> >> Assessment data (student marks) are not imported/uploaded for these Occasions : <b>', group_concat(a.ao_description SEPARATOR  ' , ') ,'</b>')
												ELSE ' ' END as status_data
												FROM assessment_occasions as a
														left join qp_definition as q on q.qpd_id = a.qpd_id
														where a.crs_id = ". $course_id ."
														and a.section_id = ". $section_id."
													and (q.qp_rollout < 2 OR q.qp_rollout IS NULL)
														group by qp_rollout");
		return  $query->result_array();
	
	}
    
}