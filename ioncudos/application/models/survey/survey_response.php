<?php

class Survey_Response extends CI_Model{
    function __construct() {
        parent::__construct();
    }
    
    function addSurveyResponse($surveyId,$userId,$questionId){
        $data = array(
            'survey_id' => $surveyId,
            'survey_user_id' => $userId,
            'survey_question_id' => $questionId,
            'responded_on' => date('Ymd')
        );
        $this->db->insert('su_survey_response',$data);
	return $this->db->insert_id();
    }
    
    function updateComments($surveyId,$userId,$questionId,$comment){
        $data = array(
            'comment' => $comment
        );
        $this->db->where('survey_id', $surveyId);
        $this->db->where('survey_user_id', $userId);
        $this->db->where('survey_question_id', $questionId);
        $this->db->update('su_survey_response', $data); 
    }
    
    function questionResponseCount($questionId=null){
        //echo "question ID: ".$questionId." <br />";
        $this->db->select('count(*) as total');
        $this->db->from('su_survey_response');
        $this->db->where('survey_question_id',$questionId);
        $result = $this->db->get()->result_array();
        return $result[0]['total'];
    }
    
    function optionResponseCount($optionId=null){
        //echo "Option ID: ".$optionId." <br />";
        $this->db->select('count(*) as total');
        $this->db->from('su_survey_resp_options');
        $this->db->where('survey_qstn_option_id',$optionId);
        $result = $this->db->get()->result_array();
        return $result[0]['total'];
    }
}

?>