<?php
/** 
* Description	:	Model(Database) Logic for Unmapped Measures Report Module.
* Created on	:	03-05-2013
* Modification History:
* Date                Modified By           Description
* 17-09-2013		Abhinay B.Angadi        Added file headers, function headers, indentations & comments.
* 18-09-2013		Abhinay B.Angadi		Variable naming, Function naming & Code cleaning.
------------------------------------------------------------------------------------------------------------
*/
?>

<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Unmapped_msr_report_model extends CI_Model {

    /* Function is used to fetch the curriculum details from curriculum table.
	* @param - 
	* @returns- a array of values of the curriculum details.
	*/ 
	public function fetch_crclm_list() {
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

    /* Function is used to fetch the unmapped POs, PIs & Measures from clo_po_map table.
	* @param - curriculum id.
	* @returns- a array of values of the POs, PIs & Measures details.
	*/
	public function fetch_unmapped_measures($crclm_id) {
        $all_msr = 'SELECT msr_id, msr_statement, pi_codes 
					FROM measures AS m, performance_indicator AS pi, po 
					WHERE m.pi_id = pi.pi_id 
					AND	pi.po_id = po.po_id AND
						po.crclm_id = "'.$crclm_id.'" ';
        $msr_list = $this->db->query($all_msr);
        $msr_list_data = $msr_list->result_array();
		
		if(!empty($msr_list_data)) {
			foreach ($msr_list_data as $item){
				$msr_list_data_array[] = $item['msr_id'];
				
			}
		} else {
			$msr_list_data_array[] = NULL;
		}
		
		$mapped_msr = 'SELECT DISTINCT map.msr_id, m.msr_statement
					   FROM  measures AS m, clo_po_map AS map
					   WHERE m.msr_id = map.msr_id
						  AND map.crclm_id = "'.$crclm_id.'" ';
		$mapped_msr_list = $this->db->query($mapped_msr);
		$mapped_msr_list_data = $mapped_msr_list->result_array();
		
		if(!empty($mapped_msr_list_data)) {
			foreach ($mapped_msr_list_data as $item1) {
				$mapped_msr_list_data_array[] = $item1['msr_id'];
			}
		} else {
			$mapped_msr_list_data_array[] = NULL;
		}
		$unmapped_msr = array_diff($msr_list_data_array, $mapped_msr_list_data_array);
	
		if(!empty($unmapped_msr)) {
			$ids = join(',', $unmapped_msr);

			$unmapped = 'SELECT m.msr_id, m.msr_statement, m.pi_codes, pi.pi_id, pi.pi_statement, po.po_id, po.po_reference, po.po_statement 
						 FROM measures AS m, performance_indicator AS pi, po 
						 WHERE msr_id IN('.$ids.') 
							AND m.pi_id = pi.pi_id 
							AND pi.po_id = po.po_id ';
			$unmapped_msr_list = $this->db->query($unmapped);
			$unmapped_msr_list_data = $unmapped_msr_list->result_array();
			$data['unmapped_msr_list_data'] = $unmapped_msr_list_data;
				
			return $data;
		} else {
			$data['unmapped_msr_list_data'] = '';
			return $data;
		}
      }
	
}// End of Model Unmapped_msr_report_model
?>