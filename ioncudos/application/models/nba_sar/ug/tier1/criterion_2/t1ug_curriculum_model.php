
<?php

/**
 * Description          :	Model logic for Criterion 2 (TIER 1) - Program curriculum.
 * Created              :	3-8-2015
 * Author               :       
 * Modification History :
 * Date                         Modified by                      Description
 * 3-8-2015                     Jevi V. G.              Added file headers, public function headers, indentations & comments.
 * 24-4-2016                    Arihant Prasad          Rework, indentation and code cleanup
  --------------------------------------------------------------------------------------------------------------- */
?><?php

defined('BASEPATH') OR exit('No direct script access allowed');

class t1ug_curriculum_model extends CI_Model {

        /**
         * Function is to fetch the details of curriculum structure table.
         * @parameter   : curriculum id
         * @return      : return curriculum course structure 
         */
        public function get_curriculum_structure($crclm_id = null) {
                $result = $this->db->query('call nba_t1ug_c2_1_2_CurriculumStructure("' . $crclm_id . '")');
                mysqli_next_result($this->db->conn_id);
                $result_data = $result->result_array();

                return $result_data;
        }

        /**
         * Function is to fetch the details of curriculumn components table.
         * @parameter   : curriculum id
         * @return      : return components details 
         */
        public function get_curriculum_components($crclm_id = null) {
                $result = $this->db->query('call nba_t1ug_c2_1_3_CurriculumComponents("' . $crclm_id . '")');
                mysqli_next_result($this->db->conn_id);
                $result_data = $result->result_array();

                return $result_data;
        }

}

/*
 * End of file t1ug_curriculum_model.php
 * Location: .nba_sar/ug/tier1/criterion_2/t1ug_curriculum_model.php 
 */
?>

