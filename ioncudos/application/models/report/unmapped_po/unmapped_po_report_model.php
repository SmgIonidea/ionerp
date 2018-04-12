<?php

/**
 * Description	:	Generates unmapped program outcomes

 * Created		:	May 12th, 2013

 * Author		:	 

 * Modification History:
 *   Date                Modified By                         Description
 * 17-09-2013		   Arihant Prasad			File header, function headers, indentation 
												and comments.
  ------------------------------------------------------------------------------------------ */
?>
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Unmapped_po_report_model extends CI_Model {

    public $po_peo_map = "po_peo_map";

	/**
	 * Function is to fetch curriculum details from curriculum table
	 * @return: curriculum id and curriculum name
	 */
    public function fetch_curriculum() {
	$loggedin_user_id = $this->ion_auth->user()->row()->id;
		if (($this->ion_auth->is_admin())) {			
			$query = 'SELECT crclm_id, crclm_name 
					  FROM curriculum 
					  WHERE status = 1
					  ORDER BY crclm_name ASC';
			$curriculum_result = $this->db->query($query);
			$curriculum_result_data = $curriculum_result->result_array();
			$curriculum_fetch_data['result'] = $curriculum_result_data;
		} else if($this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')) {
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
		} else {
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
	 * Function is to fetch unmapped program outcome details from program outcome table
	 * @parameters: curriculum id
	 * @return: program outcome id and program outcome statement
	 */
    public function fetch_unmapped_po($curriculum_id) {
		$crclm_query = 'SELECT crclm_name
						FROM curriculum
						WHERE crclm_id = "'.$curriculum_id.'"';
		$crclm_details = $this->db->query($crclm_query);
		$crclm_data = $crclm_details->result_array();
		$data['crclm_data'] = $crclm_data;
	
        $all_po = 'SELECT po_id, po_reference, po_statement 
				   FROM po
				   WHERE crclm_id = "' . $curriculum_id . '"
				   ORDER BY LENGTH(po_reference), po_reference';
        $po_list = $this->db->query($all_po);
        $po_list_data = $po_list->result_array();
		$po_list_data_array[] = array();
				
		if(!empty($po_list_data)){
			foreach ($po_list_data as $item) {
				$po_list_data_array[] = $item['po_id'];
			}
		} else {
			$po_list_data_array[] = NULL;
		}
		
		//convert two/multi dimensional array to single dimensional array
		$po_list =  new RecursiveIteratorIterator(new RecursiveArrayIterator($po_list_data_array));
		$po_list_data_array = iterator_to_array($po_list, false);
		
        $mapped_po = 'SELECT DISTINCT map.po_id, po.po_reference, po.po_statement
					  FROM po, clo_po_map AS map
					  WHERE po.po_id = map.po_id
						AND map.crclm_id = "' . $curriculum_id . '"
						ORDER BY po_id asc';
        $mapped_po_list = $this->db->query($mapped_po);
        $mapped_po_list_data = $mapped_po_list->result_array();
		$mapped_po_list_data_array[] = array();
				
		if(!empty($mapped_po_list_data)) {
			foreach ($mapped_po_list_data as $another_item) {
				$mapped_po_list_data_array[] = $another_item['po_id'];
			}
		} else {
			$mapped_po_list_data_array[] = NULL;
		}
		
		//convert two dimensional array to single dimensional array
		$mapped_po_list =  new RecursiveIteratorIterator(new RecursiveArrayIterator($mapped_po_list_data_array));
		$mapped_po_list_data_array = iterator_to_array($mapped_po_list, false);
		
		//left over values in po_list_data_array are displayed
        $unmapped_po = array_diff($po_list_data_array, $mapped_po_list_data_array);
		
		if(!empty($unmapped_po)){
			$match_ids_string = implode(',', $unmapped_po);
			
			$unmapped = 'SELECT po_id, po_reference, po_statement 
						 FROM po 
						 WHERE po_id IN ("'.$match_ids_string.'") ';
			$unmapped_po_list = $this->db->query($unmapped);
			$unmapped_po_list_data = $unmapped_po_list->result_array();
			$data['unmapped_po_list_data'] = $unmapped_po_list_data;
			
			return $data;
		} else {
			$data['unmapped_po_list_data'] = '';
			return $data;
		}
    }
}

/*
 * End of file unmapped_po_report_model.php
 * Location: ./report/unmapped_po/unmapped_po_report_model.php 
 */
?>