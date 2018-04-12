
<?php
/**
 * Description	:	Generates unmapped Performance Indicator statements

 * Created		:	May 15th, 2013

 * Author		:	 

 * Modification History:
 *   Date                Modified By                         Description
 * 18-09-2013		   Arihant Prasad			File header, function headers, indentation 
												and comments.
  ------------------------------------------------------------------------------------------ */
?>
<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Unmapped_pi_report_model extends CI_Model {

    public $po_peo_map = "po_peo_map";

	/**
	 * Function is to fetch curriculum details from curriculum table
	 * @return: curriculum id and curriculum name
	 */
    function fetch_curriculum() {
		$loggedin_user_id = $this->ion_auth->user()->row()->id;
        if (($this->ion_auth->is_admin())) {
			$query = 'SELECT crclm_id, crclm_name 
					  FROM curriculum 
					  WHERE status = 1
					  ORDER BY crclm_name ASC';
			$curriculum_result = $this->db->query($query);
			$curriculum_result_data = $curriculum_result->result_array();
			$curriculum_fetch_data['result'] = $curriculum_result_data;
		} else if($this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')){
			$dept_id = $this->ion_auth->user()->row()->user_dept_id;
			$query = 'SELECT c.crclm_id, c.crclm_name 
					  FROM curriculum AS c, program AS p 
					  WHERE c.status = 1 
						  AND c.pgm_id = p.pgm_id 
						  AND p.dept_id = "'.$dept_id.'"
					  ORDER BY c.crclm_name ASC';
			$curriculum_result = $this->db->query($query);
			$curriculum_result_data = $curriculum_result->result_array();
			$curriculum_fetch_data['result'] = $curriculum_result_data;
		}else{
			$query = 'SELECT DISTINCT c.crclm_id, c.crclm_name
				FROM curriculum AS c, users AS u, program AS p , course_clo_owner AS clo
				WHERE u.id = "' . $loggedin_user_id . '" 
				AND u.user_dept_id = p.dept_id
				AND c.pgm_id = p.pgm_id
				AND u.id = clo.clo_owner_id
				AND c.crclm_id = clo.crclm_id
				AND c.status = 1
				ORDER BY c.crclm_name ASC';
			$curriculum_result = $this->db->query($query);
			$curriculum_result_data = $curriculum_result->result_array();
			$curriculum_fetch_data['result'] = $curriculum_result_data;
		}
			
		return $curriculum_fetch_data;
    }

	/**
     * Function is to fetch unmapped performance indicator details and unmapped performance 
	   indicator details from performance indicators table
	 * @parameters: curriculum id
	 * @return: program outcome id, program outcome statements, performance indicator id and 
				performance indicator statements
	 */
    public function fetch_unmapped_pi($curriculum_id) {	
        $all_pi = 'SELECT pi.pi_id, pi.pi_statement 
				   FROM performance_indicator AS pi, po 
				   WHERE pi.po_id= po.po_id AND po.crclm_id = "' . $curriculum_id . '"';
        $pi_list = $this->db->query($all_pi);
        $pi_list_data = $pi_list->result_array();
		$pi_list_data_array = array();
		$mapped_pi_list_data_array = array();
		
		if(!empty($pi_list_data)) {
			foreach ($pi_list_data as $item) {
				$pi_list_data_array[] = $item['pi_id'];
			}
		} else {
			$pi_list_data_array[] = NULL;
		}

        $mapped_pi = 'SELECT DISTINCT map.pi_id, pi.pi_statement
					  FROM clo_po_map AS map, performance_indicator AS pi
					  WHERE pi.pi_id = map.pi_id
						AND map.crclm_id ="' . $curriculum_id . '"';
        $mapped_pi_list = $this->db->query($mapped_pi);
        $mapped_pi_list_data = $mapped_pi_list->result_array();
		
		if(!empty($mapped_pi_list_data)){
			foreach ($mapped_pi_list_data as $item) {
				$mapped_pi_list_data_array[] = $item['pi_id'];
			}
		} else {
			$mapped_pi_list_data_array[] = NULL;
		}

        $unmapped_pi = array_diff($pi_list_data_array, $mapped_pi_list_data_array);
		
		if(!empty($unmapped_pi)) {
			$match_ids = join(',', $unmapped_pi);

			$unmapped = 'SELECT pi.pi_id, pi.pi_statement, po.po_id, po.po_reference, po.po_statement 
						 FROM  performance_indicator AS pi, po
						 WHERE pi_id IN('.$match_ids.') AND pi.po_id = po.po_id';
			$unmapped_pi_list = $this->db->query($unmapped);
			$unmapped_pi_list_data = $unmapped_pi_list->result_array();
			$data['unmapped_pi_list_data'] = $unmapped_pi_list_data;
			return $data;
		} else {
			$data['unmapped_pi_list_data'] = '';
			return $data;
		}
    }
}

/* End of file unmapped_pi_report_model.php */
		/* Location: ./report/unmapped_pi_report/unmapped_pi_report_model.php */
?>