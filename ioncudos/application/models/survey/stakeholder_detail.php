<?php
class Stakeholder_Detail extends CI_Model{
    
    function __construct() {
        parent::__construct();
        $this->load->model('survey/other/curriculum');
        $this->load->model('survey/other/department');
        $this->load->model('survey/other/program');
    }    
    
    public function listDepartmentOptions($index,$column,$condition=null){
        return $this->department->listDepartmentOptions($index,$column,$condition);
    }
    
    public function listProgramOptions($index,$column,$condition=null){
        return $this->program->listProgramOptions($index,$column,$condition);
    }
    
    
    /**
     * @method:getStakeholder
     * @param: $data
     * @pupose: get stakeholder details
     * @return array
     */
    function getStakeholder($editId=null){
        
        if($editId!=null){        
            $this->db->select('stakeholder_detail_id,first_name,last_name,email,qualification,contact,stakeholder_group_id,crclm_id,student_usn,dept_id,pgm_id');
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
            if(!$this->isStakeholderAvail($data['email'],$data['dept_id'],$data['stakeholder_group_id'],$data['crclm_id'],$editId)){
                if(!$this->isSudentUSNAvail($data['student_usn'],$editId) || $data['student_usn']== 0){
                    $this->db->where('stakeholder_detail_id', $editId);
                    $this->db->update('su_stakeholder_details',$data);
                    return 1;
                }else{
                   return 'USN already exists.'; 
                }
                
            }else{
                return 'Email id already exists.';
            }
        }
        if($editId==null && !$this->isStakeholderAvail($data['email'],$data['dept_id'],$data['stakeholder_group_id'],$data['crclm_id'])){
            
            if($data['student_usn']!=''){
               if(!$this->isSudentUSNAvail($data['student_usn'])){
                    $this->db->insert('su_stakeholder_details',$data);
                    return 1;
                }else{
                    return 'USN already exists.';
                } 
            }else{
                $this->db->insert('su_stakeholder_details',$data);
                return 1;
            }
            
            
        }else{
                return 'Email id already exists.';
        }        
    }
    
    /**
     * @method:listStakeholder
     * @param: string/array $column for custom column list
     * @pupose: list stakeholder group
     * @return array stakeholder group data to display list
     */
    function listStakeholder($column=null,$groupType=null,$conditions=null, $stud_flag_Id=null){
	
		if($stud_flag_Id != 1){
	
			if($column==null){
					$column='su_stakeholder_details.status,su_stakeholder_groups.title,stakeholder_detail_id,first_name,last_name,email,qualification,contact,su_stakeholder_details.student_usn';
				} else if(is_array($column)){
					$column=  implode(',', $column);
				}
				 
				$this->db->select($column);
				$this->db->from('su_stakeholder_details');
				$this->db->join('su_stakeholder_groups', 'su_stakeholder_groups.stakeholder_group_id = su_stakeholder_details.stakeholder_group_id');

				if($groupType){
					$this->db->where('su_stakeholder_details.stakeholder_group_id',$groupType);				
				}
				if(is_array($conditions)){
					foreach($conditions as $key=>$col){
						$this->db->where($key,$col);
					}
				}
				$query = $this->db->get();			
				return $query->result_array(); 
			}else{				
					if($column==null){
					//$column='su_stakeholder_groups.title,stakeholder_detail_id,first_name,last_name,email,qualification,contact,su_stakeholder_details.status,su_stakeholder_details.student_usn';
					$column = 'su_stakeholder_groups.title, su_student_stakeholder_details.ssd_id as stakeholder_detail_id, su_student_stakeholder_details.stakeholder_group_id, su_student_stakeholder_details.dept_id, su_student_stakeholder_details.pgm_id, su_student_stakeholder_details.crclm_id, master_type_details.mt_details_name as section_id, su_student_stakeholder_details.student_usn, su_student_stakeholder_details.title, su_student_stakeholder_details.first_name, su_student_stakeholder_details.last_name, su_student_stakeholder_details.email, su_student_stakeholder_details.contact_number as contact, su_student_stakeholder_details.dob, su_student_stakeholder_details.created_on, su_student_stakeholder_details.created_by, su_student_stakeholder_details.modified_on, su_student_stakeholder_details.modified_by, su_student_stakeholder_details.status_active';
				} else if(is_array($column)){
					$column=  implode(',', $column);
				}
				$this->db->select($column);
				$this->db->from('su_student_stakeholder_details');
				$this->db->join('su_stakeholder_groups', 'su_stakeholder_groups.stakeholder_group_id = su_student_stakeholder_details.stakeholder_group_id');
                                $this->db->join('master_type_details', 'master_type_details.mt_details_id = su_student_stakeholder_details.section_id');
				if($groupType){
					$this->db->where('su_student_stakeholder_details.stakeholder_group_id',$groupType);
				}
			
				if(is_array($conditions)){
					foreach($conditions as $key=>$col){
						$this->db->where($key,$col);
					}
				}
				$query = $this->db->get();
				return $query->result_array();
			}        
    }
    
    /**
     * @method:isStakeholderAvail
     * @param: string $email
     * @pupose: check stakeholder email avalablity
     * @return integer 0 or 1
     */
    function isStakeholderAvail($email,$dept,$group_type,$crclm_id,$editId=null){
     
        if($editId!=null){
            $where=array('LOWER(email) ='=>strtolower($email),'dept_id ='=>$dept,'stakeholder_detail_id !='=>$editId , 'stakeholder_group_id'=>$group_type ,'crclm_id'=>$crclm_id );			 
        }else{
            $where=array('LOWER(email) ='=>strtolower($email),'(dept_id) ='=>$dept,'stakeholder_group_id'=>$group_type ,'crclm_id'=>$crclm_id );
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
    /**
     * @method:listCurriculumOptions
     * @param: $index=string,$column=string,$conditions=array()
     * @pupose: curriculum list for select box
     * @return  curriculum list for select box
     */
    function listCurriculumOptions($index=null,$column=null,$conditions=null){      
        return $this->curriculum->listCurriculumOptions($index,$column,$conditions);
    }
    /**
     * @method:isStakeholderAvail
     * @param: string $email
     * @pupose: check stakeholder email avalablity
     * @return integer 0 or 1
     */
    function isSudentUSNAvail($usn,$editId=null){
        if($editId!=null){
            $where=array('LOWER(student_usn) ='=>strtolower($usn),'stakeholder_detail_id !='=>$editId);
        }else{
            $where=array('LOWER(student_usn) ='=>strtolower($usn));
        }
        
        $this->db->select('count(student_usn) as usn_avail')->from('su_stakeholder_details')->where($where);
        $query = $this->db->get();
        $res=$query->result_array();
    return $res[0]['usn_avail'];
    }
	
	function delete_stakeholder($stkid){
		$is_stud_exists = $this->db->query("SELECT * FROM su_survey_users s join su_survey as s1 ON s1.survey_id = s.survey_id  where s.stakeholder_detail_id='".$stkid."' and s1.su_for != 8");
		if($is_stud_exists->num_rows > 0){
			return 2;
		}else{
			$is_deleted = $this->db->query("DELETE FROM su_stakeholder_details WHERE stakeholder_detail_id='".$stkid."'");
			if($is_deleted){
				return 0;
				}else{
					return 1;
				}
		}
	}
}
?>