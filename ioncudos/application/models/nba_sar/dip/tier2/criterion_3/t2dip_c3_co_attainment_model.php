<?php

/**
 * Description          :   Model logic for NBA SAR Report - Section 3.2 and 3.3 (Diploma TIER II) - Attainment of co and po.
 * Created              :   05-06-2017
 * Author               :   Shayista Mulla 		  
 * Modification History :
 *   Date                   Modified By                			Description
  ------------------------------------------------------------------------------------------ */
?>
<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class T2dip_c3_co_attainment_model extends CI_Model {

    /**
     * Function to fetch Program assessment methods - section 3.2.1 - 1
     * @parameters  : program id
     * @return      : array
     */
    public function pgm_assessment_methods($pgm_id) {
        $db_query = $this->db->query('CALL nba_t2dip_c3_2_1_AssessmentMethods(' . $pgm_id . ')');
        $result = $db_query->result_array();
        mysqli_next_result($this->db->conn_id);
        return $result;
    }

    /**
     * Function to fetch term list - section 3.2.1,....
     * @parameters  : curriculum id
     * @return      : array
     */
    public function get_term_list($crclm_id) {
        $term_query = 'SELECT crclm_term_id, term_name 
                        FROM crclm_terms 
                        WHERE crclm_id = "' . $crclm_id . '"';
        $term_data = $this->db->query($term_query);
        $term_list = $term_data->result_array();
        return $term_list;
    }

    /**
     * Function to fetch course list - section 3.2.1,....
     * @parameters  : curriculum id,term id 
     * @return      : array
     */
    public function get_course_list($crclm_id, $term_id) {
        $course_query = 'SELECT crs_id, crs_title 
                            FROM course 
                            WHERE crclm_id = "' . $crclm_id . '" 
                                AND crclm_term_id = "' . $term_id . '" ';
        $course_data = $this->db->query($course_query);
        $course_list = $course_data->result_array();
        return $course_list;
    }

    /**
     * Function to fetch course assement occasions list - section 3.2.1 - 1 
     * @parameters  : curriculum id,term id 
     * @return      : array
     */
    public function course_assement_occasions_list($course_id, $crclm_id = NULL, $term_id = NULL) {
        $query = $this->db->query('CALL nba_t2dip_c3_2_1_AssessmentMethods_CO(' . $crclm_id . ', ' . $term_id . ', ' . $course_id . ')');
        mysqli_next_result($this->db->conn_id);
        $asseement_query = $query->result_array();
        return $asseement_query;
    }

    /**
     * Function to fetch course assessment occasion - section 3.2.1 - 3
     * @parameters  : curriculum id,term id,course id
     * @return      : Array
     */
    public function get_course_assessment_occasion($crclm_id, $term_id, $course_id) {
        $result = $this->db->query('SELECT ao_method_id,ao_method_name 
                                    FROM ao_method 
                                    WHERE crs_id= ' . $course_id . '');
        mysqli_next_result($this->db->conn_id);
        $result_data = $result->result_array();
        return $result_data;
    }

    /**
     * Function to fetch attainment qpd rubrics - section 3.2.1 - 3
     * @parameters  : curriculum id,course id,term id, ao method id
     * @return      : array
     */
    public function attainment_qpd_rubrics($crclm_id, $course_id, $term_id, $ao_method_id) {
        $call_proce = $this->db->query('CALL nba_t2dip_c3_2_1_Rubrics(' . $ao_method_id . ')');
        mysqli_next_result($this->db->conn_id);
        $proce_result = $call_proce->result_array();
        return $call_proce;
    }

    /**
     * Function to fetch course target level - section 3.2.2
     * @parameters  : curriculum id
     * @return      : array
     */
    public function course_target_level($crs_ids = null) {
        $course_wise_query = $this->db->query('call nba_t2dip_c3_2_2_COAttainment("' . $crs_ids . '")');
        mysqli_next_result($this->db->conn_id);
        $data['course_wise_co_attainment'] = $course_wise_query->result_array();
        return $data;
    }

    /**
     * Function to fetch curriculum survey attainment tools - section 3.3.1 - 1
     * @parameters  : survey id
     * @return      : array
     */
    public function fetch_curriculum_survey_attainment_tools($survey_id) {
        $call_proce = $this->db->query('CALL nba_t2dip_c3_3_1_Survey(' . $survey_id . ')');
        mysqli_next_result($this->db->conn_id);
        $proce_result = $call_proce->result_array();

        return $call_proce;
    }

    /**
     * Function to fetch Attainment of all po - section 3.3.2 1
     * @parameters  : curriculum id
     * @return      : array
     */
    public function for_all_po_mapping($crclm_id) {
        $proc_query = $this->db->query('CALL nba_t2dip_c3_3_2_POAttainment(' . $crclm_id . ')');
        mysqli_next_result($this->db->conn_id);
        $proc_data = $proc_query->result_array();

        $proc_query_indirect_po = $this->db->query('CALL nba_t2dip_c3_3_2_POIndirectAttainment(' . $crclm_id . ')');
        mysqli_next_result($this->db->conn_id);
        $proc_data_indirect_attainment = $proc_query_indirect_po->result_array();
        $data['po_direct'] = $proc_data;
        $data['po_indirect'] = $proc_data_indirect_attainment;
        return $data;
    }

    /**
     * Function to fetch curriculum survey list - section 3.3.1
     * @parameters  : program id ,curriculum id
     * @return      : array
     */
    public function get_curriculum_survey_list($pgm_id, $crclm_id) {
        $survey_query = 'SELECT survey_id, name 
                            FROM su_survey 
                            WHERE crclm_id = ' . $crclm_id . ' 
                                AND pgm_id = ' . $pgm_id . '
                                AND su_for = 7';
        $survey_data = $this->db->query($survey_query);
        return $survey_data->result_array();
    }

    /**
     * Function to fetch curriculum survey attainment - section 3.3.2 2
     * @parameters  : survey id
     * @return      : array
     */
    public function fetch_curriculum_survey_attainment($survey_id) {
        $call_proce = $this->db->query('CALL nba_t2dip_c3_3_2_SurveyAttainment(' . $survey_id . ')');
        mysqli_next_result($this->db->conn_id);
        $proce_result = $call_proce->result_array();

        return $call_proce;
    }

    /**
     * Function to clear stored filter ids - section 3.2.2
     * @parameters  : nba sar id,nba sar view id ,nba id
     * @return      : a boolean value
     */
    public function clear_filter($nba_sar_id, $nba_sar_view_id, $nba_id) {
        $is_delete = $this->db->query('DELETE FROM nba_filters 
                                        WHERE nba_sar_view_id = ' . $nba_sar_view_id . ' 
                                            AND nba_id = ' . $nba_id);
        return $is_delete;
    }

}

/*
 * End of file t2dip_c3_co_attainment_model.php 
 * Location: .nba_sar/t2dip_c3_co_attainment_model.php
 */
?>