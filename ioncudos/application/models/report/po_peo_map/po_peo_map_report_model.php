<?php

/**
 * Description	:	Model(Database) Logic for PO to PEO Mapped Report Module.
 * Created on	:	03-05-2013
 * Modification History:
 * Date                Modified By           Description
 * 09-09-2013		Abhinay B.Angadi        Added file headers, function headers, indentations & comments.
 * 09-09-2013		Abhinay B.Angadi		Variable naming, Function naming & Code cleaning.
 * 25-02-2016 		Shayisat Mulla		Added justification data and included justification data pdf
  ------------------------------------------------------------------------------------------------------------
 */
?>
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Po_peo_map_report_model extends CI_Model {
    /* Function is used to fetch the curriculum details from curriculum table.
     * @param - 
     * @returns- a array of values of the curriculum details.
     */

    function fetch_crclm_list() {
	$loggedin_user_id = $this->ion_auth->user()->row()->id;
	if ($this->ion_auth->is_admin()) {
	    $curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name, d.state
				FROM curriculum AS c JOIN dashboard AS d on d.entity_id=2
				AND d.status=1
				WHERE c.status = 1
				GROUP BY c.crclm_id
				ORDER BY c.crclm_name ASC';
	} else if ($this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')) {

	    $dept_id = $this->ion_auth->user()->row()->user_dept_id;
	    $curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name
				FROM curriculum AS c, users AS u, program AS p
				WHERE u.id = "' . $loggedin_user_id . '" 
				AND u.user_dept_id = p.dept_id
				AND c.pgm_id = p.pgm_id
				AND c.status = 1
				ORDER BY c.crclm_name ASC';
	} else {
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

	$curriculum_list_data = $this->db->query($curriculum_list);
	$curriculum_result = $curriculum_list_data->result_array();
	$crclm_fetch_data['curriculum_result'] = $curriculum_result;
	return $crclm_fetch_data;
    }

// End of function fetch_crclm_list.

    /* Function is used to fetch the curriculum, POs, PEOs details from po_peo_map table.
     * @param - curriculum id.
     * @returns- a array of values of the curriculum, POs, PEOs details.
     */

    public function dept_name_by_crclm_id($crclm_id) {
	$dept_name_qry = 'SELECT dept_name 
			  FROM department 
			  WHERE dept_id = (SELECT dept_id 
					   FROM program 
					   WHERE pgm_id = (SELECT pgm_id 
							   FROM curriculum 
						           WHERE crclm_id= "' . $crclm_id . '"))';
	$dept_name_object = $this->db->query($dept_name_qry);
	$dept_name_array = $dept_name_object->result_array();

	return $dept_name_array[0]['dept_name'];
    }

    public function fetch_po_peo_mapping_details($crclm_id) {
	$crclum_query = $this->db
		->select('crclm_name')
		->where('crclm_id', $crclm_id)
		->get('curriculum')
		->result_array();

	$data['crclm_list'] = $crclum_query;

	$map_details = $this->db
		->select('po.po_id')
		->select('po_statement, po_reference, pso_flag')
		->select('po_peo_map.peo_id')
		->select('peo_statement')
		->join('po_peo_map', 'po.po_id=po_peo_map.po_id')
		->join('peo', 'peo.peo_id=po_peo_map.peo_id')
		->order_by('po_peo_map.po_id', 'asc')
		->order_by('po_peo_map.peo_id', 'asc')
		->where('po.crclm_id', $crclm_id)
		->get('po')
		->result_array();

	$po_peo_approver = $this->db->query('SELECT po_peo.approver_id,po_peo.last_date,u.title,u.first_name,u.last_name
					     FROM peo_po_approver po_peo
					     LEFT JOIN users u ON po_peo.approver_id = u.id
					     WHERE po_peo.crclm_id = ' . $crclm_id);
	$po_peo_approver = $po_peo_approver->row_array();

	if (!empty($map_details)) {
	    $data['map_details'] = $map_details;
	    $data['po_peo_approver'] = $po_peo_approver;
	    return $data;
	} else {
	    $data['map_details'] = NULL;
	    return $data;
	}
    }

// End of function fetch_po_peo_mapping_details.

    /* Function to fetch the value of individual mapping flag
     * @parameters: 
     * @return: Optional or Mandatory.
     */

    public function individual_map() {
	$query = $this->db->query('SELECT indv_mapping_justify_flag FROM organisation');
	$data = $query->result_array();

	return $data;
    }

    /* Function to fetch peo justification of a particular curriculum from the notes table
     * @parameters: curriculum id
     * @return: peo justification details.
     */

    public function justification_details($curriculum_id) {
	$notes = 'SELECT notes 
		  FROM notes 
		  WHERE crclm_id = "' . $curriculum_id . '" 
		  AND entity_id = 13 ';
	$notes = $this->db->query($notes);
	$notes = $notes->result_array();
	if (!empty($notes[0]['notes'])) {
	    return ($notes[0]['notes']);
	} else {
	    return null;
	}
    }

    /* Function to fetch the value of individual justification data
     * @parameters: curriculum_id
     * @return: Justification or null
     */

    public function individual_justification_details($curriculum_id) {
	$individual_justify_details = $this->db
		->select('po.po_id')
		->select('po_statement, po_reference')
		->select('po_peo_map.peo_id, po_peo_map.justification')
		->select('peo_statement, peo_reference')
		->join('po_peo_map', 'po.po_id=po_peo_map.po_id')
		->join('peo', 'peo.peo_id=po_peo_map.peo_id')
		->order_by('po_peo_map.po_id', 'asc')
		->order_by('po_peo_map.peo_id', 'asc')
		->where('po.crclm_id', $curriculum_id)
		->get('po')
		->result_array();

	return $individual_justify_details;
    }

    /* Function to fetch the value of justification data
     * @parameters: curriculum_id
     * @return: Justification or null
     */

    public function justification_data($curriculum_id) {

	$individual_justify_details = $this->db
		->select('po.po_id')
		->select('po_statement, po_reference')
		->select('po_peo_map.peo_id, po_peo_map.justification')
		->select('peo_statement')
		->join('po_peo_map', 'po.po_id=po_peo_map.po_id')
		->join('peo', 'peo.peo_id=po_peo_map.peo_id')
		->order_by('po_peo_map.po_id', 'asc')
		->order_by('po_peo_map.peo_id', 'asc')
		->where('po.crclm_id', $curriculum_id)
		->get('po')
		->result_array();

	$count = count($individual_justify_details);
	for ($i = 0; $i < $count; $i++) {
	    if ($individual_justify_details[$i]['justification'] != null) {
		return $individual_justify_details;
	    } else {
		return null;
	    }
	}
    }

}

// End of Model Po_peo_map_report_model
?>
