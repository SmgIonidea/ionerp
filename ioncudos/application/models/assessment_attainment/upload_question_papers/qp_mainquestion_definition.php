<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Qp_mainquestion_definition extends CI_Model{
    public function __construct() {
        parent::__construct();        
    }
    
    public function save_qp_mainquestion_definition($data){
        $def_id=$this->db->insert('qp_mainquestion_definition',$data);
        //echo $this->db->last_query();
        if($def_id){
            return $this->db->insert_id();
        }
        return false;
    }
}
