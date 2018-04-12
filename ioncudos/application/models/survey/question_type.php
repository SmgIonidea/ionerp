<?php

class Question_Type extends CI_Model{
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * @method:listQuestionType
     * @param: string/array $column for custom column list
     * @pupose: list department list
     * @return array department data to display as dropdown box
     */
    function listQuestionType($index,$column,$condition=null){     
	
        $this->db->select("$index,$column")->from('su_question_types');
        if(is_array($condition)){
            foreach($condition as $col => $val){
                $this->db->where("$col","$val");
            }            
        }
        $query = $this->db->get();
        $res=$query->result_array();
        $list=array();
        $list[0]='Select Question Type';        
        foreach($res as $val){
            $list[$val[$index]]=$val[$column];
        }
		
        return $list;
    }    
    
}

