<?php
/**
* Description	:	Shows scheduled emails list
* Created		:	05-11-2015. 
* Author 		:   Shivaraj Badiger
* Modification History:
* Date				Modified By				Description
-------------------------------------------------------------------------------------------------
*/
class Email_scheduler_list_model extends CI_Model{
	var $emailScheduler = "su_email_scheduler"; //db table name
	
	function getDepartmentList(){
		$result = $this->db->select('dept_id,dept_name')
		->where('status',1)
		->get('department');
		return $result->result_array();		
	}
	function getProgramList($dept_id){
		$result = $this->db->select('pgm_id,pgm_acronym')
		->where('status',1)
		->where('dept_id',$dept_id)
		->get('program');
		return $result->result_array();		
	}
	function getCurriculumList($pgm_id){
		$result = $this->db->select('crclm_id,crclm_name')
		->where('status',1)
		->where('pgm_id',$pgm_id)
		->get('curriculum');
		return $result->result_array();		 
	}
	function getSurveyList($dept_id,$pgm_id,$crclm_id){
		$result = $this->db->select('survey_id,name')
		//->where('status',1)
		->where('dept_id',$dept_id)
		->where('pgm_id',$pgm_id)
		->where('crclm_id',$crclm_id)
		->get('su_survey');
		return $result->result_array();		 
	}
	
	// funtion: function to get list of scheduled emails
	function getScheduledEmailList(){
		$query = $this->db->get($this->emailScheduler);
		if($query->num_rows>0){
			return $query->result_array();
		}else{
			return FALSE;
		}	
	}
	function getScheduledEmailListByDept($survey_id){
		//$query = $this->db->query("SELECT e.* FROM su_survey s,su_email_scheduler e where s.survey_id=e.su_survey_id AND s.dept_id='$dept_id' AND s.pgm_id='$pgm_id' AND crclm_id='$crclm_id';");
		$query = $this->db->get_where($this->emailScheduler,array('su_survey_id'=>$survey_id));
		if($query->num_rows>0){
			return $query->result_array();
		}else{
			return FALSE;
		}	
	}
	
}//END of class
?>