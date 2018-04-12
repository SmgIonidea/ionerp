<?php

class Template extends CI_Model{
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * @method:listDepartmentOption
     * @param: string/array $column for custom column list
     * @pupose: list department list
     * @return array department data to display as dropdown box
     */
    function listDepartmentOptions($index,$column){     
        
        $this->db->select("$index,$column")->from('department');
        $query = $this->db->get();
        $res=$query->result_array();
        $list=array();
        $list[0]='Select Department';        
        foreach($res as $val){
            $list[$val[$index]]=$val[$column];
        }
        return $list;
    }
    
    /**
     * @method:listDepartmentOption
     * @param: string/array $column for custom column list
     * @pupose: list department list
     * @return array department data to display as dropdown box
     */
    function listProgramOptions($index,$column){     
        
        $this->db->select("$index,$column")->from('program');
        $query = $this->db->get();
        $res=$query->result_array();
        $list=array();
        $list[0]='Select Program';        
        foreach($res as $val){
            $list[$val[$index]]=$val[$column];
        }
        return $list;
    }
}

