<?php

class Survey_Resp_Option extends CI_Model{
    function __construct() {
        parent::__construct();
    }
    
    function addRespOptions($questionId,$optionId,$responseId){
        $data = array(
            'survey_question_id' => $questionId,
            'survey_qstn_option_id' => $optionId,
            'survey_response_id' => $responseId
        );
        //print_r($data);
        $this->db->insert('su_survey_resp_options',$data);
	return $this->db->insert_id();
    }
}

?>