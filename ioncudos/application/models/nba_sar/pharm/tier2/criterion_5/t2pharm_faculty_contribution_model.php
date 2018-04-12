<?php

/**
 * Description          :   Model logic for Criterion 5 (Pharmacy TIER 2) - Faculty Information and Contributions.
 * Created              :   17-06-2017
 * Author               :   Shayista Mulla  
 * Modification History : 
 * Date                     Modified By                         Description

  ----------------------------------------------------------------------------------------------------------- */
?>

<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class T2pharm_faculty_contribution_model extends CI_Model {
    /*
     * Function to faculty information and contribution - section 5
     * @parameters  : year
     * @return      : array
     */

    public function fetch_faculty_information_contribution($year, $dept_id) {
        $call_proce = $this->db->query('CALL nba_t2pharm_c5_Faculty_Information_Contributions(' . $year . ',' . $dept_id . ')');
        mysqli_next_result($this->db->conn_id);
        $proce_result = $call_proce->result_array();

        return $proce_result;
    }

    /*
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

        $call_proce = $this->db->query('CALL nba_t2pharm_c5_1_Student_Faculty_Ratio(' . $result['start_year'] . ', ' . $pgm_id . ')');
        mysqli_next_result($this->db->conn_id);
        $proce_result = $call_proce->result_array();

        return $call_proce;
    }

    /*
     * Function to faculty cadre proportion - section 5.2
     * @parameters  : curriculum id ,program id
     * @return      : array
     */

    public function fetch_faculty_cadre_proportion($curriculum_id, $dept_id, $pgm_id) {
        $query = 'SELECT pgm_id, start_year 
                    FROM curriculum 
                    WHERE crclm_id = "' . $curriculum_id . '" ';
        $result_data = $this->db->query($query);
        $result = $result_data->row_array();

        $call_proce = $this->db->query('CALL nba_t2pharm_c5_2_Faculty_Cadre_Proportion(' . $result['start_year'] . ', ' . $pgm_id . ', ' . $dept_id . ')');
        mysqli_next_result($this->db->conn_id);
        $proce_result = $call_proce->result_array();

        return $proce_result;
    }

    /*
     * Function to faculty cadre proportion - section 5.3
     * @parameters  : curriculum id ,program id
     * @return      : array
     */

    public function fetch_faculty_qualification($curriculum_id, $dept_id, $pgm_id) {
        $query = 'SELECT pgm_id, start_year 
                    FROM curriculum 
                    WHERE crclm_id = "' . $curriculum_id . '" ';
        $result_data = $this->db->query($query);
        $result = $result_data->row_array();

        $call_proce = $this->db->query('CALL nba_t2pharm_c5_3_Faculty_Qualification(' . $result['start_year'] . ', ' . $pgm_id . ', ' . $dept_id . ')');
        mysqli_next_result($this->db->conn_id);
        $proce_result = $call_proce->result_array();

        return $call_proce;
    }

    /*
     * Function to faculty retention - section 5.4
     * @parameters  : curriculum id ,program id
     * @return      : array
     */

    public function fetch_faculty_retention($curriculum_id, $dept_id, $pgm_id) {
        $query = 'SELECT pgm_id, start_year 
                        FROM curriculum 
                        WHERE crclm_id = "' . $curriculum_id . '" ';
        $result_data = $this->db->query($query);
        $result = $result_data->row_array();

        $call_proce = $this->db->query('CALL nba_t2pharm_c5_4_Faculty_Retention(' . $result['start_year'] . ', ' . $pgm_id . ', ' . $dept_id . ')');
        mysqli_next_result($this->db->conn_id);
        $proce_result = $call_proce->result_array();

        return $call_proce;
    }

    /*
     * Function to faculty development training - section 5.6
     * @parameters  : year,department id and program id
     * @return      : array
     */

    public function fetch_faculty_development_training($year, $dept_id, $pgm_id) {
        $call_proce = $this->db->query('CALL nba_t2pharm_c5_6_Faculty_Development_Training(' . $dept_id . ',' . $year . ',' . $pgm_id . ')');
        mysqli_next_result($this->db->conn_id);
        $proce_result = $call_proce->result_array();

        return $proce_result;
    }

    /**
     * Function is to fetch academic research details - section 5.7.1
     * @paramenters : dept id
     * @return      : array
     */
    public function fetch_academic_research($dept_id) {
        $call_proce = $this->db->query('CALL nba_t2pharm_c5_7_1_Academic_Research(' . $dept_id . ')');
        mysqli_next_result($this->db->conn_id);
        $proce_result = $call_proce->result_array();

        return $proce_result;
    }

    /**
     * Function is to fetch academic book published details - section 5.7.1
     * @paramenters : dept id
     * @return      : array
     */
    public function fetch_academic_book_published($dept_id) {
        $call_proce = $this->db->query('CALL nba_t2pharm_c5_7_1_Book_Published(' . $dept_id . ')');
        mysqli_next_result($this->db->conn_id);
        $proce_result = $call_proce->result_array();

        return $proce_result;
    }

    /**
     * Function is to fetch academic PHD details - section 5.7.1
     * @paramenters : dept id
     * @return      : array
     */
    public function fetch_academic_PHD_details($dept_id) {
        $call_proce = $this->db->query('CALL nba_t2pharm_c5_7_1_PHD_details(' . $dept_id . ')');
        mysqli_next_result($this->db->conn_id);
        $proce_result = $call_proce->result_array();

        return $proce_result;
    }

    /**
     * Function is to fetch sponsored research details - section 5.7.2
     * @paramenters : dept id
     * @return      : array
     */
    public function fetch_sponsored_research($dept_id) {
        $call_proce = $this->db->query('CALL nba_t2pharm_c5_7_2_Sponsored_Research(' . $dept_id . ')');
        mysqli_next_result($this->db->conn_id);
        $proce_result = $call_proce->result_array();

        return $proce_result;
    }

    /**
     * Function is to fetch consultancy projects details - section 5.7.3
     * @paramenters : dept id
     * @return      : array
     */
    public function fetch_consultancy_projects($dept_id) {
        $call_proce = $this->db->query('CALL nba_t2pharm_c5_7_3_Consultancy_Projects(' . $dept_id . ')');
        mysqli_next_result($this->db->conn_id);
        $proce_result = $call_proce->result_array();

        return $proce_result;
    }

    /*
     * Function to faculty performance appraisal details - section 5.8
     * @parameters  :   year
     * @return      :   array
     */

    public function fetch_faculty_performance_appraisal($year, $dept_id) {
        $call_proce = $this->db->query('CALL nba_t2pharm_c5_8_Faculty_Performance_Appraisal(' . $year . ',' . $dept_id . ')');
        mysqli_next_result($this->db->conn_id);
        $proce_result = $call_proce->result_array();

        return $proce_result;
    }

    /*
     * Function to faculty adjunct details - section 5.9
     * @parameters  : year ,department id
     * @return      : array
     */

    public function fetch_faculty_adjunct($year, $dept_id) {
        $call_proce = $this->db->query('CALL nba_t2pharm_c5_9_Faculty_Visiting(' . $year . ',' . $dept_id . ')');
        mysqli_next_result($this->db->conn_id);
        $proce_result = $call_proce->result_array();

        return $proce_result;
    }

}

/*
 * End of file t2pharm_c5_faculty_model.php 
 * Location: .nba_sar/pharm/tier2/criterion_5/t2pharm_c5_faculty_model.php
 */
?>
