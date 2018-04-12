<?php 
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Model Logic for Po attainment batch wise report.	  
 * Modification History:
 * Date							Created By								Description
 * 22-12-2016					Mritunjay B S     	     				Po attainment batch wise report	    	         
  ---------------------------------------------------------------------------------------------------------------------------------
 */
  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
  class Course_po_attainment_matrix_model extends CI_Model 
  {

	 /*
        * Function is to fetch the curriculum details.
        * @param - -----.
        * returns list of curriculum names.
	*/   
	 public function crlcm_drop_down_fill() {

/* 	 	if($this->ion_auth->is_admin()) {
	 		return $this->db->select('crclm_id, crclm_name')
	 		->order_by('crclm_name','ASC')
	 		->where('status',1)        		
	 		->get('curriculum')->result_array();
	 	} else {
	 		$logged_in_user_id = $this->ion_auth->user()->row()->user_dept_id;
	 		return $this->db->select('crclm_id, crclm_name')
	 		->order_by('crclm_name','ASC')
	 		->where('status',1)        		
	 		->get('curriculum')->result_array();
	 	}  */
		
        $loggedin_user_id = $this->ion_auth->user()->row()->id;
        if ($this->ion_auth->is_admin()) {
            $curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name 
								FROM curriculum AS c
								WHERE c.status = 1
								ORDER BY c.crclm_name ASC';
        } elseif($this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')){
            $curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name 
							FROM curriculum AS c, users AS u, program AS p
							WHERE u.id = "' . $loggedin_user_id . '" 
							AND u.user_dept_id = p.dept_id 
							AND c.pgm_id = p.pgm_id 
							AND c.status = 1
							ORDER BY c.crclm_name ASC';
        }else{
		$curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name
				FROM curriculum AS c, users AS u, program AS p , course_clo_owner AS clo
				WHERE u.id = "' . $loggedin_user_id . '" 
				AND u.user_dept_id = p.dept_id
				AND c.pgm_id = p.pgm_id
				AND u.id = clo.clo_owner_id
				AND c.crclm_id = clo.crclm_id
				AND c.status = 1
				ORDER BY c.crclm_name ASC';
		}
        $resx = $this->db->query($curriculum_list);
        $crclm_data = $resx->result_array();

        return $crclm_data;
		
	 }
         
         /*
	* Function to Fetch the course po attainment matrices
	*@param: crclm_id
	*@return:
	*/
	public function get_course_po_attainment_matrix_data($crclm_id,$occa_type){
            $org_type = 'SELECT org_type FROM organisation';
            $org_type_data = $this->db->query($org_type);
            $org_type_res = $org_type_data->result_array();
            if($occa_type == 'CIA'){
                if($org_type_res[0]['org_type'] == 'TIER-I'){
                $po_attainment_data_query = 'CALL Show_Tier1_CIAPOAttainment('.$crclm_id.')';
                $po_attain_data = $this->db->query($po_attainment_data_query);
                mysqli_next_result($this->db->conn_id); 
                }else{
                    $po_attainment_data_query = 'CALL Show_Tier2_CIAPOAttainment('.$crclm_id.')';
                    $po_attain_data = $this->db->query($po_attainment_data_query);
                    mysqli_next_result($this->db->conn_id); 
                }
                return $po_attain_data;
                
            }else if($occa_type == 'MTE'){
                
                if($org_type_res[0]['org_type'] == 'TIER-I'){
                $po_attainment_data_query = 'CALL Show_Tier1_MTEPOAttainment('.$crclm_id.')';
                $po_attain_data = $this->db->query($po_attainment_data_query);
                mysqli_next_result($this->db->conn_id); 
                }else{
                    $po_attainment_data_query = 'CALL Show_Tier2_MTEPOAttainment('.$crclm_id.')';
                    $po_attain_data = $this->db->query($po_attainment_data_query);
                    mysqli_next_result($this->db->conn_id); 
                }
                return $po_attain_data;
                
            }else if($occa_type == 'TEE'){
                if($org_type_res[0]['org_type'] == 'TIER-I'){
                $po_attainment_data_query = 'CALL Show_Tier1_TEEPOAttainment('.$crclm_id.')';
                $po_attain_data = $this->db->query($po_attainment_data_query);
                mysqli_next_result($this->db->conn_id); 
                }else{
                    $po_attainment_data_query = 'CALL Show_Tier2_TEEPOAttainment('.$crclm_id.')';
                    $po_attain_data = $this->db->query($po_attainment_data_query);
                    mysqli_next_result($this->db->conn_id); 
                }
                return $po_attain_data;
                
            }else if($occa_type == 'BOTH'){
                if($org_type_res[0]['org_type'] == 'TIER-I'){
                $po_attainment_data_query = 'CALL Show_Tier1_BOTHPOAttainment('.$crclm_id.')';
                $po_attain_data = $this->db->query($po_attainment_data_query);
                mysqli_next_result($this->db->conn_id); 
                }else{
                    $po_attainment_data_query = 'CALL Show_Tier2_BOTHPOAttainment('.$crclm_id.')';
                    $po_attain_data = $this->db->query($po_attainment_data_query);
                    mysqli_next_result($this->db->conn_id); 
                }
                return $po_attain_data;
            }else{
                
            }
            
        }
}
?>
	 