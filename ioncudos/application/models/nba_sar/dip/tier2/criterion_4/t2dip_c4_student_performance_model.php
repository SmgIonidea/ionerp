<?php

/**
 * Description          :   Model logic for Criterion 4 (TIER 2)- Student Performance
 * Created              :   06-06-2017
 * Author               :   Shayista Mulla 
 * Modification History : 
 * Date                     Modified By			Description		
  ------------------------------------------------------------------------------------- */
?>

<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class T2dip_c4_student_performance_model extends CI_Model {

    /**
     * Function to fetch start year of curriculum - section 4
     * @paramenters : curriculum id
     * @return      : array
     */
    public function fetch_curriculum_year($curriculum_id = NULL) {
        $query = 'SELECT start_year 
                    FROM curriculum 
                    WHERE crclm_id =' . $curriculum_id;
        $result = $this->db->query($query);
        $result = $result->row_array();
        return $result;
    }

    /**
     * Function to fetch student performance details - section 4
     * @paramenters : pgm id and crclm start year
     * @return      : Array
     */
    public function fetch_student_performance($pgm_id, $year) {
        $result = $this->db->query('CALL nba_t2dip_c4_Student_Intake_Information(' . $pgm_id . ', ' . $year . ')');
        mysqli_next_result($this->db->conn_id);
        $result = $result->result_array();
        return $result;
    }

    /**
     * Function to fetch student performance past year for CAY, CAYm1, CAYm2 - section 4
     * @paramenters : curriculum id
     * @return      : Array
     */
    public function fetch_curriculum_past_year($crclm_id) {
        $query = 'SELECT start_year,
                    CONCAT(start_year, "-", DATE_FORMAT(DATE_SUB(DATE_FORMAT(CONCAT(start_year ,"-01-10"), "%Y-%m-%d"), INTERVAL 1 YEAR), "%y")) AS "CAY",
                    CONCAT(DATE_FORMAT(DATE_SUB(DATE_FORMAT(CONCAT(start_year ,"-01-10"), "%Y-%m-%d"), INTERVAL 1 YEAR), "%Y"), "-", DATE_FORMAT(DATE_SUB(DATE_FORMAT(CONCAT(start_year ,"-01-10"), "%Y-%m-%d"), INTERVAL 2 YEAR), "%y")) AS "CAYm1",
                    CONCAT(DATE_FORMAT(DATE_SUB(DATE_FORMAT(CONCAT(start_year ,"-01-10"), "%Y-%m-%d"), INTERVAL 2 YEAR), "%Y"), "-", DATE_FORMAT(DATE_SUB(DATE_FORMAT(CONCAT(start_year ,"-01-10"), "%Y-%m-%d"), INTERVAL 3 YEAR), "%y")) AS "CAYm2"
                    FROM curriculum c
                    WHERE c.crclm_id = ' . $crclm_id;
        $result = $this->db->query($query);
        $result = $result->result_array();
        return $result;
    }

    /**
     * Function to fetch student performance for report 4 listing students without backlog - section 4
     * @paramenters : pgm id and year
     * @return      : Array
     */
    public function fetch_student_without_backlogs($pgm_id, $year) {
        $result = $this->db->query('CALL nba_t2dip_c4_Without_backlogs(' . $pgm_id . ', ' . $year . ')');
        mysqli_next_result($this->db->conn_id);
        $result = $result->result_array();
        return $result;
    }

    /**
     * Function to fetch student performance for report 4 listing students graduated succesfully - section 4
     * @paramenters : pgm id , start year
     * @return      : Array
     */
    public function fetch_students_graduated($pgm_id, $year) {
        $result = $this->db->query('CALL nba_t2dip_c4_Graduated_students(' . $pgm_id . ', ' . $year . ')');
        mysqli_next_result($this->db->conn_id);
        $result = $result->result_array();
        return $result;
    }

    /**
     * Function to fetch the enrollment ratio - section 4.1
     * @paramenters : curriculum id
     * @return      : array
     */
    public function fetch_enrolment_ratio($crclm_id) {
        $query = 'SELECT pgm_id, start_year 
                  FROM curriculum 
                  WHERE crclm_id = "' . $crclm_id . '" ';
        $result_data = $this->db->query($query);
        $result = $result_data->row_array();

        $call_proce = $this->db->query('CALL nba_t2dip_c4_1_EnrolmentRatio(' . $result['pgm_id'] . ',' . $result['start_year'] . ')');
        mysqli_next_result($this->db->conn_id);
        $proce_result = $call_proce->result_array();
        return $proce_result;
    }

    /**
     * Function to fetch the success rate details - section 4.2.1
     * @paramenters : curriculum id
     * @return      : array
     */
    public function fetch_success_rate($crclm_id) {
        $query = 'SELECT pgm_id, start_year 
                  FROM curriculum 
                  WHERE crclm_id = "' . $crclm_id . '" ';
        $result_data = $this->db->query($query);
        $result = $result_data->row_array();

        $call_proce = $this->db->query('CALL nba_t2dip_c4_2_1_SRWithoutBacklog(' . $result['pgm_id'] . ',' . $result['start_year'] . ')');
        mysqli_next_result($this->db->conn_id);
        $proce_result = $call_proce->result_array();

        return $proce_result;
    }

    /**
     * Function to fetch the success rate stipulated details - section 4.2.2
     * @paramenters : curriculum id
     * @return      : array
     */
    public function fetch_success_rate_in_stipulated($crclm_id) {
        $query = 'SELECT pgm_id, start_year 
                  FROM curriculum 
                  WHERE crclm_id = "' . $crclm_id . '" ';
        $result_data = $this->db->query($query);
        $result = $result_data->row_array();

        $call_proce = $this->db->query('CALL nba_t2dip_c4_2_2_SRInPeriod(' . $result['pgm_id'] . ',' . $result['start_year'] . ')');
        mysqli_next_result($this->db->conn_id);
        $proce_result = $call_proce->result_array();

        return $proce_result;
    }

    /**
     * Function to fetch the academic performance in third year - section 4.3
     * @paramenters : curriculum id
     * @return      : array
     */
    public function fetch_academic_performance($crclm_id, $year) {
        $query = 'SELECT pgm_id, start_year 
                    FROM curriculum 
                    WHERE crclm_id = "' . $crclm_id . '" ';
        $result_data = $this->db->query($query);
        $result = $result_data->row_array();

        $call_proce = $this->db->query('CALL nba_t2dip_c4_3_AcademicPerformance(' . $result['pgm_id'] . ',' . $result['start_year'] . ',' . $year . ')');
        mysqli_next_result($this->db->conn_id);
        $proce_result = $call_proce->result_array();

        return $proce_result;
    }

    /**
     * Function to fetch the Placement,Higher Studies and Entrepreneurship details - section 4.6
     * @paramenters : curriculum id
     * @return      : array
     */
    public function fetch_placement_higher_studies($crclm_id) {
        $query = 'SELECT pgm_id, start_year 
                  FROM curriculum 
                  WHERE crclm_id = "' . $crclm_id . '" ';
        $result_data = $this->db->query($query);
        $result = $result_data->row_array();

        $call_proce = $this->db->query('CALL nba_t2dip_c4_6_Placement(' . $result['pgm_id'] . ',' . $result['start_year'] . ')');
        mysqli_next_result($this->db->conn_id);
        $proce_result = $call_proce->result_array();

        return $proce_result;
    }

    /**
     * Function to fetch Professional societies details - section 4.7.1
     * @paramenters : dept id
     * @return      : array
     */
    public function fetch_professional_societies($dept_id) {
        $call_proce = $this->db->query('CALL nba_t2dip_c4_7_1_Professional_Societies(' . $dept_id . ')');
        mysqli_next_result($this->db->conn_id);
        $proce_result = $call_proce->result_array();

        return $proce_result;
    }

    /**
     * Function to fetch publications details - section 4.7.2
     * @paramenters : department id
     * @return      : Array 
     */
    public function fetch_publications($dept_id) {
        $call_proce = $this->db->query('CALL nba_t2dip_c4_7_2_Publication(' . $dept_id . ')');
        mysqli_next_result($this->db->conn_id);
        $proce_result = $call_proce->result_array();

        return $proce_result;
    }

}

/*
 * End of file t2dip_c4_student_performance_model.php 
 * Location: .nba_sar/dip/tier2/criterion_4/t2dip_c4_student_performance_model.php
 */
?>