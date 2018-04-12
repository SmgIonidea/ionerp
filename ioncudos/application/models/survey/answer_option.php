<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Answer_Option extends CI_Model{
    
    function __construct() {
        parent::__construct();
        $this->load->model('survey/answer_template');
    }
    
    /**
     * @method:add
     * @param: $data
     * @pupose: Save question type
     * @return integer last inserted id as confirmation
     */
    function add($data,$editId = null){

        if($editId!=null){
            $this->db->where('question_type_id', $editId);
            $this->db->update('su_question_types',$data);
            return 1;
        }else{
            
            foreach($data['options'] as $key =>$row){
                $insertData = array();
                $insertData['answer_template_id'] = $data['answer_template_id'];
                $insertData['options'] = $row;
                $insertData['option_val'] = $data['option_val'][$key];
            
                $this->db->set($insertData);
                $this->db->insert('su_answer_options',$insertData);
            }
            return true;
        }
    }
        
    /**
     * @method:getStandardOptions
     * @param: $ansTemp array()//answer_template_id
     * @pupose: fetch the standeard option list
     * @return array answer options list
     */
    function getStandardOptions($templateId=null,$conditions=null,$multiFlag=null){
        
        if($templateId!=null){
            return $this->answerOpt($templateId,$multiFlag);//return only option list as per option template id
        }
        $conditions['status']=1;
        $ansTemp=$this->answer_template->getAnsTemplate(null,$conditions);        
        $ansTempOpt=array();
        foreach($ansTemp as $tempName){
            $ansTempOpt[$tempName['answer_template_id']]=array($tempName['name']=>$this->answerOpt($tempName['answer_template_id']));
        }           
       return $ansTempOpt;
    }
    
    function answerOpt($tempId=null,$multiFlag=null){
        if($tempId==null){
            $column='*';
        }else{
            $column=array('answer_options_id','options','option_val');
        }
        $this->db->select($column);
        $this->db->from('su_answer_options');
        if($tempId){
            $this->db->where('answer_template_id',$tempId);
        }
        //$this->db->where('status','1');
        $rec=$this->db->get()->result_array();
        $res=array();
        if($multiFlag==1){
            foreach($rec as $values){
                $res[$values['answer_options_id']]=array($values['options'],$values['option_val']);
            }
        }else{
            foreach($rec as $values){
                $res[$values['answer_options_id']]=$values['options'];
            }   
        }
        return $res;
    }
    
    function delete($id){
        $this->db->where('answer_template_id', $id);
        $this->db->delete('su_answer_options'); 
    }
}
?>
