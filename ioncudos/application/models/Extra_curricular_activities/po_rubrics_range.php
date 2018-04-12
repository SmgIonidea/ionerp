<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Po_rubrics_range extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function fetch_rubrics_range($activity_id, $column = null) {
        $cols = 'rubrics_range_id, po_extca_id, criteria_range_name, criteria_range, created_by, modified_by, created_date, modified_date';
        if ($column) {
            $cols = $column;
        }
        if ($activity_id) {
            return $this->db->select($cols)->from('po_rubrics_range')
                            ->where('po_extca_id', $activity_id)
                            ->group_by('criteria_range')
                            ->get()->result_array();
        }
        return false;
    }

    public function save_range_data($post_data) {
        if ($this->db->insert('po_rubrics_range', $post_data)) {
            return $this->db->insert_id();
        }
        return false;
    }

    public function delete_range($condition) {
        $flag = FALSE;
        if ($condition) {
            if (is_array($condition)) {
                foreach ($condition as $col => $val) {
                    $this->db->where($col, "$val");
                }
                $flag = $this->db->delete('po_rubrics_range');
            } else {
                $this->db->where($condition, null, false);
                $flag = $this->db->delete('po_rubrics_range');
            }
            //echo $this->db->last_query();
            return $flag;
        }
        return $flag;
    }

}
