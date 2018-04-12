<?php
/**
* Description	:	Data Series List View
* Created		:	25-2-2015. 
* Author 		:  Jevi V. G.
* Modification History:
* Date				Modified By				Description
-------------------------------------------------------------------------------------------------
*/
?>

<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Student_data_series extends CI_Controller {
	
	function __construct() {
        // Call the Model constructor
        parent::__construct();
		$this->load->model('assessment_attainment/data_series/student_data_series_model');
    }

	/**
	 * 
	 * 
	 */
    public function index() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			$dept_list = $this->student_data_series_model->dept_fill();
            $data['dept_data'] = $dept_list['dept_result'];
			
			$data['title'] = "Data Analysis Report";
            $this->load->view('assessment_attainment/data_series/data_series_vw',$data);
        }
    }
	
	/* Function is used to fetch program names from program table.
	* @param- 
	* @returns - an object.
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
			$pgm_data = $this->student_data_series_model->pgm_fill($dept_id);
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
	
	/* Function is used to fetch curriculum names from curriculum table.
	* @param- 
	* @returns - an object.
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
			$curriculum_data = $this->student_data_series_model->crclm_fill($pgm_id);
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
	
	/* Function is used to fetch term names from crclm_terms table.
	* @param- 
	* @returns - an object.
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
			$term_data = $this->student_data_series_model->term_fill($crclm_id);
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
			$term_data = $this->student_data_series_model->course_fill($term);
			$i = 0;
			$list[$i] = '<option value="">Select Course</option>';
			$i++;
			foreach ($term_data as $data) {
				$list[$i] = "<option value=" . $data['crs_id'] . ">" . $data['crs_title'] . "</option>";
				$i++;
			}				
			$list = implode(" ", $list);
			echo $list;
		}
    }
	
	/*
        * Function is to fetch occasions list for occasion drop down box.
        * @param - ------.
        * returns the list of occasions.
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
			$occasion_data = $this->student_data_series_model->occasion_fill($crclm_id,$term_id,$crs_id);
			$i = 0;
			$list = array();
			if($occasion_data){
				$list[$i] = '<option value="">Select Occasion</option>';
				$i++;
				foreach ($occasion_data as $data) {
					$list[$i] = "<option value=" . $data['qpd_id'] . ">" . $data['ao_description'] . "</option>";
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
	
	/*
        * Function is to fetch occasions list for occasion drop down box.
        * @param - ------.
        * returns the list of occasions.
	*/
    public function select_usn() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			
			$qpd_id = $this->input->post('qpd_id');
			$occasion_data = $this->student_data_series_model->usn_fill($qpd_id);
			$i = 0;
			$list = array();
			if($occasion_data){
				$list[$i] = '<option value="">Select USN</option>';
				$i++;
				foreach ($occasion_data as $data) {
					$list[$i] = "<option value=" . $data['student_usn'] . "> ".$data['student_usn']."</option>";
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
	/**
	*	Function is to fetch the qp_id of TEE that is rolled out
	*	@param - -----.
	*	returns qp_id 
	*/
	public function getTEEQPId() {
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
			$TEE_data = $this->student_data_series_model->getTEEQPId($crclm_id,$term_id,$crs_id);
			if($TEE_data) {
				echo $TEE_data[0]['qpd_id'];
			} else {
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
			
				$qpd_id = $this->input->post('qpd_id');
				$student_usn = $this->input->post('student_usn');
				//var_dump($student_usn);exit;
				$analysis_data['student_data'] = $this->student_data_series_model->StudentAttainmentAnalysis($qpd_id, $student_usn);
				/* if($analysis_data) {
					echo json_encode($analysis_data);
				} else {
					echo 0;
				} */
			$this->load->view('assessment_attainment/student_attainment/student_attainment_analysis_vw', $analysis_data);
		}
	
	
	
	
	
	}
	
	
}

/*
 * End of file student_data_series.php
 * Location: .assessment_attainment/student_data_series.php 
 */
?>