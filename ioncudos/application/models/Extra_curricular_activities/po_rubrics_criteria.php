<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Po_rubrics_criteria extends CI_Model{
    public function __construct(){
        parent::__construct();
    }
    public function save_criteria_data($post_data){
        if($this->db->insert('po_rubrics_criteria',$post_data)){
            return $this->db->insert_id();
        }
    return false;
    }
    
    public function get_criteria($column=null,$condition=null,$num_rows_flag=false){
        $cols="rubrics_criteria_id, po_extca_id, criteria, created_by, modified_by, created_date, modified_date";
        if($column){
            $cols=$column;
        }
        $qry=  $this->db->select($cols)->from('po_rubrics_criteria');
        if($condition){
            foreach($condition as $col=>$val){
                $this->db->where($col,"$val");
            }    
        }
        //return no of record fetched by query
        if($num_rows_flag){
            $res=$qry->get()->num_rows();
        }else{
            $res=$qry->get()->result_array();
        }        
        //echo $this->db->last_query();
        return $res;
    }

    public function delete_criteria($condition){
        $flag=FALSE;
        if($condition){            
            if(is_array($condition)){
                foreach($condition as $col=>$val){
                    $this->db->where($col, "$val");
                }
                $flag= $this->db->delete('po_rubrics_criteria');
            }else{
                $this->db->where($condition,null,false);
                $flag= $this->db->delete('po_rubrics_criteria');                
            }     
            //echo $this->db->last_query();
            return $flag;
        }
        return $flag;
    }
    public function update_criteria_data($data){
        if(isset($data['rubrics_criteria_id'])){
            $this->db->where('rubrics_criteria_id',$data['rubrics_criteria_id']);
            return $this->db->update('po_rubrics_criteria',$data);
        }
        return false;
    }
    /**
     * @param  : 
     * @desc   :
     * @return :
     * @author :
     */
    
    public function get_criteria_range($column=null,$condition=null){
        if(!$column){
            $column='prc.criteria,prr.criteria_range,';
        }
       $this->db->select($column)->from('po_rubrics_criteria prc')
                ->join('po_rubrics_range prr','prc.po_extca_id=prr.po_extca_id');
       if($condition){
           foreach($condition as $col=>$val){
                $this->db->where($col,"$val");
           }
       }               
       $result= $this->db->get()->result_array();
       echo $this->db->last_query();
       return $result;
    }
}

