<?php

/**
 * Description          :   Model logic for Criterion 3(3.1) (Pharmacy TIER 2) - Correlation between the courses and the PO and PSO.
 * Created              :   18-06-2017
 * Author               :   Shayista Mulla
 * Modification History :
 * Date                     Modified by                         Description
  --------------------------------------------------------------------------------------------------------------- */
?>
<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class T2pharm_c3_cos_model extends CI_Model {

    /**
     * Function is to fetch the co details.- section 3.1.1
     * @parameter   : curriculum id,nba sar view id,nba sar report id,filter status,course ids
     * @return      : Array
     */
    public function get_clo($crclm_id = null, $nba_sar_view_id = '', $nba_report_id = '', $with_filter = 1, $crs_list = array()) {
        if ($with_filter) {
            $co_list = $this->db->query('SELECT co.crs_title,co.crs_code,c.clo_code,clo_statement 
                                        FROM clo c 
                                        JOIN nba_filters n ON n.filter_value = c.crs_id AND n.filter_ids = "cos_checkbox_list" AND c.crclm_id = "' . $crclm_id . '" 
                                        AND n.nba_sar_view_id = "' . $nba_sar_view_id . '" AND n.nba_id = "' . $nba_report_id . '"  
                                        JOIN course co ON c.crs_id = co.crs_id 
                                        ORDER BY c.crs_id,c.clo_id');
        } else {
            $co_list = $this->db->SELECT('co.crs_title,co.crs_code,c.clo_code,clo_statement')
                    ->FROM('clo c')
                    ->JOIN('course co', 'c.crs_id = co.crs_id AND co.crclm_id = "' . $crclm_id . '" AND c.crclm_id = "' . $crclm_id . '"')
                    ->WHERE_IN('co.crs_id', $crs_list)
                    ->ORDER_BY('c.crs_id,c.clo_id')
                    ->get();
        }
        return $co_list->result_array();
    }

    /**
     * Function is to fetch the co to po mapping details. - section 3.1.2
     * @parameter   : nba sar view id as parameter and pso status 
     * @return      : Array
     */
    public function clo_po_mapping($param = null, $with_without_pso = 0) {
        $query = $this->db->query('call nba_t2dip_c3_1_2_clo_po_mapping("' . $param . '",' . $with_without_pso . ')');
        mysqli_next_result($this->db->conn_id);

        if ($query->num_rows() > 0) {
            $data['columns_list'] = $query->list_fields();
            $data['row_list'] = $query->result_array();
        } else {
            $data['columns_list'] = $data['row_list'] = array();
        }
        return $data;
    }

    /**
     * Function is to fetch the course to po mapping details. - section 3.1.3
     * @parameter   : curriculum id
     * @return      : Array
     */
    public function course_po_mapping($curriculum) {
        $query = $this->db->query('call nba_t2pharm_c3_1_3_CoursePOMapping("' . $curriculum . '")');
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
 * End of file t2pharm_c3_cos_model.php
 * Location: .nba_sar/pharm/tier2/criterion_3/t2pharm_c3_cos_model.php 
 */
?>

