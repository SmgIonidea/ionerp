<?php

/**
 * Description          :   Model logic for Criterion 3 (TIER 1) - Attainment of CO ,PO and PSO.
 * Created              :   01-05-2017
 * Author               :   Shayista Mulla
 * Modification History :
 * Date                     Modified by                      Description
  --------------------------------------------------------------------------------------------------------------- */
?>
<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class T1ug_c3_co_attainment_model extends CI_Model {

    /**
     * Function is get the Program assessment methods - section 3.2.1.
     * @parameter   : program id
     * @return      : array
     */
    public function pgm_assessment_methods($pgm_id) {
        $db_query = $this->db->query('CALL nba_t2ug_c3_2_1_AssessmentMethods(' . $pgm_id . ')');
        $result = $db_query->result_array();
        mysqli_next_result($this->db->conn_id);
        return $result;
    }

    /**
     * Function is get the term details - section 3.2.1,....
     * @parameter   : curriculum id
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
     * Function is get the course details - section 3.2.1,....
     * @parameter   : curriculum id and term id
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
     * Function is to fetch the details of course assement occasions - section 3.2.1.
     * @parameter   : course id,curriculum id and term id
     * @return      : array
     */
    public function course_assement_occasions_list($course_id, $crclm_id = NULL, $term_id = NULL) {
        $query = $this->db->query('CALL nba_t1ug_c3_2_1_AssessmentMethods_CO(' . $crclm_id . ', ' . $term_id . ', ' . $course_id . ')');
        mysqli_next_result($this->db->conn_id);
        $asseement_query = $query->result_array();
        return $asseement_query;
    }

    /**
     * Function to fetch  course rubric AO list - section 3.2.1
     * @parameters  : curriculum id,term id and course id
     * @return      : array
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
     * Function is get the attainment qpd rubrics - section 3.2.1.
     * @parameter   : curriculum id,course id,term id, AO id
     * @return      : array
     */
    public function attainment_qpd_rubrics($crclm_id, $course_id, $term_id, $ao_method_id) {
        $call_proce = $this->db->query('CALL nba_t1ug_c3_2_1_Rubrics(' . $ao_method_id . ')');
        mysqli_next_result($this->db->conn_id);
        $proce_result = $call_proce->result_array();
        return $call_proce;
    }

    /**
     * Function is to fetch the details course target level - section 3.2.2
     * @parameter   : course ids
     * @return      : array
     */
    public function course_target_level($crs_ids = null) {
        $course_wise_query = $this->db->query('call nba_t1ug_c3_2_2_COAttainment("' . $crs_ids . '")');
        mysqli_next_result($this->db->conn_id);
        $data['course_wise_co_attainment'] = $course_wise_query->result_array();
        return $data;
    }

    /**
     * Function is get curriculum survey attainment - section 3.3.1
     * @parameter   : survey id
     * @return      : array
     */
    public function fetch_curriculum_survey_attainment_tools($survey_id) {
        $call_proce = $this->db->query('CALL nba_t1ug_c3_3_1_Survey(' . $survey_id . ')');
        mysqli_next_result($this->db->conn_id);
        $proce_result = $call_proce->result_array();

        return $call_proce;
    }

    /**
     * Function to fetch Attainment of po - section 3.3.2
     * @parameters  : curriculum id
     * @return      : array
     */
    public function for_all_po_mapping($crclm_id) {
        $proc_query = $this->db->query('CALL nba_t1ug_c3_3_2_POAttainment(' . $crclm_id . ')');
        mysqli_next_result($this->db->conn_id);
        $proc_data = $proc_query->result_array();

        $proc_query_indirect_po = $this->db->query('CALL nba_t1ug_c3_3_2_POIndirectAttainment(' . $crclm_id . ')');
        mysqli_next_result($this->db->conn_id);
        $proc_data_indirect_attainment = $proc_query_indirect_po->result_array();
        $data['po_direct'] = $proc_data;
        $data['po_indirect'] = $proc_data_indirect_attainment;
        return $data;
    }

    /**
     * Function is get curriculum survey list - section 3.3.1,3.3.2
     * @parameter   : program id and curriculum id
     * @return      : array
     */
    public function get_curriculum_survey_list($pgm_id, $crclm_id) {
        $survey_query = 'SELECT survey_id,name 
                            FROM su_survey 
                            WHERE crclm_id = ' . $crclm_id . ' 
                                AND pgm_id = ' . $pgm_id . '
                                AND su_for = 7';
        $survey_data = $this->db->query($survey_query);
        return $survey_data->result_array();
    }

    /**
     * Function is get curriculum survey attainment - section 3.3.2
     * @parameter   : survey id
     * @return      : array
     */
    public function fetch_curriculum_survey_attainment($survey_id) {
        $call_proce = $this->db->query('CALL nba_t1ug_c3_3_2_SurveyAttainment(' . $survey_id . ')');
        mysqli_next_result($this->db->conn_id);
        $proce_result = $call_proce->result_array();

        return $call_proce;
    }

    /**
     * Function to delete filter id stored - section 3.1,3.2.2
     * @parameters  : 
     * @return      : A boolean value
     */
    public function clear_filter($nba_sar_id, $nba_sar_view_id, $nba_id) {
        $is_delete = $this->db->query('DELETE FROM nba_filters 
                                        WHERE nba_sar_view_id = ' . $nba_sar_view_id . ' 
                                            AND nba_id = ' . $nba_id);
        return $is_delete;
    }

}

/*
 * End of file t1ug_c3_co_attainment_model.php
 * Location: .nba_sar/ug/tier1/criterion_2/t1ug_c3_co_attainment_model.php 
 */
?>