<?php
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: MOdel Logic for CO complete Report.
 * Date				Modified By				Description
 * 26-05-2015      Mritunjay B S                           Added file headers, function headers & comments. 
  ---------------------------------------------------------------------------------------------------------------------------------
 */
?>
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Po_report_model extends CI_Model {

	/*
		Function to fetch the curriculum  list from the master table		
	*/
		public function crclm_list_fetch(){
			$crlcm_list_query = 'SELECT crclm_id, crclm_name FROM curriculum order by crclm_name';
			$crclm_data = $this->db->query($crlcm_list_query);
			$crclm_result = $crclm_data -> result_array();
			return $crclm_result;
			}
			
	/*
		Function to get the term List
	*/
	
	public function get_term_list($crclm_id){
		$crclm_term_list_query = 'SELECT crclm_term_id, term_name FROM crclm_terms WHERE crclm_id = "'.$crclm_id.'"';
		$crclm_term_data = $this->db->query($crclm_term_list_query);
		$crclm_term_res = $crclm_term_data->result_array();
		return $crclm_term_res;
	}
	
	/*
		function to get course list
	*/
	public function get_po_report_data($crclm_id,$term_id){
		// var_dump($crclm_id);
		// var_dump($term_id);
		// exit;
		$po_report_query = 'CALL getPOAttainmentReport('.$crclm_id.','.$term_id.',NULL)';
		$po_report_data = $this->db->query($po_report_query);
		$po_report_res = $po_report_data->result_array();
		
		return $po_report_res;
	}
	
	/*
		Function to get course report
	*/
	
	public function co_report_data($crs_id,$po_id){
		$course_report_query = 'CALL getCOAttainmentReport('.$crs_id.','.$po_id.')';
		$course_data = $this->db->query($course_report_query);
		$course_res = $course_data->result_array();
		return $course_res;
	}
     
	/*
		Function to get course mapping data
	*/
	
	public function get_course_mapping_data($co_id){
	
		$course_mapping_query = 'SELECT p.po_id, p.po_reference,po_statement, cp.clo_po_id, cp.crs_id
									FROM clo_po_map as cp
									JOIN po as p ON p.po_id = cp.po_id
									WHERE clo_id = "'.$co_id.'" group by p.po_id';
		$course_data = $this->db->query($course_mapping_query);
		$course_res = $course_data->result_array();
		
		return $course_res;
		
	}
	
	public function get_tlo_mapping_data($co_id){
		
		$course_topic_query = 'SELECT ct.tlo_id, ct.topic_id, tl.tlo_statement,tl.tlo_code,tp.topic_title
									FROM tlo_clo_map as ct
									JOIN tlo as tl ON tl.tlo_id = ct.tlo_id
									JOIN topic as tp ON tp.topic_id = ct.topic_id
									WHERE ct.clo_id = "'.$co_id.'"  
									ORDER BY tp.topic_title , tl.tlo_code ASC';
		$course_topic_data = $this->db->query($course_topic_query);
		$course_topic_res = $course_topic_data->result_array();
		return $course_topic_res;
		
	}
	
	public function get_occassion_mapping_data($co_id){
		
		$course_occasations_query = 'SELECT qpm.actual_mapped_id, qpm.entity_id, mnq.qp_subq_code, qpm.qp_mq_id, qpm.qp_map_id, mnq.qp_unitd_id, mnq.qp_content, un.qpd_id, qpd.qpd_type,mm.mt_details_name
										FROM qp_mapping_definition as qpm
										LEFT JOIN qp_mainquestion_definition as mnq ON mnq.qp_mq_id = qpm.qp_mq_id
										LEFT JOIN qp_unit_definition as un ON un.qpd_unitd_id = mnq.qp_unitd_id
										LEFT JOIN qp_definition as qpd ON qpd.qpd_id = un.qpd_id
										LEFT JOIN master_type_details as mm ON mm.mt_details_id = qpd.qpd_type
										WHERE qpm.actual_mapped_id = "'.$co_id.'" AND qpm.entity_id = 11 
										ORDER BY mm.mt_details_name, mnq.qp_subq_code ASC';
		$course_occasations_data =  $this->db->query($course_occasations_query);
		$course_occasations_res =   $course_occasations_data->result_array();
		return $course_occasations_res;
		
	}
}
?>