<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Stakeholder extends CI_Model{
    
    function __construct() {
        parent::__construct();
    }
    /**
     * @method:saveStakeholderGroup
     * @param: $data
     * @pupose: Save stakeholder group type
     * @return integer last inserted id as confirmation
     */
    function getStakeholderGroup($editId=null){
        
        if($editId!=null){        
            $this->db->select('stakeholder_group_id,title as stk_title,description');
            $this->db->from('su_stakeholder_groups');
            $this->db->where('stakeholder_group_id', $editId);
            $query=$this->db->get();
            return $query->result_array();
        }else{
            return 0;
        }
    }
    /**
     * @method:saveStakeholderGroup
     * @param: $data
     * @pupose: Save stakeholder group type
     * @return integer last inserted id as confirmation
     */
    function saveStakeholderGroup($data, $editId=null){
        $this->db->set($data);
        if($editId!=null){
            if(!$this->isStakeholderGroupAvail($data['title'],$editId)){                
                $this->db->where('stakeholder_group_id', $editId);
                $this->db->update('su_stakeholder_groups',$data);
                return 1;
            }else{                
                return 0;
            }
        }else if(!$this->isStakeholderGroupAvail($data['title'])){
            $this->db->insert('su_stakeholder_groups',$data);
        }else{
            return 0;
        }
        return $this->db->insert_id();
    }
    /**
     * @method:isStakeholderGroupAvail
     * @param: string $title
     * @pupose: check stakeholder group type avalablity
     * @return integer 0 or 1
     */
    function isStakeholderGroupAvail($title,$editId=null){
        echo '$editId'.$editId;
        if($editId){
            $where=array('stakeholder_group_id !='=>$editId,'LOWER(TRIM(REPLACE(title," ",""))) ='=>  strtolower(trim(str_replace(" ", "", $title))));
        }else{
            $where=array('LOWER(TRIM(REPLACE(title," ",""))) ='=>  strtolower(trim(str_replace(" ", "", $title))));
        }
        $this->db->select('count(title) as title_avail')->from('su_stakeholder_groups')->where($where);
        $query = $this->db->get();
        $res=$query->result_array();
        //print_r($res);exit;
    return $res[0]['title_avail'];
    }
    
    /**
     * @method:listStakeholderGroup
     * @param: string/array $column for custom column list
     * @pupose: list stakeholder group
     * @return array stakeholder group data to display list
     */
    function listStakeholderGroup($column=null){
        if($column==null){
            $column='stakeholder_group_id,title,description,status';
        } else if(is_array($column)){
            $column=  implode(',', $column);
        }
        
        $this->db->select($column)->from('su_stakeholder_groups');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    /**
     * @method:stakeholderGroupOptions
     * @param: $index,$column
     * @pupose: list stakeholder group
     * @return array stakeholder group data to dropdown generate
     */
    function stakeholderGroupOptions($index,$column){        
        
        $this->db->select("$index,$column")->from('su_stakeholder_groups');
        $query = $this->db->get();
        $res=$query->result_array();
        $list=array();
        $list[0]='Select stakeholder type';
        foreach($res as $val){
            $list[$val[$index]]=$val[$column];
        }
        return $list;
    }
    
     /**
     * @method:changeStakeholderGroupStatus
     * @param: integer $stakehoderGroupId
     * @pupose: Enable or disable stakeholer group
     * @return inter 0 or 1 
     */
    function changeStakeholderGroupStatus($stakehoderGroupId,$status){        
        $this->db->where('stakeholder_group_id', $stakehoderGroupId);
        if($this->db->update('su_stakeholder_groups', array('status'=>$status))){            
            return true;
        }else{            
            return false;
        }
    }
    /**
     * @method:getStakeholder
     * @param: $data
     * @pupose: get stakeholder details
     * @return array
     */
    function getStakeholder($editId=null){
        
        if($editId!=null){        
            $this->db->select('stakeholder_detail_id,first_name,last_name,email,qualification,contact,stakeholder_group_id');
            $this->db->from('su_stakeholder_details');
            $this->db->where('stakeholder_detail_id', $editId);
            $query=$this->db->get();
            return $query->result_array();
        }else{
            return 0;
        }
    }
    /**
     * @method:saveStakeholder
     * @param: array $data
     * @pupose: store stakeholder details
     * @return integer last inserted id as a confirmation
     */
    function saveStakeholder($data,$editId=null){
        $this->db->set($data);
        if($editId!=null){
            if(!$this->isStakeholderAvail($data['email'],$editId)){                
                $this->db->where('stakeholder_detail_id', $editId);
                $this->db->update('su_stakeholder_details',$data);
                return 1;
            }else{
                return 0;
            }
        }else if(!$this->isStakeholderAvail($data['email'])){
            $this->db->insert('su_stakeholder_details',$data);
        }else{
            return 0;
        }
        return $this->db->insert_id();
    }
    
    /**
     * @method:listStakeholder
     * @param: string/array $column for custom column list
     * @pupose: list stakeholder group
     * @return array stakeholder group data to display list
     */
    function listStakeholder($column=null,$groupType=null){
        if($column==null){
            $column='stkG.title,stakeholder_detail_id,first_name,last_name,email,qualification,contact';
        } else if(is_array($column)){
            $column=  implode(',', $column);
        }
        $this->db->select('*');
        $this->db->from('su_stakeholder_details');
        $this->db->join('su_stakeholder_groups', 'su_stakeholder_groups.stakeholder_group_id = su_stakeholder_details.stakeholder_group_id');
        if($groupType){
            $this->db->where('su_stakeholder_details.stakeholder_group_id',$groupType);
        }
        $query = $this->db->get();
        return $query->result_array();        
    }
    
    /**
     * @method:isStakeholderAvail
     * @param: string $email
     * @pupose: check stakeholder email avalablity
     * @return integer 0 or 1
     */
    function isStakeholderAvail($email,$editId=null){
        if($editId!=null){
            $where=array('LOWER(email) ='=>strtolower($email),'stakeholder_detail_id !='=>$editId);
        }else{
            $where=array('LOWER(email) ='=>strtolower($email));
        }
        
        $this->db->select('count(email) as email_avail')->from('su_stakeholder_details')->where($where);
        $query = $this->db->get();
        $res=$query->result_array();
    return $res[0]['email_avail'];
    }
    
    /**
     * @method:changeStakeholderStatus
     * @param: integer $stakehoderId
     * @pupose: Enable or disable stakeholer
     * @return inter 0 or 1 
     */
    function changeStakeholderStatus($stakehoderId,$status){        
        $this->db->where('stakeholder_detail_id', $stakehoderId);
        if($this->db->update('su_stakeholder_details', array('status'=>$status))){            
            return true;
        }else{            
            return false;
        }
    }
}
