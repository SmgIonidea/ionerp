<?php
/**
 * Description	:	Approved Student Outcome along with its corresponding Performance Indicators
					and Measures

 * Created		:	March 30th, 2013

 * Author		:	 

 * Modification History:
 *   Date                Modified By                         Description
 * 11-01-2014		   Arihant Prasad			File header, function headers, indentation 
												and comments.
 ----------------------------------------------------------------------------------------------*/
?>

<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pi_and_measures_model extends CI_Model {
    public $po_table = "po";
    public $performance_indicator_table = "performance_indicator";
    public $measures_table = "measures";

	/**
	 * Function to fetch curriculum details from curriculum table
	 * @return: curriculum details
	 */
    function list_curriculum() {
        if ($this->ion_auth->is_admin()) {
            $curriculum_list_query = 'SELECT DISTINCT c.crclm_id, c.crclm_name
									  FROM curriculum AS c, dashboard AS d
									  WHERE d.entity_id = 20
										AND d.status = 1
										AND c.status = 1
										ORDER BY c.crclm_name ASC';
        } else {
            $department_id = $this->ion_auth->user()->row()->user_dept_id;
            $curriculum_list_query = 'SELECT DISTINCT c.crclm_id, c.crclm_name
									  FROM curriculum AS c, program AS p, dashboard AS d
									  WHERE p.dept_id = "' . $department_id . '"
										AND d.crclm_id = c.crclm_id
										AND p.pgm_id = c.pgm_id
										AND d.entity_id = 20
										AND d.status = 1
										AND c.status = 1
										ORDER BY c.crclm_name ASC';
        }
		
        $curriculum_list_data = $this->db->query($curriculum_list_query);
        $curriculum_list = $curriculum_list_data->result_array();
		
        return $curriculum_list;
    }
	
		
	/**
	 * Function to list all program outcome statements fetched from program outcome table
	 * @parameters: curriculum id
	 * @return: program outcome details
	 */
    function list_po($curriculum_id) {
		$oe_pi_flag_query = 'SELECT oe_pi_flag
							 FROM curriculum
							 WHERE crclm_id = "'. $curriculum_id .'"';
		$oe_pi_flag_result = $this->db->query($oe_pi_flag_query);
		$oe_pi_flag_data = $oe_pi_flag_result->result_array();
		$po_data['oe_pi_flag'] = $oe_pi_flag_data;
			
        $po_list = 'SELECT po_id,pso_flag, po_reference, po_statement, crclm_id
					FROM po 
					WHERE crclm_id = "' . $curriculum_id . '"
					ORDER BY LPAD(LOWER(po_reference),5,0) ASC';
        $po_list_data = $this->db->query($po_list);
        $po_list_result = $po_list_data->result_array();
		$po_data['po_list_data'] = $po_list_result;
		
		$po_comment_query = 'SELECT po_id, actual_id, crclm_id, status, cmt_statement 
							 FROM comment 
							 WHERE crclm_id = "' . $curriculum_id . '"
								AND entity_id = 20';
		$po_comment = $this->db->query($po_comment_query);
		$po_cmt_data = $po_comment->result_array();
		$po_data['po_comment'] = $po_cmt_data;
		
        return $po_data;
    }
	
	/**
	 * Function to fetch the comments from comment table
	 * @parameters: curriculum id
	 * @return: comment details
	 */
    function list_po_pi_comment($curriculum_id) {
		$select_query = 'SELECT po_id, crclm_id, actual_id, cmt_statement, status 
						 FROM comment
						 WHERE entity_id = 20 
							AND crclm_id = "' . $curriculum_id . '"';
		$comment = $this->db->query($select_query);
		$comment = $comment->result_array();

		return $comment;
    }
	
	/**
	 * Function to fetch performance indicators for corresponding program outcomes from performance indicator table
	 * @parameters: program outcome id
	 * @return: performance indicator details
	 */
    function related_pi_for_po($po_id) {
        $pi = 'SELECT pi_id, pi_statement
			   FROM performance_indicator 
			   WHERE po_id = "' . $po_id . '"';
        $pi_list = $this->db->query($pi);

        $po = 'SELECT po_reference, po_statement 
			   FROM po 
			   WHERE po_id = "' . $po_id . '"';
        $po_stat = $this->db->query($po);
        $po_stat = $po_stat->result_array();
        $pi_list = $pi_list->result_array();

        $data['po_stat'] = $po_stat;
        $data['pi_list'] = $pi_list;

        return $data;
    }
	
	/**
	 * Function to fetch measures corresponding to performance indicators from measures
	 * @parameters: performance indicator id
	 * @return: measure details
	 */
    function related_measures_for_pi($pi_id) {
        $msr = 'SELECT msr_id, msr_statement 
				FROM measures 
				WHERE pi_id = "' . $pi_id . '"';
        $msr_list = $this->db->query($msr);
		
        if ($msr_list->num_rows() > 0) {
            $msr_list = $msr_list->result_array();
        } else {
            $msr_list = false;
        }
        return $msr_list;
    }
	
	/**
	 * Function is used to fetch the current state of PI & Measures from dashboard table.
	 * @parameter: curriculum id.
	 * @return: array value of the current state details.
	 */
    public function fetch_pi_measures_current_state($curriculum_id) {
		$pi_measures_data = 'SELECT ws.state_id, ws.status as state_name, d.state
							 FROM  workflow_state AS ws, dashboard AS d
							 WHERE d.crclm_id = "' . $curriculum_id . '"
								AND d.status = 1
								AND d.entity_id = 20
								AND d.particular_id = "' . $curriculum_id . '"
								AND d.state = ws.state_id ';
		$pi_measures_result = $this->db->query($pi_measures_data);
        $pi_measures_state_data = $pi_measures_result->result_array();
		
		if(!empty($pi_measures_state_data)) {
			return $pi_measures_state_data;
		} else {
			return null;
		}
    }
	
	/**
	 * Function to fetch approved program outcomes from po table
	 * @parameters: program outcome id
	 * @return: program outcome statement
	 */
	public function manage_po_pi($po_id) {
		//fetch po details
        $po_fetch_details = 'SELECT po_id,pso_flag, po_statement, po_reference
							 FROM po
							 WHERE po_id = "' . $po_id . '"';
		$po_fetch_results = $this->db->query($po_fetch_details);
		$po_data = $po_fetch_results->result_array();
		
		return $po_data;
	}
	
	/**
	 * Function to fetch bos comments from comment table
	 * @parameters: program outcome id
	 * @return: comment statements
	 */
	public function manage_bos_cmt($po_id) {
		//fetch comment details
        $cmt_fetch_details = 'SELECT po_id, cmt_statement
							  FROM comment
							  WHERE po_id = "' . $po_id . '"
								AND entity_id = 20';
		$cmt_fetch_results = $this->db->query($cmt_fetch_details);
		$cmt_data = $cmt_fetch_results->result_array();
		
		if(!empty($cmt_data)) {
			return $cmt_data;
		} else {
			$cmt_data[0]['cmt_statement'] = "No BOS Comments";
			return $cmt_data;
		}
    }
	
	/**
	 * Function to fetch performance indicator details from performance indicator table
	 * @parameters: program outcome id
	 * @return: performance indicator id and statement
	 */
	public function fetch_pi($po_id) {
		$pi_fetch_details = 'SELECT pi_id, pi_statement
							 FROM performance_indicator
							 WHERE po_id = "' . $po_id . '"';
		$pi_fetch_results = $this->db->query($pi_fetch_details);
		$pi_data = $pi_fetch_results->result_array();
		$data = $pi_data;
		
		return $data;
	}
	
	/**
	 * Function to fetch measure details from measure table
	 * @parameters: performance indicator id
	 * @return: performance indicator id and statement
	 */
	public function fetch_measures($pi_id) {
		$msr_fetch_details = 'SELECT msr_id, msr_statement
							  FROM measures
							  WHERE pi_id = "' . $pi_id . '"';
		$msr_fetch_results = $this->db->query($msr_fetch_details);
		$msr_data = $msr_fetch_results->result_array();
		$data['msr'] = $msr_data;
		
		$pi_fetch_details =  'SELECT pi_id, pi_statement
							  FROM performance_indicator
							  WHERE pi_id = "' . $pi_id . '"';
		$pi_fetch_results = $this->db->query($pi_fetch_details);
		$pi_data = $pi_fetch_results->result_array();
		$data['pi'] = $pi_data;
		
		return $data;
	}
	
	/**
	 * Function to fetch measure details from measure table for the related individual performance indicator
	 * @parameters: performance indicator id
	 * @return: measure statements
	 */
	public function fetch_pi_related_measures($pi_id) {
		$pi_related_msr_fetch_details = 'SELECT msr_statement
										 FROM measures
										 WHERE pi_id = "' . $pi_id . '"';
		$pi_related_msr_fetch_data = $this->db->query($pi_related_msr_fetch_details);
		$pi_related_msr_fetch = $pi_related_msr_fetch_data->result_array();
		
		$data['pi_related_msr_fetch'] = $pi_related_msr_fetch;
		return $data;
	}
	
	/**
	 * Function to fetch program outcome using performance indicator id
	 * @parameters: performance indicator id
	 * @return: program outcome id
	 */
	public function fetch_po_using_pi($pi_id) {
		$fetch_po_details = 'SELECT po_id
							 FROM performance_indicator
							 WHERE pi_id = "' . $pi_id . '"';
		$fetch_po_data = $this->db->query($fetch_po_details);
		$po_data = $fetch_po_data->result_array();
		$data = $po_data[0]['po_id'];
		
		return $data;
	}
	
	/**
	 * Function to insert and/or update performance indicators
	 * @parameters: program outcome id, add/edit counter size, pi statements, add/edit counter value, performance indicator id
	 * @return: performance indicator details
	 */
	public function insert_update_pi($po_id, $add_pi_statement, $add_edit_pi_statement, $pi_id) {
		//check if performance indicator id exists
		if(!empty($pi_id)) {
			//update existing PIs or add new PIs to the existing ones
			//fetch all performance indicator(s) related to that program outcome id
			$all_pi_details = 'SELECT pi_id
							   FROM performance_indicator
							   WHERE po_id = "' . $po_id . '"';
			$all_pi_data = $this->db->query($all_pi_details);
			$all_pi = $all_pi_data->result_array();
			
			//insert pi id in an array
			foreach ($all_pi as $current_pi_id) {
				$present_pi_id_array[] = $current_pi_id['pi_id'];
			}
			
			//remove performance indicators from the pi table
			$delete_pi_array = array_values(array_diff($present_pi_id_array, $pi_id));
			$pi_edit_statement_size = sizeof($add_edit_pi_statement);
			
			for ($k = 0; $k < $pi_edit_statement_size; $k++) {
				if(!empty($pi_id[$k])) {
					//if pi id exist update
					$pi_data = array(
							'pi_statement' => $add_edit_pi_statement[$k],
						);
					
					$this->db->where('pi_id', $pi_id[$k])
							 ->update('performance_indicator', $pi_data);
				} else {
					//if pi id does not exist, then insert
					$pi_data = array(
							'po_id' => $po_id,
							'pi_statement' => $add_edit_pi_statement[$k],
						);

					$this->db->insert('performance_indicator', $pi_data);
				}
			}
		} else {
			//insert new performance indicator - adding PI for the first time
			$pi_statement_size = sizeof($add_pi_statement);
			
			//start inserting performance indicator
			for ($j = 0; $j < $pi_statement_size; $j++) {
				$insert_pi = 'INSERT INTO performance_indicator (pi_statement, po_id) 
							  VALUES ("' . $add_pi_statement[$j] . '", "' . $po_id . '")';
				$after_pi_insert = $this->db->query($insert_pi);
			}
		}
		
		$pi_array_size = sizeof($delete_pi_array);
		
        for($del_counter = 0; $del_counter < $pi_array_size; $del_counter++) {
            $this->db->where('pi_id', $delete_pi_array[$del_counter])
                     ->delete('performance_indicator');
        }
		
		return true;
	}
	
	/**
	 * Function to insert and/or update performance indicators
	 * @parameters: program outcome id, add/edit counter size, pi statements, add/edit counter value, performance indicator id
	 * @return: performance indicator details
	 */
	public function insert_update_msr($pi_id, $add_msr_statement, $add_edit_msr_statement, $msr_id) {
		//check if performance indicator id exists
		if(!empty($msr_id)) {
			//update existing measures or add new measures to the existing ones
			//fetch all measures related to that performance indicator id
			$all_msr_details = 'SELECT msr_id
								FROM measures
								WHERE pi_id = "' . $pi_id . '"';
			$all_msr_data = $this->db->query($all_msr_details);
			$all_msr = $all_msr_data->result_array();
			
			//insert pi id in an array
			foreach ($all_msr as $current_msr_id) {
				$present_msr_id_array[] = $current_msr_id['msr_id'];
			}
			
			//remove performance indicators from the pi table
			$delete_msr_array = array_values(array_diff($present_msr_id_array, $msr_id));
			$msr_edit_statement_size = sizeof($add_edit_msr_statement);
				
			for ($k = 0; $k < $msr_edit_statement_size; $k++) {
				if(!empty($msr_id[$k])) {
					//if measure id exist update
					$msr_data = array(
							'msr_statement' => $add_edit_msr_statement[$k],
						);
					
					$this->db->where('msr_id', $msr_id[$k])
							 ->update('measures', $msr_data);
				} else {
					//if measure id does not exist, then insert
					$msr_data = array(
							'pi_id' => $pi_id,
							'msr_statement' => $add_edit_msr_statement[$k],
						);
					
					$this->db->insert('measures', $msr_data);
				}
			} 
		} else {
			//insert new measure(s) - adding measures for the first time
			$msr_statement_size = sizeof($add_msr_statement);
			
			//start inserting measures
			for ($j = 0; $j < $msr_statement_size; $j++) {
				$insert_msr = 'INSERT INTO measures (msr_statement, pi_id) 
							  VALUES ("' . $add_msr_statement[$j] . '", "' . $pi_id . '")';
				$after_msr_insert = $this->db->query($insert_msr);
			}
		}
		
		$msr_array_size = sizeof($delete_msr_array);
		
        for($del_counter = 0; $del_counter < $msr_array_size; $del_counter++) {
            $this->db->where('msr_id', $delete_msr_array[$del_counter])
                     ->delete('measures');
        }
		
		return true;
	}
	
	/**
	 * Function to fetch curriculum details from curriculum table for Approver
	 * @return: curriculum details
	 */
    function approver_list_curriculum() {
        if ($this->ion_auth->is_admin()) {
            $curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name 
								FROM curriculum AS c, dashboard AS d
								WHERE d.entity_id = 20
									AND d.status = 1
									ORDER BY c.crclm_name ASC ';
        } else {
            $department_id = $this->ion_auth->user()->row()->user_dept_id;
            $curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name 
								FROM curriculum AS c, program AS p, dashboard AS d
								WHERE d.crclm_id = c.crclm_id
									AND p.pgm_id = c.pgm_id
									AND d.entity_id = 20
									AND d.status = 1
									ORDER BY c.crclm_name ASC';
        }
        $curriculum_list = $this->db->query($curriculum_list);
        $curriculum_list = $curriculum_list->result_array();

        return $curriculum_list;
    }
	
	/**
	 * Function to insert comment in the comment table
	 * @parameters: program outcome id, curriculum id, performance indicator comment and comment status
	 * @return: boolean
	 */
    public function comment_insert($po_id, $curriculum_id, $pi_comment, $comment_status) {
        $select_query = 'SELECT entity_id 
						 FROM entity 
						 WHERE entity_name = "pi_measures"';
        $entity_id = $this->db->query($select_query);
        $entity_id_data = $entity_id->result_array();

        $entity_id = $entity_id_data[0]['entity_id'];
        $comment_add = array(
            'entity_id' => $entity_id,
            'actual_id' => $po_id,
            'po_id' => $po_id,
            'crclm_id' => $curriculum_id,
            'cmt_statement' => $pi_comment,
            'commented_by' => $this->ion_auth->user()->row()->id,
            'in_reply_to' => '0',
            'created_by' => $this->ion_auth->user()->row()->id,
            'created_date' => date('Y-m-d'),
            'status' => $comment_status,
        );

        $this->db->insert('comment', $comment_add);
		return true;
    }
	
	/**
	 * Function to publish program outcome along with its corresponding performance indicators and measures by updating the dashboard
	 * @parameters: curriculum id
	 * @return: curriculum id, entity id, particular id, state, status, sender id, receiver id, url & description
	 */
    function approve_publish_db($curriculum_id) {	
		$update = 'UPDATE dashboard
				   SET status = 0 
				   WHERE crclm_id = "' . $curriculum_id . '" 
					AND entity_id = 20
					AND particular_id = "' . $curriculum_id . '" 
					AND status = 1';
		$update_dashboard = $this->db->query($update);	
	
		$select_user_id = 'SELECT approver_id 
						   FROM peo_po_approver 
						   WHERE entity_id = 13 
							AND crclm_id = "' . $curriculum_id . '"';
		$select_user_id = $this->db->query($select_user_id);
		$select_user_id = $select_user_id->result_array();
		$receiver_id = $select_user_id[0]['approver_id'];

		$data['receiver_id'] = $receiver_id;
		$url = base_url('curriculum/pi_and_measures/approve_page/' . $curriculum_id);
		$dashboard_data = array(
				'crclm_id' => $curriculum_id,
				'entity_id' => '20',
				'particular_id' => $curriculum_id,
				'state' => '5',
				'status' => '1',
				'sender_id' => $this->ion_auth->user()->row()->id,
				'receiver_id' => $receiver_id,
				'url' => $url,
				'description' => $this->lang->line('outcome_element_plu_full').' & its Performance Indicators(PIs) are sent for approval.'
			);
		
		$this->db->insert('dashboard', $dashboard_data);
		
        return $dashboard_data;
    }
	
	/**
	 * Function to perform rework process by updating the dashboard details
	 * @return: curriculum id, entity id, particular id, state, status, sender id, receiver id, url & description
	 */
    function rework_publish_db($curriculum_id) {
        $update_dashboard_data = array(
            'status' => '0'
        );
		
        $this->db
			 ->where('crclm_id', $curriculum_id)
			 ->where('entity_id', 20)
			 ->where('particular_id', $curriculum_id)
			 ->where('status', 1)
			 ->update('dashboard', $update_dashboard_data);

        $select_user_id = 'SELECT crclm_owner 
						   FROM curriculum 
						   WHERE crclm_id = "' . $curriculum_id . '"';
        $select_user_id = $this->db->query($select_user_id);
		$select_user_id = $select_user_id->result_array();
        $receiver_id = $select_user_id[0]['crclm_owner'];

        $data['receiver_id'] = $receiver_id;
        $url = base_url('curriculum/pi_and_measures/rework_page/' . $curriculum_id);
        $dashboard_data = array(
            'crclm_id' => $curriculum_id,
            'entity_id' => '20',
            'particular_id' => $curriculum_id,
            'state' => '6',
            'status' => '1',
            'sender_id' => $this->ion_auth->user()->row()->id,
            'receiver_id' => $receiver_id,
            'url' => $url,
            'description' => $this->lang->line('outcome_element_plu_full').' & its Performance Indicators(PIs) are sent back for approval rework.'
        );
        $this->db->insert('dashboard', $dashboard_data);

        return $dashboard_data;
    }
	
	/**
	 * Function to perform accept process by updating the dashboard details
	 * @parameters: curriculum id
	 * @return: curriculum id, entity id, particular id, state, status, sender id, receiver id, url & description
	 */
    function accept_publish_db($curriculum_id) {
		$loggedin_user_id = $this->ion_auth->user()->row()->id;
		$created_date = date('Y-m-d');
		
		$update = 'UPDATE dashboard 
				   SET status = 0 
				   WHERE crclm_id = "' . $curriculum_id . '" 
					AND entity_id = 20 
					AND particular_id = "' . $curriculum_id . '" 
					AND status = 1';
		$update_dashboard = $this->db->query($update);		   
		
		$curriculum_list = 'SELECT crclm_owner 
						    FROM curriculum 
						    WHERE crclm_id = "' . $curriculum_id . '"';
		$curriculum_list = $this->db->query($curriculum_list);
		$curriculum_list = $curriculum_list->result_array();
		
		$receiver_id = $curriculum_list[0]['crclm_owner'];
		$description = $this->lang->line('outcome_element_plu_full').' & its PIs are Approved. Proceed with creation of all of courses.';
		$state_id = 7;
		$url = base_url('curriculum/course/add_course').'/'. $curriculum_id;
		
		$insert_into_dashboard = 'INSERT INTO dashboard(crclm_id, entity_id, particular_id, 
									sender_id, receiver_id, url, description, state, status)
								  VALUES("' . $curriculum_id . '", 20, "' . $curriculum_id . '", "' . $loggedin_user_id . '",
									"' . $receiver_id . '", "' . $url . '", "' . $description . '", "' . $state_id . '", 1)';
		$insert_dashboard = $this->db->query($insert_into_dashboard);
		
		$state_id = 1;
		$description = $this->lang->line('outcome_element_plu_full').' & its PIs are Approved. Proceed with creation of Courses.';
		$insert_po_peo_data_into_dashboard = 'INSERT INTO dashboard(crclm_id, entity_id, particular_id,
												sender_id, receiver_id, url, description, state, status)
											  VALUES("' . $curriculum_id . '", 4, "' . $curriculum_id . '", "' . $loggedin_user_id . '",
												"' . $receiver_id . '", "' . $url . '", "' . $description . '", "' . 
												$state_id . '", 1)';
		$insert_po_peo_dashboard = $this->db->query($insert_po_peo_data_into_dashboard);
		
		$data['entity_id'] = 20;
		$data['receiver_id'] = $receiver_id;
		$data['state'] = 7;
        $data['url'] = $url;
		
		return $data;
    }

	/*Function to fetch the pi related measures count
	  author: Mritunjay BS 
	  Date : 15-Jan-2014
	*/
	public function fetch_measures_count($crclm_id) {
		$pi_query = 'SELECT po.po_id, po.po_statement, pi.pi_id, pi.pi_statement
					 FROM po LEFT JOIN performance_indicator as pi 
					 ON po.po_id = pi.po_id 
					 LEFT JOIN measures as msr
					 ON pi.pi_id = msr.pi_id 
					 WHERE po.crclm_id = "' . $crclm_id . '" 
						AND msr.msr_id IS NULL';
		$pi_data = $this->db->query($pi_query);
		$pi_result = $pi_data->result_array();
		
		if(!empty($pi_result)) {
			$table= "<table class='table table-bordered table-hover' style='padding:3px; font-size:12px;'>
						<th><font color='#008000'>".$this->lang->line('so')." Statement</font></th>
						<th><font color='#0404B4'>".$this->lang->line('outcome_element_sing_short')." Statement</font></th>
						<th><font color='#800000'>PI Statement</font></th>
						<tbody>";
			
			foreach($pi_result as $pi) {
				if($pi['pi_id'] != NULL) {
					$msr_query = 'SELECT msr_id
								  FROM measures 
								  WHERE pi_id = "' . $pi['pi_id'] . '"';
					$msr_data = $this->db->query($msr_query);
					$msr_result = $msr_data->num_rows();
					
					if($msr_result == NULL) {
						$table.="<tr>";
						$table.="<td colspan='3'><font color='#008000'>".$pi['po_statement']."</font></td>";
						$table.="</tr>";
						$table.="<tr><td></td>";
						$table.="<td colspan='2'><font color='#0404B4'>".$pi['pi_statement']."</font></td></tr>";
						$table.="<tr><td></td><td></td>";
						$table.="<td><font color='#B40404'>No Performance Indicator(s) Present for this ".$this->lang->line('outcome_element_sing_full')."</font></td></tr>";
					} else {
						$pi_result = 0;
						echo $pi_result;
					}
				} else {
					$table.="<tr>";
					$table.="<td colspan='3'><font color='#008000'>".$pi['po_statement']."</font></td>";
					$table.="</tr>";
					$table.="<tr><td></td>";
					$table.="<td colspan='2'><font color='#B40404'>No ".$this->lang->line('outcome_element_sing_full')." is present for this ".$this->lang->line('so')."</font></td></tr>";
				}
			}
			
			$table .="</tbody></table>";
			echo $table;
		} else {
			$pi_result = 0;
			echo $pi_result;
		}
	}
	/**
	 * Function to fetch help related details for pi and measures, which is
	   used to provide link to help page
	 * @return: serial number (help id), entity data, help description, help entity id and file path
	 */
	public function pi_and_measures_help() {
        $help = 'SELECT serial_no, entity_data, help_desc 
				 FROM help_content 
				 WHERE entity_id = 20';
        $result = $this->db->query($help);
        $row = $result->result_array();
        $data['help_data'] = $row;
		
		if(!empty($data['help_data'])) {
			$help_entity_id = $row[0]['serial_no'];
		
			$file_query = 'SELECT help_entity_id, file_path
						   FROM uploads 
						   WHERE help_entity_id = "' . $help_entity_id . '"';
			$file_data = $this->db->query($file_query);
			$file_name = $file_data->result_array();
			$data['file'] = $file_name;
			
			return $data;
		} else {
			return $data;
		}
    }

	/**
	 * Function to fetch help related to pi and measures to display
	   the help contents in a new window
	 * @parameters: help id
	 * @return: entity data and help description
	 */
    public function help_content($help_id) {
        $help = 'SELECT entity_data, help_desc 
				 FROM help_content 
				 WHERE serial_no = "' . $help_id.'"';
        $result_help = $this->db->query($help);
        $row = $result_help->result_array();

        return $row;
    }
	
	/**
	 * Function to update pi codes in the measures table for OE & PIs
	 * @parameters: pi codes & measures id
	 * @return: boolean
	 */
    public function update_pi_codes($pi_codes, $msr_id) {
        $update = 'UPDATE measures
				   SET pi_codes = "' . $pi_codes . '"
				   WHERE msr_id = "' . $msr_id . '"';
		$update_pi_codes = $this->db->query($update);

        return true;
    }
	
	/**
     * Function to fetch bos user details from 
     * @parameters: curriculum id
     * @return: bos user name
     */
	 public function bos_user($curriculum_id) {
		$bos_user_query = 'SELECT a.approver_id, u.title, u.first_name, u.last_name 
						   FROM peo_po_approver AS a, users AS u
						   WHERE crclm_id = "' . $curriculum_id . '" 
							AND a.approver_id = u.id ';
		$bos_user = $this->db->query($bos_user_query);
		$bos_user = $bos_user->result_array();
		
		return $bos_user;
	}
	
	/**
     * Function to fetch program owner user details from 
     * @parameters: curriculum id
     * @return: bos user name
     */
	 public function prog_user($curriculum_id) {
		$pgm_user_query = 'SELECT u.title, u.first_name, u.last_name 
						   FROM curriculum as c, users AS u
						   WHERE c.crclm_id = "' . $curriculum_id . '" 
							AND c.crclm_owner = u.id ';
		$pgm_user = $this->db->query($pgm_user_query);
		$pgm_owner = $pgm_user->result_array();		
		
		return $pgm_owner;
	}
	
	/* Function is used to fetch curriculum id from curriculum table
	* @parameters: curriculum id
	* @return: curriculum id
	*/	
	public function fetch_curriculum($curriculum_id) {
		$curriculum_id_query = 'SELECT crclm_name
								FROM curriculum
								WHERE crclm_id = "' . $curriculum_id . '"';
		$curriculum_id_data = $this->db->query($curriculum_id_query);
		$curriculum_data = $curriculum_id_data->result_array();
		
		return $curriculum_data;
	}
	
	/**
     * Function to inserts the comments notes(text) details onto the comment table
     * @parameters: curriculum id and notes(text) statement.
     * @return: boolean
     */
    public function pi_comment_save($curriculum_id, $po_id, $po_comment) {
		$delete_query = 'DELETE FROM comment 
						 WHERE crclm_id = "' . $curriculum_id . '" 
							AND po_id = "' . $po_id . '" 
							AND entity_id = 20 ';
		$query_result = $this->db->query($delete_query);
		$data_array = array(
			'entity_id' => 20,
			'po_id' => $po_id,
			'crclm_id' => $curriculum_id,
			'cmt_statement' => $po_comment,
		);
		
		$this->db->insert('comment', $data_array);
		return true;	
	}
	
	public function insert_pis($pi_statements , $po_id){
		$data_array = array(
			'pi_statement' => $pi_statements,
			'po_id' => $po_id,
			'created_by' => $this->ion_auth->user()->row()->id,
            'create_date' => date('Y-m-d'),
			'modified_by' => $this->ion_auth->user()->row()->id,
            'modify_date' => date('Y-m-d')
		);
		
		$this->db->insert('performance_indicator', $data_array);
		return true;	
	}	
	public function update_pis($pi_statement , $po_id ,$pi_id){
		$this->db->query('update performance_indicator set pi_statement="'.$pi_statement.'" where po_id="'.$po_id.'" and pi_id ="'.$pi_id.'" ');
		return true;
	}
	
	public function fetch_pis($po_id){
	
		$query = $this->db->query('select * from performance_indicator where po_id = "'.$po_id.'"');
		return $query->result_array();
	}
	
	public function delete_competencies($pi_id){
		$this->db->query('delete from performance_indicator where pi_id = "'.$pi_id.'" ');
		return true;
	}
	
	public function save_measures($pi_id ,$pi_stmt){
			$data_array = array(
			'msr_statement' => $pi_stmt,
			'pi_id' => $pi_id
		);
		
 		$this->db->insert('measures', $data_array);
		return true; 
	}

	public function fetch_measures_data($pi_id){
		$query = $this->db->query('select * from measures where pi_id = "'.$pi_id.'"');
		return $query->result_array();
	
	}	
	public function fetch_pi_statement($pi_id){
		$query = $this->db->query('SELECT * FROM performance_indicator p where pi_id = "'.$pi_id.'"');
		return $query->result_array();
	
	}
	
	public function delete_measures($measure_id){
		$this->db->query('delete from measures where msr_id = "'.$measure_id.'" ');
		return true;
	}
	
	
	public function update_measures($pi_id ,$pi_stmt ,$measure_edit_id){
			$data_array = array(
			'msr_statement' => $pi_stmt,			
			);
			
			$where_clause = array(
				'pi_id' => $pi_id,
				'msr_id' => $measure_edit_id
			);
 		$this->db->update('measures', $data_array , $where_clause);
		return true; 
	}

}

/*
 * End of file pi_and_measures_model.php
 * Location: .curriculum/pi_and_measures/pi_and_measures_model.php 
 */
?>
