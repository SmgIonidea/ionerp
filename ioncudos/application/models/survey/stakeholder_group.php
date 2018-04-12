<?php
class Stakeholder_Group extends CI_Model{
    
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
            $this->db->select("stakeholder_group_id,title as stk_title,description,student_group");
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
        
        if($editId){
            $where=array('stakeholder_group_id !='=>$editId,'LOWER(TRIM(REPLACE(title," ",""))) ='=>  strtolower(trim(str_replace(" ", "", $title))));
        }else{
            $where=array('LOWER(TRIM(REPLACE(title," ",""))) ='=>  strtolower(trim(str_replace(" ", "", $title))));
        }
        $this->db->select('count(title) as title_avail')->from('su_stakeholder_groups')->where($where);
        $query = $this->db->get();
        $res=$query->result_array();
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
    function stakeholderGroupOptions($index,$column,$custom=null,$survey_for=null){  

        if(is_array($custom)){
            $custom_col= ','.implode(',', $custom);
        }else{
            $custom_col='';
        }
        if($survey_for == 8){
			$this->db->select("$index,$column$custom_col")->from('su_stakeholder_groups')->where('status',1)->where('student_group',1);
			$query = $this->db->get();
			$res=$query->result_array();        
			$list=array();
		}else{
			$this->db->select("$index,$column$custom_col")->from('su_stakeholder_groups')->where('status',1)->where('student_group !=',1);
			$query = $this->db->get();
			$res=$query->result_array();        
			$list=array();
		}
        
        
        if(is_array($custom)) {            
            foreach ($res as $val) {
                $list[$val[$index]] = array(
                        $column=>$val[$column],                    
                );
                foreach ($custom as $key => $col) {
                    $list[$val[$index]][$col]=$val[$col];                    
                }
            }
        } else {            
            $list[0] = 'Select Stakeholder Group';
            foreach ($res as $val) {
                $list[$val[$index]] = $val[$column];
            }
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
     * @method:getStakeholderGroupName
     * @param: integer $stakehoderGroupId
     * @pupose: get stakeholder group name
     * @return string group name
     */
    function getStakeholderGroupName($stakehoderGroupId){   
        $this->db->select('title');
        $this->db->from('su_stakeholder_groups');
        $rec=$this->db->where('stakeholder_group_id', $stakehoderGroupId)->get()->result_array();
        return $rec[0]['title'];
    }
    /**
     * @method:getStakeholderGroupFlag
     * @param: integer $stakehoderGroupId
     * @pupose: get stakeholder group name
     * @return string group name
     */
    function getStakeholderGroupFlag($stakehoderGroupId){   
        $this->db->select('student_group');
        $this->db->from('su_stakeholder_groups');
        $rec=$this->db->where('stakeholder_group_id', $stakehoderGroupId)->get()->result_array();
		if(!empty($rec)){ return $rec[0]['student_group'];}
       
    }
	
	function listDepartments($group_id,$condition=null){
            if($this->ion_auth->is_admin()) {
		$result = $this->db->query("select d.dept_id,d.dept_name from department d,su_stakeholder_details s where d.dept_id=s.dept_id AND d.status =1 AND s.stakeholder_group_id='".$group_id."' group by d.dept_id;");
		}else{
		$logged_in_user_dept_id = $this->ion_auth->user()->row()->user_dept_id;
		$result = $this->db->query("select d.dept_id,d.dept_name from department d,su_stakeholder_details s where d.dept_id=s.dept_id AND d.status =1 AND s.stakeholder_group_id='".$group_id."' AND d.dept_id='".$logged_in_user_dept_id."' group by d.dept_id;");
                
		}
		return $result->result_array();
    }
	function listPrograms($dept_id){
		$result = $this->db->query("SELECT pgm_id,pgm_acronym FROM program p where dept_id='".$dept_id."';");
		return $result->result_array();
	}	
	function listCurriculum($dept_id , $pgm_id){
		$result = $this->db->query('select * from curriculum where pgm_id = "'.$pgm_id.'"');
		return $result->result_array();
	}
	
}
?>