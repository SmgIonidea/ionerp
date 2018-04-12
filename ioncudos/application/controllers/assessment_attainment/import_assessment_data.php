<?php
/**
* Description	:	Import Assessment Data List View
* Created		:	30-09-2014. 
* Author 		:   Abhinay B.Angadi
* Modification History:
* Date				Modified By				Description
* 01-10-2014	   Arihant Prasad		Import and Export features,
										Permission setting, Added file headers, function headers, 
										indentations, comments, variable naming, 
										function naming & Code cleaning
  30-09-2014	   Waseem				View import students marks
* 12-11-2014	   Jyoti				Modified list view for QP display pdf creation
* 27-09-2016	   Bhagyalaxmi S S		
-------------------------------------------------------------------------------------------------
*/
?>

<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Import_assessment_data extends CI_Controller {
	
	function __construct() {
        // Call the Model constructor
		parent::__construct();
		$this->load->model('assessment_attainment/import_assessment_data/import_assessment_data_model');
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
			$dept_list = $this->import_assessment_data_model->dept_fill();
			
			if(!empty($dept_list)) {
				$data['dept_data'] = $dept_list['dept_result'];
				$data['title'] = 'Import Assessment List Page';
				$this->load->view('assessment_attainment/import_assessment_data/import_assessment_data_vw', $data);
			} else {
				$data['title'] = 'Import Assessment List Page';
				$this->load->view('assessment_attainment/import_assessment_data/import_assessment_data_vw', $data);
			}
		}
	}
	
	/**
	 * Function is to create temporary table to display students data
	 * @parameters: department id, program id, curriculum id, term id, course id, question paper id and reference id
	 * @return: load temporary import template view page
	 */
	public function temp_import_template($dept_id, $prog_id, $crclm_id, $term_id, $crs_id, $qpd_id, $ao_id = NULL, $ref_id = NULL , $mte = NULL) {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
			redirect('login', 'refresh');
		} elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
			redirect('curriculum/peo/blank', 'refresh');
		} else {
			$result = $this->import_assessment_data_model->fetch_all_details($dept_id, $prog_id, $crclm_id, $term_id, $crs_id, $ao_id);

			$data['file_name'] = $this->import_assessment_data_model->file_name_query($crs_id, $qpd_id, $ao_id);
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
			
			if($ao_id != NULL ) {
				//for CIA - concatenate in the end
				if( $ref_id !='ref_29'){			
								$data['ao_description'] = $result['ao_description'][0]['ao_description'];				
                                $data['section_id'] = $result['ao_description'][0]['section_id'];
                                $data['section_name'] = $result['ao_description'][0]['mt_details_name'];
				}else{
								$data['ao_description'] = '';
                                $data['section_id'] ='';
                                $data['section_name'] ='';
				
				}
				
				$data['ao_id'] = $ao_id;
			} else {
				//for final exam - concatenate in the end
				$data['final_exam'] = 'Semester End Exam';
				$data['ao_id'] = NULL;
				$data['section_id']  = NULL;
			    $data['section_name'] = NULL;
				 
                                
			}
			
			$data['ref_id'] = $ref_id;
			$data['title'] = 'Import Assessment List Page';                                            
			$this->load->view('assessment_attainment/import_assessment_data/temp_import_template_vw', $data);
		}
	}
	
		/**
	 * Function is to create temporary table to display students data
	 * @parameters: department id, program id, curriculum id, term id, course id, question paper id and reference id
	 * @return: load temporary import template view page
	 */
