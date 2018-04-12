<?php

/**
 * Description          :	Model logic for Criterion 3(3.1) (TIER 1) - Correlation between the courses and the PO and PSO.
 * Created              :	04-04-2017
 * Author               :	Shayista Mulla
 * Modification History :
 * Date                         Modified by                      Description
  --------------------------------------------------------------------------------------------------------------- */
?>
<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class T1ug_c3_cos_model extends CI_Model {

    /**
     * Function to fetch program articulation matrix - section 3.1 (TIER I)
     * @parameters  :   Curriculum id
     * @return      :   Array
     */
    public function course_po_mapping($crclm_id) {
        $val = $this->ion_auth->user()->row();
        $org_type = $val->org_name->org_type;
        $core_crs = 1; //core course

        if ($org_type == 'TIER-I') {
            $po_pso_flag = '0';
            //crclm_id, pso value (0, 1, null), basic/core (0, 1, null)
            $query = $this->db->query('call nba_t1ug_c3_1_CoursePOMapping(' . $crclm_id . ',' . $po_pso_flag . ',' . $core_crs . ')');
        } else {
            //incomplete
            $po_pso_flag = 1;
            //crclm_id, pso value (0, 1, null), basic/core (0, 1, null)
            $query = $this->db->query('call nba_t1ug_c3_1_CoursePOMapping(' . $crclm_id . ',' . $po_pso_flag . ',' . $core_crs . ')');
        }

        mysqli_next_result($this->db->conn_id);

        if ($query->num_rows() > 0) {
            $data['columns_list'] = $query->list_fields();
            $data['row_list'] = $query->result_array();
        } else {
            $data['columns_list'] = $data['row_list'] = array();
        }

        return $data;
    }

    /** done
     * Function to fetch course articulation matrix - section 3.1 (TIER I)
     * @parameter   : curriculumn id,course id's and pso falg
     * @return      : Array
     */
    public function clo_po_mapping($crclm_id = null, $crs_ids = null, $with_without_pso = 0) {
        $query = $this->db->query('call nba_t1ug_c3_1_COPOMapping("' . $crclm_id . '","' . $crs_ids . '",' . $with_without_pso . ')');
        mysqli_next_result($this->db->conn_id);
        if ($query->num_rows() > 0) {
            $data['columns_list'] = $query->list_fields();
            $data['row_list'] = $query->result_array();
        } else {
            $data['columns_list'] = $data['row_list'] = array();
        }
        return $data;
    }

}

/*
 * End of file t1ug_c3_cos_model.php
 * Location: .nba_sar/ug/tier1/criterion_3/t1ug_c3_cos_model.php 
 */
?>