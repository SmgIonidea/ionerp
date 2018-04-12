<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Map_po_rubrics_criteria_to_po extends CI_Model{
    public function __construct(){
        parent::__construct();
    }
    public function fetch_criteria_to_po($column = null,$conditions) {
        $cols = 'mprctp_id, po_extca_id, rubrics_criteria_id, po_id, created_by, modified_by, created_date, modified_date';
        if ($column) {
            $cols = $column;
        }
        $qry= $this->db->select($cols)->from('map_po_rubrics_criteria_to_po');
        if($conditions){
            foreach($conditions as $col=>$val){
                $qry->where($col, "$val");
            }
        }
        $res=$qry->get()->result_array();
        return $res;
    }
    public function save_criteria_to_po_data($post_data){
        if($this->db->insert('map_po_rubrics_criteria_to_po',$post_data)){
            return $this->db->insert_id();
        }
    return false;
    }
    
    public function delete_po($condition){
        $cond = array(
            'po_id' => $condition,
        );
        $flag=FALSE;
        if($condition){
            if(is_array($cond)){
                foreach($cond as $col=>$val){
                    $this->db->where($col, "$val");
                }
                $flag=$this->db->delete('map_po_rubrics_criteria_to_po');
            }else{
                $this->db->where($cond,null,false);
                $flag=$this->db->delete('map_po_rubrics_criteria_to_po');
            }     
            //echo $this->db->last_query();
            return $flag;
        }
        return $flag;
    }
    
}

