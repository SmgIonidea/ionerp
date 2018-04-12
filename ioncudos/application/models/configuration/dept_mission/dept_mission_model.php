<?php

/**
 * Description	:	Database Logic for Department Mission Vision Module.
 * Created		:	22-12-2014 
 * Modification History:
 * Date				Modified By				Description
 * 27-12-2014		Jevi V. G.        Added file headers, public function headers, indentations & comments.

  -------------------------------------------------------------------------------------------------
 */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class dept_mission_model extends CI_Model {

    /**
     * Function is to fetch the details of the organisation from the organization
     * table.
     * @parameter: organization id will fetch a particular row from the organization table 
     * @return: will return organization name, description 
     */
    public function get_organisation_by_id($organization_id = null) {
        return $this->db
                        ->select('vision')
                        ->select('mission')
                        ->where('org_id', $organization_id)
                        ->get('organisation')
                        ->result_array();
    }

    /**
     * Function is to fetch the details of the department from the department
     * table.
     * @parameter: department id will fetch a particular row from the department table 
     * @return: will return department vision and mission 
     */
    public function get_dept_by_id($dept_id) {
        return $this->db
                        ->select('dept_vision')
                        ->select('dept_mission')
                        ->where('dept_id', $dept_id)
                        ->get('dept_mission_map')
                        ->result_array();
    }

    /**
     * This function updates the department mission map table 
     * @parameters: department id, department mission and vision needs to be updated 
     * in the dept_mission_map and dept_mission_element_map table 
     * @return: department details returned after updating
     */
    public function update_dept_mission($dept_id, $dept_vision, $dept_mission, $mission_element, $mission_ele_size) {
        $modified_by = $this->ion_auth->user()->row()->id;
        $modify_date = date('Y-m-d');
         $delete_mission_ele_query = 'DELETE FROM dept_mission_map WHERE dept_id = "' . $dept_id . '" ';
        $delete_mission_ele_data = $this->db->query($delete_mission_ele_query); 
        $data = array(
            'dept_id' => $dept_id,
            'dept_vision' => $dept_vision,
            'dept_mission' => $dept_mission,
            'modified_by' => $modified_by,
            'modified_date' => $modify_date
        );
        $this->db->where('dept_id', $dept_id);
        $this->db->insert('dept_mission_map', $data);
        /* $delete_mission_ele_query = 'DELETE FROM dept_mission_element_map WHERE dept_id = "' . $dept_id . '" ';
        $delete_mission_ele_data = $this->db->query($delete_mission_ele_query); */
      //  for ($i = 0; $i < $mission_ele_size; $i++) {

            $mission_element_data = array(
                'dept_id' => $dept_id,
                'dept_me' => $mission_element,
                'modified_by' => $modified_by,
                'modified_date' => $modify_date);
            $this->db->insert('dept_mission_element_map', $mission_element_data);
       // }
    }   


	/**
     * This function updates the department mission map table 
     * @parameters: department id, department mission and vision needs to be updated 
     * in the dept_mission_map and dept_mission_element_map table 
     * @return: department details returned after updating
     */
    public function update_mission_details($dept_id, $dept_vision, $dept_mission, $mission_element, $mission_ele_size , $mission_id) {

        $modified_by = $this->ion_auth->user()->row()->id;
        $modify_date = date('Y-m-d');
        $data = array(
            'dept_id' => $dept_id,
            'dept_vision' => $dept_vision,
            'dept_mission' => $dept_mission,
            'modified_by' => $modified_by,
            'modified_date' => $modify_date
        );
        $this->db->where('dept_id', $dept_id);
        $this->db->update('dept_mission_map', $data);
         
		 $mission_element_data = array(
                'dept_id' => $dept_id,
                'dept_me' => $mission_element,
                'modified_by' => $modified_by,
                'modified_date' => $modify_date);
			$this->db->where('dept_me_map_id', $mission_id);
            $this->db->update('dept_mission_element_map', $mission_element_data); 

    }

	public function update_dept_mission_vission($dept_id, $dept_vision, $dept_mission){
	    $modified_by = $this->ion_auth->user()->row()->id;
        $modify_date = date('Y-m-d');
		
		$query = $this->db->query("select * from dept_mission_map where dept_id = '". $dept_id ."' ");
		$re = $query->result_array();
		
		$data = array(
			'dept_id' => $dept_id,
            'dept_vision' => $dept_vision,
            'dept_mission' => $dept_mission,
            'modified_by' => $modified_by,
            'modified_date' => $modify_date
        );
        if(!empty($re)){
			$this->db->where('dept_id', $dept_id);
			$this->db->update('dept_mission_map', $data); 
		}else{
			$this->db->insert('dept_mission_map', $data); 
		}
	
	}
	public function delete_miision_elements($mission_id , $dept_id){
		$query  =  $this->db->query('SELECT * FROM peo_me_map p  join dept_mission_element_map as d ON p.me_id = d.dept_me_map_id where p.dept_id = "'.$dept_id.'" and me_id="'.$mission_id.'" ');
		$count = $query->num_rows();
		if($count == 0){
 			$query = $this->db->query('delete from dept_mission_element_map where dept_me_map_id = "'. $mission_id .'" ');
			if($query){
				return true;
			} else{
				return false;
			}
		}else{
		return "cant delete";		
		}
			
	}	
	
	public function count_miision_elements($mission_id , $dept_id){
		$query  =  $this->db->query('SELECT * FROM peo_me_map p  join dept_mission_element_map as d ON p.me_id = d.dept_me_map_id where p.dept_id = "'.$dept_id.'" and me_id="'.$mission_id.'" ');
		$count = $query->num_rows();
		if($count == 0){
		return "can";
		}else{
		return "cant delete";		
		}
			
	}
	
    public function get_mission_elements($dept_id) {
        return $this->db
                        ->select('dept_me')
                        ->where('dept_id', $dept_id)
                        ->get('dept_mission_element_map')
                        ->result_array();
    }    
	public function get_mission($dept_id) {
        return $this->db
                        ->select('*')
                        ->where('dept_id', $dept_id)
                        ->get('dept_mission_element_map')
                        ->result_array();
    }

    /* Function to fetch department details from department table
     * @return: an array of department details.
     */

    public function list_dept() {
        $query = 'SELECT dept_id, dept_name 
						FROM department 
						ORDER BY dept_name ASC';
        $dept_list = $this->db->query($query);
        $dept_list = $dept_list->result_array();
        $dept_fetch_data['dept_list'] = $dept_list;
        return $dept_fetch_data;
    }

}

/**
 * End of file dept_mission_model.php 
 * Location: .configuration/dept_mission_model.php 
 */
?>