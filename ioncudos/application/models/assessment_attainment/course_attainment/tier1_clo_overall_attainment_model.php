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

class Tier1_clo_overall_attainment_model extends CI_Model {
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
        $fetch_course_query = $this->db->query('SELECT DISTINCT(c.crs_id),c.crs_title
                                                FROM course c,qp_definition qpd
                                                WHERE qpd.qpd_type IN (3,5) 
                                                AND c.crs_id = qpd.crs_id
                                                AND qpd.qp_rollout = 2 
                                                AND c.crclm_term_id = ' . $term);
        return $fetch_course_query->result_array();
    }
    
    /*
     * Function to fetch the course list.
     */
    public function section_dropdown_list($crclm_id, $term_id, $course_id){
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
    
    /*
     * Function is to fetch the occasion details.
     * @param - -----.
     * returns list of occasion names.
     */

    public function occasion_fill($crclm_id, $term_id, $crs_id, $section_id) {
       
         $fetch_occasion_query = $this->db->query('SELECT a.qpd_id,a.ao_description
                                                    FROM assessment_occasions a,qp_definition q
                                                    WHERE a.crclm_id = ' . $crclm_id . '
                                                    AND a.term_id = ' . $term_id . '
                                                    AND a.crs_id = ' . $crs_id. '
                                                    AND a.section_id = '.$section_id.'
                                                    AND q.qpd_id = a.qpd_id
                                                    AND q.qp_rollout > 1
                                                    AND q.qpd_type = 3
                                                    AND a.qpd_id IS NOT NULL');
//        if ($term_id != 'select_all_term') {
//            if ($crs_id != 'select_all_course') {
//                $fetch_occasion_query = $this->db->query('SELECT a.qpd_id,a.ao_description
//													FROM assessment_occasions a,qp_definition q
//													WHERE a.crclm_id = ' . $crclm_id . '
//													AND a.term_id = ' . $term_id . '
//													AND a.crs_id = ' . $crs_id . '
//													AND q.qpd_id = a.qpd_id
//													AND q.qp_rollout > 1
//													AND q.qpd_type = 3
//													AND a.qpd_id IS NOT NULL');
//            } else if ($crs_id == 'select_all_course') {
//                $fetch_occasion_query = $this->db->query('SELECT a.qpd_id,a.ao_description 
//													FROM assessment_occasions a,qp_definition q 
//													WHERE a.crclm_id = ' . $crclm_id . ' 
//													AND a.term_id = ' . $term_id . ' 
//													AND q.qpd_id = a.qpd_id 
//													AND q.qp_rollout > 1 
//													AND q.qpd_type = 3 
//													AND a.qpd_id IS NOT NULL');
//            }
//        } else if ($term_id == 'select_all_term') {
//            $fetch_occasion_query = $this->db->query('SELECT a.qpd_id,a.ao_description,a.term_id,a.crs_id 
//													FROM assessment_occasions a,qp_definition q 
//													WHERE a.crclm_id = ' . $crclm_id . ' 
//													AND q.qpd_id = a.qpd_id 
//													AND q.qp_rollout > 1 
//													AND q.qpd_type = 3 
//													AND a.qpd_id IS NOT NULL');
//        }
        return $fetch_occasion_query->result_array();
    }
    
    /*
     * Function to fetch the course clo attainment
     */
    public function fetch_clo_attainment_data($crclm_id,  $term_id, $course_id, $section_id, $type_id, $occasion_id){
        if($type_id == 2){
            $cia = 3;
        }
        $count_occasions = 'SELECT count(ao_id) as count_ao_id FROM assessment_occasions WHERE crs_id = "'.$course_id.'" AND section_id = "'.$section_id.'" ';
        $count_occasion_data = $this->db->query($count_occasions);
        $count_result = $count_occasion_data->row_array();
        if($occasion_id != 'all_occasion'){
            $attainment_query = 'SELECT att.sclo_ao_id, att.assess_type, att.ao_id, att.crclm_id, att.crclm_term_id, att.crs_id, att.section_id, att.clo_id, '
                            . ' att.ao_weightage, att.threshold_ao_direct_attainment, att.average_ao_direct_attainment, '
                            . ' att.created_by, att.created_date, att.modified_by, att.modified_date, mtd.mt_details_name as section_name, '
                            . ' cl.clo_statement, cl.cia_clo_minthreshhold, cl.clo_code'
                            . ' FROM tier1_section_clo_ao_attainment as att'
                            . ' LEFT JOIN master_type_details  as mtd  ON mtd.mt_details_id  = att.section_id'
                            . ' LEFT JOIN clo as cl ON cl.clo_id = att.clo_id'
                            . ' WHERE att.assess_type = "'.$cia.'" AND att.ao_id = "'.$occasion_id.'" AND att.crclm_id = "'.$crclm_id.'" '
                            . ' AND att.crclm_term_id = "'.$term_id.'" AND att.crs_id = "'.$course_id.'" AND att.section_id = "'.$section_id.'" ';
        }else{
            $attainment_query = 'SELECT att.sclo_ao_id, att.assess_type, att.ao_id, att.crclm_id, att.crclm_term_id, att.crs_id, att.section_id, att.clo_id, '
                            . ' att.ao_weightage, att.threshold_ao_direct_attainment, att.average_ao_direct_attainment,cast(ROUND(SUM(att.threshold_ao_direct_attainment)/'.$count_result['count_ao_id'].') as decimal(10,2)) as threshold_ao_direct_attainment, '
                            . ' att.created_by, att.created_date, att.modified_by, att.modified_date, mtd.mt_details_name as section_name, '
                            . ' cl.clo_statement, cl.cia_clo_minthreshhold, cl.clo_code'
                            . ' FROM tier1_section_clo_ao_attainment as att'
                            . ' LEFT JOIN master_type_details  as mtd  ON mtd.mt_details_id  = att.section_id'
                            . ' LEFT JOIN clo as cl ON cl.clo_id = att.clo_id'
                            . ' WHERE att.assess_type = "'.$cia.'"  AND att.crclm_id = "'.$crclm_id.'" '
                            . ' AND att.crclm_term_id = "'.$term_id.'" AND att.crs_id = "'.$course_id.'" AND att.section_id = "'.$section_id.'" GROUP BY att.clo_id ';
        }
        
       $clo_attainment_data = $this->db->query($attainment_query);
       $clo_attainment_res = $clo_attainment_data->result_array();
      
       return $clo_attainment_res;
       
    }
    
    /*
     * Function to fetch the drilldown data for individual clo
     */
    public function fetch_drilldown_attainment_data($clo_id){
        $clo_data_query = 'SELECT clo_code, clo_statement, crs_id FROM clo WHERE clo_id = "'.$clo_id.'" ';
        $clo_data = $this->db->query($clo_data_query);
        $clo_result = $clo_data->row_array();
        
        $attainment_table_query = 'SELECT att.sclo_ao_id, att.assess_type, att.ao_id, att.crclm_id, att.crclm_term_id, att.crs_id, att.section_id, att.clo_id, '
                                . ' att.ao_weightage, att.threshold_ao_direct_attainment, att.average_ao_direct_attainment, att.created_by, '
                                . ' att.created_date, att.modified_by, att.modified_date, '
                                . ' ao.ao_description, ao.ao_name, ao.ao_id, '
                                . ' crs.total_cia_weightage, crs.total_tee_weightage '
                                . ' FROM tier1_section_clo_ao_attainment as att '
                                . ' LEFT JOIN assessment_occasions as ao ON ao.qpd_id = att.ao_id '
                                . ' LEFT JOIN course as crs ON crs.crs_id = att.crs_id '
                                . ' WHERE att.clo_id = "'.$clo_id.'" '
                                . ' AND att.crs_id = "'.$clo_result['crs_id'].'" ';
        $attainment_data = $this->db->query($attainment_table_query);
        $attainment_result = $attainment_data->result_array();
        
        $attainment['clo_tbl_data'] = $clo_result;
        $attainment['attainment_data'] = $attainment_result;
        
        //var_dump($attainment);exit;
        return $attainment;
        
    }
    
    public function get_co_mapped_questions($clo_id,$type,$ao_id) {
        //$qpd_data = $this->db->select('qpd_id')->from('assessment_occasions')->where('ao_id', $ao_id)->get()->row();
        $ao_query = 'SELECT ao_id, ao_description FROM assessment_occasions WHERE qpd_id = "'.$ao_id.'" ';
        $ao_data = $this->db->query($ao_query);
        $ao_res = $ao_data->row_array(); 
       
       // var_dump($qpd_id_res);
//            if (empty($type)) {
//                $qpd_id = "";
//            } else {
//                $qpd_id = $qpd_data['qpd_id'];
//            }
           
        if ($ao_id == "all_occasion") {
                $result = $this->db->query('SELECT qpm.actual_mapped_id, qpm.entity_id, mnq.qp_subq_code, qpm.qp_mq_id, qpm.qp_map_id, mnq.qp_unitd_id, mnq.qp_content, un.qpd_id,  qpd.qpd_type,mm.mt_details_name,mnq.qp_subq_marks,aocc.ao_description,c.clo_code,c.clo_statement   
										FROM qp_mapping_definition as qpm
										LEFT JOIN qp_mainquestion_definition as mnq ON mnq.qp_mq_id = qpm.qp_mq_id
										LEFT JOIN qp_unit_definition as un ON un.qpd_unitd_id = mnq.qp_unitd_id
										LEFT JOIN qp_definition as qpd ON qpd.qpd_id = un.qpd_id
										LEFT JOIN master_type_details as mm ON mm.mt_details_id = qpd.qpd_type 
										LEFT JOIN assessment_occasions aocc ON aocc.qpd_id = un.qpd_id 
										LEFT JOIN clo c ON c.clo_id = "' . $clo_id . '"
										WHERE qpm.actual_mapped_id = "' . $clo_id . '" AND qpm.entity_id = 11 
										AND qpd.qpd_type = 3 
										AND qpd.qp_rollout = 2 
										ORDER BY aocc.ao_description, mnq.qp_subq_code ASC');
            } else {
                 $qpd_id = $ao_id;
                $result = $this->db->query('SELECT qpm.actual_mapped_id, qpm.entity_id, mnq.qp_subq_code, qpm.qp_mq_id, qpm.qp_map_id, mnq.qp_unitd_id, mnq.qp_content, un.qpd_id,  qpd.qpd_type,mm.mt_details_name,mnq.qp_subq_marks,aocc.ao_description,c.clo_code,c.clo_statement   
										FROM qp_mapping_definition as qpm
										LEFT JOIN qp_mainquestion_definition as mnq ON mnq.qp_mq_id = qpm.qp_mq_id
										LEFT JOIN qp_unit_definition as un ON un.qpd_unitd_id = mnq.qp_unitd_id
										LEFT JOIN qp_definition as qpd ON qpd.qpd_id = un.qpd_id
										LEFT JOIN master_type_details as mm ON mm.mt_details_id = qpd.qpd_type 
										LEFT JOIN assessment_occasions aocc ON aocc.ao_id = "' . $ao_res['ao_id'] . '" 
										LEFT JOIN clo c ON c.clo_id = "' . $clo_id . '"
										WHERE qpm.actual_mapped_id = "' . $clo_id . '" AND qpm.entity_id = 11 
										AND qpd.qpd_id = "' . $qpd_id . '" AND qpd.qpd_type = 3 
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
                            . ' AND att.crclm_term_id = "'.$term_id.'" AND att.crs_id = "'.$course_id.'" AND att.section_id = "'.$section_id.'" GROUP BY att.clo_id ';
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
        
      
       
       return true;
    }
    
}