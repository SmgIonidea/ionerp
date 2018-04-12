<?php
/*
* Description	:	Data Series List View
* Created		:	22-12-2014. 
* Author 		:   Jyoti V. Shetti
* Modification History:
* Date				Modified By				Description
 15-10-2015			Shivaraj B              Added Export to pdf option and Ajax loading 
-------------------------------------------------------------------------------------------------
*/
?>

<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Data_series extends CI_Controller {
	
	function __construct() {
        // Call the Model constructor
        parent::__construct();
		$this->load->model('assessment_attainment/data_series/data_series_model');
		$this->load->model('configuration/organisation/organisation_model');
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
			$dept_list = $this->data_series_model->dept_fill();
            $data['dept_data'] = $dept_list['dept_result'];
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
			$pgm_data = $this->data_series_model->pgm_fill($dept_id);
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
			$curriculum_data = $this->data_series_model->crclm_fill($pgm_id);
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
			$term_data = $this->data_series_model->term_fill($crclm_id);
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
			$term_data = $this->data_series_model->course_fill($term);
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
			$section_id = $this->input->post('section_id');
			$occasion_data = $this->data_series_model->occasion_fill($crclm_id,$term_id,$crs_id ,$section_id);
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
    public function select_occasion_student() {
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
			$occasion_data = $this->data_series_model->occasion_fill_student($crclm_id,$term_id,$crs_id ,$section_id);
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
			$occasion_data = $this->data_series_model->occasion_fill_mte($crclm_id,$term_id,$crs_id ,$section_id);
			
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
    public function select_section() {
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
			$occasion_data = $this->data_series_model->section_fill($crclm_id,$term_id,$crs_id);
			$i = 0;
			$list = array();
			if($occasion_data){
				$list[$i] = '<option value=""> Select Section</option>';
				$i++;
				foreach ($occasion_data as $data) {
					$list[$i] = "<option value=" . $data['section_id'] . ">" . $data['mt_details_name'] . "</option>";					
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
			$TEE_data = $this->data_series_model->getTEEQPId($crclm_id,$term_id,$crs_id);
			if($TEE_data) {
				echo $TEE_data[0]['qpd_id'];
			} else {
				echo 0;
			}
		}
	}

	public function export_to_pdf() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {		
		if($_POST['pdf_doc'] == 'doc'){
			$this->load->helper('to_word_helper');
    
			$dept_name = "Department of ";
			$dept_name.= $this->data_series_model->dept_name_by_crclm_id($_POST['curriculum']);
			$dept_name = strtoupper($dept_name);
			
			$param['crclm_id'] = $this->input->post('curriculum');
			$param['term_id'] = $this->input->post('term');
			$param['crs_id'] = $this->input->post('course');
						
			$main_head  = ''; $image ='';
			if(!empty($_POST['main_head'])){
				$main_head = $_POST['main_head'];
			}
			$export_content = $_POST['export_data_to_doc'];
			if(!empty($_POST['export_graph_data_to_doc'])){
			 $export_graph_content = $_POST['export_graph_data_to_doc'];
            list($type, $graph) = explode(';', $export_graph_content);
            list(, $graph) = explode(',', $graph);
            $graph = base64_decode($graph);

            $graph_image = file_put_contents('uploads/course_co_outcome_attainment.png', $graph);
            $image_location = 'uploads/course_co_outcome_attainment.png';
			
			$image = "<img src='".$image_location."' width='680' height='330' /><br>"; 
			}
			$word_content = "<p>". $main_head."</p>". "<p>" . $image . "</p>" ."<p>" .  $export_content  ."</p>";
		
			$data['word_content'] = $word_content;
			$data['dept_name'] = $dept_name;
			$filename =  $_POST['file_name'];

			html_to_word($word_content , $dept_name ,$filename, 'L');
			
		}else{
        	ini_set('max_execution_time', 3000);
        	ini_set('memory_limit','-1');
			$data_series_analysis_rep_hidden = $this->input->post('data_series_analysis_rep_hidden');
			$this->load->helper('pdf');
			pdf_create($data_series_analysis_rep_hidden,'data_series','L');
			return;
			}
		}
	}
	
	public function fetch_type_data(){
			$crclm_id = $this->input->post('crclm_id');
            $term_id = $this->input->post('term_id');
            $course_id = $this->input->post('course_id');
	        $type_data = $this->data_series_model->fetch_type_data( $crclm_id, $term_id, $course_id);
		
		
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

	
	
}//End of class

/*
 * End of file data_series.php
 * Location: .assessment_attainment/data_series.php 
 */
?>