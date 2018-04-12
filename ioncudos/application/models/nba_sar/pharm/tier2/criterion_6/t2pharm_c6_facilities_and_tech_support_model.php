<?php

/**
 * Description          :   Model logic for NBA SAR report - Criterion 6 (Pharmacy TIER 2) - Facilities and Technical Support.
 * Created              :   28-06-2017
 * Author               :   Shayista Mulla
 * Modification History :
 * Date                     Modified By                     Description
  ----------------------------------------------------------------------------------- */
?>

<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class T2pharm_c6_facilities_and_tech_support_model extends CI_Model {

    /**
     * Function to fetch laboratory details - section 6.1
     * @parameters  : department id
     * @return      : lab laboratory details
     */
    public function fetch_labratory_data($dept_id) {
        $query = $this->db->query('SELECT *
                                    FROM laboratory 
                                    WHERE dept_id = "' . $dept_id . '"');
        return $query->result_array();
    }

    /**
     * Function to fetch equipment in instrument room - section 6.3
     * @parameters  : department id
     * @return      : equipment in instrument room details
     */
    public function fetch_instrument_room_data($dept_id) {
        $query = $this->db->query('SELECT *
                                    FROM equipment 
                                    WHERE dept_id = "' . $dept_id . '" AND equipment_at="Instrument Room"');
        return $query->result_array();
    }

    /**
     * Function to fetch equipment in Machine Room details - section 6.3
     * @parameters  : department id
     * @return      : equipment in Machine Room details
     */
    public function fetch_machine_room_data($dept_id) {
        $query = $this->db->query('SELECT *
                                    FROM equipment 
                                    WHERE dept_id = "' . $dept_id . '" AND equipment_at="Machine Room"');
        return $query->result_array();
    }

    /**
     * Function to fetch non teaching support details - section 6.6
     * @parameters  : department id
     * @return      : non teaching support details
     */
    public function fetch_non_teaching_support_data($dept_id) {
        $query = $this->db->query('SELECT *
                                    FROM non_teaching_support 
                                    WHERE dept_id = "' . $dept_id . '"');
        return $query->result_array();
    }

}

/*
 * End of file t2ug_c6_facilities_and_tech_support_model.php 
 * Location: .nba_sar/pharm/tier2/criterion_6/t2ug_c6_facilities_and_tech_support_model.php
 */
?>