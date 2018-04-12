<?php

/**
 * Description          :   Model logic for Criterion 1 (TIER 1) - vision mission.
 * Created              :   04-06-2015
 * Author               :   Shayista Mulla      
 * Modification History :
 * Date                     Modified by                     Description
  --------------------------------------------------------------------------------------------------------------- */
?>

<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class T1ug_c1_vision_mission_model extends CI_Model {

    /**
     * Function is to fetch the details of the organisation from the organization table. - Criterion 1.1
     * @parameter   : organization id
     * @return      : organization name, description 
     */
    public function get_organisation_by_id($organization_id = null) {
        return $this->db
                        ->select('vision')
                        ->select('mission')
                        ->where('org_id', $organization_id)
                        ->get('organisation')
                        ->result_array();
    }

    /**
     * Function is to fetch the details of the department from the department table. - Criterion 1.1
     * @parameter   : department id
     * @return      : department vision and mission 
     */
    public function get_dept_by_id($dept_id) {
        return $this->db
                        ->select('dept_vision')
                        ->select('dept_mission')
                        ->where('dept_id', $dept_id)
                        ->get('dept_mission_map')
                        ->result_array();
    }

    /**
     * Function to fetch peo details - Criterion 1.2
     * @parameters  : curriculum id
     * @return      : array
     */
    public function get_peo($curriculum) {
        return $this->db->select('peo_id, peo_reference,peo_statement')
                        ->where('crclm_id', $curriculum)
                        ->get('peo')->result_array();
    }

    /**
     * Function to fetch peo to me mapping details - Criterion 1.5
     * @parameters  :
     * @return      : array
     */
    public function get_map_peo_me($curriculum_id, $dept_id) {
        //mission element statements
        $me_query = 'SELECT dept_me_map_id, dept_me
               FROM dept_mission_element_map 
               WHERE dept_id = "' . $dept_id . '" ';
        $me_list_data = $this->db->query($me_query);
        $me_list = $me_list_data->result_array();
        $data['me_list'] = $me_list;

        //peo to me mapping
        $call_proce = $this->db->query('CALL nba_t1ug_c1_5_PEO_ME_Mapping(' . $dept_id . ', ' . $curriculum_id . ')');
        mysqli_next_result($this->db->conn_id);
        $data['mapped_peo_me'] = $call_proce->result_array();
        $data['column_list'] = $call_proce->list_fields();
        return $data;
    }

}

/*
 * End of file t1ug_c1_vision_mission_model.php
 * Location: .nba_sar/ug/tier1/criterion_1/t1ug_c1_vision_mission_model.php 
 */
?>