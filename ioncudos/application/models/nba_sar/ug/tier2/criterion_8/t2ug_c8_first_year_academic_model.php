<?php

/**
 * Description          :   Model logic for NBA SAR Report - Section 8 (TIER II) - First Year Academics
 * Created              :   26-12-2016
 * Author               :   Shayista Mulla
 * Date                     Modified By                 Description
  --------------------------------------------------------------------------------------------------------- */
?>
<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class T2ug_c8_first_year_academic_model extends CI_Model {

    /**
     * Function to fetch first year sfr details - section 8.1
     * @parameters  :
     * @return      :
     */
    public function fysfr_grid($crclm_id, $pgm_id) {
        $query = 'SELECT pgm_id, start_year 
                    FROM curriculum 
                    WHERE crclm_id = "' . $crclm_id . '" ';
        $result_data = $this->db->query($query);
        $result = $result_data->row_array();

        $call_proce = $this->db->query('CALL nba_t2ug_c8_1_Student_Faculty_Ratio_IYear(' . $result['start_year'] . ', ' . $pgm_id . ')');
        mysqli_next_result($this->db->conn_id);
        $proce_result = $call_proce->result_array();

        return $proce_result;
    }

    /**
     * Function to fetch facutly teaching fycc - section 8.2
     * @parameters  :
     * @return      :
     */
    public function get_facutly_teaching_fycc($crclm_id, $pgm_id, $dept_id) {
        $query = 'SELECT pgm_id, start_year 
                    FROM curriculum 
                    WHERE crclm_id = "' . $crclm_id . '" ';
        $result_data = $this->db->query($query);
        $result = $result_data->row_array();

        $call_proce = $this->db->query('CALL nba_t2ug_c8_2_Faculty_Qualification_IYear(' . $result['start_year'] . ', ' . $pgm_id . ', ' . $dept_id . ')');
        mysqli_next_result($this->db->conn_id);
        $proce_result = $call_proce->result_array();

        return $proce_result;
    }

    /**
     * Function to fetch academic performance details- section 8.3
     * @parameters  :
     * @return      : 
     */
    public function get_academic_performance($crclm_id, $pgm_id) {
        $query = 'SELECT pgm_id, start_year 
                    FROM curriculum 
                    WHERE crclm_id = "' . $crclm_id . '" ';
        $result_data = $this->db->query($query);
        $result = $result_data->row_array();

        $call_proce = $this->db->query('CALL nba_t2ug_c4_3_AcademicPerformance(' . $result['pgm_id'] . ',' . $result['start_year'] . ',1' . ')');
        mysqli_next_result($this->db->conn_id);
        $proce_result = $call_proce->result_array();

        return $proce_result;
    }

    /**
     * Function to get the Term List
     * @parameters  :
     * @return      : array
     */
    public function get_term_list($crclm_id) {
        $term_query = 'SELECT crclm_term_id, term_name 
                                FROM crclm_terms 
                                WHERE crclm_id = "' . $crclm_id . '" 
                                LIMIT 2';
        $term_data = $this->db->query($term_query);
        $term_list = $term_data->result_array();
        return $term_list;
    }

    /**
     * Function to get the curriculum courses
     * @parameters  :
     * @return      : array
     */
    public function list_curriculum_courses($crclm_id = NULL) {
        $query = 'SELECT crs_id, crs_title, crclm_term_id 
                        FROM course c 
                        WHERE crclm_id = ' . $crclm_id . ' 
                            AND crclm_term_id not in (
                                SELECT * 
                                FROM (SELECT crclm_term_id 
                                      FROM crclm_terms
                                        WHERE crclm_id = "' . $crclm_id . '"
                                        limit 2, 10
                                      )as t)';
        $result = $this->db->query($query);
        return $result->result_array();
    }

    /**
     * Function to is get course list
     * @parameters  :
     * @return      : array
     */
    public function get_course_list($crclm_id, $term_id) {
        $course_query = 'SELECT crs_id, crs_title 
                            FROM course 
                            WHERE crclm_id = "' . $crclm_id . '" 
                                    AND crclm_term_id = "' . $term_id . '"  ';
        $course_data = $this->db->query($course_query);
        $course_list = $course_data->result_array();

        return $course_list;
    }

    /**
     * Function to fetch the Attainment of all po related to curriculum
     * @parameters  :
     * @return      :
     */
    public function for_all_po_mapping($crclm_id) {
        $term_id_query = 'SELECT crclm_term_id 
                            FROM crclm_terms 
                            WHERE crclm_id = "' . $crclm_id . '" 
                            LIMIT 2';
        $term_data = $this->db->query($term_id_query);
        $term_ids = $term_data->result_array();
        $term_id_array = '';

        foreach ($term_ids as $term) {
            $term_id_array[] = $term['crclm_term_id'];
        }

        $term_id_val = implode(',', $term_id_array);
        $proc_query = $this->db->query('CALL nba_t2ug_c8_5_1_POAttainment(' . $crclm_id . ',"' . $term_id_val . '")');
        mysqli_next_result($this->db->conn_id);
        $proc_data = $proc_query->result_array();

        $proc_query_indirect_po = $this->db->query('CALL nba_t2ug_c3_3_2_POIndirectAttainment(' . $crclm_id . ')');
        mysqli_next_result($this->db->conn_id);
        $proc_data_indirect_attainment = $proc_query_indirect_po->result_array();
        $data['po_direct'] = $proc_data;
        $data['po_indirect'] = $proc_data_indirect_attainment;

        return $data;
    }

}

/*
 * End of file t2ug_c8_first_year_academics_model.php 
 * Location: .nba_sar/t2ug_c8_first_year_academics_model.php
 */
?>