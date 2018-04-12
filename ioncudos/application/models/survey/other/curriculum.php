<?php

class Curriculum extends CI_Model{
    public function __construct() {
        parent::__construct();
    }
    
    function listCurriculumOptions($index,$column,$condition=null){     
        $logged_in_user_dept_id = $this->ion_auth->user()->row()->user_dept_id;
  
        if($index==null){
            $index='crclm_id';
        }
        if($column==null){
            $column='crclm_name';
        }        
        $this->db->select("$index,$column")->from('curriculum')->where('curriculum.status',1);
	/* 	$this->db->join('program', 'program.pgm_id=curriculum.pgm_id');
		$this->db->where("program.dept_id",$logged_in_user_dept_id); */
		//$this->db->where('program.pgm_id',2);
        if(is_array($condition)){		
            foreach($condition as $ky => $val){			
                $this->db->where("$ky","$val");
            }
        }
        if($this->ion_auth->is_admin()){
            
        }else{
          
			$this->db->join('program', 'program.pgm_id=curriculum.pgm_id');
            $this->db->where("program.dept_id",$logged_in_user_dept_id);
            $this->db->where('curriculum.status',1);
        }
        $query = $this->db->get();
        $res=$query->result_array();
        $list=array();
        $list[0]='Select Curriculum';
        foreach($res as $val){
            $list[$val[$index]]=$val[$column];
        }
        return $list;
    }
    
    function curriculumById($id){
        //echo $id;
        $this->db->select('crclm_name')->from('curriculum');
        $this->db->where('crclm_id',$id);
        $res = $this->db->get()->result_array();
        return $res[0]['crclm_name'];
    }
}
?>

