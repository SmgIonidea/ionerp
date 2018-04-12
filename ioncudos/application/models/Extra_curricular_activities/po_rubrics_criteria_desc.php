<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Po_rubrics_criteria_desc extends CI_Model{
    public function __construct(){
        parent::__construct();
    }
    /**
     * @param  : 
     * @desc   :
     * @return :
     * @author :
     */
    public function save_criteria_desc_data($post_data){
        if($this->db->insert('po_rubrics_criteria_desc',$post_data)){
            return $this->db->insert_id();
        }
    return false;
    }
    /**
     * @param  : 
     * @desc   :
     * @return :
     * @author :
     */
    public function delete_criteria_desc($condition){
        $flag=FALSE;
        if($condition){
            if(is_array($condition)){
                foreach($condition as $col=>$val){
                    $this->db->where($col, "$val");
                }
                $flag=$this->db->delete('po_rubrics_criteria_desc');
            }else{
                $this->db->where($condition,null,false);
                $flag=$this->db->delete('po_rubrics_criteria_desc');
            }                        
            //echo $this->db->last_query();
            return $flag;
        }
        return $flag;
    }
    /**
     * @param  : 
     * @desc   :
     * @return :
     * @author :
     */
    public function update_criteria_desc_data($data){
        if(isset($data['criteria_description_id'])){
            $this->db->where('criteria_description_id',$data['criteria_description_id']);
            return $this->db->update('po_rubrics_criteria_desc',$data);
        }
        return false;
    }
    /**
     * @param  : 
     * @desc   :
     * @return :
     * @author :
     */
    public function fetch_criteria_desc($columns=null, $conditions = null) {
        
        if (!$columns) {
            $columns = 'criteria_description_id,rubrics_range_id,rubrics_criteria_id,criteria_description'
                    . ',created_by,modified_by,created_date,modified_date';
        }
        if ($conditions) {
            
            if(is_array($conditions)){
                foreach ($conditions as $col => $val){
                    $this->db->where($col,"$val");
                }
            }else{
                $this->db->where($conditions,null,FALSE);
            }            
        }
        return $this->db->select($columns)->from('po_rubrics_criteria_desc')                            
                    ->get()->result_array();
        return false;
    }
}

