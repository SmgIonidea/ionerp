<?php
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description				: Student Threshold 
 * Modification History		:
 * Date				Author				Description
 * 04-08-2015	 Arihant Prasad		Set student threshold, list student USN
									display improvement plan table, facility to
									insert improvement plan details, view improvement plan
  ---------------------------------------------------------------------------------------------------------------------------------
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Student_threshold extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('assessment_attainment/student_threshold/student_threshold_model');
		$this->load->model('assessment_attainment/data_series/student_data_series_model');
	}

  
    public function index() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			$data['crclm_data'] = $this->student_threshold_model->crlcm_drop_down_fill();
			$data['title'] = "Threshold Reports";
			$this->load->view('assessment_attainment/student_threshold/student_threshold_vw', $data);
		}
    }
	
	/*
        * Function is to fetch term list for term drop down box.
        * @param - ------.
        * returns the list of terms.
	*/
    public function select_term() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			$crclm_id = $this->input->post('crclm_id');
			$term_data = $this->student_threshold_model->term_drop_down_fill($crclm_id);
			
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
     * Function is to fetch term list for term drop down box.
     * @param - ------.
     * returns the list of terms.
	*/
    public function select_course() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			$term = $this->input->post('term_id');
			$term_data = $this->student_threshold_model->course_drop_down_fill($term);
			
				
			if($term_data) {
				$i = 0;
			$list[$i] = '<option value="">Select Course</option>';
			$i++;
				foreach ($term_data as $data) {
					$list[$i] = "<option value=" . $data['crs_id'] . ">" . $data['crs_title'] . "</option>";
					$i++;
				}
				
			} else {
				$i = 0;
				$list[$i++] = '<option value="">Select Course</option>';
				$list[$i] = '<option value="">No Assessment Data Imported to Courses</option>';
			}
			$list = implode(" ", $list);
			echo $list;
		}
    }

	/*
	 * Function is to fetch occasions list for occasion drop down box.
	 * @param :
	 * returns : list of occasions.
	*/
    public function select_occasion() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			$crclm_id = $this->input->post('crclm_id');
			$term_id = $this->input->post('term_id');
			$crs_id = $this->input->post('crs_id');
			
			$occasion_data = $this->student_threshold_model->occasion_fill($crclm_id, $term_id, $crs_id);
			
			$i = 0;
			$list = array();
			if($occasion_data) {
				$list[$i] = '<option value="">Select Occasion</option>';
				$i++;
				foreach ($occasion_data as $data) {
					$list[$i] = "<option value=" . $data['qpd_id'] . ">" . $data['ao_description'] . "</option>";
					$i++;
				}
				$list[$i++] = '<option value="all_occasion">All Occasion</option>';
				$list = implode(" ", $list);
			}
			
			if($list) {
				echo $list;
			} else {
				echo 0;
			}
		}
    }
	
	/*
	 * Function is to fetch CO list for course drop down box.
	 * @param :
	 * returns the list of COs.
	*/
    public function fetch_course_clo() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			$course_id = $this->input->post('crs_id');
			$type_data_id = $this->input->post('type_data_id');
			$qpd_id = $this->input->post('qpd_id');
			$clo_data = $this->student_threshold_model->fetch_course_clo($course_id, $qpd_id, $type_data_id);
		
			$i = 1;
			$list = '';
		
			if(!empty($clo_data)) {
				$list .= '<table class="table table-bordered import_crs_table">';
				$list .= '<tbody>';
				$list .= '<tr>';
				$list .= '<td>';
				$list .= '<input type="checkbox" name="check_all" id="check_all" style="margin: 0px;" class="clo_check_all" value=""/> - ';
				$list .= 'Select All COs';
				$list .= '</td>';
				$list .= '</tr>';
				$list .= '<tr>';
				$list .= '</tbody>';
				$list .= '</table>';
				$list .= '<table class="table table-bordered">';
				$list .= '<tr>';
				
				foreach ($clo_data as $data) {
					$list .= '<td>';
					$list .= '<input type="checkbox" name="course" id="course" style="margin: 0px;" class="clo_check cursor_pointer" title="'.$data['clo_statement'].'" value="'.$data['clo_id'].'" /> - ';
					$list .= $data['clo_code'];
					$list .= '  ';
					$list .= '</td>';
				}
				
				$list .= '</tr>';
				$list .= '</tbody>';
				$list .= '</table>';
				
				$data = array();
				$data['list_value'] = $list;
				$data['clo_thold'] = $clo_data[0]['cia_course_minthreshhold'] . '%';
			} else {
				$list .= '<span><font color="maroon"><b>Assessment Planning or Marks upload process is incomplete.</b></font></span>';
				
				$data = array();
				$data['list_value'] = $list;
			}
		
			echo json_encode($data);
		}
	}
	
	/*
        * Function is to fetch TEE qpd_id.
        * @param :
        * returns : qpd_id.
	*/
    public function fetch_tee_qpd_id() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			$course_id = $this->input->post('course');
			$type = $this->input->post('type');
			$qp_details = $this->student_threshold_model->fetch_tee_qpd_id($course_id, $type);
			$qpd_id = $qp_details['qpd_id'];
			
			$i = 0;
			if($qpd_id != '') {
				$data = $qpd_id;
				echo $data;
			}else {
				echo 0;
			}
		}
    }
		
	/*
     * Function is to fetch list of students above or under certain threshold and display the improvement plan table
     * @parameter
     * returns the list of occasions.
	*/
	public function submit_threshold_details() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			$entity_id = $this->input->post('entity_id');
			$crclm_id = $this->input->post('crclm_id');
			$term_id = $this->input->post('term_id');
			$crs_id = $this->input->post('crs_id');
			$type_data_id = $this->input->post('type_data_id');
			$occasion = $this->input->post('occasion_id');
			$tee_qpd_id = $this->input->post('tee_qpd_id');
			$clo_thold = $this->input->post('clo_thold');
			$threshold_level_id = $this->input->post('threshold_level_id');
			$clo_ids = $this->input->post('clo_ids');
			
			//check whether user is selecting CIA, TEE or Both
			if($type_data_id == 5) {
			$mte_flag = "TEE";
				//TEE
				$qpd_id = $tee_qpd_id;
				$qpType_id = $this->lang->line('entity_tee');
			} elseif($type_data_id == 3) {
			$mte_flag = 0;
				if($occasion != 'all_occasion') {
					//either CIA Minor 1 or 2
					$qpd_id = $occasion;
					$qpType_id = $this->lang->line('entity_cie');
				} else {
					//all occasions (minor 1 + minor 2 + ...)
					$qpd_id = 'NULL';
					$qpType_id = $this->lang->line('entity_cie');
				}
			}elseif($type_data_id == 6) {
				$mte_flag = 1;
				if($occasion != 'all_occasion') {
					//either CIA Minor 1 or 2
					$qpd_id = $occasion;
					$qpType_id = $this->lang->line('entity_mte');
				} else {
					//all occasions (minor 1 + minor 2 + ...)
					$qpd_id = 'NULL';
					$qpType_id = $this->lang->line('entity_mte');
				}
			} else {
				//TEE and CIA
				$qpd_id = 'NULL';
				$qpType_id = 'BOTH';
				$mte_flag = "ALL";
			}
			
			//check threshold level - above(1) or below(0)
			if($threshold_level_id == 10 || $threshold_level_id == 12) {
				$threshold_bit = 1;
			} else {
				$threshold_bit = 0;
			}
			
			//fetch improvement plan under each qp type and its related occasion
			//do not call procedure before the below mentioned command, else it will give "command out of sync" error.
			$student_analysis_data['improvement_plan'] = $this->student_threshold_model->student_improvement_plan($entity_id, $crclm_id, $term_id, $crs_id , $mte_flag , $qpType_id , $qpd_id );
	
			//fetch students' above or below threshold
			$stud_analysis = $this->student_threshold_model->StudentResultAnalysis($crs_id, $qpd_id, $qpType_id, $clo_ids, $threshold_bit, $clo_thold);

			$student_analysis_data['student_details'] = $stud_analysis['student_details'];
			$student_analysis_data['entity_id'] = $entity_id;
			$student_analysis_data['crclm_id'] = $crclm_id;
			$student_analysis_data['term_id'] = $term_id;
			$student_analysis_data['crs_id'] = $crs_id;
			$student_analysis_data['type_id'] = $type_data_id;
			$student_analysis_data['occasion_id'] = $occasion;
			$student_analysis_data['qpd_id'] = $qpd_id;
			$student_analysis_data['count'] = $stud_analysis['count'];
			
			
			$this->load->view('assessment_attainment/student_threshold/student_threshold_data_vw', $student_analysis_data);
		}
	}	
	
	/*
     * Function is to fetch list of students above or under certain threshold and display the improvement plan table
     * @parameter
     * returns the list of occasions.
	*/
	public function submit_threshold_details1() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			$entity_id = $this->input->post('entity_id');
			$crclm_id = $this->input->post('crclm_id');
			$term_id = $this->input->post('term_id');
			$crs_id = $this->input->post('crs_id');

			//fetch improvement plan under each qp type and its related occasion
			//do not call procedure before the below mentioned command, else it will give "command out of sync" error.
			$student_analysis_data['improvement_plan'] = $this->student_threshold_model->student_improvement_plan($entity_id, $crclm_id, $term_id, $crs_id);
			
			//fetch students' above or below threshold			
			$student_analysis_data['entity_id'] = $entity_id;
			$student_analysis_data['crclm_id'] = $crclm_id;
			$student_analysis_data['term_id'] = $term_id;
			$student_analysis_data['crs_id'] = $crs_id;
			
			$this->load->view('assessment_attainment/student_threshold/student_threshold_data_course_vw', $student_analysis_data);
		}
	}
	
	/*
     * Function to delete improvement plan details
     * @parameter
     * returns the list of occasions.
	*/
	public function improvement_plan_delete() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			$imp_plan_sip_id = $this->input->post('imp_plan_sip_id');
			$delete_improvement_plan = $this->student_threshold_model->improvement_plan_delete($imp_plan_sip_id);
			
			return $delete_improvement_plan;
		}
	}
	
	/*
     * Function to view improvement plan details
     * @parameter :
     * returns : improvement details
	*/
	public function view_improvement_plan() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			$imp_plan_sip_id = $this->input->post('imp_plan_sip_id');
			$crclm_id = $this->input->post('crclm_id');
			$term_id = $this->input->post('term_id');
			$crs_id = $this->input->post('crs_id');
			$occasion_id = $this->input->post('occasion_id');
			
			$view_improvement_plan_data = $this->student_threshold_model->view_improvement_plan($imp_plan_sip_id);
			
			$student_usn_1 =  wordwrap($view_improvement_plan_data[0]['student_usn'],20,"<br>\n");
	
			$view_improvement_plan = array(
			   'problem_statement' =>  $view_improvement_plan_data[0]['problem_statement'],
			   'root_cause' =>  $view_improvement_plan_data[0]['root_cause'],
			   'corrective_action' =>  $view_improvement_plan_data[0]['corrective_action'],
			   'action_item' =>  $view_improvement_plan_data[0]['action_item'],
			   'student_usn' =>  $student_usn_1
			);
			
			echo json_encode($view_improvement_plan);
		}
	}

	/**
	* Function to  upload files
	* @parameters:
	* @return: 
	*/
	public function upload() {
		if($_POST) {
			$sip_id  =  $this->input->post('id');			
			//base_url() not working so ./uploads/... is used 
			$path = "./uploads/stud_improvement_plan/"; 
		
			$today = date('y-m-d');
			$day = date("dmY", strtotime($today));
			$time1 = date("His"); 			
			$datetime = $day.'_'.$time1;
			
			//create the folder if it's not already existing
			if(!is_dir($path)){  		 	
				$mask = umask(0);
				mkdir($path, 0777);
				umask($mask);
				
				echo "folder created";
			} 
			
			if(!empty($_FILES)) {
				$tmp_file_name = $_FILES['Filedata']['tmp_name'];
				$name = $_FILES['Filedata']['name']; 	
			}  
		
			if(!empty($_FILES)) {
				$tmp_file_name = $_FILES['Filedata']['tmp_name'];
				$name = $_FILES['Filedata']['name'];

				//check the string length and uploading the file to the selected curriculum
				$str = strlen($datetime.'dd_'.$name);  
				
				if(isset($_FILES['Filedata'])) {
					$maxsize    = 10485760;
				}
				
				if(($_FILES['Filedata']['size'] >= $maxsize)) {
					echo "file_size_exceed";
				} else { 
					if($str <= 255) { 
						echo "in file";
						$result = move_uploaded_file($tmp_file_name, "$path/$datetime".'dd_'."$name"); 					
						$logged_in_uid = $this->ion_auth->user()->row()->id;  
						$data = array( 'sip_id' => $sip_id,
									   'file_name' => $datetime.'dd_'.$name,
									   'description' => '',
									   'actual_date' => $today,
									   'created_by' => $logged_in_uid,
									   'created_date' => date('y-m-d'),
									   'modified_by' => $logged_in_uid,
									   'modified_date' =>date('y-m-d'),);
						$this->student_threshold_model->save_uploaded_files($data);
						echo ("res_guid");				
					} else {
						echo "file_name_size_exceeded";
					}
				}
			}
		} else {
			echo "file_size_exceed";
		}
	}

	/**
	* Function to fetch uploaded file details
	* @parameters: 
	* @return: 
	*/
	public function fetch_files(){
		$sip_id  =  $this->input->post('sip_id');
	
		$result = $this->student_threshold_model->fetch_records($sip_id);  
	
		$data['specializatin'] = '$specializatin';
		$data['result'] = $result;
		$data['type'] = 'res_guid';
	
		$output = $this->load->view('assessment_attainment/student_threshold/uploaded_file_vw', $data);
		echo $output;
		
	}
	
	/*
	 * Function  to delete research detail file
	 * @param:
	 * @return:
	 **/
	public function delete_file(){
		$uload_id = $this->input->post('uload_id');
		$re = $this->student_threshold_model->delete_file($uload_id);
		echo $re;
	}

	/*
	 * Function  to update description of research detail file
	 * @param:
	 * @return:
	 **/
	public function save_res_guid_desc() {
		$save_form_data = $this->input->post();	
		$result = $this->student_threshold_model->save_res_guid_desc($save_form_data);
		
		echo $result; 
	}
	
		public function fetch_type_data(){
			$crclm_id = $this->input->post('crclm_id');
            $term_id = $this->input->post('term_id');
            $course_id = $this->input->post('course_id');
	        $type_data = $this->student_threshold_model->fetch_type_data( $crclm_id, $term_id, $course_id);
				
			if ($type_data) {
				$i = 0;
				
				foreach ($type_data as $key=>$data) {
					$list[$i] = "<option value=" . $key . ">" . $data . "</option>";
					$i++;
				}			
			} else {
				$i = 0;
				$list[$i++] = '<option value="">Select Type</option>';			
			}
			$list = implode(" ", $list);
			$table_data['type_list'] = $list;
	 echo json_encode($table_data);
	}
	
	
		/*
        * Function is to fetch occasions list for occasion drop down box.
        * @param - ------.
        * returns the list of occasions.
	*/
    public function select_occasion_mte() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {		
			$crclm_id = $this->input->post('crclm_id');
			$term_id = $this->input->post('term_id');
			$crs_id = $this->input->post('crs_id');
			$section_id = $this->input->post('section_id');
			$occasion_data = $this->student_threshold_model->occasion_fill_mte($crclm_id,$term_id,$crs_id ,$section_id);
			$i = 0;
			$list = array();
			if($occasion_data){
				$list[$i] = '<option value="">Select Occasion</option>';
				$i++;
				foreach ($occasion_data as $data) {
					$list[$i] = "<option value=" . $data['qpd_id'] . ">" . $data['ao_description'] . "</option>";
					$i++;
				}			
				$list[$i++] = '<option value="all_occasion">All Occasion</option>';				
				$list = implode(" ", $list);
			}
			if($list) {
				echo $list;
			}else {
				echo 0;
			}
		}
    }
}

/* End of file form.php */
/* Location: ./application/controllers/form.php */
?>