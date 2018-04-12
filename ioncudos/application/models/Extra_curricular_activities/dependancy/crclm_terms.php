<?php

class Crclm_terms extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * @method:listDepartmentOption
     * @param: string/array $column for custom column list
     * @pupose: list department list
     * @return array department data to display as dropdown box
     */
    function listCrclmTerms($index, $column, $condition = null, $msg = null) {

        $this->db->select("$index,$column")->from('crclm_terms');
        if (is_array($condition)) {
            foreach ($condition as $col => $val) {
                $this->db->where("$col", "$val");
            }
        }
        $query = $this->db->get();
        $res = $query->result_array();
        $list = array();
        $list[0] = ($msg)?$msg:'Select Term';

        foreach ($res as $val) {
            $list[$val[$index]] = $val[$column];
        }
        return $list;
    }

}
