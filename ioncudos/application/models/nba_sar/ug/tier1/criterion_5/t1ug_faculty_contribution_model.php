<?php

/**
 * Description          :   Model logic for NBA SAR Report - Section 5 (TIER I) - Faculty Information and Contributions 
 * Created              :   06-05-2017
 * Author               :   Shayista Mulla 		  
 * Modification History :
 * Date                     Modified By                     Description
  ------------------------------------------------------------------------------------------ */
?>
<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class T1ug_faculty_contribution_model extends CI_Model {

    /**
     * Function to faculty information and contribution - section 5
     * @parameters  :   year,dept id
     * @return      :   array
     */
    public function fetch_faculty_information_contribution($year, $dept_id) {
        $call_proce = $this->db->query('CALL nba_t1ug_c5_Faculty_Information_Contributions(' . $year . ',' . $dept_id . ')');
        mysqli_next_result($this->db->conn_id);
        $proce_result = $call_proce->result_array();

        return $proce_result;
    }

    /**
     * Function to student - faculty ratio (SFR) - section 5.1
     * @parameters  : curriculum id ,program id
     * @return      : array
     */
    public function fetch_student_faculty_ratio_sfr($curriculum_id, $pgm_id) {
        $query = 'SELECT pgm_id, start_year 
                    FROM curriculum 
                    WHERE crclm_id = "' . $curriculum_id . '" ';
        $result_data = $this->db->query($query);
        $result = $result_data->row_array();

        $call_proce = $this->db->query('CALL nba_t1ug_c5_1_Student_Faculty_Ratio(' . $result['start_year'] . ', ' . $pgm_id . ')');
        mysqli_next_result($this->db->conn_id);
        $proce_result = $call_proce->result_array();

        return $call_proce;
    }

    /**
     * Function to faculty cadre proportion - section 5.2
     * @parameters  :   curriculum id ,program id
     * @return      :   array
     */
    public function fetch_faculty_cadre_proportion($curriculum_id, $dept_id, $pgm_id) {
        $query = 'SELECT pgm_id, start_year 
                    FROM curriculum 
                    WHERE crclm_id = "' . $curriculum_id . '" ';
        $result_data = $this->db->query($query);
        $result = $result_data->row_array();

        $call_proce = $this->db->query('CALL nba_t1ug_c5_2_Faculty_Cadre_Proportion(' . $result['start_year'] . ', ' . $pgm_id . ', ' . $dept_id . ')');
        mysqli_next_result($this->db->conn_id);
        $proce_result = $call_proce->result_array();

        return $proce_result;
    }

    /**
     * Function to faculty cadre proportion - section 5.3
     * @parameters  :   curriculum id ,program id
     * @return      :   array
     */
    public function fetch_faculty_qualification($curriculum_id, $dept_id, $pgm_id) {
        $query = 'SELECT pgm_id, start_year 
                        FROM curriculum 
                        WHERE crclm_id = "' . $curriculum_id . '" ';
        $result_data = $this->db->query($query);
        $result = $result_data->row_array();

        $call_proce = $this->db->query('CALL nba_t1ug_c5_3_Faculty_Qualification(' . $result['start_year'] . ', ' . $pgm_id . ', ' . $dept_id . ')');
        mysqli_next_result($this->db->conn_id);
        $proce_result = $call_proce->result_array();

        return $call_proce;
    }

    /**
     * Function to faculty retention - section 5.4
     * @parameters  :   curriculum id ,program id
     * @return      :   array
     */
    public function fetch_faculty_retention($curriculum_id, $dept_id, $pgm_id) {
        $query = 'SELECT pgm_id, start_year 
                    FROM curriculum 
                    WHERE crclm_id = "' . $curriculum_id . '" ';
        $result_data = $this->db->query($query);
        $result = $result_data->row_array();

        $call_proce = $this->db->query('CALL nba_t1ug_c5_4_Faculty_Retention(' . $result['start_year'] . ', ' . $pgm_id . ', ' . $dept_id . ')');
        mysqli_next_result($this->db->conn_id);
        $proce_result = $call_proce->result_array();

        return $call_proce;
    }

    /**
     * Function to Faculty competencies - section 5.5
     * @parameters  :   department id
     * @return      :   array
     */
    public function fetch_faculty_competencies($dept_id) {
        $call_proce = $this->db->query('CALL nba_t1ug_c5_5_Faculty_competencies(' . $dept_id . ')');
        mysqli_next_result($this->db->conn_id);
        $proce_result = $call_proce->result_array();

        return $call_proce;
    }

    /*
     * Function to faculty development training - section 5.7
     * @parameters  :   year ,department id and program id
     * @return      :   array
     */

    public function fetch_faculty_development_training($year, $dept_id, $pgm_id) {
        $call_proce = $this->db->query('CALL nba_t1ug_c5_7_Faculty_Development_Training(' . $dept_id . ',' . $year . ',' . $pgm_id . ')');
        mysqli_next_result($this->db->conn_id);
        $proce_result = $call_proce->result_array();

        return $proce_result;
    }

    /**
     * Function is to fetch academic research details - section 5.8.1
     * @paramenters :   dept id
     * @return      :   array
     */
    public function fetch_academic_research($dept_id) {
        $call_proce = $this->db->query('CALL nba_t1ug_c5_8_1_Academic_Research(' . $dept_id . ')');
        mysqli_next_result($this->db->conn_id);
        $proce_result = $call_proce->result_array();

        return $proce_result;
    }

    /**
     * Function is to fetch academic book published details - section 5.8.1
     * @paramenters :   dept id
     * @return      :   array
     */
    public function fetch_academic_book_published($dept_id) {
        $call_proce = $this->db->query('CALL nba_t1ug_c5_8_1_Book_Published(' . $dept_id . ')');
        mysqli_next_result($this->db->conn_id);
        $proce_result = $call_proce->result_array();

        return $proce_result;
    }

    /**
     * Function is to fetch academic PHD details - section 5.8.1
     * @paramenters :   dept id
     * @return      :   array
     */
    public function fetch_academic_PHD_details($dept_id) {
        $call_proce = $this->db->query('CALL nba_t1ug_c5_8_1_PHD_details(' . $dept_id . ')');
        mysqli_next_result($this->db->conn_id);
        $proce_result = $call_proce->result_array();

        return $proce_result;
    }

    /**
     * Function is to fetch sponsored research details - section 5.8.2
     * @paramenters :   dept id
     * @return      :   array
     */
    public function fetch_sponsored_research($dept_id) {
        $call_proce = $this->db->query('CALL nba_t1ug_c5_8_2_Sponsored_Research(' . $dept_id . ')');
        mysqli_next_result($this->db->conn_id);
        $proce_result = $call_proce->result_array();

        return $proce_result;
    }

    /**
     * Function is to fetch consultancy projects details  - section 5.8.4
     * @paramenters : dept id
     * @return      : array
     */
    public function fetch_consultancy_projects($dept_id) {
        $call_proce = $this->db->query('CALL nba_t1ug_c5_8_4_Consultancy_Projects(' . $dept_id . ')');
        mysqli_next_result($this->db->conn_id);
        $proce_result = $call_proce->result_array();

        return $proce_result;
    }

    /**
     * Function to faculty information condition - section 5.9
     * @parameters  :   year,department id
     * @return      :   array
     */
    public function fetch_faculty_performance_appraisal($year, $dept_id) {
        $call_proce = $this->db->query('CALL nba_t1ug_c5_9_Faculty_Performance_Appraisal(' . $year . ',' . $dept_id . ')');
        mysqli_next_result($this->db->conn_id);
        $proce_result = $call_proce->result_array();

        return $proce_result;
    }

    /*
     * Function to faculty information condition - section 5.10
     * @parameters  :   year,department id
     * @return      :   array
     */

    public function fetch_faculty_adjunct($year, $dept_id) {
        $call_proce = $this->db->query('CALL nba_t1ug_c5_10_Faculty_Visiting(' . $year . ',' . $dept_id . ')');
        mysqli_next_result($this->db->conn_id);
        $proce_result = $call_proce->result_array();

        return $proce_result;
    }

}

/*
 * End of file t1ug_c5_faculty_contribution_model.php
 * Location: .nba_sar/ug/tier1/criterion_5/t1ug_c5_faculty_contribution_model.php 
 */
?>