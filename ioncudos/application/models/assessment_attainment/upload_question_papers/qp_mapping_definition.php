<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Qp_mapping_definition extends CI_Model{
    public function __construct() {
        parent::__construct();        
    }
    
    public function save_qp_mapping_definition($data){
        $map_id=$this->db->insert('qp_mapping_definition',$data);
        //echo $this->db->last_query();
        if($map_id){
            return $this->db->insert_id();
        }
        return false;
    }
    
}
