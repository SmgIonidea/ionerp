<?php

class Survey_User extends CI_Model{
    function __construct() {
        parent::__construct();
    }
    
    function validateKey($key=null){
        $sql = "SELECT * FROM su_survey_users as users, su_survey as survey WHERE users.link_key='".$key."' AND users.status!='2' AND survey.status!='2' AND survey.end_date>=CURDATE() AND users.survey_id=survey.survey_id;";
        $query = $this->db->query($sql);
        return $query->num_rows(); 
    }
     
    function fetchUserDetailsByKey($key){
		
		
        // $this->db->select('user.survey_user_id,user.survey_id,user.link_key,user.status,stack.first_name,stack.last_name,stack.email');
        // $this->db->from('su_survey_users as user');
        // $this->db->join('su_stakeholder_details as stack', 'stack.stakeholder_detail_id = user.stakeholder_detail_id');
        // $this->db->where('user.link_key',$key);
        // $res=$this->db->get()->result_array();
        // return $res;
		
	$user_dat="SELECT survey_user_id, survey_id, stakeholder_detail_id, status, link_key FROM su_survey_users  WHERE link_key = '".$key."' ";
	$user_info = $this->db->query($user_dat);
	$usrs_res = $user_info->row_array();
	
		$survey_details = 'SELECT * FROM su_survey
                            WHERE survey_id = "'.$usrs_res['survey_id'].'" ';
        $survey_details = $this->db->query($survey_details);
        $survey_details = $survey_details->row_array();
		
		$survey_id = $usrs_res['survey_id'];
		$student_qroup = 'SELECT stakeholder_group_id from su_stakeholder_groups where student_group = 1';
		$student_group_data = $this->db->query($student_qroup);
		$student_group_res = $student_group_data->row_array();
		
		
		if($survey_details['su_stakeholder_group'] != $student_group_res['stakeholder_group_id']){
			
			// $all_user_query = "SELECT susers.survey_user_id, susers.survey_id, stholder.first_name, stholder.last_name, stholder.email, stholder.contact  FROM su_survey_users as susers JOIN su_stakeholder_details as stholder ON stholder.stakeholder_detail_id = susers.stakeholder_detail_id WHERE susers.survey_id = '".$survey_id."' AND susers.status='1' AND susers.link_key = '".$key."' ";
			
				 $all_user_query = "SELECT susers.survey_user_id, susers.survey_id, stholder.first_name, stholder.last_name, stholder.email, stholder.contact, stholder.stakeholder_detail_id AS stakeholder_id, stholder.stakeholder_group_id  FROM su_survey_users as susers JOIN su_stakeholder_details as stholder ON stholder.stakeholder_detail_id = susers.stakeholder_detail_id WHERE susers.survey_id = '".$survey_id."'  AND susers.link_key = '".$key."' ";
			
			}else{
				
				//$all_user_query = "SELECT susers.survey_user_id, susers.survey_id, stholder.first_name, stholder.last_name, stholder.email, stholder.contact_number as contact  FROM su_survey_users as susers JOIN su_student_stakeholder_details as stholder ON stholder.ssd_id = susers.stakeholder_detail_id WHERE susers.survey_id = '".$survey_id."' AND susers.status='1' AND susers.link_key = '".$key."' ";
				
				$all_user_query = "SELECT susers.survey_user_id, susers.survey_id, stholder.first_name, stholder.last_name, stholder.email, stholder.contact_number as contact, stholder.ssd_id AS stakeholder_id, stholder.stakeholder_group_id  FROM su_survey_users as susers JOIN su_student_stakeholder_details as stholder ON stholder.ssd_id = susers.stakeholder_detail_id WHERE susers.survey_id = '".$survey_id."' AND susers.link_key = '".$key."' ";
				
			}
			
			$responded_user_data = $this->db->query($all_user_query);
			$resp_res = $responded_user_data->result_array();
			return $resp_res;
		
	
    }
    
    function surveyUsersById($surveyId=null,$cond=null){
        $this->db->select('*')->from('su_survey_users');
        $this->db->where('survey_id',$surveyId);
        if(is_array($cond)){
            foreach($cond as $key=>$val){
                $this->db->where("$key","$val");
            }
        }
        $res = $this->db->get()->result_array();        
        return $res;
    }
    
    function updateStatus($key=null,$value=null){
        $this->db->where('link_key',"$key");
        $this->db->update('su_survey_users', array('status'=>$value)); 
    }
}

?>