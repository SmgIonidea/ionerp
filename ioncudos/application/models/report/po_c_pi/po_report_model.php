<?php
/** 
* Description	:	Model(Database) Logic for PO, Compentency & Performance Indicators Report Module.
* Created on	:	03-05-2013
* Modification History:
* Date                Modified By           Description
* 14-01-2016		Vinay M Havalad        Added file headers, function headers, indentations & comments.
* 14-01-2016		Vinay M Havalad		Variable naming, Function naming & Code cleaning.
------------------------------------------------------------------------------------------------------------
*/
?>
<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Po_report_model extends CI_Model {

    /* Function is used to fetch the curriculum details from curriculum table.
	* @param - 
	* @returns- a array of values of the curriculum details.
	*/    
	function fetch_crclm_list() {
	$loggedin_user_id = $this->ion_auth->user()->row()->id;	
	if (($this->ion_auth->is_admin()))
		{		
			$query = 'SELECT crclm_id, crclm_name 
						FROM curriculum 
						WHERE status = 1
						ORDER BY crclm_name ASC';
			$curriculum_result = $this->db->query($query);
			$curriculum_result = $curriculum_result->result_array();
			$crclm_fetch_data['curriculum_result'] = $curriculum_result;
			return $crclm_fetch_data;
		}
		else if($this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')){
			$dept_id = $this->ion_auth->user()->row()->user_dept_id;
			$query = 'SELECT c.crclm_id, c.crclm_name 
						FROM curriculum AS c, program AS p 
						WHERE c.status = 1 
						AND c.pgm_id = p.pgm_id 
						AND p.dept_id = "'.$dept_id.'" 
						ORDER BY c.crclm_name ASC ';
			$curriculum_result = $this->db->query($query);
			$curriculum_result = $curriculum_result->result_array();
			$crclm_fetch_data['curriculum_result'] = $curriculum_result;
			return $crclm_fetch_data;
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
			$curriculum_result = $curriculum_result->result_array();
			$crclm_fetch_data['curriculum_result'] = $curriculum_result;
			return $crclm_fetch_data;
		}
        
    }// End of function fetch_crclm_list.

    /* Function is used to fetch the curriculum, po, compentency and performance indicators details from po, measures and performance indicators table.
	* @param - curriculum id.
	* @returns- a array of values of the curriculum, po, compentency and performance indicators details.
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
	
    public function fetch_po_details($crclm_id) {
        $crclum_query = $this->db
							->select('crclm_name')
							->where('crclm_id', $crclm_id)
							->get('curriculum')
							->result_array();

        $data['crclm_list'] = $crclum_query;
		
        $po_details = $this->db
							->select('po.po_id')
							->select('po_statement, po_reference')
							->select('pi.po_id')
							->select('pi.pi_statement,pi.pi_id')
							->select('m.msr_statement,m.msr_id')
							->join('performance_indicator pi', 'po.po_id=pi.po_id')
							->join('measures m', 'pi.pi_id=m.pi_id')
							->order_by('po.po_id', 'asc')
						//	->group_by('pi.pi_id', 'asc')
						//	->order_by('m.msr_id', 'asc')
							->where('po.crclm_id', $crclm_id)
							->get('po')
							->result_array();
		
						//$QUERY = $this->db->last_query();
							
							// var_dump($QUERY);
							// exit;
		
		// $po_peo_approver = $this->db->query('SELECT po_peo.approver_id,po_peo.last_date,u.title,u.first_name,u.last_name
											// FROM peo_po_approver po_peo
											// LEFT JOIN users u ON po_peo.approver_id = u.id
											// WHERE po_peo.crclm_id = '.$crclm_id);
		// $po_peo_approver = $po_peo_approver->row_array();
        
		if(!empty($po_details)){
			$data['po_details'] = $po_details;
			
			return $data;
		}
		else
		{
			$data['po_details'] = NULL;
			return $data;
		}
		
    }// End of function fetch_po_details.
	
}// End of Model Po_report_model
?>