/* 	public function temp_import_template_mte($dept_id, $prog_id, $crclm_id, $term_id, $crs_id, $qpd_id, $ao_id = NULL, $ref_id = NULL , $) {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
			redirect('login', 'refresh');
		} elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
			redirect('curriculum/peo/blank', 'refresh');
		} else {
			$result = $this->import_assessment_data_model->fetch_all_details($dept_id, $prog_id, $crclm_id, $term_id, $crs_id, $ao_id);
			$data['file_name'] = $this->import_assessment_data_model->file_name_query($crs_id, $qpd_id, $ao_id);
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
			
			if($ao_id != NULL ) {
				//for CIA - concatenate in the end
				$data['ao_description'] = $result['ao_description'][0]['ao_description'];
                                $data['section_id'] = $result['ao_description'][0]['section_id'];
                                $data['section_name'] = $result['ao_description'][0]['mt_details_name'];
				$data['ao_id'] = $ao_id;
			} else {
				//for final exam - concatenate in the end
				$data['final_exam'] = 'Semester End Exam';
				$data['ao_id'] = NULL;
                                
			}
			
			$data['ref_id'] = $ref_id;
			$data['title'] = 'Import Assessment List Page';
                                                
			$this->load->view('assessment_attainment/import_assessment_data/temp_import_template_vw', $data);
		}
	} */
	
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
			$pgm_data = $this->import_assessment_data_model->pgm_fill($dept_id);
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
			$curriculum_data = $this->import_assessment_data_model->crclm_fill_middle($pgm_id);
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
			$term_data = $this->import_assessment_data_model->term_fill($crclm_id);
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
			$term_id = $this->input->post('crclm_term_id');
			
			$course_data = $this->import_assessment_data_model->course_list($crclm_id, $term_id);
			
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
				// Fetch ao_method_id
                                $get_ao_method_id = $this->import_assessment_data_model->fetch_ao_method_id($crs_data['crs_id']);
                                $ao_method_id = @$get_ao_method_id['ao_method_id'];
				if ($crs_data['qp_rollout'] == 1) {
					$qp_status_flag = '<a role = "button" data-toggle = "modal" title = "Ready to Import" href = "#myModal3" class = "btn btn-small btn-warning myTagRemover get_crs_id" id = "' . $crs_data['crs_id'] . '" ><i></i>Pending</a>';
				} else {
					$qp_status_flag = '<a role = "button"  data-toggle = "modal" title = "Import Completed" href = "#" class = "btn btn-small btn-success myTagRemover get_crs_id" id = "' . $crs_data['crs_id'] . '" ><i></i>Complete</a>';
				}
				if($crs_data['rubrics_flag'] == 1){
                                    $view_qp = '<a title = "View Rubrics Details" id = "' . $crs_data['crs_id'] . '" data-ao_method_id = "'.$ao_method_id.'" data-qpd_id = "'.$crs_data['qpd_id'].'"  class="cursor_pointer view_tee_rubrics" abbr="'.$prog_id.'/'.$crclm_id.'/'.$term_id.'/'.$crs_data['crs_id'].'/5'.'/'.$crs_data['qpd_id'].'"><i class = "myTagRemover "></i>View Rubrics</a>';
                                }else{
                                    $view_qp = '<a title = "View QP Details" id = "' . $crs_data['crs_id'] . '" class="cursor_pointer displayQP" abbr="'.$prog_id.'/'.$crclm_id.'/'.$term_id.'/'.$crs_data['crs_id'].'/5'.'/'.$crs_data['qpd_id'].'"><i class = "myTagRemover "></i>View QP</a>';
                                }
										$export_import = '<a role = "button"  data-crclm_id = "'.$crclm_id.'" id="tee_qp_download_template" class="tee_qp_download_template cursor_pointer" data-toggle = "modal" title = "Download Template" abbr_href = "'.base_url().'assessment_attainment/import_assessment_data/to_excel/'.$crs_data['crs_id'].'/'.$crs_data['qpd_id'].'" class = "myTagRemover get_crs_id" id = "' . $crs_data['crs_id'] . '" > Download  </a>'. '|'
                                        . '<a role = "button"  data-toggle = "modal" data-crclm_id = "'.$crclm_id.'" data-term_id="'.$term_id.'" data-crs_id ="'.$crs_data['crs_id'].'" '
                                        . 'title = "Import Student Marks" '
                                        . 'abbr_href = "'.base_url().'assessment_attainment/import_assessment_data/temp_import_template/'.$dept_id.'/'.$prog_id.'/'.$crclm_id.'/'.$term_id.'/'.$crs_data['crs_id'].'/'.$crs_data['qpd_id'].'" class = "myTagRemover get_crs_id import_data_details cursor_pointer" id = "' . $crs_data['crs_id'] . '" > Import  </a>'
				. '|<a role = "button"  data-toggle = "modal" title = "View Student Marks" href = "'.base_url().'assessment_attainment/import_assessment_data/fetch_student_marks/'.$dept_id.'/'.$prog_id.'/'.$crclm_id.'/'.$term_id.'/'.$crs_data['crs_id'].'/'.$crs_data['qpd_id'].'" class = "myTagRemover get_crs_id" id = "' . $crs_data['crs_id'] . '" > View  </a>';
                                 
				$reviewer_id = $crs_data['validator_id'];
				$user = $this->ion_auth->user($reviewer_id)->row();
				$crs_list[] = array(
					'sl_no' => $i,
					'crs_id' => $crs_data['crs_id'],
					'crs_code' => $crs_data['crs_code'],
					'crs_title' => $crs_data['crs_title'],
					'crs_type_name' => $crs_data['crs_type_name'],
					'total_credits' => $crs_data['total_credits'],
					'total_marks' => $crs_data['total_marks'],
					'crs_mode' => $msg,
					'username' => $crs_data['title'].' '.ucfirst($crs_data['first_name']).' '.ucfirst($crs_data['last_name']),
					'reviewer' => $user->title.' '.ucfirst($user->first_name).' '.ucfirst($user->last_name),
					'view_qp' =>$view_qp, 
					'export_import' => $export_import,
					'qp_status_flag' => $qp_status_flag
					);
				$i++;
			}
			echo json_encode($crs_list);
		}
	}

    /**
     * Function is to export .csv under a course
     * @parameters: course id and question paper id
     * @return: .csv file
     */
    public function to_excel($crs_id, $qpd_id, $ao_id = NULL,$section_id=null) {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } 
		
        if (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        }		
	
        $results = $this->import_assessment_data_model->attainment_template($qpd_id, $section_id);
        $file_name = $this->import_assessment_data_model->file_name_query($crs_id, $qpd_id, $ao_id);

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
    }

    /**
	 * Function is to import .csv file under a course and return to list page
	 * @parameters: course id and question paper id
	 * @return: an object
	 */
	public function to_database($crs_id, $qpd_id, $ao_id = NULL) {
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
				$export_file_name = $this->import_assessment_data_model->file_name_query($crs_id, $qpd_id, $ao_id);
				if($name == $export_file_name.'.csv') {
					//store csv file in upload folder
					$ok = move_uploaded_file($tmp_file_name, "./uploads/$name");
					
					//call read_csv function for validating the header
					$file_header_array = $this->read_csv("./uploads/$name", $qpd_id);
                                        
					$results = $this->import_assessment_data_model->load_csv_data_temp_table($crs_id, "./uploads/$name", $name, $file_header_array, $qpd_id, $ao_id);
					if($ao_id !=NULL){
						$section_id = $this->import_assessment_data_model->fetch_section_id($ao_id);
					}
					if($results) {
						$data = $this->import_assessment_data_model->fetch_student_marks($results);
						$this->load->view('assessment_attainment/import_assessment_data/temp_generate_table_vw', $data);
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
			$original_file_header = $this->import_assessment_data_model->attainment_template($qpd_id,null, false);
			
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
		
		if ($header_str_cmp==0) {	
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
			$crclm_id = $this->input->post('crclm_id');
			$term_id = $this->input->post('term_id');
			$section_id = $this->input->post('section_id');
			$crs_id = $this->input->post('crs_id');
			$qpd_id = $this->input->post('qpd_id');
			$ao_id = $this->input->post('ao_id');
			
			$result = $this->import_assessment_data_model->insert_into_student_table($qpd_id, $crs_id, $ao_id,$crclm_id , $term_id ,$section_id);

            echo $result;
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
			$drop_result = $this->import_assessment_data_model->drop_temp_table($crs_id);
			
			return true;
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
			$drop_result = $this->import_assessment_data_model->drop_main_table($qpd_id);
			
			return true;
		}
	}

	/**
	 * Function is to display students data that has been imported
	 * @parameters: department id, program id, curriculum id, term id, course id, question paper id & reference id
	 * @return: load students assessment table view page
	 */
	public function fetch_student_marks($dept_id, $prog_id, $crclm_id, $term_id, $crs_id, $qpd_id, $ao_id = NULL, $ref_id = NULL) {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
			redirect('login', 'refresh');
		} elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
			redirect('curriculum/peo/blank', 'refresh');
		} else {
			$result = $this->import_assessment_data_model->fetch_all_details($dept_id, $prog_id, $crclm_id, $term_id, $crs_id, $ao_id);			
            $data['file_name'] = $this->import_assessment_data_model->file_name_query($crs_id, $qpd_id, $ao_id);
			
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
			$data['ao_id'] = $ao_id;
			$data['ref_id'] = $ref_id;
			
		
			if($ao_id != NULL){
				if($result['mte_flag'] == 0 ){
				$data['section_id'] = $result['ao_description'][0]['section_id'];
				$data['section_name'] = $result['ao_description'][0]['mt_details_name'];
				}else{
					$data['section_id'] = 0;
					$data['section_name'] = '';
				}
			}
			
			$data['course_marks'] = $this->import_assessment_data_model->student_marks($qpd_id);			
			$data['title'] = 'Import Assessment Details';
			$this->load->view('assessment_attainment/import_assessment_data/import_student_marks_vw', $data);
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
			$data['student_dataAnalysis'] = $this->import_assessment_data_model->dataAnalysis($qpd_id);
			$data['attainment_header'] = $this->import_assessment_data_model->attainment_template($qpd_id,null,false);
			
			$this->load->view('assessment_attainment/import_assessment_data/import_assessment_dataAnalysis_vw', $data);
		}
	}
	
	public function dataAnalysisDataForGraph() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
			redirect('login', 'refresh');
		} elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
			redirect('curriculum/peo/blank', 'refresh');
		} else {
			$qpd_id = $this->input->post('qpd_id');	
			$student_dataAnalysis = $this->import_assessment_data_model->dataAnalysis($qpd_id);

			$qArray  = array();
			$attainmet_per = array();
			$attempt_per = array();
			$no_of_attempts = array();
			
			foreach ($student_dataAnalysis as $dataAnalysis) {
				$i=0;
				foreach ($dataAnalysis as  $value) {
					if($i!=0){
						array_push($qArray, $value);
					}
					$i++;	
				}
				break;
			}
			$j=0;
			foreach ($student_dataAnalysis as $dataAnalysis) {
				if($j==11){
					$count=0;
					foreach ($dataAnalysis as  $value) {
						if($count!=0){
							array_push($attainmet_per, $value);
						} $count++;
					}
				}else if($j==10){
					$count=0;
					foreach ($dataAnalysis as  $value) {
						if($count!=0){
						array_push($attempt_per, $value);
						} $count++;
					}
				}else if ($j==9) {
						$count=0;
					foreach ($dataAnalysis as  $value) {
						if($count!=0){
						array_push($no_of_attempts, $value);
						} $count++;
					}
				}
				$j++;
			}
			$grapData = array(
				//'complete' => $student_dataAnalysis,
				'questions'=>$qArray,
				'attainment'=>$attainmet_per,
				'attempt_per'=>$attempt_per,
				'no_of_attempts'=>$no_of_attempts,
				);

			echo json_encode($grapData);

			//$this->load->view('assessment_attainment/import_assessment_data/import_assessment_dataAnalysis_vw', $data);
		}
	}
        /**
         * @param : NA
         * @return: json 
         * @desc: update the student marks
         */
        
        public function update_student_marks() {
        if ($this->input->post() && $this->input->is_ajax_request()):
            $data = $this->input->post();
            $action = $data['action'];
            
            $student_name = (trim($data['student_name']) != '-') ? $data['student_name'] : '';
            $student_usn = (trim($data['student_usn']) != '-') ? $data['student_usn'] : '';
            $total_marks = (trim($data['total_marks']) != '-') ? $data['total_marks'] : '';
            unset($data['student_name'], $data['student_usn'], $data['total_marks'], $data['action']);

            $split_data = array_chunk($data, 2, TRUE);
            $db_data = array();
            
            foreach ($split_data as $val):

                $cols = array_keys($val);
                $mark_col = $cols[0];
                $mark = (trim($val[$mark_col]) != '-') ? $val[$mark_col] : '';
                $assessment_id = $val[$cols[1]];
                
                if ($assessment_id) {

                    $db_data[$mark_col] = array();
                    if ($action == 'edit') {
                        if ($student_name) {
                            $db_data[$mark_col]['student_name'] = $student_name;
                        }
                        if ($student_usn) {
                            $db_data[$mark_col]['student_usn'] = $student_usn;
                        }
                        if ($total_marks) {
                            $db_data[$mark_col]['total_marks'] = $total_marks;
                        }
                        if ($mark != '_') {
                            $db_data[$mark_col]['secured_marks'] = (int) $mark;
                        }
                    }
                    $db_data[$mark_col]['assessment_id'] = $assessment_id;
                }
            endforeach;

            if ($action == 'edit') {                
                echo $this->import_assessment_data_model->update_student_mark($db_data);
            } else if ($action == 'delete') {                
                echo $this->import_assessment_data_model->delete_student_mark($db_data);                
            }
        endif;
        return 0;
    }
    
    /*
     * Function to check the student is uploaded for the respective curriculum or not
     * @param: crclm_id
     * @return:
     */
    public function check_student_uploaded_or_not(){
       $crclm_id = $this->input->post('crclm_id');
       $check_students = $this->import_assessment_data_model->check_student_uploaded_or_not($crclm_id);
       $data['student_count'] = $check_students['student_count'];
       echo json_encode($data);
       
    }
    
    /*
 * Function to get the Rubrics table view
 * @param: ao_method_id, ao_id
 * @return:
 */
