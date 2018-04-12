<?php

class Course extends CI_Model{
    public function __construct() {
        parent::__construct();
    }   
    
    /**
     * @method:listCourseOptions
     * @param: string/array $column for custom column list
     * @pupose: list course list
     * @return array course data to display as dropdown box
     */
    function listCourseOptions($index,$column,$condition=null,$condition_two=null){     
 		$logged_user_id =  $this->ion_auth->user()->row()->id;
		$query = $this->db->query('SELECT * FROM map_courseto_course_instructor m where course_instructor_id = "'.$logged_user_id.'"');
		$result = $query->result_array();
        if(empty($result)){ 
			$this->db->select("course.$index,course.$column")->from('course')->join("course_clo_owner", "course_clo_owner.$index = course.$index")->where('status',1);
			if(is_array($condition)){
				foreach($condition as $col => $val){
					$this->db->where("course.$col","$val");
				}            
			}
			if(is_array($condition_two)){
				foreach($condition_two as $col_two => $val_two){
					$this->db->where("course_clo_owner.$col_two","$val_two");
				}            
			}
	 	}else{
			$this->db->select("course.$index,course.$column")->from('course')->join("map_courseto_course_instructor", "map_courseto_course_instructor.crs_id = course.crs_id");
			if(is_array($condition)){
				foreach($condition as $col => $val){
					$this->db->where("course.$col","$val");
				}            
			}
			if(is_array($condition_two)){
				foreach($condition_two as $col_two => $val_two){
					$this->db->where("map_courseto_course_instructor.course_instructor_id","$val_two");
				}            
			}
		} 
		$this->db->order_by("course.$column","asc");
        $query = $this->db->get();
        $res=$query->result_array();
        $list=array();
        $list[0]='Select Course';        
        foreach($res as $val){
            $list[$val[$index]]=$val[$column];
        }
        return $list;
    }     
	
	/**
     * @method:listSectionOptions
     * @param: string/array $column for custom column list
     * @pupose: list course list
     * @return array course data to display as dropdown box
     */
    function listSectionOptions($index,$column,$condition=null,$condition_two=null){     	
        $this->db->select("master_type_details.$index, master_type_details.$column")->from('map_courseto_course_instructor')->join("master_type_details", "master_type_details.mt_details_id = map_courseto_course_instructor.section_id");
        if(is_array($condition)){
            foreach($condition as $col => $val){
                $this->db->where("map_courseto_course_instructor.$col","$val");
            }            
        }
/* 		if(is_array($condition_two)){
            foreach($condition_two as $col_two => $val_two){
                $this->db->where("course_clo_owner.$col_two","$val_two");
            }            
        } */
		$this->db->order_by("master_type_details.$column","asc");
        $query = $this->db->get();
        $res=$query->result_array();
        $list=array();
        $list[0]='Select Section';
		$list['']='ALL';         
        foreach($res as $val){
            $list[$val[$index]]=$val[$column];
        }
        return $list;
    }    

	/**
     * @method:listtermOptions
     * @param: string/array $column for custom column list
     * @pupose: list course list
     * @return array course data to display as dropdown box
     */
    function listTermOptions($index,$column,$condition=null,$condition_two=null){     
        
        $this->db->select("crclm_terms.$index,crclm_terms.$column")->from('crclm_terms');
        if(is_array($condition)){
            foreach($condition as $col => $val){
                $this->db->where("crclm_terms.$col","$val");
            }            
        }

		$this->db->order_by("crclm_terms.$column","asc");
        $query = $this->db->get();
        $res=$query->result_array();
        $list=array();
        $list[0]='Select Term';        
        foreach($res as $val){
            $list[$val[$index]]=$val[$column];
        }
        return $list;
    }     
	
	function listTermOptions_co($index,$column,$condition=null,$condition_two=null){     
        
        $this->db->select("crclm_terms.$index,crclm_terms.$column")->from('crclm_terms')->join("map_courseto_course_instructor", "map_courseto_course_instructor.crclm_term_id = crclm_terms.crclm_term_id");
        if(is_array($condition)){
            foreach($condition as $col => $val){
                $this->db->where("crclm_terms.$col","$val");
            }            
        }
		if(is_array($condition_two)){
            foreach($condition_two as $col_two => $val_two){
                $this->db->where("map_courseto_course_instructor.$col_two","$val_two");
            }            
        }
		$this->db->order_by("crclm_terms.$column","asc");
        $query = $this->db->get();
        $res=$query->result_array();
        $list=array();
        $list[0]='Select Term';        
        foreach($res as $val){
            $list[$val[$index]]=$val[$column];
        }
        return $list;
    } 
    
    /**
     * @method:courseById
     * @param: course id
     * @pupose: To fetch course title
     * @return course title
     */
    function courseById($id){
        //echo $id;
        $this->db->select('crs_title')->from('course');
        $this->db->where('crs_id',$id);
        $res = $this->db->get()->result_array();
        return $res[0]['crs_title'];
    }
}