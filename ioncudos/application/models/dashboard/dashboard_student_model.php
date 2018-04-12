<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dashboard_student_model extends CI_Model {
    
    
    /* Function to fetch survey details for the student.
     * @parameters: 
     * @return: an array of survey details.
    */
    public function survey_data() {
        $user_id = $this->ion_auth->user()->row()->id;
        $query = 'SELECT c.crclm_name, crs.crs_code, crs.crs_title, ssu.status AS "response_status", e.subject, ssu.*, s.*, CONCAT("survey/response/start/",ssu.link_key) AS "link"
                    FROM su_survey_users ssu
                    LEFT JOIN su_survey s ON s.survey_id = ssu.survey_id
                    LEFT JOIN su_student_stakeholder_details ssd ON ssd.ssd_id = ssu.stakeholder_detail_id
                    LEFT JOIN course crs ON crs.crs_id = s.crs_id
                    LEFT JOIN curriculum c ON c.crclm_id = crs.crclm_id
                    LEFT JOIN su_email_scheduler e ON (e.su_survey_id = s.survey_id
                    AND e.stakeholder_group_id = 5
                    AND e.stakeholder_id = ssu.stakeholder_detail_id)
                    WHERE ssd.user_id = '.$user_id.'
                    AND ssd.status_active = 1 AND s.status = 1
                    ORDER BY s.survey_id DESC , response_status ASC ';
        $query = $this->db->query($query);
        $result = $query->result_array();
        return $result;
    }
    
}

?>