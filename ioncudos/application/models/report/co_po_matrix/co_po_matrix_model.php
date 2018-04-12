<?php
class Co_po_matrix_model extends CI_Model{
	
	public function fetch_curriculum_details() {
				$loggedin_user_id = $this->ion_auth->user()->row()->id;
	if ($this->ion_auth->is_admin()) {
	    $curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name, d.state
				FROM curriculum AS c JOIN dashboard AS d on d.entity_id=2
				AND d.status=1
				WHERE c.status = 1
				GROUP BY c.crclm_id
				ORDER BY c.crclm_name ASC';
	} else if($this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')) {

	    $dept_id = $this->ion_auth->user()->row()->user_dept_id;
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
			$crclm_details_data = $this->db->query($curriculum_list);
			$crclm_details_result = $crclm_details_data->result_array();
		
		
		return $crclm_details_result;
	}
	
	public function get_crs($crclm_id = null) {
		$result = $this->db->query('call co_po_matrices_get_crs("'.$crclm_id.'","0")');
		mysqli_next_result($this->db->conn_id);
		$result_data = $result->result_array();
		return $result_data;
    }
	
	public function clo_po_mapping($param = null,$crs_ids = null,$with_without_pso = 0){
		$query = $this->db->query('call co_po_matrices_report("'.$param.'","'.$crs_ids.'",'.$with_without_pso.')');
		mysqli_next_result($this->db->conn_id);
		if($query->num_rows() > 0){
			$data['columns_list'] = $query->list_fields();
			$data['row_list'] = $query->result_array();
		}else{
			$data['columns_list']  = $data['row_list'] = array();
		}
		return $data;
		}
}//end of Co_po_matrix_model