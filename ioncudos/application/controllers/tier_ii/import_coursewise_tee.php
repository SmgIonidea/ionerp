<?php
/**
* Description		:	Tier II - Import Course wise Data List View
* Created		:	29-12-2015
* Author 		:   Arihant Prasad
* Modification History:
* Date				Modified By				Description
* 11-06-2017		Jyoti				Consolidated View for CIA and TEE Marks 
-------------------------------------------------------------------------------------------------
*/
?>

<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Import_coursewise_tee extends CI_Controller {
	
	function __construct() {
        // Call the Model constructor
		parent::__construct();
		$this->load->model('assessment_attainment/tier_ii/import_coursewise_tee/import_coursewise_tee_data_model');
	}

	/**
	 * Function is to list rolled out courses and their details
	 * @parameters:
	 * @return: load import assessment list view page
	 */
	public function index() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
			redirect('login', 'refresh');
		} elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
			redirect('curriculum/peo/blank', 'refresh');
		} else {
			$dept_list = $this->import_coursewise_tee_data_model->dept_fill();
			
			if(!empty($dept_list)) {
				$data['dept_data'] = $dept_list['dept_result'];
				$data['title'] = 'Import Course-wise '.$this->lang->line('entity_see').' List Page';
				$this->load->view('assessment_attainment/tier_ii/import_coursewise_tee/import_coursewise_tee_vw', $data);
			} else {
				$data['title'] = 'Import Course-wise '.$this->lang->line('entity_see').' List Page';
				$this->load->view('assessment_attainment/tier_ii/import_coursewise_tee/import_coursewise_tee_vw', $data);
			}
		}
	}
	
	/**
	 * Function is to fetch all the programs under the department
	 * @parameters:
	 * @return: an object
	 */
	public function select_pgm_list() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
			redirect('login', 'refresh');
		} elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
			redirect('curriculum/peo/blank', 'refresh');
		} else {
			$dept_id = $this->input->post('dept_id');
			$pgm_data = $this->import_coursewise_tee_data_model->pgm_fill($dept_id);
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
	}
	
	/**
	 * Function is to fetch all the curricula under the program
	 * @parameters:
	 * @return: an object
	 */
	public function select_crclm_list() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
			redirect('login', 'refresh');
		} elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
			redirect('curriculum/peo/blank', 'refresh');
		} else {
			$pgm_id = $this->input->post('pgm_id');
			$curriculum_data = $this->import_coursewise_tee_data_model->crclm_fill($pgm_id);
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
	}
	
	/**
	 * Function is to fetch all the term under the curriculum
	 * @parameters:
	 * @return: an object
	 */
	public function select_termlist() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
			redirect('login', 'refresh');
		} elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
			redirect('curriculum/peo/blank', 'refresh');
		} else {
			$crclm_id = $this->input->post('crclm_id');
			$term_data = $this->import_coursewise_tee_data_model->term_fill($crclm_id);
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
	}
	
	/**
	 * Function is to fetch all the course under the term
	 * @parameters:
	 * @return: an object
	 */
	public function show_course() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
			redirect('login', 'refresh');
		} elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
			redirect('curriculum/peo/blank', 'refresh');
		} else {
			$dept_id = $this->input->post('dept_id');
			$prog_id = $this->input->post('prog_id');
			$crclm_id = $this->input->post('crclm_id');
			$term_id = $this->input->post('term_id');
			
			$course_data = $this->import_coursewise_tee_data_model->course_list($crclm_id, $term_id);
			
			$i = 1;
			$msg = '';
			$del = '';
			$qp_status_flag = '';
			$data = $course_data['crs_list_data'];
			$crs_list = array();
			
			foreach ($data as $crs_data) {
				if ($crs_data['crs_mode'] == 1) {
					$msg = 'Practical';
				} else {
					$msg = 'Theory';
				}
				
				$upload_view = '<a type="button" id="file_uploader" value="Upload" title = ".doc, .docx, .odt, .rtf and .pdf files of size 10MB are allowed." data-toggle= "modal" data-id="'.$crs_data['crs_id'].'"  class="upload_qp cursor_pointer"> Upload </a> | <a role="button" data-crs_id = "'.$crs_data['crs_id'].'", data-dept_id ="'.$dept_id.'", data-prog_id = "'.$prog_id.'", data-crclm_id = "'.$crclm_id.'", data-term_id = "'.$term_id.'"  href="#" class = "upload_qp_view"> View </a>';  

				$export_import = '<a role = "button"  data-toggle = "modal" data-crclm_id = "'.$crclm_id.'" data-term_id="'.$term_id.'" data-crs_id="'.$crs_data['crs_id'].'" title = "Download Template" abbr_href = "'.base_url().'tier_ii/import_coursewise_tee/to_excel/'.$crclm_id.'/'.$term_id.'/'.$crs_data['crs_id'].'" class = "myTagRemover get_crs_id tee_qp_download_template cursor_pointer" id = "' . $crs_data['crs_id'] . '" > Download  </a> | '
                                        . '<a role = "button"  data-toggle = "modal" title = "Import Template" data-crclm_id = "'.$crclm_id.'" data-term_id="'.$term_id.'" data-crs_id="'.$crs_data['crs_id'].'"'
                                        . 'abbr_href = "'.base_url().'tier_ii/import_coursewise_tee/temp_import_template/'.$dept_id.'/'.$prog_id.'/'.$crclm_id.'/'.$term_id.'/'.$crs_data['crs_id'].'" '
                                        . 'class = "myTagRemover get_crs_id cursor_pointer import_data_details" id = "' . $crs_data['crs_id'] . '" > Import  </a> | '
                                        . '<a role = "button"  data-toggle = "modal" title = "View Template" href = "'.base_url().'tier_ii/import_coursewise_tee/fetch_student_marks/'.$dept_id.'/'.$prog_id.'/'.$crclm_id.'/'.$term_id.'/'.$crs_data['crs_id'].'/'.'" class = "myTagRemover get_crs_id" id = "' . $crs_data['crs_id'] . '" > View </a>';
                                
                                $consolidated_view = '<a role = "button" title = "Consolidated CIA and TEE" href = "'.base_url().'tier_ii/import_coursewise_tee/fetch_consolidated_student_marks/'.$dept_id.'/'.$prog_id.'/'.$crclm_id.'/'.$term_id.'/'.$crs_data['crs_id'].'/'.'" class = "myTagRemover get_crs_id" id = "' . $crs_data['crs_id'] . '" > View Marks </a>';

				//status flag has been commented temporarily
				$reviewer_id = $crs_data['validator_id'];
				$user = $this->ion_auth->user($reviewer_id)->row();
				$crs_list[] = array(
						'sl_no' => $i,
						'crs_id' => $crs_data['crs_id'],
						'crs_code' => $crs_data['crs_code'],
						'crs_title' => $crs_data['crs_title'],
						'crs_type_name' => $crs_data['crs_type_name'],
						'total_credits' => $crs_data['total_credits'],
						'total_marks' => $crs_data['see_marks'] + $crs_data['ss_marks'],
						'crs_mode' => $msg,
						'username' => $crs_data['title'].' '.ucfirst($crs_data['first_name']).' '.ucfirst($crs_data['last_name']),
						'reviewer' => $user->title.' '.ucfirst($user->first_name).' '.ucfirst($user->last_name),
						'uploaded' => $upload_view,
						'export_import' => $export_import,
                                                'cia_tee' => $consolidated_view
						//'qp_status_flag' => $qp_status_flag
					);
				$i++;
			}
			
			echo json_encode($crs_list);
		}
	}
	
	/**
	 * Function is to export .csv under a course
	 * @parameters: curriculum id, term id and course id
	 * @return: .csv file
	 */
	public function to_excel($crclm_id, $term_id, $crs_id) {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
			redirect('login', 'refresh');
		} elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
			redirect('curriculum/peo/blank', 'refresh');
		} else {
			//insert qp into qp definition table and get qpd id
			$qpd_id = $this->import_coursewise_tee_data_model->qp_insert_details($crclm_id, $term_id, $crs_id);
			$results = $this->import_coursewise_tee_data_model->attainment_template($qpd_id);
			$file_name = $this->import_coursewise_tee_data_model->file_name_query($crs_id, $qpd_id);
			
			if($file_name == 5) {
				$file_name = "Unknown_Course";
				$results[0]['student_data'] = "Unknown Course";
				//move this command to __construct part
				$this->load->helper('download');
				//to remove unwanted '\n' while inserting the data to .csv
				ob_clean();
				force_download($file_name.'.csv', trim($results));
			} else {
				//move this command to __construct part
				$this->load->helper('download');
				//to remove unwanted '\n' while inserting the data to .csv
				ob_clean();
				force_download($file_name.'.csv', trim($results));
			}
		}
	}
	
	/**
	 * Function is to create temporary table to display students data
	 * @parameters: department id, program id, curriculum id, term id, course id, question paper id and reference id
	 * @return: load temporary import template view page
	 */
	public function temp_import_template($dept_id, $prog_id, $crclm_id, $term_id, $crs_id) {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
			redirect('login', 'refresh');
		} elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
			redirect('curriculum/peo/blank', 'refresh');
		} else {
			$qpd_id = $this->import_coursewise_tee_data_model->qp_details($crs_id);
			
			//check if qpd id exists or not
			if($qpd_id == 0) {
				//if qpd id does not exists - download .csv file and then redirect
				$this->to_excel($crclm_id, $term_id, $crs_id);
			}

			$qpd_id = $this->import_coursewise_tee_data_model->qp_id_details($crclm_id, $term_id, $crs_id);
			$result = $this->import_coursewise_tee_data_model->fetch_all_details($dept_id, $prog_id, $crclm_id, $term_id, $crs_id); 
			$final_marks = $this->import_coursewise_tee_data_model->qp_marks($crs_id, $qpd_id); 
			$data['file_name'] = $this->import_coursewise_tee_data_model->file_name_query($crs_id, $qpd_id); 
			
			$data['department'] = $result['dept_prog_name'][0]['dept_name'];
			$data['department_id'] = $dept_id;
			$data['program'] = $result['dept_prog_name'][0]['pgm_acronym'];
			$data['program_id'] = $prog_id;
			$data['curriculum'] = $result['crclm_term_crs_name'][0]['crclm_name'];
			$data['curriculum_id'] = $crclm_id;
			$data['term'] = $result['crclm_term_crs_name'][0]['term_name'];
			$data['crclm_term_id'] = $term_id;
			$data['course'] = $result['crclm_term_crs_name'][0]['crs_title'].' ('.$result['crclm_term_crs_name'][0]['crs_code'].')';
			$data['course_id'] = $crs_id;
			$data['qpd_id'] = $qpd_id;
			$data['qp_subq_marks'] = $final_marks[0]['qpd_max_marks'];
			$data['qpd_unitd_id'] = $final_marks[0]['qpd_unitd_id'];
			//for final exam - concatenate in the end
			$data['no_of_questions'] = count($final_marks);
			$data['final_exam'] = 'Semester End Exam';			
			$data['title'] = 'Import Course-wise '.$this->lang->line('entity_see').' List Page';

			$this->load->view('assessment_attainment/tier_ii/import_coursewise_tee/temp_import_template_vw', $data); 
		}
	}
	
	/**
	 * Function is to discard temporary table on cancel
	 * @parameters:
	 * @return: boolean value
	 */
	public function drop_temp_table() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
			redirect('login', 'refresh');
		} elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
			redirect('curriculum/peo/blank', 'refresh');
		} else {
			$crs_id = $this->input->post('crs_id');
			$drop_result = $this->import_coursewise_tee_data_model->drop_temp_table($crs_id);
			
			return true;
		}
	}
	
	/**
	 * Function is to insert student data from temporary table to main table
	 * @parameters: 
	 * @return: an object
	 */
	public function insert_into_student_table() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
			redirect('login', 'refresh');
		} elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
			redirect('curriculum/peo/blank', 'refresh');
		} else {
	//	var_dump($_POST);exit;
			$qpd_id = $this->input->post('qpd_id');
			$crs_id = $this->input->post('crs_id');
			$crclm_id = $this->input->post('crclm_id');
			$term_id = $this->input->post('term_id');
			
			$result = $this->import_coursewise_tee_data_model->insert_into_student_table($qpd_id, $crs_id , $crclm_id , $term_id);

			echo $result;
		}
	} 

	
	/**
	 * Function is to import .csv file for a course and return to list page
	 * @parameters: course id and question paper id
	 * @return: an object
	 */
	public function to_database($crs_id, $qpd_id) {
		$file_header_array =  Array();

		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
			redirect('login', 'refresh');
		} elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
			redirect('curriculum/peo/blank', 'refresh');
		} else {
			if(!empty($_FILES)) {
				$tmp_file_name = $_FILES['Filedata']['tmp_name'];
				$name = $_FILES['Filedata']['name'];
				
				$export_file_name = $this->import_coursewise_tee_data_model->file_name_query($crs_id, $qpd_id);

				if($name == $export_file_name.'.csv') {
					//store csv file in upload folder
					$ok = move_uploaded_file($tmp_file_name, "./uploads/$name");
					
					//call read_csv function for validating the header
					$file_header_array = $this->read_csv("./uploads/$name", $qpd_id);					
					if(count($file_header_array)==3){ 
						$code_question = array();				
						for($i = 0; $i<count($file_header_array) ;$i++){					
								$str = array_shift(explode('(', $file_header_array[$i]));
								$str = strtolower ($str );
								$code_question[]= $str;
						}
						$file_header_array =$code_question;
					}
					$results = $this->import_coursewise_tee_data_model->load_csv_data_temp_table($crs_id, "./uploads/$name", $name, $file_header_array, $qpd_id);
					
					if($results) {
						$data = $this->import_coursewise_tee_data_model->fetch_student_marks($results);
						$this->load->view('assessment_attainment/tier_ii/import_coursewise_tee/temp_generate_table_vw', $data);
					}
				} else {
					echo '0';
				}
			}
		}
	}

	/**
	 * Function is to validate .csv file
	 * @parameters: file path and question paper id
	 * @return: flag
	 */
	public function read_csv($full_path, $qpd_id) {
		// extract the post data which contains uploaded file information.
		//Fetch file headers
		if(($file_handle = fopen($full_path, "r")) != FALSE) {
			// Read the csv file contents
			$file_header_array = fgetcsv($file_handle, 2000, ',', '"');
			
			//fetch template header from database for comparison
			$original_file_header = $this->import_coursewise_tee_data_model->attainment_template($qpd_id, false);
			
			//trim and revmove space
			array_walk($file_header_array, function (&$value) { $value = trim($value); });
			array_walk($original_file_header[0], function (&$value) { $value = trim($value); });
			
			//comma separated
			$header_file = implode(",", $file_header_array);
			$header_fetch = implode(",", $original_file_header[0]);			
			$header_str_cmp = strcmp($header_file, $header_fetch);
		}

		// close the file
		fclose($file_handle);
		
		if ($header_str_cmp == 0) {	
			$csv_data_count = $this->csv_file_check_data($full_path);
			
			if($csv_data_count == 1) {
				//csv file is empty
				echo '4';
			} else {
				return $file_header_array;
			}
		} else {
			//incorrect file is uploaded
			echo '3';
		}
	}
	
	/**
	 * Function is to check if there is any data inside the .csv file
	 * @parameters: file path
	 * @return: flag
	 */
	public function csv_file_check_data($full_path) {
		//Fetch file headers
		if(($file_handle = fopen($full_path, "r")) != FALSE) {			
			$row = 0;
			while(($data = fgetcsv($file_handle, 1000, ",")) !== FALSE) {
				$row++;
			}
		}

		// close the file
		fclose($file_handle);
		return $row;
	}
	
	/**
	 * Function is to display students data that has been imported
	 * @parameters: department id, program id, curriculum id, term id, course id, question paper id & reference id
	 * @return: load students assessment table view page
	 */
	public function fetch_student_marks($dept_id, $prog_id, $crclm_id, $term_id, $crs_id) {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
			redirect('login', 'refresh');
		} elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
			redirect('curriculum/peo/blank', 'refresh');
		} else {
			$qpd_id = $this->import_coursewise_tee_data_model->qp_details($crs_id);			
			//check if qpd id exists or not
			if($qpd_id == 0) {
				//if qpd id does not exists - download .csv file and then redirect
				$this->to_excel($crclm_id, $term_id, $crs_id);
			}
			
			$result = $this->import_coursewise_tee_data_model->fetch_all_details($dept_id, $prog_id, $crclm_id, $term_id, $crs_id);
			$data['file_name'] = $this->import_coursewise_tee_data_model->file_name_query($crs_id, $qpd_id);
			
			$data['department'] = $result['dept_prog_name'][0]['dept_name'];
			$data['dept_id'] = $dept_id;
			$data['program'] = $result['dept_prog_name'][0]['pgm_acronym'];
			$data['prog_id'] = $prog_id;
			$data['curriculum'] = $result['crclm_term_crs_name'][0]['crclm_name'];
			$data['crclm_id'] = $crclm_id;
			$data['term'] = $result['crclm_term_crs_name'][0]['term_name'];
			$data['term_id'] = $term_id;
			$data['course'] = $result['crclm_term_crs_name'][0]['crs_title'].' ('.$result['crclm_term_crs_name'][0]['crs_code'].')';
			$data['crs_id'] = $crs_id;
			$data['qpd_id'] = $qpd_id;
			$data['ref_id'] = NULL;
			
			$data['course_marks'] = $this->import_coursewise_tee_data_model->student_marks($qpd_id);
			
			$data['title'] = 'View Imported Course-wise '.$this->lang->line('entity_see').' Marks Page';
			$this->load->view('assessment_attainment/tier_ii/import_coursewise_tee/import_student_marks_vw', $data);
		}
	}
        
	
	/**
	 * Function is to fetch and display data analysis report
	 * @parameters: 
	 * @return: load students assessment table view page
	 */
	public function dataAnalysis() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
			redirect('login', 'refresh');
		} elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
			redirect('curriculum/peo/blank', 'refresh');
		} else {
			$qpd_id = $this->input->post('qpd_id');	
			$data['student_dataAnalysis'] = $this->import_coursewise_tee_data_model->dataAnalysis($qpd_id);
			$data['attainment_header'] = $this->import_coursewise_tee_data_model->attainment_template($qpd_id);
			
			$this->load->view('assessment_attainment/tier_ii/import_coursewise_tee/import_assessment_dataAnalysis_vw', $data);
		}
	}
	
	/**
	 * Function is to discard main table from the database
	 * @parameters:
	 * @return: boolean value
	 */
	public function drop_main_table() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
			redirect('login', 'refresh');
		} elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
			redirect('curriculum/peo/blank', 'refresh');
		} else {
			$qpd_id = $this->input->post('qpd_id');
			$drop_result = $this->import_coursewise_tee_data_model->drop_main_table($qpd_id);
			
			return true;
		}
	}

	public function save_txt(){
		$test = $this->input->post('test');
		$unit_id = $this->input->post('unitid');
		$qpd_id = $this->input->post('qpd_id');
		$value = $this->import_coursewise_tee_data_model->save_marks_db($test,$unit_id,$qpd_id); 
		echo $value;
	}

	/**
	 * Function is to upload the file to the particular folder
	 * @parameters:
	 * @return: adds the file name into the qp_tee_upload table
	 */
	public function upload_tee() {
		$today = date('y-m-d');
		$day = date("dmY", strtotime($today));
		$time = date("His"); 			
		$datetime = $day.'_'.$time;
			
 		$crclm_id = $this->input->post('u_crclm_id'); 
		$term_id = $this->input->post('u_term_id'); 
		$crs_id = $this->input->post('u_crs_id');  
		$name = $_FILES['Filedata']['name']; 
		
		$allowedExts = array("pdf", "doc", "docx", "odt", "rtf");
		$imageFileType = pathinfo($name, PATHINFO_EXTENSION); 
		if($imageFileType != "doc" && $imageFileType != "pdf" && $imageFileType != "docx" && $imageFileType !="odt" && $imageFileType != "rtf") {
		    echo "-1";	    
		} else {	
			
			$qpd_type = 5;
			$qpd_id = $this->import_coursewise_tee_data_model->qp_detail($crs_id); 
			$crclm_term_id =  $this->import_coursewise_tee_data_model->show($crs_id, $qpd_type); 
	 
			$file_name = array( 'qpd_id' => $qpd_id,
					    'qpd_type' => $qpd_type,
					    'crclm_id' => $crclm_id,
					    'crclm_term_id' => $crclm_term_id[0]['crclm_term_id'],
					    'crs_id' => $crs_id,
					    'file_name' => $qpd_id.'_'.$datetime.'_'.$name,
					    'uplaod_date' => $today); 

			$this->import_coursewise_tee_data_model->add_file($file_name);
			
			//base_url() not working
			$upload_dir = "./uploads/upload_qp/";
			
			$upload_file = $upload_dir .$qpd_id.'_'.$datetime.'_'.basename($_FILES['Filedata']['name']); 
			if (move_uploaded_file($_FILES['Filedata']['tmp_name'], $upload_file)) {
				echo "1";
			} else {
				echo "0";
			} 
		}
	}

	/**
	 * Function is to fetch the uploaded file from the database
	 * @parameters:
	 * @return: Displays the selected file
	 */

	public function fetch_upload_qp() {
		$crs_id = $this->input->post('crs_id'); 
		$dept_id = $this->input->post('dept_id');
		$prog_id = $this->input->post('prog_id'); 
		$crclm_id = $this->input->post('crclm_id'); 
		$term_id = $this->input->post('term_id'); 
		
		$qpd_type = 5;	
		$qpd_id = $this->import_coursewise_tee_data_model->fetch_qp_details($crclm_id, $term_id, $crs_id, $qpd_type); 
		if(!empty($qpd_id)){
			$upload['result'] = $this->import_coursewise_tee_data_model->upload_qp($crs_id, $qpd_id[0]['qpd_id']); 
	
			if(!empty($upload['result'])){
				echo $upload['result'][0]['file_name'];
			} else {
				echo "-2";
			}
		} else {
			echo "0";
		}
	}

	/**
	 * Function is to check whether the file is already present
	 * @parameters:
	 * @return: 
	 */
	public function check_crsid(){
		$crs_id = $this->input->post('crs_id');
		$qpd_id = $this->import_coursewise_tee_data_model->qp_detail($crs_id); 
		$file = $this->import_coursewise_tee_data_model->filename($crs_id);
		$a['file'] = $file; 
		$a['qpd_id'] = $qpd_id;
		echo json_encode($a);
	} 

	/**
	 * Function is to update the new data fpr the same course id
	 * @parameters:
	 * @return: updates the file_name with the new one
	 */
	public function update_filename(){
		$file = $this->input->post('re_u_filedata'); 
		$crs_id = $this->input->post('re_u_crs_id');   
		$qpd_id = $this->import_coursewise_tee_data_model->qp_detail($crs_id); 
		$new_file = str_replace("C:\\fakepath\\", "", $file); 
		
		$today = date('y-m-d');
		$day = date("dmY", strtotime($today));
		$time = date("His"); 			
		
		$datetime = $day.'_'.$time;
		$update_new_file = $qpd_id.'_'.$datetime.'_'.$new_file; 
		$allowedExts = array("pdf", "doc", "docx", "odt", "rtf");
		
		$name = $_FILES['Filedata_1']['name']; 

		$imageFileType = pathinfo($name,PATHINFO_EXTENSION); 
		if($imageFileType != "doc" && $imageFileType != "pdf" && $imageFileType != "docx" && $imageFileType !="odt" && $imageFileType != "rtf") {
		    echo "-1";
		    
		} else {		
			$up_file = $this->import_coursewise_tee_data_model->update_file($update_new_file, $crs_id);
			$qpd_type = 5;
			
			//base_url() not working
			$upload_dir = "./uploads/upload_qp/"; 
			
			$upload_file = $upload_dir.$qpd_id.'_'.$datetime.'_'.basename($_FILES['Filedata_1']['name']);
		
			if (move_uploaded_file($_FILES['Filedata_1']['tmp_name'], $upload_file)) {
				echo json_encode(1);
			} else {
				return 0;
			} 	
		}
	}
	
	       /*
     * Function to get the organisation type
     * @param: 
     * @return:
     */
    public function get_organisation_type(){
        $crclm_id = $this->input->post('crclm_id');
        $term_id = $this->input->post('term_id');
        $crs_id = $this->input->post('crs_id');
                
            $get_organisation_type = $this->import_coursewise_tee_data_model->get_organisation_type($crclm_id,$term_id,$crs_id); 
            $get_org_type_data['target_or_threshold'] = $get_organisation_type['target_or_threshold_size'];
            $get_org_type_data['org_type'] = $get_organisation_type['org_type'];
            if(($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner'))){
                $admin_hod_po_val = 1;
            }else{
                $admin_hod_po_val = 0;
            }
            $get_org_type_data['admin_hod_po_val'] = $admin_hod_po_val;
            echo json_encode($get_org_type_data);
    }
	
	/*
     * Function to check the student is uploaded for the respective curriculum or not
     * @param: crclm_id
     * @return:
     */
    public function check_student_uploaded_or_not(){
       $crclm_id = $this->input->post('crclm_id');
       $check_students = $this->import_coursewise_tee_data_model->check_student_uploaded_or_not($crclm_id);
       $data['student_count'] = $check_students['student_count'];
       echo json_encode($data);
       
    }
    
    /**
    * Function is to display students data that has been attainemnt in CIA and TEE 
    * @parameters: department id, program id, curriculum id, term id, course id, question paper id & reference id
    * @return: load students assessment table view page
    */
   public function fetch_consolidated_student_marks($dept_id, $prog_id, $crclm_id, $term_id, $crs_id) {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            $qpd_id = $this->import_coursewise_tee_data_model->qp_details($crs_id);			
            //check if qpd id exists or not
            if($qpd_id == 0) {
                    //if qpd id does not exists - download .csv file and then redirect
                    $this->to_excel($crclm_id, $term_id, $crs_id);
            }
            $result = $this->import_coursewise_tee_data_model->fetch_all_details($dept_id, $prog_id, $crclm_id, $term_id, $crs_id);
            $data['file_name'] = $this->import_coursewise_tee_data_model->file_name_query($crs_id, $qpd_id);

            $data['department'] = $result['dept_prog_name'][0]['dept_name'];
            $data['dept_id'] = $dept_id;
            $data['program'] = $result['dept_prog_name'][0]['pgm_acronym'];
            $data['prog_id'] = $prog_id;
            $data['curriculum'] = $result['crclm_term_crs_name'][0]['crclm_name'];
            $data['crclm_id'] = $crclm_id;
            $data['term'] = $result['crclm_term_crs_name'][0]['term_name'];
            $data['term_id'] = $term_id;
            $data['course'] = $result['crclm_term_crs_name'][0]['crs_title'].' ('.$result['crclm_term_crs_name'][0]['crs_code'].')';
            $data['crs_id'] = $crs_id;
            $data['qpd_id'] = $qpd_id;
            $data['ref_id'] = NULL;

            $data['course_marks'] = $this->import_coursewise_tee_data_model->consolidated_student_marks($crclm_id, $term_id, $crs_id, $qpd_id);
            $data['course_students'] = $this->import_coursewise_tee_data_model->get_assessment_student($qpd_id, $crclm_id, $term_id, $crs_id);			
//var_dump($data['course']);
            $data['title'] = 'View Imported Course-wise '.$this->lang->line('entity_see').' Marks Page';
            $this->load->view('assessment_attainment/tier_ii/import_coursewise_tee/import_consolidated_student_marks_vw', $data);
        }
    }
	
}
?>
