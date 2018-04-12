<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Answer_Template extends CI_Model{
    
    function __construct() {
        parent::__construct();
    }
    
    /**
     * @method:add
     * @param: $data
     * @pupose: Save question type
     * @return integer last inserted id as confirmation
     */
    function add($data,$editId = null){
        $this->db->set($data);
        if($editId!=null){
            if(!$this->isAnsTempNameAvail($data['name'],$editId)){
                $this->db->where('answer_template_id', $editId);
                $this->db->update('su_answer_templates',$data);
                return 1;
            }else{                
                return 'Template name already exists.';
            }
        }else{
            if(!$this->isAnsTempNameAvail($data['name'])){
                $this->db->insert('su_answer_templates',$data);
                return 1;
            }else{
                return 'Template name already exists.';
            }
        }
    }
    
    public function addAnswerTemplate($data){
        if (!$this->isAnsTempNameAvail($data['su_answer_templates']['name'])) {
           //insert answer templates
            $this->db->insert('su_answer_templates',$data['su_answer_templates']);
            $templateId=  $this->db->insert_id();
            foreach($data['su_answer_options'] as $ky=>$val){
                $data['su_answer_options'][$ky]['answer_template_id']=$templateId;
                $this->db->insert('su_answer_options',$data['su_answer_options'][$ky]);            
            }            
            return 1;
        } else {
            return 'Template name already exists.';
        }
    }
    
    
    function getAnsTemplate($id = null,$conditions = null){
        $this->db->select(array('answer_template_id','name','status','feedbk_flag'));
        $this->db->from('su_answer_templates');//->where('status',1);
        if($id!=null){
            $this->db->where('answer_template_id', $id);
        }
        if(is_array($conditions)){
            foreach($conditions as $key=>$val){
                $this->db->where("$key","$val");
            }
        }
       
        $ansTemp=$this->db->get()->result_array();
        return $ansTemp;
    }
    function getAnsTemplateList($id = null,$conditions = null){
        $this->db->select(array('answer_template_id','name','status','feedbk_flag'));
        $this->db->from('su_answer_templates');
        if($id!=null){
            $this->db->where('answer_template_id', $id);
        }
        if(is_array($conditions)){
            foreach($conditions as $key=>$val){
                $this->db->where("$key","$val");
            }
        }
       
        $ansTemp=$this->db->get()->result_array();
        return $ansTemp;
    }
    function changeAnswerTemplateStatus($answerTemplateId,$status){
        $this->db->where('answer_template_id', $answerTemplateId);
        if($this->db->update('su_answer_templates', array('status'=>$status))){            
            return true;
        }else{            
            return false;
        }
    }
    function isAnsTempNameAvail($ansTempName,$ansTempId=null){
        
        $where=array("LOWER(TRIM(REPLACE(name,' ',''))) ="=>  strtolower(trim(str_replace(' ','', $ansTempName))));
        
        if($ansTempId!=null){
            $where['answer_template_id !=']=$ansTempId;
        }
        $this->db->select('count(name) as name_avail')->from('su_answer_templates')->where($where);
        $res = $this->db->get()->result_array();         
        
    return $res[0]['name_avail'];       
        
    }
}
