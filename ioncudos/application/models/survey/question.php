<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Question extends CI_Model{
    
    function __construct() {
        parent::__construct();
    }
    
    /**
     * @method:add
     * @param: $data
     * @pupose: Save question type
     * @return integer last inserted id as confirmation
     */
        function add($data, $editId = null) {
            $this->db->set($data);
           // $data['question_type_name']= strtolower(str_replace(' ','',$data['question_type_name']));
				$data['question_type_name']= strtolower($data['question_type_name']);
            
            if ($editId != null) {                
                
                if(!$this->questionTypeAvail($editId,$data['question_type_name'])){
                    $this->db->where('question_type_id', $editId);
                    $this->db->update('su_question_types', $data);
                    return 1;                
                }else{
                    return 2;
                }                
            } else {                
                //$data['question_type_name']= strtolower(str_replace(' ','',$data['question_type_name']));
                $data['question_type_name']= strtolower($data['question_type_name']);
                if(!$this->questionTypeAvail(null,$data['question_type_name'])){
                    $this->db->insert('su_question_types', $data);
                    return 1;                
                }else{
                    return 2;
                }
            }
        }

        function listQuestionTypes(){
            $this->db->select('question_type_id,question_type_name,description,status');
            $this->db->from('su_question_types');
            $query=$this->db->get();
            return $query->result_array();
   	}
   	
	function getQuestionType($editId=null){
        if($editId!=null){        
            $this->db->select('question_type_id,question_type_name,description');
            $this->db->from('su_question_types');
            $this->db->where('question_type_id', $editId);
            $query=$this->db->get();
            return $query->result_array();
        }else{
            return 0;
        }
    }
    
    
    function changeQuestionTypeStatus($questionTypeId,$status){
    	$this->db->where('question_type_id', $questionTypeId);
        if($this->db->update('su_question_types', array('status'=>$status))){            
            return true;
        }else{            
            return false;
        }
    }
    function questionTypeAvail($questionTypeId=null,$questionTypeName){
        $where=array('LOWER(TRIM(REPLACE(question_type_name," ",""))) ='=>  strtolower(trim(str_replace(" ", "", $questionTypeName))));
        
        if($questionTypeId!=null){
            $where['question_type_id !=']=$questionTypeId;
        }
        $this->db->select('count(question_type_name) as name_avail')->from('su_question_types')->where($where);
        $res = $this->db->get()->result_array();                 
    return $res[0]['name_avail'];
    }
}
