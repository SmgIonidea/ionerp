<?php
/* -----------------------------------------------------------------------------------------------------
 * Description	: Controller Logic for Student Attainment.
 * Modification History:
 * Date							 Author							Description
 * 25-02-2015					Jevi V G     	     			
 * -----------------------------------------------------------------------------------------------------
 */
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Student_attainment extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('assessment_attainment/student_attainment/student_attainment_model');
		$this->load->model('assessment_attainment/data_series/student_data_series_model');
		$this->load->model('configuration/organisation/organisation_model');
	}

    public function index() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			$data['deptlist'] = $this->student_attainment_model->dropdown_dept_title();
			$data['crclm_data'] = $this->student_attainment_model->crlcm_drop_down_fill();
			$data['title'] = "Student COs Attainment";
			$this->load->view('assessment_attainment/student_attainment/student_attainment_vw', $data);
		}
    }
	
	 /*
        * Function is to fetch curricula list for curricula drop down box.
        * @param - ------.
        * returns the list of curricula.
	*/
    public function select_pgm() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			$dept_id = $this->input->post('dept_id');
			$pgm_data = $this->student_attainment_model->dropdown_program_title($dept_id);
			
			$i = 0;
			$list[$i] = '<option value="">Select Program</option>';
			$i++;
			
			foreach ($pgm_data as $data) {
				$list[$i] = "<option value=" . $data['pgm_id'] . ">" . $data['pgm_acronym'] . "</option>";
				$i++;
			}
			$list = implode(" ", $list);
			echo $list;
		}
    }
	
	   /*
        * Function is to fetch curricula list for curricula drop down box.
        * @param - ------.
        * returns the list of curricula.
	*/
    public function select_crclm() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			$pgm_id = $this->input->post('pgm_id');
			$crclm_data = $this->student_attainment_model->crlcm_drop_down_fill($pgm_id);
			
			$i = 0;
			$list[$i] = '<option value="">Select Curriculum</option>';
			$i++;
			
			foreach ($crclm_data as $data) {
				$list[$i] = "<option value=" . $data['crclm_id'] . ">" . $data['crclm_name'] . "</option>";
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
    public function select_term() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			$crclm_id = $this->input->post('crclm_id');
			$term_data = $this->student_attainment_model->term_drop_down_fill($crclm_id);
			
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
			$term_data = $this->student_attainment_model->course_drop_down_fill($term);
			
				
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
	
		

	
	/**/
	public function CourseCOAttainment() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			$crs = $this->input->post('course');
			$type = $this->input->post('type');
			$student_usn = $this->input->post('student_usn');
			if($type == 5) {
				$qpd_type = 'TEE';
				$qp_rolled_out = $this->student_attainment_model->qp_rolled_out($crs);
				if($qp_rolled_out) {
					$graph_data = $this->student_attainment_model->getCourseCOAttainment($crs, $qp_rolled_out[0]['qpd_id'],$student_usn,$qpd_type);
					if($graph_data) {
						echo json_encode($graph_data);
					} 
				}
				else {
					echo 0;
				}
			} else if($type == 3){
				$qpd_type = 'CIA';
				$qpd_id = $this->input->post('qpd_id');
				if ($qpd_id != 'all_occasion') {
					$graph_data = $this->student_attainment_model->getCourseCOAttainment($crs, $qpd_id,$student_usn,$qpd_type);					
				} else { 
					$qpd_id = 'NULL';
					$graph_data = $this->student_attainment_model->getCourseCOAttainment($crs, $qpd_id,$student_usn,$qpd_type);
				}
				if($graph_data) {
					echo json_encode($graph_data);
				} else {
					echo 0;
				}
			}else if($type == 6){
				$qpd_type = 'MTE';
				$qpd_id = $this->input->post('qpd_id');
				if ($qpd_id != 'all_occasion') {
					$graph_data = $this->student_attainment_model->getCourseCOAttainment($crs, $qpd_id,$student_usn,$qpd_type);					
				} else { 
					$qpd_id = 'NULL';
					$graph_data = $this->student_attainment_model->getCourseCOAttainment($crs, $qpd_id,$student_usn,$qpd_type);
				}
				if($graph_data) {
					echo json_encode($graph_data);
				} else {
					echo 0;
				}
			} else {
				$qpd_type = 'BOTH';
				$qpd_id = 'NULL';
				$graph_data = $this->student_attainment_model->getCourseCOAttainment($crs, $qpd_id,$student_usn,$qpd_type);
				if($graph_data) {
					echo json_encode($graph_data);
				} else {
					echo 0;
				}
			}
		}
	}
	
	/**/
	public function CourseCOAttainment_Contribution() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			$crs = $this->input->post('course');
			$type = $this->input->post('type');
			$student_usn = $this->input->post('student_usn');
			if($type == 1) {
				$qpd_type = 'TEE';
				$qp_rolled_out = $this->student_attainment_model->qp_rolled_out($crs);
				if($qp_rolled_out) {
					$graph_data = $this->student_attainment_model->CourseCOAttainment_Contribution($crs, $qp_rolled_out[0]['qpd_id'],$student_usn,$qpd_type);
					if($graph_data) {
						echo json_encode($graph_data);
					} 
				}
				else {
					echo 0;
				}
			} else if($type == 2){
				$qpd_type = 'CIA';
				$qpd_id = $this->input->post('qpd_id');
				if ($qpd_id != 'all_occasion') {
					$graph_data = $this->student_attainment_model->CourseCOAttainment_Contribution($crs, $qpd_id,$student_usn,$qpd_type);
				} else { 
					$qpd_id = 'NULL';
					$graph_data = $this->student_attainment_model->CourseCOAttainment_Contribution($crs, $qpd_id,$student_usn,$qpd_type);
				}
				if($graph_data) {
					echo json_encode($graph_data);
				} else {
					echo 0;
				}
			} else {
				$qpd_type = 'BOTH';
				$qpd_id = 'NULL';
				$graph_data = $this->student_attainment_model->CourseCOAttainment_Contribution($crs, $qpd_id,$student_usn,$qpd_type);
				if($graph_data) {
					echo json_encode($graph_data);
				} else {
					echo 0;
				}
			}
		}
	}
	
	/**/
	public function CourseBloomsLevelAttainment(){
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			$crs = $this->input->post('course');
			$type = $this->input->post('type');
			$student_usn = $this->input->post('student_usn');
			
			if($type == 5) {
				$qpd_type = 'TEE';
				$qp_rolled_out = $this->student_attainment_model->qp_rolled_out($crs);
			
				if($qp_rolled_out) {
					$graph_data = $this->student_attainment_model->CourseBloomsLevelAttainment($crs, $qp_rolled_out[0]['qpd_id'],$student_usn,$qpd_type);
					
					if($graph_data) {
						echo json_encode($graph_data);
					} 
				}else {
					echo 0;
				}
			} else if($type == 3){
				$qpd_type = 'CIA';
				$qpd_id = $this->input->post('qpd_id');
				$student_usn = $this->input->post('student_usn');
				if ($qpd_id != 'all_occasion') {
					$graph_data = $this->student_attainment_model->CourseBloomsLevelAttainment($crs, $qpd_id, $student_usn,$qpd_type);
				} else {
					$qpd_id = 'NULL';
					$graph_data = $this->student_attainment_model->CourseBloomsLevelAttainment($crs, $qpd_id, $student_usn,$qpd_type);
				}
				if($graph_data) {
					echo json_encode($graph_data);
				} else {
					echo 0;
				}
			} else if($type == 6){
				$qpd_type = 'MTE';
				$qpd_id = $this->input->post('qpd_id');
				$student_usn = $this->input->post('student_usn');
				if ($qpd_id != 'all_occasion') {
					$graph_data = $this->student_attainment_model->CourseBloomsLevelAttainment($crs, $qpd_id, $student_usn,$qpd_type);
				} else {
					$qpd_id = 'NULL';
					$graph_data = $this->student_attainment_model->CourseBloomsLevelAttainment($crs, $qpd_id, $student_usn,$qpd_type);
				}
				if($graph_data) {
					echo json_encode($graph_data);
				} else {
					echo 0;
				}
			} else {
				$qpd_type = 'BOTH';
				$qpd_id = 'NULL';
				$graph_data = $this->student_attainment_model->CourseBloomsLevelAttainment($crs, $qpd_id,$student_usn,$qpd_type);
				if($graph_data) {
					echo json_encode($graph_data);
				} else {
					echo 0;
				}
			}
		}
	}
	
	
	/**/
	public function CourseBloomsLevelCumulativeData(){
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			$crs = $this->input->post('course');
			$type = $this->input->post('type');
			$student_usn = $this->input->post('student_usn');
			if($type == 1) {
				$qpd_type = 'TEE';
				$qp_rolled_out = $this->student_attainment_model->qp_rolled_out($crs);
				if($qp_rolled_out) {
					$graph_data = $this->student_attainment_model->CourseBloomsLevelCumulativeData($crs, $qp_rolled_out[0]['qpd_id'],$student_usn,$qpd_type);
					
					if($graph_data) {
						echo json_encode($graph_data);
					} 
				}else {
					echo 0;
				}
			} else if($type == 2){
				$qpd_type = 'CIA';
				$qpd_id = $this->input->post('qpd_id');
				$student_usn = $this->input->post('student_usn');
				if ($qpd_id != 'all_occasion') {
					$graph_data = $this->student_attainment_model->CourseBloomsLevelCumulativeData($crs, $qpd_id, $student_usn,$qpd_type);
				} else {
					$qpd_id = 'NULL';
					$graph_data = $this->student_attainment_model->CourseBloomsLevelCumulativeData($crs, $qpd_id, $student_usn,$qpd_type);
				}
				if($graph_data) {
					echo json_encode($graph_data);
				} else {
					echo 0;
				}
			} else {
				$qpd_type = 'BOTH';
				$qpd_id = 'NULL';
				$graph_data = $this->student_attainment_model->CourseBloomsLevelCumulativeData($crs, $qpd_id,$student_usn,$qpd_type);
				if($graph_data) {
					echo json_encode($graph_data);
				} else {
					echo 0;
				}
			}
		}
	}

	public function BloomsLevelMarksDistribution() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			$crs = $this->input->post('course');
			$student_usn = $this->input->post('student_usn');
			$qp_rolled_out = $this->student_attainment_model->qp_rolled_out($crs);
			$graph_data = $this->student_attainment_model->BloomsLevelMarksDistribution($crs, $qp_rolled_out[0]['qpd_id'],$student_usn);
			
			if($graph_data) {
				echo json_encode($graph_data);
			} else {
				echo 0;
			}
		}
	}
	
	/**/
	public function BloomsLevelPlannedCoverageDistribution() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			$crs = $this->input->post('course');
			$student_usn = $this->input->post('student_usn');
			$qp_rolled_out = $this->student_attainment_model->qp_rolled_out($crs);
			$graph_data = $this->student_attainment_model->BloomsLevelPlannedCoverageDistribution($crs, $qp_rolled_out[0]['qpd_id'],$student_usn);
			
			if($graph_data) {
				echo json_encode($graph_data);
			} else {
				echo 0;
			}
		}
	}
	
	/**/
	public function CoursePOCOAttainment() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			$crs = $this->input->post('course');
			$qpd_id = $this->input->post('qpd_id');
			$qpd_type = $this->input->post('type');
			$student_usn = $this->input->post('student_usn');
			$po_list = $this->student_attainment_model->course_po_list($crs);
			if ($qpd_type == 2) {
				if ($qpd_id != 'all_occasion') {
					$qpd_type = 'CIA';
					$graph_data = $this->student_attainment_model->CoursePOCOAttainment($po_list, $crs,$qpd_id,$student_usn,$qpd_type);
				} else {
					$qpd_id = 'NULL';
					$qpd_type = 'CIA';
					$graph_data = $this->student_attainment_model->CoursePOCOAttainment($po_list, $crs,$qpd_id,$student_usn,$qpd_type);
				}
			} else if ($qpd_type == 1) {
				$qpd_type = 'TEE';
				$graph_data = $this->student_attainment_model->CoursePOCOAttainment($po_list, $crs,$qpd_id,$student_usn,$qpd_type);
			} else {
				$qpd_type = 'BOTH';
				$qpd_id = 'NULL';
				$graph_data = $this->student_attainment_model->CoursePOCOAttainment($po_list, $crs,$qpd_id,$student_usn,$qpd_type);
			}
			if($graph_data) {
				echo json_encode($graph_data);
			} else {
				echo 0;
			}
		}
	}
	
	/*
        * Function is to fetch student usn list for TEE drop down box.
        * @param - ------.
        * returns the list of student usn.
	*/
    public function select_usn() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			
			$course_id = $this->input->post('course');
			$type = $this->input->post('type');
			$student_details = $this->student_attainment_model->usn_fill($course_id,$type);
			$student_data = $student_details['student_usn'];
			$qpd_id = $student_details['qpd_id'];
			$i = 0;
			$list = array();
			if($student_data){
				$list[$i] = '<option value="">Select ID</option>';
				$i++;
				foreach ($student_data as $data) {
					$list[$i] = "<option title='".$data['student_name']."' value=" . $data['student_usn'] . "> ".$data['student_usn']."</option>";
					$i++;
				}				
				$list = implode(" ", $list);
			}
			if($list) {
				$data['usn'] = $list;
				$data['qpd_id'] = $qpd_id;
				echo json_encode($data);
			}else {
				echo 0;
			}
		}
    }
	
	public function StudentAttainmentAnalysis(){
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {			
			$crs_id = $this->input->post('course');
			$qp_rolled_out = $this->student_attainment_model->qp_rolled_out($crs_id);
			$qpd_id = $qp_rolled_out[0]['qpd_id'];
			$student_usn = $this->input->post('student_usn');
			$analysis_data['student_data'] = $this->student_attainment_model->StudentAttainmentAnalysis($qpd_id, $student_usn);
			
			$this->load->view('assessment_attainment/student_attainment/student_attainment_analysis_vw', $analysis_data);
		}
	}
	
		/*
        * Function is to fetch occasions list for occasion drop down box.
        * @param - ------.
        * returns the list of occasions.
	*/
    public function cia_select_usn() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			
			$qpd_id = $this->input->post('qpd_id');
			$crs_id = $this->input->post('course');
			$occasion_data = $this->student_attainment_model->cia_usn_fill($qpd_id,$crs_id);
			$i = 0;
			$list = array();
			if($occasion_data){
				$list[$i] = '<option value="">Select ID</option>';
				$i++;
				foreach ($occasion_data as $data) {
					$list[$i] = "<option title='".$data['student_name']."' value=" . $data['student_usn'] . "> ".$data['student_usn']."</option>";
					$i++;
				}				
				$list = implode(" ", $list);
			}
			if($list) {
				echo $list;
			}else {
				echo 0;
			}
		}
    }
	/*Function - To create PDF of the displayed graph
	*
	*
	*/
	public function export_to_pdf() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			
			$course_outcome_attainment_graph_data = $this->input->post('course_outcome_attainment_graph_data_hidden');
			// $co_contribution_graph_data = $this->input->post('co_contribution_graph_data_hidden');
			$bloom_level_distribution_data = $this->input->post('bloom_level_distribution_hidden');
			$bloom_level_cumm_perf_graph_hidden = $this->input->post('bloom_level_cumm_perf_graph_hidden');
			$student_attainment_analysis_data_hidden = $this->input->post('student_attainment_analysis_data_hidden');

			$dept_name = "Department of ";
			$dept_name.= $this->student_attainment_model->dept_name_by_crclm_id($_POST['crclm_name']);
			$dept_name = strtoupper($dept_name);
	
			$image1 = $_POST['image1'];
            list($type, $graph1) = explode(';', $image1);
            list(, $graph1) = explode(',', $graph1);
            $graph1 = base64_decode($graph1);

            $graph_image = file_put_contents('uploads/student_course_outcome.png', $graph1);
            $image_location1 = 'uploads/student_course_outcome.png';
			
			$image1 = "<img src='".$image_location1."' width='680' height='330' /><br>"; 	

			$image2 = $_POST['image2'];
            list($type, $graph2) = explode(';', $image2);
            list(, $graph2) = explode(',', $graph2);
            $graph2 = base64_decode($graph2);

            $graph_image = file_put_contents('uploads/student_bloom_level.png', $graph2);
            $image_location2 = 'uploads/student_bloom_level.png';
			
			$image2 = "<img src='".$image_location2."' width='680' height='330' /><br>"; 
			
			
			$image3 = $_POST['image3'];
            list($type, $graph3) = explode(';', $image3);
            list(, $graph3) = explode(',', $graph3);
            $graph3 = base64_decode($graph3);

            $graph_image = file_put_contents('uploads/student_cumulative.png', $graph3);
            $image_location3 = 'uploads/student_cumulative.png';
			
			$image3 = "<img src='".$image_location3."' width='680' height='330' /><br>"; 
			$main_head = $_POST['main_head'];
			$content = "<p>". $main_head ."</p><p>". $image1 ."</p><p>".$course_outcome_attainment_graph_data."</p><p>". $image2."</p><p style='page-break-before: always;'>".$bloom_level_distribution_data."</p><p>". $image3 ."</p><p style='page-break-before: always;'>".$bloom_level_cumm_perf_graph_hidden."</p><p style='page-break-before: always;'>".$student_attainment_analysis_data_hidden."</p>";
			$filename = $_POST['file_name'];
			
			
			
			if($_POST['pdf_or_doc'] == 'doc'){
			$this->load->helper('to_word_helper');
			html_to_word($content, $dept_name , $filename, 'L');
			}else{
			$this->load->helper('pdf');
			pdf_create($content,'student_attainment','P');
			}
			return;
		}
		
    }//End of function
	public function fetch_type_data(){
			$crclm_id = $this->input->post('crclm_id');
            $term_id = $this->input->post('term_id');
            $course_id = $this->input->post('course_id');		
			$type_data = $this->student_attainment_model->fetch_type_data( $crclm_id, $term_id, $course_id);
			
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

}

/* End of file form.php */
/* Location: ./application/controllers/form.php */
?>
