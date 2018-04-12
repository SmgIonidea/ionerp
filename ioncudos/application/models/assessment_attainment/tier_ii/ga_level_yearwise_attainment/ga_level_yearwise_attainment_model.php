<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* Description	:	Tier II GA Level yearwise Attainment

* Created		:	December 31st, 2015

* Author		:	 Shivaraj B

* Modification History:
* Date				Modified By				Description
* 28/04/2016		Bhagyalaxmi S S 		Sorted Curriculum and Term list depen

----------------------------------------------------------------------------------------------*/
?>
<?php 
class Ga_level_yearwise_attainment_model extends CI_Model{
	function get_department_list(){
		if($this->ion_auth->is_admin()) {
		$result = $this->db->get_where('department',array('status'=>1));
		}else{
		$logged_in_user_dept_id = $this->ion_auth->user()->row()->base_dept_id;
		$result = $this->db->select('dept_id, dept_name')
			->order_by('dept_name','ASC')
			->where('status',1)
			->where('dept_id',$logged_in_user_dept_id)
			->get('department');
		}
		return $result->result_array();
	}
	
	function get_program_list($dept_id){
		$result = $this->db->select('pgm_id,pgm_title,pgm_acronym')->get_where('program',array('status'=>1,'dept_id'=>$dept_id));
		return $result->result_array();
	}
	function get_ga_report($dept_id,$pgm_id,$year){
		$result = $this->db->query("call get_AcademicYear_GAAttainment (".$pgm_id.",".$year.",false,null,null);");
		//echo $this->db->last_query();
		return $result->result_array();
	}
	function get_ga_drill_down_data($ga_id,$pgm_id,$year){
		$result = $this->db->query("call get_AcademicYear_GAAttainment (".$pgm_id.",".$year.",true,".$ga_id.",null);");
		//echo $this->db->last_query();
		return $result->result_array();
	}
	function get_crclm_drill_down_data($ga_id,$crclm_id,$pgm_id,$year){
		$result = $this->db->query("call get_AcademicYear_GAAttainment (".$pgm_id.",".$year.",true,".$ga_id.",".$crclm_id.");");
		//echo $this->db->last_query();
		return $result->result_array();
	}
}// end of class 
?>