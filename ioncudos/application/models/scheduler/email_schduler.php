<?php

class email_schduler extends CI_Model{

	var $emailDetailsTab = "su_email_scheduler";

	function get_schduled_emails_list(){
		$result = $this->db->select('*')->where('email_status','0')->limit(100)->get($this->emailDetailsTab);
		return $result->result_array();
	}

	function update_email_status($e_id){
		$date_time = date("Y-m-d H:i:s");
		$update_data = array('email_status'=>'1','last_email_sent'=>$date_time);
		$this->db->where('email_details_id',$e_id)->update($this->emailDetailsTab,$update_data);
	}
	
	function test_cron(){
        $date_time = date("Y-m-d H:i:s");
        $ins_data = array('value'=>$date_time,);
        return $this->db->insert('cron_test',$ins_data);
    }

}