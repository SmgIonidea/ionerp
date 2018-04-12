<?php

class Po extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * @method:listDepartmentOption
     * @param: string/array $column for custom column list
     * @pupose: list department list
     * @return array department data to display as dropdown box
     */
    function listPo($index, $column, $condition = null, $msg = null) {

        $this->db->select("$index,$column")->from('po');
        if (is_array($condition)) {
            foreach ($condition as $col => $val) {
                $this->db->where("$col", "$val");
            }
        }
        $query = $this->db->get();
        $res = $query->result_array();
        $list = array();
        $list[0] = ($msg) ? $msg : 'Select PO';
        foreach ($res as $val) {
            $list[$val[$index]] = $val[$column];
        }
        return $list;
    }

    /**
     * @method:listDepartmentOption
     * @param: string/array $column for custom column list
     * @pupose: list department list
     * @return array department data to display as dropdown box
     */
    function listPoTitle($condition = null, $msg = null) {
       // $crclm_id_query = 'SELECT crclm_id  FROM po_extra_cocurricular_activity WHERE po_extca_id =  '
        $this->db->select("po_id,po_reference,po_statement")->from('po');
        if (is_array($condition)) {
            foreach ($condition as $col => $val) {
                $this->db->where("$col", "$val");
            }
        }
        $query = $this->db->get();
        $res = $query->result_array();
        $list = array();
        //$list[0] = array('ref'=>($msg) ? $msg : 'Select PO','title'=>($msg) ? $msg : 'Select PO');
        
        foreach ($res as $val) {
            $list[$val['po_id']] = array(
                'ref'=>$val['po_reference'],
                'title'=>$val['po_statement']
                );
        }
        
        
        return $list;
    }
    
    public function getcrclmid($activit_id){
        $crclm_id_query = 'SELECT crclm_id FROM po_extra_cocurricular_activity WHERE po_extca_id = "'.$activit_id.'" ';
        $crclm_id_data = $this->db->query($crclm_id_query);
        $crclm_id_res = $crclm_id_data->row_array();
        $crclm_id = $crclm_id_res['crclm_id'];
        return $crclm_id;
    }
    
    function listPOdata($crclm_id){
        $query = 'SELECT po_id,po_reference,po_statement FROM PO WHERE crclm_id = "'.$crclm_id.'" ';
        $query_data = $this->db->query($query);
        $query_res = $query_data->result_array();
        foreach($query_res as $res){
            $list[$res['po_id']]=array(
                'ref' => $res['po_reference'],
                'title' => $res['po_statement'],
            );
        }
        return $list;
    }

}
