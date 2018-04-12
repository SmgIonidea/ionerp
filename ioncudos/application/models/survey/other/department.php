<?php

class Department extends CI_Model{
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * @method:listDepartmentOption
     * @param: string/array $column for custom column list
     * @pupose: list department list
     * @return array department data to display as dropdown box
     */
    function listDepartmentOptions($index,$column,$condition=null){     
        $logged_in_user_dept_id = $this->ion_auth->user()->row()->user_dept_id;
        $this->db->select("$index,$column")->from('department')->where('status',1);
        //Added by Shivaraj B
        if(!$this->ion_auth->is_admin()){
            $this->db->where('dept_id',$logged_in_user_dept_id);
        }
        if(is_array($condition)){
            foreach($condition as $col => $val){
                $this->db->where("$col","$val");
            }            
        }
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
     * @method:departmentById
     * @param: department id
     * @pupose: To fetch department name
     * @return department name
     */
    function departmentById($id){
        //echo $id;
        $this->db->select('dept_name')->from('department');
        $this->db->where('dept_id',$id);
        $res = $this->db->get()->result_array();
        return $res[0]['dept_name'];
    }
}

