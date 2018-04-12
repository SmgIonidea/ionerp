<?php
/**
* Description	:	Select activities that will elicit actions related to the verbs in the 
					course outcomes. 
					Select Curriculum and then select the related term (semester) which 
					will display related course. For each course related CLO's and PO's
					are displayed.
					Write comments if required.
					Send for approve on completion.
					
* Created		:	June 12th, 2013

* Author		:	
		  
* Modification History:
* 	Date                Modified By                			Description
  20/05/2014		  Arihant Prasad D			File header, function headers, indentation 
												and comments.
---------------------------------------------------------------------------------------------*/
?>

<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Clo_po_model extends CI_Model {

    public $clo_po_map = "clo_po_map";

    /*
     * Function to fetch all the curriculum details if the user is admin
     * @return: dashboard state and status
     */
    public function list_curriculum() {
        $curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name 
							FROM curriculum AS c, dashboard AS d 
							WHERE d.crclm_id = c.crclm_id 
								AND entity_id = 14 
								AND d.status = 1
								AND c.status = 1
								ORDER BY c.crclm_name ASC';
        $curriculum_list = $this->db->query($curriculum_list);
        $curriculum_list = $curriculum_list->result_array();

        //dashboard details are fetched from the dashboard table
        $dashboard_query = 'SELECT state, status 
							FROM dashboard 
							WHERE entity_id = 16 AND status = 1';
        $dashboard_query_result = $this->db->query($dashboard_query);
        $dashboard_query_result = $dashboard_query_result->result_array();

        $curriculum_data['curriculum_list'] = $curriculum_list;
        $curriculum_data['dashboard_query_result'] = $dashboard_query_result;
		
        //replace this part of code with array_key_exists()
        if ($curriculum_data['dashboard_query_result']) {
            return $curriculum_data;
		} else {
            $curriculum_data['dashboard_query_result'] = 0;
            return $curriculum_data;
        }
    }

    /**
     * Function to fetch confined curriculum details if the logged in user is program owner
     * @parameters: user id and curriculum id
     * @return: curriculum id, curriculum name, state and status
     */
    public function clo_po($user_id, $curriculum_id) {
		$user_dept_id = $this->ion_auth->user()->row()->user_dept_id;
		
        $curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name, c.pgm_id, p.pgm_id, p.dept_id, dp.dept_id 
							FROM curriculum AS c, dashboard AS d, program AS p, department AS dp 
							WHERE d.crclm_id = c.crclm_id 
								AND c.pgm_id = p.pgm_id
								AND p.dept_id = "' . $user_dept_id . '"
								AND dp.dept_id = "' . $user_dept_id . '"
								AND d.entity_id = 14 
								AND d.status = 1
								AND c.status = 1
								ORDER BY c.crclm_name ASC';
        $curriculum_list = $this->db->query($curriculum_list);
        $curriculum_list = $curriculum_list->result_array();
		
        if ($curriculum_id) {
            // Fetch dashboard details from the dashboard table
            $dashboard_query = 'SELECT state 
								FROM dashboard 
								WHERE crclm_id = "' . $curriculum_id . '" 
									AND entity_id = 16 
									AND status = 1 
									AND state = 4';
            $dashboard_query_result = $this->db->query($dashboard_query);
            $dashboard_query_result = $dashboard_query_result->result_array();

            $curriculum_data['curriculum_list'] = $curriculum_list;
            $curriculum_data['dashboard_query_result'] = $dashboard_query_result;

			//replace this part of code with array_key_exists()
            if ($curriculum_data['dashboard_query_result']) {
                return $curriculum_data;
			} else {
                $curriculum_data['dashboard_query_result'] = 0;
                return $curriculum_data;
            }
        } else {
            // Fetch dashboard details from the dashboard table
            $dashboard_query = 'SELECT state, status 
								FROM dashboard 
								WHERE entity_id = 16 AND status = 1';
            $dashboard_query_result = $this->db->query($dashboard_query);
            $dashboard_query_result = $dashboard_query_result->result_array();

            $curriculum_data['curriculum_list'] = $curriculum_list;
            $curriculum_data['dashboard_query_result'] = $dashboard_query_result;

			//replace this part of code with array_key_exists()            
            if ($curriculum_data['dashboard_query_result']) {
                return $curriculum_data;
			} else {
                $curriculum_data['dashboard_query_result'] = 0;
                return 0;
            }
        }
    }

    /** 
	 * Function to fetch term details using curriculum id from curriculum terms table
	 * @parameters: curriculum id
	 * @return: term id and term name
	 */
    public function clo_po_select($curriculum_id) {
        $term_name = 'SELECT term_name, crclm_term_id 
					  FROM crclm_terms 
					  WHERE crclm_id = "' . $curriculum_id . '"';
        $term_name_result = $this->db->query($term_name);
        $term_name_result = $term_name_result->result_array();
        $term_data['term_name_result'] = $term_name_result;

        return $term_data;
    }

    /** 
	 * Function to fetch course outcomes details that are mapped
	 * @parameters: curriculum id and term id
	 * @return: curriculum id, course id, course title, course code, course outcome id,
	  			course outcome statements, program outcome id, program outcome statements,
				course outcome mapped to program outcome id, comments, entity id,
				particular id, state and status
	 */
    public function clo_details($curriculum_id, $term_id) {
        $data['curriculum_id'] = $curriculum_id;

        // Fetch course details from the course table
        $course_query = 'SELECT c.crclm_id, c.crs_id, c.crs_title, c.crs_code, c.state_id, 
							o.crs_id, o.clo_owner_id, u.first_name, u.last_name
						 FROM course AS c, course_clo_owner AS o, users AS u
						 WHERE c.crclm_id = "' . $curriculum_id . '" 
							AND c.crclm_term_id = "' . $term_id . '" 
							AND c.status = 1
							AND c.state_id <= 6 
							AND o.crs_id = c.crs_id
							AND u.id = o.clo_owner_id
							ORDER BY c.crs_title ASC';
        $course_list = $this->db->query($course_query);
        $course_list_data = $course_list->result_array();
        $data['course_list'] = $course_list_data;
        $size = sizeof($data['course_list']);
			
		$term_course_count_details = 'SELECT count(crs_id) AS term_course_count
									  FROM course 
									  WHERE crclm_id = "' . $curriculum_id . '" 
										AND crclm_term_id = "' . $term_id . '" 
										AND status = 1';
        $term_course_count_data = $this->db->query($term_course_count_details);
        $term_course_count = $term_course_count_data->result_array();
			
        // Fetch course outcome details from the course outcome table
        $clo = 'SELECT clo.clo_id, clo.crclm_id, clo.crs_id, clo.clo_statement, clo.crs_id, c.crs_id, c.state_id
				FROM clo, course AS c
				WHERE clo.crclm_id = "' . $curriculum_id . '" 
					AND clo.term_id = "' . $term_id . '"
					AND clo.crs_id = c.crs_id
					AND c.state_id = 4';
        $clo_list = $this->db->query($clo);
        $clo_list = $clo_list->result_array();
        $data['clo_list'] = $clo_list;
		
        // Fetch program outcome details from the program outcome table
        $po = 'SELECT po_id, crclm_id, po_statement, po_reference
			   FROM po 
			   WHERE crclm_id = "' . $curriculum_id . '"
			   ORDER BY LENGTH(po_reference), po_reference';
        $po_list = $this->db->query($po);
        $po_list = $po_list->result_array();
        $data['po_list'] = $po_list;

        /**
		 * Fetch course outcomes mapped to program outcome details from course 
		   outcomes to program outcome mapping table
		 */
        $clo_po_map = 'SELECT DISTINCT clo_id, po_id, crclm_id, map_level
					   FROM clo_po_map 
					   WHERE crclm_id = "' . $curriculum_id . '"';
        $clo_po_map_list = $this->db->query($clo_po_map);
        $map_list = $clo_po_map_list->result_array();
        $data['map_list'] = $map_list;
		
		$dashboard_state_details = 'SELECT DISTINCT crclm_id, entity_id, particular_id, state, status 
									FROM dashboard 
									WHERE particular_id = "' . $term_id . '"
										AND entity_id = 14 
										AND crclm_id = "' . $curriculum_id . '"
										AND status = 1';
		$dashboard_state_result = $this->db->query($dashboard_state_details);
		$dashboard_state = $dashboard_state_result->result_array();
		
		if ($dashboard_state) {
			$data['dashboard_state_result'] = $dashboard_state;
		} else {
            $data['dashboard_state_result'] = 0;
        }
        
		return $data;
    }

    /** 
	 * Function to fetch text (notes) to be displayed from the notes table
	 * @parameters: curriculum id and term id
	 * @return: text (notes)
	 */
    public function text_details($curriculum_id, $term_id) {
        $notes = 'SELECT notes 
				  FROM notes 
				  WHERE crclm_id = "' . $curriculum_id . '" AND term_id = "' . $term_id . '" AND entity_id = 14';
        $notes = $this->db->query($notes);
        $notes = $notes->result_array();
		
        if (isset($notes[0]['notes'])) {
            header('Content-Type: application/x-json; charset=utf-8');
            echo(json_encode($notes[0]['notes']));
        } else {
            header('Content-Type: application/x-json; charset=utf-8');
            $temp = 'Enter text here...';
            echo(json_encode($temp));
        }
    }
	
	/** 
	 * Function to save/insert the mapped values along with its corresponding performance indicators
	   and its measures into course outcome mapped to program outcome table
	 * @parameters: curriculum id, program outcome id, course outcome id, course, 
					performance indicators and measures
	 * @return: boolean
	 */
	    public function oncheck_save_db($crclmid, $po_id, $clo_id, $pi, $msr, $crsid, $map_level ) {
			$map_del_query = 'DELETE FROM clo_po_map 
							  WHERE po_id = "'.$po_id.'" AND clo_id = "'.$clo_id.'" ';
			$map_del_result = $this->db->query($map_del_query);
		
        $pi_id = sizeof($pi);
		
        $msr_id = sizeof($msr);
        for ($j = 0; $j < $pi_id; $j++) {
		
            $count = 0;
            for ($m = 0; $m < $msr_id; $m++) {
                $pi_msr_query = 'SELECT msr_id 
								 FROM measures 
								 WHERE pi_id = "' . $pi[$j] . '" AND msr_id = "' . $msr[$m] . '" ';
                $pi_msr_data = $this->db->query($pi_msr_query);
                $pi_msr_result = $pi_msr_data->result_array();
				
                if ($pi_msr_result) {
                    $count++;
                    $pimsr[$count] = $pi_msr_result;
                }
            }
			
			$po_id_count_query  = ' SELECT po_id FROM clo_po_map WHERE po_id= "'.$po_id.'" AND clo_id ="'.$clo_id.'"' ;
			$po_id_count_data 	= $this->db->query($po_id_count_query);
            $po_id_count 	= $po_id_count_data->result_array();
			
			
			if(empty($po_id_count)){
            for ($k = 1; $k <= $count; $k++) {
                $add_clo_po_map = array(
                    'clo_id' 	=> $clo_id,
                    'po_id' 	=> $po_id,
                    'crclm_id' 	=> $crclmid,
                    'crs_id' 	=> $crsid,
                    'pi_id' 	=> $pi[$j],
                    'msr_id' 	=> $pimsr[$k][0]['msr_id'],
                    'map_level' 	=> $map_level,
                    'created_by' 	=> $this->ion_auth->user()->row()->id,
                    'create_date' 	=> date('Y-m-d') );

                $this->db->insert('clo_po_map', $add_clo_po_map);
				}
			}
			else{
			
			for ($k = 1; $k <= $count; $k++) {
                $add_clo_po_map = array(
                    'clo_id' 	=> $clo_id,
                    'po_id' 	=> $po_id,
                    'crclm_id' 	=> $crclmid,
                    'crs_id' 	=> $crsid,
                    'pi_id' 	=> $pi[$j],
                    'msr_id' 	=> $pimsr[$k][0]['msr_id'],
                    'map_level' 	=> $map_level,
                    'created_by' 	=> $this->ion_auth->user()->row()->id,
                    'create_date' 	=> date('Y-m-d') );

                $this->db->insert('clo_po_map', $add_clo_po_map);
				}
			}
        }
		
        return true;
		
    }

	/** 
	 * Function to fetch performance indicators statements and its corresponding measures from measures table
	 * @parameters: program outcome id
	 * @return: performance indicator id, measure id, performance indicator statements and 
				measure statements
	 */
    public function pi_select($po_id,$clo_id,$map_level,$crs_id,$crclm_id) {
        $pi_measure = $this->db->SELECT('msr_id, msr_statement, pi_codes, performance_indicator.pi_id, pi_statement')
							   ->JOIN('performance_indicator', 'performance_indicator.pi_id = measures.pi_id')
							   ->WHERE('performance_indicator.po_id', $po_id)
							   ->GET('measures')
							   ->result_array();

        if(empty($pi_measure)) {
			// OE & PIs optional
			// Delete earlier entry of po_id onto table clo_po_map
			$query = ' DELETE FROM clo_po_map 
						WHERE clo_id = "'.$clo_id.'" 
						AND po_id = "'.$po_id.'" ';
			$result = $this->db->query($query);
			// Insert only po_id onto table clo_po_map
			$add_clo_po_map = array(
								'clo_id' 	=> $clo_id,
								'po_id' 	=> $po_id,
								'crclm_id' 	=> $crclm_id,
								'crs_id' 	=> $crs_id,
								'map_level' 	=> $map_level,
								'created_by' 	=> $this->ion_auth->user()->row()->id,
								'create_date' 	=> date('Y-m-d') 
							);
			$this->db->insert('clo_po_map', $add_clo_po_map);
			return false;
		} else {
			return $pi_measure;
		}
    }

	/** 
	 * Function to unmap course outcome mapped to program outcomes from course outcome to program outcome map table
	 * @parameters: program outcome id, course outcome id
	 * @return: boolean
	 */
    public function unmap_db($po_id, $clo_id) {
        $mapping_delete = 'DELETE FROM clo_po_map 
						   WHERE po_id = "' . $po_id . '" AND clo_id = "' . $clo_id . '"';
        $mapping_delete_result = $this->db->query($mapping_delete);
    }

	/** 
	 * Function to insert curriculum id, entity id and state id into workflow history table
	 * @parameters: curriculum id
	 * @return: boolean
	 */
    public function approve_db($curriculum_id) {
        $map_approve = 'INSERT INTO workflow_history(entity_id, crclm_id, state_id) 
						VALUES (14, "' . $curriculum_id . '", 5)';
        $approve = $this->db->query($map_approve);
    }

    /** 
	 * Function to update notes (text) details to notes table
	 * @parameters: curriculum id, term id, text (notes)
	 * @return: boolean
	 */
    public function save_txt_db($curriculum_id, $term_id, $text) {
        $notes_query = $this->db
					  ->select('notes_id')
					  ->where('crclm_id', $curriculum_id)
					  ->where('term_id', $term_id)
					  ->where('entity_id', '14')
					  ->get('notes');

        $count = $notes_query->num_rows();
        $data = array(
            'notes' => $text,
            'crclm_id' => $curriculum_id,
            'term_id' => $term_id,
            'entity_id' => 14
        );

        if ($count > 0) {
            $notes_query = 'UPDATE notes 
							SET crclm_id = "' . $curriculum_id . '", term_id = "' . $term_id . '", notes = "' . $text . '" 
							WHERE crclm_id = "' . $curriculum_id . '" AND term_id = "' . $term_id . '" AND entity_id = 14';
            $notes_query = $this->db->query($notes_query);
        }

        if ($count == 0) {
            $this->db->insert('notes', $data);
        }
    }

    /** 
	 * Function to fetch help details from help table
	 * @return: serial number (help id), entity data and help description
	 */
    public function clo_po_help() {
        $help = 'SELECT serial_no, entity_data, help_desc
				 FROM help_content 
				 WHERE entity_id = 14';
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
	 * Function to fetch help related to course outcomes to program outcomes to display
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
	 * Function to fetch approver details from course clo approver table and user table
	 * @parameters: curriculum id
	 * @return: approver id
	 */
    public function clo_po_approver($curriculum_id) {
        $clo_po_approver_name = 'SELECT c.approver_id, u.username 
								 FROM course_clo_approver AS c, users AS u 
								 WHERE c.crclm_id = "' . $curriculum_id . '" 
									AND c.approver_id = u.id';
        $clo_po_approver_name_result = $this->db->query($clo_po_approver_name);
        $clo_po_approver_name_result = $clo_po_approver_name_result->result_array();
        $approver_id = $clo_po_approver_name_result[0]['approver_id'];
		
        return $approver_id;
    }
	
	/**
	 * Function to fetch saved performance indicator and measures from clo po map table and 
	   performance indicator & measures table respectively
	 * @parameters: curriculum id, term id, course id, course outcomes id and program outcomes id
	 * @return: performance indicator and measure statements
	 */
	public function modal_display_pm_model($curriculum_id, $term_id, $clo_id, $po_id) {
		$clo_po_map_pm = 'SELECT DISTINCT cpm.pi_id, cpm.msr_id, pi.pi_id, pi.pi_statement, msr.msr_id, msr.msr_statement, msr.pi_codes
						  FROM clo_po_map AS cpm, performance_indicator AS pi, measures AS msr
						  WHERE cpm.crclm_id = "'.$curriculum_id.'"
							AND cpm.clo_id = "'.$clo_id.'"
							AND cpm.po_id = "'.$po_id.'"
							AND cpm.pi_id = pi.pi_id
							AND cpm.msr_id = msr.msr_id';
        $clo_po_map_list_pm = $this->db->query($clo_po_map_pm);
        $map_list_pm = $clo_po_map_list_pm->result_array();
        $data['map_list_pm'] = $map_list_pm;
		
		return $data;
	}

    /** 
	 * Function to fetch course details from course table, to update dashboard table and to send mail
	 * @parameters: curriculum id, term id and approver id
	 * @return: curriculum id, approver id, entity id, url and state
	 */
    public function dashboard_data_send_approval($curriculum_id, $term_id, $approver_id) {
		$term_data_query = 'SELECT term_name 
						    FROM crclm_terms 
						    WHERE crclm_term_id = "'.$term_id.'" ';
        $term_data = $this->db->query($term_data_query);
        $term_result = $term_data->result_array();
		
		$update_query = 'UPDATE dashboard 
						 SET status = 0 
						 WHERE particular_id = "' . $term_id . '" 
							AND crclm_id = "' . $curriculum_id . '" 
							AND entity_id = 14 
							AND status = 1';
        $update_data = $this->db->query($update_query);
			
		$course = 'SELECT c.crs_id , c.crs_title, o.clo_owner_id
				   FROM course AS c, course_clo_owner AS o
				   WHERE c.crclm_id = "' . $curriculum_id . '" 
						AND c.crclm_term_id = "' . $term_id . '" 
						AND c.crs_id = o.crs_id 
						AND c.state_id = 4 
						AND c.status = 1 
						ORDER BY c.crs_title ASC';
        $course_result = $this->db->query($course);
        $course_result = $course_result->result_array();
        $size = sizeof($course_result);

		//BOS status (on approval rework state = 6) updation
		$bos_update_query = 'UPDATE dashboard 
							 SET status = 0
							 WHERE crclm_id = "' . $curriculum_id . '" 
								AND particular_id = "' . $term_id . '"
								AND entity_id = 14
								AND receiver_id = "' . $approver_id . '"';
		$bos_update_data = $this->db->query($bos_update_query);
		
        for ($i = 0; $i < $size; $i++) {
            $crs_id = $course_result[$i]['crs_id'];
			$course_owner = $course_result[$i]['clo_owner_id'];
			//In Course-Owners dashboard
            $update_query = 'UPDATE dashboard 
							 SET status = 0
							 WHERE particular_id = "' . $crs_id . '" 
								AND crclm_id = "' . $curriculum_id . '" 
								AND entity_id = 16 ';
            $update_data = $this->db->query($update_query);
			
			//In Program-Owners dashboard
			$update_query = 'UPDATE dashboard 
							 SET status = 0
							 WHERE particular_id = "' . $crs_id . '" 
								AND crclm_id = "' . $curriculum_id . '" 
								AND entity_id = 14 ';
            $update_data = $this->db->query($update_query);
			
			$dashboard_data = array(
								'crclm_id' => $curriculum_id,
								'particular_id' => $crs_id,
								'sender_id' => $this->ion_auth->logged_in(),
								'receiver_id' => $course_owner,
								'entity_id' => '16',
								'url' => '#',
								'description' =>$term_result[0]['term_name'].': COs to '.$this->lang->line('sos').' Mapping is Sent for Approval.',
								'state' => '5',
								'status' => '1',
							);

			$this->db->insert('dashboard', $dashboard_data);
			
			$course_update_query = 'UPDATE course 
								    SET state_id = 5
								    WHERE crs_id = "' . $crs_id . '" 
										AND crclm_id = "' . $curriculum_id . '" ';
			$course_update_data = $this->db->query($course_update_query);
			
        }
		
		$url = base_url('curriculum/clo_po_approve_comment/index/' . $curriculum_id . '/' . $term_id);
        $dashboard_data = array(
								'crclm_id' => $curriculum_id,
								'particular_id' => $term_id,
								'sender_id' => $this->ion_auth->logged_in(),
								'receiver_id' => $approver_id,
								'entity_id' => '14',
								'url' => $url,
								'description' =>$term_result[0]['term_name'].': Mapping between COs and '.$this->lang->line('sos').'(termwise) is sent for approval.',
								'state' => '5',
								'status' => '1',
							);

        $this->db->insert('dashboard', $dashboard_data);

        //email
        $dashboard_data = array(
            'crclm_id' => $curriculum_id,
            'receiver_id' => $approver_id,
            'entity_id' => '14',
            'url' => $url,
            'state' => '5',
        );

        return ($dashboard_data);
    }
	
	/**
	 * Function to fetch course outcome to program outcome owner details from course clo
	   owner table, to fetch course details from course table, to update dashboard table and to fetch
	   approver details and user details from course clo approver table to send mail on approval
	 * @parameters: curriculum id and term id
	 * @return: course outcome owner id, course id, course title, approver id and user name
	 */
    public function skip_bos_approval_accept($curriculum_id, $term_id) {
		$select_user_id = 'SELECT crclm_owner 
						   FROM curriculum 
						   WHERE crclm_id = "' . $curriculum_id . '"';
        $select_user_id = $this->db->query($select_user_id);
		$select_user_id = $select_user_id->result_array();
        $receiver_id = $select_user_id[0]['crclm_owner'];
		
		$term_name_data = 'SELECT term_name
						   FROM crclm_terms
						   WHERE crclm_term_id = "' . $term_id . '"';
        $term_name_details = $this->db->query($term_name_data);
		$term_name_result = $term_name_details->result_array();
		$term_name = $term_name_result[0]['term_name'];
		
        $course_title = 'SELECT crs_id, crs_title 
						 FROM course 
						 WHERE crclm_id = "' .  $curriculum_id . '"
							AND crclm_term_id = "' . $term_id . '"
							AND status = 1
							AND state_id >= 4
							ORDER BY crs_title ASC';
        $course_query = $this->db->query($course_title);
        $course_title_result = $course_query->result_array();
        $count = $course_query->num_rows();
		
		for ($i = 0; $i < $count; $i++) {
            $course_reviewer_name = 'SELECT c.clo_owner_id, u.username, u.id
									 FROM course_clo_owner AS c, users AS u 
									 WHERE c.crs_id = "' . $course_title_result[$i]['crs_id'] . '" 
										AND c.clo_owner_id = u.id';
            $course_reviewer_name_result = $this->db->query($course_reviewer_name);
            $course_reviewer[$i] = $course_reviewer_name_result->result_array();
		}
		
		$update_query = 'UPDATE dashboard 
						 SET status = 0 
						 WHERE particular_id = "' . $term_id . '" 
							AND crclm_id = "' . $curriculum_id . '" 
							AND entity_id = 14 
							AND status = 1';
        $update_data = $this->db->query($update_query);
		
		// course owner notification
        for ($i = 0; $i < $count; $i++) {

			$update_query = 'UPDATE dashboard 
							 SET status = 0 
							 WHERE entity_id 
								IN (4,11,14,16)
								AND crclm_id = "' . $curriculum_id . '" 
								AND particular_id = "' . $course_title_result[$i]['crs_id'] . '"  
								AND status = 1';
			$update_data = $this->db->query($update_query);
			
			$course_update_query = 'UPDATE course 
									SET state_id = 7
									WHERE crs_id = "' . $course_title_result[$i]['crs_id'] . '" 
										AND crclm_id = "' . $curriculum_id . '" ';
			$course_update_data = $this->db->query($course_update_query);
			
            $url = base_url('curriculum/topicadd/index/' . $curriculum_id . '/' . $term_id . '/' . $course_title_result[$i]['crs_id']);
            $dashboard_data = array(
                'crclm_id' => $curriculum_id,
                'particular_id' => $course_title_result[$i]['crs_id'],
                'sender_id' => $this->ion_auth->user()->row()->id,
                'receiver_id' => $course_reviewer[$i][0]['clo_owner_id'],
                'entity_id' => '10',
                'url' => $url,
                'description' => $term_name . ' - ' . $course_title_result[$i]['crs_title'] . ' - COs to '.$this->lang->line('sos').' Mapping is Approved, Proceed to Create Topics.',
                'state' => '1',
                'status' => '1',
            );
            $this->db->insert('dashboard', $dashboard_data);
					
        }
		
		//program owner notification after bos term-wise approval
		$program_owner_dashboard_data = array(
                'crclm_id' => $curriculum_id,
                'particular_id' => $term_id,
                'sender_id' => $this->ion_auth->user()->row()->id,
                'receiver_id' => $receiver_id,
                'entity_id' => '14',
                'url' => '#',
                'description' => $term_name . ' - COs to '.$this->lang->line('sos').' Termwise Mapping is Approved.',
                'state' => '7',
                'status' => '1',
            );

        $this->db->insert('dashboard', $program_owner_dashboard_data);
		
		//program owner notification
		$program_owner_dashboard_data = array(
                'crclm_id' => $curriculum_id,
                'particular_id' => $term_id,
                'sender_id' => $this->ion_auth->user()->row()->id,
                'receiver_id' => $receiver_id,
                'entity_id' => '10',
                'url' => '#',
                'description' => $term_name . ' - COs to '.$this->lang->line('sos').' of Termwise Mapping is Approved, now each Course Owners will be able to add Topics & its respective .'.$this->lang->line('entity_tlo'),
                'state' => '1',
                'status' => '1',
            );

        $this->db->insert('dashboard', $program_owner_dashboard_data);

        //email
        $clo_po_receiver_name = 'SELECT o.clo_owner_id, u.username, o.crs_id, c.crs_id, c.crs_title
								 FROM course_clo_owner AS o, users AS u, course AS c
								 WHERE o.crclm_term_id = "' . $term_id . '"
									AND o.clo_owner_id = u.id
									AND o.crs_id = c.crs_id
									AND c.status = 1
									ORDER BY c.crs_title ASC';
        $clo_po_receiver_name_result = $this->db->query($clo_po_receiver_name);
        $clo_po_receiver_name_result = $clo_po_receiver_name_result->result_array();
        $count = sizeof($clo_po_receiver_name_result);

        for ($i = 0; $i < $count; $i++) {
            $url = base_url('curriculum/topicadd/index/' . $curriculum_id . '/' . $term_id . '/' . $course_title_result[$i]['crs_id']);
            $email_data[$i] = array(
                'crclm_id' => $curriculum_id,
                'receiver_id' => $clo_po_receiver_name_result[$i]['clo_owner_id'],
				'course_name' => $clo_po_receiver_name_result[$i]['crs_title'],
                'entity_id' => '14',
                'url' => $url,
                'state' => '7'
            );
        }
		
        return $email_data;
    }
	
	/*
     * Function to fetch the Term name from the term id.
     * @param term_id
     * @return Term Name
     */
    public function term_name_by_id($term_id){
    	$term_name = 'SELECT term_name 
					  FROM crclm_terms
					  WHERE crclm_term_id = "' . $term_id . '"';
		$term_name = $this->db->query($term_name);
        $term_name = $term_name->result_array();
        return $term_name[0]['term_name'];
    }
	
	/**
     * Function to fetch user details from 
     * @parameters: curriculum id
     * @return: bos user name
     */
	 public function bos_user($curriculum_id) {
		$bos_user_query = 'SELECT a.approver_id, u.title, u.first_name, u.last_name 
						   FROM course_clo_approver AS a, users AS u
						   WHERE crclm_id = "' . $curriculum_id . '" 
							AND a.approver_id = u.id ';
		$bos_user = $this->db->query($bos_user_query);
		$bos_user = $bos_user->result_array();
		
		return $bos_user;
	}
	
	/* Function is used to fetch curriculum id from curriculum table
	* @parameters: curriculum id
	* @return: curriculum id
	*/	
	public function fetch_curriculum($curriculum_id) {
		$curriculum_id_query = 'SELECT crclm_name
								FROM curriculum
								WHERE crclm_id = "' . $curriculum_id . '"
								ORDER BY crclm_name ASC';
		$curriculum_id_data = $this->db->query($curriculum_id_query);
		$curriculum_data = $curriculum_id_data->result_array();
		
		return $curriculum_data;
	}
	
	/**
	  * Function to fetch clo and po id from clo po map table
	  * @parameter: 
	  * @return: clo id and po id
	  */
	public function get_map_level_val($po_id, $clo_id){
		$map_level_query = 'SELECT map_level 
							FROM clo_po_map 
							WHERE clo_id = "'.$clo_id.'" 
								AND po_id = "'.$po_id.'" 
							Group By map_level ';
		$map_level_data = $this->db->query($map_level_query);
        $map_level_result = $map_level_data->result_array();
      
		if(!empty($map_level_result)) {
			return $map_level_result[0]['map_level'];
		} else {
			return 0;
		}
	}
	
	public function skip_approval_flag_fetch(){
	   return $this->db->select('skip_approval')
					   ->where('entity_id', 14)
						->get('entity')
						->result_array();
	
	}
}

/*
 * End of file clo_po_model.php
 * Location: .curriculum/map_clo_to_po/clo_po_model.php 
 */
?>