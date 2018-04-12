<?php

class Survey_scheduler_model extends CI_Model{
	
	
	function getSurveyInfo(){
		$cur_date = date('Y-m-d');
		//$res = $this->db->query("SELECT * FROM su_survey s where end_date = CURDATE()");
		$up_status = $this->db->query("UPDATE su_survey SET status=2 WHERE end_date = CURDATE()");
		return $up_status;
	}
}
?>