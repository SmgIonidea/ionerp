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
    
  
    function listProgramOptions($index,$column,$condition=null,$msg=null){     
        $base_dept_id = $this->ion_auth->user()->row()->user_dept_id;
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
    		redirect('login', 'refresh');
    	}elseif($this->ion_auth->is_admin()){
            $this->db->select("$index,$column")->from('program')->where('status',1);
        }elseif($this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')){
            $this->db->select("$index,$column")->from('program')->where('status',1)->where('dept_id',$base_dept_id);
        }else{
            $this->db->select("$index,$column")->from('program')->where('status',1)->where('dept_id',$base_dept_id);
        }
       
        if(is_array($condition)){
            foreach($condition as $col => $val){
                $this->db->where("$col","$val");
            }            
        }
        $query = $this->db->get();
        $res=$query->result_array();
        $list=array();
        $list[0] = ($msg)?$msg:'Select Program';          
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

