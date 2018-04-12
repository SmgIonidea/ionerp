<?php

/* --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Controller to manage question paper 	  
 * Modification History:
 * Author : Bhagyalaxmi.shivapuji	
 * Date : 02-03-2017
 * Date							Modified By							Description
 * 								
 * ---------------------------------------------------------------------------------------------------------------------------------
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Manage_mte_qp extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('question_paper/mte_qp/manage_mte_qp_model');
	     $this->load->model('assessment_attainment/import_cia_data/import_cia_data_model');
        
    }
	
	    public function index() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            /* Fetches department list */
            $dept_list = $this->manage_mte_qp_model->dept_fill();
            $data['dept_data'] = $dept_list['dept_result'];
            $data['title'] = $this->lang->line('entity_mte') . " List Page";
            $this->load->view('question_paper/mte_qp/list_mte_qp_vw', $data);
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
			$pgm_data = $this -> manage_mte_qp_model -> pgm_fill($dept_id);
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
			$curriculum_data = $this -> manage_mte_qp_model -> crclm_fill($pgm_id);
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
			$term_data = $this -> manage_mte_qp_model -> term_fill($crclm_id);
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
	
		/* Function is used to generate List of Course Grid (Table).
	 * @param-
	 * @returns - an object.
	 */
	public function mte_show_course() {
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
			$ao_id = '';
			$qpd_type = 3;
			$course_data = $this -> manage_mte_qp_model -> course_list($crclm_id, $term_id);
			//$pgm_data = $this->manage_mte_qp_model->fetch_pgm_type($prog_id);
			//$pgmtype_id = $pgm_data[0]['pgmtype_id'];
			$i = 1;
			$msg = '';
			$del = '';
			$publish = '';
			$data = $course_data['crs_list_data'];
			$crs_list = array();
			$entity_mte = $this->lang->line('entity_mte');
			foreach ($data as $crs_data) {
				if ($crs_data['crs_mode'] == 1) {
					$msg = 'Practical';
				} else {
					$msg = 'Theory';
				}

				$crs_id_val = $crs_data['crs_id'];
				$one_qp_defined = $this -> manage_mte_qp_model -> oneQPDefined($crs_id_val);
				
				$course_bloom_status = $this->manage_mte_qp_model->course_bloom_status($crclm_id , $term_id , $crs_data['crs_id']);
				
				$topic_defined = $this-> manage_mte_qp_model ->check_topic_defined_or_not($crs_id_val);
				$check_topic_status = $this->manage_mte_qp_model->check_topic_status();
				$count_topic = count($topic_defined);				
				$export_import = '<a title = "Add '.$entity_mte.' QP" class="tee_question_paper cursor_pointer" abbr = "' . base_url() . 'question_paper/manage_mte_qp/generate_model_qp/' . $pgmtype_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_data['crs_id'] . '/5" class = "myTagRemover get_crs_id"  data-term_id = "'.$term_id.'" data-crclm_id ="'. $crclm_id .'"  id = "' . $crs_data['crs_id'] . '" >Add '.$entity_mte.' QP</a>';
				
			
					$view_edit_qp = '<a title = "View/Edit '.$entity_mte.' QP" data-bl_status = "'. $course_bloom_status[0]['count_val'] .'" data-clo_bl_flag = "'. $course_bloom_status[0]['clo_bl_flag'] .'" class="fetch_mte_qp_data cursor_pointer" abbr = "' . base_url() . 'question_paper/manage_mte_qp/fetch_mte_qp/' . $pgmtype_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_data['crs_id'] . '/3" class = "myTagRemover get_crs_id" id = "' . $crs_data['crs_id'] . '"  data-topic_staus = "'. $check_topic_status . '" data-topic_count = "'. $count_topic.'" value="' . $crs_data['crs_id'] . '">  Manage '. $this->lang->line('entity_mte') .' QP </a>';
						
					$import_mte_qp = 	'<td><a class="cursor_pointer import_mte_qp" data-crclm_id="' . $crclm_id . '" data-term_id="' . $term_id . '" data-course_id="' . $crs_data['crs_id'] . '" data-crs_title = "'. $crs_data['crs_title'] .'" > Import '.$this->lang->line('entity_mte').' QP</a></td>';	
					
						
					$upload_qp_url =    base_url().'assessment_attainment/upload_mte_question_papers/index/'.$pgmtype_id.'/'.$crclm_id .'/'.$term_id.'/'.$crs_data['crs_id'] . '/' . $qpd_type;
					
					if($course_bloom_status[0]['clo_bl_flag'] == 1 && $course_bloom_status[0]['count_val'] == 0 ){ 			
									$data = 'Cannot Add / Edit Question Paper as Blooms Level are mandatory for this course . <br/> Bloom\'s Level are missing in Course Outcome . ';
									$upload_mte_qp = '<a title = "Upload '.$entity_mte.' QP"  data-link = "curriculum/clo"  data-type_name = " Map Bloom Level"  data-error_mag = "'. $data .'" href="" class=" topic_not_defined cursor_pointer " >  Import '. $this->lang->line('entity_mte') .' QP </a>';	
					}else if($check_topic_status == 1 && empty($topic_defined)){
									$data = 'Cannot Upload Question Paper as topics are not defined for this Course.';
									$upload_mte_qp = '<a title = "Upload'.$entity_mte.' QP"  data-link = "curriculum/topic"  data-type_name = " Add Topics"  data-error_mag = "'. $data .'" href="" class=" topic_not_defined cursor_pointer " >  Import '. $this->lang->line('entity_mte') .' QP </a>';	
					}else{
							$upload_mte_qp = '<td><a class="cursor_pointer mte_qp_upload"  href = "'. $upload_qp_url .'"  data-pgm_id = "'. $pgmtype_id.'"   data-crclm_id ="'. $crclm_id .'" data-term_id = "'. $term_id .'" data-crs_id = "'. $crs_data['crs_id'] .'" data-qp_type = "'. $qpd_type .'" > Import '.$this->lang->line('entity_mte').' QP</a></td>';
					}
					
					
					$mte_qp_url =   base_url() . 'question_paper/manage_mte_qp/generate_mte_model_qp/' . $pgmtype_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_data['crs_id'] . '/' . $ao_id . '/' . $qpd_type;

					
					$publish = '<a role = "button"  data-toggle = "modal" title = "QP In-Progress"   abbr = "'. $mte_qp_url .'" href = "#" class = "btn btn-small btn-success myTagRemover get_crs_id" id = "' . $crs_data['crs_id'] . '" ><i></i>Initiated</a>';
		

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
				//'import_qp' => $import_mte_qp,
				'upload_mte_qp' => $upload_mte_qp
				
				);
				$i++;
			}
			echo json_encode($crs_list);
		}
	}//End of function show_course.
		
	
	public function fetch_mte_qp($pgm_id = NULL, $crclm_id = NULL, $term_id = NULL, $crs_id = NULL, $qp_type = NULL, $qp_existance = NULL) {
		if (!$this -> ion_auth -> logged_in()) {
			//redirect them to the login page
			redirect('login', 'refresh');
		} elseif (!($this -> ion_auth -> is_admin() || $this -> ion_auth -> in_group('Chairman') || $this -> ion_auth -> in_group('Program Owner') || $this -> ion_auth -> in_group('Course Owner'))) {
			//redirect them to the home page because they must be an administrator or owner to view this
			redirect('curriculum/peo/blank', 'refresh');
		} else {
			$mte_qp_data = $this -> manage_mte_qp_model -> fetch_mte_qp_data($crclm_id, $term_id, $crs_id, $qp_type);
			$mte_rubrics_data = $this -> manage_mte_qp_model -> fetch_rubrics_qp_data($crclm_id, $term_id, $crs_id, $qp_type);
			
			$counter = 1;
                        $tee_qp_counter = 0;
                        $rubrics_qp_counter = 0;
			$table_creation = '<table id="modal_tee_qp_lis" class="table table-bordered table-hover"  aria-describedby="example_info" >';
			$table_creation .= '<tr>';
			$table_creation .= '<th colspan="12">'.$this->lang->line('entity_mte').' QP List</th>';
			$table_creation .= '</tr>';
			$table_creation .= '<tr>';
			$table_creation .= '<th>Occasion Name</th>';
			//$table_creation .= '<th>Sl No. </th>';
			$table_creation .= '<th>QP Title</th>';
			$table_creation .= '<th>Created Date</th>';
			$table_creation .= '<th>Created By</th>';
			$table_creation .= '<th colspan="2">Action</th>';			
			$table_creation .= '<th>View QP</th>';
			$table_creation .= '<th>Upload QP</th>';	
			$table_creation .= '</tr>';
			$table_creation .= '<tbody>';
            $upload_name = 'Upload';  $remove_data ='';     
                        
			foreach ($mte_qp_data as $mte) {
			$mte_qp_data = $this->manage_mte_qp_model->check_data_imported($mte['qpd_id'], $crclm_id, $term_id, $mte['crs_id']);
		
                if($mte['rubrics_flag'] != 1){
					$check_file_uploaded =  $this -> manage_mte_qp_model -> check_file_uploaded($mte['qpd_id']);					
					$tee_qp_counter++;
					$table_creation .= '<tr>';
					$table_creation .= '<td>' . $mte['ao_description'] . '</td>';
				//	$table_creation .= '<td>' . $tee_qp_counter. '</td>';
					$table_creation .= '<td>' . $mte['qpd_title'] . '</td>';
					$table_creation .= '<td>' . $mte['created_date'] . '</td>';
					$table_creation .= '<td>' . $mte['mtitle'] . ' ' . $mte['mfirst'] . ' ' . $mte['mlast'] . '</td>';
							
					if ($mte_qp_data[0]['qp_rollout'] == 2) {
						$table_creation .= '<td><a id="cont_edit_mte_qp" class=" marks_uploaded_already1  cursor_pointer"  title="Edit"><i class="icon-pencil" title="Edit"></i></a></td>';
						
						$table_creation .= '<td><a id="delete_mte_qp" class="mte_qp_delete_warning cursor_pointer" abbr="' . base_url() . 'question_paper/manage_mte_qp/mte_qp_delete/' . $pgm_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_id . '/' . $mte['qpd_type'] . '/' . $mte['qpd_id'] . '" title="Delete"><i class="icon-remove" title="Delete"></i></a></td>';	
					}else{
					$table_creation .= '<td><a id="edit_mte_qp" class="edit_mte_qp cursor_pointer" href="' . base_url() . 'question_paper/manage_mte_qp/generate_mte_model_qp/' . $pgm_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_id . '/' . $mte['ao_id'] . '/' . $mte['qpd_type'] . '" title="Edit"><i class="icon-pencil" title="Edit"></i></a></td>';
					$table_creation .= '<td><a id="delete_mte_qp" class="delete_individual_qp cursor_pointer" abbr="' . base_url() . 'question_paper/manage_mte_qp/mte_qp_delete/' . $pgm_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_id . '/' . $mte['qpd_type'] . '/' . $mte['qpd_id'] . '" title="Delete"><i class="icon-remove" title="Delete"></i></a></td>';	
					}
					$table_creation .= '<td><a class="cursor_pointer view_qp" id="view_qp_' . $counter . '" abbr="' . $pgm_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_id . '/' . $mte['qpd_type'] . '/' . $mte['qpd_id'] . '">View QP</a></td>';
			
					if(!empty($check_file_uploaded)){ 
						$upload_name = "Uploaded";
					//	var_dump($check_file_uploaded); var_dump($check_file_uploaded[0]['file_name']);
						$file_name_data = substr($check_file_uploaded[0]['file_name'], strpos($check_file_uploaded[0]['file_name'], "sep_") + 4);
						$remove_data = '| <a class="cursor_pointer delete_uploaded_qp"  data-crs_id = "'.$crs_id.'",  data-prog_id = "'.$pgm_id.'", data-crclm_id = "'.$crclm_id.'", data-term_id = "'.$term_id.'"  data-qpd_type_name = "MTE"  data-sec_id = "0" data-file_name = "'. $file_name_data .'"  data-ao_id = "'.$mte['ao_id'].'" data-qpd_id = "'. $mte['qpd_id'] .'"  data-qpd_type="'. $mte['qpd_type'].'" > Delete </a> ';
					}else{$upload_name = "Upload"; $remove_data = "";}	
					
					
					$table_creation .= '<td><a type="button" id="file_uploader" value="Upload" title = ".doc, .docx, .odt, .rtf and .pdf files of size 10MB are allowed." data-toggle= "modal" data-qpd_type_name = "MTE"  data-id="'.$crs_id.'" data-qpd_id = "'. $mte['qpd_id'] .'"  data-sec_id = "0" data-ao_id = "'. $mte['ao_id'] .'" data-qpd_type="'. $mte['qpd_type']  .'" class="upload_qp cursor_pointer"> '. $upload_name .' </a> | <a role="button" data-crs_id = "'.$crs_id.'",  data-prog_id = "'.$pgm_id.'", data-crclm_id = "'.$crclm_id.'", data-term_id = "'.$term_id.'"  data-sec_id = "0" data-ao_id = "'.$mte['ao_id'].'" data-qpd_id = "'. $mte['qpd_id'] .'"  data-qpd_type_name = "MTE"  data-qpd_type="'. $mte['qpd_type']  .'" href="#" class = "upload_qp_view"> View </a>  '. $remove_data .'</td>';
			 
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
                        $rubrics_table .= '<th colspan="12">'.$this->lang->line('entity_mte').' Rubrics List</th>';
                        $rubrics_table .= '</tr>';
                        $rubrics_table .= '<tr>';
			$rubrics_table .= '<th>Occasion Name</th>';
			$rubrics_table .= '<th>Rubrics Title</th>';
			$rubrics_table .= '<th>Created Date</th>';
			$rubrics_table .= '<th>Created By</th>';
			$rubrics_table .= '<th colspan="2">Action</th>';
			$rubrics_table .= '<th>Finalize Rubrics</th>';
			$rubrics_table .= '<th>View Rubrics</th>';
			$rubrics_table .= '<th>Upload QP</th>';
			$rubrics_table .= '</tr>';
                        $rubrics_table .= '</thead>';
                        $rubrics_table .= '<tbody>';						
                        foreach ($mte_rubrics_data as $mte) {
                            if($mte['rubrics_flag'] == 1){
                                $rubrics_qp_counter++;
                                $rubrics_table .= '<tr>';
				$rubrics_table .= '<td>' . $mte['ao_description'] . '</td>';
				$rubrics_table .= '<td>' . $mte['qpd_title'] . '</td>';
				$rubrics_table .= '<td>' . $mte['created_date'] . '</td>';
				$rubrics_table .= '<td>' . $mte['mtitle'] . ' ' . $mte['mfirst'] . ' ' . $mte['mlast'] . '</td>';
				$check_file_uploaded =  $this -> manage_mte_qp_model -> check_file_uploaded($mte['qpd_id']);              
                                
                                if ($mte['qp_rollout'] == 0) {
                                        $rubrics_table .= '<td><a id="edit_mte_rubrics_data" class="edit_mte_rubrics_data cursor_pointer" tee_rubrics_abbr="' . base_url() . 'question_paper/mte_rubrics/mte_rubrics_edit_data/' . $pgm_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_id . '/' . $mte['qpd_type'] . '/' . $mte['qpd_id']. '/' . $mte['ao_method_id']  . '" abbr="' . base_url() . 'question_paper/mte_rubrics/mte_rubrics_edit_data/' . $pgm_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_id . '/' . $mte['qpd_type'] . '/' . $mte['qpd_id'] . '/' . $mte['ao_method_id']. '" title="Edit"><i class="icon-pencil" title="Edit"></i></a></td>';
                                        $rubrics_table .= '<td><a id="delete_mte_rubrics_data" class="delete_individual_mte_rubrics_data cursor_pointer" data-pgm_id="'.$pgm_id.'" data-crclm_id="'.$crclm_id.'" data-term_id="'.$term_id.'" data-crs_id="'.$crs_id.'" data-qp_type="'.$mte['qpd_type'].'" data-qpd_id="'.$mte['qpd_id'].'" data-ao_method_id = "'.$mte['ao_method_id'].'" title="Delete"><i class="icon-remove" title="Delete"></i></a></td>';
                                        $rubrics_table .= '<td><a class="btn btn-warning show_rubrics_pending_modal"> Pending</a></td>';
                                    }else if($mte['qp_rollout'] == 1){
                                        $rubrics_table .= '<td><a id="edit_mte_rubrics_data" class="edit_mte_rubrics_data cursor_pointer" tee_rubrics_abbr="' . base_url() . 'question_paper/mte_rubrics/mte_rubrics_edit_data/' . $pgm_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_id . '/' . $mte['qpd_type'] . '/' . $mte['qpd_id']. '/' . $mte['ao_method_id']  . '" abbr="' . base_url() . 'question_paper/mte_rubrics/mte_rubrics_edit_data/' . $pgm_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_id . '/' . $mte['qpd_type'] . '/' . $mte['qpd_id'] . '/' . $mte['ao_method_id']. '" title="Edit"><i class="icon-pencil" title="Edit"></i></a></td>';
                                        $rubrics_table .= '<td><a id="delete_mte_rubrics_data" class="delete_individual_mte_rubrics_data cursor_pointer" data-pgm_id="'.$pgm_id.'" data-crclm_id="'.$crclm_id.'" data-term_id="'.$term_id.'" data-crs_id="'.$crs_id.'" data-qp_type="'.$mte['qpd_type'].'" data-qpd_id="'.$mte['qpd_id'].'" data-ao_method_id = "'.$mte['ao_method_id'].'" title="Delete"><i class="icon-remove" title="Delete"></i></a></td>';
                                        $rubrics_table .= '<td><a class="btn btn-success" title="Rubrics Finalized"> Finalized </a></td>';       
                                    }else if($mte['qp_rollout'] == 2){
                                        $rubrics_table .= '<td><a id="cant_edit_mte_rubrics_data" class="cant_edit_mte_rubrics_data cursor_pointer" tee_rubrics_abbr="' . base_url() . 'question_paper/mte_rubrics/mte_rubrics_edit_data/' . $pgm_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_id . '/' . $mte['qpd_type'] . '/' . $mte['qpd_id']. '/' . $mte['ao_method_id']  . '" abbr="' . base_url() . 'question_paper/mte_rubrics/mte_rubrics_edit_data/' . $pgm_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_id . '/' . $mte['qpd_type'] . '/' . $mte['qpd_id'] . '/' . $mte['ao_method_id']. '" title="Edit"><i class="icon-pencil" title="Edit"></i></a></td>';
                                        $rubrics_table .= '<td><a id="cant_delete_mte_rubrics_data" class="cant_delete_individual_mte_rubrics_data cursor_pointer" data-pgm_id="'.$pgm_id.'" data-crclm_id="'.$crclm_id.'" data-term_id="'.$term_id.'" data-crs_id="'.$crs_id.'" data-qp_type="'.$mte['qpd_type'].'" data-qpd_id="'.$mte['qpd_id'].'" data-ao_method_id = "'.$mte['ao_method_id'].'" title="Delete"><i class="icon-remove" title="Delete"></i></a></td>';
                                        $rubrics_table .= '<td><a class="btn btn-success" disabled title="Rubrics Marks Uploaded" >Data Imported</a></td>';      
                                    }else{
                                        
                                    }
					$rubrics_table .= '<td><a id="view_mte_rubrics_data" class="cursor_pointer view_mte_rubrics_data"  data-pgm_id="'.$pgm_id.'" data-crclm_id="'.$crclm_id.'" data-term_id="'.$term_id.'" data-crs_id="'.$crs_id.'" data-qp_type="5" data-ao_method_id="'.$mte['ao_method_id'].'" data-qpd_id="'.$mte['qpd_id'].'" data-all_data = "'. $pgm_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_id . '/5/'.$mte['ao_method_id'].'" data-view_mte_rubrics_data = "' . base_url() . 'question_paper/mte_rubrics/view_mte_rubrics_details/' . $pgm_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_id . '/5/'.$mte['ao_method_id'].'" > View Rubrics</a>';
					$rubrics_table .= '<input type="hidden" name="rollout_btn_id" id="rollout_btn_id" value="rollout_tee_qp_' . $counter . '" /></td>';
					
					if(!empty($check_file_uploaded)){ 
						$upload_name = "Uploaded";
						$remove_data = '| <a class="cursor_pointer delete_uploaded_qp"  data-crs_id = "'.$crs_id.'",  data-prog_id = "'.$pgm_id.'", data-crclm_id = "'.$crclm_id.'", data-term_id = "'.$term_id.'"  data-qpd_type_name = "MTE" data-file_name = "'. substr($check_file_uploaded[0]['file_name'], strpos($check_file_uploaded[0]['file_name'], "sep_") + 4) .'" data-sec_id = "0" data-ao_id = "'.$mte['ao_id'].'" data-qpd_id = "'. $mte['qpd_id'] .'"  data-qpd_type="'. $mte['qpd_type'].'" > Delete </a> ';
					}else{$upload_name = "Upload"; $remove_data = "";}	
					
					
					$rubrics_table .= '<td><a type="button" id="file_uploader" value="Upload" title = ".doc, .docx, .odt, .rtf and .pdf files of size 10MB are allowed." data-toggle= "modal" data-id="'.$crs_id.'"data-qpd_type_name = "MTE" data-qpd_id = "'. $mte['qpd_id'] .'"  data-sec_id = "0" data-ao_id = "'. $mte['ao_id'] .'" data-qpd_type="'. $mte['qpd_type']  .'" class="upload_qp cursor_pointer"> '. $upload_name .' </a> | <a role="button" data-crs_id = "'.$crs_id.'",  data-prog_id = "'.$pgm_id.'", data-crclm_id = "'.$crclm_id.'", data-term_id = "'.$term_id.'"  data-sec_id = "0" data-ao_id = "'.$mte['ao_id'].'" data-qpd_id = "'. $mte['qpd_id'] .'"  data-qpd_type_name = "MTE" data-qpd_type="'. $mte['qpd_type']  .'" href="#" class = "upload_qp_view"> View </a>  '. $remove_data .'</td>';
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
	
	
	
	public function mte_model_qp_def() {	
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

		$result = $this -> manage_mte_qp_model -> mte_model_qp_def($data);

		echo json_encode($result);
	}
	
	
	public function update_header() {
		$data['qpd_id'] = $this -> input -> post('qpd_id');
		$data['qp_title'] = $this -> input -> post('qp_title');
		$data['time'] = $this -> input -> post('time');
		$data['Grand_total'] = $this -> input -> post('Grand_total');
		$data['max_marks'] = $this -> input -> post('max_marks');
		$data['qp_notes'] = $this -> input -> post('#qp_notes');
		$data['qp_model'] = $this-> input -> post('qp_model');
		$result = $this -> manage_mte_qp_model -> update_header($data);
		echo(json_encode($result));
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
		 $result= $this -> manage_mte_qp_model -> update_FM($units, $unit_ids, $no_q, $sub_marks, $qpd_id,$unit_marks);
		$data['result']=$result['qp_unit_result'];
		$data['unit_sum'] = $this -> manage_mte_qp_model -> generate_model_unit_sum($qpd_id);
		$sec_val= $this -> manage_mte_qp_model -> generate_model_unit($qpd_id);
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
		
		$grand_total=$this->manage_mte_qp_model->generate_grand_total($crclm_id,$term_id,$crs_id,$qpd_id,$qpd_type);
		echo json_encode($grand_total[0]['qpd_gt_marks']);
	}
	/*
	 Function to generate the CIA question paper
	 @param course id, ao id.
	 */
	public function generate_mte_model_qp($pgm_id = NULL, $crclm_id = NULL, $term_id = NULL, $crs_id = NULL, $ao_id = NULL, $qp_type = NULL) {
		$cia_model_qp = $this -> manage_mte_qp_model -> mte_model_qp_details($crclm_id, $term_id, $crs_id, $ao_id);
		if (!empty($cia_model_qp[0]['qpd_id'])) {
			$data['ao_id'] = $ao_id;
			$qpd_id = $cia_model_qp[0]['qpd_id'];
			$qp_mapping_entity = $this -> manage_mte_qp_model -> mapping_entity();
			$data['entity_id'] = $qp_mapping_entity;
			$unit_data = $this -> manage_mte_qp_model -> generate_model_unit($qpd_id);
			$model_qp_details = $this -> manage_mte_qp_model -> generate_model_qp($pgm_id);
			$total_marks = $this -> manage_mte_qp_model -> generate_total_marks($qpd_id);
			$unit_sum = $this -> manage_mte_qp_model -> generate_model_unit_sum($qpd_id);
			if(!empty($model_qp_details['qpf_unit_details'])){$data['qp_unit_data'] = $model_qp_details['qpf_unit_details'];} else{ $data['qp_unit_data'] = $unit_data; }
			if (!empty($model_qp_details['qpf_mq_details'])) {
				$data['qp_mq_data'] = $model_qp_details['qpf_mq_details'];
			}
			$data['qp_entity'] = $model_qp_details['qp_entity_config'];
			$course_data = $this -> manage_mte_qp_model -> model_qp_course_data($crclm_id, $term_id, $crs_id);
			$model_qp_edit = $this -> manage_mte_qp_model -> model_qp_edit_details($crclm_id, $term_id, $crs_id, $qp_type, $qpd_id);
			$qp_mapping_entity = $this -> manage_mte_qp_model -> mapping_entity();
			$data['entity_list'] = $qp_mapping_entity;
			$data['total_marks'] = $total_marks['marks'];
			
			$data['co_details'] = $course_data['co_data'];
			$data['question_type_details'] = $course_data['question_type_data'];
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
			$data['url_ids'] = $pgm_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_id;

			$data['title'] = 'Edit '.$this->lang->line('entity_mte').' Model Question Paper for ' . $course_data['crs_title'][0]['crs_title'] . ' - [' . $course_data['crs_title'][0]['crs_code'] . ']';
			$this -> load -> view('question_paper/mte_qp/mte_model_qp_edit_vw', $data);

		} else {
		 
			$data['ao_id'] = '';
			$model_qp_details = $this -> manage_mte_qp_model -> generate_model_qp($pgm_id);
			if(!empty($model_qp_details['qpf_unit_details'])){$data['qp_unit_data'] = $model_qp_details['qpf_unit_details'];} else{ $data['qp_unit_data'] = 0; }
			if (!empty($model_qp_details['qpf_mq_details'])) {
				$data['qp_mq_data'] = $model_qp_details['qpf_mq_details'];
			}

			$data['qp_entity'] = $model_qp_details['qp_entity_config'];
			if (!empty($cia_model_qp[0]['qpd_id'])) {
				$qpd_id = $cia_model_qp[0]['qpd_id'];
				$model_qp_edit = $this -> manage_mte_qp_model -> model_qp_edit_details($crclm_id, $term_id, $crs_id, $qp_type, $qpd_id);
				$data['meta_data'] = $model_qp_edit['qp_meta_data'];
			} else {
				$qpd_id = NULL;
			}
			$course_data = $this -> manage_mte_qp_model -> model_qp_course_data($crclm_id, $term_id, $crs_id);
			$qp_mapping_entity = $this -> manage_mte_qp_model -> mapping_entity();
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
			$data['url_ids'] = $pgm_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_id;
			$data['title'] = 'Add '.$this->lang->line('entity_mte_full').'('.$this->lang->line('entity_mte').') Question Paper for Course : ' . $course_data['crs_title'][0]['crs_title'] . ' - (' . $course_data['crs_title'][0]['crs_code'] . ')';
			$this -> load -> view('question_paper/mte_qp/mte_model_qp_add_vw', $data);

		}
	}
	
		/*Function to fetch the total marks*/
	public function fetch_total_marks() {
		$qpd_id = $this -> input -> post('qpd_id');
		$total_marks = $this -> manage_mte_qp_model -> generate_total_marks($qpd_id);
		echo json_encode($total_marks);
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
			$qpf_list = $this -> manage_mte_qp_model -> unit_list($qpf_id, $qpd_id);
			echo json_encode($qpf_list);

		}

	}
	
		/**
	 Function to Check redunndancy of questions
	 **/

	public function check_question() {
		$qp_subq_code = $this -> input -> post('q_no');
		$qpd_unitd_id = $this -> input -> post('qpd_unitd_id');
		$qp_crs_id = $this -> input -> post('crs_id');
		$qp_qpd_id = $this -> input -> post('qpd_id');
		$result = $this -> manage_mte_qp_model -> check_question($qpd_unitd_id, $qp_subq_code);		
		echo $result;
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

			$po_list = $this -> manage_mte_qp_model -> po_list($co_id, $crclm_id, $term_id, $crs_id);
			$bl_list = $this -> manage_mte_qp_model -> bl_list($co_id , $crclm_id , $term_id , $crs_id );
		//	$qt_list = $this-> manage_mte_qp_model ->  qt_list($co_id , $crclm_id , $term_id , $crs_id);
			if(!empty($po_list['qp_po_entity_data'])){$po_list_data['entity_data'] = $po_list['qp_po_entity_data'];}
			if(!empty($po_list['mapped_po_res'])){$po_list_data['po_result'] = $po_list['mapped_po_res'];}
			
			//if(!empty($po_list['mapped_po_res'])){$po_list_data['po_result'] = $po_list['mapped_po_res'];}
			
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

			$pi_list = $this -> manage_mte_qp_model -> pi_list($co_id, $crclm_id, $term_id, $crs_id);

			$pi_list_data['entity_data'] = $pi_list['qp_pi_entity_data'];
			$pi_list_data['pi_result'] = $pi_list['mapped_pi_res'];

			echo json_encode($pi_list_data);
		}
	}
	
	public function add_question() {
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
		$topic = $this -> input -> post('topic');
		$mark = $this -> input -> post('mark');
		$pi = $this -> input -> post('pi');
		$question = $this -> input -> post('question');
		$mandatory = $this -> input -> post('mandatory');
		$q_num=$this->input->post('q_num');
		$question_type=$this->input->post('question_type');
		$qpd_unit_id = $this -> manage_mte_qp_model -> get_qpd_unit_id($qpd_id, $sec_name2);
		$qpd_unitd_id = $qpd_unit_id[0]['qpd_unitd_id'];

		// $data['pi']=$pi;$data['co_data']=$co_data;$data['qpd_unitd_id']=$qpd_unitd_id;$data['sec_name']=$sec_name; $data['qn']=$qn;$data['sec_name2']=$sec_name2;$data['sec_id']=$sec_id;$data['qes_num']=$qes_num;$data['co']=$co;$data['po']=$po;$data['topic']=$topic;$data['level']=$level;$data['mark']=$mark;$data['question']=$question;
		$result = $this -> manage_mte_qp_model -> check_question($sec_name, $qes_num);
		$data = array('pi' => $pi, 'co_data' => $co_data, 'qpd_unitd_id' => $qpd_unitd_id, 'sec_name' => $sec_name, 'qn' => $qn, 'sec_name2' => $sec_name2, 'sec_id' => $sec_id, 'qes_num' => $qes_num, 'co' => $co, 'po' => $po, 'topic' => $topic, 'level' => $level, 'mark' => $mark, 'question' => $question, 'qpd_id' => $qpd_id, 'mandatory' => $mandatory,'q_num'=>$q_num ,'question_type' => $question_type);
	
		if ($result == 0) {
			$re = $this -> manage_mte_qp_model -> insert_data_new($data);
		} else {
			$re = "false";
		}
		echo json_encode($re);

	}
	
	

	public function fetch_questions_data() {

		$qpd_id = $this -> input -> post('qpd_id');
		$result = $this -> manage_mte_qp_model -> fetch_groups($qpd_id);
		$co_data_val = $result['co_data'];
		$po_data_val = $result['po_data'];
		$topic_data_val = $result['topic_data'];
		$level_data_val = $result['level_data'];
		$pi_data_val = $result['pi_data'];
		$qt_data_val = $result['qt_data'];
		$table = '';
		$qp_mapping_entity = $this -> manage_mte_qp_model -> mapping_entity();
		
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
						$table .= '<td>' . $qt_data_val[$j] . '</td>';
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
		$result = $this -> manage_mte_qp_model -> fetch_Mapping_data($qp_map_id, $crclm_id, $term_id, $crs_id, $qpd_id,$unit_id);		
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
		$sec_name = $this -> input -> post('unit_q_no');
		$mandatory = $this -> input -> post('mandatory');
		$question_type = $this->input->post('question_type');		
		$re = $this -> manage_mte_qp_model -> check_question_update($sec_name, $qp_subq_code,$qp_mq_id);
		 if ($re == 0) {
			$result = $this -> manage_mte_qp_model -> update_qp($sec_name,$qp_subq_code, $qp_subq_marks, $qp_mq_id, $qp_content, $co, $po, $topic, $bl, $pi, $mandatory,$qp_mq_code , $question_type);
		} else {
			$result['value'] =-2;
		}
		echo $result['value'];
	}
	
		public function mte_qp_delete($pgm_id = NULL, $crclm_id = NULL, $term_id = NULL, $crs_id = NULL, $qp_type = NULL, $qpd_id = NULL) {
		if (!$this -> ion_auth -> logged_in()) {
			//redirect them to the login page
			redirect('login', 'refresh');
		} elseif (!($this -> ion_auth -> is_admin() || $this -> ion_auth -> in_group('Chairman') || $this -> ion_auth -> in_group('Program Owner') || $this -> ion_auth -> in_group('Course Owner'))) {
			//redirect them to the home page because they must be an administrator or owner to view this
			redirect('curriculum/peo/blank', 'refresh');
		} else {
			$model_qp_edit = $this -> manage_mte_qp_model -> mte_qp_delete($crclm_id, $term_id, $crs_id, $qp_type, $qpd_id);

			echo $model_qp_edit;
		}
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
			$deleted = $this -> manage_mte_qp_model -> deleteUser_Roles($Sl_id);
		}
	}//End of function deleteUser_Roles
	
	public function generate_model_mte_qp() {
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
                        
			$model_qp_details = $this -> manage_mte_qp_model -> generate_model_qp($pgm_id);
			//$data['qp_unit_data'] 	= $model_qp_details['qpf_unit_details'] ;
			if(!empty($model_qp_details['qpf_mq_details'])){$model_qp_data['qp_mq_data'] = $model_qp_details['qpf_mq_details'];}
			$model_qp_data['qp_entity'] 		= $model_qp_details['qp_entity_config'];
			$data['qp_entity'] 		= $model_qp_details['qp_entity_config'];
			$result = $this -> manage_mte_qp_model -> model_mte_qp_details($crclm_id, $term_id, $crs_id, $qp_type, $qpd_id);

			$model_qp_data['meta_data'] = $result['qp_meta_data'];
			if(!empty( $result['co_data'])){$co_data_val = $result['co_data'];}
			if(!empty( $result['po_data'])){$po_data_val = $result['po_data'];}
			if(!empty( $result['topic_data'])){$topic_data_val = $result['topic_data'];}
			if(!empty( $result['level_data'])){$level_data_val = $result['level_data'];}
			if(!empty( $result['pi_data'])){$pi_data_val = $result['pi_data'];}
			if(!empty( $result['qt_data'])){$qt_data_val = $result['qt_data'];}
			$table = '';

			$qp_mapping_entity = $this -> manage_mte_qp_model -> mapping_entity();
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
			$model_qp_data['blooms_graph_data'] = $this -> manage_mte_qp_model -> getBloomsLevelPlannedCoverageDistribution($crs_id, $qpd_id, $qp_type);
			$this -> db -> reconnect();
			$model_qp_data['blooms_level_marks_graph_data'] = $this -> manage_mte_qp_model -> getBloomsLevelMarksDistribution($crs_id, $qpd_id, $qp_type);
			$this -> db -> reconnect();
			$model_qp_data['co_planned_coverage_graph_data'] = $this -> manage_mte_qp_model -> getCOLevelMarksDistribution($crs_id, $qpd_id);
			$this -> db -> reconnect();
			$model_qp_data['topic_planned_coverage_graph_data'] = $this -> manage_mte_qp_model -> getTopicCoverageDistribution($crs_id, $qpd_id);

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
	
	
    /*
     * Function to get the curriculum list
     */

    public function get_dept_list() {
        $dept_list = $this->manage_mte_qp_model->list_dept();
        $html = '<option value>Select Department</option>';
        foreach ($dept_list['dept_result'] as $list) {
            $html .= '<option value="' . $list['dept_id'] . '">' . $list['dept_name'] . '</option>';
        }
        echo $html;
    }
	
	
    /*
     * Function to get the Program List
     */

    public function get_pgm_list() {
        $dept_id = $this->input->post('dept_id');
        $pgm_data = $this->manage_mte_qp_model->pgm_fill($dept_id);

        $pgm_details = $pgm_data['pgm_result'];
        $i = 0;
        $list[$i] = '<option value="">Select Program</option>';
        $i++;
        foreach ($pgm_details as $data) {
            $list[$i] = "<option value = " . $data['pgm_id'] . ">" . $data['pgm_acronym'] . "</option>";
            $i++;
        }
        $list = implode(" ", $list);
        echo $list;
    }

    /*
     * Function to get the list of curriculum
     */

    public function get_crclm_list() {
        $crclm_list = $this->select_crclm_list();
        echo $crclm_list;
    }

    /*
     * Function to get the list of curriculum terms
     */

    public function get_term_list() {
        $term_list = $this->select_term();
        echo $term_list;
    }

    /*
     * Function to get the list of courses
     */

    public function get_course_list() {
        $crclm_id = $this->input->post('crclm_id');
        $term = $this->input->post('term_id');
        $to_crs_id = $this->input->post('course_id');

        $course_data = $this->manage_mte_qp_model->course_fill($crclm_id, $term, $to_crs_id);
        if ($course_data) {
            $i = 0;
            $list[$i] = '<option value="">Select Course</option>';
            $i++;
            foreach ($course_data as $data) {
                $list[$i] = "<option value=" . $data['crs_id'] . ">" . $data['crs_title'] . "</option>";
                $i++;
            }
        } else {
            $i = 0;
            $list[$i++] = '<option value="">Select Course</option>';
            $list[$i] = '<option value="">No Courses to display</option>';
        }

        $list = implode(" ", $list);
        echo $list;
    }
    public function select_term() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            $crclm_id = $this->input->post('crclm_id');
            $term_data = $this->manage_mte_qp_model->term_drop_down_fill($crclm_id);
            $i = 0;
            $list[$i] = '<option value="">Select Term</option>';
            $i++;

            foreach ($term_data as $data) {
                $list[$i] = "<option value=" . $data['crclm_term_id'] . ">" . $data['term_name'] . "</option>";
                $i++;
            }
            $list = implode(" ", $list);
            echo $list;
        }
    }
	
    /*
     * Function to get the lis of question paper
     */

    public function get_qp_list() {
        $dept_id = $this->input->post('dept_id');
        $pgm_id = $this->input->post('pgm_id');
        $crclm_id = $this->input->post('crclm_id');
        $term_id = $this->input->post('term_id');
        $crs_id = $this->input->post('crs_id');
        $ao_id = $this->input->post('ao_id');
        $qp_list = $this->manage_mte_qp_model->fetch_qp_list($dept_id, $pgm_id, $crclm_id, $term_id, $crs_id, $ao_id);

        $html = '<div id="occasion_div_title"><u><b>' . $this->lang->line('entity_mte') . ' Question Paper List</b></u></div></br>';
        $html .='<table id="cia_qp_list_table" class="table table-bordered dataTable">';
        $html .='<thead>';
        $html .='<tr>';
        // $html .='<th>Sl No.</th>';
        $html .='<th>Section/Division</th>';
        $html .='<th>Select QP</th>';
        $html .='<th>AO Name</th>';
        $html .='<th>QP Title</th>';
        $html .='<th>Duration</th>';
        $html .='<th>QP Max Marks</th>';
        $html .='<th>QP Grand Total</th>';
        $html .='</tr>';
        $html .='</thead>';
        $html .='<tbody>';

        $i = 0;
        $j = 1;
        foreach ($qp_list as $qp) {
            $html .='<tr title="' . $qp['qpd_notes'] . '">';
            //   $html .= '<td>'.$j.'</td>';
            $html .= '<td><b>Section ' . $qp['section_name'] . '</b></td>';
            $html .= '<td><center><input type="radio" name="qp_radio_id" value="' . $qp['qpd_id'] . '" data-qpd_id="' . $qp['qpd_id'] . '" data-ao_id="' . $qp['aoid'] . '" id="radio_id_' . $i . '" data-ao_name="' . $qp['aoname'] . '"  class="qp_list"/><center></td>';
            
            $html .= '<td>' . $qp['ao_desc'] . '</td>';
            $html .= '<td>' . $qp['qpd_title'] . '</td>';
            $html .= '<td>' . $qp['qpd_timing'] . '</td>';
            $html .= '<td>' . $qp['qpd_max_marks'] . '</td>';
            $html .= '<td>' . $qp['qpd_gt_marks'] . '</td>';
            $html .= '</tr>';

            $i++;
            $j++;
        }

        $html .= '</tbody>';
        $html .= '</table>';
        // $html .= '<div><input type="hidden" name="occasion_count" value="0" id="occasion_count"  class="occasion_cout input-mini"/></div>';
        echo $html;
    }
	
	
    /*
     * Function to check the existance of the qp for the course
     */

    public function existance_of_qp() {
        $qpd_id = $this->input->post('qpd_id');
        $ao_id = $this->input->post('ao_id');
        $crclm_id = $this->input->post('crclm_id');
        $term_id = $this->input->post('term_id');
        $crs_id = $this->input->post('crs_id');
        $existance_of_qp = $this->manage_mte_qp_model->existance_of_qp($qpd_id, $ao_id, $crclm_id, $term_id, $crs_id);
        echo $existance_of_qp['size'];
    }
	    /*
     * Function to get the qp details 
     */

    public function get_qp_data_import($flag = null, $qpd_id = null, $ao_id = null, $crclm_id = null, $term_id = null, $crs_id = null) {
	
        if ($flag) {
            $qp_details = $this->manage_mte_qp_model->get_qp_data_import($qpd_id, $ao_id, $crclm_id, $term_id, $crs_id);
            return $qp_details;
        } else {
            $qpd_id = $this->input->post('qpd_id');
          //  $ao_id = $this->input->post('ao_id');
            $crclm_id = $this->input->post('crclm_id');
            $term_id = $this->input->post('term_id');
            $crs_id = $this->input->post('crs_id');
			$ao_id = $this->input->post('ao_id');
            $qp_details = $this->manage_mte_qp_model->get_qp_data_import($qpd_id, $ao_id, $crclm_id, $term_id, $crs_id);
        }
        echo $qp_details;
        exit();
    }
	
	/*
         * Function to get the review and assignment questions
         */
    public function get_rev_assin_questions(){
            $crs_id = $this->input->post('crs_id');
            $que_type = $this->input->post('que_type');
            
            $question_list = $this->manage_mte_qp_model->get_rev_assin_questions($crs_id,$que_type);
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
	
    
}


