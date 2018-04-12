<?php

/**
 * Description          :	Model logic for Criterion 2 (TIER 2) - Teaching Learning Process.
 * Created              :	04-04-2017
 * Author               :	Shayista Mulla
 * Modification History :
 * Date                         Modified by                      Description
  --------------------------------------------------------------------------------------------------------------- */
?>
<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class T2ug_c2_teaching_process_model extends CI_Model {

    /**
     * Function is to fetch list of curriculum courses.
     * @parameter   : curriculum id
     * @return      : Array
     */
    public function list_curriculum_courses($crclm_id = NULL) {

        $list_crs_query = 'SELECT crs_id, crs_title, crclm_term_id 
                            FROM course c 
                            WHERE crclm_id = ' . $crclm_id . ' 
                                    AND crclm_term_id IN (SELECT * 
                                    FROM (
                                        SELECT crclm_term_id 
                                        FROM crclm_terms
                                        WHERE crclm_id = "' . $crclm_id . '"
                                        limit 2, 10
                                    ) as t)';
        $result = $this->db->query($list_crs_query);
        return $result->result_array();
    }

    /**
     * Function is to fetch list of course question papers.
     * @parameter   : curriculum id and course id
     * @return      : Array
     */
    public function list_course_qp($crclm_id, $crs_id) {
        $query = 'SELECT qp.qpd_id, qpd_title, qpd_type
                    FROM qp_definition qp
                    JOIN assessment_occasions ao ON ao.qpd_id = qp.qpd_id AND ao_type_id = 1
                    WHERE qp.crclm_id = ' . $crclm_id . ' 
                       AND qp.crs_id = ' . $crs_id . '
                    UNION
                    SELECT qp.qpd_id, qpd_title, qpd_type
                    FROM qp_definition qp 
                        WHERE qp.crclm_id = ' . $crclm_id . ' 
                            AND qp.crs_id = ' . $crs_id . '
                            AND qpd_type in(4,5) 
                            AND rubrics_flag = 0';
        $result = $this->db->query($query);
        return $result->result_array();
    }

    /**
     * Function to fetch Question paper details - section 2.2.2,3.2.1,8.4.1
     * @parameters  : curriculum id,term id,course id,question paper type and question paper id.
     * @return      : Question paper details
     */
    public function qp_details($crclm_id, $term_id, $crs_id, $qp_type, $qpd_id) {

        $model_qp_meta_data_query = 'SELECT qpd.qpf_id, qpd.qpd_title, qpd.qpd_timing, qpd.qpd_gt_marks,qpd.qpd_max_marks, qpd.qpd_notes, qpd.qpd_num_units, crs.crs_title,crs.crs_code
                                        FROM qp_definition AS qpd
                                        JOIN course AS crs ON qpd.crs_id = crs.crs_id
                                        WHERE qpd.crclm_id = "' . $crclm_id . '"
                                            AND qpd.crclm_term_id = "' . $term_id . '"
                                            AND qpd.crs_id = "' . $crs_id . '" 
                                            AND qpd.qpd_type = "' . $qp_type . '" 
                                            AND qpd.qpd_id = "' . $qpd_id . '"';

        $model_qp_meta_data = $this->db->query($model_qp_meta_data_query);
        $model_qp_meta_data_res = $model_qp_meta_data->result_array();
        $data['qp_meta_data'] = $model_qp_meta_data_res;

        $call_proce = $this->db->query('CALL nba_t2ug_c2_2_2_Question_Paper(' . $qpd_id . ')');
        mysqli_next_result($this->db->conn_id);
        $proce_result = $call_proce->result_array();
        $data['question_paper_data'] = $proce_result;

        return $data;
    }

    /**
     * Function to fetch Question paper mapping entity - section 2.2.2,3.2.1,8.4.1
     * @parameters  :
     * @return      : Question paper mapping entity
     */
    public function qp_mapping_entity() {

        $qp_entity_config_query = 'SELECT entity_id, entity_name, alias_entity_name, qpf_config_orderby 
                                    FROM entity 
                                    WHERE qpf_config = 1 
                                    ORDER BY qpf_config_orderby';
        $qp_entity_config_data = $this->db->query($qp_entity_config_query);
        $qp_entity_config_result = $qp_entity_config_data->result_array();
        return $qp_entity_config_result;
    }

    /**
     * Function to fetch Question paper defination details - section 2.2.2,3.2.1,8.4.1
     * @parameters  : question paper id.
     * @return      : Question paper defination details
     */
    public function get_qpd_details($qpd_id) {
        $qp_data = $this->db->query('SELECT * 
                                        FROM qp_definition 
                                        WHERE qpd_id = ' . $qpd_id);
        return $qp_data->row_array();
    }

    /**
     * Function is to fetch term. - section - 2.2.3,3.2.1
     * @parameter   : curriculum id
     * @return      : Array
     */
    public function get_term_list($crclm_id) {
        $term_query = 'SELECT crclm_term_id, term_name FROM crclm_terms WHERE crclm_id = "' . $crclm_id . '" ';
        $term_data = $this->db->query($term_query);
        $term_list = $term_data->result_array();
        return $term_list;
    }

    /**
     * Function is to fetch course list.
     * @parameter   : curriculum id and term id
     * @return      : Array
     */
    public function get_course_list($crclm_id, $term_id) {
        $course_query = 'SELECT crs_id, crs_title FROM course WHERE crclm_id = "' . $crclm_id . '" and crclm_term_id = "' . $term_id . '" ';
        $course_data = $this->db->query($course_query);
        $course_list = $course_data->result_array();
        return $course_list;
    }

    /**
     * Function is to fetch quality student project details. - section 2.2.3
     * @parameter   : curriculum id and  course id
     * @return      : Array
     */
    public function get_quality_student_project($crclm_id = NULL, $course_id = NULL) {
        $call_proce = $this->db->query('CALL nba_t2ug_c2_2_3_COPOMapping(' . $crclm_id . ', ' . $course_id . ')');
        mysqli_next_result($this->db->conn_id);
        $proce_result = $call_proce->result_array();

        return $call_proce;
    }

    /**
     * Function is to fetch companies visited details.
     * @parameter   : program id
     * @return      : Array
     */
    public function fetch_program_companies_visited($pgm_id = NULL) {
        $call_proce = $this->db->query('CALL nba_t2ug_c2_2_4_CompaniesList(' . $pgm_id . ')');
        mysqli_next_result($this->db->conn_id);
        $proce_result = $call_proce->result_array();

        return $call_proce;
    }

    /**
     * Function is to fetch industry internship details.
     * @parameter   : curriculum id
     * @return      : Array
     */
    public function fetch_industry_internship($curriculum = NULL) {
        $call_proce = $this->db->query('CALL nba_t2ug_c2_2_5_IndustryInternship(' . $curriculum . ')');
        mysqli_next_result($this->db->conn_id);
        $proce_result = $call_proce->result_array();

        return $call_proce;
    }

    /* Function To get data from procedure call for BloomsLevelMarksDistribution_custom - section 2.2.2
     * @param : course id and question paper id
     * returns :
     */

    public function getBloomsLevelMarksDistribution_custom($crs, $qid) {
        $call_proce = $this->db->query("call getBloomsLevelMarksDistribution_custom(" . $crs . "," . $qid . ")");
        mysqli_next_result($this->db->conn_id);
        $proce_result = $call_proce->result_array();
        return $proce_result;
    }

    /* Function To get data from procedure call for COLevelMarksDistribution - section 2.2.2
     * @param   :  course id and question paper id
     * returns  :
     */

    public function getCOLevelMarksDistribution($crs, $qid) {
        $call_proce = $this->db->query("call getCOLevelMarksDistribution(" . $crs . "," . $qid . ")");
        mysqli_next_result($this->db->conn_id);
        $proce_result = $call_proce->result_array();
        return $proce_result;
    }

}

/*
 * End of file t1ug_c2_teaching_process_model.php
 * Location: .nba_sar/ug/tier1/criterion_2/t1ug_c2_teaching_process_model.php 
 */
?>