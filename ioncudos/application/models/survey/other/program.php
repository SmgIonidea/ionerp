<?php

class Program extends CI_Model{
    public function __construct() {
        parent::__construct();
    }   
    
    /**
     * @method:listDepartmentOption
     * @param: string/array $column for custom column list
     * @pupose: list department list
     * @return array department data to display as dropdown box
     */
    function listProgramOptions($index,$column,$condition=null){     
        
        $this->db->select("$index,$column")->from('program')->where('status',1);
        if(is_array($condition)){
            foreach($condition as $col => $val){
                $this->db->where("$col","$val");
            }            
        }
        $query = $this->db->get();
        $res=$query->result_array();
        $list=array();
        $list[0]='Select Program';        
        foreach($res as $val){
            $list[$val[$index]]=$val[$column];
        }
        return $list;
    }
    
    /**
     * @method:programById
     * @param: program id
     * @pupose: To fetch program title
     * @return program title
     */
    function programById($id){
        //echo $id;
        $this->db->select('pgm_title')->from('program');
        $this->db->where('pgm_id',$id);
        $res = $this->db->get()->result_array();
        return $res[0]['pgm_title'];
    }
}