public function get_rubrics_table_modal_view(){
    $ao_method_id = $this->input->post('ao_method_id');
    $qpd_id = $this->input->post('qpd_id');
    $rubrics_table = $this->pdf_report_rubrics_list_table($ao_method_id,$qpd_id);
    if($rubrics_table !='nodata'){
        echo $rubrics_table;
    }else{
        echo 'false';
    }
    
}

/*
 * Function to generate the Rubrics table for pdf roprt
 * @param: ao_method_id
 * @return: 
 */

public function pdf_report_rubrics_list_table($ao_method_id,$qpd_id){
    $get_rubrics_details = $this->import_assessment_data_model->get_saved_rubrics($ao_method_id);
   // $check_qp_created = $this->cia_rubrics_model->check_question_paper_created($ao_id); // Check Question question paper is created or not
    $meta_data = $this->import_assessment_data_model->pdf_report_meta_data($qpd_id); // Check Question question paper is created or not

    $criteria_clo = $get_rubrics_details['criteria_clo'];
    $criteria_desc = $get_rubrics_details['criteria_desc'];
    $criteria_range = $get_rubrics_details['rubrics_range'];

    if(!empty($criteria_clo)){
        $data['table'] = '<table id="rubrics_meta_data_display" class="rubrics_meta_data_table" >';
    $data['table'] .= '<tr>';
    $data['table'] .= '<td><b>Curriculum:</b></td>';
    $data['table'] .= '<td style="padding-right: 70px;"><b><font color="blue">'.$meta_data['crclm_name'].'</font></b></td>';
    $data['table'] .= '<td><b>Term:</b></td>';
    $data['table'] .= '<td style="padding-right: 70px;"><b><font color="blue">'.$meta_data['term_name'].'</font></b></td>';
    $data['table'] .= '<td><b>Course:</b></td>';
    $data['table'] .= '<td><b><font color="blue">'.$meta_data['crs_title'].'</font></b></td>';
    $data['table'] .= '</tr>';
//    $data['table'] .= '<tr>';
//    $data['table'] .= '<td><b>Section:</b></td>';
//    $data['table'] .= '<td><b><font color="blue">'.$meta_data['mt_details_name'].'</font></b></td>';
//    $data['table'] .= '<td><b>Assessment Occasion:</b></td>';
//    $data['table'] .= '<td><b><font color="blue">'.$meta_data['ao_description'].'</font></b></td>';
//    $data['table'] .= '</tr>';
    $data['table'] .= '</tr>';
    $data['table'] .= '</table>';
    $data['table'] .= '</br>';
    $data['table'] .= '<b>Rubrics List :-</b>';
    $data['table'] .= '<hr>';
    $data['table'] .= '<table id="rubrics_list_display" class="table table-bordered table-hover dataTable" >';
    $data['table'] .= '<thead>';
    $data['table'] .= '<tr>';
    $data['table'] .= '<th rowspan="2">Sl No.</th>';
    $data['table'] .= '<th rowspan="2">Criteria</th>';
    $data['table'] .= '<th rowspan="2">CO Code</th>';
    $data['table'] .= '<th colspan="'.count($criteria_range).'"><center>Scale of Assessment</center></th>';
    $data['table'] .= '</tr>';
    $data['table'] .= '<tr>';
    foreach($criteria_range as $range){
        if(@$range['criteria_range_name']){
            $data['table'] .= '<th>'.$range['criteria_range_name'].' - '.$range['criteria_range'].'</th>';
        }else{
            $data['table'] .= '<th>'.$range['criteria_range'].'</th>';
        }
    
    }

    $data['table'] .= '</tr>';
    $data['table'] .= '</thead>';
    $data['table'] .= '<tbody>';
    $no = 1;
    foreach($criteria_clo as $criteria){
    $data['table'] .= '<tr>';
    $data['table'] .= '<td>'.$no.'</td>';
    $data['table'] .= '<td>'.$criteria['criteria'].'</td>';
    $data['table'] .= '<td>'.$criteria['co_code'].'</td>';
    foreach($criteria_desc as $desc){
        if($desc['rubrics_criteria_id'] == $criteria['rubrics_criteria_id']){
            $data['table'] .= '<td>'.$desc['criteria_description'].'</td>';
        }

    }
    $data['table'] .= '</tr>';
    $no++;
    }
    $data['table'] .= '</tbody>';
    $data['table'] .= '</table>';
    return $data['table'];
    }else{
        $data = 'nodata';
        return $data;
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
                
            $get_organisation_type = $this->import_assessment_data_model->get_organisation_type($crclm_id,$term_id,$crs_id); 
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


}

/*
 * End of file import_assessment_data.php
 * Location: .assessment_attainment/import_assessment_data.php 
 */
?>