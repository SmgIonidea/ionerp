<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* Description	:	Tier II GA Level Attainment

* Created		:	December 21st, 2015

* Author		:	 Shivaraj B

* Modification History:
* Date				Modified By				Description

----------------------------------------------------------------------------------------------*/
?>
<?php
class Ga_level_attainment_model extends CI_Model{
	
	function get_department_list(){
		$result = $this->db->get_where('department',array('status'=>1));
		return $result->result_array();
	}
	function get_program_type(){
		$result = $this->db->get_where('program_type',array('status'=>1));
		return $result->result_array();
	}
	function get_ga_for_pgm_type($pgm_type_id){
		$result = $this->db->get_where('graduate_attributes',array('pgmtype_id'=>$pgm_type_id));
		return $result->result_array();
	}
	function get_crclmwise_ga_data($dept_id,$pgm_type,$ga_id){
		$result = $this->db->query("call get_tier_ii_GALevelAttainment($dept_id,$pgm_type,$ga_id);");
		return $result->result_array();
	}
	function get_drilldown_data($ga_id,$crclm_id){
		$result = $this->db->query("SELECT t.crclm_id,t.po_id,p.po_reference,p.po_statement,t.overall_attainment,t.attainment_level, perf.performance_level_name_alias FROM ga_po_map g,tier_ii_po_overall_attainment t,po p,performance_level_po perf WHERE t.po_id=g.po_id AND g.ga_id=$ga_id AND g.crclm_id=$crclm_id AND t.po_id=p.po_id AND t.po_id=perf.po_id AND t.attainment_level BETWEEN perf.start_range AND end_range group by g.po_id");
		return $result->result_array();
	}
	function get_assessment_levels($po_id){
		$result = $this->db->query("SELECT * FROM performance_level_po p,po where p.po_id=po.po_id AND p.po_id='".$po_id."'");
		return $result->result_array();
	}
}//end of class ga level attainment