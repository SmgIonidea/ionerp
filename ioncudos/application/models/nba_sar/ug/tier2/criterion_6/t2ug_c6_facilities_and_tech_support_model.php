<?php

/**
 * Description          :   Model logic for NBA SAR report - Criterion 6 (TIER 2) - Facilities and Technical Support.
 * Created              :   24-12-2016
 * Author               :   Shayista Mulla
 * Modification History :
 * Date                     Modified By                     Description
 *
  ---------------------------------------------------------------------------------- */
?>

<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class T2ug_c6_facilities_and_tech_support_model extends CI_Model {

    /**
     * Function to fetch lab adequate details from facilities_adequate table - section 6.1
     * @parameters  : department id
     * @return      : lab adequate details
     */
    public function fetch_adequate_data($dept_id) {
        $query = $this->db->query('SELECT lab_name, no_of_stud, equipment_name, utilization_status,
                                    technical_staff_name, designation, qualification
                                    FROM facilities_adequate 
                                    WHERE dept_id = "' . $dept_id . '"');
        return $query->result_array();
    }

    /**
     * Function to fetch lab safety measures - section 6.5
     * @parameters  : department id
     * @return      : lab safety measure details
     */
    public function safety_measures($dept_id) {
        $query = $this->db->query('SELECT lab_name, safety_measures
                                   FROM safety_measures_in_laboratories 
                                   WHERE dept_id = "' . $dept_id . '"');
        return $query->result_array();
    }

}

/*
 * End of file t2ug_c6_facilities_and_tech_support_model.php 
 * Location: .nba_sar/t2ug_c6_facilities_and_tech_support_model.php
 */
?>