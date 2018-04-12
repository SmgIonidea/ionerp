
<?php

/**
 * Description	:	To Fetch  map_level_weightage of Faculty

 * Created		:	01/09/2015

 * Author		:	 Bhagyalaxmi Shivapuji

 * Modification History:
 *   Date                Modified By                         Description
 * 
  ------------------------------------------------------------------------------------------ */
?> 
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Map_level_weightage_model extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    /* Function is used to fetch the map_level_weightage table details.
     * @param - 
     * @returns- a array of values of the map_level_weightage details.
     */

    public function map_level_values() {

        $query = "select * from map_level_weightage";
        $query_re = $this->db->query($query);
        $data['value'] = $query_re->result_array();

        $query1 = "select SUM(map_level_weightage) from map_level_weightage where status=1";
        $query_re1 = $this->db->query($query1);
        $data1 = $query_re1->result_array();
        $data['total'] = $data1[0]['SUM(map_level_weightage)'];
        return $data;
    }

    /**
     * Function to Update map_level_weightage details
     * @param - map_level_name,priority,status,weightage,total
     * @return- total sum of weightage
     * */
    public function update_map($map_level_name, $priority, $status, $weightage, $tot) {

        $query = "select mlw_id from map_level_weightage";
        $query_re = $this->db->query($query);
        $data = $query_re->result_array();

        for ($i = 0; $i < 3; $i++) {
            $weightage_id = isset($weightage[$i]) ? intval($weightage[$i]) : 0.00;
            $update_map = 'UPDATE map_level_weightage SET map_level_name_alias="' . $map_level_name[$i] . '" ,map_level_short_form="' . $priority[$i] . '", status="' . $status[$i] . '", map_level_weightage="' . $weightage_id . '", modified_by="' . $this->ion_auth->user()->row()->id . '", modified_date="' . date('Y-m-d') . '" where mlw_id="' . $data[$i]['mlw_id'] . '"';
            $re['val'] = $this->db->query($update_map);
            $query1 = "select SUM(map_level_weightage) from map_level_weightage where status=1";
            $query_re1 = $this->db->query($query1);
            $data1 = $query_re1->result_array();
            $re['total'] = $data1[0]['SUM(map_level_weightage)'];
        }

        //code is commented for future use    
        /* $query = "select mlw_id from map_level_weightage";
          $query_re = $this->db->query($query);
          $data = $query_re->result_array();
          $tt;
          $query1 = "select SUM(map_level_weightage) from map_level_weightage where status=1";
          $query_re1 = $this->db->query($query1);
          $data1 = $query_re1->result_array();
          $re['total'] = $data1[0]['SUM(map_level_weightage)'];

          if ($tot == 100) {
          for ($i = 0; $i < 3; $i++) {
          $weightage_id = isset($weightage[$i])? intval($weightage[$i]) : 0.00;
          $update_map = 'UPDATE map_level_weightage SET map_level_name_alias="' . $map_level_name[$i] . '" ,map_level_short_form="' . $priority[$i] . '", status="' . $status[$i] . '", map_level_weightage="' . $weightage_id . '", modified_by="' . $this->ion_auth->user()->row()->id . '", modified_date="' . date('Y-m-d') . '" where mlw_id="' . $data[$i]['mlw_id'] . '"';
          $re['val'] = $this->db->query($update_map);
          $query1 = "select SUM(map_level_weightage) from map_level_weightage where status=1";
          $query_re1 = $this->db->query($query1);
          $data1 = $query_re1->result_array();
          $re['total'] = $data1[0]['SUM(map_level_weightage)'];
          }
          } */
        return $re;
    }

}
