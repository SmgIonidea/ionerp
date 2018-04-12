<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Manage_model_qp extends CI_Controller {

	function __construct() {
		// Call the Model constructor
		parent::__construct();
		$this -> load -> model('question_paper/model_qp/manage_model_qp_model_re');
	}

	/**
	 * Function is to display the list view of QP Framework
	 * @return: list view of QP Framework
	 */
	public function index() {
		if (!$this -> ion_auth -> logged_in()) {
			//redirect them to the login page
			redirect('login', 'refresh');
		} elseif (!($this -> ion_auth -> is_admin() || $this -> ion_auth -> in_group('Chairman') || $this -> ion_auth -> in_group('Program Owner') || $this -> ion_auth -> in_group('Course Owner'))) {
			//redirect them to the home page because they must be an administrator or owner to view this
			redirect('curriculum/peo/blank', 'refresh');
		} else {
			$dept_list = $this -> manage_model_qp_model_re -> dept_fill();
			$data['dept_data'] = $dept_list['dept_result'];

			$data['title'] = "QP Framework List Page";
			$this -> load -> view('question_paper/model_qp/list_model_qp_vw', $data);
		}
	}

	public function tee_list_view() {
		if (!$this -> ion_auth -> logged_in()) {
			//redirect them to the login page
			redirect('login', 'refresh');
		} elseif (!($this -> ion_auth -> is_admin() || $this -> ion_auth -> in_group('Chairman') || $this -> ion_auth -> in_group('Program Owner') || $this -> ion_auth -> in_group('Course Owner'))) {
			//redirect them to the home page because they must be an administrator or owner to view this
			redirect('curriculum/peo/blank', 'refresh');
		} else {
			$dept_list = $this -> manage_model_qp_model_re -> dept_fill();
			$data['dept_data'] = $dept_list['dept_result'];

			$data['title'] = "QP Framework List Page";
			$this -> load -> view('question_paper/model_qp/list_tee_qp_vw', $data);
		}
	}

	/* Function is used to fetch program names from program table.
	 * @param-
	 * @returns - an object.
	 */
	public function select_pgm_list() {
		if (!$this -> ion_auth -> logged_in()) {
			//redirect them to the login page
			redirect('login', 'refresh');
		} elseif (!($this -> ion_auth -> is_admin() || $this -> ion_auth -> in_group('Chairman') || $this -> ion_auth -> in_group('Program Owner') || $this -> ion_auth -> in_group('Course Owner'))) {
			//redirect them to the home page because they must be an administrator or owner to view this
			redirect('curriculum/peo/blank', 'refresh');
		} else {
			$dept_id = $this -> input -> post('dept_id');
			$pgm_data = $this -> manage_model_qp_model_re -> pgm_fill($dept_id);
			$pgm_data = $pgm_data['pgm_result'];
			$i = 0;
			$list[$i] = '<option value="">Select Program</option>';
			$i++;
			foreach ($pgm_data as $data) {
				$list[$i] = "<option value = " . $data['pgm_id'] . ">" . $data['pgm_acronym'] . "</option>";
				$i++;
			}
			$list = implode(" ", $list);
			echo $list;
		}
	}//End of function select_pgm_list.

	/* Function is used to fetch curriculum names from curriculum table.
	 * @param-
	 * @returns - an object.
	 */
	public function select_crclm_list() {
		if (!$this -> ion_auth -> logged_in()) {
			//redirect them to the login page
			redirect('login', 'refresh');
		} elseif (!($this -> ion_auth -> is_admin() || $this -> ion_auth -> in_group('Chairman') || $this -> ion_auth -> in_group('Program Owner') || $this -> ion_auth -> in_group('Course Owner'))) {
			//redirect them to the home page because they must be an administrator or owner to view this
			redirect('curriculum/peo/blank', 'refresh');
		} else {
			$pgm_id = $this -> input -> post('pgm_id');
			$curriculum_data = $this -> manage_model_qp_model_re -> crclm_fill($pgm_id);
			$curriculum_data = $curriculum_data['crclm_result'];
			$i = 0;
			$list[$i] = '<option value="">Select Curriculum</option>';
			$i++;
			foreach ($curriculum_data as $data) {
				$list[$i] = "<option value = " . $data['crclm_id'] . ">" . $data['crclm_name'] . "</option>";
				$i++;
			}
			$list = implode(" ", $list);
			echo $list;
		}
	}//End of function select_crclm_list.

	/* Function is used to fetch term names from crclm_terms table.
	 * @param-
	 * @returns - an object.
	 */
	public function select_termlist() {
		if (!$this -> ion_auth -> logged_in()) {
			//redirect them to the login page
			redirect('login', 'refresh');
		} elseif (!($this -> ion_auth -> is_admin() || $this -> ion_auth -> in_group('Chairman') || $this -> ion_auth -> in_group('Program Owner') || $this -> ion_auth -> in_group('Course Owner'))) {
			//redirect them to the home page because they must be an administrator or owner to view this
			redirect('curriculum/peo/blank', 'refresh');
		} else {
			$crclm_id = $this -> input -> post('crclm_id');
			$term_data = $this -> manage_model_qp_model_re -> term_fill($crclm_id);
			$term_data = $term_data['res2'];
			$i = 0;
			$list[$i] = '<option value="">Select Term</option>';
			$i++;
			foreach ($term_data as $data) {
				$list[$i] = "<option value = " . $data['crclm_term_id'] . ">" . $data['term_name'] . "</option>";
				$i++;
			}
			$list = implode(" ", $list);
			echo $list;
		}
	}//End of function select_termlist.

	public function Fetch_dum() {

		$qpd_id = $this -> input -> post('qpd_id');
		$result = $this -> manage_model_qp_model_re -> fetch_groups($qpd_id);
		
		$co_data_val = $result['co_data'];
		$po_data_val = $result['po_data'];
		$topic_data_val = $result['topic_data'];
		$level_data_val = $result['level_data'];
		$pi_data_val = $result['pi_data'];
                $question_type_val = $result['question_type'];

		$table = '';
		$qp_mapping_entity = $this -> manage_model_qp_model_re -> mapping_entity();
		
		$qp_size = count($result['question_paper_data']);
		$question_paper_data = $result['question_paper_data'];
		
		$j = 0;
		for ($q = 0; $q < $qp_size; $q++) {
			$size = count($question_paper_data[$q]);
			for ($qp = 0; $qp < $size; $qp++) {
				$table .= '<tr>';
				$table .= '<td>' . $question_paper_data[$q][$qp]['qp_unit_code'] . '</td>';
				$table .= '<td>' . $question_paper_data[$q][$qp]['qp_subq_code'] . '</td>';
				$table .= '<td>' . ($question_paper_data[$q][$qp]['qp_content']) . '</td>';
				foreach ($qp_mapping_entity as $entity) {
					if ($entity['entity_id'] == 11) {
						$table .= '<td>' . $co_data_val[$j] . '</td>';
					}
				}
				foreach ($qp_mapping_entity as $entity) {
					if ($entity['entity_id'] == 10) {
						$table .= '<td>' . $topic_data_val[$j] . '</td>';
					}
				}
				foreach ($qp_mapping_entity as $entity) {
					if ($entity['entity_id'] == 23) {
						$table .= '<td>' . $level_data_val[$j] . '</td>';
					}
				}
				foreach ($qp_mapping_entity as $entity) {
					if ($entity['entity_id'] == 6) {
						$table .= '<td>' . $po_data_val[$j] . '</td>';
					}
				}
				foreach ($qp_mapping_entity as $entity) {
					if ($entity['entity_id'] == 22) {
						$table .= '<td>' . $pi_data_val[$j] . '</td>';
					}
				}
                foreach ($qp_mapping_entity as $entity) {
					if ($entity['entity_id'] == 29) {
						$table .= '<td>' . $question_type_val[$j] . '</td>';
					}
				}
				$table .= '<td class="marks_val" style="text-align: -webkit-right;">' . $question_paper_data[$q][$qp]['qp_subq_marks'] . '</td>';
				//$table .= '<td><a class="weightage_add cursor_pointer"   data_qp_mq_id="' . $question_paper_data[$q][$qp]['qp_mq_id'] . '">Edit Weightage</a></td>';

				$table .= '<td><a role = "button" class="edit_qp cursor_pointer" data-qpd_unit_id="'.$question_paper_data[$q][$qp]['qpd_unitd_id'].'" data-qp_mq_id="' . $question_paper_data[$q][$qp]['qp_mq_id'] . '"  data-marks="' . $question_paper_data[$q][$qp]['qp_subq_marks'] . '" data-q_num="'.$question_paper_data[$q][$qp]['qp_mq_code'].'" id="' . $question_paper_data[$q][$qp]['qp_subq_code'] . '"    data-qp_content="' . htmlspecialchars($question_paper_data[$q][$qp]['qp_content']) . '"><i class="icon-pencil icon-black"> </i></a></td>';
				$table .= '<td><a role = "button"   class="delete_qp cursor_pointer"   id="' . $question_paper_data[$q][$qp]['qp_mq_id'] . '" data-qpd_id ="' . $qpd_id . '" data-test="' . htmlspecialchars($question_paper_data[$q][$qp]['qp_content']). '" ><i class="icon-remove icon-black"> </i></a></td>';
				$table .= '</tr>';
				$j++;
			}
		}
		echo $table;

	}

	public function fetch_Mapping_data() {
		$qp_map_id = $this -> input -> post('qp_map_id');		
		$crclm_id = $this -> input -> post('crclm_id');
		$term_id = $this -> input -> post('term_id');
		$crs_id = $this -> input -> post('crs_id');
		$qpd_id = $this -> input -> post('qpd_id');
		$unit_id=$this->input->post('unit_id');
		$result = $this -> manage_model_qp_model_re -> fetch_Mapping_data($qp_map_id, $crclm_id, $term_id, $crs_id, $qpd_id,$unit_id);
		echo json_encode($result);
	}
	public function update_qp() {
		$qp_subq_code = $this -> input -> post('qp_subq_code');
		$qp_subq_marks = $this -> input -> post('qp_subq_marks');
		$qp_mq_id = $this -> input -> post('qp_mq_id');
		$qp_mq_code = $this -> input -> post('qp_mq_code');
		$qp_content=$this -> input -> post('qp_content');
		//$qp_content = mysql_real_escape_string(trim($this -> input -> post('qp_content')));
		$co = $this -> input -> post('co');
		$po = $this -> input -> post('po');
		$topic = $this -> input -> post('topic');
		$bl = $this -> input -> post('bl');
		$pi = $this -> input -> post('pi');
		$qn_type = $this -> input -> post('qn_type');
		$sec_name = $this -> input -> post('unit_q_no');
		$mandatory = $this -> input -> post('mandatory');
		$question_type = $this->input->post('question_type');	
		$re = $this -> manage_model_qp_model_re -> check_question_update($sec_name, $qp_subq_code,$qp_mq_id);
		 if ($re == 0) {
			$result = $this -> manage_model_qp_model_re -> update_qp($sec_name,$qp_subq_code, $qp_subq_marks, $qp_mq_id, $qp_content, $co, $po, $topic, $bl, $pi,$qn_type, $mandatory,$qp_mq_code , $question_type);
		} else {
			$result['value'] =-2;
		}
		echo $result['value'];
	}

	/*Function to delete qp record
	 *@returns:
	 */
	public function deleteUser_Roles() {
		if (!$this -> ion_auth -> logged_in()) {
			//redirect them to the login page
			redirect('login', 'refresh');
		} else {

			$Sl_id = $this -> input -> post('val');
			$deleted = $this -> manage_model_qp_model_re -> deleteUser_Roles($Sl_id);
		}
	}//End of function deleteUser_Roles

	/**
	 Function to delete the units of Framework
	 **/
	public function delete_Unit() {
		if (!$this -> ion_auth -> logged_in()) {
			//redirect them to the login page
			redirect('login', 'refresh');
		} else {
			$qpd_unitd_id = $this -> input -> post('unit_id');
			$qpd_id=$this->input->post('qpd_id');
			$deleted = $this -> manage_model_qp_model_re -> delete_Unit($qpd_unitd_id);
			$unit_sum = $this -> manage_model_qp_model_re -> generate_model_unit_sum($qpd_id);
			$data['deleted']=$deleted;
			$data['unit_sum']=$unit_sum;
			echo json_encode($data);
		}
	}

	/* Function is used to generate List of Course Grid (Table).
	 * @param-
	 * @returns - an object.
	 */
	public function show_course() {
		if (!$this -> ion_auth -> logged_in()) {
			//redirect them to the login page
			redirect('login', 'refresh');
		} elseif (!($this -> ion_auth -> is_admin() || $this -> ion_auth -> in_group('Chairman') || $this -> ion_auth -> in_group('Program Owner') || $this -> ion_auth -> in_group('Course Owner'))) {
			//redirect them to the home page because they must be an administrator or owner to view this
			redirect('curriculum/peo/blank', 'refresh');
		} else {
			$dept_id = $this -> input -> post('dept_id');
			$pgmtype_id = $this -> input -> post('prog_id');
			$crclm_id = $this -> input -> post('crclm_id');
			$term_id = $this -> input -> post('crclm_term_id');

			//check whether model qp framework has been created or not
			$check_result = $this -> manage_model_qp_model_re -> check_framework($pgmtype_id);

			$course_data = $this -> manage_model_qp_model_re -> course_list($crclm_id, $term_id);
			$i = 1;
			$msg = '';
			$del = '';
			$publish = '';
			$data = $course_data['crs_list_data'];
			$crs_list = array();

			foreach ($data as $crs_data) {
				if ($crs_data['crs_mode'] == 1) {
					$msg = 'Practical';
				} else {
					$msg = 'Theory';
				}
				$check_fm = $this -> manage_model_qp_model_re -> check_qp($crs_data['crs_id']);
				$topic_defined = $this-> manage_model_qp_model_re ->check_topic_defined_or_not($crs_data['crs_id']);
				$check_topic_status = $this->manage_model_qp_model_re->check_topic_status();
				$count_topic = count($topic_defined);

				if ($check_result == 1) {
					if ($crs_data['state_id'] >= 4) {						
						if($check_topic_status == 1){
							if(empty($topic_defined)){
									$data = 'Cannot Create Question Paper as topics are not defined for this Course.';	
									$export_import = '<a title = "Add / Edit Model QP"  data-error_mag = "'. $data .'" href="#topic_not_defined_modal" class=" topic_not_defined cursor_pointer " > Add / Edit Model QP </a>';	
							}else{	
							$export_import = '<a title = "Add / Edit Model QP" href = "' . base_url() . 'question_paper/manage_model_qp/generate_model_qp/' . $pgmtype_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_data['crs_id'] . '/4" class = "myTagRemover get_crs_id" id = "' . $crs_data['crs_id'] . '" > Add / Edit Model QP  </a>';
							}
					}else{
						$export_import = '<a title = "Add / Edit Model QP" href = "' . base_url() . 'question_paper/manage_model_qp/generate_model_qp/' . $pgmtype_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_data['crs_id'] . '/4" class = "myTagRemover get_crs_id" id = "' . $crs_data['crs_id'] . '" > Add / Edit Model QP  </a>';
					}
						$publish = '<a role = "button"  data-toggle = "modal" title = "Model QP creation In-Progress" href = "#" class = "btn btn-small btn-success span9 myTagRemover get_crs_id" id = "' . $crs_data['crs_id'] . '" ><i></i>In-Progress</a>';
					} else {
						$export_import = '<a role = "button"  data-toggle = "modal" title = "CO to PO mapping Incomplete" href = "#myModal" class = "myTagRemover get_crs_id" id = "' . $crs_data['crs_id'] . '" >Mapping Incomplete</a>';

						$publish = '<a role = "button" data-toggle = "modal" title = "Model QP creation Pending" href = "#" class = "btn btn-small btn-warning span9 myTagRemover get_crs_id" id = "' . $crs_data['crs_id'] . '" ><i></i>Pending</a>';
					}
				} else {	
					if(!empty($check_fm)){
						if ($crs_data['state_id'] >= 4) {
							if ($crs_data['state_id'] >= 4) {						
								if($check_topic_status == 1){
									if(empty($topic_defined)){
											$data = 'Cannot Upload Question Paper as topics are not defined for this Course.';	
											$export_import = '<a title = "Add / Edit Model QP"  data-error_mag = "'. $data .'" href="#topic_not_defined_modal" class=" topic_not_defined cursor_pointer " > Add / Edit Model QP </a>';	
									}else{						
										$export_import = '<a title = "Add / Edit Model QP" href = "' . base_url() . 'question_paper/manage_model_qp/generate_model_qp_fm_not_defined/' . $pgmtype_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_data['crs_id'] . '/4" class = "myTagRemover get_crs_id" data-val="fm_not_defined" id = "' . $crs_data['crs_id'] . '" > Add / Edit Model QP   </a>';
									}
								}else{
									$export_import = '<a title = "Add / Edit Model QP" href = "' . base_url() . 'question_paper/manage_model_qp/generate_model_qp_fm_not_defined/' . $pgmtype_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_data['crs_id'] . '/4" class = "myTagRemover get_crs_id" data-val="fm_not_defined" id = "' . $crs_data['crs_id'] . '" > Add / Edit Model QP   </a>';
								}
								$publish = '<a role = "button" data-toggle = "modal" title = "Model QP creation Pending" href = "#" class = "btn btn-small btn-warning span9 myTagRemover get_crs_id" id = "' . $crs_data['crs_id'] . '" ><i></i>Pending</a>';
						}
						else {
							$export_import = '<a role = "button"  data-toggle = "modal" title = "CO to PO mapping Incomplete" href = "#myModal" class = "myTagRemover get_crs_id" id = "' . $crs_data['crs_id'] . '" >Mapping Incomplete</a>';
							$publish = '<a role = "button" data-toggle = "modal" title = "Model QP creation Pending" href = "#" class = "btn btn-small btn-warning span9 myTagRemover get_crs_id" id = "' . $crs_data['crs_id'] . '" ><i></i>Pending</a>';
						}
					}else{
							$export_import = '<a role = "button" data-toggle = "modal" title = "Add / Edit Model QP" href = "#qp_without_framework_confirmation" class = "myTagRemover get_crs_id" data-val="" id = "' . $crs_data['crs_id'] . '" > Incomplete  </a>';
					}
				}
			}
			
				if($check_topic_status == 1){
					if(empty($topic_defined)){
							$data = 'Cannot Import Question Paper as topics are not defined for this Course.';	
					$import_data = '<a title = "Add / Edit Model QP"  data-error_mag = "'. $data .'" href="#topic_not_defined_modal" class=" topic_not_defined cursor_pointer " >Import Model QP </a>';	
					}else{ $import_data = '<a title="Import Model QP" class="cursor_pointer import_model_qp" data-pgm_id="'.$pgmtype_id.'" data-crclm_id="'.$crclm_id.'" data-term_id="'.$term_id.'" data-crs_id="'.$crs_data['crs_id'].'" data-crs_title="'.$crs_data['crs_title'].'">Import Model QP </a>';}
				}else{
					$import_data = '<a title="Import Model QP" class="cursor_pointer import_model_qp" data-pgm_id="'.$pgmtype_id.'" data-crclm_id="'.$crclm_id.'" data-term_id="'.$term_id.'" data-crs_id="'.$crs_data['crs_id'].'" data-crs_title="'.$crs_data['crs_title'].'">Import Model QP </a>';
				}
									
				$reviewer_id = $crs_data['validator_id'];
				$user = $this -> ion_auth -> user($reviewer_id) -> row();
				$crs_list[] = array('sl_no' => $i,
									'crs_id' => $crs_data['crs_id'],
									'crs_code' => $crs_data['crs_code'], 
									'crs_title' => $crs_data['crs_title'], 
									'crs_type_name' => $crs_data['crs_type_name'],
									'total_credits' => $crs_data['total_credits'], 
									'total_marks' => $crs_data['total_marks'], 
									'crs_mode' => $msg, 'username' => $crs_data['title'] . ' ' . ucfirst($crs_data['first_name']) . ' ' . ucfirst($crs_data['last_name']),
									'reviewer' => $user -> title . ' ' . ucfirst($user -> first_name) . ' ' . ucfirst($user -> last_name), 
									'crs_id_edit' =>'<a title = "View Model QP Details"  data-topic_staus = "'. $check_topic_status . '" data-topic_count = "'. $count_topic.'" href = "#" id = "' . $crs_data['crs_id'] . '" class="myModalQPdisplay displayQP"  abbr="' . $pgmtype_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_data['crs_id'] . '/4"><i class = "myTagRemover "></i>View Model QP</a>', 
									'crs_id_delete' => $export_import,
                                     'import_qp' => $import_data,
									'delete_qp'=>"<center><a class='delete_qp' data-pgm_id=".$pgmtype_id." data-crclm_id=".$crclm_id." data-term_id=".$term_id." data-crs_id=".$crs_data['crs_id']."><i class='icon-remove icon-black'> </i></a></center>",
									'publish' => $publish);
				$i++;
			}
			echo json_encode($crs_list);
		}
	}//End of function show_course.

	/* Function is used to generate List of Course Grid (Table).
	 * @param-
	 * @returns - an object.
	 */
	public function tee_show_course() {
		if (!$this -> ion_auth -> logged_in()) {
			//redirect them to the login page
			redirect('login', 'refresh');
		} elseif (!($this -> ion_auth -> is_admin() || $this -> ion_auth -> in_group('Chairman') || $this -> ion_auth -> in_group('Program Owner') || $this -> ion_auth -> in_group('Course Owner'))) {
			//redirect them to the home page because they must be an administrator or owner to view this
			redirect('curriculum/peo/blank', 'refresh');
		} else {
			$dept_id = $this -> input -> post('dept_id');
			$pgmtype_id = $this -> input -> post('prog_id');
			$crclm_id = $this -> input -> post('crclm_id');
			$term_id = $this -> input -> post('crclm_term_id');

			$course_data = $this -> manage_model_qp_model_re -> course_list($crclm_id, $term_id);
			//$pgm_data = $this->manage_model_qp_model_re->fetch_pgm_type($prog_id);
			//$pgmtype_id = $pgm_data[0]['pgmtype_id'];
			$i = 1;
			$msg = '';
			$del = '';
			$publish = '';
			$data = $course_data['crs_list_data'];
			$crs_list = array();
			$entity_see = $this->lang->line('entity_see');
			foreach ($data as $crs_data) {
				if ($crs_data['crs_mode'] == 1) {
					$msg = 'Practical';
				} else {
					$msg = 'Theory';
				}

				$crs_id_val = $crs_data['crs_id'];
				$one_qp_defined = $this -> manage_model_qp_model_re -> oneQPDefined($crs_id_val);
				$course_bloom_status = $this->manage_model_qp_model_re->course_bloom_status($crclm_id , $term_id , $crs_data['crs_id']); 
				
				
				$topic_defined = $this-> manage_model_qp_model_re ->check_topic_defined_or_not($crs_id_val);
				$check_topic_status = $this->manage_model_qp_model_re->check_topic_status();
				$count_topic = count($topic_defined);
				$export_import = '<a title = "Add '.$entity_see.' QP"    data-bl_status = "'. $course_bloom_status[0]['count_val'] .'" data-clo_bl_flag = "'. $course_bloom_status[0]['clo_bl_flag'] .'" class="tee_question_paper cursor_pointer" abbr = "' . base_url() . 'question_paper/manage_model_qp/generate_model_qp/' . $pgmtype_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_data['crs_id'] . '/5" class = "myTagRemover get_crs_id"  data-term_id = "'.$term_id.'"   data-topic_staus = "'. $check_topic_status . '" data-topic_count = "'. $count_topic.'" data-crclm_id ="'. $crclm_id .'"  id = "' . $crs_data['crs_id'] . '" >Add '.$entity_see.' QP</a>';

				if ($one_qp_defined[0] -> defined) {
					$view_edit_qp = '<a title = "View/Edit '.$entity_see.' QP" class="fetch_tee_qp_data cursor_pointer" abbr = "' . base_url() . 'question_paper/manage_model_qp/fetch_tee_qp/' . $pgmtype_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_data['crs_id'] . '/5" class = "myTagRemover get_crs_id" id = "' . $crs_data['crs_id'] . '"  data-topic_staus = "'. $check_topic_status . '" data-topic_count = "'. $count_topic.'" value="' . $crs_data['crs_id'] . '"> View / Edit </a>';					
					
					$publish = '<a role = "button"  data-toggle = "modal" title = "QP In-Progress" href = "#" class = "btn btn-small btn-success myTagRemover get_crs_id" id = "' . $crs_data['crs_id'] . '" ><i></i>Initiated</a>';
				} else {
					$view_edit_qp = 'QP not defined';
					$publish = '<a role = "button" data-toggle = "modal" title = "QP creation Pending" href = "#myModal3" class = "btn btn-small btn-warning myTagRemover get_crs_id" id = "' . $crs_data['crs_id'] . '" ><i></i>Pending</a>';
				}
				if($course_bloom_status[0]['clo_bl_flag'] == 1 && $course_bloom_status[0]['count_val'] == 0 ){ 
										
					$data = 'Cannot Add / Edit Question Paper as Blooms Level are mandatory for this course . <br/> Bloom\'s Level are missing in Course Outcome . ';
					$qp_upload_link = '<a title = "View/Edit '.$this->lang->line('entity_tee') .' QP"  data-link = "curriculum/clo"  data-type_name = " Map Bloom Level"  data-error_mag = "'. $data .'" href="#topic_not_defined_modal" class=" topic_not_defined cursor_pointer " >  Import '. $this->lang->line('entity_tee') .' QP </a>';	
				}else if($check_topic_status == 1 && empty($topic_defined)){
					
					$data = 'Cannot Upload Question Paper as topics are not defined for this Course.';
					$qp_upload_link = '<a title = "View/Edit '.$this->lang->line('entity_tee') .' QP"  data-link = "curriculum/topic"  data-type_name = " Add Topics" data-error_mag = "'. $data .'" href="#topic_not_defined_modal" class=" topic_not_defined cursor_pointer " >  Import '. $this->lang->line('entity_tee') .' QP </a>';								
				}
				else{
					$qp_upload_link = '<a role="button" title="Import Question Paper From .xls file" class="import_qp_template cursor_pointer" '
					. ' data-crclm_id="'.$crclm_id.'" data-term_id="'.$term_id.'" data-crs_id="'.$crs_data['crs_id'].'" '
					. ' abbr_href="'.base_url().'assessment_attainment/upload_question_papers/index/' . $pgmtype_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_data['crs_id'] . '/5" >Import '. $this->lang->line('entity_tee') .' QP</a>';
				}
	
				$reviewer_id = $crs_data['validator_id'];
				$user = $this -> ion_auth -> user($reviewer_id) -> row();
				$crs_list[] = array('sl_no' => $i, 
				'crs_id' => $crs_data['crs_id'], 
				'crs_code' => $crs_data['crs_code'], 
				'crs_title' => $crs_data['crs_title'], 
				'crs_type_name' => $crs_data['crs_type_name'], 
				'total_credits' => '<p style="text-align: -webkit-right;">'.$crs_data['total_credits'].'</p>', 
				'total_marks' => '<p style="text-align: -webkit-right;">'.$crs_data['total_marks'].'</p>', 
				'crs_mode' => $msg,
				'username' => $crs_data['title'] . ' ' . ucfirst($crs_data['first_name']) . ' ' . ucfirst($crs_data['last_name']),
				'reviewer' => $user -> title . ' ' . ucfirst($user -> first_name) . ' ' . ucfirst($user -> last_name),
				'crs_id_edit' => $view_edit_qp,
				'crs_id_delete' => $export_import, 
                                'publish' => $publish,
                                'upload_link'=>$qp_upload_link,
                                        );
				$i++;
			}
			echo json_encode($crs_list);
		}
	}//End of function show_course.
        
        /*
         * Function to check any qp rolled out
         */
        public function check_any_qp_rolled_out(){
            $crclm_id = $this->input->post('crclm_id');
            $term_id = $this->input->post('term_id');
            $crs_id = $this->input->post('crs_id');
            $one_qp_defined = $this -> manage_model_qp_model_re -> check_any_qp_rolled_out($crclm_id,$term_id,$crs_id);
            $data['qp_count'] = $one_qp_defined['qp_data'];
            $data['qp_rollout_val'] = $one_qp_defined['qp_rollout'];
            echo json_encode($data);
        }
        
        /*
         * Function to roll back the QP
         */
        public function roll_back_qp(){
            $crclm_id = $this->input->post('crclm_id');
            $crs_id = $this->input->post('crs_id');
            $one_qp_defined = $this -> manage_model_qp_model_re -> roll_back_qp($crclm_id,$crs_id);
            if($one_qp_defined == true){
                echo 'true';
            }else{
                echo 'false';
            }
        }

                public function qp_def() {
		$data['qpf_id'] = $this -> input -> post('qpf_id');
		$data['qpd_type'] = $this -> input -> post('qpd_type');
		$data['crclm_id'] = $this -> input -> post('crclm_id');
		$data['term_id'] = $this -> input -> post('term_id');
		$data['crs_id'] = $this -> input -> post('crs_id');
		$data['pgm_id'] = $this -> input -> post('pgm_id');
		$data['qp_title'] = $this -> input -> post('qp_title');
		$data['time'] = $this -> input -> post('time');
		$data['max_marks'] = $this -> input -> post('max_marks');
		$data['Grand_total'] = $this -> input -> post('Grand_total');
		$data['qp_notes'] = $this -> input -> post('qp_notes');
		$data['val'] = $this -> input -> post('val');
		$data['count'] = $this -> input -> post('count');
		$data['b'] = $this -> input -> post('b');
		$result = $this -> manage_model_qp_model_re -> qp_def($data);

		echo json_encode($result);
	}

	/*
	 Function to create the cia model qp defination.

	 */
	public function cia_model_qp_def() {
	
		$data['qpf_id'] = $this -> input -> post('qpf_id');
		$data['qpd_type'] = $this -> input -> post('qpd_type');
		$data['crclm_id'] = $this -> input -> post('crclm_id');
		$data['term_id'] = $this -> input -> post('term_id');
		$data['crs_id'] = $this -> input -> post('crs_id');
		$data['pgm_id'] = $this -> input -> post('pgm_id');
		$data['qp_title'] = $this -> input -> post('qp_title');
		$data['time'] = $this -> input -> post('time');
		$data['max_marks'] = $this -> input -> post('max_marks');
		$data['Grand_total'] = $this -> input -> post('Grand_total');
		$data['qp_notes'] = $this -> input -> post('qp_notes');
		$data['val'] = $this -> input -> post('val');
		$data['count'] = $this -> input -> post('count');
		$data['b'] = $this -> input -> post('b');
		$data['ao_id'] = $this -> input -> post('ao_id');
		$data['qp_model'] = $this -> input -> post('qp_model');

		$result = $this -> manage_model_qp_model_re -> cia_model_qp_def($data);

		echo json_encode($result);
	}

	/*Function to generate Framework */
	public function generate_FM() {	
                $qpd_id = $this -> input -> post('qpd_id');
		$val = $this -> input -> post('val');
		$count = $this -> input -> post('count');
                
		$data['result'] = $this -> manage_model_qp_model_re -> generate_FM($val, $count, $qpd_id);
		$data['unit_sum'] = $this -> manage_model_qp_model_re -> generate_model_unit_sum($qpd_id);
		
		echo json_encode($data);
	}

	/*
	 Function to Update Framework
	 */
	public function update_FM() {
		$units = $this -> input -> post('units');
		$unit_ids = $this -> input -> post('unit_ids');
		$no_q = $this -> input -> post('no_q');
		$sub_marks = $this -> input -> post('sub_marks');	
		$qpd_id = $this -> input -> post('qpd_id');			
		$unit_marks = $this -> input -> post('unit_marks');
		 $result= $this -> manage_model_qp_model_re -> update_FM($units, $unit_ids, $no_q, $sub_marks, $qpd_id,$unit_marks);
		$data['result']=$result['qp_unit_result'];
		$data['unit_sum'] = $this -> manage_model_qp_model_re -> generate_model_unit_sum($qpd_id);
		$sec_val= $this -> manage_model_qp_model_re -> generate_model_unit($qpd_id);
		$data['section_name']=$sec_val[0]['qp_unit_code'];		
		echo json_encode($data);
	}
	
	/*Function to fetch grand total marks of QP*/
	public function get_grand_total(){
			$qpd_type 	=   	$this -> input -> post('qpd_type');
		   $crclm_id 	=      $this -> input -> post('crclm_id');
		  $term_id 		=     $this -> input -> post('term_id');
		 $crs_id 		=    $this -> input -> post('crs_id');
		$qpd_id 		=   $this -> input -> post('qpd_id');
		
		$grand_total=$this->manage_model_qp_model_re->generate_grand_total($crclm_id,$term_id,$crs_id,$qpd_id,$qpd_type);
		echo json_encode($grand_total[0]['qpd_gt_marks']);
	}
	
	public function generate_model_qp_fm_not_defined($pgm_id = NULL, $crclm_id = NULL, $term_id = NULL, $crs_id = NULL, $qp_type = NULL){
	if ($qp_type == 4) {
			$model_qp_existance = $this -> manage_model_qp_model_re -> model_qp_existance($crs_id, $qp_type);
			$course_data = $this -> manage_model_qp_model_re -> model_qp_course_data($crclm_id, $term_id, $crs_id);

			$dp_def = $this -> manage_model_qp_model_re -> get_qpd_id($crs_id, $qp_type);
				$qp_mapping_entity = $this -> manage_model_qp_model_re -> mapping_entity();
				if ($model_qp_existance == 0) {
				$data['entity_list'] = $qp_mapping_entity;
				$data['co_details'] = $course_data['co_data'];
				$data['topic_details'] = $course_data['topic_list'];
				$data['bloom_data'] = $course_data['level_list'];
				$data['pi_list'] = $course_data['pi_code_list'];
				$data['course_title'] = $course_data['crs_title'];
				
				$data['crclm_id'] = $crclm_id;
				$data['term_id'] = $term_id;
				$data['crs_id'] = $crs_id;
				$data['pgm_id'] = $pgm_id;
				$data['crclm_title'] = $course_data['crclm_title'];
				$data['term_title'] = $course_data['term_title'];
				$data['qpd_type'] = 4;
				$data['model_qp_existance'] = 0;

						$data['url_ids'] = $pgm_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_id;
						$data['title'] = 'Add '.$this->lang->line('entity_tee').' Model Question Paper for  Course : ' . $course_data['crs_title'][0]['crs_title'] . ' - [' . $course_data['crs_title'][0]['crs_code'] . ']';
						$data['sub_title'] = "Add TEE Model Framework";
						$this -> load -> view('question_paper/model_qp/qp_without_framework', $data);
			}else{
				
					$qp_mapping_entity = $this -> manage_model_qp_model_re -> mapping_entity();
					$qpd_id = $dp_def[0]['qpd_id'];
					$data['entity_id'] = $qp_mapping_entity;
					$unit_data = $this -> manage_model_qp_model_re -> generate_model_unit($qpd_id);
					$unit_sum = $this -> manage_model_qp_model_re -> generate_model_unit_sum($qpd_id);
					$model_qp_details = $this -> manage_model_qp_model_re -> generate_model_qp_not_fm($pgm_id , $qpd_id);
					
					$total_marks = $this -> manage_model_qp_model_re -> generate_total_marks($dp_def[0]['qpd_id']);
					$data['qp_unit_data'] = $model_qp_details['qpf_unit_details'];
					$data['qp_mq_data'] = '';
					$data['qp_entity'] = $model_qp_details['qp_entity_config'];
					$course_data = $this -> manage_model_qp_model_re -> model_qp_course_data($crclm_id, $term_id, $crs_id);
					$model_qp_edit = $this -> manage_model_qp_model_re -> model_qp_edit_details($crclm_id, $term_id, $crs_id, $qp_type, $qpd_id);
					$data['total_marks'] = $total_marks['marks'];
					$qp_mapping_entity = $this -> manage_model_qp_model_re -> mapping_entity();
                                        $question_type = $this -> manage_model_qp_model_re -> get_all_question_types();
					$data['entity_list'] = $qp_mapping_entity;

					$data['co_details'] = $course_data['co_data'];
					$data['topic_details'] = $course_data['topic_list'];
					$data['bloom_data'] = $course_data['level_list'];
					$data['pi_list'] = $course_data['pi_code_list'];
					$data['course_title'] = $course_data['crs_title'];
					$data['crclm_title'] = $course_data['crclm_title'];
					$data['term_title'] = $course_data['term_title'];
					$data['question_type_details'] = $course_data['question_type'];
					$data['crclm_id'] = $crclm_id;
					$data['term_id'] = $term_id;
					$data['crs_id'] = $crs_id;
					$data['pgm_id'] = $pgm_id;
					$data['unit_data'] = $unit_data;
					$data['unit_sum']=$unit_sum;
					$data['model_qp_existance'] = $model_qp_existance;
					$data['qpd_id'] = $dp_def[0]['qpd_id'];
					$data['meta_data'] = $model_qp_edit['qp_meta_data'];
					$data['question_type'] = $question_type;

					$data['url_ids'] = $pgm_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_id;

					$data['title'] = 'Edit '.$this->lang->line('entity_tee').' Model Question Paper for Course : ' . $course_data['crs_title'][0]['crs_title'] . ' - [' . $course_data['crs_title'][0]['crs_code'] . ']';					
					$this -> load -> view('question_paper/model_qp/model_qp_edit_vw_re', $data);
				
			}
		} else if($qp_type == 5){
			$model_qp_details = $this -> manage_model_qp_model_re -> generate_model_qp($pgm_id);
			
			
			$dp_def = $this -> manage_model_qp_model_re -> get_qpd_id($crs_id, $qp_type);
				$data['qp_unit_data'] = $model_qp_details['qpf_unit_details'];
				$data['qp_mq_data'] ="";
				$data['qp_entity'] = $model_qp_details['qp_entity_config'];
				$data['qpd_type'] = 5;
				
				
			if (!empty($dp_def)) {
				$qpd_id = $dp_def[0]['qpd_id'];
				$model_qp_edit = $this -> manage_model_qp_model_re -> model_qp_edit_details($crclm_id, $term_id, $crs_id, $qp_type, $qpd_id);
				$data['meta_data'] = $model_qp_edit['qp_meta_data'];
			
			} else {
				$qpd_id = NULL;
			}

			//$unit_data = $this->manage_model_qp_model_re->generate_model_unit($qpd_id);
			$course_data = $this -> manage_model_qp_model_re -> model_qp_course_data($crclm_id, $term_id, $crs_id);
			//$model_qp_edit = $this->manage_model_qp_model_re->model_qp_edit_details($crclm_id, $term_id, $crs_id, $qp_type, $qpd_id);
			$qp_mapping_entity = $this -> manage_model_qp_model_re -> mapping_entity();
			$data['entity_list'] = $qp_mapping_entity;
			$data['co_details'] = $course_data['co_data'];
			$data['topic_details'] = $course_data['topic_list'];
			$data['bloom_data'] = $course_data['level_list'];
			$data['pi_list'] = $course_data['pi_code_list'];
			$data['course_title'] = $course_data['crs_title'];
			$data['course_title'] = $course_data['crs_title'];
			$data['crclm_title'] = $course_data['crclm_title'];
			$data['term_title'] = $course_data['term_title'];
			$data['crclm_id'] = $crclm_id;
			$data['term_id'] = $term_id;
			$data['crs_id'] = $crs_id;
			$data['pgm_id'] = $pgm_id;
			$data['qpd_id'] = $qpd_id;
			$data['model_qp_existance'] = 0;
			$data['url_ids'] = $pgm_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_id;			
			$data['title'] = 'Add '.$this->lang->line('entity_see_full').'('.$this->lang->line('entity_tee').') Question Paper for Course : ' . $course_data['crs_title'][0]['crs_title'] . ' - (' . $course_data['crs_title'][0]['crs_code'] . ')';
		
				$data['sub_title'] = "Add".$this->lang->line('entity_tee'). " Framework";
				$this -> load -> view('question_paper/model_qp/qp_without_framework', $data);
			
		}
	}

	public function generate_model_qp($pgm_id = NULL, $crclm_id = NULL, $term_id = NULL, $crs_id = NULL, $qp_type = NULL) {
		//public function generate_model_qp(){
		if (!$this -> ion_auth -> logged_in()) {
			//redirect them to the login page
			redirect('login', 'refresh');
		} elseif (!($this -> ion_auth -> is_admin() || $this -> ion_auth -> in_group('Chairman') || $this -> ion_auth -> in_group('Program Owner') || $this -> ion_auth -> in_group('Course Owner'))) {
			//redirect them to the home page because they must be an administrator or owner to view this
			redirect('curriculum/peo/blank', 'refresh');
		} else {	
	
			if ($qp_type == 4) {
				$model_qp_existance = $this -> manage_model_qp_model_re -> model_qp_existance($crs_id, $qp_type);
				$qp_mapping_entity = $this -> manage_model_qp_model_re -> mapping_entity();

				$dp_def = $this -> manage_model_qp_model_re -> get_qpd_id($crs_id, $qp_type);

				if ($model_qp_existance == 0) {
					$model_qp_details = $this -> manage_model_qp_model_re -> generate_model_qp($pgm_id);
					$data['qp_unit_data'] = $model_qp_details['qpf_unit_details'];
				//	$data['qp_mq_data'] = $model_qp_details['qpf_mq_details'];

					if (!empty($model_qp_details['qpf_mq_details'])) {
						$data['qp_mq_data'] = $model_qp_details['qpf_mq_details'];
					}
					
					$data['qp_entity'] = $model_qp_details['qp_entity_config'];
					if (!empty($dp_def)) {
						$qpd_id = $dp_def[0]['qpd_id'];
						$model_qp_edit = $this -> manage_model_qp_model_re -> model_qp_edit_details($crclm_id, $term_id, $crs_id, $qp_type, $qpd_id);
						$data['meta_data'] = $model_qp_edit['qp_meta_data'];
					} else {
						$qpd_id = NULL;
					}
					$data['entity_id'] = $qp_mapping_entity;

					$course_data = $this -> manage_model_qp_model_re -> model_qp_course_data($crclm_id, $term_id, $crs_id);

					$qp_mapping_entity = $this -> manage_model_qp_model_re -> mapping_entity();
					$data['entity_list'] = $qp_mapping_entity;
					$data['co_details'] = $course_data['co_data'];
					$data['topic_details'] = $course_data['topic_list'];
					$data['bloom_data'] = $course_data['level_list'];
					$data['pi_list'] = $course_data['pi_code_list'];
					$data['course_title'] = $course_data['crs_title'];
					$data['crclm_title'] = $course_data['crclm_title'];
					$data['term_title'] = $course_data['term_title'];
					$data['crclm_id'] = $crclm_id;
					$data['term_id'] = $term_id;
					$data['crs_id'] = $crs_id;
					$data['pgm_id'] = $pgm_id;
					$data['model_qp_existance'] = $model_qp_existance;

					$data['url_ids'] = $pgm_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_id;
					$data['title'] = 'Add '.$this->lang->line('entity_tee').' Model Question Paper for  Course : ' . $course_data['crs_title'][0]['crs_title'] . ' - [' . $course_data['crs_title'][0]['crs_code'] . ']';
					
					$this -> load -> view('question_paper/model_qp/model_qp_add_vw_re', $data);

				} else {
					$qp_mapping_entity = $this -> manage_model_qp_model_re -> mapping_entity();
					$qpd_id = $dp_def[0]['qpd_id'];
					$data['entity_id'] = $qp_mapping_entity;
					$unit_data = $this -> manage_model_qp_model_re -> generate_model_unit($qpd_id);
					$unit_sum = $this -> manage_model_qp_model_re -> generate_model_unit_sum($qpd_id);
					$model_qp_details = $this -> manage_model_qp_model_re -> generate_model_qp($pgm_id);
					$total_marks = $this -> manage_model_qp_model_re -> generate_total_marks($dp_def[0]['qpd_id']);
					$data['qp_unit_data'] = $model_qp_details['qpf_unit_details'];
					$data['qp_mq_data'] = $model_qp_details['qpf_mq_details'];
					$data['qp_entity'] = $model_qp_details['qp_entity_config'];
					$course_data = $this -> manage_model_qp_model_re -> model_qp_course_data($crclm_id, $term_id, $crs_id);
					$model_qp_edit = $this -> manage_model_qp_model_re -> model_qp_edit_details($crclm_id, $term_id, $crs_id, $qp_type, $qpd_id);
					$data['total_marks'] = $total_marks['marks'];
					$qp_mapping_entity = $this -> manage_model_qp_model_re -> mapping_entity();
					$data['entity_list'] = $qp_mapping_entity;

					$data['co_details'] = $course_data['co_data'];
					$data['topic_details'] = $course_data['topic_list'];
					$data['bloom_data'] = $course_data['level_list'];
					$data['pi_list'] = $course_data['pi_code_list'];
					$data['course_title'] = $course_data['crs_title'];
					$data['crclm_title'] = $course_data['crclm_title'];
					$data['term_title'] = $course_data['term_title'];
					$data['question_type_details'] = $course_data['question_type'];
					$data['crclm_id'] = $crclm_id;
					$data['term_id'] = $term_id;
					$data['crs_id'] = $crs_id;
					$data['pgm_id'] = $pgm_id;
					$data['unit_data'] = $unit_data;
					$data['unit_sum']=$unit_sum;
					$data['model_qp_existance'] = $model_qp_existance;
					$data['qpd_id'] = $dp_def[0]['qpd_id'];
					$data['meta_data'] = $model_qp_edit['qp_meta_data'];

					$data['url_ids'] = $pgm_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_id;

					$data['title'] = 'Edit '.$this->lang->line('entity_tee').' Model Question Paper for Course : ' . $course_data['crs_title'][0]['crs_title'] . ' - [' . $course_data['crs_title'][0]['crs_code'] . ']';
					$this -> load -> view('question_paper/model_qp/model_qp_edit_vw_re', $data);
				}
			} elseif ($qp_type == 5) {
			
				$model_qp_existance = $this -> manage_model_qp_model_re -> model_qp_existance($crs_id, $qp_type);
				if ($model_qp_existance == 0) {

					// $pgm_id = 93;
					/* $model_qp_details = $this->manage_model_qp_model_re->generate_model_qp($pgm_id);
					 $data['qp_unit_data'] 	= $model_qp_details['qpf_unit_details'] ;
					 $data['qp_mq_data'] 	= $model_qp_details['qpf_mq_details'];
					 $data['qp_entity'] 		= $model_qp_details['qp_entity_config'];

					 $course_data = $this->manage_model_qp_model_re->model_qp_course_data($crclm_id, $term_id, $crs_id);

					 $data['co_details'] = $course_data['co_data'];
					 $data['topic_details'] = $course_data['topic_list'];
					 $data['bloom_data'] = $course_data['level_list'];
					 $data['course_title'] = $course_data['crs_title'];
					 $data['crclm_id'] = $crclm_id;
					 $data['term_id'] = $term_id;
					 $data['crs_id'] = $crs_id;

					 $data = array(
					 'pgm_id' 	=> $pgm_id,
					 'crclm_id' => $crclm_id,
					 'term_id' 	=> $term_id,
					 'crs_id' 	=> $crs_id,
					 'qpd_type' => $qp_type,
					 'model_qp'	=> 0	);
	
					 $data['title'] = 'TEE Model Question Paper';
					 $this->load->view('question_paper/model_qp/tee_qp_add_vw', $data); */

					$data = array('pgm_id' => $pgm_id, 'crclm_id' => $crclm_id, 'term_id' => $term_id, 'crs_id' => $crs_id, 'qpd_type' => $qp_type, 'model_qp' => 0);

					echo json_encode($data);
				} else {
					$data = array('pgm_id' => $pgm_id, 'crclm_id' => $crclm_id, 'term_id' => $term_id, 'crs_id' => $crs_id, 'qpd_type' => $qp_type, 'model_qp' => 1);

					echo json_encode($data);

					/* $model_qp_details = $this->manage_model_qp_model_re->generate_model_qp($pgm_id);
					 //$data['qp_unit_data'] 	= $model_qp_details['qpf_unit_details'] ;
					 $model_qp_data['qp_mq_data'] 	= $model_qp_details['qpf_mq_details'];
					 //$data['qp_entity'] 		= $model_qp_details['qp_entity_config'];

					 $model_qp_edit = $this->manage_model_qp_model_re->model_qp_edit_details($crclm_id, $term_id, $crs_id, $qp_type);

					 $model_qp_data ['meta_data'] = $model_qp_edit['qp_meta_data'];
					 $model_qp_data ['main_qp_data'] = $model_qp_edit['main_qp_data'];
					 $model_qp_data ['co_data'] = $model_qp_edit['co_data'];
					 $model_qp_data ['topic_data'] = $model_qp_edit['topic_data'];
					 $model_qp_data ['bloom_data'] = $model_qp_edit['bloom_data'];
					 $model_qp_data ['unit_def_data'] = $model_qp_edit['unit_def_data'];
					 $model_qp_data ['entity_list'] = $model_qp_edit['entity_list'];
					 $model_qp_data['crclm_id'] = $crclm_id;
					 $model_qp_data['term_id'] = $term_id;
					 $model_qp_data['crs_id'] = $crs_id;
					 $model_qp_data['title'] = 'TEE Model Question Paper';
					 $this->load->view('question_paper/model_qp/tee_qp_edit_vw', $model_qp_data); */
				}
			} else if ($qp_type == 3) {

				$model_qp_existance = $this -> manage_model_qp_model_re -> model_qp_existance($crs_id, $qp_type);

				$dp_def = $this -> manage_model_qp_model_re -> get_qpd_id($crs_id, $qp_type);
				if ($model_qp_existance == 0) {
					// $pgm_id = 93;
					/* $model_qp_details = $this->manage_model_qp_model_re->generate_model_qp($pgm_id);
					 $data['qp_unit_data'] 	= $model_qp_details['qpf_unit_details'] ;
					 $data['qp_mq_data'] 	= $model_qp_details['qpf_mq_details'];
					 $data['qp_entity'] 		= $model_qp_details['qp_entity_config'];

					 $course_data = $this->manage_model_qp_model_re->model_qp_course_data($crclm_id, $term_id, $crs_id);

					 $data['co_details'] = $course_data['co_data'];
					 $data['topic_details'] = $course_data['topic_list'];
					 $data['bloom_data'] = $course_data['level_list'];
					 $data['pi_list'] = $course_data['pi_code_list'];
					 $data['course_title'] = $course_data['crs_title'];
					 $data['crclm_id'] = $crclm_id;
					 $data['term_id'] = $term_id;
					 $data['crs_id'] = $crs_id;
					 $data['ao_id'] = $ao_id;
					 //$data['script_data'] = $this->load->view('includes/cia_model_qp_js_data','',true);

					 $data['title'] = 'CIA Model Question Paper';
					 $this->load->view('question_paper/model_qp/cia_model_qp_add_vw_re', $data); */

					$model_qp_details = $this -> manage_model_qp_model_re -> generate_model_qp($pgm_id);
					$data['qp_unit_data'] = $model_qp_details['qpf_unit_details'];
					$data['qp_mq_data'] = $model_qp_details['qpf_mq_details'];
					$data['qp_entity'] = $model_qp_details['qp_entity_config'];
					if (!empty($dp_def)) {
						$qpd_id = $dp_def[0]['qpd_id'];
						$model_qp_edit = $this -> manage_model_qp_model_re -> model_qp_edit_details($crclm_id, $term_id, $crs_id, $qp_type, $qpd_id);
						$data['meta_data'] = $model_qp_edit['qp_meta_data'];
					} else {
						$qpd_id = NULL;
					}
					//$qpd_id=$dp_def[0]['qpd_id'];
					//	$unit_data = $this->manage_model_qp_model_re->generate_model_unit($qpd_id);
					$course_data = $this -> manage_model_qp_model_re -> model_qp_course_data($crclm_id, $term_id, $crs_id);					
					$qp_mapping_entity = $this -> manage_model_qp_model_re -> mapping_entity();
					$data['entity_list'] = $qp_mapping_entity;
					$data['co_details'] = $course_data['co_data'];
					$data['topic_details'] = $course_data['topic_list'];
					$data['bloom_data'] = $course_data['level_list'];
					$data['pi_list'] = $course_data['pi_code_list'];
					$data['course_title'] = $course_data['crs_title'];
					$data['crclm_title'] = $course_data['crclm_title'];
					$data['term_title'] = $course_data['term_title'];
					$data['crclm_id'] = $crclm_id;
					$data['term_id'] = $term_id;
					$data['crs_id'] = $crs_id;
					$data['pgm_id'] = $pgm_id;
					$data['model_qp_existance'] = $model_qp_existance;
			
					$data['url_ids'] = $pgm_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_id;
					$cia_lang=$this->lang->line('student_outcome_full');
					$data['title'] = 'Course '.$this->lang->line('entity_cie').'  Question Paper';

					$this -> load -> view('question_paper/model_qp/model_qp_add_vw_re', $data);

				} else {

					$qpd_id = $dp_def[0]['qpd_id'];
					$unit_data = $this -> manage_model_qp_model_re -> generate_model_unit($qpd_id);
					$model_qp_details = $this -> manage_model_qp_model_re -> generate_model_qp($pgm_id);
					$total_marks = $this -> manage_model_qp_model_re -> generate_total_marks($qpd_id);
					$data['qp_unit_data'] = $model_qp_details['qpf_unit_details'];
					$data['qp_mq_data'] = $model_qp_details['qpf_mq_details'];
					$data['qp_entity'] = $model_qp_details['qp_entity_config'];
					$course_data = $this -> manage_model_qp_model_re -> model_qp_course_data($crclm_id, $term_id, $crs_id);
					$model_qp_edit = $this -> manage_model_qp_model_re -> model_qp_edit_details($crclm_id, $term_id, $crs_id, $qp_type, $qpd_id);
					$qp_mapping_entity = $this -> manage_model_qp_model_re -> mapping_entity();
					$data['entity_list'] = $qp_mapping_entity;
					$data['total_marks'] = $total_marks['marks'];
					$data['co_details'] = $course_data['co_data'];
					$data['topic_details'] = $course_data['topic_list'];
					$data['bloom_data'] = $course_data['level_list'];
					$data['pi_list'] = $course_data['pi_code_list'];
					$data['course_title'] = $course_data['crs_title'];
					$data['crclm_title'] = $course_data['crclm_title'];
					$data['term_title'] = $course_data['term_title'];
					$data['question_type_details'] = $course_data['question_type'];
					$data['crclm_id'] = $crclm_id;
					$data['term_id'] = $term_id;
					$data['crs_id'] = $crs_id;
					$data['pgm_id'] = $pgm_id;
					$data['unit_data'] = $unit_data;
					$data['model_qp_existance'] = $model_qp_existance;
					$data['qpd_id'] = $dp_def[0]['qpd_id'];
					$data['meta_data'] = $model_qp_edit['qp_meta_data'];
					/* $data['main_qp_data'] = $model_qp_edit['main_qp_data'];
					 $data ['co_data'] = $model_qp_edit['co_data'];
					 $data ['topic_data'] = $model_qp_edit['topic_data'];
					 $data ['bloom_data'] = $model_qp_edit['bloom_data'];
					 $data ['pi_list'] = $model_qp_edit['pi_code_list'];
					 $data ['unit_def_data'] = $model_qp_edit['unit_def_data'];
					 $data ['entity_list'] = $model_qp_edit['entity_list']; */
					$data['url_ids'] = $pgm_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_id;

					$data['title'] = 'Course '.$this->lang->line('entity_cie').' Question Paper';
		
					$this -> load -> view('question_paper/model_qp/model_qp_edit_vw_re', $data);
				}
			}
		}
	}

	/*
	 Function to generate the CIA question paper
	 @param course id, ao id.
	 */
	public function generate_cia_model_qp($pgm_id = NULL, $crclm_id = NULL, $term_id = NULL, $crs_id = NULL, $ao_id = NULL, $qp_type = NULL) {
		$cia_model_qp = $this -> manage_model_qp_model_re -> cia_model_qp_details($crclm_id, $term_id, $crs_id, $ao_id);

		//$dp_def=$this->manage_model_qp_model_re->get_qpd_id($crs_id,$qp_type);
		if (!empty($cia_model_qp[0]['qpd_id'])) {
			$data['ao_id'] = $ao_id;
			$qpd_id = $cia_model_qp[0]['qpd_id'];
			$qp_mapping_entity = $this -> manage_model_qp_model_re -> mapping_entity();
			$data['entity_id'] = $qp_mapping_entity;
			$unit_data = $this -> manage_model_qp_model_re -> generate_model_unit($qpd_id);
			$model_qp_details = $this -> manage_model_qp_model_re -> generate_model_qp($pgm_id);
			$total_marks = $this -> manage_model_qp_model_re -> generate_total_marks($qpd_id);
			$unit_sum = $this -> manage_model_qp_model_re -> generate_model_unit_sum($qpd_id);			
			if(!empty($model_qp_details['qpf_unit_details'])){$data['qp_unit_data'] = $model_qp_details['qpf_unit_details'];} else{ $data['qp_unit_data'] = $unit_data; }
			if (!empty($model_qp_details['qpf_mq_details'])) {
				$data['qp_mq_data'] = $model_qp_details['qpf_mq_details'];
			}
			$data['qp_entity'] = $model_qp_details['qp_entity_config'];
			$course_data = $this -> manage_model_qp_model_re -> model_qp_course_data($crclm_id, $term_id, $crs_id);
			$model_qp_edit = $this -> manage_model_qp_model_re -> model_qp_edit_details($crclm_id, $term_id, $crs_id, $qp_type, $qpd_id);
			$qp_mapping_entity = $this -> manage_model_qp_model_re -> mapping_entity();
			$data['entity_list'] = $qp_mapping_entity;
			$data['total_marks'] = $total_marks['marks'];
			
			$data['co_details'] = $course_data['co_data'];
			$data['topic_details'] = $course_data['topic_list'];
			$data['bloom_data'] = $course_data['level_list'];
			$data['pi_list'] = $course_data['pi_code_list'];
			$data['course_title'] = $course_data['crs_title'];
			$data['crclm_title'] = $course_data['crclm_title'];
			$data['term_title'] = $course_data['term_title'];
			$data['crclm_id'] = $crclm_id;
			$data['term_id'] = $term_id;
			$data['crs_id'] = $crs_id;
			$data['pgm_id'] = $pgm_id;
			$data['unit_sum']=$unit_sum;
			$data['unit_data'] = $unit_data;
			$data['model_qp_existance'] = 1;
			$data['qpd_id'] = $cia_model_qp[0]['qpd_id'];
			$data['meta_data'] = $model_qp_edit['qp_meta_data'];
			$data['occassion_name']=$model_qp_edit['occassion_name'];
			$data['question_type_details'] = $course_data['question_type_data'];
			$data['url_ids'] = $pgm_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_id;

			$data['title'] = 'Edit '.$this->lang->line('entity_cie').' Model Question Paper for ' . $course_data['crs_title'][0]['crs_title'] . ' - [' . $course_data['crs_title'][0]['crs_code'] . ']';
			$this -> load -> view('question_paper/model_qp/cia_model_qp_edit_vw_re', $data);

		} else {
			$data['ao_id'] = $ao_id;
			$model_qp_details = $this -> manage_model_qp_model_re -> generate_model_qp($pgm_id);
		//	$unit_data = $this -> manage_model_qp_model_re -> generate_model_unit($qpd_id);
			//$unit_data = '';
			if(!empty( $model_qp_details['qpf_unit_details'])){$data['qp_unit_data'] = $model_qp_details['qpf_unit_details'];}
			if (!empty($model_qp_details['qpf_mq_details'])) {
				$data['qp_mq_data'] = $model_qp_details['qpf_mq_details'];
			}

			$data['qp_entity'] = $model_qp_details['qp_entity_config'];
			if (!empty($cia_model_qp[0]['qpd_id'])) {
				$qpd_id = $cia_model_qp[0]['qpd_id'];
				$model_qp_edit = $this -> manage_model_qp_model_re -> model_qp_edit_details($crclm_id, $term_id, $crs_id, $qp_type, $qpd_id);
				$data['meta_data'] = $model_qp_edit['qp_meta_data'];
			} else {
				$qpd_id = NULL;
			}
			//$qpd_id=$dp_def[0]['qpd_id'];
			//	$unit_data = $this->manage_model_qp_model_re->generate_model_unit($qpd_id);
			$course_data = $this -> manage_model_qp_model_re -> model_qp_course_data($crclm_id, $term_id, $crs_id);

			$qp_mapping_entity = $this -> manage_model_qp_model_re -> mapping_entity();
			$data['entity_list'] = $qp_mapping_entity;
			$data['co_details'] = $course_data['co_data'];
			$data['topic_details'] = $course_data['topic_list'];
			$data['bloom_data'] = $course_data['level_list'];
			$data['pi_list'] = $course_data['pi_code_list'];
			$data['course_title'] = $course_data['crs_title'];
			
			$data['crclm_id'] = $crclm_id;
			$data['term_id'] = $term_id;
			$data['crs_id'] = $crs_id;
			$data['pgm_id'] = $pgm_id;
			$data['crclm_title'] = $course_data['crclm_title'];
			$data['term_title'] = $course_data['term_title'];

			$data['model_qp_existance'] = 0;
			//$data['unit_data']=$dp_def[0]['qpd_id'];
			//$data['unit_data']=$unit_data;

			/* $data['main_qp_data'] = $model_qp_edit['main_qp_data'];
			 $data ['co_data'] = $model_qp_edit['co_data'];
			 $data ['topic_data'] = $model_qp_edit['topic_data'];
			 $data ['bloom_data'] = $model_qp_edit['bloom_data'];
			 $data ['pi_list'] = $model_qp_edit['pi_code_list'];
			 $data ['unit_def_data'] = $model_qp_edit['unit_def_data'];
			 $data ['entity_list'] = $model_qp_edit['entity_list']; */
			$data['url_ids'] = $pgm_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_id;
			$data['title'] = 'Add '.$this->lang->line('entity_cie').' Question Paper for Course : ' . $course_data['crs_title'][0]['crs_title'] . ' - [' . $course_data['crs_title'][0]['crs_code'] . ']';

			$this -> load -> view('question_paper/model_qp/cia_model_qp_add_vw_re', $data);

		}
	}

	public function isModelQPDefined() {
		if (!$this -> ion_auth -> logged_in()) {
			//redirect them to the login page
			redirect('login', 'refresh');
		} elseif (!($this -> ion_auth -> is_admin() || $this -> ion_auth -> in_group('Chairman') || $this -> ion_auth -> in_group('Program Owner') || $this -> ion_auth -> in_group('Course Owner'))) {
			//redirect them to the home page because they must be an administrator or owner to view this
			redirect('curriculum/peo/blank', 'refresh');
		} else {
			$pgm_id = $this -> input -> post('pgmtype');
			$crclm_id = $this -> input -> post('crclm_id');
			$term_id = $this -> input -> post('term_id');
			$crs_id = $this -> input -> post('crs_id');
			$qp_type = $this -> input -> post('qp_type');
			$model_qp_edit = $this -> manage_model_qp_model_re -> isModelQPDefined($crclm_id, $term_id, $crs_id, $qp_type);
			if ($model_qp_edit) {
				echo "1";
			} else {
				echo "0";
			}
		}
	}

	public function generate_model_qp_modal() {
		if (!$this -> ion_auth -> logged_in()) {
			//redirect them to the login page
			redirect('login', 'refresh');
		} elseif (!($this -> ion_auth -> is_admin() || $this -> ion_auth -> in_group('Chairman') || $this -> ion_auth -> in_group('Program Owner') || $this -> ion_auth -> in_group('Course Owner'))) {
			//redirect them to the home page because they must be an administrator or owner to view this
			redirect('curriculum/peo/blank', 'refresh');
		} else {
			$pgm_id = $this -> input -> post('pgmtype');
			$crclm_id = $this -> input -> post('crclm_id');
			$term_id = $this -> input -> post('term_id');
			$crs_id = $this -> input -> post('crs_id');
			$qp_type = $this -> input -> post('qp_type');
			$model_qp_details = $this -> manage_model_qp_model_re -> generate_model_qp($pgm_id);
			//$data['qp_unit_data'] 	= $model_qp_details['qpf_unit_details'] ;
			if(!empty( $model_qp_details['qpf_mq_details'])){$model_qp_data['qp_mq_data'] = $model_qp_details['qpf_mq_details'];}
			$model_qp_data['qp_entity'] 		= $model_qp_details['qp_entity_config'];
			$qpd_id = NULL;
			$result = $this -> manage_model_qp_model_re -> model_qp_edit_details($crclm_id, $term_id, $crs_id, $qp_type, $qpd_id);
			$model_qp_data['meta_data'] = $result['qp_meta_data'];
			$qpd_id = $model_qp_data['meta_data'][0]['qpd_id'];
			$crs_id = $crs_id;
			if(!empty($result['co_data']) && !empty($result['po_data']) && !empty($result['topic_data']) && !empty($result['level_data']) && !empty($result['pi_data'])){
			$co_data_val = $result['co_data'];
			$po_data_val = $result['po_data'];
			$topic_data_val = $result['topic_data'];
			$level_data_val = $result['level_data'];
			$pi_data_val = $result['pi_data'];
			if(!empty( $result['qt_data'])){$qt_data_val = $result['qt_data'];}
			}
			$table = '';

			$qp_mapping_entity = $this -> manage_model_qp_model_re -> mapping_entity();
			$model_qp_data['entity_list'] = $qp_mapping_entity;

			$qp_size = count($result['question_paper_data']);
			$question_paper_data = $result['question_paper_data'];
			$j = 0;
			for ($q = 0; $q < $qp_size; $q++) {
				$size = count($question_paper_data[$q]);
				for ($qp = 0; $qp < $size; $qp++) {
					$table .= '<tr>';
					$table .= '<td>' . $question_paper_data[$q][$qp]['qp_unit_code'] . '</td>';
					$table .= '<td>' . $question_paper_data[$q][$qp]['qp_subq_code'] . '</td>';
					$table .= '<td>' . $question_paper_data[$q][$qp]['qp_content'] . '</td>';
					foreach ($qp_mapping_entity as $entity) {
						if ($entity['entity_id'] == 11) {
							$table .= '<td>' . $co_data_val[$j] . '</td>';
						}
					}
					foreach ($qp_mapping_entity as $entity) {
						if ($entity['entity_id'] == 10) {
							$table .= '<td>' . $topic_data_val[$j] . '</td>';
						}
					}
					foreach ($qp_mapping_entity as $entity) {
						if ($entity['entity_id'] == 23) {
							$table .= '<td>' . $level_data_val[$j] . '</td>';
						}
					}
					foreach ($qp_mapping_entity as $entity) {
						if ($entity['entity_id'] == 6) {
							$table .= '<td>' . $po_data_val[$j] . '</td>';
						}
					}
					foreach ($qp_mapping_entity as $entity) {
						if ($entity['entity_id'] == 22) {
							$table .= '<td>' . $pi_data_val[$j] . '</td>';
						}
					}
					foreach ($qp_mapping_entity as $entity) {
						if ($entity['entity_id'] == 29) {
							$table .= '<td>' . $qt_data_val[$j] . '</td>';
						}
					}
					$table .= '<td style="text-align: -webkit-right;">' . $question_paper_data[$q][$qp]['qp_subq_marks'] . '</td>';

					$table .= '</tr>';
					$j++;
				}
			}

			$model_qp_data['qp_table'] = $table;

			if ($qpd_id != '') {
				$model_qp_data['blooms_graph_data'] = $this -> manage_model_qp_model_re -> getBloomsLevelPlannedCoverageDistribution($crs_id, $qpd_id, $qp_type);
				$this -> db -> reconnect();
				$model_qp_data['blooms_level_marks_graph_data'] = $this -> manage_model_qp_model_re -> getBloomsLevelMarksDistribution($crs_id, $qpd_id, $qp_type);
				$this -> db -> reconnect();
				$model_qp_data['co_planned_coverage_graph_data'] = $this -> manage_model_qp_model_re -> getCOLevelMarksDistribution($crs_id, $qpd_id);
				$this -> db -> reconnect();
				$model_qp_data['topic_planned_coverage_graph_data'] = $this -> manage_model_qp_model_re -> getTopicCoverageDistribution($crs_id, $qpd_id);

				$BloomsLevel = array();
				$PlannedPercentageDistribution = array();
				$ActualPercentageDistribution = array();
				$BloomsLevelDescription = array();
				foreach ($model_qp_data['blooms_graph_data'] as $bloom_data) {
					$BloomsLevel[] = $bloom_data['BloomsLevel'];
					$PlannedPercentageDistribution[] = $bloom_data['PlannedPercentageDistribution'];
					$ActualPercentageDistribution[] = $bloom_data['ActualPercentageDistribution'];
					$BloomsLevelDescription[] = $bloom_data['description'];
				}
				$bloom_level_array_cs = implode(",", $BloomsLevel);
				$bloom_level_array_cs = $bloom_level_array_cs;
				$PlannedPercentageDistribution_cs = implode(",", $PlannedPercentageDistribution);
				$PlannedPercentageDistribution_cs = $PlannedPercentageDistribution_cs;
				$ActualPercentageDistribution_cs = implode(",", $ActualPercentageDistribution);
				$ActualPercentageDistribution_cs = $ActualPercentageDistribution_cs;
				$BloomsLevelDescription_cs = implode(",", $BloomsLevelDescription);
				$BloomsLevelDescription_cs = $BloomsLevelDescription_cs;
				$model_qp_data['BloomsLevel'] = array('name' => 'BloomsLevel', 'id' => 'BloomsLevel', 'type' => 'hidden', 'value' => $bloom_level_array_cs);
				$model_qp_data['PlannedPercentageDistribution'] = array('name' => 'PlannedPercentageDistribution', 'id' => 'PlannedPercentageDistribution', 'type' => 'hidden', 'value' => $PlannedPercentageDistribution_cs);
				$model_qp_data['ActualPercentageDistribution'] = array('name' => 'ActualPercentageDistribution', 'id' => 'ActualPercentageDistribution', 'type' => 'hidden', 'value' => $ActualPercentageDistribution_cs);
				$model_qp_data['BloomsLevelDescription'] = array('name' => 'BloomsLevelDescription', 'id' => 'BloomsLevelDescription', 'type' => 'hidden', 'value' => $BloomsLevelDescription_cs);
				//BloomsLevelMarksDistribution
				$blooms_level_marks_dist = array();
				$total_marks_marks_dist = array();
				$percentage_distribution_marks_dist = array();
				$bloom_level_marks_description = array();
				foreach ($model_qp_data['blooms_level_marks_graph_data'] as $bloom_lvl_marks_data) {
					$blooms_level_marks_dist[] = $bloom_lvl_marks_data['BloomsLevel'];
					$total_marks_marks_dist[] = $bloom_lvl_marks_data['TotalMarks'];
					$percentage_distribution_marks_dist[] = $bloom_lvl_marks_data['PercentageDistribution'];
					$bloom_level_marks_description[] = $bloom_lvl_marks_data['description'];
				}
				$blooms_level_marks_dist_cs = implode(",", $blooms_level_marks_dist);
				$blooms_level_marks_dist_cs = $blooms_level_marks_dist_cs;
				$total_marks_marks_dist_cs = implode(",", $total_marks_marks_dist);
				$total_marks_marks_dist_cs = $total_marks_marks_dist_cs;
				$percentage_distribution_marks_dist_cs = implode(",", $percentage_distribution_marks_dist);
				$percentage_distribution_marks_dist_cs = $percentage_distribution_marks_dist_cs;
				$bloom_level_marks_description_cs = implode(",", $bloom_level_marks_description);
				$bloom_level_marks_description_cs = $bloom_level_marks_description_cs;
				$model_qp_data['blooms_level_marks_dist'] = array('name' => 'blooms_level_marks_dist', 'id' => 'blooms_level_marks_dist', 'type' => 'hidden', 'value' => $blooms_level_marks_dist_cs);
				$model_qp_data['total_marks_marks_dist'] = array('name' => 'total_marks_marks_dist', 'id' => 'total_marks_marks_dist', 'type' => 'hidden', 'value' => $total_marks_marks_dist_cs);
				$model_qp_data['percentage_distribution_marks_dist'] = array('name' => 'percentage_distribution_marks_dist', 'id' => 'percentage_distribution_marks_dist', 'type' => 'hidden', 'value' => $percentage_distribution_marks_dist_cs);
				$model_qp_data['bloom_level_marks_description'] = array('name' => 'bloom_level_marks_description', 'id' => 'bloom_level_marks_description', 'type' => 'hidden', 'value' => $bloom_level_marks_description_cs);
				//Course Outcome Planned Coverage Distribution
				$clo_code = array();
				$clo_total_marks_dist = array();
				$clo_percentage_dist = array();
				$clo_statement_dist = array();
				foreach ($model_qp_data['co_planned_coverage_graph_data'] as $clo_data) {
					$clo_code[] = $clo_data['clocode'];
					$clo_total_marks_dist[] = $clo_data['TotalMarks'];
					$clo_percentage_dist[] = $clo_data['PercentageDistribution'];
					$clo_statement_dist[] = $clo_data['clo_statement'];
				}
				$clo_code_cs = implode(",", $clo_code);
				$clo_code_cs = $clo_code_cs;
				$clo_total_marks_dist_cs = implode(",", $clo_total_marks_dist);
				$clo_total_marks_dist_cs = $clo_total_marks_dist_cs;
				$clo_percentage_dist_cs = implode(",", $clo_percentage_dist);
				$clo_percentage_dist_cs = $clo_percentage_dist_cs;
				$clo_statement_dist_cs = implode(",", $clo_statement_dist);
				$clo_statement_dist_cs = $clo_statement_dist_cs;
				$model_qp_data['clo_code'] = array('name' => 'clo_code', 'id' => 'clo_code', 'type' => 'hidden', 'value' => $clo_code_cs);
				$model_qp_data['clo_total_marks_dist'] = array('name' => 'clo_total_marks_dist', 'id' => 'clo_total_marks_dist', 'type' => 'hidden', 'value' => $clo_total_marks_dist_cs);
				$model_qp_data['clo_percentage_dist'] = array('name' => 'clo_percentage_dist', 'id' => 'clo_percentage_dist', 'type' => 'hidden', 'value' => $clo_percentage_dist_cs);
				$model_qp_data['clo_statement_dist'] = array('name' => 'clo_statement_dist', 'id' => 'clo_statement_dist', 'type' => 'hidden', 'value' => $clo_statement_dist_cs);
				//topicCoverageDistribution
				$topic_title = array();
				$topic_marks_dist = array();
				$topic_percentage_dist = array();
				foreach ($model_qp_data['topic_planned_coverage_graph_data'] as $topic_data) {
					$topic_title[] = $topic_data['topic_title'];
					$topic_marks_dist[] = $topic_data['TotalMarks'];
					$topic_percentage_dist[] = $topic_data['PercentageDistribution'];
				}
				$topic_title_cs = implode(",", $topic_title);
				$topic_title_cs = $topic_title_cs;
				$topic_marks_dist_cs = implode(",", $topic_marks_dist);
				$topic_marks_dist_cs = $topic_marks_dist_cs;
				$topic_percentage_dist_cs = implode(",", $topic_percentage_dist);
				$topic_percentage_dist_cs = $topic_percentage_dist_cs;
				$model_qp_data['topic_title'] = array('name' => 'topic_title', 'id' => 'topic_title', 'type' => 'hidden', 'value' => $topic_title_cs);
				$model_qp_data['topic_marks_dist'] = array('name' => 'topic_marks_dist', 'id' => 'topic_marks_dist', 'type' => 'hidden', 'value' => $topic_marks_dist_cs);
				$model_qp_data['topic_percentage_dist'] = array('name' => 'topic_percentage_dist', 'id' => 'topic_percentage_dist', 'type' => 'hidden', 'value' => $topic_percentage_dist_cs);
			}
			$this -> load -> view('question_paper/model_qp/model_qp_modal_display', $model_qp_data);
		}
	}

	public function generate_model_qp_modal_tee() {
		if (!$this -> ion_auth -> logged_in()) {
			//redirect them to the login page
			redirect('login', 'refresh');
		} elseif (!($this -> ion_auth -> is_admin() || $this -> ion_auth -> in_group('Chairman') || $this -> ion_auth -> in_group('Program Owner') || $this -> ion_auth -> in_group('Course Owner'))) {
			//redirect them to the home page because they must be an administrator or owner to view this
			redirect('curriculum/peo/blank', 'refresh');
		} else {
			$pgm_id = $this -> input -> post('pgmtype');
			$crclm_id = $this -> input -> post('crclm_id');
			$term_id = $this -> input -> post('term_id');
			$crs_id = $this -> input -> post('crs_id');
			$qp_type = $this -> input -> post('qp_type');
			$qpd_id = $this -> input -> post('qpd_id');
                        $nba_flag = $this -> input -> post('nba_flag');
                        
			$model_qp_details = $this -> manage_model_qp_model_re -> generate_model_qp($pgm_id);
			//$data['qp_unit_data'] 	= $model_qp_details['qpf_unit_details'] ;
			if(!empty($model_qp_details['qpf_mq_details'])){$model_qp_data['qp_mq_data'] = $model_qp_details['qpf_mq_details'];}
			$model_qp_data['qp_entity'] 		= $model_qp_details['qp_entity_config'];
			$data['qp_entity'] 		= $model_qp_details['qp_entity_config'];
			$result = $this -> manage_model_qp_model_re -> model_qp_edit_details_tee($crclm_id, $term_id, $crs_id, $qp_type, $qpd_id);

			$model_qp_data['meta_data'] = $result['qp_meta_data'];
			if(!empty( $result['co_data'])){$co_data_val = $result['co_data'];}
			if(!empty( $result['po_data'])){$po_data_val = $result['po_data'];}
			if(!empty( $result['topic_data'])){$topic_data_val = $result['topic_data'];}
			if(!empty( $result['level_data'])){$level_data_val = $result['level_data'];}
			if(!empty( $result['pi_data'])){$pi_data_val = $result['pi_data'];}
			if(!empty( $result['qt_data'])){$qt_data_val = $result['qt_data'];}
			$table = '';

			$qp_mapping_entity = $this -> manage_model_qp_model_re -> mapping_entity();
			$model_qp_data['entity_list'] = $qp_mapping_entity;

			$qp_size = count($result['question_paper_data']);
			$question_paper_data = $result['question_paper_data'];
			$j = 0;
			for ($q = 0; $q < $qp_size; $q++) {
				$size = count($question_paper_data[$q]);
				for ($qp = 0; $qp < $size; $qp++) {
					$table .= '<tr>';
					$table .= '<td>' . $question_paper_data[$q][$qp]['qp_unit_code'] . '</td>';
					$table .= '<td>' . $question_paper_data[$q][$qp]['qp_subq_code'] . '</td>';
					$table .= '<td>' . $question_paper_data[$q][$qp]['qp_content'] . '</td>';
					foreach ($qp_mapping_entity as $entity) {
						if ($entity['entity_id'] == 11) {
							$table .= '<td>' . $co_data_val[$j] . '</td>';
						}
					}
					foreach ($qp_mapping_entity as $entity) {
						if ($entity['entity_id'] == 10) {
							$table .= '<td>' . $topic_data_val[$j] . '</td>';
						}
					}
					foreach ($qp_mapping_entity as $entity) {
						if ($entity['entity_id'] == 23) {
							$table .= '<td>' . $level_data_val[$j] . '</td>';
						}
					}
					if(!empty($po_data_val)){
						foreach ($qp_mapping_entity as $entity) {
							if ($entity['entity_id'] == 6 ){
									if(!empty($po_data_val[$j])){$table .= '<td>' . $po_data_val[$j] . '</td>';}else{
								$table .= '<td> </td>';
								}
							}
						}
					}
					foreach ($qp_mapping_entity as $entity) {
						if ($entity['entity_id'] == 22) {
							$table .= '<td>' . $pi_data_val[$j] . '</td>';
						}
					}
					foreach ($qp_mapping_entity as $entity) {
						if ($entity['entity_id'] == 29) {
							$table .= '<td>' . $qt_data_val[$j] . '</td>';
						}
					}
					$table .= '<td style="text-align: -webkit-right;" >' . $question_paper_data[$q][$qp]['qp_subq_marks'] . '</td>';

					$table .= '</tr>';
					$j++;
				}
			}

			$model_qp_data['qp_table'] = $table;

			$this -> db -> reconnect();
			$model_qp_data['blooms_graph_data'] = $this -> manage_model_qp_model_re -> getBloomsLevelPlannedCoverageDistribution($crs_id, $qpd_id, $qp_type);
			$this -> db -> reconnect();
			$model_qp_data['blooms_level_marks_graph_data'] = $this -> manage_model_qp_model_re -> getBloomsLevelMarksDistribution($crs_id, $qpd_id, $qp_type);
			$this -> db -> reconnect();
			$model_qp_data['co_planned_coverage_graph_data'] = $this -> manage_model_qp_model_re -> getCOLevelMarksDistribution($crs_id, $qpd_id);
			$this -> db -> reconnect();
			$model_qp_data['topic_planned_coverage_graph_data'] = $this -> manage_model_qp_model_re -> getTopicCoverageDistribution($crs_id, $qpd_id);

			$BloomsLevel = array();
			$PlannedPercentageDistribution = array();
			$ActualPercentageDistribution = array();
			$BloomsLevelDescription = array();
			foreach ($model_qp_data['blooms_graph_data'] as $bloom_data) {
				$BloomsLevel[] = $bloom_data['BloomsLevel'];
				$PlannedPercentageDistribution[] = $bloom_data['PlannedPercentageDistribution'];
				$ActualPercentageDistribution[] = $bloom_data['ActualPercentageDistribution'];
				$BloomsLevelDescription[] = $bloom_data['description'];
			}
			$bloom_level_array_cs = implode(",", $BloomsLevel);
			$bloom_level_array_cs = $bloom_level_array_cs;
			$PlannedPercentageDistribution_cs = implode(",", $PlannedPercentageDistribution);
			$PlannedPercentageDistribution_cs = $PlannedPercentageDistribution_cs;
			$ActualPercentageDistribution_cs = implode(",", $ActualPercentageDistribution);
			$ActualPercentageDistribution_cs = $ActualPercentageDistribution_cs;
			$BloomsLevelDescription_cs = implode(",", $BloomsLevelDescription);
			$BloomsLevelDescription_cs = $BloomsLevelDescription_cs;
			$model_qp_data['BloomsLevel'] = array('name' => 'BloomsLevel', 'id' => 'BloomsLevel', 'type' => 'hidden', 'value' => $bloom_level_array_cs);
			$model_qp_data['PlannedPercentageDistribution'] = array('name' => 'PlannedPercentageDistribution', 'id' => 'PlannedPercentageDistribution', 'type' => 'hidden', 'value' => $PlannedPercentageDistribution_cs);
			$model_qp_data['ActualPercentageDistribution'] = array('name' => 'ActualPercentageDistribution', 'id' => 'ActualPercentageDistribution', 'type' => 'hidden', 'value' => $ActualPercentageDistribution_cs);
			$model_qp_data['BloomsLevelDescription'] = array('name' => 'BloomsLevelDescription', 'id' => 'BloomsLevelDescription', 'type' => 'hidden', 'value' => $BloomsLevelDescription_cs);
			//BloomsLevelMarksDistribution
			$blooms_level_marks_dist = array();
			$total_marks_marks_dist = array();
			$percentage_distribution_marks_dist = array();
			$bloom_level_marks_description = array();
			foreach ($model_qp_data['blooms_level_marks_graph_data'] as $bloom_lvl_marks_data) {
				$blooms_level_marks_dist[] = $bloom_lvl_marks_data['BloomsLevel'];
				$total_marks_marks_dist[] = $bloom_lvl_marks_data['TotalMarks'];
				$percentage_distribution_marks_dist[] = $bloom_lvl_marks_data['PercentageDistribution'];
				$bloom_level_marks_description[] = $bloom_lvl_marks_data['description'];
			}
			$blooms_level_marks_dist_cs = implode(",", $blooms_level_marks_dist);
			$blooms_level_marks_dist_cs = $blooms_level_marks_dist_cs;
			$total_marks_marks_dist_cs = implode(",", $total_marks_marks_dist);
			$total_marks_marks_dist_cs = $total_marks_marks_dist_cs;
			$percentage_distribution_marks_dist_cs = implode(",", $percentage_distribution_marks_dist);
			$percentage_distribution_marks_dist_cs = $percentage_distribution_marks_dist_cs;
			$bloom_level_marks_description_cs = implode(",", $bloom_level_marks_description);
			$bloom_level_marks_description_cs = $bloom_level_marks_description_cs;
			$model_qp_data['blooms_level_marks_dist'] = array('name' => 'blooms_level_marks_dist', 'id' => 'blooms_level_marks_dist', 'type' => 'hidden', 'value' => $blooms_level_marks_dist_cs);
			$model_qp_data['total_marks_marks_dist'] = array('name' => 'total_marks_marks_dist', 'id' => 'total_marks_marks_dist', 'type' => 'hidden', 'value' => $total_marks_marks_dist_cs);
			$model_qp_data['percentage_distribution_marks_dist'] = array('name' => 'percentage_distribution_marks_dist', 'id' => 'percentage_distribution_marks_dist', 'type' => 'hidden', 'value' => $percentage_distribution_marks_dist_cs);
			$model_qp_data['bloom_level_marks_description'] = array('name' => 'bloom_level_marks_description', 'id' => 'bloom_level_marks_description', 'type' => 'hidden', 'value' => $bloom_level_marks_description_cs);
			//Course Outcome Planned Coverage Distribution
			$clo_code = array();
			$clo_total_marks_dist = array();
			$clo_percentage_dist = array();
			$clo_statement_dist = array();
			foreach ($model_qp_data['co_planned_coverage_graph_data'] as $clo_data) {
				$clo_code[] = $clo_data['clocode'];
				$clo_total_marks_dist[] = $clo_data['TotalMarks'];
				$clo_percentage_dist[] = $clo_data['PercentageDistribution'];
				$clo_statement_dist[] = $clo_data['clo_statement'];
			}
			$clo_code_cs = implode(",", $clo_code);
			$clo_code_cs = $clo_code_cs;
			$clo_total_marks_dist_cs = implode(",", $clo_total_marks_dist);
			$clo_total_marks_dist_cs = $clo_total_marks_dist_cs;
			$clo_percentage_dist_cs = implode(",", $clo_percentage_dist);
			$clo_percentage_dist_cs = $clo_percentage_dist_cs;
			$clo_statement_dist_cs = implode(",", $clo_statement_dist);
			$clo_statement_dist_cs = $clo_statement_dist_cs;
			$model_qp_data['clo_code'] = array('name' => 'clo_code', 'id' => 'clo_code', 'type' => 'hidden', 'value' => $clo_code_cs);
			$model_qp_data['clo_total_marks_dist'] = array('name' => 'clo_total_marks_dist', 'id' => 'clo_total_marks_dist', 'type' => 'hidden', 'value' => $clo_total_marks_dist_cs);
			$model_qp_data['clo_percentage_dist'] = array('name' => 'clo_percentage_dist', 'id' => 'clo_percentage_dist', 'type' => 'hidden', 'value' => $clo_percentage_dist_cs);
			$model_qp_data['clo_statement_dist'] = array('name' => 'clo_statement_dist', 'id' => 'clo_statement_dist', 'type' => 'hidden', 'value' => $clo_statement_dist_cs);
			//topicCoverageDistribution
			$topic_title = array();
			$topic_marks_dist = array();
			$topic_percentage_dist = array();
			foreach ($model_qp_data['topic_planned_coverage_graph_data'] as $topic_data) {
				$topic_title[] = $topic_data['topic_title'];
				$topic_marks_dist[] = $topic_data['TotalMarks'];
				$topic_percentage_dist[] = $topic_data['PercentageDistribution'];
			}
			$topic_title_cs = implode(",", $topic_title);
			$topic_title_cs = $topic_title_cs;
			$topic_marks_dist_cs = implode(",", $topic_marks_dist);
			$topic_marks_dist_cs = $topic_marks_dist_cs;
			$topic_percentage_dist_cs = implode(",", $topic_percentage_dist);
			$topic_percentage_dist_cs = $topic_percentage_dist_cs;
			$model_qp_data['topic_title'] = array('name' => 'topic_title', 'id' => 'topic_title', 'type' => 'hidden', 'value' => $topic_title_cs);
			$model_qp_data['topic_marks_dist'] = array('name' => 'topic_marks_dist', 'id' => 'topic_marks_dist', 'type' => 'hidden', 'value' => $topic_marks_dist_cs);
			$model_qp_data['topic_percentage_dist'] = array('name' => 'topic_percentage_dist', 'id' => 'topic_percentage_dist', 'type' => 'hidden', 'value' => $topic_percentage_dist_cs);
			
                        if(!$nba_flag) {
                            $this -> load -> view('question_paper/model_qp/model_qp_modal_display', $model_qp_data);
                        } else {
                            $this -> load -> view('nba_sar/ug/tier1/criterion_2/c2_2_2_qp_display', $model_qp_data);
                        }

		}
	}

	/**Function to check whether framework for a course has been set or not**/
	public function check_framework() {
		if (!$this -> ion_auth -> logged_in()) {
			//redirect them to the login page
			redirect('login', 'refresh');
		} elseif (!($this -> ion_auth -> is_admin() || $this -> ion_auth -> in_group('Chairman') || $this -> ion_auth -> in_group('Program Owner') || $this -> ion_auth -> in_group('Course Owner'))) {
			//redirect them to the home page because they must be an administrator or owner to view this
			redirect('curriculum/peo/blank', 'refresh');
		} else {
			$pgm_id = $this -> input -> post('pgm_id');
			$check_result = $this -> manage_model_qp_model_re -> check_framework($pgm_id);

			echo $check_result;
		}
	}	
	
	/**Function to check whether framework for a course has been set or not**/
	public function check_framework_tee() {
		if (!$this -> ion_auth -> logged_in()) {
			//redirect them to the login page
			redirect('login', 'refresh');
		} elseif (!($this -> ion_auth -> is_admin() || $this -> ion_auth -> in_group('Chairman') || $this -> ion_auth -> in_group('Program Owner') || $this -> ion_auth -> in_group('Course Owner'))) {
			//redirect them to the home page because they must be an administrator or owner to view this
			redirect('curriculum/peo/blank', 'refresh');
		} else {
			$pgm_id = $this -> input -> post('pgm_id');
			$crs_id = $this -> input -> post('crs_id');
			$check_result = $this -> manage_model_qp_model_re -> check_framework_tee($pgm_id , $crs_id);

			echo json_encode($check_result);
		}
	}

	/*
	 Function to generate add TEE question paper for the  course
	 */

	public function generate_tee_qp($pgm_id = NULL, $crclm_id = NULL, $term_id = NULL, $crs_id = NULL, $qp_type = NULL, $qpd_id = NULL) {

		if (!$this -> ion_auth -> logged_in()) {
			//redirect them to the login page
			redirect('login', 'refresh');
		} elseif (!($this -> ion_auth -> is_admin() || $this -> ion_auth -> in_group('Chairman') || $this -> ion_auth -> in_group('Program Owner') || $this -> ion_auth -> in_group('Course Owner'))) {
			//redirect them to the home page because they must be an administrator or owner to view this
			redirect('curriculum/peo/blank', 'refresh');
		} else {
			$model_qp_existance = $this -> manage_model_qp_model_re -> model_qp_existance_for_tee($crs_id, $qp_type);

			//$dp_def=$this->manage_model_qp_model_re->get_qpd_id($crs_id,$qp_type);
			
		
			if ($model_qp_existance == 0 && $qpd_id == NULL) {
				$model_qp_details = $this -> manage_model_qp_model_re -> generate_model_qp($pgm_id);
				$qp_mapping_entity = $this -> manage_model_qp_model_re -> mapping_entity();
				$data['entity_id'] = $qp_mapping_entity;
				
				$data['qp_unit_data'] = $model_qp_details['qpf_unit_details'];
				$data['qp_mq_data']   = $model_qp_details['qpf_mq_details'];
				$data['qp_entity']    = $model_qp_details['qp_entity_config'];
				
				
				
				$course_data = $this -> manage_model_qp_model_re -> model_qp_course_data($crclm_id, $term_id, $crs_id);
				$model_qp_edit = $this -> manage_model_qp_model_re -> model_qp_edit_details($crclm_id, $term_id, $crs_id, $qp_type , $qpd_id);
	
				$qp_mapping_entity = $this -> manage_model_qp_model_re -> mapping_entity();
				$data['entity_list'] = $qp_mapping_entity;
				$data['co_details'] = $course_data['co_data'];
				$data['topic_details'] = $course_data['topic_list'];
				$data['bloom_data'] = $course_data['level_list'];
				$data['pi_list'] = $course_data['pi_code_list'];
				$data['course_title'] = $course_data['crs_title'];
				$data['crclm_title'] = $course_data['crclm_title'];
				$data['term_title'] = $course_data['term_title'];
				$data['question_type'] = $course_data['question_type'];
				$data['crclm_id'] = $crclm_id;
				$data['term_id'] = $term_id;
				$data['crs_id'] = $crs_id;
				$data['pgm_id'] = $pgm_id;
				$data['model_qp_existance'] = $model_qp_existance;

				$data['meta_data'] = $model_qp_edit['qp_meta_data'];
				$data['url_ids'] = $pgm_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_id;
				$data['title'] = 'Course term end examination question paper creation';
				
				$this -> load -> view('question_paper/model_qp/tee_qp_add_vw_re', $data);

			} else {
			
				if(empty($qpd_id)) {
					$dp_def = $this -> manage_model_qp_model_re -> get_qpd_id($crs_id, $qp_type);
					$qpd_id_data = end($dp_def);
					$qpd_id = ($qpd_id_data['qpd_id']); 
				}
			
				$qp_mapping_entity = $this -> manage_model_qp_model_re -> mapping_entity();
				$data['entity_id'] = $qp_mapping_entity;				
				$unit_data = $this -> manage_model_qp_model_re -> generate_model_unit($qpd_id);
				$model_qp_details = $this -> manage_model_qp_model_re -> generate_model_qp($pgm_id);
				$total_marks = $this -> manage_model_qp_model_re -> generate_total_marks($qpd_id);
				if($model_qp_details['qpf_unit_details']){$data['qp_unit_data'] = $model_qp_details['qpf_unit_details'];} else{$data['qp_unit_data'] =  $unit_data;}				
				if(!empty($data['qp_mq_data']) && $model_qp_details['qpf_unit_details'] ){$data['qp_mq_data'] = $model_qp_details['qpf_mq_details'];}else{$data['qp_mq_data'] = "";}
				$data['qp_entity'] = $model_qp_details['qp_entity_config'];
				$course_data = $this -> manage_model_qp_model_re -> model_qp_course_data($crclm_id, $term_id, $crs_id);
				$model_qp_edit = $this -> manage_model_qp_model_re -> model_qp_edit_details($crclm_id, $term_id, $crs_id, $qp_type, $qpd_id);
				$qp_mapping_entity = $this -> manage_model_qp_model_re -> mapping_entity();
				$unit_sum = $this -> manage_model_qp_model_re -> generate_model_unit_sum($qpd_id);
				$data['entity_list'] = $qp_mapping_entity;
				$data['total_marks'] = $total_marks['marks'];

				$data['co_details'] = $course_data['co_data'];
				$data['topic_details'] = $course_data['topic_list'];
				$data['bloom_data'] = $course_data['level_list'];
				$data['pi_list'] = $course_data['pi_code_list'];
				$data['course_title'] = $course_data['crs_title'];
				$data['crclm_title'] = $course_data['crclm_title'];
				$data['term_title'] = $course_data['term_title'];
				$data['question_type'] = $course_data['question_type'];
				$data['crclm_id'] = $crclm_id;
				$data['term_id'] = $term_id;
				$data['crs_id'] = $crs_id;
				$data['pgm_id'] = $pgm_id;
				$data['unit_data'] = $unit_data;
				$data['unit_sum']=$unit_sum;
				$data['model_qp_existance'] = $model_qp_existance;
				$data['qpd_id'] = $qpd_id;
				$data['meta_data'] = $model_qp_edit['qp_meta_data'];
				$entity_see = $this->lang->line('entity_see');


				$data['url_ids'] = $pgm_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_id;
				$data['title'] = 'Edit Term End Examination('.$entity_see.') Question Paper for Course : ' . $course_data['crs_title'][0]['crs_title'] . ' - (' . $course_data['crs_title'][0]['crs_code'] . ')';
				$this -> load -> view('question_paper/model_qp/tee_qp_edit_vw_re', $data);
			}
		}
	}

	/*
	 Function to copy model question paper to tee question paper with new id.
	 */
	public function copy_existing_model_to_tee_qp($pgm_id = NULL, $crclm_id = NULL, $term_id = NULL, $crs_id = NULL, $qp_type = NULL, $qpd_id = NULL) {
		if (!$this -> ion_auth -> logged_in()) {
			//redirect them to the login page
			redirect('login', 'refresh');
		} elseif (!($this -> ion_auth -> is_admin() || $this -> ion_auth -> in_group('Chairman') || $this -> ion_auth -> in_group('Program Owner') || $this -> ion_auth -> in_group('Course Owner'))) {
			//redirect them to the home page because they must be an administrator or owner to view this
			redirect('curriculum/peo/blank', 'refresh');
		} else {

			$model_qp_type = 4;
			// its hard coded value model qp is 4, tee is 5 and cia is 3.
			$model_qp_existance = $this -> manage_model_qp_model_re -> model_qp_existance($crs_id, $model_qp_type);
			$model_dp_def = $this -> manage_model_qp_model_re -> get_qpd_id($crs_id, $model_qp_type);
			$qpd_id = $model_dp_def[0]['qpd_id'];
			$model_qp_copy_data = $this -> manage_model_qp_model_re -> copy_existing_model_to_tee_qp($crs_id, $model_qp_type, $qpd_id);
			$new_qpd_id = $model_qp_copy_data[0]['new_qpd_id'];
			redirect('question_paper/manage_model_qp/generate_tee_qp/' . $pgm_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_id . '/' . $qp_type . '/' . $new_qpd_id);
		}

	}

	/*
	 Function to create the new TEE question paper every time.
	 @param pgm_id, crclm_id, term_id, crs_id, qpd_type_id
	 @return loading new tee question paper creation
	 */

	public function create_tee_qp($pgm_id = NULL, $crclm_id = NULL, $term_id = NULL, $crs_id = NULL, $qp_type = NULL) {
		if (!$this -> ion_auth -> logged_in()) {
			//redirect them to the login page
			redirect('login', 'refresh');
		} elseif (!($this -> ion_auth -> is_admin() || $this -> ion_auth -> in_group('Chairman') || $this -> ion_auth -> in_group('Program Owner') || $this -> ion_auth -> in_group('Course Owner'))) {
			//redirect them to the home page because they must be an administrator or owner to view this
			redirect('curriculum/peo/blank', 'refresh');
		} else {

			$model_qp_details = $this -> manage_model_qp_model_re -> generate_model_qp($pgm_id);
			
			$data['qp_unit_data'] = $model_qp_details['qpf_unit_details'];
			$data['qp_mq_data'] = $model_qp_details['qpf_mq_details'];
			$data['qp_entity'] = $model_qp_details['qp_entity_config'];

			$dp_def = $this -> manage_model_qp_model_re -> get_qpd_id($crs_id, $qp_type);
			if (!empty($dp_def)) {
				$qpd_id = $dp_def[0]['qpd_id'];
				$model_qp_edit = $this -> manage_model_qp_model_re -> model_qp_edit_details($crclm_id, $term_id, $crs_id, $qp_type, $qpd_id);
				$data['meta_data'] = $model_qp_edit['qp_meta_data'];
			} else {
				$qpd_id = NULL;
			}

			//$unit_data = $this->manage_model_qp_model_re->generate_model_unit($qpd_id);
			$course_data = $this -> manage_model_qp_model_re -> model_qp_course_data($crclm_id, $term_id, $crs_id);
			//$model_qp_edit = $this->manage_model_qp_model_re->model_qp_edit_details($crclm_id, $term_id, $crs_id, $qp_type, $qpd_id);
			$qp_mapping_entity = $this -> manage_model_qp_model_re -> mapping_entity();
			$data['entity_list'] = $qp_mapping_entity;
			$data['co_details'] = $course_data['co_data'];
			$data['topic_details'] = $course_data['topic_list'];
			$data['bloom_data'] = $course_data['level_list'];
			$data['pi_list'] = $course_data['pi_code_list'];
			$data['course_title'] = $course_data['crs_title'];
			$data['course_title'] = $course_data['crs_title'];
			$data['crclm_title'] = $course_data['crclm_title'];
			$data['term_title'] = $course_data['term_title'];
			$data['crclm_id'] = $crclm_id;
			$data['term_id'] = $term_id;
			$data['crs_id'] = $crs_id;
			$data['pgm_id'] = $pgm_id;
			$data['model_qp_existance'] = 0;
			//$data['unit_data']=$dp_def[0]['qpd_id'];
			//$data['unit_data']=$unit_data;
			//$data['meta_data'] = $model_qp_edit['qp_meta_data'];
			/* $data['main_qp_data'] = $model_qp_edit['main_qp_data'];
			 $data ['co_data'] = $model_qp_edit['co_data'];
			 $data ['topic_data'] = $model_qp_edit['topic_data'];
			 $data ['bloom_data'] = $model_qp_edit['bloom_data'];
			 $data ['pi_list'] = $model_qp_edit['pi_code_list'];
			 $data ['unit_def_data'] = $model_qp_edit['unit_def_data'];
			 $data ['entity_list'] = $model_qp_edit['entity_list']; */
			$data['url_ids'] = $pgm_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_id;
			$data['qpd_type'] = array('name' => 'qpd_type', 'id' => 'qpd_type', 'type' => 'text', 'value' => $qp_type, 'class' => 'input-small');
			$data['title'] = 'Add '.$this->lang->line('entity_see_full').'('.$this->lang->line('entity_tee').') Question Paper for Course : ' . $course_data['crs_title'][0]['crs_title'] . ' - (' . $course_data['crs_title'][0]['crs_code'] . ')';
			$this -> load -> view('question_paper/model_qp/tee_qp_add_vw_re', $data);
		}

	}

	public function fetch_tee_qp($pgm_id = NULL, $crclm_id = NULL, $term_id = NULL, $crs_id = NULL, $qp_type = NULL, $qp_existance = NULL) {
		if (!$this -> ion_auth -> logged_in()) {
			//redirect them to the login page
			redirect('login', 'refresh');
		} elseif (!($this -> ion_auth -> is_admin() || $this -> ion_auth -> in_group('Chairman') || $this -> ion_auth -> in_group('Program Owner') || $this -> ion_auth -> in_group('Course Owner'))) {
			//redirect them to the home page because they must be an administrator or owner to view this
			redirect('curriculum/peo/blank', 'refresh');
		} else {
			$tee_qp_data = $this -> manage_model_qp_model_re -> fetch_tee_qp_data($crclm_id, $term_id, $crs_id, $qp_type);
			$counter = 1;
                        $tee_qp_counter = 0;
                        $rubrics_qp_counter = 0;
			$table_creation = '<table id="modal_tee_qp_lis" class="table table-bordered table-hover"  aria-describedby="example_info" >';
			$table_creation .= '<tr>';
			$table_creation .= '<th colspan="12">'.$this->lang->line('entity_see').' QP List</th>';
			$table_creation .= '</tr>';
			$table_creation .= '<tr>';
			$table_creation .= '<th>QP Title</th>';
			$table_creation .= '<th>Created Date</th>';
			$table_creation .= '<th>Created By</th>';
			//$table_creation .= '<th>Modified Date</th>';
			//$table_creation .= '<th>Modified By</th>';
			$table_creation .= '<th colspan="2">Action</th>';
			$table_creation .= '<th>Roll-Out</th>';
			$table_creation .= '<th>View QP</th>';
			$table_creation .= '<th>Upload QP</th>';
			//$table_creation .= '<th>Analyze QP</th>';
			$table_creation .= '</tr>';
			$table_creation .= '<tbody>';
  
			foreach ($tee_qp_data as $tee) {
				if($tee['rubrics_flag'] != 1){
									$tee_qp_counter++;
					$table_creation .= '<tr>';
					$table_creation .= '<td>' . $tee['qpd_title'] . '</td>';
					$table_creation .= '<td>' . $tee['created_date'] . '</td>';
					$table_creation .= '<td>' . $tee['mtitle'] . ' ' . $tee['mfirst'] . ' ' . $tee['mlast'] . '</td>';
					$check_file_uploaded =  $this -> manage_model_qp_model_re -> check_file_uploaded($tee['qpd_id']);

					if ($tee['qp_rollout'] == 1) {

						$table_creation .= '<td><a id="edit_tee_qp" class="edit_tee_qp cursor_pointer" qp_status_abbr="' . base_url() . 'question_paper/manage_model_qp/tee_individual_qp_edit/' . $pgm_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_id . '/' . $tee['qpd_type'] . '/' . $tee['qpd_id'] . '" abbr="' . base_url() . 'question_paper/manage_model_qp/forcible_tee_qp_edit/' . $pgm_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_id . '/' . $tee['qpd_type'] . '/' . $tee['qpd_id'] . '" title="Edit"><i class="icon-pencil" title="Edit"></i></a></td>';

						$table_creation .= '<td><a id="edit_tee_qp" class="delete_individual_qp_message cursor_pointer" abbr="' . base_url() . 'question_paper/manage_model_qp/tee_individual_qp_delete/' . $pgm_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_id . '/' . $tee['qpd_type'] . '/' . $tee['qpd_id'] . '" title="Delete"><i class="icon-remove" title="Delete"></i></a></td>';

						$table_creation .= '<td><a id="rollout_tee_qp_' . $counter . '" class="btn btn-success cursor_pointer roll_out_update"  data-all_data = "'. $pgm_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_id . '/5/' . $tee['qpd_id'] . '" data-fetch_data = "' . base_url() . 'question_paper/manage_model_qp/fetch_tee_qp/' . $pgm_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_id . '/5" class = "myTagRemover get_crs_id" id = "' . $crs_id . '"title="Rolled-Out"  abbr="' . base_url() . 'question_paper/manage_model_qp/tee_qp_rollout/' . $pgm_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_id . '/' . $tee['qpd_type'] . '/' . $tee['qpd_id'] . '" value="' . $crs_id . '">Rolled-Out</i></a>';
						$table_creation .= '<input type="hidden" name="rollout_btn_id" id="rollout_btn_id" value="rollout_tee_qp_' . $counter . '" /></td>';

					} elseif ($tee['qp_rollout'] == 2) {

						$table_creation .= '<td><a id="edit_tee_qp" class="edit_tee_qp cursor_pointer" qp_status_abbr="' . base_url() . 'question_paper/manage_model_qp/tee_individual_qp_edit/' . $pgm_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_id . '/' . $tee['qpd_type'] . '/' . $tee['qpd_id'] . '" abbr="' . base_url() . 'question_paper/manage_model_qp/forcible_tee_qp_edit/' . $pgm_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_id . '/' . $tee['qpd_type'] . '/' . $tee['qpd_id'] . '" title="Edit"><i class="icon-pencil" title="Edit"></i></a></td>';

						$table_creation .= '<td><a id="edit_tee_qp" class="delete_individual_qp_message cursor_pointer" abbr="' . base_url() . 'question_paper/manage_model_qp/tee_individual_qp_delete/' . $pgm_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_id . '/' . $tee['qpd_type'] . '/' . $tee['qpd_id'] . '" title="Delete"><i class="icon-remove" title="Delete"></i></a></td>';				

						$table_creation .= '<td><button id="rollout_tee_qp_' . $counter . '" disabled="disabled" class="btn btn-success cursor_pointer" title="Question Paper Rolled out & Data Imported"  abbr="' . base_url() . 'question_paper/manage_model_qp/tee_qp_rollout/' . $pgm_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_id . '/' . $tee['qpd_type'] . '/' . $tee['qpd_id'] . '">Data Imported</i></button>';
					} else if(($tee['qp_rollout'] == -2)){
						$table_creation .= '<td><a id="edit_tee_qp" class="edit_tee_qp cursor_pointer" qp_status_abbr="' . base_url() . 'question_paper/manage_model_qp/tee_individual_qp_edit/' . $pgm_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_id . '/' . $tee['qpd_type'] . '/' . $tee['qpd_id'] . '" abbr="' . base_url() . 'question_paper/manage_model_qp/forcible_tee_qp_edit/' . $pgm_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_id . '/' . $tee['qpd_type'] . '/' . $tee['qpd_id'] . '" title="Edit"><i class="icon-pencil" title="Edit"></i></a></td>';

						$table_creation .= '<td><a id="edit_tee_qp" class="delete_individual_qp cursor_pointer" abbr="' . base_url() . 'question_paper/manage_model_qp/tee_individual_qp_delete/' . $pgm_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_id . '/' . $tee['qpd_type'] . '/' . $tee['qpd_id'] . '" title="Delete"><i class="icon-remove" title="Delete"></i></a></td>';
						$exist = $this-> manage_model_qp_model_re -> check_co_mapping($crclm_id, $term_id, $crs_id, $qp_type, $tee['qpd_id']);
						if($exist[0]['count'] != 0){
						$table_creation .= '<td><a id="rollout_tee_qp_' . $counter . '" class="btn btn-warning cursor_pointer roll_out_update" data-all_data = "'. $pgm_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_id . '/5/' . $tee['qpd_id'] . '" title="Rolled-Out Pending"  abbr="' . base_url() . 'question_paper/manage_model_qp/tee_qp_rollout/' . $pgm_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_id . '/' . $tee['qpd_type'] . '/' . $tee['qpd_id'] . '"      data-fetch_data = "' . base_url() . 'question_paper/manage_model_qp/fetch_tee_qp/' . $pgm_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_id . '/5" class = "myTagRemover get_crs_id" id = "' . $crs_id . '" value="' . $crs_id . '">Ready to Rollout</i></a></td>';
						}else{
						$table_creation .= '<td><a id="rollout_tee_qp_' . $counter . '" class="btn btn-warning cursor_pointer   roll_out_update_not" href="#roll_out_update_not" class = "myTagRemover get_crs_id" id = "' . $crs_id . '" value="' . $crs_id . '">Ready to Rollout</i></a></td>';
						}
					}else {
						$table_creation .= '<td><a id="edit_tee_qp" class="edit_tee_qp cursor_pointer" qp_status_abbr="' . base_url() . 'question_paper/manage_model_qp/tee_individual_qp_edit/' . $pgm_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_id . '/' . $tee['qpd_type'] . '/' . $tee['qpd_id'] . '" abbr="' . base_url() . 'question_paper/manage_model_qp/forcible_tee_qp_edit/' . $pgm_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_id . '/' . $tee['qpd_type'] . '/' . $tee['qpd_id'] . '" title="Edit"><i class="icon-pencil" title="Edit"></i></a></td>';

						$table_creation .= '<td><a id="edit_tee_qp" class="delete_individual_qp cursor_pointer" abbr="' . base_url() . 'question_paper/manage_model_qp/tee_individual_qp_delete/' . $pgm_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_id . '/' . $tee['qpd_type'] . '/' . $tee['qpd_id'] . '" title="Delete"><i class="icon-remove" title="Delete"></i></a></td>';

						$table_creation .= '<td><a id="rollout_tee_qp_' . $counter . '" class="btn btn-warning cursor_pointer roll_out_update" data-all_data = "'. $pgm_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_id . '/5/' . $tee['qpd_id'] . '" title="Rolled-Out Pending"  abbr="' . base_url() . 'question_paper/manage_model_qp/tee_qp_rollout/' . $pgm_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_id . '/' . $tee['qpd_type'] . '/' . $tee['qpd_id'] . '"      data-fetch_data = "' . base_url() . 'question_paper/manage_model_qp/fetch_tee_qp/' . $pgm_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_id . '/5" class = "myTagRemover get_crs_id" id = "' . $crs_id . '" value="' . $crs_id . '">Pending </i></a></td>';
					}
					$table_creation .= '<td><a class="cursor_pointer view_qp" id="view_qp_' . $counter . '" abbr="' . $pgm_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_id . '/' . $tee['qpd_type'] . '/' . $tee['qpd_id'] . '">View QP</a></td>';
					$upload_name  = "Upload";	
					// <a  class="cursor_pointer download_defined_qp_template" href= "' . base_url() . 'question_paper/manage_model_qp/to_excel/'.  $tee['qpd_id'] .'/'.$crs_id .'/" data-qpd_id = "'. $tee['qpd_id'] .'"> View  </a> |		
					$file_name = '';					
					if(!empty($check_file_uploaded)){ $upload_name = "Uploaded";$remove_data = '| <a class="cursor_pointer delete_uploaded_qp"  data-crs_id = "'.$crs_id.'",  data-prog_id = "'.$pgm_id.'", data-crclm_id = "'.$crclm_id.'", data-file_name = "'. substr($check_file_uploaded[0]['file_name'], strpos($check_file_uploaded[0]['file_name'], "sep_") + 4) .'" data-qpd_type_name = "TEE" data-term_id = "'.$term_id.'"  data-sec_id = "0" data-ao_id = "" data-qpd_id = "'. $tee['qpd_id'] .'"  data-qpd_type="'. $tee['qpd_type'].'" > Delete </a>';}else{ $remove_data = '';}				
					$table_creation .= '<td><a type="button" id="file_uploader" value="Upload" title = ".doc, .docx, .odt, .rtf and .pdf files of size 10MB are allowed." data-toggle= "modal" data-qpd_type_name = "TEE" data-id="'.$crs_id.'" data-qpd_id = "'. $tee['qpd_id'] .'"  data-sec_id = "0" data-ao_id = ""  data-qpd_type="'. $tee['qpd_type']  .'" class="upload_qp cursor_pointer"> '. $upload_name .' </a> | <a role="button" data-crs_id = "'.$crs_id.'",  data-prog_id = "'.$pgm_id.'", data-crclm_id = "'.$crclm_id.'", data-qpd_type_name = "TEE" data-term_id = "'.$term_id.'"  data-sec_id = "0" data-ao_id = "" data-qpd_id = "'. $tee['qpd_id'] .'"  data-qpd_type="'. $tee['qpd_type']  .'" href="#" class = "upload_qp_view"> View </a> '.$remove_data.' </td>';
					$table_creation .= '</tr>';
					$counter++;
				}
			}
			$table_creation .= '</tbody>';
			$table_creation .= '</table>';
                        
                        $rubrics_table = '';
                        $rubrics_table .= '<table id="rubrics_table" class="table table-bordered dataTable" >';
                        $rubrics_table .= '<thead>';
                        $rubrics_table .= '<tr>';
                        $rubrics_table .= '<th colspan="12">'.$this->lang->line('entity_see').' Rubrics List</th>';
                        $rubrics_table .= '</tr>';
                        $rubrics_table .= '<tr>';
			$rubrics_table .= '<th>Rubrics Title</th>';
			$rubrics_table .= '<th>Created Date</th>';
			$rubrics_table .= '<th>Created By</th>';
			$rubrics_table .= '<th colspan="2">Action</th>';
			$rubrics_table .= '<th>Finalize Rubrics</th>';
			$rubrics_table .= '<th>View Rubrics</th>';
			$rubrics_table .= '<th> Upload QP</th>';
			$rubrics_table .= '</tr>';
                        $rubrics_table .= '</thead>';
                        $rubrics_table .= '<tbody>';
                        foreach ($tee_qp_data as $tee) {
                            if($tee['rubrics_flag'] == 1){
                                $rubrics_qp_counter++;
                                $rubrics_table .= '<tr>';
				$rubrics_table .= '<td>' . $tee['qpd_title'] . '</td>';
				$rubrics_table .= '<td>' . $tee['created_date'] . '</td>';
				$rubrics_table .= '<td>' . $tee['mtitle'] . ' ' . $tee['mfirst'] . ' ' . $tee['mlast'] . '</td>';
                $check_file_uploaded =  $this -> manage_model_qp_model_re -> check_file_uploaded($tee['qpd_id']);            
                                
                                if ($tee['qp_rollout'] == 0) {
                                        $rubrics_table .= '<td><a id="edit_tee_rubrics_data" class="edit_tee_rubrics_data cursor_pointer" tee_rubrics_abbr="' . base_url() . 'question_paper/tee_rubrics/tee_rubrics_edit_data/' . $pgm_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_id . '/' . $tee['qpd_type'] . '/' . $tee['qpd_id']. '/' . $tee['ao_method_id']  . '" abbr="' . base_url() . 'question_paper/tee_rubrics/tee_rubrics_edit_data/' . $pgm_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_id . '/' . $tee['qpd_type'] . '/' . $tee['qpd_id'] . '/' . $tee['ao_method_id']. '" title="Edit"><i class="icon-pencil" title="Edit"></i></a></td>';
                                        $rubrics_table .= '<td><a id="delete_tee_rubrics_data" class="delete_individual_tee_rubrics_data cursor_pointer" data-pgm_id="'.$pgm_id.'" data-crclm_id="'.$crclm_id.'" data-term_id="'.$term_id.'" data-crs_id="'.$crs_id.'" data-qp_type="'.$tee['qpd_type'].'" data-qpd_id="'.$tee['qpd_id'].'" data-ao_method_id = "'.$tee['ao_method_id'].'" title="Delete"><i class="icon-remove" title="Delete"></i></a></td>';
                                        $rubrics_table .= '<td><a class="btn btn-warning show_rubrics_pending_modal"> Pending</a></td>';
                                    }else if($tee['qp_rollout'] == 1){
                                        $rubrics_table .= '<td><a id="edit_tee_rubrics_data" class="edit_tee_rubrics_data cursor_pointer" tee_rubrics_abbr="' . base_url() . 'question_paper/tee_rubrics/tee_rubrics_edit_data/' . $pgm_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_id . '/' . $tee['qpd_type'] . '/' . $tee['qpd_id']. '/' . $tee['ao_method_id']  . '" abbr="' . base_url() . 'question_paper/tee_rubrics/tee_rubrics_edit_data/' . $pgm_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_id . '/' . $tee['qpd_type'] . '/' . $tee['qpd_id'] . '/' . $tee['ao_method_id']. '" title="Edit"><i class="icon-pencil" title="Edit"></i></a></td>';
                                        $rubrics_table .= '<td><a id="delete_tee_rubrics_data" class="delete_individual_tee_rubrics_data cursor_pointer" data-pgm_id="'.$pgm_id.'" data-crclm_id="'.$crclm_id.'" data-term_id="'.$term_id.'" data-crs_id="'.$crs_id.'" data-qp_type="'.$tee['qpd_type'].'" data-qpd_id="'.$tee['qpd_id'].'" data-ao_method_id = "'.$tee['ao_method_id'].'" title="Delete"><i class="icon-remove" title="Delete"></i></a></td>';
                                        $rubrics_table .= '<td><a class="btn btn-success" title="Rubrics Finalized"> Finalized </a></td>';       
                                    }else if($tee['qp_rollout'] == 2){
                                        $rubrics_table .= '<td><a id="cant_edit_tee_rubrics_data" class="cant_edit_tee_rubrics_data cursor_pointer" tee_rubrics_abbr="' . base_url() . 'question_paper/tee_rubrics/tee_rubrics_edit_data/' . $pgm_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_id . '/' . $tee['qpd_type'] . '/' . $tee['qpd_id']. '/' . $tee['ao_method_id']  . '" abbr="' . base_url() . 'question_paper/tee_rubrics/tee_rubrics_edit_data/' . $pgm_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_id . '/' . $tee['qpd_type'] . '/' . $tee['qpd_id'] . '/' . $tee['ao_method_id']. '" title="Edit"><i class="icon-pencil" title="Edit"></i></a></td>';
                                        $rubrics_table .= '<td><a id="cant_delete_tee_rubrics_data" class="cant_delete_individual_tee_rubrics_data cursor_pointer" data-pgm_id="'.$pgm_id.'" data-crclm_id="'.$crclm_id.'" data-term_id="'.$term_id.'" data-crs_id="'.$crs_id.'" data-qp_type="'.$tee['qpd_type'].'" data-qpd_id="'.$tee['qpd_id'].'" data-ao_method_id = "'.$tee['ao_method_id'].'" title="Delete"><i class="icon-remove" title="Delete"></i></a></td>';
                                        $rubrics_table .= '<td><a class="btn btn-success" disabled title="Rubrics Marks Uploaded" >Data Imported</a></td>';      
                                    }else{
                                        
                                    }
					$rubrics_table .= '<td><a id="view_tee_rubrics_data" class="cursor_pointer view_tee_rubrics_data"  data-pgm_id="'.$pgm_id.'" data-crclm_id="'.$crclm_id.'" data-term_id="'.$term_id.'" data-crs_id="'.$crs_id.'" data-qp_type="5" data-ao_method_id="'.$tee['ao_method_id'].'" data-qpd_id="'.$tee['qpd_id'].'" data-all_data = "'. $pgm_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_id . '/5/'.$tee['ao_method_id'].'" data-view_tee_rubrics_data = "' . base_url() . 'question_paper/tee_rubrics/view_tee_rubrics_details/' . $pgm_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_id . '/5/'.$tee['ao_method_id'].'" > View Rubrics</a>';
					$rubrics_table .= '<input type="hidden" name="rollout_btn_id" id="rollout_btn_id" value="rollout_tee_qp_' . $counter . '" /></td>';
					
					$file_name = '';					
					if(!empty($check_file_uploaded)){ $upload_name = "Uploaded";$remove_data = '| <a class="cursor_pointer delete_uploaded_qp"  data-crs_id = "'.$crs_id.'",  data-prog_id = "'.$pgm_id.'", data-qpd_type_name = "TEE"  data-crclm_id = "'.$crclm_id.'", data-file_name = "'. substr($check_file_uploaded[0]['file_name'], strpos($check_file_uploaded[0]['file_name'], "sep_") + 4) .'" data-term_id = "'.$term_id.'"  data-sec_id = "0" data-ao_id = "" data-qpd_id = "'. $tee['qpd_id'] .'"  data-qpd_type="'. $tee['qpd_type'].'" > Delete </a>';}else{ $remove_data = '';}				
					$rubrics_table .= '<td><a type="button" id="file_uploader" value="Upload" title = ".doc, .docx, .odt, .rtf and .pdf files of size 10MB are allowed." data-toggle= "modal"  data-qpd_type_name = "TEE"  data-id="'.$crs_id.'" data-qpd_id = "'. $tee['qpd_id'] .'"  data-sec_id = "0" data-ao_id = ""  data-qpd_type="'. $tee['qpd_type']  .'" class="upload_qp cursor_pointer"> '. $upload_name .' </a> | <a role="button" data-crs_id = "'.$crs_id.'",  data-prog_id = "'.$pgm_id.'", data-crclm_id = "'.$crclm_id.'", data-qpd_type_name = "TEE" data-term_id = "'.$term_id.'"  data-sec_id = "0" data-ao_id = "" data-qpd_id = "'. $tee['qpd_id'] .'"  data-qpd_type="'. $tee['qpd_type']  .'" href="#" class = "upload_qp_view"> View </a> '.$remove_data.' </td>';
                                }else{
                                    
                                }
                        $rubrics_table .= '</tr>';
                        }
                        $rubrics_table .= '</tbody>';
                        $rubrics_table .= '</table>';
                        $data['qp_table'] = $table_creation;
                        $data['rubrics_table'] = $rubrics_table;
                        $data['rubrics_qp'] = $rubrics_qp_counter;
                        $data['tee_qp'] = $tee_qp_counter;
			echo json_encode($data);
		}
	}
	public function check_data_imported(){
		$pgm_id = $this->input->post('pgm_id');
		$crclm_id = $this->input->post('crclm_id');
		$term_id = $this->input->post('term_id');
		$crs_id = $this->input->post('crs_id');
		$qp_type = $this->input->post('qpd_type');
		$qpd_id = $this->input->post('qpd_id');
		
		$tee_qp_data = $this -> manage_model_qp_model_re -> check_data_imported($pgm_id, $crclm_id, $term_id, $crs_id, $qp_type);		
			if( $tee_qp_data[0]['count(qpd_id)'] != 0){ echo $tee_qp_data[0]['count(qpd_id)'];		
		}else{
			$tee_roll_out = $this -> manage_model_qp_model_re -> check_qp_rolled_out($pgm_id, $crclm_id, $term_id, $crs_id, $qp_type , $qpd_id );			
			if( $tee_roll_out[0]['qp_rollout'] == 1) { echo "Are you sure you want to Reset this ". $this->lang->line('entity_see') ." Question Paper ?";}else{
				echo "Are you sure you want to Roll-out this ". $this->lang->line('entity_see') ." Question Paper ?";
			}
		}
		
	}
	public function tee_individual_qp_edit($pgm_id = NULL, $crclm_id = NULL, $term_id = NULL, $crs_id = NULL, $qp_type = NULL, $qpd_id = NULL) {
		$tee_qp_rollout_status = $this -> manage_model_qp_model_re -> tee_qp_rollout_status($crclm_id, $term_id, $crs_id, $qp_type, $qpd_id);
		$tee_qp_rollout_val = $tee_qp_rollout_status[0]['qp_rollout'];
		if ($tee_qp_rollout_val == 0 || $tee_qp_rollout_val == NULL || $tee_qp_rollout_val == -2) {
			echo '0';
		} elseif ($tee_qp_rollout_val == 1) {
			echo '1';
		} else {
			echo '2';
		}
	}

	/*
	 Function to Load the Edit TEE QP Even after Rolling out the TEE QP
	 */

	public function forcible_tee_qp_edit($pgm_id = NULL, $crclm_id = NULL, $term_id = NULL, $crs_id = NULL, $qp_type = NULL, $qpd_id = NULL) {

		$unit_data = $this -> manage_model_qp_model_re -> generate_model_unit($qpd_id);
		$model_qp_details = $this -> manage_model_qp_model_re -> generate_model_qp($pgm_id);
		$total_marks = $this -> manage_model_qp_model_re -> generate_total_marks($qpd_id);
		if( $model_qp_details['qpf_unit_details']){$data['qp_unit_data'] = $model_qp_details['qpf_unit_details'];}else{ $data['qp_unit_data'] = $unit_data; }
		if(!empty($model_qp_details['qpf_mq_details'])){$data['qp_mq_data'] = $model_qp_details['qpf_mq_details'];}
		$data['qp_entity'] = $model_qp_details['qp_entity_config'];
		$course_data = $this -> manage_model_qp_model_re -> model_qp_course_data($crclm_id, $term_id, $crs_id);
		$model_qp_edit = $this -> manage_model_qp_model_re -> model_qp_edit_details($crclm_id, $term_id, $crs_id, $qp_type, $qpd_id);
		$qp_mapping_entity = $this -> manage_model_qp_model_re -> mapping_entity();
		$unit_sum = $this -> manage_model_qp_model_re -> generate_model_unit_sum($qpd_id);
                $question_type = $this->manage_model_qp_model_re->get_all_question_types();
		$data['entity_list'] = $qp_mapping_entity;
		$data['total_marks'] = $total_marks['marks'];
		$data['co_details'] = $course_data['co_data'];
		$data['topic_details'] = $course_data['topic_list'];
		$data['bloom_data'] = $course_data['level_list'];
		$data['pi_list'] = $course_data['pi_code_list'];
		$data['course_title'] = $course_data['crs_title'];
		$data['crclm_title'] = $course_data['crclm_title'];
		$data['term_title'] = $course_data['term_title'];
		$data['crclm_id'] = $crclm_id;
		$data['term_id'] = $term_id;
		$data['crs_id'] = $crs_id;
		$data['pgm_id'] = $pgm_id;
		$data['unit_sum']=$unit_sum;
		$data['unit_data'] = $unit_data;
		$data['model_qp_existance'] = 1;
		$data['question_type'] = $question_type;

		$data['qpd_id'] = $qpd_id;
		$data['meta_data'] = $model_qp_edit['qp_meta_data'];


		$data['url_ids'] = $pgm_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_id;
		$data['title'] = 'Edit '.$this->lang->line('entity_see_full').'('.$this->lang->line('entity_tee').') Question Paper for ' . $course_data['crs_title'][0]['crs_title'] . ' - (' . $course_data['crs_title'][0]['crs_code'] . ')';
		$this -> load -> view('question_paper/model_qp/tee_qp_edit_vw_re', $data);
	}

	public function tee_individual_qp_delete($pgm_id = NULL, $crclm_id = NULL, $term_id = NULL, $crs_id = NULL, $qp_type = NULL, $qpd_id = NULL) {
		if (!$this -> ion_auth -> logged_in()) {
			//redirect them to the login page
			redirect('login', 'refresh');
		} elseif (!($this -> ion_auth -> is_admin() || $this -> ion_auth -> in_group('Chairman') || $this -> ion_auth -> in_group('Program Owner') || $this -> ion_auth -> in_group('Course Owner'))) {
			//redirect them to the home page because they must be an administrator or owner to view this
			redirect('curriculum/peo/blank', 'refresh');
		} else {
			$model_qp_edit = $this -> manage_model_qp_model_re -> tee_individual_qp_delete($crclm_id, $term_id, $crs_id, $qp_type, $qpd_id);

			echo $model_qp_edit;
		}
	}

	public function tee_qp_rollout($pgm_id = NULL, $crclm_id = NULL, $term_id = NULL, $crs_id = NULL, $qp_type = NULL, $qpd_id = NULL) {
		if (!$this -> ion_auth -> logged_in()) {
			//redirect them to the login page
			redirect('login', 'refresh');
		} elseif (!($this -> ion_auth -> is_admin() || $this -> ion_auth -> in_group('Chairman') || $this -> ion_auth -> in_group('Program Owner') || $this -> ion_auth -> in_group('Course Owner'))) {
			//redirect them to the home page because they must be an administrator or owner to view this
			redirect('curriculum/peo/blank', 'refresh');
		} else {
			$model_qp_edit = $this -> manage_model_qp_model_re -> tee_qp_rollout($crclm_id, $term_id, $crs_id, $qp_type, $qpd_id);

			echo $model_qp_edit;
		}
	}

	public function model_qp_data_ajax_call($pgm_id = NULL, $crclm_id = NULL, $term_id = NULL, $crs_id = NULL) {
		if (!$this -> ion_auth -> logged_in()) {
			//redirect them to the login page
			redirect('login', 'refresh');
		} elseif (!($this -> ion_auth -> is_admin() || $this -> ion_auth -> in_group('Chairman') || $this -> ion_auth -> in_group('Program Owner') || $this -> ion_auth -> in_group('Course Owner'))) {
			//redirect them to the home page because they must be an administrator or owner to view this
			redirect('curriculum/peo/blank', 'refresh');
		} else {
			// $pgm_id = 93;
			$model_qp_details = $this -> manage_model_qp_model_re -> generate_model_qp($pgm_id);
			 $data['qp_unit_data'] 	= $model_qp_details['qpf_unit_details'] ;
			 $data['qp_mq_data'] 	= $model_qp_details['qpf_mq_details'];
			$data['qp_entity'] = $model_qp_details['qp_entity_config'];

			$course_data = $this -> manage_model_qp_model_re -> model_qp_course_data($crclm_id, $term_id, $crs_id);

			$data['co_details'] = $course_data['co_data'];
			$data['topic_details'] = $course_data['topic_list'];
			$data['bloom_data'] = $course_data['level_list'];
			$data['pi_list'] = $course_data['pi_code_list'];

			echo json_encode($data);
		}
	}


	public function add_qp_dat() {
	
		$co_data = $this -> input -> post('co_list');
		$qpd_id = $this -> input -> post('qpd_id');
		$sec_name = $this -> input -> post('sec_name');
		$sec_name2 = $this -> input -> post('sec_name2');
		$qn = $this -> input -> post('qn');
		$sec_id = $this -> input -> post('sec_id');
		$qes_num = $this -> input -> post('qes_num');
		$co = $this -> input -> post('co');
		$po = $this -> input -> post('po');
		$level = $this -> input -> post('bl');
		$level = $this -> input -> post('bl');
		$topic = $this -> input -> post('topic');
		$mark = $this -> input -> post('mark');
		$pi = $this -> input -> post('pi');
		$question = $this -> input -> post('question');
		$mandatory = $this -> input -> post('mandatory');
		$q_num=$this->input->post('q_num');
		$question_type=$this->input->post('question_type');
		$qpd_unit_id = $this -> manage_model_qp_model_re -> get_qpd_unit_id($qpd_id, $sec_name2);
		$qpd_unitd_id = $qpd_unit_id[0]['qpd_unitd_id'];
		// $data['pi']=$pi;$data['co_data']=$co_data;$data['qpd_unitd_id']=$qpd_unitd_id;$data['sec_name']=$sec_name; $data['qn']=$qn;$data['sec_name2']=$sec_name2;$data['sec_id']=$sec_id;$data['qes_num']=$qes_num;$data['co']=$co;$data['po']=$po;$data['topic']=$topic;$data['level']=$level;$data['mark']=$mark;$data['question']=$question;
		$result = $this -> manage_model_qp_model_re -> check_question($sec_name, $qes_num);
		$data = array('pi' => $pi, 'co_data' => $co_data, 'qpd_unitd_id' => $qpd_unitd_id, 'sec_name' => $sec_name, 'qn' => $qn, 'sec_name2' => $sec_name2, 'sec_id' => $sec_id, 'qes_num' => $qes_num, 'co' => $co, 'po' => $po, 'topic' => $topic, 'level' => $level, 'mark' => $mark, 'question' => $question, 'qpd_id' => $qpd_id, 'mandatory' => $mandatory,'q_num'=>$q_num,'question_type'=>$question_type);
	
		if ($result == 0) {
			$re = $this -> manage_model_qp_model_re -> insert_data_new($data);
		} else {
			$re = "false";
		}
		echo json_encode($re);

	}

	/*Function to fetch the total marks*/
	public function fetch_total_marks() {
		$qpd_id = $this -> input -> post('qpd_id');
		$total_marks = $this -> manage_model_qp_model_re -> generate_total_marks($qpd_id);
		echo json_encode($total_marks);
	}

	/**
	 Function to Check redunndancy of questions
	 **/

	public function check_question() {
		$qp_subq_code = $this -> input -> post('q_no');
		$qpd_unitd_id = $this -> input -> post('qpd_unitd_id');
		$qp_crs_id = $this -> input -> post('crs_id');
		$qp_qpd_id = $this -> input -> post('qpd_id');
		$result = $this -> manage_model_qp_model_re -> check_question($qpd_unitd_id, $qp_subq_code);
		//$check_question_result = $this->manage_model_qp_model_re->check_question_data($qpd_unitd_id,$qp_subq_code);
		echo $result;
	}

	public function update_header() {
		$data['qpd_id'] = $this -> input -> post('qpd_id');
		$data['qp_title'] = $this -> input -> post('qp_title');
		$data['time'] = $this -> input -> post('time');
		$data['Grand_total'] = $this -> input -> post('Grand_total');
		$data['max_marks'] = $this -> input -> post('max_marks');
		$data['qp_notes'] = $this -> input -> post('#qp_notes');
		$data['qp_model'] = $this-> input -> post('qp_model');
		$result = $this -> manage_model_qp_model_re -> update_header($data);
		echo(json_encode($result));
	}

	public function add_qp_data($qpd_type) {

		if (!$this -> ion_auth -> logged_in()) {
			//redirect them to the login page
			redirect('login', 'refresh');
		} elseif (!($this -> ion_auth -> is_admin() || $this -> ion_auth -> in_group('Chairman') || $this -> ion_auth -> in_group('Program Owner') || $this -> ion_auth -> in_group('Course Owner'))) {
			//redirect them to the home page because they must be an administrator or owner to view this
			redirect('curriculum/peo/blank', 'refresh');
		} else {
			$qp_data = $this -> input -> post('array_data');
			//$qp_data = $this->input->post('qp_data');
			$mq_counter = $this -> input -> post('total_counter');
			$qp_data_one = json_decode($qp_data, true);

			//$form_data = $this->input->post('form_data');

			//QP definition data
			$qp_title = $this -> input -> post('qp_title');
			$qp_notes = $this -> input -> post('qp_notes');
			$max_marks = $this -> input -> post('max_marks');
			$total_dration = $this -> input -> post('total_duration');
			$unit_counter = $this -> input -> post('unit_counter');
			$qpf_id = $this -> input -> post('qpf_id');
			$crclm_id = $this -> input -> post('crclm_id');
			$term_id = $this -> input -> post('term_id');
			$crs_id = $this -> input -> post('crs_id');
			$pgm_id = $this -> input -> post('pgm_id');
			$grand_total_marks = $this -> input -> post('qp_max_marks');

			for ($m = 1; $m <= $unit_counter; $m++) {
				$total_num_questions[$m] = $this -> input -> post('total_question_' . $m);
				$attemptable_questions[$m] = $this -> input -> post('attempt_question_' . $m);
				$unit_code[$m] = $this -> input -> post('unit_code_' . $m);
				$unit_total_marks[$m] = $this -> input -> post('unit_total_marks_' . $m);
			}
			$temp = 1;
			$unit_q = 0;
			$size = sizeof($qp_data_one);
			for ($uid = 1; $uid <= $unit_counter; $uid++) {
				$unit_val = $this -> input -> post('total_question_' . $uid);
				$unit_q = $unit_q + $unit_val;
				for ($i = 0; $i < $size; $i++) {
					for ($j = $temp; $j <= $unit_q; $j++) {
						if ($qp_data_one[$i]['index'] == $j) {
							$main_question_array[$uid][$j][] = $j . '_' . $qp_data_one[$i]['value'];
						}
					}
				}

				$temp = $j;
				$j--;
				$unit_q = $j;
			}

			$sq_temp = 1;
			$sub_q_val = 0;
			for ($u = 1; $u <= $unit_counter; $u++) {
				$main_qsize = sizeof($main_question_array[$u]);

				$sub_q_val = $sub_q_val + $main_qsize;
				for ($k = $sq_temp; $k <= $sub_q_val; $k++) {
					$sub_q_size = sizeof($main_question_array[$u][$k]);
					for ($t = 0; $t < $sub_q_size; $t++) {
						$main_question_val[$u][$k][] = $this -> input -> post('question_' . $main_question_array[$u][$k][$t]);
						$co_data[$u][$k][] = $this -> input -> post('co_list_' . $main_question_array[$u][$k][$t]);
						$po_data[$u][$k][] = $this -> input -> post('po_list_' . $main_question_array[$u][$k][$t]);
						$topic_data[$u][$k][] = $this -> input -> post('topic_list_' . $main_question_array[$u][$k][$t]);
						$bloom_data[$u][$k][] = $this -> input -> post('bloom_list_' . $main_question_array[$u][$k][$t]);
						$picode_data[$u][$k][] = $this -> input -> post('pi_code_' . $main_question_array[$u][$k][$t]);
						$mq_marks[$u][$k][] = $this -> input -> post('mq_marks_' . $main_question_array[$u][$k][$t]);
						$image_names[$u][$k][] = $this -> input -> post('image_hidden_' . $main_question_array[$u][$k][$t]);
						$question_name[$u][$k][] = $this -> input -> post('question_name_' . $main_question_array[$u][$k][$t]);
						$compolsury_qsn[$u][$k][] = $this -> input -> post('unit_' . $u . '_' . $k);
					}
				}
				$sq_temp = $k;
			}

			$model_qp_insertion = $this -> manage_model_qp_model_re -> model_qp_data_insertion($crclm_id, $term_id, $crs_id, $grand_total_marks, $main_question_array, $qp_title, $qp_notes, $max_marks, $total_dration, $unit_counter, $total_num_questions, $attemptable_questions, $unit_code, $unit_total_marks, $main_question_val, $co_data, $po_data, $topic_data, $bloom_data, $picode_data, $mq_marks, $image_names, $qpf_id, $question_name, $compolsury_qsn, $qpd_type);

			if ($qpd_type == 4) {
				redirect("question_paper/manage_model_qp/generate_model_qp/" . $pgm_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_id . '/' . $qpd_type, 'refresh');
			}
			if ($qpd_type == 5) {
				redirect("question_paper/tee_qp_list/", 'refresh');
			}
		}
	}

	/*
	 Function to add CIA data
	 */

	public function cia_add_qp_data($qpd_type) {
		if (!$this -> ion_auth -> logged_in()) {
			//redirect them to the login page
			redirect('login', 'refresh');
		} elseif (!($this -> ion_auth -> is_admin() || $this -> ion_auth -> in_group('Chairman') || $this -> ion_auth -> in_group('Program Owner') || $this -> ion_auth -> in_group('Course Owner'))) {
			//redirect them to the home page because they must be an administrator or owner to view this
			redirect('curriculum/peo/blank', 'refresh');
		} else {
			$qp_data = $this -> input -> post('array_data');
			//$qp_data = $this->input->post('qp_data');
			$mq_counter = $this -> input -> post('total_counter');
			$qp_data_one = json_decode($qp_data, true);

			//$form_data = $this->input->post('form_data');

			//QP definition data
			$qp_title = $this -> input -> post('qp_title');
			$qp_notes = $this -> input -> post('qp_notes');
			$max_marks = $this -> input -> post('max_marks');
			$total_dration = $this -> input -> post('total_duration');
			$unit_counter = $this -> input -> post('unit_counter');
			$qpf_id = $this -> input -> post('qpf_id');
			$crclm_id = $this -> input -> post('crclm_id');
			$term_id = $this -> input -> post('term_id');
			$crs_id = $this -> input -> post('crs_id');
			$ao_id = $this -> input -> post('ao_id');

			for ($m = 1; $m <= $unit_counter; $m++) {
				$total_num_questions[$m] = $this -> input -> post('total_question_' . $m);
				$attemptable_questions[$m] = $this -> input -> post('attempt_question_' . $m);
				$unit_code[$m] = $this -> input -> post('unit_code_' . $m);

			}
			$temp = 1;
			$unit_q = 0;
			$size = sizeof($qp_data_one);
			for ($uid = 1; $uid <= $unit_counter; $uid++) {
				//$unit_val = $this->input->post('total_question_'.$uid);
				//$unit_q = $unit_q + $unit_val;
				$main_que_num = $this -> input -> post('main_que_array');
				$main_que_num_array = explode(',', $main_que_num);
				$main_que_array_size = sizeof($main_que_num_array);
				for ($i = 0; $i < $size; $i++) {
					for ($j = 0; $j < $main_que_array_size; $j++) {
						if ($qp_data_one[$i]['index'] == $main_que_num_array[$j]) {
							$main_question_array[$uid][$j][] = $main_que_num_array[$j] . '_' . $qp_data_one[$i]['value'];

						}

					}

				}

				$temp = $j;
				$j--;
				$unit_q = $j;
			}

			$sq_temp = 1;
			$sub_q_val = 0;
			for ($u = 1; $u <= $unit_counter; $u++) {
				$main_qsize = sizeof($main_question_array[$u]);				
				$sub_q_val = $sub_q_val + $main_qsize;
				for ($k = 0; $k < $sub_q_val; $k++) {
					$comp = $k;
					$comp++;
					$sub_q_size = sizeof($main_question_array[$u][$k]);			
					for ($t = 0; $t < $sub_q_size; $t++) {						
						$main_question_val[$u][$k][] = $this -> input -> post('question_' . $main_question_array[$u][$k][$t]);
						$co_data[$u][$k][] = $this -> input -> post('co_list_' . $main_question_array[$u][$k][$t]);
						$po_data[$u][$k][] = $this -> input -> post('po_list_' . $main_question_array[$u][$k][$t]);
						$topic_data[$u][$k][] = $this -> input -> post('topic_list_' . $main_question_array[$u][$k][$t]);
						$bloom_data[$u][$k][] = $this -> input -> post('bloom_list_' . $main_question_array[$u][$k][$t]);
						$picode_data[$u][$k][] = $this -> input -> post('pi_code_' . $main_question_array[$u][$k][$t]);
						$mq_marks[$u][$k][] = $this -> input -> post('mq_marks_' . $main_question_array[$u][$k][$t]);
						$image_names[$u][$k][] = $this -> input -> post('image_hidden_' . $main_question_array[$u][$k][$t]);
						$question_name[$u][$k][] = $this -> input -> post('question_name_' . $main_question_array[$u][$k][$t]);
						$compolsury_qsn[$u][$k][] = $this -> input -> post('unit_' . $u . '_' . $comp);
					}
				}
				$sq_temp = $k;

			}
			// echo '<pre>';
			// print_r($main_question_array);
			// print_r($question_name);
			// print_r($compolsury_qsn);
			// print_r($main_question_val);
			// print_r($co_data);
			// print_r($topic_data);
			// print_r($bloom_data);
			// print_r($mq_marks);
			//print_r($image_names);
			//print_r($qp_data_one);
			//exit;

			$model_qp_insertion = $this -> manage_model_qp_model_re -> cia_qp_data_insertion($crclm_id, $term_id, $crs_id, $main_question_array, $qp_title, $qp_notes, $max_marks, $total_dration, $unit_counter, $total_num_questions, $attemptable_questions, $unit_code, $main_question_val, $co_data, $po_data, $topic_data, $bloom_data, $picode_data, $mq_marks, $image_names, $qpf_id, $question_name, $compolsury_qsn, $qpd_type, $ao_id);

			$cia_data = $model_qp_insertion['qpd_id'];
			echo $cia_data;
			// if($qpd_type == 3){
			// redirect("question_paper/manage_model_qp", 'refresh');
			// }
		}
	}

	public function update_qp_data($qpd_type) {
		if (!$this -> ion_auth -> logged_in()) {
			//redirect them to the login page
			redirect('login', 'refresh');
		} elseif (!($this -> ion_auth -> is_admin() || $this -> ion_auth -> in_group('Chairman') || $this -> ion_auth -> in_group('Program Owner') || $this -> ion_auth -> in_group('Course Owner'))) {
			//redirect them to the home page because they must be an administrator or owner to view this
			redirect('curriculum/peo/blank', 'refresh');
		} else {
			$qp_data = $this -> input -> post('array_data');
			//$qp_data = $this->input->post('qp_data');
			$mq_counter = $this -> input -> post('total_counter');
			$qp_data_one = json_decode($qp_data, true);

			//QP definition data
			$qp_title = $this -> input -> post('qp_title');
			$qp_notes = $this -> input -> post('qp_notes');
			$max_marks = $this -> input -> post('max_marks');
			$total_dration = $this -> input -> post('total_duration');
			$unit_counter = $this -> input -> post('unit_counter');
			$qpf_id = $this -> input -> post('qpf_id');
			$qpd_id = $this -> input -> post('qpd_id');
			$crclm_id = $this -> input -> post('crclm_id');
			$term_id = $this -> input -> post('term_id');
			$crs_id = $this -> input -> post('crs_id');
			$url_ids = $this -> input -> post('url_ids');
			$grand_total_marks = $this -> input -> post('qp_max_marks');

			for ($m = 1; $m <= $unit_counter; $m++) {
				$total_num_questions[$m] = $this -> input -> post('total_question_' . $m);
				$attemptable_questions[$m] = $this -> input -> post('attempt_question_' . $m);
				$unit_code[$m] = $this -> input -> post('unit_code_' . $m);
				$unit_total_marks[$m] = $this -> input -> post('unit_total_marks_' . $m);

			}
			$temp = 1;
			$unit_q = 0;
			$size = sizeof($qp_data_one);
			for ($uid = 1; $uid <= $unit_counter; $uid++) {
				$unit_val = $this -> input -> post('total_question_' . $uid);
				$unit_q = $unit_q + $unit_val;
				for ($i = 0; $i < $size; $i++) {
					for ($j = $temp; $j <= $unit_q; $j++) {
						if ($qp_data_one[$i]['index'] == $j) {
							$main_question_array[$uid][$j][] = $j . '_' . $qp_data_one[$i]['value'];
						}

					}

				}

				$temp = $j;
				$j--;
				$unit_q = $j;
			}

			$sq_temp = 1;
			$sub_q_val = 0;
			for ($u = 1; $u <= $unit_counter; $u++) {
				$main_qsize = sizeof($main_question_array[$u]);
				$sub_q_val = $sub_q_val + $main_qsize;
				for ($k = $sq_temp; $k <= $sub_q_val; $k++) {
					$sub_q_size = sizeof($main_question_array[$u][$k]);
					for ($t = 0; $t < $sub_q_size; $t++) {
						$main_question_val[$u][$k][] = $this -> input -> post('question_' . $main_question_array[$u][$k][$t]);
						$co_data[$u][$k][] = $this -> input -> post('co_list_' . $main_question_array[$u][$k][$t]);
						$po_data[$u][$k][] = $this -> input -> post('po_list_' . $main_question_array[$u][$k][$t]);
						$topic_data[$u][$k][] = $this -> input -> post('topic_list_' . $main_question_array[$u][$k][$t]);
						$bloom_data[$u][$k][] = $this -> input -> post('bloom_list_' . $main_question_array[$u][$k][$t]);
						$picode_data[$u][$k][] = $this -> input -> post('pi_code_' . $main_question_array[$u][$k][$t]);
						$mq_marks[$u][$k][] = $this -> input -> post('mq_marks_' . $main_question_array[$u][$k][$t]);
						$image_names[$u][$k][] = $this -> input -> post('image_hidden_' . $main_question_array[$u][$k][$t]);
						$question_name[$u][$k][] = $this -> input -> post('question_name_' . $main_question_array[$u][$k][$t]);
						$compolsury_qsn[$u][$k][] = $this -> input -> post('unit_' . $u . '_' . $k);
					}
				}
				$sq_temp = $k;

			}

			//echo '<pre>';
			// print_r($main_question_array);
			// print_r($question_name);
			// print_r($compolsury_qsn);
			// print_r($main_question_val);
			// print_r($co_data);
			// print_r($picode_data);
			// print_r($bloom_data);
			// print_r($mq_marks);
			//print_r($image_names);
			//print_r($qp_data_one);
			//exit;

			$model_qp_insertion = $this -> manage_model_qp_model_re -> model_qp_data_update_insertion($crclm_id, $term_id, $crs_id, $grand_total_marks, $main_question_array, $qp_title, $qp_notes, $max_marks, $total_dration, $unit_counter, $total_num_questions, $attemptable_questions, $unit_code, $unit_total_marks, $main_question_val, $co_data, $po_data, $topic_data, $bloom_data, $picode_data, $mq_marks, $image_names, $qpf_id, $qpd_id, $question_name, $compolsury_qsn, $qpd_type);

			$last_instd_qpd_id = $model_qp_insertion['qpd_id'];
			if ($qpd_type == 4) {
				redirect("question_paper/manage_model_qp/generate_model_qp/" . $url_ids . '/' . $qpd_type);
			}
			if ($qpd_type == 5) {
				redirect("question_paper/manage_model_qp/forcible_tee_qp_edit/" . $url_ids . '/' . $qpd_type . '/' . $last_instd_qpd_id);
			}
		}
	}

	/*
	 Function for updating CIA Data
	 */
	public function cia_update_qp_data($qpd_type) {
		if (!$this -> ion_auth -> logged_in()) {
			//redirect them to the login page
			redirect('login', 'refresh');
		} elseif (!($this -> ion_auth -> is_admin() || $this -> ion_auth -> in_group('Chairman') || $this -> ion_auth -> in_group('Program Owner') || $this -> ion_auth -> in_group('Course Owner'))) {
			//redirect them to the home page because they must be an administrator or owner to view this
			redirect('curriculum/peo/blank', 'refresh');
		} else {
			$qp_data = $this -> input -> post('array_data');
			//$qp_data = $this->input->post('qp_data');
			$mq_counter = $this -> input -> post('total_counter');
			$qp_data_one = json_decode($qp_data, true);

			//QP definition data
			$qp_title = $this -> input -> post('qp_title');
			$qp_notes = $this -> input -> post('qp_notes');
			$max_marks = $this -> input -> post('max_marks');
			$total_dration = $this -> input -> post('total_duration');
			$unit_counter = $this -> input -> post('unit_counter');
			$qpf_id = $this -> input -> post('qpf_id');
			$qpd_id = $this -> input -> post('qpd_id');
			$crclm_id = $this -> input -> post('crclm_id');
			$term_id = $this -> input -> post('term_id');
			$crs_id = $this -> input -> post('crs_id');
			$ao_id = $this -> input -> post('ao_id');

			for ($m = 1; $m <= $unit_counter; $m++) {
				$total_num_questions[$m] = $this -> input -> post('total_question_' . $m);
				$attemptable_questions[$m] = $this -> input -> post('attempt_question_' . $m);
				$unit_code[$m] = $this -> input -> post('unit_code_' . $m);

			}
			$temp = 1;
			$unit_q = 0;
			$size = sizeof($qp_data_one);
			for ($uid = 1; $uid <= $unit_counter; $uid++) {
				//$unit_val = $this->input->post('total_question_'.$uid);
				$main_que_num = $this -> input -> post('main_que_array');
				$main_que_num_array = explode(',', $main_que_num);
				$main_que_array_size = sizeof($main_que_num_array);
				//$unit_q = $unit_q + $unit_val;
				for ($i = 0; $i < $size; $i++) {
					for ($j = 0; $j < $main_que_array_size; $j++) {
						if ($qp_data_one[$i]['index'] == $main_que_num_array[$j]) {
							$main_question_array[$uid][$j][] = $main_que_num_array[$j] . '_' . $qp_data_one[$i]['value'];
						}

					}

				}

				$temp = $j;
				$j--;
				$unit_q = $j;
			}
			$sq_temp = 1;
			$sub_q_val = 0;
			for ($u = 1; $u <= $unit_counter; $u++) {
				$main_qsize = sizeof($main_question_array[$u]);
				$sub_q_val = $sub_q_val + $main_qsize;
				for ($k = 0; $k < $sub_q_val; $k++) {
					$comp = $k;
					$comp++;
					$sub_q_size = sizeof($main_question_array[$u][$k]);
					for ($t = 0; $t < $sub_q_size; $t++) {
						$main_question_val[$u][$k][] = $this -> input -> post('question_' . $main_question_array[$u][$k][$t]);
						$co_data[$u][$k][] = $this -> input -> post('co_list_' . $main_question_array[$u][$k][$t]);
						$po_data[$u][$k][] = $this -> input -> post('po_list_' . $main_question_array[$u][$k][$t]);
						$topic_data[$u][$k][] = $this -> input -> post('topic_list_' . $main_question_array[$u][$k][$t]);
						$bloom_data[$u][$k][] = $this -> input -> post('bloom_list_' . $main_question_array[$u][$k][$t]);
						$picode_data[$u][$k][] = $this -> input -> post('pi_code_' . $main_question_array[$u][$k][$t]);
						$mq_marks[$u][$k][] = $this -> input -> post('mq_marks_' . $main_question_array[$u][$k][$t]);
						$image_names[$u][$k][] = $this -> input -> post('image_hidden_' . $main_question_array[$u][$k][$t]);
						$question_name[$u][$k][] = $this -> input -> post('question_name_' . $main_question_array[$u][$k][$t]);
						$compolsury_qsn[$u][$k][] = $this -> input -> post('unit_' . $u . '_' . $comp);
					}
				}
				$sq_temp = $k;

			}

			// echo '<pre>';
			// print_r($main_question_array);
			// print_r($question_name);
			// print_r($compolsury_qsn);
			// print_r($main_question_val);
			// print_r($co_data);
			// print_r($topic_data);
			// print_r($bloom_data);
			// print_r($mq_marks);
			// print_r($image_names);
			// print_r($qp_data_one);
			//exit;

			$model_qp_insertion = $this -> manage_model_qp_model_re -> cia_qp_data_update_insertion($crclm_id, $term_id, $crs_id, $main_question_array, $qp_title, $qp_notes, $max_marks, $total_dration, $unit_counter, $total_num_questions, $attemptable_questions, $unit_code, $main_question_val, $co_data, $po_data, $topic_data, $bloom_data, $picode_data, $mq_marks, $image_names, $qpf_id, $qpd_id, $question_name, $compolsury_qsn, $qpd_type, $ao_id);

			$qpd_id = $model_qp_insertion['qpd_id'];
			echo $qpd_id;

			// if($qpd_type == 3){
			// redirect("question_paper/manage_model_qp", 'refresh');
			// }
		}
	}

	public function po_list_data() {
		if (!$this -> ion_auth -> logged_in()) {
			//redirect them to the login page
			redirect('login', 'refresh');
		} elseif (!($this -> ion_auth -> is_admin() || $this -> ion_auth -> in_group('Chairman') || $this -> ion_auth -> in_group('Program Owner') || $this -> ion_auth -> in_group('Course Owner'))) {
			//redirect them to the home page because they must be an administrator or owner to view this
			redirect('curriculum/peo/blank', 'refresh');
		} else {

			$co_id = $this -> input -> post('co_id');
			$crclm_id = $this -> input -> post('crclm_id');
			$term_id = $this -> input -> post('term_id');
			$crs_id = $this -> input -> post('crs_id');

			$po_list = $this -> manage_model_qp_model_re -> po_list($co_id, $crclm_id, $term_id, $crs_id);
			$bl_list = $this -> manage_model_qp_model_re -> bl_list($co_id , $crclm_id , $term_id , $crs_id );
			if(!empty($po_list['qp_po_entity_data'])){$po_list_data['entity_data'] = $po_list['qp_po_entity_data'];}
			if(!empty($po_list['mapped_po_res'])){$po_list_data['po_result'] = $po_list['mapped_po_res'];}
           {$po_list_data['bl_result'] = $bl_list['mapped_bl_res'];}
                     
			echo json_encode($po_list_data);
		}
	}

	public function pi_list_data() {
		if (!$this -> ion_auth -> logged_in()) {
			//redirect them to the login page
			redirect('login', 'refresh');
		} elseif (!($this -> ion_auth -> is_admin() || $this -> ion_auth -> in_group('Chairman') || $this -> ion_auth -> in_group('Program Owner') || $this -> ion_auth -> in_group('Course Owner'))) {
			//redirect them to the home page because they must be an administrator or owner to view this
			redirect('curriculum/peo/blank', 'refresh');
		} else {

			$co_id = $this -> input -> post('co_id');
			$crclm_id = $this -> input -> post('crclm_id');
			$term_id = $this -> input -> post('term_id');
			$crs_id = $this -> input -> post('crs_id');

			$pi_list = $this -> manage_model_qp_model_re -> pi_list($co_id, $crclm_id, $term_id, $crs_id);

			$pi_list_data['entity_data'] = $pi_list['qp_pi_entity_data'];
			$pi_list_data['pi_result'] = $pi_list['mapped_pi_res'];

			echo json_encode($pi_list_data);
		}
	}

	public function unit_list_data() {
		if (!$this -> ion_auth -> logged_in()) {
			//redirect them to the login page
			redirect('login', 'refresh');
		} elseif (!($this -> ion_auth -> is_admin() || $this -> ion_auth -> in_group('Chairman') || $this -> ion_auth -> in_group('Program Owner') || $this -> ion_auth -> in_group('Course Owner'))) {
			//redirect them to the home page because they must be an administrator or owner to view this
			redirect('curriculum/peo/blank', 'refresh');
		} else {

			$qpf_id = $this -> input -> post('unit_id');
			$qpd_id = $this -> input -> post('qpd_id');
			$qpf_list = $this -> manage_model_qp_model_re -> unit_list($qpf_id, $qpd_id);
			echo json_encode($qpf_list);

		}

	}

	//Graphs
	/* Function - To get data for BloomsLevelPlannedCoverageDistribution
	 * @param -
	 * returns -
	 */
	public function BloomsLevelPlannedCoverageDistribution() {
		if (!$this -> ion_auth -> logged_in()) {
			//redirect them to the login page
			redirect('login', 'refresh');
		} elseif (!($this -> ion_auth -> is_admin() || $this -> ion_auth -> in_group('Chairman') || $this -> ion_auth -> in_group('Program Owner') || $this -> ion_auth -> in_group('Course Owner'))) {
			//redirect them to the home page because they must be an administrator or owner to view this
			redirect('curriculum/peo/blank', 'refresh');
		} else {
			$crs = $this -> input -> post('crs');
			$qid = $this -> input -> post('qid');
			$qp_type = $this -> input -> post('qpd_type');
			$graph_data = $this -> manage_model_qp_model_re -> getBloomsLevelPlannedCoverageDistribution($crs, $qid, $qp_type);
			if ($graph_data)
				echo json_encode($graph_data);
			else
				echo 0;
		}
	}

	/* Function - To get data for BloomsLevelMarksDistribution
	 * @param -
	 * returns -
	 */
	public function BloomsLevelMarksDistribution() {
		if (!$this -> ion_auth -> logged_in()) {
			//redirect them to the login page
			redirect('login', 'refresh');
		} elseif (!($this -> ion_auth -> is_admin() || $this -> ion_auth -> in_group('Chairman') || $this -> ion_auth -> in_group('Program Owner') || $this -> ion_auth -> in_group('Course Owner'))) {
			//redirect them to the home page because they must be an administrator or owner to view this
			redirect('curriculum/peo/blank', 'refresh');
		} else {
			$crs = $this -> input -> post('crs');
			$qid = $this -> input -> post('qid');
			$qp_type = $this -> input -> post('qpd_type');
			$graph_data = $this -> manage_model_qp_model_re -> getBloomsLevelMarksDistribution($crs, $qid, $qp_type);
			if ($graph_data)
				echo json_encode($graph_data);
			else
				echo 0;
		}
	}

	/* Function - To get data for COLevelMarksDistribution
	 * @param - course id and question paper id
	 * returns -
	 */
	public function COLevelMarksDistribution() {
		if (!$this -> ion_auth -> logged_in()) {
			//redirect them to the login page
			redirect('login', 'refresh');
		} elseif (!($this -> ion_auth -> is_admin() || $this -> ion_auth -> in_group('Chairman') || $this -> ion_auth -> in_group('Program Owner') || $this -> ion_auth -> in_group('Course Owner'))) {
			//redirect them to the home page because they must be an administrator or owner to view this
			redirect('curriculum/peo/blank', 'refresh');
		} else {
			$crs = $this -> input -> post('crs');
			$qid = $this -> input -> post('qid');
			$graph_data = $this -> manage_model_qp_model_re -> getCOLevelMarksDistribution($crs, $qid);
			if ($graph_data)
				echo json_encode($graph_data);
			else
				echo 0;
		}
	}

	public function getQPBloomLevelCoverageGraphData() {
		if (!$this -> ion_auth -> logged_in()) {
			//redirect them to the login page
			redirect('login', 'refresh');
		} elseif (!($this -> ion_auth -> is_admin() || $this -> ion_auth -> in_group('Chairman') || $this -> ion_auth -> in_group('Program Owner') || $this -> ion_auth -> in_group('Course Owner'))) {
			//redirect them to the home page because they must be an administrator or owner to view this
			redirect('curriculum/peo/blank', 'refresh');
		} else {
			$crs_id = $this -> input -> post('crs_id');
			$qp_data['model_qpd_id'] = $this -> manage_model_qp_model_re -> getModelQP_qpid($crs_id);
			$qp_data['tee_qpd_id'] = $this -> manage_model_qp_model_re -> getTEEQP_qpid($crs_id);
			$model_qp_data['model_qp'] = Array();
			$model_qp_data['blm_mks_dist_model_qp'] = Array();
			if ($qp_data['model_qpd_id']) {
				$qpd_id = $qp_data['model_qpd_id'][0]['qpd_id'];
				$qpd_type = $qp_data['model_qpd_id'][0]['qpd_type'];
				$this -> db -> reconnect();
				$model_qp_data['model_qp'] = $this -> manage_model_qp_model_re -> getBloomsLevelPlannedCoverageDistribution($crs_id, $qpd_id, $qpd_type);
				$this -> db -> reconnect();
				$model_qp_data['blm_mks_dist_model_qp'] = $this -> manage_model_qp_model_re -> getBloomsLevelMarksDistribution_custom($crs_id, $qpd_id);
			} else {
				$model_qp_data['model_qp'] = '';
			}
			foreach ($qp_data['tee_qpd_id'] as $tee_qpd_id) {
				$this -> db -> reconnect();
				$tee_qp_data[] = $this -> manage_model_qp_model_re -> getBloomsLevelPlannedCoverageDistribution($crs_id, $tee_qpd_id['qpd_id'], $tee_qpd_id['qpd_type']);
				$this -> db -> reconnect();
				$blm_mks_dist_tee_qp_data[] = $this -> manage_model_qp_model_re -> getBloomsLevelMarksDistribution_custom($crs_id, $tee_qpd_id['qpd_id']);
			}
			$model_qp_data['tee_qp'] = $tee_qp_data;
			$model_qp_data['blm_mks_dist_tee_qp'] = $blm_mks_dist_tee_qp_data;
			if ($model_qp_data)
				echo json_encode($model_qp_data);
			else
				echo 0;
		}
	}

	public function getQPCOCoverageGraphData() {
		if (!$this -> ion_auth -> logged_in()) {
			//redirect them to the login page
			redirect('login', 'refresh');
		} elseif (!($this -> ion_auth -> is_admin() || $this -> ion_auth -> in_group('Chairman') || $this -> ion_auth -> in_group('Program Owner') || $this -> ion_auth -> in_group('Course Owner'))) {
			//redirect them to the home page because they must be an administrator or owner to view this
			redirect('curriculum/peo/blank', 'refresh');
		} else {
			$crs_id = $this -> input -> post('crs_id');
			$qp_data['model_qpd_id'] = $this -> manage_model_qp_model_re -> getModelQP_qpid($crs_id);
			$qp_data['tee_qpd_id'] = $this -> manage_model_qp_model_re -> getTEEQP_qpid($crs_id);
			$model_qp_data['model_qp'] = Array();
			if ($qp_data['model_qpd_id']) {
				$qpd_id = $qp_data['model_qpd_id'][0]['qpd_id'];
				$this -> db -> reconnect();
				$model_qp_data['model_qp'] = $this -> manage_model_qp_model_re -> getCOLevelMarksDistribution($crs_id, $qpd_id);
			} else {
				$model_qp_data['model_qp'] = '';
			}
			foreach ($qp_data['tee_qpd_id'] as $tee_qpd_id) {
				$this -> db -> reconnect();
				$tee_qp_data[] = $this -> manage_model_qp_model_re -> getCOLevelMarksDistribution($crs_id, $tee_qpd_id['qpd_id']);
			}
			$model_qp_data['tee_qp'] = $tee_qp_data;
			if ($model_qp_data)
				echo json_encode($model_qp_data);
			else
				echo 0;
		}
	}

	public function getQPTopicCoverageGraphData() {
		if (!$this -> ion_auth -> logged_in()) {
			//redirect them to the login page
			redirect('login', 'refresh');
		} elseif (!($this -> ion_auth -> is_admin() || $this -> ion_auth -> in_group('Chairman') || $this -> ion_auth -> in_group('Program Owner') || $this -> ion_auth -> in_group('Course Owner'))) {
			//redirect them to the home page because they must be an administrator or owner to view this
			redirect('curriculum/peo/blank', 'refresh');
		} else {
			$crs_id = $this -> input -> post('crs_id');
			$qp_data['model_qpd_id'] = $this -> manage_model_qp_model_re -> getModelQP_qpid($crs_id);
			$qp_data['tee_qpd_id'] = $this -> manage_model_qp_model_re -> getTEEQP_qpid($crs_id);
			$model_qp_data['model_qp'] = Array();
			if ($qp_data['model_qpd_id']) {
				$qpd_id = $qp_data['model_qpd_id'][0]['qpd_id'];
				$this -> db -> reconnect();
				$model_qp_data['model_qp'] = $this -> manage_model_qp_model_re -> getTopicCoverageDistribution($crs_id, $qpd_id);
			}
			foreach ($qp_data['tee_qpd_id'] as $tee_qpd_id) {
				$this -> db -> reconnect();
				$tee_qp_data[] = $this -> manage_model_qp_model_re -> getTopicCoverageDistribution($crs_id, $tee_qpd_id['qpd_id']);
			}
			$model_qp_data['tee_qp'] = $tee_qp_data;
			if ($model_qp_data)
				echo json_encode($model_qp_data);
			else
				echo 0;
		}
	}

	public function export_model_qp_pdf() {
		if (!$this -> ion_auth -> logged_in()) {
			//redirect them to the login page
			redirect('login', 'refresh');
		} elseif (!($this -> ion_auth -> is_admin() || $this -> ion_auth -> in_group('Chairman') || $this -> ion_auth -> in_group('Program Owner') || $this -> ion_auth -> in_group('Course Owner'))) {
			//redirect them to the home page because they must be an administrator or owner to view this
			redirect('curriculum/peo/blank', 'refresh');
		} else {
			
			$qp_content = $this -> input -> post('qp_hidden');
			
			$total_val_hidden = $this -> input -> post('total_val_hidden');
			$chart1_img_hidden = $this -> input -> post('chart1_img_hidden');
			$chart2_img_hidden = $this -> input -> post('chart2_img_hidden');
			$chart3_img_hidden = $this -> input -> post('chart3_img_hidden');
			$chart4_img_hidden = $this -> input -> post('chart4_img_hidden');
			
			$html = "<div><br><br>" . $qp_content . "<div style='text-align:right;' >" . $total_val_hidden . "</div><pagebreak>" . $chart1_img_hidden . "<pagebreak>" . $chart2_img_hidden . "<pagebreak>" . $chart3_img_hidden . "<pagebreak>" . $chart4_img_hidden . "</div>";
			$this->load->helper('pdf');
			pdf_create($html,'model_qp_report','P',160);
			return;
		}
	}

	/*
	 Function to fetch the mapped weightage
	 @param main question id
	 @return list of co and po with weight.
	 */

	public function fetch_mapped_weightage() {
		$main_que_id = $this -> input -> post('main_que_id');
		$main_que_id_result = $this -> manage_model_qp_model_re -> get_main_que_weight($main_que_id);

		$co_data = $main_que_id_result['co_data'];
		$po_data = $main_que_id_result['po_data'];

		$co_size = count($co_data);
		$co_table = '<table id="co_mapped_weight" class="table table-bordered" >';
		$co_table .= '<thead>';
		$co_table .= '<tr>';
		$co_table .= '<th>Course Outcome</th>';
		$co_table .= '<th>Weightage - %</th>';
		$co_table .= '</tr>';
		$co_table .= '</thead>';
		$co_table .= '<tbody>';
		for ($i = 0; $i < $co_size; $i++) {
			$co_table .= '<tr>';
			$co_table .= '<td title="' . $co_data[$i]['clo_statement'] . '" class="cursor_pointer">' . $co_data[$i]['clo_code'] . '</td>';
			$co_table .= '<td><input max="100" type="text" name="co_weight_' . $i . '" id="co_weight_' . $i . '" class="input-mini co_weight_text" style="text-align:right;"	value = "' . $co_data[$i]['mapped_percentage'] . '"/><input type="hidden" name="co_map_id_' . $i . '" id="co_map_id_' . $i . '" class="input-small co_weight_text" 	value = "' . $co_data[$i]['qp_map_id'] . '"/><b>%</b></td>';
			$co_table .= '</tr>';

		}
		$co_table .= '</tbody>';
		$co_table .= '</table>';
		$co_table .= '<input type="hidden" name="co_weight_count" id="co_weight_count" value="' . $co_size . '"/>';

		$po_size = count($po_data);
		$program_outcome_lang=$this->lang->line('student_outcome_full');
		$po_table = '<table id="co_mapped_weight" class="table table-bordered" >';
		$po_table .= '<thead>';
		$po_table .= '<tr>';
		$po_table .= '<th>'.$program_outcome_lang.'</th>';
		$po_table .= '<th>Weightage - %</th>';
		$po_table .= '</tr>';
		$po_table .= '</thead>';
		$po_table .= '<tbody>';
		for ($j = 0; $j < $po_size; $j++) {
			$po_table .= '<tr>';
			$po_table .= '<td title="' . $po_data[$j]['po_statement'] . '" class="cursor_pointer">' . $po_data[$j]['po_reference'] . '</td>';
			$po_table .= '<td><input max=100 type="text" name="po_weight_' . $j . '" id="po_weight_' . $j . '" class="required input-mini co_weight_text" style="text-align:right;"	value = "' . $po_data[$j]['mapped_percentage'] . '"/><input type="hidden" name="po_map_id_' . $j . '" id="po_map_id_' . $j . '" class="input-small co_weight_text" 	value = "' . $po_data[$j]['qp_map_id'] . '"/><b>%</b></td>';
			$po_table .= '</tr>';

		}
		$po_table .= '</tbody>';
		$po_table .= '</table>';
		$po_table .= '<input type="hidden" name="po_weight_count" id="po_weight_count" value="' . $po_size . '"/>';

		$data['co_table'] = $co_table;
		$data['po_table'] = $po_table;

		echo json_encode($data);

	}

	/*
	 Function to update the mapped weightage qp mapping details.
	 */

	public function update_weightage() {
		$co_weight_array = $this -> input -> post('co_weight_array');
		$co_map_id_array = $this -> input -> post('co_map_id_array');
		$po_weight_array = $this -> input -> post('po_weight_array');
		$po_map_id_array = $this -> input -> post('po_map_id_array');
		$main_question_id = $this -> input -> post('main_question_id');
		$update_result = $this -> manage_model_qp_model_re -> update_weightage_data($co_weight_array, $co_map_id_array, $po_weight_array, $po_map_id_array, $main_question_id);

		echo '0';

	}
	
	/*Function to delete the model question paper*/
	
	public function delete_model_qp(){
	$pgmtype_id=$this->input->post('pgmtype_id');
	$crclm_id=$this->input->post('crclm_id');
	$term_id=$this->input->post('term_id');
	$crs_id=$this->input->post('crs_id');
	$result = $this -> manage_model_qp_model_re -> delete_model_qp($pgmtype_id,$crclm_id,$term_id,$crs_id);
	echo  json_encode($result);
	}
	
	/* Function to fetch model qp details*/
	
	public function fetch_model_qp_details(){
	$pgmtype_id=$this->input->post('pgmtype_id');
	$crclm_id=$this->input->post('crclm_id');
	$term_id=$this->input->post('term_id');
	$crs_id=$this->input->post('crs_id');
	$result = $this -> manage_model_qp_model_re -> fetch_model_qp_details($pgmtype_id,$crclm_id,$term_id,$crs_id);
	echo json_encode($result);
	}
        
        /*
         * Import Model QP function starts from here
         * Author: Mritunjay B S
         * Date: 13/4/2016
         */
        
        public function get_dept_list(){
                    $dept_list = $this -> manage_model_qp_model_re -> list_dept();
                    //$data['dept_data'] = $dept_list['dept_result'];
                    $dept_data = $dept_list['dept_result'];
			$i = 0;
			$list[$i] = '<option value>Select Department</option>';
			$i++;
			foreach ($dept_data as $data) {
				$list[$i] = "<option value = " . $data['dept_id'] . ">" . $data['dept_name'] . "</option>";
				$i++;
			}
			$list = implode(" ", $list);
			echo $list;
        }
        
    /*
     * Function to get the program list
     */
       
        public function get_program_list(){
           $dept_id = $this->input->post('dept_id');
           $pgm_list = $this->select_pgm_list();
           echo $pgm_list;
        }
        
    /*
     * Function to get the crclm list
     */
        public function get_curriculum_list(){
            $dept_id = $this->input->post('dept_id');
            $pgm_id = $this->input->post('pgm_id');
			$curriculum_data = $this -> manage_model_qp_model_re -> curriculum_list_for_import($pgm_id);
			$curriculum_data = $curriculum_data['crclm_result'];
			$i = 0;
			$list[$i] = '<option value="">Select Curriculum</option>';
			$i++;
			foreach ($curriculum_data as $data) {
				$list[$i] = "<option value = " . $data['crclm_id'] . ">" . $data['crclm_name'] . "</option>";
				$i++;
			}
			$list = implode(" ", $list);
			echo $list;
            //echo $crclm_list;
             
        }
        
    /*
     * Function to get the term list
     */
        public function get_term_list(){
            $dept_id = $this->input->post('dept_id');
            $pgm_id = $this->input->post('pgm_id');
            $crclm_id = $this->input->post('crclm_id');
			$term_data = $this -> manage_model_qp_model_re -> term_list_import_qp($crclm_id);
			$term_data_res = $term_data;
			$i = 0;
			$list[$i] = '<option value="">Select Term</option>';
			$i++;
			foreach ($term_data_res as $data) {
				$list[$i] = "<option value = " . $data['crclm_term_id'] . ">" . $data['term_name'] . "</option>";
				$i++;
			}
			$list = implode(" ", $list);
			echo $list;
             
        }
        
    /*
     * Function to get the term list
     */
        public function get_course_list(){
            $dept_id = $this->input->post('dept_id');
            $pgm_id = $this->input->post('pgm_id');
            $crclm_id = $this->input->post('crclm_id');
            $term_id = $this->input->post('term_id');
            $to_crs_id = $this->input->post('to_crs_id');
            $course_list =$this->manage_model_qp_model_re ->get_course_list($crclm_id,$term_id,$to_crs_id);
           
            $i = 0;
            $list[$i] = '<option value="">Select Course</option>';
            $i++;
            foreach ($course_list as $data) {
                    $list[$i] = "<option value = " . $data['crs_id'] . ">" . $data['crs_title'] . "</option>";
                    $i++;
            }
            $list = implode(" ", $list);
            echo $list;
           
             
        }
        
     /*
      * Function to get the list of question paper.
      */
        
        public function get_question_paper_list(){
            $from_dept_id = $this->input->post('from_dept_id');
            $from_pgm_id = $this->input->post('from_pgm_id');
            $from_crclm_id = $this->input->post('from_crclm_id');
            $from_term_id = $this->input->post('from_term_id');
            $from_crs_id = $this->input->post('from_crs_id');
            $to_crs_id = $this->input->post('to_crs_id');
            $to_term_id = $this->input->post('to_term_id');
            $to_crclm_id = $this->input->post('to_crclm_id');
            
            $qp_list =$this->manage_model_qp_model_re ->get_question_paper_list($from_crclm_id,$from_term_id,$to_crs_id,$from_crs_id);
            
            $table = '';
            $table .= '<table id="question_paper_list" class="table table-hover table-bordered dataTable">';
            $table .= '<thead>';
            $table .= '<tr>';
            $table .= '<th>Sl No.</th>';
            $table .= '<th>Select</th>';
            $table .= '<th>QP Title</th>';
            $table .= '<th>QP Max Marks</th>';
            $table .= '<th>QP Grand Total Marks</th>';
            $table .= '<th>Duration in Hrs</th>';
            $table .= '</tr>';
            $table .= '</thead>';
            $table .= '<tbody>';
            $i=1;
            foreach($qp_list as $qp){
                $table .= '<tr>';
                $table .= '<td>'.$i.'</td>';
                $table .= '<td><center><input type="checkbox" class="selected_model_qp" name="select_qp" id="select_qp_'.$i.'" data-to_crs_id="'.$to_crs_id.'" data-to_crclm_id="'.$to_crclm_id.'" data-to_term_id = "'.$to_term_id.'" data-qpd_id="'.$qp['qpd_id'].'"/></center></td>';
                $table .= '<td>'.$qp['qpd_title'].'</td>';
                $table .= '<td>'.$qp['qpd_max_marks'].'</td>';
                $table .= '<td>'.$qp['qpd_gt_marks'].'</td>';
                $table .= '<td>'.$qp['qpd_timing'].'</td>';
                $table .= '</tr>';  
                $i++;
            }
           
            $table .= '</tbody>';
            $table .= '</table>';
            echo $table;
          
        }
        
         /*
         * Function to get the qp details 
         */
      /*  public function get_qp_data_import(){
                    $qpd_id = $this->input->post('qpd_id');
                    $to_crclm_id = $this->input->post('to_crclm_id');
                    $to_term_id = $this->input->post('to_term_id');
                    $to_crs_id = $this->input->post('to_crs_id');
                    $qp_details = $this -> manage_model_qp_model_re->get_qp_data_import($qpd_id,$to_crclm_id,$to_term_id,$to_crs_id);
            echo $qp_details;
            
        }
       * 
       */
        
        public function get_qp_data_import($flag=null,$qpd_id=null,$to_crclm_id=null,$to_term_id=null,$to_crs_id=null){

            if($flag){
               $qp_details = $this -> manage_model_qp_model_re->get_qp_data_import($qpd_id,$to_crclm_id,$to_term_id,$to_crs_id);
               return $qp_details;
            }else{
                    $qpd_id = $this->input->post('qpd_id');
                    $to_crclm_id = $this->input->post('to_crclm_id');
                    $to_term_id = $this->input->post('to_term_id');
                    $to_crs_id = $this->input->post('to_crs_id');
                    $qp_details = $this -> manage_model_qp_model_re->get_qp_data_import($qpd_id,$to_crclm_id,$to_term_id,$to_crs_id);
                
            }
            echo $qp_details;
           exit();
            
        }
        
        public function existance_of_qp(){
            $qpd_id = $this->input->post('qpd_id');
            //$ao_id = $this->input->post('ao_id');
            $to_crclm_id = $this->input->post('to_crclm_id');
            $to_term_id = $this->input->post('to_term_id');
            $to_crs_id = $this->input->post('to_crs_id');
            $existance_of_qp = $this -> manage_model_qp_model_re->existance_of_qp($qpd_id,$to_crclm_id,$to_term_id,$to_crs_id);
            echo $existance_of_qp['size'];
    }
    
    /*
         *  Function to overwrite the question paper.
         * 
         */
        
        public function overwrite_qp(){
            //$overwrite = $this->get_qp_data_import();
            $qpd_id = $this->input->post('qpd_id');
           // $ao_id = $this->input->post('ao_id');
            $to_crclm_id = $this->input->post('to_crclm_id');
            $to_term_id = $this->input->post('to_term_id');
            $to_crs_id = $this->input->post('to_crs_id');
//            $qp_overwrite = $this->manage_cia_qp_model->overwrite_qp($qpd_id,$ao_id,$crclm_id,$term_id,$crs_id);
            $overwrite = $this->get_qp_data_import(1,$qpd_id,$to_crclm_id,$to_term_id,$to_crs_id);
            echo $overwrite;
        }
        
        /*
         * Function to get the review and assignment questions
         */
        public function get_rev_assin_questions(){
            $crs_id = $this->input->post('crs_id');
            $que_type = $this->input->post('que_type');
            
            $question_list = $this->manage_model_qp_model_re->get_rev_assin_questions($crs_id,$que_type);
            $table='';
            $table .= '<table id="rev_assin_question" class="table table-bordered dataTable dataTables">';
            $table .= '<thead>';
            $table .= '<tr style="background-color:#D8D8D8;">';
            $table .= '<th style="width:95px;">Select Question</th>';
            $table .= '<th style="width:110px;">Topic Name</th>';
            $table .= '<th>Question</th>';
            $table .= '</tr>';
            $table .= '</thead>';
            $table .= '<tbody>';
            foreach($question_list as $question){
                 $table .= '<tr>';
                 if($question['que_id'] == 1){
                     $table .= '<td><center><input type="checkbox" class="select_question" name="question[]" data-question="'.$question['review_question'].'"/></center></td>';
                     $table .= '<td>'.$question['topic_title'].'</td>';
                     $table .= '<td>'.$question['review_question'].'</td>';
                 }else{
                     $table .= '<td><center><input type="checkbox" class="select_question" name="question[]" data-question="'.$question['assignment_question'].'"/></center></td>';
                     $table .= '<td>'.$question['topic_title'].'</td>';
                     $table .= '<td>'.$question['assignment_question'].'</td>';
                 }
                 $table .= '</tr>';
            }
            $table .= '</tbody>';
            
            $table .= '</table>';
            
            echo $table;
            
        }
		
/* 	public function to_excel($qpd_id , $crs_id) {	
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } 
		
        if (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        }	
	$file_name 	= "Test";	
	$section_id = 143;	
	$ao_id = "";
       $results = $this->manage_model_qp_model_re->attainment_template($qpd_id, $section_id);
       $file_name = $this->manage_model_qp_model_re->file_name_query($crs_id, $qpd_id, $ao_id);

       if ($file_name == 5) {
            $file_name = "Unknown_Course";
            $results = 'Unknown Course';
            //move this command to __construct part
            $this->load->helper('download');
            //to remove unwanted '\n' while inserting the data to .csv
            ob_clean();
            force_download($file_name . '.csv', trim($results));
        } else { 

            //move this command to __construct part
            $this->load->helper('download');
            //to remove unwanted '\n' while inserting the data to .csv			
            ob_clean();		
            force_download($file_name . '.csv', trim($results));
       }
    } */
	
	
	

}
?>
